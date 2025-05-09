@extends('layouts.app')

@section('title', '登録')

@section('css')
<link rel="stylesheet" href="{{ asset('css/auth.css') }}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
@endsection

@section('content')
<div class="auth">
    <x-header-component />
    <div class="auth__container">
        <div class="auth__header">
            <h2 class="auth__title">Registration</h2>
        </div>
        <form method="POST" action="{{ route('register') }}" novalidate class="auth__form" autocomplete="off">
            @csrf
            <div class="auth__form-group">
                <label for="name" class="auth__form-label">
                    <span class="auth__icon auth__icon--name"></span>
                </label>
                <input
                    type="text"
                    id="name"
                    name="name"
                    placeholder="Username"
                    required
                    autofocus
                    class="auth__input auth__input--name"
                    autocomplete="username"
                >
            </div>
            @error('name')
                <div class="auth__error">{{ $message }}</div>
            @enderror

            <div class="auth__form-group">
                <label for="email" class="auth__form-label">
                    <span class="auth__icon auth__icon--email"></span>
                </label>
                <input
                    type="email"
                    id="email"
                    name="email"
                    placeholder="Email"
                    required
                    class="auth__input auth__input--email"
                    autocomplete="email"
                >
            </div>
            @error('email')
                <div class="auth__error">{{ $message }}</div>
            @enderror

            <div class="auth__form-group">
                <label for="password" class="auth__form-label">
                    <span class="auth__icon auth__icon--password"></span>
                </label>
                <div class="auth__password-container">
                    <input
                        type="password"
                        id="password"
                        name="password"
                        placeholder="Password"
                        required
                        class="auth__input auth__input--password"
                        autocomplete="new-password"
                    >
                    <span class="auth__toggle-password">
                        <i class="fa-solid fa-eye"></i>
                    </span>
                </div>
            </div>

            <div class="auth__form-group">
                <button type="submit" class="auth__submit-btn">登録</button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('js')
<script src="{{ asset('js/auth.js') }}"></script>
@endsection
