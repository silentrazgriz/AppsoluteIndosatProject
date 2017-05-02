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
	public function storeValidator($data)
	{
		return Validator::make($data, [
			'name' => 'max:255',
			'start_date' => 'date',
			'end_date' => 'date'
		]);
	}

	public function index()
	{
		$events = Event::select('id', 'name', 'start_date as mulai', 'end_date as selesai', 'auth_code as code')
			->orderBy('start_date', 'desc')
			->get()
			->toArray();
		$data = [
			'id' => 'event-table',
			'columns' => array(),
			'values' => $events,
			'edit' => 'edit-event',
			'destroy' => 'delete-event',
			'detail' => 'show-event'
		];
		if (count($events) > 0) {
			$data['columns'] = TableHelpers::getColumns($events[0], ['id']);
		}

		return view('admin.event.list', ['page' => 'event', 'data' => $data]);
	}

	public function show($id)
	{
		$event = Event::find($id)
			->select('id', 'name', 'start_date as mulai', 'end_date as selesai', 'survey as column')
			->first()
			->toArray();

		$eventAnswers = EventAnswer::where('event_answers.event_id', $id)
			->join('users', 'event_answers.user_id', '=', 'users.id')
			->select('event_answers.id', 'users.name as sales', 'users.email as email',
				'event_answers.step', 'event_answers.is_terminated as status',
				'event_answers.created_at as time')
			->orderBy('event_answers.created_at', 'desc')
			->paginate(config('constants.ITEM_PER_PAGE'));

		$answerValues = $this->parseSurveyAnswer($eventAnswers->toArray()['data']);

		$data = [
			'pages' => $eventAnswers,
			'id' => 'answer-table',
			'summary' => $event,
			'columns' => array(),
			'values' => $answerValues,
			'detail' => 'edit-survey'
		];

		if (count($eventAnswers) > 0) {
			$data['columns'] = TableHelpers::getColumns($answerValues[0], ['id', 'detail', 'key']);
		}

		return view('admin.answer.list', ['page' => 'event', 'data' => $data]);
	}

	public function create()
	{
		return view('admin.event.create', ['page' => 'create-event']);
	}

	public function edit($id)
	{
		$event = Event::find($id)
			->toArray();

		return view('admin.event.edit', ['page' => 'event', 'event' => $event]);
	}

	public function store(Request $request)
	{
		$data = $request->only([
			'name', 'start_date', 'end_date', 'auth_code', 'survey', 'kpi'
		]);

		$validator = $this->storeValidator($data);

		if ($validator->fails()) {
			return redirect()->back()
				->withErrors($validator)
				->withInput();
		}

		$data['survey'] = json_decode($data['survey']);
		$data['kpi'] = json_decode($data['kpi']);
		$data['auth_code'] = substr(Uuid::uuid4(), 0, 5);

		DB::transaction(function () use ($data) {
			Event::create($data);
		});

		return redirect()->route('event');
	}

	public function update($id, Request $request)
	{
		$data = $request->only([
			'name', 'start_date', 'end_date', 'auth_code', 'survey', 'kpi'
		]);

		$validator = $this->storeValidator($data);

		if ($validator->fails()) {
			return redirect()->back()
				->withErrors($validator)
				->withInput();
		}

		DB::transaction(function () use ($id, $data) {
			$event = Event::find($id);

			$event->name = $data['name'] ?? $event->name;
			$event->start_date = $data['start_date'] ?? $event->start_date;
			$event->end_date = $data['end_date'] ?? $event->end_date;
			$event->survey = json_decode($data['survey']) ?? $event->survey;
			$event->kpi = json_decode($data['kpi']) ?? $event->kpi;

			$event->save();
		});

		return redirect()->route('event');
	}

	public function destroy($id)
	{
		DB::transaction(function () use ($id) {
			Event::destroy($id);
		});

		return redirect()->route('event');
	}

	private function parseSurveyAnswer($answers)
	{
		foreach ($answers as &$answer) {
			$answer['status'] = ($answer['status'] == 0) ? 'Success' : 'Terminated';
		}
		unset($answer);

		return $answers;
	}
}
