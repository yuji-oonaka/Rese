@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/auth.css') }}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
@endsection

@section('content')
<div class="auth">
    <div class="auth__container">
        <div class="auth__header">
            <h3>メールアドレスを認証してください</h3>
        </div>
        <div class="auth__verify-message">
            <p class="auth__verify-text">
                登録したメールアドレス宛に認証メールを送信しました。<br>
                メール内のリンクをクリックして認証を完了してください。
            </p>
            @if (session('status') == 'verification-link-sent')
                <div class="auth__alert alert-success">
                    新しい認証メールを送信しました！
                </div>
            @endif
            <form method="POST" action="{{ route('verification.send') }}">
                @csrf
                <div class="auth__form-group">
                    <button type="submit" class="auth__submit-btn">
                        認証メールを再送信
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
