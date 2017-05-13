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
        $salesAreas = SalesArea::select('id', 'description as area')
			->get()
            ->toArray();

		$data = [
			'id' => 'area-table',
			'columns' => array(),
			'values' => $salesAreas,
            'actions' => true
		];

		if (count($data['values']) > 0) {
			$data['columns'] = TableHelpers::getColumns($data['values'][0], ['id']);
			foreach ($data['values'] as &$value) {
                $value['actions'] = '<a href="' . route('edit-area', ['id' => $value['id']]) . '" class="btn btn-info btn-xs"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Ubah</a> <form method="POST" action="' . route('delete-area', ['id' => $value['id']]) . '" class="inline">' . csrf_field() . '<input name="_method" type="hidden" value="DELETE"><button type="submit" class="btn btn-danger btn-xs"><i class="fa fa-times" aria-hidden="true"></i> Hapus</button></form>';

                unset($value['id']);

                $value = array_values($value);
            }
            unset($value);
		}

        $data['values'] = json_encode($data['values']);

		return view('admin.area.list', ['page' => 'area', 'data' => $data]);
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