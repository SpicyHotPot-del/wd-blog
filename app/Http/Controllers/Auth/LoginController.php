<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

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
    protected $redirectTo = '/admin';

	protected $maxLoginAttempts = 5; //每分钟最大尝试登录次数
	
	protected $lockoutTime = 300;  //登录锁定时间

	/**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function showLoginForm()
    {
        return view('admin.auth.login');
    }

	protected function validator(array $data)
	{
//		return Validator::make($data, [
//			'password' => ['required', 'string', 'min:7', 'confirmed'],
//		]);
		return Validator::make($data, [
			'captcha' => ['required', 'captcha'],
		], [
			'captcha.required' => '验证码不能为空',
			'captcha.captcha' => '请输入正确的验证码',
		]);
	}

	public function login(Request $request)
	{
		$this->validateLogin($request);
		Log::error('Parameter verification failed');
		$validator = $this->validator($request->all());
//		if ($validator->fails()) {
//			Log::error('Verification code error');
//			echo 'Verification code error';die;
//		}

			// If the class is using the ThrottlesLogins trait, we can automatically throttle
		// the login attempts for this application. We'll key this by the username and
		// the IP address of the client making these requests into this application.
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

		// If the login attempt was unsuccessful we will increment the number of attempts
		// to login and redirect the user back to the login form. Of course, when this
		// user surpasses their maximum number of attempts they will get locked out.
		$this->incrementLoginAttempts($request);

		$res = $this->sendFailedLoginResponse($request);
		if (!$res) {
			Log::error('Username does not exist');
		}
		Log::info('login success 3');
		return $res;
	}

    public function loggedOut()
    {
        return redirect('/login');
    }
}
