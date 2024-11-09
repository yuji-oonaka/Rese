@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/reservation_complete.css') }}">
@endsection

@section('content')
<x-header-component />
<div class="reservation-complete-container">
    <p>ご予約ありがとうございます</p>
    <a href="{{ route('shop.list') }}" class="btn">戻る</a>
</div>
@endsection