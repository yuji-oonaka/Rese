@extends('layouts.app')

@section('title', '代表者ダッシュボード')

@section('css')
<link rel="stylesheet" href="{{ asset('css/mypage.css') }}">
@endsection

@section('content')
<div class="container">
    <h1>{{ $shop->name }} 管理ダッシュボード</h1>
    
    <div class="card">
        <div class="card-body">
            <h5 class="card-title">基本情報</h5>
            <p class="card-text">{{ $shop->description }}</p>
            <p class="card-text">
                <small class="text-muted">
                    エリア: {{ $shop->area->name }} / ジャンル: {{ $shop->genre->name }}
                </small>
            </p>
        </div>
    </div>

    <div class="mt-4">
        <a href="{{ route('representative.shops.edit', $shop) }}" class="btn btn-primary">
            店舗情報を編集
        </a>
    </div>
</div>
@endsection