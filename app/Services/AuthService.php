<?php


namespace App\Services;

use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use App\User;
use Illuminate\Foundation\Auth\ThrottlesLogins;


class AuthService
{
	use AuthenticatesUsers, AuthorizesRequests, RegistersUsers, ThrottlesLogins;

	public function loginInfo($request)
	{
		if (method_exists($this, 'hasTooManyLoginAttempts') &&
			$this->hasTooManyLoginAttempts($request)) {
			$this->fireLockoutEvent($request);
			Log::info('login success 1');
			return $this->sendLockoutResponse($request);
		}

		if ($this->attemptLogin($request)) {
			Log::info('login success 2');
			return $this->sendLoginResponse($request);
		}

		$this->incrementLoginAttempts($request);

		$res = $this->sendFailedLoginResponse($request);
		if (!$res) {
			Log::error('Username does not exist');
		}
		Log::info('login success 3');
		return $this;
	}

	public function reg($request)
	{
		$this->throttleKey($request);
		$this->validator($request->all())->validate();
		$user = $this->create($request->all());
		event(new Registered($user));
		$this->guard()->login($user);
		return $this->registered($request, $user)
			?: redirect($this->redirectPath());
	}

	public function addUser(array $data)
	{
		return User::create([
			'name' => $data['name'],
			'email' => $data['email'],
			'password' => Hash::make($data['password']),
		]);
	}
}