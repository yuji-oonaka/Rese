@extends('layouts.app')

@section('title', 'パスワード変更')

@section('css')
<link rel="stylesheet" href="{{ asset('css/representative_dashboard.css') }}">
@endsection

@section('content')
<div class="representative-dashboard">
    <x-header-component />
    <h1 class="representative-dashboard__title">パスワード変更</h1>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form method="POST" action="{{ route('representative.password.update') }}" class="representative-dashboard__form">
        @csrf

        <div class="representative-dashboard__form-group">
            <label for="current_password" class="representative-dashboard__label">現在のパスワード</label>
            <input type="password" name="current_password" id="current_password" class="representative-dashboard__input" required>
            @error('current_password')
                <div class="representative-dashboard__error">{{ $message }}</div>
            @enderror
        </div>

        <div class="representative-dashboard__form-group">
            <label for="password" class="representative-dashboard__label">新しいパスワード</label>
            <input type="password" name="password" id="password" class="representative-dashboard__input" required>
            @error('password')
                <div class="representative-dashboard__error">{{ $message }}</div>
            @enderror
        </div>

        <div class="representative-dashboard__form-group">
            <label for="password_confirmation" class="representative-dashboard__label">新しいパスワード（確認）</label>
            <input type="password" name="password_confirmation" id="password_confirmation" class="representative-dashboard__input" required>
        </div>

        <div class="representative-dashboard__actions">
            <button type="submit" class="representative-dashboard__btn representative-dashboard__btn--primary">パスワードを変更</button>
            <a href="{{ route('representative.dashboard') }}" class="representative-dashboard__btn representative-dashboard__btn--secondary">ダッシュボードへ戻る</a>
        </div>
    </form>
</div>
@endsection
