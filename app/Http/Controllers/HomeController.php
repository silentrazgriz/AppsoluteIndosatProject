<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
    	$data = [
    		'date' => Carbon::now()->format("d F Y, h:i A")
	    ];

        return view('web.home', $data);
    }
}
