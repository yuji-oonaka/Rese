@extends('layouts.app')

@section('title', '利用者へのメール送信')

@section('css')
<link rel="stylesheet" href="{{ asset('css/notice_mail.css') }}">
@endsection

@section('content')
<div class="notice-mail">
    <x-header-component />
    <h2 class="notice-mail__title">お知らせメール送信</h2>

    @if(session('success'))
        <div class="notice-mail__alert notice-mail__alert--success">
            {{ session('success') }}
        </div>
    @endif

    <form method="POST" action="{{ route('admin.notice_mail.send') }}" class="notice-mail__form" autocomplete="off">
        @csrf
        <div class="notice-mail__form-group">
            <label for="notice-subject" class="notice-mail__label">件名</label>
            <input
                type="text"
                id="notice-subject"
                name="subject"
                class="notice-mail__input"
                required
                autocomplete="off"
            >
        </div>

        <div class="notice-mail__form-group">
            <label for="notice-message" class="notice-mail__label">本文</label>
            <textarea
                id="notice-message"
                name="message"
                class="notice-mail__textarea"
                required
                rows="6"
                autocomplete="off"
            ></textarea>
        </div>

        <button type="submit" class="notice-mail__button">送信</button>
    </form>
</div>
@endsection
