@extends('layouts.app')

@section('title', 'ログイン')

@section('css')
<link rel="stylesheet" href="{{ asset('css/auth.css') }}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
@endsection

@section('content')
<div class="container">
    <x-header-component />
    <div class="login-container">
        <div class="login-header">
            <h2>Login</h2>
        </div>
        <form method="POST" action="{{ route('login') }}" novalidate>
            @csrf
            <div class="form-group">
                <label for="email">
                    <span class="icon-email"></span>
                </label>
                <input type="email" id="email" name="email" placeholder="Email" value="{{ old('email') }}" required autofocus>
            </div>
            @error('email')
                <div class="error-message">{{ $message }}</div>
            @enderror
            <div class="form-group">
                <label for="password">
                    <span class="icon-password"></span>
                </label>
                <div class="password-container">
                    <input type="password" id="password" name="password" placeholder="Password" required>
                    <span class="toggle-password">
                        <i class="fa-solid fa-eye"></i>
                    </span>
                </div>
            </div>
            @error('password')
                <div class="error-message">{{ $message }}</div>
            @enderror
            <div class="form-group">
                <button type="submit">ログイン</button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('js')
<script src="{{ asset('js/auth.js') }}"></script>
@endsection