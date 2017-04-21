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
    private $eventLists, $salesAreaLists;

    public function __construct()
    {
        $this->eventLists = $this->getEventLists();
        $this->salesAreaLists = $this->getSalesAreaLists();
    }

    public function index(Request $request)
    {
        $eventId = $request['event_id'] ?? $this->eventLists[0]['key'];
        $salesAreaId = $request['sales_area_id'] ?? 0;

        $date = [
            'from' => $request['from'] ?? Carbon::now()->subWeek(1)->toDateString(),
            'to' => $request['to'] ?? Carbon::now()->subDay(1)->toDateString()
        ];

        $event = Event::find($eventId);

        $data = array(
            'eventLists' => $this->eventLists,
            'salesAreaLists' => $this->salesAreaLists,
            'date' => $date,
            'chartData' => KpiHelpers::getReportPerSalesArea($event, $date['from'], $date['to'])
        );

        return view('admin.dashboard', ['page' => 'dashboard', 'data' => $data]);
    }

    private function getEventLists()
    {
        return Event::select('id as key', 'name as text')
            ->get()
            ->toArray();
    }

    private function getSalesAreaLists()
    {
        return SalesArea::select('id as key', 'description as text')
            ->get()
            ->toArray();
    }
}
