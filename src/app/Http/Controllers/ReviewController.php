<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Review;
use App\Models\Reservation;

class ReviewController extends Controller
{
    public function submit(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'reservation_id' => 'required|exists:reservations,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $reservation = Reservation::findOrFail($request->reservation_id);

        // ユーザーが予約者本人であることを確認
        if ($reservation->user_id !== auth()->id()) {
            return redirect()->back()->with('error', 'この予約の評価を送信する権限がありません。');
        }

        // 予約日時が過去であることを確認
        if ($reservation->date > now()->toDateString() ||
            ($reservation->date == now()->toDateString() && $reservation->time > now()->toTimeString())) {
            return redirect()->back()->with('error', '予約日時が過ぎてからレビューを投稿してください。');
        }

        // レビューの作成または更新
        Review::updateOrCreate(
            ['reservation_id' => $request->reservation_id],
            [
                'user_id' => auth()->id(),
                'shop_id' => $reservation->shop_id,
                'rating' => $request->rating,
                'comment' => $request->comment,
            ]
        );

        return redirect()->route('reservation.history')->with('success', '評価が送信されました。ありがとうございます！');
    }
}