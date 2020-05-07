<?php


namespace App\Services;

use App\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;


class AuthRegService
{
	use ThrottlesLogins, RegistersUsers;

	public function reg($request)
	{
//		$this->throttleKey($request);
		$user = $this->addUser($request->all());
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