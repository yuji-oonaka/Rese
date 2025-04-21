<?php

namespace App\Http\Controllers\Representative;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Reservation;
use Illuminate\Support\Facades\Auth;

class ReservationController extends Controller
{
    public function index()
    {
        // 代表者自身が管理する店舗の予約のみ表示
        $user = Auth::user();
        $shop = $user->managedShops()->first();
        
        if (!$shop) {
            return redirect()->route('representative.dashboard')
                ->with('error', '管理する店舗が見つかりません');
        }
        
        // 予約一覧を取得（日付の新しい順）
        $reservations = $shop->reservations()
            ->with('user')  // ユーザー情報をEager Loading
            ->orderBy('date', 'desc')
            ->orderBy('time', 'desc')
            ->paginate(10);
            
        return view('representative.reservations.index', compact('shop', 'reservations'));
    }
    
    public function show(Reservation $reservation)
    {
        // 代表者が管理する店舗の予約のみ表示可能
        $user = Auth::user();
        $shop = $user->managedShops()->first();
        
        if (!$shop || $reservation->shop_id !== $shop->id) {
            return redirect()->route('representative.reservations.index')
                ->with('error', 'この予約は表示できません');
        }
        
        return view('representative.reservations.show', compact('reservation'));
    }
}
