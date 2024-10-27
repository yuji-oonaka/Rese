@extends('layouts.app')

@section('title', '飲食店一覧')

@section('css')
<link rel="stylesheet" href="{{ asset('css/shop_list.css') }}">
@endsection

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="logo">
            <div class="icon-box">
                <i class="fas fa-align-left"></i>
            </div>
            Rese
        </h1>
        <form action="{{ route('shop.list') }}" method="GET" id="search-form">
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
                <input type="text" name="search" id="search-input" placeholder="Search..." value="{{ request('search') }}">
            </div>
        </form>
    </div>
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
                            <form action="{{ route('shop.favorite', ['shop' => $shop->id]) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn-favorite {{ $shop->favorites_count > 0 ? 'active' : '' }}">
                                    <i class="fa-solid fa-heart"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    <div class="pagination">
        {{ $shops->appends(request()->query())->links() }}
    </div>
</div>
@endsection