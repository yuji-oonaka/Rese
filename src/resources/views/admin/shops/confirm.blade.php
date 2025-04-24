@extends('layouts.app')

@section('title', 'CSVインポート内容確認')

@section('css')
<link rel="stylesheet" href="{{ asset('css/csv_import_confirm.css') }}">
@endsection

@section('content')
<div class="csv-import-confirm">
    <h2 class="csv-import-confirm__title">CSVインポート内容確認</h2>

    @if(session('import_errors'))
        <div class="csv-import-confirm__alert csv-import-confirm__alert--danger">
            <ul class="csv-import-confirm__error-list">
                @foreach(session('import_errors') as $error)
                    <li class="csv-import-confirm__error-item">{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if(count($shops) > 0)
        <div class="csv-import-confirm__alert csv-import-confirm__alert--info">
            下記の内容で新規店舗を登録します。よろしければ「インポート実行」を押してください。
        </div>
        <div class="csv-import-confirm__table-wrap">
            <table class="csv-import-confirm__table">
                <thead class="csv-import-confirm__thead">
                    <tr>
                        <th class="csv-import-confirm__th">店舗名</th>
                        <th class="csv-import-confirm__th">地域</th>
                        <th class="csv-import-confirm__th">ジャンル</th>
                        <th class="csv-import-confirm__th">店舗概要</th>
                        <th class="csv-import-confirm__th">画像</th>
                    </tr>
                </thead>
                <tbody class="csv-import-confirm__tbody">
                    @foreach($shops as $shop)
                        <tr class="csv-import-confirm__row">
                            <td class="csv-import-confirm__cell">{{ $shop['name'] }}</td>
                            <td class="csv-import-confirm__cell">{{ $shop['area'] }}</td>
                            <td class="csv-import-confirm__cell">{{ $shop['genre'] }}</td>
                            <td class="csv-import-confirm__cell">{{ Str::limit($shop['description'], 60) }}</td>
                            <td class="csv-import-confirm__cell">
                                <img src="{{ $shop['image_url'] }}" alt="画像" class="csv-import-confirm__image">
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <form action="{{ route('shops.import.process') }}" method="POST" class="csv-import-confirm__form">
            @csrf
            <button type="submit" class="csv-import-confirm__btn csv-import-confirm__btn--primary">インポート実行</button>
            <a href="{{ route('shops.create') }}" class="csv-import-confirm__btn csv-import-confirm__btn--secondary">キャンセル</a>
        </form>
    @else
        <div class="csv-import-confirm__alert csv-import-confirm__alert--warning">
            インポート対象データがありません。
        </div>
    @endif
</div>
@endsection
