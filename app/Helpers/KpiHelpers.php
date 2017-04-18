<?php


namespace App\Helpers;


use App\Models\EventAnswer;
use App\Models\SalesArea;
use App\Models\User;
use Carbon\Carbon;

class KpiHelpers
{
	public static function getReportPerSalesArea($event, $from, $to)
	{
		$results = [
			'type' => 'horizontalBar',
			'data' => [
				'labels' => [],
				'datasets' => []
			]
		];

		// Get all users from designated sales area
		$salesAreas = SalesArea::with('users')
			->get()
			->toArray();

		$results['data']['labels'] = array_column($salesAreas, 'description');

		foreach ($event['kpi'] as $key => $kpi) {
			array_push($results['data']['datasets'], [
				'type' => 'horizontalBar',
				'label' => '',
				'data' => [],
				'backgroundColor' => config('constants.CHART_COLORS')[$key]
			]);
		}

		foreach ($salesAreas as $salesArea) {
			$kpis = [];

			foreach ($salesArea['users'] as $user) {
				$userKpis = self::getUserKpi($event, $user['id'], $from, $to);

				foreach($userKpis as $key => $userKpi) {
					$result = $userKpi['result'] / $userKpi['reportUnit'];

					$kpis[$key]['text'] = $userKpi['text'];
					$kpis[$key]['text'] .=  ($userKpi['reportUnit'] > 1) ? ' x' . number_format($userKpi['reportUnit']) : '';

					if (isset($kpis[$key]['result'])) {
						$kpis[$key]['result'] += $result;
					} else {
						$kpis[$key]['result'] = $result;
					}
				}
			}

			foreach ($kpis as $key => $kpi) {
				$results['data']['datasets'][$key]['label'] = $kpi['text'];

				if (isset($results['data']['datasets'][$key]['data'])) {
					array_push($results['data']['datasets'][$key]['data'], round($kpi['result'], 2));
				} else {
					$results['data']['datasets'][$key]['data'] = [round($kpi['result'], 2)];
				}
			}
		}

		return json_encode($results);
	}

	public static function getReportPerSalesAgent($event)
	{
	}

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

	public static function getUserKpi($event, $userId, $from = null, $to = null)
	{
		$kpis = $event['kpi'];
		$eventAnswers = self::getUserEventAnswers($event, $userId, $from, $to)
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

	public static function getUserEventAnswers($event, $userId, $from = null, $to = null)
	{
		// format for $date "Y-m-d" ex: "1975-05-21"
		$fromDate = (isset($from)) ? Carbon::createFromFormat("Y-m-d", $from)->setTime(config('constants.RESET_HOUR'), 0, 0) : Carbon::now()->setTime(config('constants.RESET_HOUR'), 0, 0);
		$toDate = (isset($to)) ? Carbon::createFromFormat("Y-m-d", $to)->addDay(1)->setTime(config('constants.RESET_HOUR'), 0, 0) : Carbon::now()->addDay(1)->setTime(config('constants.RESET_HOUR'), 0, 0);

		return EventAnswer::where('event_id', $event['id'])
			->where('created_at', '>=', $fromDate->toDateTimeString())
			->where('created_at', '<=', $toDate->toDateTimeString())
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