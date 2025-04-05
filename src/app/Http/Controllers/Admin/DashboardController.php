<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function index()
    {
        // ダッシュボードで必要なデータを取得
        $representativesCount = \App\Models\User::role('representative')->count();
        $shopsCount = \App\Models\Shop::count();
        $reservationsCount = \App\Models\Reservation::count();

        return view('admin.dashboard', compact('representativesCount', 'shopsCount', 'reservationsCount'));
    }
}
