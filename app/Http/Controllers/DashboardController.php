<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\EventAnswer;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index() {
    	$event = Event::first()->toArray();

    	$data = array(
    		'event' => $event,
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
			    ->toArray()
	    );
		return view('admin.home', ['page' => 'dashboard', 'data' => $data]);
    }
}
