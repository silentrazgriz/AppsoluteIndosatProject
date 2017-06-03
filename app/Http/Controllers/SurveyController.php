<?php


namespace App\Http\Controllers;


use App\Helpers\ImageHelpers;
use App\Helpers\SurveyHelpers;
use App\Models\BalanceHistory;
use App\Models\CompressImage;
use App\Models\Event;
use App\Models\EventAnswer;
use App\Models\NumberList;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SurveyController extends Controller
{
	public function edit($id)
	{
		$eventAnswer = EventAnswer::with('event')
			->with('user')
			->find($id)
			->toArray();

		$eventAnswer = $this->parseSurveyAnswerForEdit($eventAnswer);

		$data = [
			'survey' => $eventAnswer,
			'event' => $eventAnswer['event'],
			'user' => $eventAnswer['user'],
			'numbers' => NumberList::where('is_taken', 0)
				->orderBy('number', 'asc')
				->get()
				->toArray()
		];

		unset($data['survey']['event']);
		unset($data['survey']['user']);

		return view('admin.answer.edit', ['page' => 'event', 'data' => $data]);
	}

	public function show($eventId)
	{
		$data = [
			'event' => Event::find($eventId)->toArray(),
			'user' => Auth::user()->toArray(),
			'numbers' => NumberList::where('is_taken', 0)
				->orderBy('number', 'asc')
				->get()
				->toArray()
		];

		$data['count'] = EventAnswer::where('user_id', Auth::id())
			->where('event_id', $data['event']['id'])
			->count() + 1;

		return view('web.survey', $data);
	}

	public function showByNumber($number)
	{
		$eventAnswer = EventAnswer::where('answer', 'like', '%' . $number . '%')
			->first();

		if (isset($eventAnswer)) {
			return redirect()->route('edit-survey', ['id' => $eventAnswer->id]);
		}

		return redirect()->back();
	}

	public function store($eventId, Request $request)
	{
		$event = Event::find($eventId)->toArray();
		$userId = Auth::id();
		$isTerminated = $request['is_terminated'];
		$area = $request['area'];
		$step = $request['step'];
		$data = $request->only($this->getSurveyColumns($event, ['terminate']));

		$data = $this->parseSurveyAnswer($event, $userId, $data);

		DB::transaction(function () use ($data, $area, $step, $event, $userId, $isTerminated) {
			EventAnswer::create([
				'event_id' => $event['id'],
				'user_id' => $userId,
				'area' => $area,
				'step' => $step,
				'answer' => $data,
				'is_terminated' => $isTerminated
			]);

			if (isset($data['sales'])) {
				$this->removeTakenNumber($data['sales']);
			}

			if (!$isTerminated && isset($data['sales'])) {
				$voucher = $this->getVoucherValue($data['sales']);

				$sales = User::find(Auth::id());

				$sales->balance -= $voucher;
				$sales->save();

				if ($voucher > 0) {
					BalanceHistory::create([
						'user_id' => Auth::id(),
						'balance' => $voucher * -1,
						'added_by_admin' => false
					]);
				}
			}
		});

		return redirect()->route('home');
	}

	public function update($id, Request $request)
	{
		$eventAnswer = EventAnswer::with('event')
			->with('user')
			->find($id)
			->toArray();

		$event = $eventAnswer['event'];
		$userId = $eventAnswer['user'];

		$data = $request->only($this->getSurveyColumns($event, ['terminate']));

		$data = $this->parseSurveyAnswer($event, $userId, $data);

		DB::transaction(function () use ($data, $id) {
			$survey = EventAnswer::find($id);

			$current = $survey->answer;
			foreach ($data as $key => $value) {
				$current[$key] = $value ?? $current[$key];
			}
			$survey->answer = $current;
			$survey->save();
		});

		return redirect()->route('edit-survey', ['id' => $id]);
	}

	private function parseSurveyAnswerForEdit($eventAnswer)
	{
		$questions = SurveyHelpers::getQuestions($eventAnswer['event']['survey']);

		foreach ($questions as $question) {
			if (isset($question['key'])) {
				$key = $question['key'];
				switch ($question['type']) {
					case 'image':
						$isExist = file_exists(storage_path('app/public/' . $eventAnswer['answer'][$key]));
						$filePath = asset('storage/' . $eventAnswer['answer'][$key]);

						$eventAnswer['answer'][$key] = $isExist ? $filePath : asset('images/no-image.png');
						break;
					case 'phone':
						$eventAnswer['answer'][$key] = str_replace('+62', '', $eventAnswer['answer'][$key]);
						break;
				}
			}
		}

		return $eventAnswer;
	}

	private function parseSurveyAnswer($event, $userId, $data)
	{
		$questions = SurveyHelpers::getQuestions($event['survey']);

		foreach ($questions as $question) {
			if (isset($question['key'])) {
				$key = $question['key'];
				switch ($question['type']) {
					case 'checkboxes':
					case 'number_sales':
						$data[$key] = json_decode($data[$key], TRUE);
						break;
					case 'image':
						if (isset($data[$key])) {
							$path = $data[$key]->store($event['id'] . '/' . $key . '/' . $userId);
							CompressImage::create(['path' => ImageHelpers::compressImage($path)]);
							$data[$key] = $path;
						}
						break;
					case 'phone':
						$data[$key] = '+62' . $data[$key];
						break;
				}
			}
		}

		return $data;
	}

	private function removeTakenNumber($data)
	{
		foreach ($data as $sales) {
			$number = NumberList::where('number', $sales['new_number'])
				->first();

			if (isset($number)) {
				$number->is_taken = 1;
				$number->save();
			}
		}
	}

	private function getVoucherValue($data)
	{
		$total = 0;
		foreach ($data as $sales) {
			foreach ($sales['voucher'] as $denom) {
				$total += $denom;
			}
		}
		return $total;
	}

	private function getSurveyColumns($event, array $extra = null)
	{
		$steps = array_column($event['survey'], 'questions');
		$questions = array();

		foreach ($steps as $step) {
			$questions = array_merge($questions, $step);
		}

		$result = array_column($questions, 'key');

		if (isset($extra)) {
			$result = array_merge($result, $extra);
		}

		return $result;
	}
}