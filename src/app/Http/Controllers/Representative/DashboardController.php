<?php

namespace App\Http\Controllers\Representative;

use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function index()
    {
        // 自分が管理する店舗情報を取得
        $shop = auth()->user()->managedShop;
        
        return view('representative.dashboard', compact('shop'));
    }
}
