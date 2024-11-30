<?php

namespace App\Http\Controllers;

use App\Models\Favorite;
use App\Models\Shop;
use Illuminate\Http\Request;

class FavoriteController extends Controller
{
    public function makeFavorite(Request $request, Shop $shop)
    {
        // 既にお気に入りかどうか確認
        if (!auth()->user()->favorites()->where('shop_id', $shop->id)->exists()) {
            // お気に入りとして登録
            auth()->user()->favorites()->create([
                'shop_id' => $shop->id,
            ]);
        }

        return redirect()->back()->with('success', 'お気に入りに追加しました！');
    }

    public function deleteFavorite(Request $request, Shop $shop)
    {
        // お気に入りから削除
        auth()->user()->favorites()->where('shop_id', $shop->id)->delete();

        return redirect()->back()->with('success', 'お気に入りから削除しました！');
    }
}