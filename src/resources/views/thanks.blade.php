@extends('layouts.app')

@section('title', '登録完了')

@section('css')
<link rel="stylesheet" href="{{ asset('css/thanks.css') }}">
@endsection

@section('content')
<div class="thank-you-container">
    <p>会員登録ありがとうございます</p>
    <a href="{{ route('login') }}" class="btn btn-primary">ログインする</a>
</div>
@endsection