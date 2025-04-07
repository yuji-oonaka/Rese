<?php

namespace App\Http\Controllers\Representative;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Shop;
use App\Models\Area;
use App\Models\Genre;
use Illuminate\Support\Facades\Storage;

class ShopController extends Controller
{
    public function edit(Shop $shop)
    {
        if ($shop->representative_id !== auth()->id()) {
            abort(403, "この店舗の編集権限がありません");
        }

        $areas = Area::all();
        $genres = Genre::all();

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
            'area_id' => 'required|exists:areas,id',
            'genre_id' => 'required|exists:genres,id',
            'image_url' => 'nullable|url',
            'image_file' => 'nullable|file|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        // 画像ファイルのアップロード処理
        if ($request->hasFile('image_file')) {
            // 古い画像の削除
            if ($shop->image_url) {
                $oldImagePath = str_replace('/storage', '', parse_url($shop->image_url, PHP_URL_PATH));
                Storage::disk('public')->delete($oldImagePath);
            }

            // 新しい画像の保存
            $path = $request->file('image_file')->store('shops', 'public');
            $validatedData['image_url'] = Storage::disk('public')->url($path);
        }

        $shop->update($validatedData);

        return redirect()->route('representative.dashboard')
            ->with('success', '店舗情報が更新されました');
    }
}
