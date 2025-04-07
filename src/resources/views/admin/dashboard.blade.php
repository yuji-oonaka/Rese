@extends('layouts.app')

@section('title', '管理者ダッシュボード')

@section('css')
<link rel="stylesheet" href="{{ asset('css/admin_dashboard.css') }}">
@endsection

@section('content')
<div class="admin-dashboard">
    <h1 class="admin-dashboard__title">管理者ダッシュボード</h1>
    <div class="admin-dashboard__stats">
        <div class="admin-dashboard__stat">
            <h3 class="admin-dashboard__stat-title">代表者数</h3>
            <p class="admin-dashboard__stat-value">{{ $representativesCount }}人</p>
        </div>
        <div class="admin-dashboard__stat">
            <h3 class="admin-dashboard__stat-title">店舗数</h3>
            <p class="admin-dashboard__stat-value">{{ $shopsCount }}店舗</p>
        </div>
        <div class="admin-dashboard__stat">
            <h3 class="admin-dashboard__stat-title">予約数</h3>
            <p class="admin-dashboard__stat-value">{{ $reservationsCount }}件</p>
        </div>
    </div>

    <!-- アクションエリア -->
    <div class="admin-dashboard__actions">
        <!-- 店舗作成ページへのリンク -->
        <a href="{{ route('shops.create') }}" class="admin-dashboard__btn admin-dashboard__btn--primary">新しい店舗を作成する</a>

        <a href="{{ route('shops.index') }}" class="admin-dashboard__btn admin-dashboard__btn--primary">店舗一覧へ</a>

        <!-- Topページへのリンク -->
        <a href="{{ route('shop.list') }}" class="admin-dashboard__btn admin-dashboard__btn--secondary">Topページへ戻る</a>
    </div>
</div>
@endsection
