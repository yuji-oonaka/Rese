@extends('layouts.app')

@section('title', '飲食店一覧')

@section('css')
<link rel="stylesheet" href="{{ asset('css/shop_list.css') }}">
@endsection

@section('content')
<div class="shop-container">
    <div class="d-flex justify-content-end mb-4">
        <div class="filter-bar d-flex">
            <select class="form-select me-2">
                <option>All areas</option>
                <!-- エリアオプション -->
            </select>
            <select class="form-select me-2">
                <option>All genres</option>
                <!-- ジャンルオプション -->
            </select>
            <input type="text" class="form-control" placeholder="Search...">
        </div>
    </div>
    <div class="row">
        @foreach($shops as $shop)
            <div class="col-md-3 mb-4">
                <div class="card shadow-sm h-100">
                    <img src="{{ $shop->image_url }}" alt="{{ $shop->name }}" class="card-img-top">
                    <div class="card-body d-flex flex-column justify-content-between">
                        <div>
                            <h2>{{ $shop->name }}</h2>
                            <p>#{{ $shop->area->name }} #{{ $shop->genre->name }}</p>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mt-auto">
                            <a href="{{ route('shop.detail', ['shop_id' => $shop->id]) }}" class="btn btn-primary">詳しくみる</a>
                            <button class="btn btn-light" onclick="toggleFavorite(this)"><i class="fa fa-heart"></i></button>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- ページネーション -->
    <div class="pagination-container">
        {{ $shops->links() }}
    </div>
</div>

<script>
function toggleFavorite(button) {
   button.classList.toggle('active');
   // AJAXでfavoriteテーブルに登録するコードを追加
}
</script>
@endsection