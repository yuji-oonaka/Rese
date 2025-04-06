@extends('layouts.app')

@section('title', '口コミ投稿')

@section('css')
<link rel="stylesheet" href="{{ asset('css/review.css') }}">
@endsection

@section('content')
<div class="container">
    <x-header-component />
    <div class="header">
        <a href="{{ route('shop.detail', ['shop_id' => $shop->id]) }}" class="back-btn">&lt;</a>
        <h2>{{ $shop->name }} - 口コミ投稿</h2>
    </div>
    
    <div class="review-form">
        <form action="{{ route('review.store', ['shop_id' => $shop->id]) }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="form-group">
                <label>体験を評価してください</label>
                <div class="rating">
                    @for ($i = 5; $i >= 1; $i--)
                        <input type="radio" id="star{{ $i }}" name="rating" value="{{ $i }}" {{ old('rating') == $i ? 'checked' : '' }} />
                        <label for="star{{ $i }}">★</label>
                    @endfor
                </div>
                @error('rating')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="form-group">
                <label>口コミを投稿</label>
                <textarea name="comment" rows="5" maxlength="400" placeholder="カジュアルな夜のお出かけにおすすめのスポット">{{ old('comment') }}</textarea>
                <div class="char-count"><span id="char-count">0</span>/400</div>
                @error('comment')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="form-group">
                <label>画像の追加</label>
                <div class="image-upload">
                    <input type="file" name="image" id="image" accept="image/jpeg,image/png" />
                    <div class="upload-preview">
                        <p>クリックして写真を追加<br>またはドラッグアンドドロップ</p>
                    </div>
                </div>
                @error('image')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="form-actions">
                <button type="submit" class="submit-btn">口コミを投稿</button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('js')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // 文字数カウンター
        const textarea = document.querySelector('textarea[name="comment"]');
        const charCount = document.getElementById('char-count');
        
        textarea.addEventListener('input', function() {
            charCount.textContent = this.value.length;
        });
        
        // 初期値設定
        charCount.textContent = textarea.value.length;
        
        // 画像プレビュー
        const imageInput = document.getElementById('image');
        const previewArea = document.querySelector('.upload-preview');
        
        imageInput.addEventListener('change', function() {
            if (this.files && this.files[0]) {
                const reader = new FileReader();
                
                reader.onload = function(e) {
                    previewArea.innerHTML = `<img src="${e.target.result}" alt="Preview">`;
                }
                
                reader.readAsDataURL(this.files[0]);
            }
        });
    });
</script>
@endsection
