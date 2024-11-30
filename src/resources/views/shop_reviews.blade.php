@extends('layouts.app')

@section('title', $shop->name . ' - レビュー')

@section('css')
<link rel="stylesheet" href="{{ asset('css/shop_reviews.css') }}">
@endsection

@section('content')
<div class="container">
    <x-header-component />
    <div class="header">
    <a href="{{ route('shop.list') }}" class="back-btn">&lt;</a>
    <h2>{{ $shop->name }} のレビュー</h2>
</div>
    @foreach($reviews as $review)
        <div class="review">
            <p>評価: {{ $review->rating }}</p>
            <p>{{ $review->comment }}</p>
            <p>投稿者: {{ $review->user->name }}</p>
            <p>投稿日: {{ $review->created_at->format('Y-m-d H:i') }}</p>
        </div>
    @endforeach
    <div class="pagination">
        {{ $reviews->links() }}
    </div>
</div>
@endsection