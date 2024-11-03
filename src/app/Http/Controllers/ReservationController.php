<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reservation;
use App\Models\Shop;

class ReservationController extends Controller
{
    public function makeReservation(Request $request, $shop_id)
    {
        // バリデーション
        $request->validate([
            'date' => 'required|date',
            'time' => 'required',
            'number_of_people' => 'required|integer|min:1',
        ]);

        // 予約処理
        Reservation::create([
            'user_id' => auth()->id(),
            'shop_id' => $shop_id,
            'date' => $request->input('date'),
            'time' => $request->input('time'),
            'number_of_people' => $request->input('number_of_people'),
        ]);

        // 予約完了ページにリダイレクト
        return redirect()->route('reservation.show')->with('success', '予約が完了しました。');
    }

    // 予約完了ページ表示用メソッド
    public function showReservation()
    {
        return view('reservation_complete'); // 完了ページのビューを返す
    }
}