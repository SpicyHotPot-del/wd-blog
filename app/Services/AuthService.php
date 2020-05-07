<?php


namespace App\Services;

use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Log;


class AuthService
{
	use AuthenticatesUsers, AuthorizesRequests;

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
}