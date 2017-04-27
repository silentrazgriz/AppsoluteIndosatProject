<?php


namespace App\Http\Controllers;


use App\Helpers\TableHelpers;
use App\Models\SalesArea;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class SalesAreaController
{
	public function storeValidator($data) {
		return Validator::make($data, [
			'description' => 'required|max:255'
		]);
	}

	public function updateValidator($data) {
		return Validator::make($data, [
			'description' => 'exists:max:255'
		]);
	}

	public function index()
	{
		$salesArea = SalesArea::select('id', 'description as area')
			->paginate(config('constants.ITEM_PER_PAGE'));

		$data = [
			'salesAreas' => $salesArea,
			'id' => 'area-table',
			'columns' => array(),
			'values' => $salesArea->toArray()['data'],
			'edit' => 'edit-area',
			'destroy' => 'delete-area'
		];

		if (count($data['values']) > 0) {
			$data['columns'] = TableHelpers::getColumns($data['values'][0], ['id']);
		}

		return view('admin.area.list', ['page' => 'area', 'data' => $data, 'paginate' => true]);
	}

	public function create()
	{
		return view("admin.area.create", ['page' => 'create-area']);
	}

	public function edit($id)
	{
		$area = SalesArea::find($id)->toArray();

		return view('admin.area.edit', ['page' => 'area', 'data' => $area]);
	}

	public function store(Request $request)
	{
		$data = $request->only([
			'description'
		]);

		$validator = $this->storeValidator($data);

		if ($validator->fails()) {
			return redirect()->back()
				->withErrors($validator)
				->withInput();
		}

		DB::transaction(function () use ($data) {
			SalesArea::create($data);
		});

		return redirect()->route('area');
	}

	public function update($id, Request $request)
	{
		$data = $request->only([
			'description'
		]);

		$validator = $this->updateValidator($data);

		if ($validator->fails()) {
			return redirect()->back()
				->withErrors($validator)
				->withInput();
		}

		DB::transaction(function () use ($id, $data) {
			$salesArea = SalesArea::find($id);

			$salesArea->description = $data['description'];
			$salesArea->save();
		});

		return redirect()->route('area');
	}

	public function destroy($id)
	{
		DB::transaction(function () use ($id) {
			SalesArea::destroy($id);
		});

		return redirect()->route('area');
	}
}