<?php

namespace App\Http\Controllers;

use App\Helpers\TableHelpers;
use App\Models\NumberList;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class NumberListController extends Controller
{
	public function storeValidator($data) {
		return Validator::make($data, [
			'number' => "required|regex:/^[(0-9)\r\n]+$/"
		]);
	}

    public function index() {
		$numbers = NumberList::select('id', 'number', 'is_taken as taken')
			->orderBy('is_taken', 'asc')
			->get()
			->toArray();
	    $data = [
		    'id' => 'number-table',
		    'columns' => array(),
		    'values' => $numbers,
		    'destroy' => 'delete-number'
	    ];
	    if (count($numbers) > 0) {
			$data['columns'] = TableHelpers::getColumns($numbers[0], ['id']);
			foreach ($data['values'] as &$value) {
				$value['taken'] = ($value['taken']) ? 'YES' : 'NO';
			}
	    }

	    return view('admin.number.list', ['page' => 'number', 'data' => $data]);
    }

    public function create() {
	    return view("admin.number.create", ['page' => 'create-number']);
    }

    public function store(Request $request) {
		$data = $request->only([
			'number'
		]);

		$validator = $this->storeValidator($data);

		if ($validator->fails()) {
			return redirect()->back()
				->withErrors($validator)
				->withInput();
		}

	    DB::transaction(function () use ($data) {
			$numbers = explode("\r\n", $data['number']);
			foreach ($numbers as $number) {
				if (!empty($number)) {
					NumberList::create(['number' => $number]);
				}
			}
	    });

	    return redirect()->route('number');
    }

    public function destroy($id) {
	    DB::transaction(function () use ($id) {
		    NumberList::destroy($id);
	    });

	    return redirect()->route('number');
    }
}
