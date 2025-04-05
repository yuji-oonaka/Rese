@extends('layouts.app')

@section('title', '店舗情報編集')

@section('content')
<div class="container">
    <h1>店舗情報編集</h1>
    <form method="POST" action="{{ route('representative.shops.update', $shop) }}">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="name">店舗名</label>
            <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $shop->name) }}" required>
        </div>

        <div class="form-group">
            <label for="description">説明</label>
            <textarea name="description" id="description" class="form-control" rows="4" required>{{ old('description', $shop->description) }}</textarea>
        </div>

        <div class="form-group">
            <label for="area_id">エリア</label>
            <select name="area_id" id="area_id" class="form-control" required>
                @foreach($areas as $area)
                    <option value="{{ $area->id }}" {{ $shop->area_id == $area->id ? 'selected' : '' }}>
                        {{ $area->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="genre_id">ジャンル</label>
            <select name="genre_id" id="genre_id" class="form-control" required>
                @foreach($genres as $genre)
                    <option value="{{ $genre->id }}" {{ $shop->genre_id == $genre->id ? 'selected' : '' }}>
                        {{ $genre->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="image_url">画像URL</label>
            <input type="url" name="image_url" id="image_url" class="form-control" value="{{ old('image_url', $shop->image_url) }}">
        </div>

        <button type="submit" class="btn btn-primary">更新する</button>
    </form>
</div>
@endsection
