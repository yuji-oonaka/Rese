<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Shop;

class ShopController extends Controller
{
    public function showShopList()
    {
        $shops = Shop::with(['area', 'genre'])->paginate(12);
        return view('shop_list', compact('shops'));
    }

    public function showShopDetail($shop_id)
    {
        $shop = Shop::with(['area', 'genre'])->findOrFail($shop_id);
        return view('shop_detail', compact('shop'));
    }
}
