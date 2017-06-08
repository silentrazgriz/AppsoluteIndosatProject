<?php

namespace App\Http\Controllers;

use App\Helpers\DateHelpers;
use App\Helpers\ImageHelpers;
use App\Helpers\KpiHelpers;
use App\Helpers\SurveyHelpers;
use App\Helpers\TableHelpers;
use App\Models\CompressImage;
use App\Models\Event;
use App\Models\EventAnswer;
use App\Models\SalesArea;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Thujohn\Twitter\Facades\Twitter;

class DashboardController extends Controller
{
	private $eventLists, $salesAreaLists, $userLists;

	public function __construct()
	{
		$this->eventLists = $this->getEventLists();
		$this->salesAreaLists = $this->getSalesAreaLists();
		$this->userLists = $this->getUserLists();
	}

	public function index(Request $request)
	{
		$eventId = $request['event_id'] ?? $this->eventLists[0]['key'];

		$date = [
			'from' => $request['from'] ?? Carbon::now()->subWeek(1)->toDateString(),
			'to' => $request['to'] ?? Carbon::now()->toDateString()
		];

		$event = Event::find($eventId);

		$data = array(
			'eventLists' => $this->eventLists,
			'salesAreaLists' => $this->salesAreaLists,
			'date' => $date,
			'form' => $request->all(),
			'chartData' => KpiHelpers::getAnswerReport($event, null, null, $date['from'], $date['to'])
		);

		return view('admin.dashboard.summary', ['page' => 'dashboard', 'data' => $data]);
	}

	public function dashboardPerArea(Request $request)
	{
		$eventId = $request['event_id'] ?? $this->eventLists[0]['key'];
		$areaId1 = $request['sales_area_id_1'] ?? 1;
		$areaId2 = $request['sales_area_id_2'] ?? 0;

		$date = [
			'from' => $request['from'] ?? Carbon::now()->subWeek(1)->toDateString(),
			'to' => $request['to'] ?? Carbon::now()->toDateString()
		];

		$event = Event::find($eventId);

		$data = array(
			'eventLists' => $this->eventLists,
			'salesAreaLists' => $this->salesAreaLists,
			'date' => $date,
			'form' => $request->all(),
			'chartData' => [
				'area1' => KpiHelpers::getAnswerReport($event, null, $areaId1, $date['from'], $date['to']),
				'area2' => ($areaId2 != 0) ? KpiHelpers::getAnswerReport($event, null, $areaId2, $date['from'], $date['to']) : []
			]
		);

		return view('admin.dashboard.area', ['page' => 'dashboard-area', 'data' => $data]);
	}

	public function dashboardPerAgent(Request $request)
	{
		$eventId = $request['event_id'] ?? $this->eventLists[0]['key'];
		$userId = $request['user_id'] ?? $this->userLists[0]['key'];

		$date = [
			'from' => $request['from'] ?? Carbon::now()->subWeek(1)->toDateString(),
			'to' => $request['to'] ?? Carbon::now()->toDateString()
		];

		$event = Event::find($eventId);

		$eventAnswers = EventAnswer::select('created_at as time', 'area as location', 'step', 'is_terminated as status', 'answer')
			->where('event_id', $eventId)
			->where('user_id', $userId)
			->where('created_at', '>=', DateHelpers::getDateFromFormat($date['from'])->toDateTimeString())
			->where('created_at', '<=', DateHelpers::getDateFromFormat($date['to'])->toDateTimeString())
			->orderBy('time', 'desc')
			->get()
			->toArray();

		$data = array(
			'eventLists' => $this->eventLists,
			'userLists' => $this->userLists,
			'date' => $date,
			'form' => $request->all(),
			'chartData' => KpiHelpers::getAnswerReport($event, $userId, null, $date['from'], $date['to']),

			'id' => 'answer-table',
			'columns' => array(),
			'values' => $this->processAnswerPerAgent($eventAnswers),
			'actions' => false
		);

		if (count($data['values']) > 0) {
			$data['columns'] = TableHelpers::getColumns($data['values'][0], ['id']);
			foreach ($data['values'] as &$value) {
				$value['status'] = ($value['status']) ? 'Failed' : 'Success';

				unset($value['id']);

				$value = array_values($value);
			}
			unset($value);
		}

		$data['values'] = json_encode($data['values']);

		return view('admin.dashboard.agent', ['page' => 'dashboard-agent', 'data' => $data]);
	}

	public function report(Request $request)
	{
		$date = [
			'from' => $request['from'] ?? Carbon::now()->subWeek(1)->toDateString(),
			'to' => $request['to'] ?? Carbon::now()->toDateString()
		];

		$data = array(
			'eventLists' => $this->eventLists,
			'date' => $date,
			'users' => array_merge([['key' => '0', 'text' => 'Semua']], $this->getUserLists()),
			'areas' => $this->getSalesAreaLists(),
			'form' => $request->all(),
		);

		return view('admin.report', ['page' => 'report', 'data' => $data]);
	}

	public function gallery(Request $request)
	{
		$eventId = $request['event_id'] ?? $this->eventLists[0]['key'];

		$date = [
			'from' => $request['from'] ?? Carbon::now()->subWeek(1)->toDateString(),
			'to' => $request['to'] ?? Carbon::now()->toDateString()
		];

		$imageKeys = $this->getImageKeys($eventId);
		$gallery = $this->getGalleryAnswers($eventId, $date, $imageKeys);

		$data = array(
			'eventLists' => $this->eventLists,
			'date' => $date,
			'pages' => $gallery,
			'images' => $this->getGalleries($gallery->toArray()['data'], $imageKeys),
			'form' => $request->all()
		);

		return view('admin.gallery', ['page' => 'gallery', 'data' => $data]);
	}

