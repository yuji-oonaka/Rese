@extends('layouts.app')

@section('title', '予約確認')

@section('css')
<link rel="stylesheet" href="{{ asset('css/verify.css') }}">
@endsection

@section('content')
<div class="verify-container">
    <div class="reservation-details">
        <h2>予約確認</h2>
        <table>
            <tr>
                <th>予約番号</th>
                <td>{{ $reservation->id }}</td>
            </tr>
            <tr>
                <th>お客様名</th>
                <td>{{ $reservation->user->name }}様</td>
            </tr>
            <tr>
                <th>店舗名</th>
                <td>{{ $reservation->shop->name }}</td>
            </tr>
            <tr>
                <th>予約日</th>
                <td>{{ $reservation->date }}</td>
            </tr>
            <tr>
                <th>時間</th>
                <td>{{ $reservation->time }}</td>
            </tr>
            <tr>
                <th>人数</th>
                <td>{{ $reservation->number_of_people }}人</td>
            </tr>
        </table>
    </div>
</div>
@endsection