<?php


namespace App\Http\Controllers;


use App\Helpers\DateHelpers;
use App\Helpers\KpiHelpers;
use App\Helpers\SurveyHelpers;
use App\Models\Event;
use App\Models\EventAnswer;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ExcelController
{
	public function salesToExcel(Request $request)
	{
		$eventId = $request['event_id'];
		$from = $request['from'];
		$to = $request['to'];

		$result = [];

		$eventAnswers = EventAnswer::select('created_at as date', 'area', 'answer')
			->where('event_id', $eventId)
			->where('created_at', '>=', DateHelpers::getDateFromFormat($from)->format(config('constants.DATE_FORMAT.MYSQL')))
			->where('created_at', '<=', DateHelpers::getDateFromFormat($to)->format(config('constants.DATE_FORMAT.MYSQL')))
			->orderBy('date', 'desc')
			->get()
			->toArray();

		foreach ($eventAnswers as $answer) {
			if (isset($answer['answer']['sales'])) {
				foreach ($answer['answer']['sales'] as $sale) {
					array_push($result, [
						'date' => $answer['date'],
						'area' => $answer['area'],
						'number' => $sale['number'],
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
		$unset = [];

		$eventId = $request['event_id'];
		$from = $request['from'];
		$to = $request['to'];

		$eventAnswers = EventAnswer::select('created_at as date', 'area', 'answer')
			->where('event_id', $eventId)
			->where('created_at', '>=', DateHelpers::getDateFromFormat($from)->format(config('constants.DATE_FORMAT.MYSQL')))
			->where('created_at', '<=', DateHelpers::getDateFromFormat($to)->format(config('constants.DATE_FORMAT.MYSQL')))
			->orderBy('date', 'desc')
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
			$answer = array_merge($answer, $answer['answer']);
			unset($answer['answer']);

			$answer['number'] = strval($this->splitColumnArray($answer['sales'], 'number', "|"));
			$answer['package'] = $this->splitColumnArray($answer['sales'], 'package', "|");
			$answer['voucher'] = $this->splitVoucherData($answer['sales']);
			unset($answer['sales']);

			foreach ($answer as &$value) {
				if (is_array($value)) {
					$value = implode(";", $value);
				}
			}
			unset($value);

			foreach ($unset as $key) {
				unset($answer[$key]);
			}
		}
		unset($answer);

		Excel::create('Report-Answer-' . $from . '-' . $to, function ($excel) use ($eventAnswers) {
			$excel = $this->getExcelConfig($excel);

			$excel->sheet('Answer Summary', function ($sheet) use ($eventAnswers) {
				$sheet->fromArray($eventAnswers, null, 'A1', true);
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

		$event = Event::find($eventId)->toArray();
		$startDate = DateHelpers::getDateFromFormat($from);
		$endDate = DateHelpers::getDateFromFormat($to);

		while ($startDate->diffInDays($endDate) != 0) {
			$date = $startDate->format(config('constants.DATE_FORMAT.DEFAULT'));
			$data[$date] = KpiHelpers::getLeaderboardKpi($event, $date);

			$startDate->addDay(1);
		}

		Excel::create('Report-Leaderboard-Indosat-' . $from . '-' . $to, function ($excel) use ($data, &$summary) {
			$excel = $this->getExcelConfig($excel);

			foreach ($data as $date => $leaderboard) {
				$excel->sheet($date, function ($sheet) use (&$summary, $leaderboard) {
					$sheetData = $this->parseLeaderboardToArray($leaderboard);
					$summary = $this->processSummary($summary, $sheetData);

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
				$temp .= implode(';', $voucher) . "|";
			}
			return $temp;
		}

		return '-';
	}

	private function splitColumnArray($array, $key, $delimiter)
	{
		return (isset($array)) ? implode($delimiter, array_column($array, $key)) : '-';
	}

	private function processSummary($summary, $sheetData)
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

	private function parseLeaderboardToArray($leaderboard)
	{
		$result = [];

		foreach ($leaderboard['data'] as $data) {
			$row = [
				'Nama' => $data['user']['name'],
				'Area' => $data['user']['sales_area']['description'] ?? '-'
			];

			foreach ($leaderboard['columns'] as $key => $column) {
				$row[$column] = $data['kpis'][$key]['result'];
			}

			array_push($result, $row);
		}

		return $result;
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