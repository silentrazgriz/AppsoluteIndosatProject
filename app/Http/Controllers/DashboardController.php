<?php

namespace App\Http\Controllers;

use App\Helpers\KpiHelpers;
use App\Models\Event;
use App\Models\EventAnswer;
use App\Models\SalesArea;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    private $eventLists, $salesAreaLists, $userLists;

    public function __construct()
    {
        $this->eventLists = $this->getEventLists();
        $this->salesAreaLists = $this->getSalesAreaLists();
		$this->userLists = $this->getUserLists();
    }

    public function index(Request $request)
    {
        $eventId = $request['event_id'] ?? $this->eventLists[0]['key'];

        $date = [
            'from' => $request['from'] ?? Carbon::now()->subWeek(1)->toDateString(),
            'to' => $request['to'] ?? Carbon::now()->subDay(1)->toDateString()
        ];

        $event = Event::find($eventId);

        $data = array(
            'eventLists' => $this->eventLists,
            'salesAreaLists' => $this->salesAreaLists,
            'date' => $date,
	        'form' => $request->all(),
            'chartData' => KpiHelpers::getAnswerReport($event, null, null, $date['from'], $date['to'])
        );

        return view('admin.dashboard', ['page' => 'dashboard', 'data' => $data]);
    }

    public function dashboardPerArea(Request $request)
    {
	    $eventId = $request['event_id'] ?? $this->eventLists[0]['key'];
	    $areaId1 = $request['sales_area_id_1'] ?? 1;
	    $areaId2 = $request['sales_area_id_2'] ?? 0;

	    $date = [
		    'from' => $request['from'] ?? Carbon::now()->subWeek(1)->toDateString(),
		    'to' => $request['to'] ?? Carbon::now()->subDay(1)->toDateString()
	    ];

	    $event = Event::find($eventId);

	    $data = array(
		    'eventLists' => $this->eventLists,
		    'salesAreaLists' => $this->salesAreaLists,
		    'date' => $date,
		    'form' => $request->all(),
		    'chartData' => [
		    	'area1' => KpiHelpers::getAnswerReport($event, null, $areaId1, $date['from'], $date['to']),
			    'area2' => ($areaId2 != 0) ? KpiHelpers::getAnswerReport($event, null, $areaId2, $date['from'], $date['to']) : []
		    ]
	    );

	    return view('admin.dashboard_area', ['page' => 'dashboard-area', 'data' => $data]);
    }

    public function dashboardPerAgent(Request $request)
    {
	    $eventId = $request['event_id'] ?? $this->eventLists[0]['key'];
	    $userId = $request['user_id'] ?? 1;

	    $date = [
		    'from' => $request['from'] ?? Carbon::now()->subWeek(1)->toDateString(),
		    'to' => $request['to'] ?? Carbon::now()->subDay(1)->toDateString()
	    ];

	    $event = Event::find($eventId);

	    $data = array(
		    'eventLists' => $this->eventLists,
		    'userLists' => $this->userLists,
		    'date' => $date,
		    'form' => $request->all(),
		    'chartData' => KpiHelpers::getAnswerReport($event, $userId, null, $date['from'], $date['to'])
	    );

	    return view('admin.dashboard_agent', ['page' => 'dashboard-agent', 'data' => $data]);
    }

    public function report(Request $request)
    {
	    $eventId = $request['event_id'] ?? $this->eventLists[0]['key'];
	    $date = [
		    'from' => $request['from'] ?? Carbon::now()->subWeek(1)->toDateString(),
		    'to' => $request['to'] ?? Carbon::now()->subDay(1)->toDateString()
	    ];

	    $data = array(
		    'eventLists' => $this->eventLists,
		    'date' => $date,
		    'form' => $request->all()
	    );

    	return view('admin.report', ['page' => 'report', 'data' => $data]);
    }

    private function getUserLists()
    {
    	return User::select('id as key', 'email as text')
		    ->where('is_admin', 0)
		    ->orderBy('text', 'asc')
		    ->get()
		    ->toArray();
    }

    private function getEventLists()
    {
        return Event::select('id as key', 'name as text')
	        ->orderBy('created_at', 'asc')
            ->get()
            ->toArray();
    }

    private function getSalesAreaLists()
    {
        $salesArea = SalesArea::select('id as key', 'description as text')
            ->get()
            ->toArray();

        return array_merge([['key' => 0, 'text' => 'Pilih Area']], $salesArea);
    }
}
