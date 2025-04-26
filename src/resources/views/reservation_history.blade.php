@extends('layouts.app')

@section('title', '予約履歴')

@section('css')
<link rel="stylesheet" href="{{ asset('css/reservation_history.css') }}">
@endsection

@section('content')
<div class="container">
    <div class="header-row">
        <x-header-component />
        <h1 class="user-name">{{ auth()->user()->name }}さん</h1>
    </div>
    <h2>予約履歴</h2>
    @if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif
        @if($past_reservations->isEmpty())
            <p>予約履歴はありません。</p>
        @else
        <div class="content-wrapper">
            <table class="reservation-table">
                <thead>
                    <tr>
                        <th>予約</th>
                        <th>店舗名</th>
                        <th>日付</th>
                        <th>時間</th>
                        <th>人数</th>
                        <th>評価</th>
                        <th>QRコード</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($past_reservations as $index => $reservation)
                    <tr>
                        <td data-label="予約">予約{{ $total_count - ($past_reservations->perPage() * ($past_reservations->currentPage() - 1) + $loop->index) }}</td>
                        <td data-label="店舗名">
                            @if($reservation->shop)
                                <a href="{{ route('shop.detail', ['shop_id' => $reservation->shop->id]) }}">
                                    {{ $reservation->shop->name }}
                                </a>
                            @else
                                なし
                            @endif
                        </td>
                        <td data-label="日付">{{ $reservation->formatted_date }}</td>
                        <td data-label="時間">{{ $reservation->formatted_time }}</td>
                        <td data-label="人数">{{ $reservation->number_of_people }}人</td>
                        <td data-label="評価">
                            @if($reservation->review)
                                <a href="{{ route('reviews.edit', ['reservation' => $reservation->id]) }}" class="review-btn">評価を修正</a>
                            @else
                                <button class="review-btn" onclick="showReviewForm({{ $reservation->id }})">評価する</button>
                            @endif
                        </td>
                        <td data-label="QRコード">
                            @if($reservation->isValidForQrCode())
                                <button class="qr-btn" onclick="showQRCode('{{ $reservation->qr_code_url }}')">QRコードを表示</button>
                            @else
                                <span class="qr-expired">QRコード期限切れ</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        <!-- QRコードモーダル -->
        <div id="qrCodeModal" class="modal">
            <div class="modal-content">
                <span class="close">&times;</span>
                <img id="qrCodeImage" src="" alt="QRコード">
            </div>
        </div>
        <div class="pagination">
            {{ $past_reservations->links() }}
        </div>
    @endif
</div>

<!-- 評価フォームのモーダル -->
<div id="reviewModal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h2>店舗評価</h2>
        <form id="reviewForm" action="{{ route('review.submit') }}" method="POST">
            @csrf
            <input type="hidden" id="reservationId" name="reservation_id">
            <div class="rating">
                <input type="radio" id="star5" name="rating" value="5" /><label for="star5">★</label>
                <input type="radio" id="star4" name="rating" value="4" /><label for="star4">★</label>
                <input type="radio" id="star3" name="rating" value="3" /><label for="star3">★</label>
                <input type="radio" id="star2" name="rating" value="2" /><label for="star2">★</label>
                <input type="radio" id="star1" name="rating" value="1" /><label for="star1">★</label>
            </div>
            <textarea id="comment" name="comment" placeholder="サービスや料理についての感想をお書きください"></textarea>
            <div class="review-form-footer">
                <button class="review-btn" type="submit">評価を送信</button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('js')
<script src="{{ asset('js/reservation_history.js') }}"></script>
@endsection