<?php


namespace App\Helpers;


use Carbon\Carbon;

class DateHelpers
{
	public static function getDateFromFormat($date = null, $format = 'Y-m-d')
	{
		return (isset($date)) ? Carbon::createFromFormat($format, $date)->setTime(config('constants.RESET_HOUR'), 0, 0) : Carbon::now()->setTime(config('constants.RESET_HOUR'), 0, 0);
	}
}