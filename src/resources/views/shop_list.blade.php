@extends('layouts.app')

@section('title', '飲食店一覧')

@section('css')
<link rel="stylesheet" href="{{ asset('css/shop_list.css') }}">
@endsection

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <x-header-component />
        <form action="{{ route('shop.list') }}" method="GET" id="search-form">
            <div class="filter-container">
                <div class="filter">
                    <select name="area" id="area-filter" onchange="this.form.submit()">
                        <option value="">All areas</option>
                        @foreach($areas as $area)
                            <option value="{{ $area->id }}" {{ request('area') == $area->id ? 'selected' : '' }}>{{ $area->name }}</option>
                        @endforeach
                    </select>
                    <select name="genre" id="genre-filter" onchange="this.form.submit()">
                        <option value="">All genres</option>
                        @foreach($genres as $genre)
                            <option value="{{ $genre->id }}" {{ request('genre') == $genre->id ? 'selected' : '' }}>{{ $genre->name }}</option>
                        @endforeach
                    </select>
                    <div class="search-wrapper">
                        <svg class="search-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="11" cy="11" r="8"></circle>
                            <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                        </svg>
                        <input type="text" name="search" id="search-input" placeholder="Search..." value="{{ request('search') }}">
                    </div>
                </div>
            </div>
        </form>
    </div>
    @if($isSearched)
        @if($shops->isEmpty())
            <div class="alert alert-info">
                検索結果が見つかりませんでした。
            </div>
        @else
            <div class="alert alert-info">
                {{ $shops->total() }}件の検索結果が見つかりました。
            </div>
        @endif
    @endif

    @if($shops->isNotEmpty())
        <div class="grid">
            @foreach($shops as $shop)
                <div class="item">
                    <div class="card">
                        <img src="{{ $shop->image_url }}" alt="{{ $shop->name }}">
                        <div class="content">
                            <div>
                                <h2>{{ $shop->name }}</h2>
                                <p>#{{ $shop->area->name }} #{{ $shop->genre->name }}</p>
                            </div>
                            <div class="actions">
                                <a href="{{ route('shop.detail', ['shop_id' => $shop->id]) }}" class="btn-detail">詳しくみる</a>
                                @auth
                                    <form action="{{ route('shop.favorite', ['shop' => $shop->id]) }}" method="POST" class="favorite-form">
                                        @csrf
                                        <button type="button" class="btn-favorite {{ $shop->favorites_count > 0 ? 'active' : '' }}" data-shop-id="{{ $shop->id }}">
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
            @endforeach
        </div>
        <div class="pagination">
            {{ $shops->appends(request()->query())->links() }}
        </div>
    @endif
</div>
@endsection

<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.btn-favorite').forEach(button => {
        // キラキラ要素を追加
        const wrapper = document.createElement('div');
        wrapper.className = 'sparkle-wrapper';
        button.parentNode.insertBefore(wrapper, button);
        wrapper.appendChild(button);

        const sparklesContainer = document.createElement('div');
        sparklesContainer.className = 'sparkles';
        wrapper.appendChild(sparklesContainer);

        button.addEventListener('click', function(e) {
            e.preventDefault();
            const shopId = this.dataset.shopId;
            const form = this.closest('form');
            const csrfToken = form.querySelector('input[name="_token"]').value;

            // キラキラエフェクトを作成
            createSparkles(sparklesContainer);

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

// キラキラエフェクトを生成する関数
function createSparkles(container) {
    for (let i = 0; i < 8; i++) {
        const sparkle = document.createElement('div');
        sparkle.className = 'sparkle';

        // ランダムな位置と角度を設定
        const angle = (i * 45) + Math.random() * 30;
        const distance = 20 + Math.random() * 20;
        const x = Math.cos(angle * Math.PI / 180) * distance;
        const y = Math.sin(angle * Math.PI / 180) * distance;

        sparkle.style.left = x + 'px';
        sparkle.style.top = y + 'px';
        sparkle.style.animation = `sparkle 0.8s ease-in-out forwards`;

        container.appendChild(sparkle);

        // アニメーション終了後に要素を削除
        setTimeout(() => {
            sparkle.remove();
        }, 800);
    }
}
function redirectToLogin() {
    // 現在のURLをセッションストレージに保存
    sessionStorage.setItem('returnTo', window.location.href);
    // ログインページへリダイレクト
    window.location.href = '{{ route("login") }}';
}

// お気に入りボタンのイベントハンドラ（認証済みユーザー用）
document.querySelectorAll('.favorite-form .btn-favorite').forEach(button => {
    button.addEventListener('click', function(e) {
        e.preventDefault();
        // 既存のお気に入り処理
        const shopId = this.dataset.shopId;
        const form = this.closest('form');
        const csrfToken = form.querySelector('input[name="_token"]').value;
        
        // 以下既存のfetch処理
        // ...
    });
});
</script>