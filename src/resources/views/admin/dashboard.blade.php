@extends('layouts.app')

@section('title', '管理者ダッシュボード')

@section('content')
<div class="container">
    <h1>管理者ダッシュボード</h1>
    <div class="stats">
        <div class="stat">
            <h3>代表者数</h3>
            <p>{{ $representativesCount }}人</p>
        </div>
        <div class="stat">
            <h3>店舗数</h3>
            <p>{{ $shopsCount }}店舗</p>
        </div>
        <div class="stat">
            <h3>予約数</h3>
            <p>{{ $reservationsCount }}件</p>
        </div>
    </div>
</div>
@endsection
