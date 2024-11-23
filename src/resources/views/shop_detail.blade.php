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
            <input type="date" id="date-input" name="date" class="input date" value="{{ old('date', request('date')) }}" min="{{ date('Y-m-d') }}"required>
            @error('date')
                <span class="text-danger">{{ $message }}</span>
            @enderror
            <!-- 時間選択 -->
            <input type="time" id="time-input" name="time" class="input time" value="{{ old('time', request('time')) }}" required>
            @error('time')
                <span class="text-danger">{{ $message }}</span>
            @enderror
            <!-- 人数選択 -->
            <select id="people-input" name="number_of_people" class="input people" required>
                @for ($i = 0; $i <= 10; $i++)
                    <option value="{{ $i }}" {{ old('number_of_people',request('number_of_people')) == $i ? 'selected' : '' }}>{{ $i }}人</option>
                @endfor
            </select>
            @error('number_of_people')
                <span class="text-danger">{{ $message }}</span>
            @enderror
            <!-- サマリー表示 -->
            <div class="summary">
                <table class="summary-table">
                    <tr>
                        <td>Shop</td>
                        <td>{{ $shop->name }}</td>
                    </tr>
                    <tr>
                        <td>Date</td>
                        <td><span id="summary-date">{{ old('date', request('date')) }}</span></td>
                    </tr>
                    <tr>
                        <td>Time</td>
                        <td><span id="summary-time">{{ old('time', request('time')) }}</span></td>
                    </tr>
                    <tr>
                        <td>Number</td>
                        <td><span id="summary-people">{{ old('number_of_people', request('number_of_people')) }}</span>人</td>
                    </tr>
                </table>
            </div>
            <button type="submit" formaction="{{ route('reservation.store', ['shop_id' => $shop->id]) }}" class="submit-btn">
                予約する
            </button>
        </form>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    const dateInput = document.getElementById('date-input');
    const timeInput = document.getElementById('time-input');
    const peopleInput = document.getElementById('people-input');

    // 今日の日付を取得
    const today = new Date().toISOString().split('T')[0];
    dateInput.setAttribute('min', today);

    // 初期値をサマリーに反映
    if (dateInput.value) updateSummary('date', dateInput.value);
    if (timeInput.value) updateSummary('time', timeInput.value);
    if (peopleInput.value) updateSummary('people', peopleInput.value);

    // 時刻の制御を行う関数
    function updateTimeRestrictions() {
        const selectedDate = new Date(dateInput.value);
        const now = new Date();

        // 選択された日付が今日の場合
        if (selectedDate.toDateString() === now.toDateString()) {
            const hours = now.getHours();
            const minutes = now.getMinutes();

            // 現在時刻を "HH:MM" 形式に変換
            const currentTime = `${hours.toString().padStart(2, '0')}:${minutes.toString().padStart(2, '0')}`;

            // 最小時刻を設定
            timeInput.min = currentTime;

            // 既に選択されている時刻が現在時刻より前の場合、現在時刻にリセット
            if (timeInput.value && timeInput.value < currentTime) {
                timeInput.value = currentTime;
                updateSummary('time', currentTime);
            }
        } else {
            // 今日以外の日付の場合、時刻の制限を解除
            timeInput.removeAttribute('min');
        }
    }

    // イベントリスナーの設定
    dateInput.addEventListener('input', function() {
        updateSummary('date', this.value);
        updateTimeRestrictions();
    });

    timeInput.addEventListener('input', function() {
        updateSummary('time', this.value);
        updateTimeRestrictions();
    });

    peopleInput.addEventListener('change', function() {
        updateSummary('people', this.value );
    });

    // ページ読み込み時に初期チェック
    updateTimeRestrictions();

    // サマリー更新関数
    function updateSummary(type, value) {
        const element = document.getElementById(`summary-${type}`);
        if (element) {
            element.textContent = value;
        }
    }
});
</script>

@endsection