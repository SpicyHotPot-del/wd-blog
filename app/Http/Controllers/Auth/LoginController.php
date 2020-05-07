<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\AuthRequests;
use App\Services\AuthService;

class LoginController extends Controller
{
    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/admin';

	protected $maxLoginAttempts = 5; //每分钟最大尝试登录次数
	
	protected $lockoutTime = 300;  //登录锁定时间

	protected $AuthService;

	/**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(AuthService $AuthService)
    {
    	$this->AuthService = $AuthService;
        $this->middleware('guest')->except('logout');
    }

    public function showLoginForm()
    {
        return view('admin.auth.login');
    }

	public function login(AuthRequests $request)
	{
		$res = $this->AuthService->loginInfo($request);
		return $res;
	}

    public function loggedOut()
    {
        return redirect('/login');
    }
}
