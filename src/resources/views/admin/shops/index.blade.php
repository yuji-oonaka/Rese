@extends('layouts.app')

@section('title', '店舗一覧')

@section('css')
<link rel="stylesheet" href="{{ asset('css/shops_index.css') }}">
@endsection

@section('content')
<div class="shop-list">
    <x-header-component />
    <h1 class="shop-list__title">店舗一覧</h1>
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <!-- ダッシュボードへのリンク -->
    <div class="shop-list__actions">
        <a href="{{ route('admin.dashboard') }}" class="shop-list__btn shop-list__btn--secondary">ダッシュボードへ戻る</a>
        <a href="{{ route('shops.create') }}" class="shop-list__btn shop-list__btn--primary">新規店舗作成</a>
    </div>

    @if($shops->isEmpty())
        <p class="shop-list__empty-message">店舗が登録されていません。</p>
    @else
        <div class="shop-list__table-wrapper">
            <table class="shop-list__table">
                <thead class="shop-list__table-header">
                    <tr>
                        <th class="shop-list__table-header-cell">ID</th>
                        <th class="shop-list__table-header-cell">名前</th>
                        <th class="shop-list__table-header-cell">エリア</th>
                        <th class="shop-list__table-header-cell">ジャンル</th>
                        <th class="shop-list__table-header-cell">代表者</th>
                        <th class="shop-list__table-header-cell">操作</th>
                    </tr>
                </thead>
                <tbody class="shop-list__table-body">
                @foreach($shops as $shop)
                    <tr class="shop-list__table-row">
                        <td class="shop-list__table-cell" data-label="ID">{{ $shop->id }}</td>
                        <td class="shop-list__table-cell" data-label="店舗名">{{ $shop->name }}</td>
                        <td class="shop-list__table-cell" data-label="エリア">{{ $shop->area->name }}</td>
                        <td class="shop-list__table-cell" data-label="ジャンル">{{ $shop->genre->name }}</td>
                        <td class="shop-list__table-cell" data-label="代表者">{{ $shop->representative->name ?? '未設定' }}</td>
                        <td class="shop-list__actions-cell" data-label="操作">
                            <a href="{{ route('shops.edit', $shop) }}" class="shop-list__btn shop-list__btn--warning">代表者の変更</a>
                            <form action="{{ route('shops.destroy', $shop) }}" method="POST" class="shop-list__form">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="shop-list__btn shop-list__btn--danger" onclick="return confirm('本当に削除しますか？')">店舗の削除</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
            </table>
        </div>

        <!-- ページネーション -->
        <div class="pagination">
            {{ $shops->links() }}
        </div>
    @endif
</div>
@endsection

@section('js')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const alert = document.querySelector('.alert-success');
    if (alert) {
        setTimeout(() => {
            alert.style.transition = 'opacity 0.7s';
            alert.style.opacity = '0';
            setTimeout(() => alert.remove(), 700);
        }, 3000);
    }
});
</script>
@endsection

