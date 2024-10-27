@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="{{ asset('css/shop_detail.css') }}">

<div class="main">
    <div class="left">
        <div class="header">
            <a href="{{ url()->previous() }}" class="back-btn">&lt;</a>
            <h2>{{ $shop->name }}</h2>
        </div>
        <img src="{{ $shop->image_url }}" alt="{{ $shop->name }}" class="shop-image">
        <p>#{{ $shop->area->name }} #{{ $shop->genre->name }}</p>
        <p>{{ $shop->description }}</p>
    </div>
    <div class="right">
        <h3>予約</h3>
        <form action="{{ route('reservation.show', ['shop_id' => $shop->id]) }}" method="GET">
            @csrf
            <input type="date" name="date" class="input" value="{{ request('date') }}" onchange="this.form.submit()">
            <input type="time" name="time" class="input" value="{{ request('time') }}" onchange="this.form.submit()">
            <select name="number_of_people" class="input" onchange="this.form.submit()">
                @for ($i = 1; $i <= 10; $i++)
                    <option value="{{ $i }}" {{ request('number_of_people') == $i ? 'selected' : '' }}>{{ $i }}人</option>
                @endfor
            </select>
            <div class="summary">
                <p>Shop: {{ $shop->name }}</p>
                @if(request('date'))
                    <p>Date: {{ request('date') }}</p>
                @endif
                @if(request('time'))
                    <p>Time: {{ request('time') }}</p>
                @endif
                @if(request('number_of_people'))
                    <p>Number: {{ request('number_of_people') }}人</p>
                @endif
            </div>
            <button type="submit" class="submit-btn">予約する</button>
        </form>
    </div>
</div>

@endsection