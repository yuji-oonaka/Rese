<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Laravel\Fortify\Http\Requests\LoginRequest as FortifyLoginRequest;

class LoginRequest extends FortifyLoginRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'email' => 'required',
            'email' => 'required|email:rfc,dns',
            'password' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'email.required' => 'メールアドレスのご入力をお願いします。',
            'email.email' => '有効なメールアドレスをご入力ください（例: example@example.com）。',
            'password.required' => 'パスワードのご入力をお願いします。',
        ];
    }
}