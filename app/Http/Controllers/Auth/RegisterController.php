<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\AuthRegRequests;
use App\Services\AuthService;

class RegisterController extends Controller
{
	/**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/admin';

	protected $maxLoginAttempts = 1; //每分钟最大尝试登录次数

	protected $lockoutTime = 60;  //登录锁定时间

	protected $authService;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(AuthService $authService)
    {
    	$this->authService = $authService;
        $this->middleware('guest');
    }

	protected function username()
	{
		return 'email';
	}

	/**
	 * Show the application registration form.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function showRegistrationForm()
	{
		return view('admin.auth.register');
	}

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        return $this->authService->addUser($data);
    }

	public function register(AuthRegRequests $request)
	{
		return $this->authService->reg($request);
	}
}
