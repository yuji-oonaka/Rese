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
                            <td>予約{{ $total_count - ($past_reservations->perPage() * ($past_reservations->currentPage() - 1) + $loop->index) }}</td>
                            <td>{{ $reservation->shop ? $reservation->shop->name : 'なし' }}</td>
                            <td>{{ $reservation->date }}</td>
                            <td>{{ $reservation->time }}</td>
                            <td>{{ $reservation->number_of_people }}人</td>
                            <td><button class="review-btn" onclick="showReviewForm({{ $reservation->id }})">評価する</button></td>
                            <td>
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