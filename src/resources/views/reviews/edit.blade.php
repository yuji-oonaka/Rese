@extends('layouts.app')

@section('title', 'レビュー編集')

@section('css')
<link rel="stylesheet" href="{{ asset('css/review_create.css') }}">
@endsection

@section('content')
<div class="container">
    <x-header-component />

    <div class="review-container">
        <div class="shop-info">
            <h2>レビューを編集してください</h2>

            <div class="shop-card">
                <img src="{{ $reservation->shop->image_url }}" alt="{{ $reservation->shop->name }}">
                <div class="shop-details">
                    <h3>{{ $reservation->shop->name }}</h3>
                    <p>#{{ $reservation->shop->area->name }} #{{ $reservation->shop->genre->name }}</p>
                    <div class="actions">
                        <a href="{{ route('shop.detail', ['shop_id' => $reservation->shop->id]) }}" class="btn-detail">詳しくみる</a>
                        @auth
                            <form action="{{ route('shop.favorite', ['shop' => $reservation->shop->id]) }}" method="POST" class="favorite-form">
                                @csrf
                                <button type="button" class="btn-favorite {{ auth()->user()->favorites()->where('shop_id', $reservation->shop->id)->exists() ? 'active' : '' }}" data-shop-id="{{ $reservation->shop->id }}">
                                    <i class="fa-solid fa-heart"></i>
                                </button>
                            </form>
                        @else
                            <button type="button" class="btn-favorite" onclick="redirectToLogin()">
                                <i class="fa-solid fa-heart"></i>
                            </button>
                        @endauth
                    </div>
                </div>
            </div>
        </div>

        <div class="review-form">
            <h2>体験を評価してください</h2>
            <form id="review-form" action="{{ route('review.update', ['reservation_id' => $review->reservation_id]) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="rating-section">
                    <div class="stars">
                        @for ($i = 5; $i >= 1; $i--)
                            <input type="radio" id="star{{ $i }}" name="rating" value="{{ $i }}" {{ old('rating', $review->rating) == $i ? 'checked' : '' }}>
                            <label for="star{{ $i }}">★</label>
                        @endfor
                    </div>
                    @error('rating')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <div class="comment-section">
                    <h3>口コミを投稿</h3>
                    <textarea name="comment" placeholder="カジュアルな夜のお出かけにおすすめのスポット">{{ old('comment', $review->comment) }}</textarea>
                    <div class="char-count">
                        <span id="current-count" class="{{ strlen(old('comment', $review->comment)) > 400 ? 'over-limit' : '' }}">
                            {{ strlen(old('comment', $review->comment)) }}
                        </span>/400 (最大文字数)
                    </div>
                    @error('comment')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <div class="image-section">
                    <h3>画像の追加</h3>
                    <div class="image-upload-area">
                        <input type="file" name="image" id="image-upload" accept="image/jpeg,image/png">
                        <div class="upload-placeholder">
                            @if($review->image_path)
                                <img src="{{ asset('storage/' . $review->image_path) }}" alt="レビュー画像">
                            @else
                                <p>クリックして写真を追加<br>またはドラッグアンドドロップ</p>
                            @endif
                        </div>
                    </div>
                    @error('image')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>
            </form>
        </div>
    </div>
    <button type="submit" form="review-form" class="submit-btn">口コミを投稿</button>
</div>
@endsection

@section('js')
<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.btn-favorite').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const shopId = this.dataset.shopId;
            const form = this.closest('form');
            const csrfToken = form.querySelector('input[name="_token"]').value;

            this.classList.add('animating');

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
                if (data.status) {
                    this.classList.add('active');
                } else {
                    this.classList.remove('active');
                }
                setTimeout(() => {
                    this.classList.remove('animating');
                }, 400);
            })
            .catch(error => {
                console.error('Error:', error);
                this.classList.remove('animating');
            });
        });
    });
});
    // 文字数カウント
    const textarea = document.querySelector('textarea[name="comment"]');
    const charCount = document.getElementById('current-count');

    textarea.addEventListener('input', function() {
        const count = this.value.length;
        charCount.textContent = count;

        if (count > 400) {
            charCount.classList.add('over-limit');
        } else {
            charCount.classList.remove('over-limit');
        }
    });

    // 初期カウント表示
    charCount.textContent = textarea.value.length;

    // 画像アップロードプレビュー
    const imageInput = document.getElementById('image-upload');
    const previewArea = document.querySelector('.upload-placeholder');

    imageInput.addEventListener('change', function(e) {
        if (this.files && this.files[0]) {
            const reader = new FileReader();

            reader.onload = function(e) {
                previewArea.innerHTML = `<img src="${e.target.result}" alt="プレビュー">`;
                previewArea.classList.add('has-image');
            }

            reader.readAsDataURL(this.files[0]);
        }
    });
</script>
@endsection
