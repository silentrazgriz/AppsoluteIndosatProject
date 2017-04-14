<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\EventAnswer;
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

	private function getValidReports($event, $userId) {
		$date = Carbon::now();
		if ($date->hour < $this->RESET_HOUR) {
			$date->subDay(1)->setTime(3, 0, 0);
		} else {
			$date->setTime(3, 0, 0);
		}

		return EventAnswer::where('user_id', $userId)
			->where('event_id', $event['id'])
			->where('created_at', '>=', $date->toDateTimeString())
			->get();
	}

    private function getUserKpi($event, $userId) {
		$kpis = $event['kpi'];
		$eventAnswers = $this->getValidReports($event, $userId)
			->where('is_terminated', 0)
			->toArray();

		foreach ($kpis as &$kpi) {
			$kpi['result'] = 0;
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
				$kpi['result'] += $result;
			}
		}

		return $kpis;
    }

    private function getRecursiveArray($arr, array $keys) {
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

    private function getLocation() {
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

    private function getReportCount($eventId, $userId) {
		$reports = $this->getValidReports($eventId, $userId);

	    $results['success'] = $reports->where('is_terminated', 0)->count();
	    $results['failed'] = $reports->where('is_terminated', 1)->count();

	    return $results;
    }
}
