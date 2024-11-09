@extends('layouts.app')

@section('title', '登録')

@section('css')
<link rel="stylesheet" href="{{ asset('css/auth.css') }}">

@section('content')
<div class="container">
    <x-header-component />
    <div class="register-container">
        <div class="register-header">
            <h2>Registration</h2>
        </div>
        <form method="POST" action="{{ route('register') }}" novalidate>
            @csrf
            <div class="form-group">
                <label for="name">
                    <span class="icon-name"></span>
                </label>
                <input type="text" id="name" name="name" placeholder="Username" required autofocus>
            </div>
            @error('name')
                <div class="error-message">{{ $message }}</div>
            @enderror
            <div class="form-group">
                <label for="email">
                    <span class="icon-email"></span>
                </label>
                <input type="email" id="email" name="email" placeholder="Email" required>
            </div>
            @error('email')
                <div class="error-message">{{ $message }}</div>
            @enderror
            <div class="form-group">
                <label for="password">
                    <span class="icon-password"></span>
                </label>
                <input type="password" id="password" name="password" placeholder="Password" required>
            </div>
            @error('password')
                <div class="error-message">{{ $message }}</div>
            @enderror
            <div class="form-group">
                <button type="submit">登録</button>
            </div>
        </form>
    </div>
</div>
@endsection