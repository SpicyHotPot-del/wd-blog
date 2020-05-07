<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Validator;

class AuthRequests extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
	        "email" => "required",
            "password" => "required",
            "captcha" => "required",
        ];
    }

	public function validator()
	{
		return Validator::make($this->postFillData(), [
			'captcha' => ['required', 'captcha'],
		], [
			'captcha.required' => '验证码不能为空',
			'captcha.captcha' => '请输入正确的验证码',
		]);
	}

	/**
	 * Return the fields and values to create a new post from
	 */
	public function postFillData()
	{
		return [
			'email' => $this->email,
			'password' => $this->password,
			'captcha' => $this->captcha,
		];
	}
}
