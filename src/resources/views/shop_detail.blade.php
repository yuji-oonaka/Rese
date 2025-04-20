@extends('layouts.app')

@section('title', '飲食店詳細')

@section('css')
<link rel="stylesheet" href="{{ asset('css/shop_detail.css') }}">
<link rel="stylesheet" href="{{ asset('css/shop_reviews.css') }}">
@endsection

@section('content')
<div class="shop-detail">
    <div class="shop-detail__left" id="content-left">
        <x-header-component />
        <div class="shop-detail__header">
            <a href="{{ route('shop.list') }}" class="shop-detail__back-btn">&lt;</a>
            <h2 class="shop-detail__title">{{ $shop->name }}</h2>
        </div>
        <img src="{{ $shop->image_url }}" alt="{{ $shop->name }}" class="shop-detail__image">
        <p class="shop-detail__tags">#{{ $shop->area->name }} #{{ $shop->genre->name }}</p>
        <p class="shop-detail__description">{{ $shop->description }}</p>

        <!-- 口コミボタン -->
        <a href="{{ route('shop.reviews', ['shop_id' => $shop->id]) }}" class="shop-detail__all-reviews-btn" id="review-button">
            全ての口コミ情報
        </a>

        @auth
            @if(!$hasReviewed)
                <a href="{{ route('review.create', ['shop_id' => $shop->id]) }}" class="shop-detail__review-link">
                    口コミを投稿する
                </a>
            @endif
        @endauth

        @if(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif
    </div>


    <div class="shop-detail__right">
        <h3 class="shop-detail__reservation-title">予約</h3>

        <!-- 予約フォーム -->
        <form id="reservation-form" method="POST" novalidate class="shop-detail__form">
            @csrf
            <!-- 日付選択 -->
            <input type="date" id="date-input" name="date" class="shop-detail__input shop-detail__input--date" value="{{ old('date', request('date')) }}" min="{{ date('Y-m-d') }}" required>
            @error('date')
                <span class="shop-detail__error">{{ $message }}</span>
            @enderror
            <!-- 時間選択 -->
            <input type="time" id="time-input" name="time" class="shop-detail__input shop-detail__input--time" value="{{ old('time', request('time')) }}" required>
            @error('time')
                <span class="shop-detail__error">{{ $message }}</span>
            @enderror
            <!-- 人数選択 -->
            <select id="people-input" name="number_of_people" class="shop-detail__input shop-detail__input--people" required>
                @for ($i = 0; $i <= 10; $i++)
                    <option value="{{ $i }}" {{ old('number_of_people',request('number_of_people')) == $i ? 'selected' : '' }}>{{ $i }}人</option>
                @endfor
            </select>
            @error('number_of_people')
                <span class="shop-detail__error">{{ $message }}</span>
            @enderror
            <!-- サマリー表示 -->
            <div class="shop-detail__summary">
                <table class="shop-detail__summary-table">
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
            <!-- 予約ボタン -->
            <button type="submit" formaction="{{ route('reservation.store', ['shop_id' => $shop->id]) }}" class="shop-detail__submit-btn">
                予約する
            </button>
        </form>
    </div>
</div>
@endsection

@section('js')
<script src="{{ asset('js/shop_detail.js') }}"></script>
@endsection
