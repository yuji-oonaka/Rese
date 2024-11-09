@extends('layouts.app')

@section('title', 'マイページ')

@section('css')
<link rel="stylesheet" href="{{ asset('css/mypage.css') }}">
@endsection

@section('content')
<div class="container">
    <div class="header-row">
        <x-header-component />
        <h1 class="user-name">{{ auth()->user()->name }}さん</h1>
    </div>
    <div class="content-row">
        <!-- 左側: 予約状況 -->
        <div class="left-section">
            <h2>予約状況</h2>
            @foreach($reservations as $index => $reservation)
                <div class="reservation-card">
                    <div class="reservation-header">
                        <!-- 時計アイコン -->
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-clock">
                            <circle cx="12" cy="12" r="10"></circle>
                            <polyline points="12 6 12 12 16 14"></polyline>
                        </svg>
                        <!-- 予約番号 -->
                        <p>予約{{ $index + 1 }}</p>
                        <!-- 削除ボタン -->
                        <form action="{{ route('reservation.delete', $reservation->id) }}" method="POST" class="delete-form">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="delete-btn" onclick="return confirm('この予約を削除してもよろしいですか？')">×</button>
                        </form>
                    </div>
                    @if($reservation->shop)
                        <p>Shop {{ $reservation->shop->name }}</p>
                        <p>Date {{ $reservation->date }}</p>
                        <p>Time {{ $reservation->time }}</p>
                        <p>Number {{ $reservation->number_of_people }}人</p>
                    @else
                        <p>この予約には店舗情報がありません。</p>
                    @endif
                </div>
            @endforeach
        </div>

        <!-- 右側: お気に入り店舗 -->
        <div class="right-section">
            <h2>お気に入り店舗</h2>
            <div class="favorite-grid">
                @foreach($favorites as $favorite)
                    <div class="favorite-card">
                        @if($favorite->shop)
                            <img src="{{ $favorite->shop->image_url }}" alt="{{ $favorite->shop->name }}">
                            <p>{{ $favorite->shop->name }}</p>
                            <p>#{{ $favorite->shop->area->name }} #{{ $favorite->shop->genre->name }}</p>
                            <a href="{{ route('shop.detail', ['shop_id' => $favorite->shop->id]) }}" class="detail-link">詳しくみる</a>

                            <!-- ハートマークの表示 -->
                            <form method="POST" action="{{ route('shop.favorite', ['shop' => $favorite->shop->id]) }}">
                                @csrf
                                @if(auth()->user()->favorites()->where('shop_id', $favorite->shop->id)->exists())
                                    <!-- お気に入り解除ボタン -->
                                    <button type="submit" class="heart-btn active">❤️</button>
                                @else
                                    <!-- お気に入り追加ボタン -->
                                    <button type="submit" class="heart-btn">🤍</button>
                                @endif
                            </form>
                        @else
                            <p>このお気に入りには店舗情報がありません。</p>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection