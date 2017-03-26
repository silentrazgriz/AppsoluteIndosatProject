<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
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
		return view('web.survey', ['event' => $event]);
	}

    public function index() {
		$events = Event::select('id', 'name', 'date', 'auth_code as code')
			->orderBy('date', 'desc')->get()->toArray();
		$data = [
			'id' => 'event-table',
			'columns' => array(),
			'values' => $events,
			'edit' => 'edit-event',
			'destroy' => 'delete-event',
			'detail' => 'show-event'
		];
	    if (count($events) > 0) {
		    $data['columns'] = array_diff(array_keys($events[0]), ['id']);
	    }

		return view('admin.event.list', ['page' => 'event', 'data' => $data]);
    }

    public function show($id) {

    }

    public function create() {
		return view('admin.event.create', ['page' => 'create-event']);
    }

    public function edit($id) {

    }

    public function storeAnswer($id, Request $request) {

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
}
