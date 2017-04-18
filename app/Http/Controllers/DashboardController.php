<?php

namespace App\Http\Controllers;

use App\Helpers\KpiHelpers;
use App\Models\Event;
use App\Models\EventAnswer;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request) {
    	$events = Event::select('id as key', 'name as text')
		    ->get()
		    ->toArray();

	    $event = Event::find($events[0]['key'])
	        ->toArray();

    	$date = [
		    'from' => Carbon::now()->subWeek(1)->toDateString(),
		    'to' => Carbon::now()->subDay(1)->toDateString()
	    ];

    	if ($request->has('from') && $request->has('to')) {
			$date['from'] = $request['from'];
			$date['to'] = $request['to'];
	    }

	    if ($request->has('event')) {
    		$event = Event::find($request['event'])
			    ->toArray();
	    }

    	$data = array(
    		/*'event' => $event,
		    'success_answer' => EventAnswer::where('event_id', $event['id'])
			    ->where('is_terminated', false)
			    ->count(),
		    'failed_answer' => EventAnswer::where('event_id', $event['id'])
			    ->where('is_terminated', true)
			    ->count(),
		    'sales' => User::select('users.email', 'users.name', DB::raw('(select count(id) from event_answers where is_terminated = 0 and event_answers.user_id = users.id) as success_count'),
			    DB::raw('(select count(id) from event_answers where is_terminated = 1 and event_answers.user_id = users.id) as failed_count'))
			    ->orderBy('users.email', 'asc')
			    ->get()
			    ->toArray(),*/
		    'events' => $events,
		    'date' => $date,
		    'chartData' => KpiHelpers::getReportPerSalesArea($event, $date['from'], $date['to'])
	    );

		//return view('admin.home', ['page' => 'dashboard', 'data' => $data]);
	    return view('admin.dashboard', ['page' => 'dashboard', 'data' => $data]);
    }
}
