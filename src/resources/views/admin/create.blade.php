@extends('layouts.app')

@section('title', '代表者作成')

@section('content')
<div class="container">
    <h1>新規代表者作成</h1>

    <form method="POST" action="{{ route('representatives.store') }}">
        @csrf
        <div class="form-group">
            <label for="name">名前</label>
            <input type="text" name="name" id="name" class="form-control" required />
        </div>

        <div class="form-group">
            <label for="email">メールアドレス</label>
            <input type="email" name="email" id="email" class="form-control" required />
        </div>

        <div class="form-group">
            <label for="password">パスワード</label>
            <input type="password" name="password" id="password" class="form-control" required />
        </div>

        <button type="submit" class="btn btn-primary">作成する</button>
    </form>
</div>
@endsection
