@extends('layouts.app')

@section('title', '登録')

@section('content')
<div class="register-container">
    <div class="register-header">
        <h2>Registration</h2>
    </div>
    <form method="POST" action="{{ route('register') }}">
        @csrf
        <div class="form-group">
            <label for="name">
                <span class="icon-name"></span>
            </label>
            <input type="text" id="name" name="name" placeholder="Username" required autofocus>
        </div>
        <div class="form-group">
            <label for="email">
                <span class="icon-email"></span>
            </label>
            <input type="email" id="email" name="email" placeholder="Email" required>
        </div>
        <div class="form-group">
            <label for="password">
                <span class="icon-password"></span>
            </label>
            <input type="password" id="password" name="password" placeholder="Password" required>
        </div>
        <div class="form-group">
            <button type="submit">登録</button>
        </div>
    </form>
</div>
@endsection