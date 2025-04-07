@extends('layouts.app')

@section('title', '店舗作成')

@section('css')
<link rel="stylesheet" href="{{ asset('css/shops_create.css') }}">
@endsection

@section('content')
<div class="shop-create">
    <h1 class="shop-create__title">店舗作成</h1>

    <!-- CSVインポートフォーム -->
    <form method="POST" action="{{ route('shops.importCsv') }}" enctype="multipart/form-data" class="shop-create__form shop-create__form--csv-import">
        @csrf
        <div class="shop-create__form-group">
            <label class="shop-create__label">CSVファイルを選択</label>
            <input type="file" name="csv_file" class="shop-create__input" required>
        </div>
        <button type="submit" class="shop-create__btn shop-create__btn--primary">CSVインポート</button>
    </form>

    <hr class="shop-create__divider">

    <!-- 手動入力フォーム -->
    <form method="POST" action="{{ route('shops.store') }}" class="shop-create__form shop-create__form--manual">
        @csrf
        <div class="shop-create__form-group">
            <label class="shop-create__label">店舗名</label>
            <input type="text" name="name" class="shop-create__input" required>
        </div>
        <div class="shop-create__form-group">
            <label class="shop-create__label">エリア</label>
            <select name="area_id" class="shop-create__input shop-create__select" required>
                @foreach($areas as $area)
                    <option value="{{ $area->id }}">{{ $area->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="shop-create__form-group">
            <label class="shop-create__label">ジャンル</label>
            <select name="genre_id" class="shop-create__input shop-create__select" required>
                @foreach($genres as $genre)
                    <option value="{{ $genre->id }}">{{ $genre->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="shop-create__form-group">
            <label class="shop-create__label">説明</label>
            <textarea name="description" class="shop-create__input shop-create__textarea" rows="3" required></textarea>
        </div>
        <div class="shop-create__form-group">
            <label class="shop-create__label">画像URL</label>
            <input type="url" name="image_url" class="shop-create__input">
        </div>
        <button type="submit" class="shop-create__btn shop-create__btn--primary">作成</button>
    </form>

    <div class="shop-create__actions">
        <a href="{{ route('admin.dashboard') }}" class="shop-create__btn shop-create__btn--primary">管理者ダッシュボードへ</a>
    </div>
</div>
@endsection
