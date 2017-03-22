<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function index($eventId) {
		$event = Event::find($eventId);
		return view('web.survey', ['survey' => $event->survey]);
    }
}
