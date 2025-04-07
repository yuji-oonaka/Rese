<?php

namespace App\Http\Controllers\Representative;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Reservation;

class ReservationController extends Controller
{
    public function index()
    {
        // 自分の店舗に紐づく予約一覧を取得
        $reservations = Reservation::whereHas('shop', function ($query) {
            $query->where('representative_id', auth()->id());
        })->paginate(10);

        return view('representative.reservations.index', compact('reservations'));
    }
}