	public function hashtag(Request $request)
	{
		if (isset($request['q'])) {
			$tweets = Twitter::getSearch([
				'q' => $request['q'],
				'result_type' => 'recent',
				'count' => 48
			]);
		}

		return view('admin.hashtag', ['page' => 'hashtag', 'data' => isset($tweets) ? $tweets->statuses : [], 'q' => $request['q'] ?? '#']);
	}

	public function askVendor($key)
	{
		$config = CompressImage::where('path', 'askVendor')
			->first();
		$status = false;

		DB::transaction(function() use ($key, $config, &$status) {
			if ($key == 'active' && !isset($config)) {
				$config = new CompressImage();
				$config->path = 'askVendor';
				$config->save();

				$status = true;
			} else if ($key == 'release' && isset($config)) {
				$config->delete();

				$status = false;
			}
		});

		return 'App lock ' . ($status ? 'activated' : 'disabled');
	}

	public function compressImage()
	{
		$start = microtime(true);
		$compressedFiles = array_column(CompressImage::get()->toArray(), 'path');
		$files = array_values(array_diff(Storage::allFiles(''), $compressedFiles));
		$index = 0;
		$count = 0;
		$message = '';
		while (microtime(true) - $start < 29) {
			$filename = ImageHelpers::compressImage($files[$index]);
			if (!empty($filename)) {
				$message .= $filename . " compressed\n";
				CompressImage::create(['path' => $filename]);
				$count++;
			}
			$index++;
		}
		$message .= 'Done compressing ' . $count . ' files';
		$now = Carbon::now();
		Storage::put('compress-report-' . $now->toDateString() . '-' . $now->format('H-i-s') . '.txt', $message);
		return 'Done compressing ' . $count . ' files';
	}

	private function processAnswerPerAgent($eventAnswers)
	{
		foreach ($eventAnswers as &$eventAnswer) {
			$eventAnswer['customer'] = $eventAnswer['answer']['name'] ?? '';

			$sp = '';
			$voucher = '';
			$package = '';
			if (isset($eventAnswer['answer']['sales'])) {
				foreach ($eventAnswer['answer']['sales'] as $key => $sale) {
					$sp .= (($key > 0 && empty($sp)) || !empty($sp)) ? '; ' . $sale['new_number'] : $sale['new_number'];
					$voucher .= (($key > 0 && empty($voucher)) || !empty($voucher)) ? '; ' . implode(',', $sale['voucher']) : implode(',', $sale['voucher']);
					$package .= (($key > 0 && empty($package)) || !empty($package)) ? '; ' . str_replace('_', ' ', $sale['package']) : str_replace('_', ' ', $sale['package']);
				}
			}

			$eventAnswer['SP'] = $sp;
			$eventAnswer['voucher'] = $voucher;
			$eventAnswer['package'] = $package;

			unset($eventAnswer['answer']);
		}

		return $eventAnswers;
	}

	private function getGalleryAnswers($eventId, $date, $imageKeys)
	{
		$startDate = DateHelpers::getDateFromFormat($date['from']);
		$endDate = DateHelpers::getDateFromFormat($date['to']);

		$eventAnswers = EventAnswer::with('user')
			->where('event_id', $eventId)
			->where('created_at', '>=', $startDate->format(config('constants.DATE_FORMAT.MYSQL')))
			->where('created_at', '<=', $endDate->format(config('constants.DATE_FORMAT.MYSQL')))
			->orderBy('created_at', 'desc');

		foreach ($imageKeys as $imageKey) {
			$eventAnswers = $eventAnswers->where('answer', 'NOT LIKE', '%"' . $imageKey . '":null%');
		}

		$eventAnswers = $eventAnswers->paginate(config('constants.IMAGE_PER_PAGE'));

		return $eventAnswers;
	}

	private function getGalleries($data, $imageKeys)
	{
		$results = [];

		foreach ($data as $row) {
			foreach ($row['answer'] as $key => $answer) {
				if (in_array($key, $imageKeys)) {
					$isExist = file_exists(storage_path('app/public/' . $answer));
					if (!empty($answer)) {
						$filePath = asset('storage/' . $answer);
						array_push($results, [
							'name' => $row['user']['name'],
							'area' => $row['area'],
							'image' => $isExist ? $filePath : asset('images/no-image.png'),
							'date' => $row['created_at']
						]);
					}
				}
			}
		}

		return $results;
	}

	private function getImageKeys($eventId)
	{
		$imageKeys = [];
		$event = Event::find($eventId)
			->toArray();

		$questions = SurveyHelpers::getQuestions($event['survey']);
		foreach ($questions as $question) {
			if (isset($question['key']) && $question['type'] == 'image') {
				array_push($imageKeys, $question['key']);
			}
		}

		return $imageKeys;
	}

	private function getUserLists()
	{
		return User::select('id as key', 'email as text')
			->where('is_admin', 0)
			->orderBy('text', 'asc')
			->get()
			->toArray();
	}

	private function getEventLists()
	{
		return Event::select('id as key', 'name as text')
			->orderBy('created_at', 'desc')
			->get()
			->toArray();
	}

	private function getSalesAreaLists()
	{
		$salesArea = SalesArea::select('id as key', 'description as text')
			->get()
			->toArray();

		return array_merge([['key' => 0, 'text' => 'Pilih Area']], $salesArea);
	}
}
