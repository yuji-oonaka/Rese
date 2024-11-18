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
        // 現在の日時を取得
        $now = now();
        $reservationDateTime = \Carbon\Carbon::parse($request->date . ' ' . $request->time);

        // 過去の日時チェック
        if ($reservationDateTime < $now) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['time' => '過去の日時は選択できません。']);
        }

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

        // 現在の日時を取得
        $now = now();

        // バリデーション
        $validated = $request->validate([
            'date' => [
                'required',
                'date',
                'after_or_equal:today',
            ],
            'time' => [
                'required',
                'date_format:H:i',
                function ($attribute, $value, $fail) use ($request, $now) {
                    $reservationDateTime = \Carbon\Carbon::parse($request->date . ' ' . $value);
                    if ($reservationDateTime < $now) {
                        $fail('過去の日時は選択できません。');
                    }
                },
            ],
            'number_of_people' => 'required|integer|min:1|max:10',
        ]);

        // 更新処理
        $reservation->update([
            'date' => $validated['date'],
            'time' => $validated['time'],
            'number_of_people' => $validated['number_of_people'],
        ]);

        return redirect()->route('mypage')->with('success', '予約が更新されました。');
    }
}