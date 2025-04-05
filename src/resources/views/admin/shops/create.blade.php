@extends('layouts.app')

@section('title', '店舗作成')

@section('content')
<div class="container">
    <h1>店舗作成</h1>
    <form method="POST" action="{{ route('shops.store') }}">
        @csrf
        <div class="form-group">
            <label>店舗名</label>
            <input type="text" name="name" class="form-control" required>
        </div>
        <div class="form-group">
            <label>エリア</label>
            <select name="area_id" class="form-control" required>
                @foreach($areas as $area)
                    <option value="{{ $area->id }}">{{ $area->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label>ジャンル</label>
            <select name="genre_id" class="form-control" required>
                @foreach($genres as $genre)
                    <option value="{{ $genre->id }}">{{ $genre->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label>説明</label>
            <textarea name="description" class="form-control" rows="3" required></textarea>
        </div>
        <div class="form-group">
            <label>画像URL</label>
            <input type="url" name="image_url" class="form-control">
        </div>
        <div class="form-group">
            <label>代表者</label>
            <select name="representative_id" class="form-control">
                @foreach($representatives as $user)
                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="btn btn-primary">作成</button>
    </form>
</div>
@endsection
