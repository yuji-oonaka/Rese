@extends('layouts.app')

@section('title', '店舗情報編集')

@section('css')
<link rel="stylesheet" href="{{ asset('css/representative_edit.css') }}">
@endsection

@section('content')
<div class="shop-edit">
    <h1 class="shop-edit__title">店舗情報編集</h1>
    <form method="POST" action="{{ route('representative.shops.update', $shop) }}" enctype="multipart/form-data" class="shop-edit__form">
        @csrf
        @method('PUT')

        <div class="shop-edit__form-group">
            <label for="name" class="shop-edit__label">店舗名</label>
            <input type="text" name="name" id="name" class="shop-edit__input" value="{{ old('name', $shop->name) }}" required>
        </div>

        <div class="shop-edit__form-group">
            <label for="description" class="shop-edit__label">説明</label>
            <textarea name="description" id="description" class="shop-edit__textarea" rows="4" required>{{ old('description', $shop->description) }}</textarea>
        </div>

        <div class="shop-edit__form-group">
            <label for="area_id" class="shop-edit__label">エリア</label>
            <select name="area_id" id="area_id" class="shop-edit__select" required>
                @foreach($areas as $area)
                    <option value="{{ $area->id }}" {{ $shop->area_id == $area->id ? 'selected' : '' }}>
                        {{ $area->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="shop-edit__form-group">
            <label for="genre_id" class="shop-edit__label">ジャンル</label>
            <select name="genre_id" id="genre_id" class="shop-edit__select" required>
                @foreach($genres as $genre)
                    <option value="{{ $genre->id }}" {{ $shop->genre_id == $genre->id ? 'selected' : '' }}>
                        {{ $genre->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="shop-edit__form-group">
            <label for="image_url" class="shop-edit__label">画像URL</label>
            <input type="url" name="image_url" id="image_url" class="shop-edit__input" value="{{ old('image_url', $shop->image_url) }}">
        </div>

        <!-- 画像アップロードフォーム -->
        <div class="shop-edit__form-group">
            <label for="image_file" class="shop-edit__label">画像ファイルをアップロード</label>
            <input type="file" name="image_file" id="image_file" class="shop-edit__input shop-edit__file-input">
        </div>

        <button type="submit" class="shop-edit__btn shop-edit__btn--primary">更新する</button>
    </form>
</div>
@endsection
