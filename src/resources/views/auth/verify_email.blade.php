@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/auth_verify.css') }}">
@endsection

@section('content')
<div class="auth-verify-bg">
    <div class="auth-verify-container">
        <x-header-component />
        <div class="auth-verify-header">
            <span>メールアドレスを認証してください</span>
        </div>
        <div class="auth-verify-message">
            <p>
                登録したメールアドレス宛に認証メールを送信しました。<br>
                メール内のリンクをクリックして認証を完了してください。
            </p>
            @if (session('status') == 'verification-link-sent')
                <div class="auth-verify-alert success">
                    新しい認証メールを送信しました！
                </div>
            @endif
            <form method="POST" action="{{ route('verification.send') }}">
                @csrf
                <button type="submit" class="auth-verify-btn">
                    認証メールを再送信
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
