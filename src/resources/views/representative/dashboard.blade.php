@extends('layouts.app')

@section('title', '代表者ダッシュボード')

@section('css')
<link rel="stylesheet" href="{{ asset('css/representative_dashboard.css') }}">
@endsection

@section('content')
<div class="representative-dashboard">
    <x-header-component />
    <h1 class="representative-dashboard__title">{{ $shop->name }} 管理ダッシュボード</h1>
    
    <div class="representative-dashboard__card">
        <div class="representative-dashboard__card-body">
            <h5 class="representative-dashboard__card-title">基本情報</h5>
            <p class="representative-dashboard__card-text">{{ $shop->description }}</p>
            <p class="representative-dashboard__card-text representative-dashboard__card-text--small">
                エリア: {{ $shop->area->name }} / ジャンル: {{ $shop->genre->name }}
            </p>
        </div>
    </div>

    <div class="representative-dashboard__actions">
        <a href="{{ route('representative.shops.edit', $shop) }}" class="representative-dashboard__btn representative-dashboard__btn--primary">
            店舗情報を編集
        </a>
        <a href="{{ route('representative.reservations.index') }}" class="representative-dashboard__btn representative-dashboard__btn--primary">
            予約状況を確認
        </a>
        <a href="{{ route('representative.password.edit') }}" class="representative-dashboard__btn representative-dashboard__btn--primary">パスワード変更</a>
        <a href="{{ route('shop.list') }}" class="representative-dashboard__btn representative-dashboard__btn--secondary">Topページへ戻る</a>
    </div>
</div>
@endsection
