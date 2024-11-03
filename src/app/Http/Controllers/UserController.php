<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Favorite;
use App\Models\Reservation;

class UserController extends Controller
{
    public function showUserPage()
    {
        // ログインユーザーのお気に入りと予約情報を取得し、関連する店舗情報もロード
        $favorites = auth()->user()->favorites()->with('shop')->get();
        $reservations = auth()->user()->reservations()->with('shop')->get();

        return view('mypage', compact('favorites', 'reservations'));
    }
}