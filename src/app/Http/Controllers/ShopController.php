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
        $query->where('name', 'like', '%' . $request->search . '%');
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

        if ($user->favorites()->where('shop_id', $shop_id)->exists()) {
            $user->favorites()->detach($shop_id);
            $status = 'removed';
        } else {
            $user->favorites()->attach($shop_id);
            $status = 'added';
        }

        return response()->json(['status' => $status]);
    }
}