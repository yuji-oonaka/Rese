@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="{{ asset('css/shop_detail.css') }}">

<div class="main">
    <div class="left">
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
        <form id="reservation-form" method="POST">
            @csrf
            <!-- 日付選択 -->
            <input type="date" id="date-input" name="date" class="input" value="{{ request('date') }}" required>

            <!-- 時間選択 -->
            <input type="time" id="time-input" name="time" class="input" value="{{ request('time') }}" required>

            <!-- 人数選択 -->
            <select id="people-input" name="number_of_people" class="input" required>
                @for ($i = 1; $i <= 10; $i++)
                    <option value="{{ $i }}" {{ request('number_of_people') == $i ? 'selected' : '' }}>{{ $i }}人</option>
                @endfor
            </select>

            <!-- サマリー表示 -->
            <div class="summary">
                <p>Shop: {{ $shop->name }}</p>
                <p>Date: <span id="summary-date">{{ request('date') }}</span></p>
                <p>Time: <span id="summary-time">{{ request('time') }}</span></p>
                <p>Number: <span id="summary-people">{{ request('number_of_people') }}</span>人</p>
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
</script>

@endsection