@extends('layouts.app')

@section('title', '代表者編集')

@section('css')
<link rel="stylesheet" href="{{ asset('css/representative_create.css') }}">
@endsection

@section('content')
<div class="representative-create">
    <x-header-component />
    <h1 class="representative-create__title">代表者編集</h1>
    <form method="POST" action="{{ route('representatives.update', $representative) }}" class="representative-create__form">
        @csrf
        @method('PUT')

        <div class="representative-create__form-group">
            <label for="name" class="representative-create__label">名前 <span class="text-danger">*</span></label>
            <input type="text" id="name" name="name" value="{{ old('name', $representative->name) }}" required class="representative-create__input @error('name') is-invalid @enderror">
            @error('name')
                <div class="representative-create__error">{{ $message }}</div>
            @enderror
        </div>

        <div class="representative-create__form-group">
            <label for="email" class="representative-create__label">メールアドレス <span class="text-danger">*</span></label>
            <input type="email" id="email" name="email" value="{{ old('email', $representative->email) }}" required class="representative-create__input @error('email') is-invalid @enderror">
            @error('email')
                <div class="representative-create__error">{{ $message }}</div>
            @enderror
        </div>
        <div class="representative-create__actions">
            <button type="submit" class="representative-create__button">代表者情報を変更</button>
            <a href="{{ route('representatives.index') }}" class="representative-create__button representative-create__button--secondary">キャンセル</a>
        </div>
    </form>
</div>
@endsection
