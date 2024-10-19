@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="{{ asset('css/shop_detail.css') }}">

<div class="container">
    <div class="image-container">
        <div class="d-flex align-items-center mb-3">
            <a href="{{ url()->previous() }}" class="btn btn-light me-2">&lt; 戻る</a>
            <h2>{{ $shop->name }}</h2>
        </div>
        <img src="{{ $shop->image_url }}" alt="{{ $shop->name }}" class="img-fluid mb-3">
        <p>#{{ $shop->area->name }} #{{ $shop->genre->name }}</p>
        <p>{{ $shop->description }}</p>
    </div>
    <div class="reservation-container">
        <h3>予約</h3>
        <form>
            <input type="date" class="form-control mb-2">
            <input type="time" class="form-control mb-2">
            <select class="form-control mb-2">
                <option>1人</option>
                <!-- その他のオプション -->
            </select>
            <div class="bg-light text-dark p-2 mb-2">
                <p>Shop: {{ $shop->name }}</p>
                <!-- 他の予約情報 -->
            </div>
            <button type="submit" class="btn btn-dark w-100">予約する</button>
        </form>
    </div>
</div>
@endsection