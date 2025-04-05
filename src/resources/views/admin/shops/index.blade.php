@extends('layouts.app')

@section('title', '店舗一覧')

@section('content')
<div class="container">
    <h1>店舗一覧</h1>
    <a href="{{ route('shops.create') }}" class="btn btn-primary mb-3">新規店舗作成</a>

    @if($shops->isEmpty())
        <p>店舗が登録されていません。</p>
    @else
        <table class="table table-striped">
            <thead class="thead-dark">
                <tr>
                    <th>ID</th>
                    <th>名前</th>
                    <th>エリア</th>
                    <th>ジャンル</th>
                    <th>代表者</th>
                    <th>操作</th>
                </tr>
            </thead>
            <tbody>
                @foreach($shops as $shop)
                    <tr>
                        <td>{{ $shop->id }}</td>
                        <td>{{ $shop->name }}</td>
                        <td>{{ $shop->area->name }}</td>
                        <td>{{ $shop->genre->name }}</td>
                        <td>{{ $shop->representative->name ?? '未設定' }}</td>
                        <td>
                            <a href="{{ route('shops.edit', $shop) }}" class="btn btn-sm btn-warning">編集</a>
                            <form action="{{ route('shops.destroy', $shop) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('本当に削除しますか？')">削除</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="d-flex justify-content-center">
            {{ $shops->links() }}
        </div>
    @endif
</div>
@endsection
