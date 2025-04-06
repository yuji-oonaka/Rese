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
                    <select name="sort" id="sort-filter" onchange="this.form.submit()">
                        <option value="random" {{ request('sort') == 'random' || !request('sort') ? 'selected' : '' }}>並び替え：ランダム</option>
                        <option value="rating_high" {{ request('sort') == 'rating_high' ? 'selected' : '' }}>評価が高い順</option>
                        <option value="rating_low" {{ request('sort') == 'rating_low' ? 'selected' : '' }}>評価が低い順</option>
                    </select>
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
    
    @if($isSearched || request('sort'))
        @if($shops->isEmpty())
            <div class="alert alert-info">
                検索結果が見つかりませんでした。
            </div>
        @else
            <div class="alert alert-info">
                @if(request('sort') == 'rating_high')
                    検索情報： "評価の高い順"
                @elseif(request('sort') == 'rating_low')
                    検索情報： "評価の低い順"
                @endif
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
                            <div class="rating">
                                @for ($i = 1; $i <= 5; $i++)
                                    @if ($i <= $shop->fullStars)
                                        <span class="star full">★</span>
                                    @elseif ($i == $shop->fullStars + 1 && $shop->halfStar)
                                        <span class="star half">★</span>
                                    @else
                                        <span class="star empty">☆</span>
                                    @endif
                                @endfor
                                <span class="rating-value">{{ number_format($shop->averageRating, 1) }}</span>
                                @if($shop->reviews_count > 0)
                                    <a href="{{ route('shop.reviews', ['shop_id' => $shop->id]) }}" class="reviews-count">({{ $shop->reviews_count }})</a>
                                @endif
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

@section('js')
<script src="{{ asset('js/shop_list.js') }}"></script>
@endsection
