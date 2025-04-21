<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Favorite;
use App\Models\Reservation;
use Carbon\Carbon;

class UserController extends Controller
{
    public function showUserPage()
    {
        $user = auth()->user();
        $favorites = $user->favorites()->with('shop')->get();
        $reservations = $user->reservations()->with('shop')->get();

        $currentDate = Carbon::now();
        list($future_reservations, $past_reservations) = $this->organizeReservations($reservations, $currentDate);

        return view('mypage', compact('favorites', 'future_reservations'));
    }

    public function showReservationHistory()
    {
        $user = auth()->user();
        $reservations = $user->reservations()->with('shop')->get();

        $currentDate = Carbon::now();
        list($future_reservations, $past_reservations) = $this->organizeReservations($reservations, $currentDate);

        return view('reservation_history', compact('past_reservations'));
    }

    private function organizeReservations($reservations, $currentDate)
    {
        $future = collect();
        $past = collect();

        foreach ($reservations as $reservation) {
            try {
                // 日付と時刻を厳密に結合
                $datetime = Carbon::createFromFormat(
                    'Y-m-d H:i:s',
                    $reservation->date->format('Y-m-d') . ' ' . $reservation->time->format('H:i:s')
                );

                if ($datetime->gt($currentDate)) {
                    $future->push($reservation);
                } else {
                    $past->push($reservation);
                }
            } catch (\Exception $e) {
                // エラーログに記録
                \Log::error("予約日時パースエラー: {$reservation->id} - " . $e->getMessage());
                continue;
            }
        }

        return [$future, $past];
    }
}