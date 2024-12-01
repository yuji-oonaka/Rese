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
@endsection

@section('js')
<script src="{{ asset('js/shop_detail.js') }}"></script>
@endsection