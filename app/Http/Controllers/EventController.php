<?php

namespace App\Http\Controllers;

use App\Models\BalanceHistory;
use App\Models\Event;
use App\Models\EventAnswer;
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

		return view('web.survey', ['event' => $event, 'user' => $user, 'count' => $reportCount + 1]);
	}

    public function index() {
		$events = Event::select('id', 'name', 'date', 'auth_code as code')
			->orderBy('date', 'desc')->get()->toArray();
		$data = [
			'id' => 'event-table',
			'columns' => array(),
			'values' => $events,
			//'edit' => 'edit-event',
			//'destroy' => 'delete-event',
			'detail' => 'show-event'
		];
	    if (count($events) > 0) {
		    $data['columns'] = array_diff(array_keys($events[0]), ['id']);
	    }

		return view('admin.event.list', ['page' => 'event', 'data' => $data]);
    }

    public function show($id) {
		$event = Event::find($id)
			->select('id', 'name', 'date', 'survey as column')
			->first()
			->toArray();

		$eventAnswers = EventAnswer::where('event_id', $id)
			->join('users', 'event_answers.user_id', '=', 'users.id')
			->select('event_answers.id as key', 'users.name as sales', 'users.email as email',
				'event_answers.is_terminated as terminated', 'event_answers.created_at as time',
				'event_answers.answer as detail')
			->get()
			->toArray();

	    $data = [
		    'id' => 'answer-table',
		    'summary' => $event,
		    'columns' => array(),
		    'values' => $this->parseSurveyAnswer($eventAnswers, $event['column']),
		    'popup' => true
	    ];

	    if (count($eventAnswers) > 1) {
		    $data['columns'] = array_diff(array_keys($eventAnswers[0]), ['id', 'detail', 'key']);
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
		$data = $request->only($this->getSurveyColumns($event));
		// Upload file
		foreach($data as $key => $answer) {
			if ($request->hasFile($key)) {
				$path = $request[$key]->store($event['id'] . '/' . $key . '/' . $userId);
				$data[$key] = $path;
 			}
		}

		DB::transaction(function () use ($data, $event, $userId, $isTerminated) {
			EventAnswer::create([
				'event_id' => $event['id'],
				'user_id' => $userId,
				'answer' => $data,
				'is_terminated' => $isTerminated
 			]);

			if (!$isTerminated && isset($data['voucher'])) {
				$sales = User::find(Auth::id());

				$sales->balance -= $data['voucher'];
				$sales->save();

				if ($data['voucher'] > 0) {
					BalanceHistory::create([
						'user_id' => Auth::id(),
						'balance' => $data['voucher'] * -1,
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

    private function parseSurveyAnswer($answers, $columns) {
	    $questions = array();

	    foreach($columns as $column) {
		    foreach ($column['questions'] as $question) {
			    if(isset($question['key'])) {
				    array_push($questions, $question);
			    }
		    }
	    }

	    array_push($questions, ['key' => 'terminate', 'type' => 'terminate']);

	    foreach($answers as &$answer) {
		    foreach ($answer['detail'] as $key => &$detail) {
			    foreach($questions as $question) {
				    if ($question['type'] != 'line' && $question['key'] == $key) {
					    switch ($question['type']) {
						    case 'checkbox':
							    $detail = ($detail == 1) ? 'True' : 'False';
							    break;
						    case 'image':
							    $detail = (empty($detail)) ? '-' : '<a href="' . asset('storage/' . $detail) . '" target="_blank"><img src="' . asset('storage/' . $detail) . '"/></a>';
							    break;
						    default:
							    $detail = ($detail == '') ? '-' : $detail;
					    }
				    }
			    }
		    }
	    }

	    return $answers;
    }

    private function getSurveyColumns($event) {
	    $steps = array_column($event['survey'], 'questions');
	    $questions = array();
	    foreach ($steps as $step) {
	    	$questions = array_merge($questions, $step);
	    }
	    $result = array_diff(array_column($questions, 'key'), ['balance']);
	    array_push($result, 'terminate');
	    return $result;
    }
}
