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

    public function deleteReservation(Request $request, $reservation_id)
    {
        // 予約を取得
        $reservation = Reservation::findOrFail($reservation_id);

        // 現在のユーザーが予約者であることを確認
        if ($reservation->user_id !== auth()->id()) {
            return redirect()->route('mypage')->with('error', '予約の削除権限がありません。');
        }

        // 予約を削除
        $reservation->delete();

        // マイページにリダイレクトし、成功メッセージを表示
        return redirect()->route('mypage')->with('success', '予約が削除されました。');
    }

    // 予約完了ページ表示用メソッド
    public function showReservation()
    {
        return view('reservation_complete'); // 完了ページのビューを返す
    }
}