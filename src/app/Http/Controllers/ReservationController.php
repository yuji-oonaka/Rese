<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reservation;

class ReservationController extends Controller
{
    public function makeReservation(Request $request, $shop_id)
    {
        // バリデーション
        $validated = $request->validate([
            'date' => 'required|date',
            'time' => 'required',
            'number_of_people' => 'required|integer|min:1',
        ]);

        // 予約の保存処理
        Reservation::create([
            'user_id' => auth()->id(),
            'shop_id' => $shop_id,
            'date' => $validated['date'],
            'time' => $validated['time'],
            'number_of_people' => $validated['number_of_people'],
        ]);

        return redirect()->route('shop.list')->with('success', '予約が完了しました。');
    }
}
