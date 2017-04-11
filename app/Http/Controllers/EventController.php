<?php

namespace App\Http\Controllers;

use App\Helpers\TableHelpers;
use App\Models\BalanceHistory;
use App\Models\Event;
use App\Models\EventAnswer;
use App\Models\NumberList;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Ramsey\Uuid\Uuid;

class EventController extends Controller
{
	public function storeValidator($data) {
		return Validator::make($data, [
			'name' => 'max:255',
			'date' => 'date'
		]);
	}

	public function survey($id) {
		$event = Event::find($id)->toArray();
		$user = Auth::user()->toArray();
		$reportCount = EventAnswer::where('user_id', Auth::id())
			    ->where('event_id', $event['id'])
			    ->count();
		$numbers = NumberList::where('is_taken', 0)
			->get()
			->toArray();

		return view('web.survey', ['event' => $event, 'user' => $user, 'count' => $reportCount + 1, 'numbers' => $numbers]);
	}

    public function index() {
		$events = Event::select('id', 'name', 'date', 'auth_code as code')
			->orderBy('date', 'desc')
			->get()
			->toArray();
		$data = [
			'id' => 'event-table',
			'columns' => array(),
			'values' => $events,
			//'edit' => 'edit-event',
			//'destroy' => 'delete-event',
			'detail' => 'show-event'
		];
	    if (count($events) > 0) {
		    $data['columns'] = TableHelpers::getColumns($events[0], ['id']);
	    }

		return view('admin.event.list', ['page' => 'event', 'data' => $data]);
    }

    public function show($id) {
		$event = Event::find($id)
			->select('id', 'name', 'date', 'survey as column')
			->first()
			->toArray();

		$eventAnswers = EventAnswer::where('event_answers.event_id', $id)
			->join('users', 'event_answers.user_id', '=', 'users.id')
			->select('event_answers.id as key', 'users.name as sales', 'users.email as email',
				'event_answers.step', 'event_answers.is_terminated as status',
				'event_answers.created_at as time', 'event_answers.answer as detail')
			->orderBy('event_answers.created_at', 'desc')
			->get()
			->toArray();

	    $data = [
		    'id' => 'answer-table',
		    'summary' => $event,
		    'columns' => array(),
		    'values' => $this->surveyAnswerToViewFormat($eventAnswers, $event['column']),
		    'popup' => true
	    ];

	    if (count($eventAnswers) > 0) {
		    $data['columns'] = TableHelpers::getColumns($eventAnswers[0], ['id', 'detail', 'key']);
	    }

		return view('admin.answer.list', ['page' => 'event', 'data' => $data]);
    }

    public function create() {
		return view('admin.event.create', ['page' => 'create-event']);
    }

    public function edit($id) {

    }

    public function storeAnswer($id, Request $request) {
		$event = Event::find($id)->toArray();
		$userId = Auth::id();
		$isTerminated = $request['is_terminated'];
		$data = $request->only($this->getSurveyColumns($event, ['terminate']));
		$area = $request['area'];
		$step = $request['step'];

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

			if (!$isTerminated && isset($data['voucher'])) {
				$voucher = $this->getVoucherValue($data['sales']);

				$sales = User::find(Auth::id());

				$sales->balance -= $voucher;
				$sales->save();

				if ($data['voucher'] > 0) {
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

	public function store(Request $request) {
		$data = $request->only([
			'name', 'date', 'auth_code', 'survey'
		]);

		$validator = $this->storeValidator($data);

		if ($validator->fails()) {
			return redirect()->back()
				->withErrors($validator)
				->withInput();
		}

		$data['survey'] = json_decode($data['survey']);
		$data['auth_code'] = substr(Uuid::uuid4(), 0, 5);

		DB::transaction(function () use ($data) {
			Event::create($data);
		});

		return redirect()->route('event');
	}

	public function update($id, Request $request) {

	}

    public function destroy($id) {

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
			foreach ($sales['voucher'] as $denom) {
				$total += $denom;
			}
		}
		return $total;
    }

    private function surveyAnswerToViewFormat($answers, $columns) {
	    $questions = $this->getQuestions($columns, [['key' => 'terminate', 'type' => 'terminate']]);

	    foreach($answers as &$answer) {
	    	$answer['status'] = ($answer['status'] == 0) ? 'Success' : 'Terminated';

		    foreach ($answer['detail'] as $key => &$detail) {
			    foreach($questions as $question) {
				    if ($question['type'] != 'line' && $question['key'] == $key) {
					    switch ($question['type']) {
						    case 'checkbox':
							    $detail = ($detail == 1) ? 'Ya' : 'Tidak';
							    break;
						    case 'image':
							    $detail = (empty($detail)) ? '-' : '<a href="' . asset('storage/' . $detail) . '" target="_blank"><img src="' . asset('storage/' . $detail) . '"/></a>';
							    break;
						    case 'number_sales':
						    	if (isset($detail)) {
								    $result = '';
								    foreach ($detail as $row) {
									    $result .= '<p>Number: ' . $row['number'] . '<br>Package: ' . $row['package'] . '<br>Voucher: ' . implode(', ', $row['voucher']) . '</p>';
								    }
								    $detail = str_replace('_', ' ', $result);
							    } else {
						    		$detail = '-';
							    }
								break;
						    default:
							    $detail = ($detail == '') ? '-' : str_replace('_', ' ', $detail);
					    }
				    }
			    }
			    if (is_array($detail)) {
			    	$detail = implode(', ', $detail);
			    }
		    }
	    }

	    return $answers;
    }

    private function parseSurveyAnswer($event, $userId, &$data) {
		$questions = $this->getQuestions($event['survey']);

		foreach($questions as $question) {
			if (isset($question['key'])) {
				$key = $question['key'];
				switch($question['type']) {
					case 'checkboxes':
					case 'number_sales':
						$data[$key] = json_decode($data[$key], TRUE);
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

    private function getQuestions($columns, array $extra = null) {
	    $questions = array();

	    foreach($columns as $column) {
		    foreach ($column['questions'] as $question) {
			    if(isset($question['key'])) {
				    array_push($questions, $question);
			    }
		    }
	    }

	    if (isset($extra)) {
		    array_merge($questions, $extra);
	    }

	    return $questions;
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
