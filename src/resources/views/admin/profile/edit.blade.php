@extends('layouts.app')

@section('title', '管理者プロフィール編集')

@section('css')
<link rel="stylesheet" href="{{ asset('css/admin_profile_edit.css') }}">
@endsection

@section('content')
<div class="container">
    <x-header-component />
    <div class="admin-profile-edit">
        <h1>管理者プロフィール編集</h1>
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        {{-- 名前・メールアドレス編集フォーム --}}
        <form method="POST" action="{{ route('admin.profile.update') }}">
            @csrf
            <div>
                <label>名前</label>
                <input type="text" name="name" value="{{ old('name', $admin->name) }}" required>
                @error('name')<div class="text-danger">{{ $message }}</div>@enderror
            </div>
            <div>
                <label>メールアドレス</label>
                <input type="email" name="email" value="{{ old('email', $admin->email) }}" required>
                @error('email')<div class="text-danger">{{ $message }}</div>@enderror
            </div>
            <button type="submit">プロフィールを変更</button>
        </form>

        <hr>

        {{-- パスワード変更フォーム --}}
        <form method="POST" action="{{ route('admin.profile.password') }}">
            @csrf
            <div>
                <label>新しいパスワード</label>
                <input type="password" name="password" required>
                @error('password')<div class="text-danger">{{ $message }}</div>@enderror
            </div>
            <div>
                <label>新しいパスワード（確認）</label>
                <input type="password" name="password_confirmation" required>
            </div>
            <button type="submit">パスワードを変更</button>
        </form>
    </div>
</div>
@endsection
