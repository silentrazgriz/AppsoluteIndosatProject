<?php


namespace App\Helpers;


use Carbon\Carbon;

class DateHelpers
{
	public static function getDateFromFormat($date = null, $format = 'Y-m-d')
	{
		$default = (Carbon::now()->hour < 2) ? Carbon::now()->subDay(1) : Carbon::now();
		return (isset($date)) ?
			Carbon::createFromFormat($format, $date)->setTime(config('constants.RESET_HOUR'), 0, 0) :
			$default->setTime(config('constants.RESET_HOUR'), 0, 0);
	}
}