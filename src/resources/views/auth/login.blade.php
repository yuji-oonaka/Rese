@extends('layouts.app')

@section('title', 'ログイン')

@section('css')
<link rel="stylesheet" href="{{ asset('css/auth.css') }}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
@endsection

@section('content')
<div class="auth">
    <x-header-component />
    <div class="auth__container">
        <div class="auth__header">
            <h2 class="auth__title">Login</h2>
        </div>
        <form method="POST" action="{{ route('login') }}" novalidate class="auth__form">
            @csrf
            <div class="auth__form-group">
                <label for="email" class="auth__form-label">
                    <span class="auth__icon auth__icon--email"></span>
                </label>
                <input type="email" id="email" name="email" placeholder="Email" value="{{ old('email') }}" required autofocus class="auth__input auth__input--email">
            </div>
            @error('email')
                <div class="auth__error">{{ $message }}</div>
            @enderror
            <div class="auth__form-group">
                <label for="password" class="auth__form-label">
                    <span class="auth__icon auth__icon--password"></span>
                </label>
                <div class="auth__password-container">
                    <input type="password" id="password" name="password" placeholder="Password" required class="auth__input auth__input--password">
                    <span class="auth__toggle-password">
                        <i class="fa-solid fa-eye"></i>
                    </span>
                </div>
            </div>
            @error('password')
                <div class="auth__error">{{ $message }}</div>
            @enderror
            <div class="auth__form-group">
                <button type="submit" class="auth__submit-btn">ログイン</button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('js')
<script src="{{ asset('js/auth.js') }}"></script>
@endsection
