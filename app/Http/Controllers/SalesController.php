<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class SalesController extends Controller
{
	public function storeValidator($data) {
		return Validator::make($data, [
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
			'name' => 'max:255',
			'email' => 'email|max:255',
			'password' => 'min:4|confirmed',
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

	public function index() {
		$sales = User::all()->toArray();
		$data = [
			'id' => 'sales-table',
			'columns' => array(),
			'values' => $sales,
			'edit' => 'edit-sales',
			'destroy' => 'delete-sales'
		];
		if (count($sales) > 0) {
			$data['columns'] = array_diff(array_keys($sales[0]), ['id']);
			foreach ($data['values'] as &$value) {
				$value['gender'] = ucfirst($value['gender']);
				$value['balance'] = 'Rp. ' . number_format($value['balance']);
			}
		}

		return view('admin.sales.list', ['page' => 'sales', 'data' => $data]);
	}

	public function show($id) {

	}

	public function create() {
		return view("admin.sales.create", ['page' => 'create-sales']);
	}

	public function edit($id) {
		$sales = User::find($id)->toArray();

		return view('admin.sales.edit', ['page' => 'sales', 'data' => $sales]);
	}

	public function editBalance() {
		$sales = User::select('id as key', 'name as text')->get()->toArray();

		return view('admin.sales.balance', ['page' => 'sales-balance', 'data' => $sales]);
	}

	public function store(Request $request) {
		$data = $request->only([
			'name', 'email', 'password', 'password_confirmation', 'gender', 'phone', 'balance'
		]);

		$validator = $this->storeValidator($data);

		if ($validator->fails()) {
			return redirect()->back()
				->withErrors($validator)
				->withInput();
		}

		DB::transaction(function () use ($data) {
			unset($data['password-confirmation']);
			User::create($data);
		});

		return redirect()->route('sales');
	}

	public function updateBalance(Request $request) {
		$data = $request->only([
			'id', 'balance'
		]);

		$validator = $this->updateValidator($data);

		if ($validator->fails()) {
			return redirect()->back()
				->withErrors($validator)
				->withInput();
		}

		DB::transaction(function () use ($data) {
			$user = User::find($data['id']);
			$user->balance += $data['balance'];
			$user->save();

			print_r($user);
		});

		return redirect()->route('sales');
	}

	public function update($id, Request $request) {
		$data = $request->only([
			'name', 'email', 'gender', 'phone', 'balance'
		]);

		$validator = $this->updateValidator($data);

		if ($validator->fails()) {
			return redirect()->back()
				->withErrors($validator)
				->withInput();
		}

		DB::transaction(function () use ($id, $data) {
			$user = User::find($id);

			$user->name = $data['name'] ?? $user->name;
			$user->email = $data['email'] ?? $user->email;
			$user->password = $data['password'] ?? $user->password;
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
