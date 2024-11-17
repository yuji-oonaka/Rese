@extends('layouts.app')

@section('title', '飲食店詳細')

@section('css')
<link rel="stylesheet" href="{{ asset('css/shop_detail.css') }}">
@endsection
@section('content')

<div class="main">
    <div class="left">
        <x-header-component />
        <div class="header">
            <a href="{{ route('shop.list') }}" class="back-btn">&lt;</a>
            <h2>{{ $shop->name }}</h2>
        </div>
        <img src="{{ $shop->image_url }}" alt="{{ $shop->name }}" class="shop-image">
        <p>#{{ $shop->area->name }} #{{ $shop->genre->name }}</p>
        <p>{{ $shop->description }}</p>
    </div>

    <div class="right">
        <h3>予約</h3>

        <!-- 予約フォーム -->
        <form id="reservation-form" method="POST" novalidate>
            @csrf
            <!-- 日付選択 -->
            <input type="date" id="date-input" name="date" class="input date" value="{{ request('date') }}" required>
            @if ($errors->has('date'))
                <span class="text-danger">{{ $errors->first('date') }}</span>
            @endif
            <!-- 時間選択 -->
            <input type="time" id="time-input" name="time" class="input time" value="{{ request('time') }}" required>
            @if ($errors->has('time'))
                <span class="text-danger">{{ $errors->first('time') }}</span>
            @endif
            <!-- 人数選択 -->
            <select id="people-input" name="number_of_people" class="input people" required>
                @for ($i = 0; $i <= 10; $i++)
                    <option value="{{ $i }}" {{ request('number_of_people') == $i ? 'selected' : '' }}>{{ $i }}人</option>
                @endfor
            </select>
            @if ($errors->has('number_of_people'))
                <span class="text-danger">{{ $errors->first('number_of_people') }}</span>
            @endif
            <!-- サマリー表示 -->
            <div class="summary">
                <table class="summary-table">
                    <tr>
                        <td>Shop</td>
                        <td>{{ $shop->name }}</td>
                    </tr>
                    <tr>
                        <td>Date</td>
                        <td><span id="summary-date">{{ request('date') }}</span></td>
                    </tr>
                    <tr>
                        <td>Time</td>
                        <td><span id="summary-time">{{ request('time') }}</span></td>
                    </tr>
                    <tr>
                        <td>Number</td>
                        <td><span id="summary-people">{{ request('number_of_people') }}</span>人</td>
                    </tr>
                </table>
            </div>
            <!-- 予約ボタン -->
            <button type="submit" formaction="{{ route('reservation.store', ['shop_id' => $shop->id]) }}" class="submit-btn">予約する</button>
        </form>
    </div>
</div>

<script>
// JavaScriptでフォームの選択内容をリアルタイムで取得し、サマリーを更新
document.getElementById('date-input').addEventListener('change', function() {
    document.getElementById('summary-date').textContent = this.value;
});

document.getElementById('time-input').addEventListener('change', function() {
    document.getElementById('summary-time').textContent = this.value;
});

document.getElementById('people-input').addEventListener('change', function() {
    document.getElementById('summary-people').textContent = this.value;
});

// 今日の日付以降しか選べないようにする
document.addEventListener("DOMContentLoaded", function() {
    const dateInput = document.getElementById('date-input');

    // 今日の日付を取得
    const today = new Date().toISOString().split('T')[0];

    // 日付フィールドの最小値（min）を今日の日付に設定
    dateInput.setAttribute('min', today);
});
</script>

@endsection