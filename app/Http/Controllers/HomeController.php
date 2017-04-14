<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\EventAnswer;
use App\Models\User;
use App\Models\UserLocation;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
	private $RESET_HOUR = 3;

	/**
	 * Show the application dashboard.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{
		$data = [
			'date' => Carbon::now()->format("d F Y, h:i A"),
			//'event' => null,
			'event' => Event::first()->toArray(),
			'user' => null
		];
		if (!Auth::guest()) {
			$data['event'] = Auth::user()->event->toArray();
			$data['user'] = Auth::user()->toArray();
			$data['reports'] = $this->getReportCount($data['event'], Auth::id());
			$data['location'] = $this->getLocation();
			$data['kpis'] = $this->getUserKpi($data['event'], Auth::id());
		}

		return view('web.home', $data);
	}

	public function leaderboard()
	{
		$data = [
			'event' => Auth::user()->event->toArray(),
			'date' => Carbon::now()->format("d F Y")
		];

		$data['leaderboard'] = $this->getLeaderboardKpi($data['event']);

		return view('web.leaderboard', $data);
	}

	private function getLeaderboardKpi($event)
	{
		$kpis = $event['kpi'];
		$users = User::where('is_admin', false)
			->orderBy('name', 'asc')
			->get()
			->toArray();

		$results = [
			'columns' => array_column($kpis, 'short_text'),
			'data' => []
		];

		foreach ($users as $user) {
			array_push($results['data'], ['user' => $user, 'kpis' => $this->getUserKpi($event, $user['id'])]);
		}

		return $results;
	}

	private function getUserKpi($event, $userId)
	{
		$kpis = $event['kpi'];
		$eventAnswers = $this->getUserEventAnswers($event, $userId)
			->where('is_terminated', 0)
			->get()
			->toArray();

		$results = [];
		foreach ($kpis as &$kpi) {
			$tempResult = 0;

			foreach ($eventAnswers as $eventAnswer) {
				$data = $this->getRecursiveArray($eventAnswer['answer'], explode('.', $kpi['field']));
				$result = null;
				switch ($kpi['type']) {
					case 'count':
						$result = count($data);
						break;
					case 'require':
						$result = isset($data) ? 1 : 0;
						break;
					case 'require_multiple':
						$result = (count(array_diff($kpi['values'], $data)) == 0) ? 1 : 0;
						break;
					case 'price':
						$result = 0;
						foreach ($data as $package) {
							$packageSplit = explode('_', $package);
							$result += array_pop($packageSplit);
						}
						break;
					default:
						$result = 0;
				}
				$tempResult += $result;
			}

			array_push($results, [
				'text' => $kpi['text'],
				'goal' => $kpi['goal'],
				'unit' => $kpi['unit'],
				'result' => $tempResult
			]);
		}

		return $results;
	}

	private function getLocation()
	{
		$userLocation = UserLocation::where('user_id', Auth::id())
			->where('state', 'login')
			->orderBy('created_at', 'asc')
			->get()
			->last();

		if (isset($userLocation)) {
			$userLocation = $userLocation->toArray();

			$client = new Client();
			$response = $client->request('GET',
				'https://maps.googleapis.com/maps/api/geocode/json?latlng=' . $userLocation['location']['lat'] . ',' . $userLocation['location']['lng'] .
				'&key=AIzaSyCNuAicyuHAcSTDb7fZVeJH-pU9Qns0KBk');
			$gmapsLocation = json_decode($response->getBody(), true)['results'];
			foreach ($gmapsLocation as $gmapLocation) {
				if (in_array('establishment', $gmapLocation['types']) ||
					in_array('route', $gmapLocation['types']) ||
					in_array('street_address', $gmapLocation['types'])
				) {
					return $gmapLocation['formatted_address'];
				}
			}
		}

		return 'Location not found';
	}

	private function getRecursiveArray($arr, array $keys)
	{
		if (count($keys) > 0) {
			$key = array_shift($keys);
			if (array_key_exists($key, $arr)) {
				return $this->getRecursiveArray($arr[$key], $keys);
			} else {
				$temp = [];
				foreach ($arr as $item) {
					array_push($temp, $item[$key]);
				}
				return $this->getRecursiveArray($temp, $keys);
			}
		}

		return $arr;
	}

	private function getUserEventAnswers($event, $userId)
	{
		$date = Carbon::now();
		if ($date->hour < $this->RESET_HOUR) {
			$date->subDay(1)->setTime(3, 0, 0);
		} else {
			$date->setTime(3, 0, 0);
		}

		return EventAnswer::where('event_id', $event['id'])
			->where('created_at', '>=', $date->toDateTimeString())
			->where('user_id', $userId);
	}

	private function getReportCount($eventId, $userId)
	{
		$reports = $this->getUserEventAnswers($eventId, $userId)->get();

		$results['success'] = $reports->where('is_terminated', 0)->count();
		$results['failed'] = $reports->where('is_terminated', 1)->count();

		return $results;
	}
}
