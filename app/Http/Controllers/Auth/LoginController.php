<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\UserLocation;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LoginController extends Controller
{
	/*
	|--------------------------------------------------------------------------
	| Login Controller
	|--------------------------------------------------------------------------
	|
	| This controller handles authenticating users for the application and
	| redirecting them to your home screen. The controller uses a trait
	| to conveniently provide its functionality to your applications.
	|
	*/

	use AuthenticatesUsers;

	/**
	 * Where to redirect users after login.
	 *
	 * @var string
	 */
	protected $redirectTo = '/';

	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		$this->middleware('guest', ['except' => 'logout']);
	}

	public function showLoginForm()
	{
		return view("web.home");
	}

	protected function validateLogin(Request $request)
	{
		$this->validate($request, [
			$this->username() => 'required', 'password' => 'required', 'location' => 'required'
		]);
	}

	protected function sendLoginResponse(Request $request)
	{
		$request->session()->regenerate();

		$this->clearLoginAttempts($request);

		$this->saveLastLocation($request, 'login');

		return $this->authenticated($request, $this->guard()->user())
			?: redirect()->intended($this->redirectPath());
	}

	public function logout(Request $request)
	{
		$this->saveLastLocation($request, 'logout');

		$this->guard()->logout();

		$request->session()->flush();

		$request->session()->regenerate();

		return redirect('/');
	}

	private function saveLastLocation(Request $request, $state) {
		DB::transaction(function () use ($request, $state) {
			UserLocation::create([
				'user_id' => Auth::id(),
				'state' => $state,
				'location' => json_decode($request['location'], true)
			]);
		});
	}
}