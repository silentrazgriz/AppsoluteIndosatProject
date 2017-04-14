<?php


namespace App\Http\Controllers;


use App\Helpers\SurveyHelpers;
use App\Models\BalanceHistory;
use App\Models\Event;
use App\Models\EventAnswer;
use App\Models\NumberList;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SurveyController extends Controller
{
	public function show($id) {
		$data = [
			'event' => Event::find($id)->toArray(),
			'user' => Auth::user()->toArray(),
			'numbers' => NumberList::where('is_taken', 0)
				->orderBy('number', 'asc')
				->get()
				->toArray()
		];
		$data['count'] = EventAnswer::where('user_id', Auth::id())
			->where('event_id', $data['event']['id'])
			->count();

		return view('web.survey', $data);
	}

	public function store($id, Request $request) {
		$event = Event::find($id)->toArray();
		$userId = Auth::id();
		$isTerminated = $request['is_terminated'];
		$area = $request['area'];
		$step = $request['step'];
		$data = $request->only($this->getSurveyColumns($event, ['terminate']));

		$this->parseSurveyAnswer($event, $userId, $data);

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

	private function parseSurveyAnswer($event, $userId, &$data) {
		$questions = SurveyHelpers::getQuestions($event['survey']);

		foreach($questions as $question) {
			if (isset($question['key'])) {
				$key = $question['key'];
				switch($question['type']) {
					case 'checkboxes':
						$data[$key] = json_decode($data[$key], TRUE);
						break;
					case 'number_sales':
						$data[$key] = json_decode($data[$key], TRUE);
						foreach ($data[$key] as $index => $item) {
							if (empty($item['number'])) {
								unset($data[$key][$index]);
							}
						}
						break;
					case 'image':
						if (isset($data[$key])) {
							$path = $data[$key]->store($event['id'] . '/' . $key . '/' . $userId);
							$data[$key] = $path;
						}
						break;
					case 'phone':
						$data[$key] = '+62' . $data[$key];
						break;
				}
			}
		}
	}

	private function removeTakenNumber($data) {
		foreach ($data as $sales) {
			$number = NumberList::where('number', $sales['number'])
				->first();

			if (isset($number)) {
				$number->is_taken = 1;
				$number->save();
			}
		}
	}

	private function getVoucherValue($data) {
		$total = 0;
		foreach ($data as $sales) {
			if (!empty($sales['number'])) {
				foreach ($sales['voucher'] as $denom) {
					$total += $denom;
				}
			}
		}
		return $total;
	}

	private function getSurveyColumns($event, array $extra = null) {
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