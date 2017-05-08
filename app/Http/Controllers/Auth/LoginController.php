<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\User;
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
			$this->username() => 'required',
			'password' => 'required',
			'location' => 'required',
			'auth_code' => 'required',
			'area' => 'required'
		]);
	}

	protected function sendLoginResponse(Request $request)
	{
		$request->session()->regenerate();

		$this->clearLoginAttempts($request);

		$this->getCurrentEvent($request);

		$this->saveLocation($request, 'login');

		return $this->authenticated($request, $this->guard()->user())
			?: (Auth::user()->is_admin == 0) ? redirect()->intended($this->redirectPath()) : (Auth::user()->is_admin == 1) ? redirect()->route('sales') : redirect()->route('dashboard');
	}

	public function login(Request $request)
	{
		$this->validateLogin($request);

		// If the class is using the ThrottlesLogins trait, we can automatically throttle
		// the login attempts for this application. We'll key this by the username and
		// the IP address of the client making these requests into this application.
		if ($this->hasTooManyLoginAttempts($request)) {
			$this->fireLockoutEvent($request);

			return $this->sendLockoutResponse($request);
		}
		if ($this->isEventExists($request)) {
			if ($this->attemptLogin($request)) {
				return $this->sendLoginResponse($request);
			}
		} else {
			return redirect()->back()
				->withErrors(['auth_code' => 'Kode otentikasi yang anda masukkan salah'])
				->withInput();
		}

		// If the login attempt was unsuccessful we will increment the number of attempts
		// to login and redirect the user back to the login form. Of course, when this
		// user surpasses their maximum number of attempts they will get locked out.
		$this->incrementLoginAttempts($request);

		return $this->sendFailedLoginResponse($request);
	}

	public function logout(Request $request)
	{
		$this->saveLocation($request, 'logout');

		$this->guard()->logout();

		$request->session()->flush();

		$request->session()->regenerate();

		return redirect('/');
	}

	private function isEventExists(Request $request) {
		$event = Event::where('auth_code', $request['auth_code'])->count();
		return $event == 0 ? false : true;
	}

	private function getCurrentEvent(Request $request) {
		DB::transaction(function () use ($request) {
			$event = Event::where('auth_code', $request['auth_code'])
				->orderBy('created_at', 'desc')
				->first();

			$user = Auth::user();
			$user->event_id = $event->id;
			$user->save();
		});
	}

	private function saveLocation(Request $request, $state) {
		DB::transaction(function () use ($request, $state) {
			$user = Auth::user();

			$user->area = ($state == 'login') ? $request['area'] : null;
			$user->save();

			UserLocation::create([
				'user_id' => Auth::id(),
				'state' => $state,
				'location' => json_decode($request['location'], true)
			]);
		});
	}
}