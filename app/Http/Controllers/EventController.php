<?php

namespace App\Http\Controllers;

use App\Helpers\SurveyHelpers;
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
			'destroy' => 'delete-event',
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
		    'values' => $this->parseSurveyAnswer($eventAnswers, $event['column']),
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
	    DB::transaction(function () use ($id) {
		    Event::destroy($id);
	    });

	    return redirect()->route('event');
    }

    private function parseSurveyAnswer($answers, $columns) {
	    $questions = SurveyHelpers::getQuestions($columns, [['key' => 'terminate', 'type' => 'terminate']]);

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
}
