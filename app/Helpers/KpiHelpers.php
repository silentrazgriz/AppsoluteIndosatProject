<?php


namespace App\Helpers;


use App\Models\EventAnswer;
use App\Models\User;
use Carbon\Carbon;

class KpiHelpers
{
	public static function getLeaderboardKpi($event)
	{
		$kpis = $event['kpi'];
		$users = User::where('is_admin', false)
			->orderBy('name', 'asc')
			->get()
			->toArray();

		$results = [
			'columns' => array_column($kpis, 'short_text'),
			'data' => []
		];

		foreach ($users as $user) {
			array_push($results['data'], ['user' => $user, 'kpis' => self::getUserKpi($event, $user['id'])]);
		}

		return $results;
	}

	public static function getUserKpi($event, $userId)
	{
		$kpis = $event['kpi'];
		$eventAnswers = self::getUserEventAnswers($event, $userId)
			->where('is_terminated', 0)
			->get()
			->toArray();

		$results = [];
		foreach ($kpis as &$kpi) {
			$tempResult = 0;

			foreach ($eventAnswers as $eventAnswer) {
				$data = self::getRecursiveArray($eventAnswer['answer'], explode('.', $kpi['field']));
				$result = null;
				switch ($kpi['type']) {
					case 'count':
						$result = count($data);
						break;
					case 'require':
						$result = isset($data) ? 1 : 0;
						break;
					case 'require_multiple':
						$result = (count(array_diff($kpi['values'], $data)) == 0) ? 1 : 0;
						break;
					case 'price':
						$result = 0;
						foreach ($data as $package) {
							$packageSplit = explode('_', $package);
							$result += array_pop($packageSplit);
						}
						break;
					default:
						$result = 0;
				}
				$tempResult += $result;
			}

			array_push($results, [
				'text' => $kpi['text'],
				'goal' => $kpi['goal'],
				'unit' => $kpi['unit'],
				'result' => $tempResult
			]);
		}

		return $results;
	}

	public static function getUserEventAnswers($event, $userId)
	{
		$date = Carbon::now();
		if ($date->hour < config('constants.RESET_HOUR')) {
			$date->subDay(1)->setTime(3, 0, 0);
		} else {
			$date->setTime(3, 0, 0);
		}

		return EventAnswer::where('event_id', $event['id'])
			->where('created_at', '>=', $date->toDateTimeString())
			->where('user_id', $userId);
	}

	private static function getRecursiveArray($arr, array $keys)
	{
		if (count($keys) > 0) {
			$key = array_shift($keys);
			if (array_key_exists($key, $arr)) {
				return self::getRecursiveArray($arr[$key], $keys);
			} else {
				$temp = [];
				foreach ($arr as $item) {
					array_push($temp, $item[$key]);
				}
				return self::getRecursiveArray($temp, $keys);
			}
		}

		return $arr;
	}
}