@extends('layouts.app')

@section('title', '店舗編集')

@section('content')
<div class="container">
    <h1>店舗編集</h1>
    <form method="POST" action="{{ route('shops.update', $shop) }}">
        @csrf
        @method('PUT')
        <!-- 作成フォームと同じフィールドを表示 -->
        <!-- 各フィールドに現在の値を表示 -->
    </form>
</div>
@endsection
