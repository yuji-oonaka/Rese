@extends('layouts.app')

@section('title', 'レビュー投稿')

@section('css')
<link rel="stylesheet" href="{{ asset('css/reviews.css') }}">
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
                    <a href="{{ route('shop.detail', ['shop_id' => $shop->id]) }}" class="details-btn">詳しく見る</a>
                    
                </div>
            </div>
        </div>
        
        <div class="review-form">
            <h2>体験を評価してください</h2>
            <form action="{{ route('review.submit') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="reservation_id" value="{{ request('reservation_id') }}">
                
                <div class="rating-section">
                    <div class="stars">
                        @for ($i = 5; $i >= 1; $i--)
                            <input type="radio" id="star{{ $i }}" name="rating" value="{{ $i }}" {{ old('rating') == $i ? 'checked' : '' }}>
                            <label for="star{{ $i }}">★</label>
                        @endfor
                    </div>
                </div>
                
                <div class="comment-section">
                    <h3>口コミを投稿</h3>
                    <textarea name="comment" placeholder="カジュアルな夜のお出かけにおすすめのスポット">{{ old('comment') }}</textarea>
                    <div class="char-count"><span id="current-count">0</span>/400 (最大文字数)</div>
                </div>
                
                <div class="image-section">
                    <h3>画像の追加</h3>
                    <div class="image-upload-area">
                        <input type="file" name="image" id="image-upload" accept="image/jpeg,image/png">
                        <div class="upload-placeholder">
                            <p>クリックして写真を追加<br>またはドラッグアンドドロップ</p>
                        </div>
                    </div>
                </div>
                
                <button type="submit" class="submit-btn">口コミを投稿</button>
            </form>
        </div>
    </div>
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
            }
            
            reader.readAsDataURL(this.files[0]);
        }
    });
</script>
@endsection