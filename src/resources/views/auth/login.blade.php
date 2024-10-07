@extends('layouts.app')

@section('title', 'ログイン')

@section('content')
<div class="login-container">
    <div class="login-header">
        <h2>Login</h2>
    </div>
    <form method="POST" action="{{ route('login') }}">
        @csrf
        <div class="form-group">
            <label for="email">
                <span class="icon-email"></span>
            </label>
            <input type="email" id="email" name="email" placeholder="Email" required autofocus>
        </div>

        <div class="form-group">
            <label for="password">
                <span class="icon-password"></span>
            </label>
            <input type="password" id="password" name="password" placeholder="Password" required>
        </div>
        <div class="form-group">
            <button type="submit">ログイン</button>
        </div>
    </form>
</div>
@endsection