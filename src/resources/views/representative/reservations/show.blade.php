@extends('layouts.app')

@section('title', '予約詳細')

@section('css')
<link rel="stylesheet" href="{{ asset('css/representative_dashboard.css') }}">
@endsection

@section('content')
<div class="representative-dashboard">
    <h1 class="representative-dashboard__title">予約詳細 #{{ $reservation->id }}</h1>
    
    <div class="representative-dashboard__card">
        <div class="representative-dashboard__card-header">
            <h5 class="representative-dashboard__card-title">予約情報</h5>
        </div>
        <div class="representative-dashboard__card-body">
            <div class="representative-dashboard__info-grid">
                <div class="representative-dashboard__info-item">
                    <p class="representative-dashboard__info-label">予約ID</p>
                    <p class="representative-dashboard__info-value">{{ $reservation->id }}</p>
                </div>
                <div class="representative-dashboard__info-item">
                    <p class="representative-dashboard__info-label">予約日</p>
                    <p class="representative-dashboard__info-value">{{ $reservation->date->format('Y年m月d日') }}</p>
                </div>
                <div class="representative-dashboard__info-item">
                    <p class="representative-dashboard__info-label">予約時間</p>
                    <p class="representative-dashboard__info-value">{{ $reservation->time->format('H:i') }}</p>
                </div>
                <div class="representative-dashboard__info-item">
                    <p class="representative-dashboard__info-label">人数</p>
                    <p class="representative-dashboard__info-value">{{ $reservation->number_of_people }}名</p>
                </div>
            </div>
        </div>
    </div>
    
    <div class="representative-dashboard__card">
        <div class="representative-dashboard__card-header">
            <h5 class="representative-dashboard__card-title">予約者情報</h5>
        </div>
        <div class="representative-dashboard__card-body">
            <div class="representative-dashboard__info-grid">
                <div class="representative-dashboard__info-item">
                    <p class="representative-dashboard__info-label">お名前</p>
                    <p class="representative-dashboard__info-value">{{ $reservation->user->name }}</p>
                </div>
                <div class="representative-dashboard__info-item">
                    <p class="representative-dashboard__info-label">メールアドレス</p>
                    <p class="representative-dashboard__info-value">{{ $reservation->user->email }}</p>
                </div>
                <div class="representative-dashboard__info-item">
                    <p class="representative-dashboard__info-label">電話番号</p>
                    <p class="representative-dashboard__info-value">{{ $reservation->user->phone ?? '未登録' }}</p>
                </div>
            </div>
        </div>
    </div>
    
    <div class="representative-dashboard__actions">
        <a href="{{ route('representative.reservations.index') }}" class="representative-dashboard__btn representative-dashboard__btn--secondary">予約一覧へ戻る</a>
    </div>
</div>
@endsection
