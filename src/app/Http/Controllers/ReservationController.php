<?php

namespace App\Http\Controllers;

use App\Http\Requests\ReservationRequest;
use Illuminate\Http\Request;
use App\Models\Reservation;
use App\Models\Shop;

class ReservationController extends Controller
{
    public function makeReservation(ReservationRequest $request, $shop_id)
    {

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


    public function updateReservation(Request $request, $reservation_id)
    {
        // 予約情報を取得
        $reservation = Reservation::findOrFail($reservation_id);

        // 現在のユーザーが予約者であることを確認
        if ($reservation->user_id !== auth()->id()) {
            return redirect()->route('mypage')->with('error', '予約の編集権限がありません。');
        }

        // バリデーション
        $request->validate([
            'date' => 'nullable|date|after_or_equal:today', // 日付はnullでもOK
            'time' => 'nullable|date_format:H:i',           // 時間もnullでもOK
            'number_of_people' => 'nullable|integer|min:1', // 人数もnullでもOK
        ]);

        // 更新処理：リクエストに値があれば更新し、なければ元の値を保持
        $reservation->update([
            'date' => $request->input('date') ?? $reservation->date,
            'time' => $request->input('time') ?? $reservation->time,
            'number_of_people' => $request->input('number_of_people') ?? $reservation->number_of_people,
        ]);

        return redirect()->route('mypage')->with('success', '予約が更新されました。');
    }
}