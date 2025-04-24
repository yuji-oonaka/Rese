@extends('layouts.app')

@section('title', '代表者一覧')

@section('css')
<link rel="stylesheet" href="{{ asset('css/shops_index.css') }}">
@endsection

@section('content')
<div class="shop-list">
    <x-header-component />
    <h1 class="shop-list__title">代表者一覧</h1>

    <div class="shop-list__actions">
        <a href="{{ route('admin.dashboard') }}" class="shop-list__btn shop-list__btn--secondary">ダッシュボードへ戻る</a>
        <a href="{{ route('representatives.create') }}" class="shop-list__btn shop-list__btn--primary">新規代表者作成</a>
    </div>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="shop-list__table-wrapper">
        <table class="shop-list__table">
            <thead>
                <tr>
                    <th class="shop-list__table-header-cell">ID</th>
                    <th class="shop-list__table-header-cell">名前</th>
                    <th class="shop-list__table-header-cell">メールアドレス</th>
                    <th class="shop-list__table-header-cell">担当店舗</th>
                    <th class="shop-list__table-header-cell">操作</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($representatives as $representative)
                <tr class="shop-list__table-row">
                    <td class="shop-list__table-cell">{{ $representative->id }}</td>
                    <td class="shop-list__table-cell">{{ $representative->name }}</td>
                    <td class="shop-list__table-cell">{{ $representative->email }}</td>
                    <td class="shop-list__table-cell">
                        @if ($representative->managedShops->count())
                            @foreach($representative->managedShops as $shop)
                                {{ $shop->name }}@if(!$loop->last), @endif
                            @endforeach
                        @else
                            未割当
                        @endif
                    </td>
                    <td class="shop-list__table-cell shop-list__actions-cell">
                        <a href="{{ route('representatives.edit', $representative) }}" class="shop-list__btn shop-list__btn--warning">代表者情報の編集</a>
                        <form action="{{ route('representatives.destroy', $representative) }}" method="POST" onsubmit="return confirm('本当に削除しますか？');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="shop-list__btn shop-list__btn--danger">代表者削除</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td class="shop-list__table-cell" colspan="5">代表者が登録されていません。</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="pagination">
        {{ $representatives->links() }}
    </div>
</div>
@endsection
