<?php


namespace App\Helpers;


use App\Models\EventAnswer;
use App\Models\SalesArea;
use App\Models\User;
use Carbon\Carbon;

class KpiHelpers
{
	public static function getAnswerReport($event, $userId = null, $areaId = null, $from = null, $to = null)
	{
		$reportQuestionType = config('constants.REPORT_QUESTION_TYPE');
		$chartDateFormat = config('constants.DATE_FORMAT.CHART');
		$mysqlDateFormat = config('constants.DATE_FORMAT.MYSQL');
		$results = [];

		$surveys = $event['survey'];

		$answers = self::getUserEventAnswers($event, $userId, $areaId, $from, $to)
			->get()
			->toArray();

		$results = array_merge($results, self::getKpiReport($event, $userId, $areaId, $from, $to, $chartDateFormat));
		if (!isset($userId)) {
			$results = array_merge($results, self::getResultsFromAnswer($areaId, $surveys, $reportQuestionType, $answers, $from, $to, $chartDateFormat, $mysqlDateFormat));
		}

		return $results;
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
		$eventAnswers = self::getUserEventAnswers($event, $userId, null, $from, $to)
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

	public static function getUserEventAnswers($event, $userId = null, $areaId = null, $from = null, $to = null)
	{
		// format for $date "Y-m-d" ex: "1975-05-21"
		$fromDate = self::getDateFromFormat($from);
		$toDate = isset($to) ? self::getDateFromFormat($to) : self::getDateFromFormat($from)->addDay(1);

		$eventAnswer = EventAnswer::where('event_id', $event['id'])
			->where('created_at', '>=', $fromDate->toDateTimeString())
			->where('created_at', '<=', $toDate->toDateTimeString());

		if (isset($areaId)) {
			$salesAreas = SalesArea::where('id', $areaId)
				->with('users')
				->first()
				->toArray();

			$eventAnswer = $eventAnswer->whereIn('user_id', array_column($salesAreas['users'], 'id'));
		}

		if (isset($userId)) {
			$eventAnswer = $eventAnswer->where('user_id', $userId);
		}

		return $eventAnswer->orderBy('created_at', 'asc');
	}

	private static function processAnswerReportData($question, $answers, $areaId)
	{
		$data = [];

		foreach ($question['values'] as $value) {
			$data = array_merge($data, [$value['key'] => 0]);
		}

		foreach ($answers as $answer) {
			if ($question['type'] == 'checkboxes' && isset($answer['answer'][$question['key']])) {
				foreach ($answer['answer'][$question['key']] as $value) {
					$data[$value]++;
				}
			} else if (isset($answer['answer'][$question['key']])) {
				$data[$answer['answer'][$question['key']]]++;
			}
		}

		return [
			'key' => $question['key'] . (isset($areaId) ? $areaId : ''),
			'text' => $question['text'],
			'chartData' => self::createChartData('horizontalBar', $data, 'HIDE_LABEL_OPTIONS'),
			'drawDataInside' => true,
			'hiddenLabel' => true,
			'dataCount' => count($data)
		];
	}

	private static function processNumberSalesReportData($question, $answers, $areaId, $from, $to, $chartDateFormat, $mysqlDateFormat)
	{
		$results = [];
		$numbers = [];
		$packages = [];
		$vouchers = [];

		foreach ($question['package']['values'] as $value) {
			$packages[$value['key']] = [];
		}

		foreach ($question['voucher']['values'] as $value) {
			$vouchers[$value['key']] = [];
		}

		$startDate = self::getDateFromFormat($from);
		$endDate = self::getDateFromFormat($to);

		$dates = [];
		while ($startDate->diffInDays($endDate) != 0) {
			$numbers[$startDate->format($chartDateFormat)] = 0;

			foreach ($packages as &$package) {
				$package[$startDate->format($chartDateFormat)] = 0;
			}
			unset($package);

			foreach ($vouchers as &$voucher) {
				$voucher[$startDate->format($chartDateFormat)] = 0;
			}
			unset($voucher);

			array_push($dates, $startDate->format($chartDateFormat));

			$startDate->addDay(1);
		}

		foreach ($answers as $answer) {
			if (isset($answer['answer']['sales'])) {
				foreach ($answer['answer']['sales'] as $sale) {
					$answerDate = self::getDateFromFormat($answer['created_at'], $mysqlDateFormat);

					if (isset($sale['number'])) {
						$numbers[$answerDate->format($chartDateFormat)]++;
					}

					$packages[$sale['package']][$answerDate->format($chartDateFormat)]++;

					if (isset($sale['voucher'])) {
						foreach ($sale['voucher'] as $voucher) {
							$vouchers[$voucher][$answerDate->format($chartDateFormat)]++;
						}
					}
				}
			}
		}

		array_push($results, [
			'key' => $question['number']['key'] . (isset($areaId) ? $areaId : ''),
			'text' => $question['number']['text'],
			'chartData' => self::createChartData('line', $numbers, 'NO_LEGEND_OPTIONS'),
			'drawDataInside' => false,
			'hiddenLabel' => false,
			'dataCount' => 6
		]);

		array_push($results, [
			'key' => $question['package']['key'] . (isset($areaId) ? $areaId : ''),
			'text' => $question['package']['text'],
			'chartData' => self::createChartData('line', $packages, 'POINT_STYLE_LEGEND_OPTIONS', $dates, true),
			'drawDataInside' => false,
			'hiddenLabel' => false,
			'dataCount' => 10
		]);

		array_push($results, [
			'key' => $question['voucher']['key'] . (isset($areaId) ? $areaId : ''),
			'text' => $question['voucher']['text'],
			'chartData' => self::createChartData('line', $vouchers, 'POINT_STYLE_LEGEND_OPTIONS', $dates),
			'drawDataInside' => false,
			'hiddenLabel' => false,
			'dataCount' => 6
		]);

		return $results;
	}

	private static function getKpiReport($event, $userId = null, $areaId = null, $from, $to, $chartDateFormat)
	{
		// Get all users from designated sales area
		$salesAreas = SalesArea::with('users');

		$labels = [];
		$data = [];

		if (isset($areaId) || isset($userId)) {
			$startDate = self::getDateFromFormat($from);
			$endDate = self::getDateFromFormat($to);

			while ($startDate->diffInDays($endDate) != 0) {
				$description = $startDate->format($chartDateFormat);

				if (isset($areaId)) {
					$salesArea = $salesAreas->where('id', $areaId)
						->first()
						->toArray();

					foreach ($salesArea['users'] as $user) {
						$userKpis = self::getUserKpi($event, $user['id'], $startDate->format('Y-m-d'), null);

						self::kpiToChartFormat($data, $userKpis, $description);
					}
				}
				else if (isset($userId)) {
					$userKpis = self::getUserKpi($event, $userId, $startDate->format('Y-m-d'), null);

					self::kpiToChartFormat($data, $userKpis, $description);
				}

				array_push($labels, $startDate->format($chartDateFormat));

				$startDate->addDay(1);
			}
		} else {
			$salesAreas = $salesAreas->get()->toArray();

			$labels = array_column($salesAreas, 'description');

			foreach ($salesAreas as $salesArea) {
				$description = str_replace(' ', '_', $salesArea['description']);

				foreach ($salesArea['users'] as $user) {
					$userKpis = self::getUserKpi($event, $user['id'], $from, $to);

					self::kpiToChartFormat($data, $userKpis, $description);
				}
			}
		}

		return [
			[
				'key' => 'kpi' . (isset($areaId) ? $areaId : ''),
				'text' => 'KPI',
				'chartData' => self::createChartData('bar', $data, 'KPI_OPTIONS', $labels),
				'drawDataInside' => false,
				'hiddenLabel' => false,
				'dataCount' => 8
			]
		];
	}

	private static function kpiToChartFormat(&$data, $userKpis, $description) {
		foreach ($userKpis as $key => $userKpi) {
			$result = $userKpi['result'] / $userKpi['reportUnit'];

			$label = str_replace(' ', '_', $userKpi['text']);
			$label .= ($userKpi['reportUnit'] > 1) ? '_x' . number_format($userKpi['reportUnit']) : '';

			if (isset($data[$label][$description])) {
				$data[$label][$description] += $result;
			} else {
				$data[$label][$description] = $result;
			}
			$data[$label][$description] = round($data[$label][$description], 2);
		}
	}

	private static function getResultsFromAnswer($areaId, $surveys, $reportQuestionType, $answers, $from, $to, $chartDateFormat, $mysqlDateFormat)
	{
		$results = [];

		foreach ($surveys as $survey) {
			foreach ($survey['questions'] as $question) {
				if (in_array($question['type'], $reportQuestionType)) {
					if ($question['type'] == 'dropdown' || $question['type'] == 'radio' || $question['type'] == 'checkboxes') {
						array_push($results, self::processAnswerReportData($question, $answers, $areaId));
					} else if ($question['type'] == 'number_sales') {
						$results = array_merge($results, self::processNumberSalesReportData($question, $answers, $areaId, $from, $to, $chartDateFormat, $mysqlDateFormat));
					}
				}
			}
		}

		return $results;
	}

	private static function getDateFromFormat($date = null, $format = 'Y-m-d')
	{
		return (isset($date)) ? Carbon::createFromFormat($format, $date)->setTime(config('constants.RESET_HOUR'), 0, 0) : Carbon::now()->setTime(config('constants.RESET_HOUR'), 0, 0);
	}

	private static function getFieldKpiResult($result, $kpi)
	{
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

	private static function getKpiResult($data, $kpi)
	{
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

	private static function createChartData($type, $data, $config = null, $labels = null, $removeLegendLastWord = false)
	{
		$result = [
			'type' => $type,
			'data' => [
				'labels' => array_map('ucwords', str_replace('_', ' ', $labels ?? array_keys($data))),
				'datasets' => []
			]
		];

		if (is_array(array_values($data)[0])) {
			$index = 0;
			foreach ($data as $key => $values) {
				$label = $key;

				if ($removeLegendLastWord) {
					$label = explode('_', $label);
					array_pop($label);
					$label = implode('_', $label);
				}

				$dataset = [
					'label' => ucwords(str_replace('_', ' ', $label)),
					'data' => array_values($values),
				];

				if ($type == 'line') {
					$dataset = array_merge($dataset, [
						'backgroundColor' => 'rgba(0, 0, 0, 0)',
						'borderColor' => config('constants.CHART_COLORS')[$index],
						'borderWidth' => 2,
						'pointBackgroundColor' => config('constants.CHART_COLORS')[$index]
					]);
				} else if ($type == 'horizontalBar' || $type == 'bar') {
					$dataset = array_merge($dataset, [
						'backgroundColor' => config('constants.CHART_COLORS')[$index],
						'borderColor' => 'rgba(0, 0, 0, 1)',
						'borderWidth' => 1
					]);
				}
				array_push($result['data']['datasets'], $dataset);

				$index++;
			}
		} else {
			$dataset = [
				'label' => '',
				'data' => array_values($data),
			];

			if ($type == 'line') {
				$dataset = array_merge($dataset, [
					'backgroundColor' => 'rgba(0, 0, 0, 0)',
					'borderColor' => config('constants.CHART_COLORS')[0],
					'borderWidth' => 2,
					'pointBackgroundColor' => config('constants.CHART_COLORS')[0]
				]);
			} else if ($type == 'horizontalBar' || $type == 'bar') {
				$dataset = array_merge($dataset, [
					'type' => $type,
					'backgroundColor' => config('constants.CHART_COLORS')[0],
					'borderColor' => 'rgba(0, 0, 0, 1)',
					'borderWidth' => 1
				]);
			}
			$result['data']['datasets'] = [$dataset];
		}

		if (isset($config)) {
			$result = array_merge($result, [
				'options' => config('constants.CHART.' . $config)
			]);
		}

		return json_encode($result);
	}

}