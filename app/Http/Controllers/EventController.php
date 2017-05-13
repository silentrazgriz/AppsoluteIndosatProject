<?php

namespace App\Http\Controllers;

use App\Helpers\SurveyHelpers;
use App\Helpers\TableHelpers;
use App\Models\BalanceHistory;
use App\Models\Event;
use App\Models\EventAnswer;
use App\Models\NumberList;
use App\Models\SalesArea;
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
            'actions' => true
		];
		if (count($events) > 0) {
			$data['columns'] = TableHelpers::getColumns($events[0], ['id']);

            foreach ($data['values'] as &$value) {
                $value['actions'] = '<a href="' . route('show-event', ['id' => $value['id']]) . '" class="btn btn-primary btn-xs"><i class="fa fa-search" aria-hidden="true"></i> Lihat</a> <a href="' . route('edit-event', ['id' => $value['id']]) . '" class="btn btn-info btn-xs"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Ubah</a> <form method="POST" action="' . route('delete-event', ['id' => $value['id']]) . '" class="inline">' . csrf_field() . '<input name="_method" type="hidden" value="DELETE"><button type="submit" class="btn btn-danger btn-xs"><i class="fa fa-times" aria-hidden="true"></i> Hapus</button></form>';
                unset($value['id']);

                $value = array_values($value);
            }
            unset($value);
		}

        $data['values'] = json_encode($data['values']);
		
		return view('admin.event.list', ['page' => 'event', 'data' => $data]);
	}

	public function show($id, Request $request)
	{
		$userId = $request['user_id'] ?? 0;
		$salesAreaId = $request['sales_area_id'] ?? 0;

		$event = Event::select('id', 'name', 'start_date as mulai', 'end_date as selesai', 'survey as column')
			->find($id)
			->toArray();

		$eventAnswers = EventAnswer::where('event_answers.event_id', $id)
			->join('users', 'event_answers.user_id', '=', 'users.id')
			->select('event_answers.id', 'users.name as buddies', 'users.email as email',
				'event_answers.step', 'event_answers.is_terminated as status',
				'event_answers.created_at as time');

		if ($userId != 0) {
			$eventAnswers = $eventAnswers->where('event_answers.user_id', $userId);
		} else if ($salesAreaId != 0) {
			$users = array_column(User::where('sales_area_id', $salesAreaId)
				->select('id')
				->get()
				->toArray(), 'id');

			$eventAnswers = $eventAnswers->whereIn('user_id', $users);
		}

		$eventAnswers = $eventAnswers->orderBy('event_answers.created_at', 'desc')
			->get()
            ->toArray();

		$data = [
			'id' => 'answer-table',
			'summary' => $event,
			'columns' => array(),
			'values' => $eventAnswers,
            'actions' => true,
			'form' => $request->all(),
			'users' => array_merge(
				[['key' => '0', 'text' => 'Semua']],
				User::select('id as key', 'email as text')
					->orderBy('text', 'asc')
					->get()
					->toArray()
			),
			'salesAreas' => array_merge(
				[['key' => '0', 'text' => 'Semua']],
				SalesArea::select('id as key', 'description as text')
					->get()
					->toArray()
			)
		];

		if (count($eventAnswers) > 0) {
			$data['columns'] = TableHelpers::getColumns($eventAnswers[0], ['id', 'detail', 'key']);
			foreach ($data['values'] as &$value) {
                $value['status'] = ($value['status'] == 0) ? 'Success' : 'Terminated';

                $value['actions'] = '<a href="' . route('edit-survey', ['id' => $value['id']]) . '" class="btn btn-primary btn-xs"><i class="fa fa-search" aria-hidden="true"></i> Lihat</a>';
			    unset($value['id']);

                $value = array_values($value);
            }
            unset($value);
		}

        $data['values'] = json_encode($data['values']);

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
}
