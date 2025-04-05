<?php

namespace App\Http\Controllers\Representative;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Shop;
use App\Models\Area;
use App\Models\Genre;

class ShopController extends Controller
{
    public function edit(Shop $shop)
    {
        if ($shop->representative_id !== auth()->id()) {
            abort(403, "この店舗の編集権限がありません");
        }

        $areas = Area::all(); // エリアデータを取得
        $genres = Genre::all(); // ジャンルデータを取得

        return view('representative.shops.edit', compact('shop', 'areas', 'genres'));
    }

    public function update(Request $request, Shop $shop)
    {
        if ($shop->representative_id !== auth()->id()) {
            abort(403, "この店舗の編集権限がありません");
        }

        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:1000',
            // 必要に応じて他のフィールドも追加可能
        ]);

        $shop->update($validatedData);

        return redirect()->route('representative.dashboard')->with('success', "店舗情報が更新されました");
    }
}
