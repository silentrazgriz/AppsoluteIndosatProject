<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\EventAnswer;
use App\Models\UserLocation;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
	private const RESET_HOUR = 3;

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
    	$data = [
    		'date' => Carbon::now()->format("d F Y, h:i A"),
		    //'event' => null,
		    'event' => Event::first()->toArray(),
		    'user' => null
	    ];
    	if (!Auth::guest()) {
    		$data['event'] = Auth::user()->event->toArray();
		    $data['user'] = Auth::user()->toArray();
			$data['reports'] = $this->getReportCount($data['event']);
		    $data['location'] = $this->getLocation();
	    }

	    return view('web.home', $data);
    }

    private function getLocation() {
    	$userLocation = UserLocation::where('user_id', Auth::id())
		    ->where('state', 'login')
	        ->orderBy('created_at', 'desc')
	        ->get()
		    ->last();

    	if (isset($userLocation)) {
		    $userLocation = $userLocation->toArray();

		    $client = new Client();
		    $response = $client->request('GET',
			    'https://maps.googleapis.com/maps/api/geocode/json?latlng=' . $userLocation['location']['lat'] . ',' . $userLocation['location']['lng'] .
			    '&key=AIzaSyCNuAicyuHAcSTDb7fZVeJH-pU9Qns0KBk');
		    $gmapsLocation = json_decode($response->getBody(), true)['results'];
		    foreach ($gmapsLocation as $gmapLocation) {
			    if (in_array('establishment', $gmapLocation['types']) ||
				    in_array('route', $gmapLocation['types']) ||
				    in_array('street_address', $gmapLocation['types'])
			    ) {
				    return $gmapLocation['formatted_address'];
			    }
		    }
	    }

	    return 'Location not found';
    }

    private function getReportCount($event) {
		$date = Carbon::now();
		if ($date->hour < self::RESET_HOUR) {
			$date->subDay(1)->setTime(3, 0, 0);
		} else {
			$date->setTime(3, 0, 0);
		}

	    $reports = EventAnswer::where('user_id', Auth::id())
		    ->where('event_id', $event['id'])
		    ->where('created_at', '>=', $date->toDateTimeString())
		    ->get();

	    $results['success'] = $reports->where('is_terminated', 0)->count();
	    $results['failed'] = $reports->where('is_terminated', 1)->count();

	    return $results;
    }
}
