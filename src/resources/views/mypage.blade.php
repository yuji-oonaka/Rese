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
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    <div class="content-row">
        <!-- 左側: 予約状況 -->
        <div class="left-section">
            <h2>予約状況</h2>
            @if($future_reservations->isEmpty())
                <p>現在、予約はありません。</p>
            @else
            <div class="reservation-grid">
            @foreach($future_reservations as $reservation)
                <div class="reservation-card">
                    <div class="reservation-header">
                        <!-- 時計アイコン -->
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-clock">
                            <circle cx="12" cy="12" r="10"></circle>
                            <polyline points="12 6 12 12 16 14"></polyline>
                        </svg>
                        <!-- 予約番号 -->
                        <p>予約{{ $loop->iteration }}</p>
                        <!-- 削除ボタン -->
                        <form action="{{ route('reservation.delete', $reservation->id) }}" method="POST" class="delete-form">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="delete-btn" onclick="return confirm('この予約を削除してもよろしいですか？')">×</button>
                        </form>
                    </div>
                    @if($reservation->shop)
                    <table class="reservation-info-table">
                        <tr>
                            <th>Shop</th>
                            <td>{{ $reservation->shop->name }}</td>
                        </tr>
                        <tr>
                            <th>Date</th>
                            <td>{{ $reservation->date }}</td>
                        </tr>
                        <tr>
                            <th>Time</th>
                            <td>{{ $reservation->time }}</td>
                        </tr>
                        <tr>
                            <th>Number</th>
                            <td>{{ $reservation->number_of_people }}人</td>
                        </tr>
                        @if($reservation->qr_code_path)
                            <tr>
                                <th>QR Code</th>
                                <td>
                                    <button type="button" class="qr-code-btn" onclick="showQRCode('{{ asset('storage/' . $reservation->qr_code_path) }}')">
                                        QRコードを表示
                                    </button>
                                </td>
                            </tr>
                        @endif
                    </table>
                    <button class="edit-btn" onclick="toggleForm({{ $loop->index }})">変更</button>
                    <!-- 変更フォーム (非表示) -->
                    <form id="edit-form-{{ $loop->index }}" action="{{ route('reservation.update', ['reservation_id' => $reservation->id]) }}" method="POST" style="display: none;">
                        @csrf
                        @method('PUT')
                        <table class="reservation-edit-table">
                            <tr>
                                <th><label for="date">日付</label></th>
                                <td>
                                    <input type="date" name="date" id="date-input-{{ $loop->index }}"
                                        value="{{ old('date', $reservation->date) }}"
                                        min="{{ now()->toDateString() }}">
                                </td>
                            </tr>
                            <tr>
                                <th><label for="time">時間</label></th>
                                <td>
                                    <input type="time" name="time" id="time-input-{{ $loop->index }}"
                                        value="{{ old('time', $reservation->time) }}">
                                </td>
                            </tr>
                            <tr>
                                <th><label for="number_of_people">人数</label></th>
                                <td>
                                    <input type="number" name="number_of_people"
                                        value="{{ old('number_of_people', $reservation->number_of_people) }}"
                                        min="1">
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2" class="form-actions">
                                    <button type="submit" class="update-btn">更新する</button>
                                    <button type="button" class="cancel-btn" onclick="toggleForm({{ $loop->index }})">キャンセル</button>
                                </td>
                            </tr>
                        </table>
                    </form>
                    @else
                        <p>この予約には店舗情報がありません。</p>
                    @endif
                </div>
            @endforeach
            </div>
            @endif
            <div class="history-section">
                <a href="{{ route('reservation.history') }}" class="btn-history">予約履歴を見る</a>
            </div>
        </div>

        <!-- 右側: お気に入り店舗 -->
        <div class="right-section">
            <h2>お気に入り店舗</h2>
            @if($favorites->isEmpty())
                <p>現在、お気に入りの店舗はありません。</p>
            @else
            <div class="favorite-grid">
            @foreach($favorites as $favorite)
                <div class="favorite-card" id="favorite-{{ $favorite->shop->id }}">
                    @if($favorite->shop)
                        <img src="{{ $favorite->shop->image_url }}" alt="{{ $favorite->shop->name }}">
                        <div class="content">
                            <h2>{{ $favorite->shop->name }}</h2>
                            <p>#{{ $favorite->shop->area->name }} #{{ $favorite->shop->genre->name }}</p>
                            <div class="actions">
                                <a href="{{ route('shop.detail', ['shop_id' => $favorite->shop->id]) }}" class="detail-link">詳しくみる</a>
                                <form method="POST" action="{{ route('shop.favorite', ['shop' => $favorite->shop->id]) }}" class="favorite-form">
                                    @csrf
                                    <button type="button" class="heart-btn active" data-shop-id="{{ $favorite->shop->id }}">
                                        <i class="fa-solid fa-heart"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
            @endif
        </div>
    </div>
    <div id="qrCodeModal" class="modal" style="display: none;">
        <div class="modal-content">
            <span class="close">&times;</span>
            <img id="qrCodeImage" src="" alt="QR Code">
        </div>
    </div>
</div>
@endsection

@section('js')
<script src="{{ asset('js/mypage.js') }}"></script>
@endsection