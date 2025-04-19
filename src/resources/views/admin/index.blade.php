@extends('layouts.app')

@section('title', '代表者一覧')

@section('content')
<div class="container">
    <x-header-component />
    <h1>代表者一覧</h1>
    <a href="{{ route('representatives.create') }}" class="btn btn-primary">新規代表者作成</a>

    @if($representatives->isEmpty())
        <p>代表者が登録されていません。</p>
    @else
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>名前</th>
                    <th>メールアドレス</th>
                    <th>操作</th>
                </tr>
            </thead>
            <tbody>
                @foreach($representatives as $representative)
                    <tr>
                        <td>{{ $representative->id }}</td>
                        <td>{{ $representative->name }}</td>
                        <td>{{ $representative->email }}</td>
                        <td>
                            <a href="{{ route('representatives.edit', $representative) }}" class="btn btn-warning">編集</a>
                            <form action="{{ route('representatives.destroy', $representative) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger" onclick="return confirm('削除してもよろしいですか？')">削除</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        {{ $representatives->links() }}
    @endif
</div>
@endsection
