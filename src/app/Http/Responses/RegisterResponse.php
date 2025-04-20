<?php

namespace App\Http\Responses;

use Laravel\Fortify\Contracts\RegisterResponse as RegisterResponseContract;

class RegisterResponse implements RegisterResponseContract
{
    public function toResponse($request)
    {
        // メール認証が必要なユーザーの場合
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->route('thanks');
        }

        // メール認証が必要な場合のリダイレクト
        return redirect()->route('verification.notice');
    }
}
