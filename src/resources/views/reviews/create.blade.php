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
                    <a href="{{ route('shop.detail', ['shop_id' => $shop->id]) }}" class="details-btn">詳しく見る</a>
                </div>
            </div>
        </div>
        
        <div class="review-form">
            <!-- エラーメッセージ全体表示 -->
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            
            <h2>体験を評価してください</h2>
            <form action="{{ route('review.submit') }}" method="POST" enctype="multipart/form-data" novalidate>
                @csrf
                <input type="hidden" name="reservation_id" value="{{ request('reservation_id') }}">
                
                <!-- 星評価 -->
                <div class="rating-section">
                    <label for="rating">星評価</label>
                    <div class="stars">
                        @for ($i = 5; $i >= 1; $i--)
                            <input type="radio" id="star{{ $i }}" name="rating" value="{{ $i }}" {{ old('rating') == $i ? 'checked' : '' }} required>
                            <label for="star{{ $i }}">★</label>
                        @endfor
                    </div>
                    @error('rating')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>
                
                <!-- コメント -->
                <div class="comment-section">
                    <label for="comment">口コミ</label>
                    <textarea name="comment" placeholder="カジュアルな夜のお出かけにおすすめのスポット" required maxlength="400">{{ old('comment') }}</textarea>
                    @error('comment')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                    <div class="char-count"><span id="current-count">0</span>/400 (最大文字数)</div>
                </div>
                
                <!-- 画像アップロード -->
                <div class="image-section">
                    <label for="image-upload">画像（任意）</label>
                    <input type="file" name="image" id="image-upload" accept=".jpeg,.png">
                    @error('image')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <!-- 投稿ボタン -->
                <button type="submit" class="submit-btn">口コミを投稿</button>
            </form>
        </div>
    </div>
</div>
@endsection

@section('js')
<script>
// 文字数カウント機能
document.addEventListener("DOMContentLoaded", function () {
    const textarea = document.querySelector("textarea[name='comment']");
    const charCount = document.getElementById("current-count");

    textarea.addEventListener("input", function () {
        const count = this.value.length;
        charCount.textContent = count;

        if (count > 400) {
            charCount.classList.add("over-limit");
        } else {
            charCount.classList.remove("over-limit");
        }
    });

    charCount.textContent = textarea.value.length;
});
</script>
@endsection
