@extends('layouts.app')

@section('title', '店舗作成')

@section('css')
<link rel="stylesheet" href="{{ asset('css/shops_create.css') }}">
@endsection

@section('content')
<div class="shop-create">
    <x-header-component />
    <h1 class="shop-create__title">店舗作成</h1>

    {{-- CSVインポートフォーム --}}
    <form method="POST" action="{{ route('shops.import') }}" enctype="multipart/form-data" class="shop-create__form shop-create__form--csv-import" novalidate autocomplete="off">
        @csrf
        <div class="shop-create__form-group">
            <label for="csv-file" class="shop-create__label">CSVファイルを選択</label>
            <input type="file" id="csv-file" name="csv_file" class="shop-create__input" required autocomplete="off">
            @error('csv_file')
                <div class="error">{{ $message }}</div>
            @enderror
        </div>

        {{-- CSV内容のエラーメッセージ --}}
        @if(session('import_errors'))
            <div class="alert alert-danger mt-2">
                <h4 class="alert-heading">CSV内容のエラー</h4>
                <ul class="mb-0">
                    @foreach(session('import_errors') as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- その他のエラーメッセージ --}}
        @if(session('error'))
            <div class="alert alert-danger mt-2">
                {{ session('error') }}
            </div>
        @endif

        <button type="submit" class="shop-create__btn shop-create__btn--primary">CSVインポート</button>
    </form>

    <hr class="shop-create__divider">

    {{-- 手動入力フォーム --}}
    <form method="POST" action="{{ route('shops.store') }}" class="shop-create__form shop-create__form--manual" enctype="multipart/form-data" novalidate autocomplete="off">
        @csrf
        <div class="shop-create__form-group">
            <label for="shop-name" class="shop-create__label">店舗名</label>
            <input type="text" id="shop-name" name="name" class="shop-create__input" value="{{ old('name') }}" required autocomplete="organization">
            @error('name')
                <div class="error">{{ $message }}</div>
            @enderror
        </div>
        <div class="shop-create__form-group">
            <label for="area-id" class="shop-create__label">エリア</label>
            <select id="area-id" name="area_id" class="shop-create__input shop-create__select" required autocomplete="address-level2">
                @foreach($areas as $area)
                    <option value="{{ $area->id }}" {{ old('area_id') == $area->id ? 'selected' : '' }}>{{ $area->name }}</option>
                @endforeach
            </select>
            @error('area_id')
                <div class="error">{{ $message }}</div>
            @enderror
        </div>
        <div class="shop-create__form-group">
            <label for="genre-id" class="shop-create__label">ジャンル</label>
            <select id="genre-id" name="genre_id" class="shop-create__input shop-create__select" required autocomplete="off">
                @foreach($genres as $genre)
                    <option value="{{ $genre->id }}" {{ old('genre_id') == $genre->id ? 'selected' : '' }}>{{ $genre->name }}</option>
                @endforeach
            </select>
            @error('genre_id')
                <div class="error">{{ $message }}</div>
            @enderror
        </div>
        <div class="shop-create__form-group">
            <label for="description" class="shop-create__label">説明</label>
            <textarea id="description" name="description" class="shop-create__input shop-create__textarea" rows="3" required autocomplete="off">{{ old('description') }}</textarea>
            @error('description')
                <div class="error">{{ $message }}</div>
            @enderror
        </div>
        <div class="shop-create__form-group">
            <label for="image-input" class="shop-create__label">画像アップロード</label>
            <div id="image-drop-area" class="shop-create__drop-area">
                <p id="drop-text">ここに画像をドラッグ＆ドロップ、またはクリックして選択</p>
                <input type="file" name="image" id="image-input" class="shop-create__input" accept="image/*" style="display:none;" autocomplete="off">
                <img id="preview-image" src="" alt="" style="display:none; max-width:100%; margin-top:10px;">
            </div>
            @error('image')
                <div class="error">{{ $message }}</div>
            @enderror
        </div>
        <button type="submit" class="shop-create__btn shop-create__btn--primary">作成</button>
    </form>

    <div class="shop-create__actions">
        <a href="{{ route('admin.dashboard') }}" class="shop-create__btn shop-create__btn--primary">管理者ダッシュボードへ</a>
    </div>
</div>
@endsection

@section('js')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const dropArea = document.getElementById('image-drop-area');
    const input = document.getElementById('image-input');
    const preview = document.getElementById('preview-image');
    const dropText = document.getElementById('drop-text');

    // クリックでファイル選択
    dropArea.addEventListener('click', () => input.click());

    // ドラッグオーバー時のスタイル
    dropArea.addEventListener('dragover', (e) => {
        e.preventDefault();
        dropArea.classList.add('dragover');
    });
    dropArea.addEventListener('dragleave', (e) => {
        e.preventDefault();
        dropArea.classList.remove('dragover');
    });

    // ドロップ時
    dropArea.addEventListener('drop', (e) => {
        e.preventDefault();
        dropArea.classList.remove('dragover');
        if (e.dataTransfer.files && e.dataTransfer.files[0]) {
            input.files = e.dataTransfer.files;
            previewImage(input.files[0]);
        }
    });

    // ファイル選択時
    input.addEventListener('change', function() {
        if (input.files && input.files[0]) {
            previewImage(input.files[0]);
        }
    });

    function previewImage(file) {
        if (!file.type.startsWith('image/')) return;
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.src = e.target.result;
            preview.style.display = 'block';
            dropText.style.display = 'none';
        }
        reader.readAsDataURL(file);
    }
});
</script>
@endsection
