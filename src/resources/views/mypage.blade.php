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
            @if($reservations->isEmpty())
                <p>現在、予約はありません。</p>
            @else
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
                    </table>
                    <button class="edit-btn" onclick="toggleForm({{ $index }})">変更</button>
                    <!-- 変更フォーム (非表示) -->
                    <form id="edit-form-{{ $index }}" action="{{ route('reservation.update', ['reservation_id' => $reservation->id]) }}" method="POST" style="display: none;">
                        @csrf
                        @method('PUT')
                        <table class="reservation-edit-table">
                            <tr>
                                <th><label for="date">日付</label></th>
                                <td>
                                    <input type="date" name="date" id="date-input-{{ $index }}"
                                        value="{{ old('date', $reservation->date) }}"
                                        min="{{ now()->toDateString() }}">
                                </td>
                            </tr>
                            <tr>
                                <th><label for="time">時間</label></th>
                                <td>
                                    <input type="time" name="time" id="time-input-{{ $index }}"
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
                                    <button type="button" class="cancel-btn" onclick="toggleForm({{ $index }})">キャンセル</button>
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
        </div>

        <!-- 右側: お気に入り店舗 -->
        <div class="right-section">
            <h2>お気に入り店舗</h2>
            @if($favorites->isEmpty())
                <p>現在、お気に入りの店舗はありません。</p>
            @else
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
            @endif
        </div>
    </div>
</div>
@endsection

<script>
// フォームの表示・非表示を切り替える関数
function toggleForm(index) {
    var form = document.getElementById('edit-form-' + index);
    if (form.style.display === "none") {
        form.style.display = "block";
        // フォームが表示されたときに日付と時間のバリデーションを設定
        setDateAndTimeValidation(index);
    } else {
        form.style.display = "none";
    }
}

// 日付と時間のバリデーション設定
function setDateAndTimeValidation(index) {
    const dateInput = document.getElementById('date-input-' + index);
    const timeInput = document.getElementById('time-input-' + index);

    // 日付が変更されたときに実行
    dateInput.addEventListener('change', function() {
        const selectedDate = new Date(this.value);
        const currentDate = new Date();

        // 今日の日付が選択された場合
        if (selectedDate.toDateString() === currentDate.toDateString()) {
            // 現在時刻以降のみ許可
            const hours = currentDate.getHours();
            const minutes = currentDate.getMinutes();
            const minTime = `${hours}:${minutes < 10 ? '0' + minutes : minutes}`;

            timeInput.min = minTime;

            // もし現在時刻より前の時間が設定されていたらリセットする
            if (timeInput.value && timeInput.value < minTime) {
                timeInput.value = minTime;
            }
        } else {
            // 他の日付が選択された場合は制限なし
            timeInput.removeAttribute('min');
        }
    });

    // ページ読み込み時にもチェック（初期値が今日の場合に対応）
    dateInput.dispatchEvent(new Event('change'));
}

</script>