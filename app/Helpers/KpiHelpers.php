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
			foreach ($eventAnswers as $key => $eventAnswer) {
				$result = 0;
				if (in_array($kpi['type'], config('constants.FIELD_REQUIRED_TYPES'))) {
					foreach ($kpi['values'] as $field) {
						$data = self::getRecursiveArray($eventAnswer['answer'], explode('.', $field));
						$result += max($result, self::getKpiResult($data, $kpi));
					}

					$result = self::getFieldKpiResult($result, $kpi);
				} else {
					$data = self::getRecursiveArray($eventAnswer['answer'], explode('.', $kpi['field']));
					$result = self::getKpiResult($data, $kpi);
				}
				$results[$key][$kpi['field']] = [
					'required' => $kpi['required'],
					'data' => $result
				];
			}
		}
		unset($kpi);

		foreach ($results as &$result) {
			foreach ($result as &$detail) {
				if (!empty($detail['required']) && $result[$detail['required']]['data'] == 0) {
					$detail['data'] = 0;
				}
			}
			unset($detail);
		}
		unset($result);

		foreach ($kpis as &$kpi) {
			$kpi['result'] = 0;
			foreach ($results as $result) {
				$kpi['result'] += $result[$kpi['field']]['data'];
			}
		}
		unset($kpi);

		return $kpis;
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
			//->where('created_at', '>=', $date->toDateTimeString())
			->where('user_id', $userId);
	}

	private static function getFieldKpiResult($result, $kpi) {
		switch ($kpi['type']) {
			case 'require_one_field':
				return ($result > 0) ? 1 : 0;
				break;
			case 'require_multiple_field':
				return ($result == count($kpi['field'])) ? 1 : 0;
				break;
			default:
				return 0;
		}
	}

	private static function getKpiResult($data, $kpi) {
		switch ($kpi['type']) {
			case 'count':
				return count($data);
				break;
			case 'require_multiple_field':
			case 'require_one_field':
			case 'require':
				return !empty($data) ? 1 : 0;
				break;
			case 'require_one':
				return (count(array_diff($kpi['values'], $data)) < count($kpi['values'])) ? 1 : 0;
				break;
			case 'require_multiple':
				return (count(array_diff($kpi['values'], $data)) == 0) ? 1 : 0;
				break;
			case 'price':
				$result = 0;
				foreach ($data as $package) {
					$packageSplit = explode('_', $package);
					$result += array_pop($packageSplit);
				}
				return $result;
				break;
			default:
				return 0;
		}
	}

	private static function getRecursiveArray($arr, array $keys)
	{
		if (!isset($arr)) {
			return [];
		}

		if (count($keys) > 0) {
			$key = array_shift($keys);
			if (array_key_exists($key, $arr)) {
				return self::getRecursiveArray($arr[$key], $keys);
			} else {
				$temp = [];
				foreach ($arr as $item) {
					if (!empty($item[$key])) {
						array_push($temp, $item[$key]);
					}
				}
				return self::getRecursiveArray($temp, $keys);
			}
		}

		return $arr;
	}
}