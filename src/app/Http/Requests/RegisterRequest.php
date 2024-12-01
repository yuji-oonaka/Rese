<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:191',
            'email' => 'required|email|unique:users|string|max:191',
            'password' => 'required|string|min:8|max:191',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'ユーザーネームをご入力ください。',
            'name.string' => 'ユーザーネームは文字列でお願いします。',
            'name.max' => 'ユーザーネームは191文字までとなります。',
            'email.required' => 'メールアドレスのご入力をお願いします。',
            'email.email' => 'メールアドレスの形式が正しくありません。ご確認ください。',
            'email.unique' => 'このメールアドレスは既に登録されています。',
            'email.string' => 'メールアドレスは文字列でお願いします。',
            'email.max' => 'メールアドレスは191文字までとなります。',
            'password.required' => 'パスワードのご入力をお願いします。',
            'password.string' => 'パスワードは文字列でお願いします。',
            'password.min' => 'パスワードは安全のため、8文字以上でお願いします。',
            'password.max' => 'パスワードは191文字までとなります。',
        ];
    }
}