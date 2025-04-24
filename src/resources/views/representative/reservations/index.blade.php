@extends('layouts.app')

@section('title', '予約一覧')

@section('css')
<link rel="stylesheet" href="{{ asset('css/representative_dashboard.css') }}">
@endsection

@section('content')
<div class="representative-dashboard">
    <x-header-component />
    <h1 class="representative-dashboard__title">{{ $shop->name }} 予約一覧</h1>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="representative-dashboard__card">
        <div class="representative-dashboard__card-header">
            <h5 class="representative-dashboard__card-title">現在の予約状況</h5>
        </div>
        <div class="representative-dashboard__card-body">
            @if($reservations->isEmpty())
                <p class="representative-dashboard__empty-message">現在予約はありません。</p>
            @else
                <div class="representative-dashboard__table-wrapper">
                    <table class="representative-dashboard__table">
                        <thead>
                            <tr>
                                <th>予約ID</th>
                                <th>予約者名</th>
                                <th>予約日</th>
                                <th>予約時間</th>
                                <th>人数</th>
                                <th>ステータス</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($reservations as $reservation)
                                <tr>
                                    <td>{{ $reservation->id }}</td>
                                    <td>{{ $reservation->user->name }}</td>
                                    <td>{{ $reservation->date->format('Y年m月d日') }}</td>
                                    <td>{{ $reservation->time->format('H:i') }}</td>
                                    <td>{{ $reservation->number_of_people }}名</td>
                                    <td>
                                        <a href="{{ route('representative.reservations.show', $reservation) }}" class="representative-dashboard__btn representative-dashboard__btn--small">詳細</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="pagination-container">
                    {{ $reservations->links() }}
                </div>
            @endif
        </div>
    </div>
    
    <div class="representative-dashboard__actions">
        <a href="{{ route('representative.dashboard') }}" class="representative-dashboard__btn representative-dashboard__btn--secondary">ダッシュボードへ戻る</a>
    </div>
</div>
@endsection
