<?php


namespace App\Http\Controllers;


use App\Helpers\DateHelpers;
use App\Helpers\KpiHelpers;
use App\Helpers\SurveyHelpers;
use App\Models\Event;
use App\Models\EventAnswer;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ExcelController
{
	public function salesToExcel(Request $request)
	{
		$eventId = $request['event_id'];
		$from = $request['from'];
		$to = $request['to'];
		$userId = $request['user_id'];
		$salesAreaId = $request['sales_area_id'];

		$result = [];

		$eventAnswers = EventAnswer::select('created_at as date', 'area', 'answer')
			->where('event_id', $eventId)
			->where('created_at', '>=', DateHelpers::getDateFromFormat($from)->format(config('constants.DATE_FORMAT.MYSQL')))
			->where('created_at', '<=', DateHelpers::getDateFromFormat($to)->format(config('constants.DATE_FORMAT.MYSQL')));

		if ($userId != 0) {
			$eventAnswers = $eventAnswers->where('user_id', $userId);
		} else if ($salesAreaId != 0) {
			$users = $this->getUserIdsInArea($salesAreaId);
			$eventAnswers = $eventAnswers->whereIn('user_id', $users);
		}

		$eventAnswers = $eventAnswers->orderBy('date', 'desc')
			->get()
			->toArray();

		foreach ($eventAnswers as $answer) {
			if (isset($answer['answer']['sales'])) {
				foreach ($answer['answer']['sales'] as $sale) {
					array_push($result, [
						'date' => Carbon::parse($answer['created_at'])->toDateString(),
						'time' => Carbon::parse($answer['created_at'])->toTimeString(),
						'new_number' => $sale['new_number'],
						'old_number' => $sale['old_number'],
						'package' => $sale['package'],
						'voucher' => implode(";", $sale['voucher'])
					]);
				}
			}
		}

		Excel::create('Report-Sales-' . $from . '-' . $to, function ($excel) use ($result) {
			$excel = $this->getExcelConfig($excel);

			$excel->sheet('Sales Summary', function ($sheet) use ($result) {
				$sheet->fromArray($result, null, 'A1', true);
			});
		})->download('xls');
	}

	public function answerToExcel(Request $request)
	{
		$unset = ['sales'];
		$results = [];

		$eventId = $request['event_id'];
		$from = $request['from'];
		$to = $request['to'];
		$salesAreaId = $request['sales_area_id'];
		$userId = $request['user_id'];

		$eventAnswers = EventAnswer::with('user')
			->where('event_id', $eventId)
			->where('created_at', '>=', DateHelpers::getDateFromFormat($from)->format(config('constants.DATE_FORMAT.MYSQL')))
			->where('created_at', '<=', DateHelpers::getDateFromFormat($to)->format(config('constants.DATE_FORMAT.MYSQL')));

		if ($userId != 0) {
			$eventAnswers = $eventAnswers->where('user_id', $userId);
		} else if ($salesAreaId != 0) {
			$users = $this->getUserIdsInArea($salesAreaId);
			$eventAnswers = $eventAnswers->whereIn('user_id', $users);
		}

		$eventAnswers = $eventAnswers->orderBy('created_at', 'desc')
			->get()
			->toArray();

		$event = Event::find($eventId);
		$questions = SurveyHelpers::getQuestions($event['survey']);
		foreach ($questions as $question) {
			if (isset($question['key']) && $question['type'] == 'image') {
				array_push($unset, $question['key']);
			}
		}

		foreach ($eventAnswers as &$answer) {
			$result = [];

			$result['date'] = Carbon::parse($answer['created_at'])->toDateString();
			$result['time'] = Carbon::parse($answer['created_at'])->toTimeString();
			$result['buddies'] = $answer['user']['name'];
			$result['area'] = $answer['area'];

			$result['new_number'] = strval($this->splitColumnArray($answer['answer']['sales'], 'new_number', '|'));
			$result['old_number'] = strval($this->splitColumnArray($answer['answer']['sales'], 'old_number', '|'));
			$result['package'] = $this->splitColumnArray($answer['answer']['sales'], 'package', "|");
			$result['voucher'] = $this->splitVoucherData($answer['answer']['sales']);
			unset($answer['sales']);

			foreach ($answer['answer'] as $key => &$value) {
				if (!in_array($key, $unset)) {
					if (is_array($value)) {
						$result[$key] = implode(";", $value);
					} else {
						$result[$key] = $value;
					}
				}
			}
			unset($value);

			array_push($results, $result);
		}
		unset($answer);

		Excel::create('Report-Answer-' . $from . '-' . $to, function ($excel) use ($results) {
			$excel = $this->getExcelConfig($excel);

			$excel->sheet('Answer Summary', function ($sheet) use ($results) {
				$sheet->fromArray($results, null, 'A1', true);
			});
		})->download('xls');
	}

	public function kpiToExcel(Request $request)
	{
		$data = [];
		$summary = [];

		$eventId = $request['event_id'];
		$from = $request['from'];
		$to = $request['to'];
		$userId = $request['user_id'];
		$salesAreaId = $request['sales_area_id'];

		$event = Event::find($eventId)->toArray();
		$startDate = DateHelpers::getDateFromFormat($from);
		$endDate = DateHelpers::getDateFromFormat($to)->addDay(1);

		while ($startDate->diffInDays($endDate) != 0) {
			$date = $startDate->format(config('constants.DATE_FORMAT.DEFAULT'));
			$data[$date] = KpiHelpers::getLeaderboardKpi($event, $date);

			$startDate->addDay(1);
		}

		Excel::create('Report-Leaderboard-Indosat-' . $from . '-' . $to, function ($excel) use ($data, &$summary, $userId, $salesAreaId) {
			$excel = $this->getExcelConfig($excel);

			foreach ($data as $date => $leaderboard) {
				$excel->sheet($date, function ($sheet) use (&$summary, $leaderboard, $userId, $salesAreaId) {
					$sheetData = $this->parseLeaderboardToArray($leaderboard, $userId, $salesAreaId);
					$summary = $this->processSummary($summary, $sheetData, $userId, $salesAreaId);

					$sheet = $this->getKpiSheetConfig($sheet, $sheetData);
					$sheet->fromArray($sheetData, null, 'A1', true);
				});
			}

			$excel->sheet('Summary', function ($sheet) use ($summary) {
				$sheet = $this->getKpiSheetConfig($sheet, $summary);
				$sheet->fromArray($summary, null, 'A1', true);
			});
		})->download('xls');
	}

	private function splitVoucherData($sales)
	{
		$answer['voucher'] = (isset($answer['sales']) ? array_column($answer['sales'], 'voucher') : '-');

		if (isset($sales)) {
			$vouchers = array_column($sales, 'voucher');

			$temp = '';
			foreach ($vouchers as $voucher) {
				$temp .= (!empty($temp)) ? '|' . implode(';', $voucher) : implode(';', $voucher);
			}

			return (empty($temp)) ? '-' : $temp;
		}

		return '-';
	}

	private function splitColumnArray($array, $key, $delimiter)
	{
		$result = '-';
		if (isset($array)) {
			$temp = implode($delimiter, array_column($array, $key));
			$result = (count($temp) != count($array) - 1) ? $temp : '-';
 		}
		return (!empty($result)) ? $result : '-';
	}

	private function processSummary($summary, $sheetData, $userId, $salesAreaId)
	{
		if (count($summary) != count($sheetData)) {
			foreach ($sheetData as $index => $data) {
				foreach ($data as $key => $value) {
					$summary[$index][$key] = $value;
				}
			}
		} else {
			foreach ($sheetData as $index => $data) {
				foreach ($data as $key => $value) {
					if (!in_array($key, ['Nama', 'Area'])) {
						$summary[$index][$key] += $value;
					}
				}
			}
		}

		return $summary;
	}

	private function parseLeaderboardToArray($leaderboard, $userId, $salesAreaId)
	{
		$result = [];

		foreach ($leaderboard['data'] as $data) {
			if (
				($userId != 0 && $data['user']['id'] == $userId) ||
				($salesAreaId != 0 && $data['user']['sales_area']['id'] == $salesAreaId) ||
				($userId == 0 && $salesAreaId == 0)
			) {
				$row = [
					'Nama' => $data['user']['name'],
					'Area' => $data['user']['sales_area']['description'] ?? '-'
				];

				foreach ($leaderboard['columns'] as $key => $column) {
					$row[$column] = $data['kpis'][$key]['result'];
				}

				array_push($result, $row);
			}
		}

		return $result;
	}

	private function getUserIdsInArea($salesAreaId)
	{
		return array_column(User::where('sales_area_id', $salesAreaId)
			->select('id')
			->get()
			->toArray(), 'id');
	}

	private function getKpiSheetConfig($sheet, $data)
	{
		$sheet->cells('A1:F1', function($cells) {
			$cells->setFontWeight('bold');
		});
		$sheet->cells('A1:F' . (count($data) + 1), function($cells) {
			$cells->setBorder(array(
				'allborders' => array(
					'style' => \PHPExcel_Style_Border::BORDER_THIN,
					'color' => array('rgb' => '000000')
				)
			));
		});
		$sheet->setColumnFormat([
			'A2:F' . (count($data) + 1) => '#,##0'
		]);

		return $sheet;
	}

	private function getExcelConfig($excel)
	{
		$excel->setCreator("Hantze Sudarma")
			->setLastModifiedBy("Hantze Sudarma")
			->setTitle("Report Leaderboard")
			->setSubject("Report Leaderboard")
			->setDescription("Report Leaderboard")
			->setKeywords("Report Leaderboard")
			->setCategory("Report Leaderboard");

		return $excel;
	}
}