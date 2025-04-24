@extends('layouts.app')

@section('title', 'レビュー投稿')

@section('css')
<link rel="stylesheet" href="{{ asset('css/review_create.css') }}">
@endsection

@section('content')
<div class="container">
    <x-header-component />
    
    <div class="review-container">
        <div class="shop-info">
            <h2>今回のご利用はいかがでしたか？</h2>
            
            <div class="shop-card">
                <img src="{{ $shop->image_url }}" alt="{{ $shop->name }}">
                <div class="shop-details">
                    <h3>{{ $shop->name }}</h3>
                    <p>#{{ $shop->area->name }} #{{ $shop->genre->name }}</p>
                    <div class="actions">
                        <a href="{{ route('shop.detail', ['shop_id' => $shop->id]) }}" class="btn-detail">詳しくみる</a>
                        @auth
                            <form action="{{ route('shop.favorite', ['shop' => $shop->id]) }}" method="POST" class="favorite-form">
                                @csrf
                                <button type="button" class="btn-favorite {{ auth()->user()->favorites()->where('shop_id', $shop->id)->exists() ? 'active' : '' }}" data-shop-id="{{ $shop->id }}">
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
            
            <!-- 全体のエラーメッセージ -->
            @if($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form id="review-form" action="{{ route('review.submit') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="reservation_id" value="{{ $reservation_id }}">
                <!-- 評価セクション -->
                <div class="rating-section">
                    <div class="stars">
                        @for ($i = 5; $i >= 1; $i--)
                            <input type="radio" id="star{{ $i }}" name="rating" value="{{ $i }}" {{ old('rating') == $i ? 'checked' : '' }}>
                            <label for="star{{ $i }}">★</label>
                        @endfor
                    </div>
                </div>
                @error('rating')
                    <span class="error-message">{{ $message }}</span>
                @enderror

                <!-- コメントセクション -->
                <div class="comment-section">
                    <h3>口コミを投稿</h3>
                    <textarea name="comment" placeholder="カジュアルな夜のお出かけにおすすめのスポット">{{ old('comment') }}</textarea>
                    <div class="char-count">
                        <span id="current-count" class="{{ strlen(old('comment', '')) > 400 ? 'over-limit' : '' }}">
                            {{ strlen(old('comment', '')) }}
                        </span>/400 (最大文字数)
                    </div>
                    @error('comment')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <!-- 画像セクション -->
                <div class="image-section">
                    <h3>画像の追加</h3>
                    <div class="image-upload-area" id="drop-area">
                        <input type="file" name="image" id="image-upload" accept="image/jpeg,image/png">
                        <div class="upload-placeholder">
                            <p>クリックして写真を追加<br>またはドラッグアンドドロップ</p>
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
        };

        reader.readAsDataURL(this.files[0]);
    }
});
</script>

<script>
// お気に入りボタンのイベントハンドラ
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

document.addEventListener('DOMContentLoaded', function() {
    const dropArea = document.getElementById('drop-area');
    const fileInput = document.getElementById('image-upload');
    const previewArea = document.querySelector('.upload-placeholder');

    // ドラッグ時の見た目変更
    dropArea.addEventListener('dragover', function(e) {
        e.preventDefault();
        dropArea.classList.add('dragover');
    });
    dropArea.addEventListener('dragleave', function(e) {
        e.preventDefault();
        dropArea.classList.remove('dragover');
    });

    // ドロップ時の処理
    dropArea.addEventListener('drop', function(e) {
        e.preventDefault();
        dropArea.classList.remove('dragover');
        const files = e.dataTransfer.files;
        if (files && files[0]) {
            fileInput.files = files; // inputにセット
            // プレビュー表示
            const reader = new FileReader();
            reader.onload = function(e) {
                previewArea.innerHTML = `<img src="${e.target.result}" alt="プレビュー">`;
                previewArea.classList.add('has-image');
            };
            reader.readAsDataURL(files[0]);
        }
    });

    // 通常のinput選択時もプレビュー
    fileInput.addEventListener('change', function(e) {
        if (this.files && this.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                previewArea.innerHTML = `<img src="${e.target.result}" alt="プレビュー">`;
                previewArea.classList.add('has-image');
            };
            reader.readAsDataURL(this.files[0]);
        }
    });
});

// ログインページへのリダイレクト
function redirectToLogin() {
    // 現在のURLをセッションストレージに保存
    sessionStorage.setItem('returnTo', window.location.href);
    // ログインページへリダイレクト
    window.location.href = "{{ route('login') }}";
}

</script>
@endsection
