@extends('layouts.app')

@section('title', '登録完了')

@section('css')
<link rel="stylesheet" href="{{ asset('css/thanks.css') }}">
@endsection

@section('content')
<div class="container">
    <x-header-component />
    <div class="thank-you-container">
        <p>会員登録ありがとうございます</p>
        <a href="{{ route('mypage') }}" class="btn btn-primary">ログインする</a>
    </div>
</div>
@endsection