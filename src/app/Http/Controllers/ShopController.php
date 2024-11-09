<?php

namespace App\Http\Controllers;

use App\Models\Shop;
use App\Models\Area;
use App\Models\Genre;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    public function showShopList(Request $request)
{
    $query = Shop::query();

    if ($request->filled('area')) {
        $query->where('area_id', $request->area);
    }

    if ($request->filled('genre')) {
        $query->where('genre_id', $request->genre);
    }

    if ($request->filled('search')) {
        $search = $request->search;
        // 全角スペースを半角スペースに変換
        $search = str_replace('　', ' ', $search);
        // 連続する半角スペースを1つにまとめる
        $search = preg_replace('/\s+/', ' ', $search);
        // 半角スペースで分割
        $searchTerms = explode(' ', $search);

        $query->where(function($q) use ($searchTerms) {
            foreach ($searchTerms as $term) {
                $q->where(function($subQ) use ($term) {
                    $subQ->where('name', 'like', '%' . $term . '%')
                        ->orWhereHas('area', function($areaQ) use ($term) {
                            $areaQ->where('name', 'like', '%' . $term . '%')
                                ->orWhere('name_kana', 'like', '%' . $term . '%')
                                ->orWhere('name_katakana', 'like', '%' . $term . '%');
                        })
                        ->orWhereHas('genre', function($genreQ) use ($term) {
                            $genreQ->where('name', 'like', '%' . $term . '%');
                        });
                });
            }
        });
    }
    $userId = auth()->id();

    $shops = $query->with(['area', 'genre'])
        ->withCount(['favorites' => function ($query) use ($userId) {
            $query->where('user_id', $userId);
        }])
        ->paginate(12);

    $areas = Area::all();
    $genres = Genre::all();

    return view('shop_list', compact('shops', 'areas', 'genres'));
}

    public function showShopDetail($shop_id)
    {
        $shop = Shop::with(['area', 'genre'])->findOrFail($shop_id);
        return view('shop_detail', compact('shop'));
    }

    public function toggleFavorite(Request $request, $shop_id)
    {
        $user = auth()->user();
        $shop = Shop::findOrFail($shop_id);

        $favorite = $user->favorites()->where('shop_id', $shop_id)->first();

        if ($favorite) {
            $favorite->delete();
        } else {
            $user->favorites()->create(['shop_id' => $shop_id]);
        }

        return redirect()->back();
    }
}