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
            @if($future_reservations->isEmpty())
                <p>現在、予約はありません。</p>
            @else
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
            @endif
            <a href="{{ route('reservation.history') }}" class="btn-history">予約履歴を見る</a>
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

<script>
document.addEventListener('DOMContentLoaded', function() {
    // フォーム表示・非表示の関数をグローバルスコープに定義
    window.toggleForm = function(index) {
        var form = document.getElementById('edit-form-' + index);
        if (form.style.display === "none") {
            form.style.display = "block";
            setDateAndTimeValidation(index);
        } else {
            form.style.display = "none";
        }
    }

    // 日付と時間のバリデーション設定
    function setDateAndTimeValidation(index) {
        const dateInput = document.getElementById('date-input-' + index);
        const timeInput = document.getElementById('time-input-' + index);

        dateInput.addEventListener('change', function() {
            const selectedDate = new Date(this.value);
            const currentDate = new Date();

            if (selectedDate.toDateString() === currentDate.toDateString()) {
                const hours = currentDate.getHours();
                const minutes = currentDate.getMinutes();
                const minTime = `${hours}:${minutes < 10 ? '0' + minutes : minutes}`;

                timeInput.min = minTime;
                if (timeInput.value && timeInput.value < minTime) {
                    timeInput.value = minTime;
                }
            } else {
                timeInput.removeAttribute('min');
            }
        });

        dateInput.dispatchEvent(new Event('change'));
    }

    // QRコードモーダル関連の機能
    const modal = document.getElementById('qrCodeModal');
    const closeBtn = document.querySelector('.close');

    // QRコードを表示する関数をグローバルスコープに定義
    window.showQRCode = function(imagePath) {
        const img = document.getElementById('qrCodeImage');
        img.src = imagePath;
        modal.style.display = "block";
    };

    // ×ボタンでモーダルを閉じる
    closeBtn.addEventListener('click', function() {
        modal.style.display = "none";
    });

    // モーダルの背景クリックで閉じる
    modal.addEventListener('click', function(e) {
        if (e.target === modal) {
            modal.style.display = "none";
        }
    });

    // お気に入りボタンの機能
    document.querySelectorAll('.heart-btn').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const shopId = this.dataset.shopId;
            const form = this.closest('form');
            const csrfToken = form.querySelector('input[name="_token"]').value;
            const favoriteCard = document.getElementById(`favorite-${shopId}`);

            // お気に入り状態を切り替える
            fetch(form.action, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                credentials: 'same-origin'
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // アニメーションを追加してカードを削除
                    favoriteCard.style.transition = 'opacity 0.3s ease';
                    favoriteCard.style.opacity = '0';

                    setTimeout(() => {
                        favoriteCard.remove();

                        // カードがすべて無くなった場合のメッセージを表示
                        const remainingCards = document.querySelectorAll('.favorite-card');
                        if (remainingCards.length === 0) {
                            const grid = document.querySelector('.favorite-grid');
                            grid.innerHTML = '<p>現在、お気に入りの店舗はありません。</p>';
                        }
                    }, 300);
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
        });
    });
});
</script>