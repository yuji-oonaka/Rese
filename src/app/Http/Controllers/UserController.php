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
        $future_reservations = $reservations->filter(function ($reservation) use ($currentDate) {
            return Carbon::parse($reservation->date . ' ' . $reservation->time)->gt($currentDate);
        });

        $past_reservations = $reservations->filter(function ($reservation) use ($currentDate) {
            return Carbon::parse($reservation->date . ' ' . $reservation->time)->lte($currentDate);
        });

        return [$future_reservations, $past_reservations];
    }
}