@extends('layouts.app')

@section('title', '代表者の変更')

@section('css')
<link rel="stylesheet" href="{{ asset('css/admin_shops_edit.css') }}">
@endsection


@section('content')
<div class="shop-edit">
    <h1 class="shop-edit__title">代表者の変更</h1>

    <form action="{{ route('shops.update', $shop) }}" method="POST" class="shop-edit__form">
        @csrf
        @method('PUT')

        <div class="shop-edit__form-group">
            <label for="representative_id" class="shop-edit__label">代表者</label>
            <select name="representative_id" id="representative_id" class="shop-edit__input @error('representative_id') is-invalid @enderror">
                <option value="">代表者を選択してください</option>
                @foreach ($representatives as $representative)
                    <option value="{{ $representative->id }}" {{ old('representative_id', $shop->representative_id) == $representative->id ? 'selected' : '' }}>
                        {{ $representative->name }} ({{ $representative->email }})
                    </option>
                @endforeach
            </select>
            @error('representative_id')
                <div class="shop-edit__error">{{ $message }}</div>
            @enderror
        </div>

        <div class="shop-edit__actions">
            <button type="submit" class="shop-edit__button">代表者の変更</button>
            <a href="{{ route('shops.index') }}" class="shop-edit__button shop-edit__button--secondary">キャンセル</a>
        </div>
    </form>
</div>
@endsection
