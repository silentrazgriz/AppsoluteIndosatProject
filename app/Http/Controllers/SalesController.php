<?php

namespace App\Http\Controllers;

use App\Helpers\TableHelpers;
use App\Models\BalanceHistory;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class SalesController extends Controller
{
	public function storeValidator($data) {
		return Validator::make($data, [
			'sales_area_id' => 'required|exists:sales_areas,id',
			'name' => 'required|max:255',
			'email' => 'required|email|max:255|unique:users',
			'password' => 'required|min:4|confirmed',
			'gender' => 'required',
			'phone' => 'required|numeric',
			'balance' => 'required|numeric'
		]);
	}

	public function updateValidator($data) {
		return Validator::make($data, [
			'sales_area_id' => 'exists:sales_areas,id',
			'name' => 'max:255',
			'email' => 'email|max:255',
			'gender' => '',
			'phone' => 'numeric',
			'balance' => 'numeric'
		]);
	}

	public function updateBalanceValidator($data) {
		return Validator::make($data, [
			'id' => 'exists:users,id',
			'balance' => 'numeric'
		]);
	}

	public function updateSalesBalanceValidator($data) {
		return Validator::make($data, [
			'balance' => 'numeric'
		]);
	}

	public function index() {
		$data = [
			'id' => 'sales-table',
			'columns' => array(),
			'values' => User::orderBy('email', 'asc')
				->leftJoin('sales_areas', 'sales_areas.id', '=', 'sales_area_id')
				->select('users.id', 'email', 'name', 'gender', 'description as area', 'phone', 'balance')
				->get()
				->toArray(),
			'edit' => 'edit-sales',
			'destroy' => 'delete-sales'
		];
		if (count($data['values']) > 0) {
			$data['columns'] = TableHelpers::getColumns($data['values'][0], ['id']);
			foreach ($data['values'] as &$value) {
				$value['gender'] = ucfirst($value['gender']);
				$value['balance'] = 'Rp. ' . number_format($value['balance']);
			}
			unset($value);
		}

		return view('admin.sales.list', ['page' => 'sales', 'data' => $data]);
	}

	public function create() {
		return view("admin.sales.create", ['page' => 'create-sales']);
	}

	public function edit($id) {
		$sales = User::find($id)->toArray();

		return view('admin.sales.edit', ['page' => 'sales', 'data' => $sales]);
	}

	public function editBalance() {
		$sales = User::select('id as key', 'email as text')->get()->toArray();

		return view('admin.sales.balance', ['page' => 'sales-balance', 'data' => $sales]);
	}

	public function store(Request $request) {
		$data = $request->only([
			'sales_area_id', 'name', 'email', 'password', 'password_confirmation', 'gender', 'phone', 'balance'
		]);

		$validator = $this->storeValidator($data);

		if ($validator->fails()) {
			return redirect()->back()
				->withErrors($validator)
				->withInput();
		}

		DB::transaction(function () use ($data) {
			unset($data['password-confirmation']);
			$data['password'] = bcrypt($data['password']);
			User::create($data);
		});

		return redirect()->route('sales');
	}

	public function updateSalesBalance(Request $request) {
		$data = $request->only([
			'balance'
		]);

		$validator = $this->updateSalesBalanceValidator($data);

		if ($validator->fails()) {
			return redirect()->back()
				->withErrors($validator)
				->withInput();
		}

		DB::transaction(function () use ($data) {
			$user = User::find(Auth::id());
			$user->balance += $data['balance'];
			$user->save();

			BalanceHistory::create([
				'user_id' => Auth::id(),
				'balance' => $data['balance'],
				'added_by_admin' => false
			]);
		});

		return redirect()->route('home');
	}

	public function updateBalance(Request $request) {
		$data = $request->only([
			'id', 'balance'
		]);

		$validator = $this->updateBalanceValidator($data);

		if ($validator->fails()) {
			return redirect()->back()
				->withErrors($validator)
				->withInput();
		}

		DB::transaction(function () use ($data) {
			$user = User::find($data['id']);
			$user->balance += $data['balance'];
			$user->save();

			BalanceHistory::create([
				'user_id' => $data['id'],
				'balance' => $data['balance'],
				'added_by_admin' => true
			]);
		});

		return redirect()->route('sales');
	}

	public function update($id, Request $request) {
		$data = $request->only([
			'sales_area_id', 'name', 'email', 'gender', 'phone', 'balance'
		]);

		$validator = $this->updateValidator($data);

		if ($validator->fails()) {
			return redirect()->back()
				->withErrors($validator)
				->withInput();
		}


		DB::transaction(function () use ($id, $data) {
			$user = User::find($id);

			$user->sales_area_id = $data['sales_area_id'] ?? $user->sales_area_id;
			$user->name = $data['name'] ?? $user->name;
			$user->email = $data['email'] ?? $user->email;
			$user->gender = $data['gender'] ?? $user->gender;
			$user->phone = $data['phone'] ?? $user->phone;
			$user->balance = $data['balance'] ?? $user->balance;

			$user->save();
		});

		return redirect()->route('sales');
	}

	public function destroy($id) {
		DB::transaction(function () use ($id) {
			User::destroy($id);
		});

		return redirect()->route('sales');
	}
}
