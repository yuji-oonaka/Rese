<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Shop;
use App\Models\User;
use App\Models\Area;
use App\Models\Genre;
use Illuminate\Support\Facades\Validator;

class ShopController extends Controller
{
    public function index()
    {
        // 店舗一覧を取得
        $shops = Shop::with(['area', 'genre', 'representative'])->paginate(10);
        return view('admin.shops.index', compact('shops'));
    }

    public function create()
    {
        // エリアとジャンルを取得
        $areas = Area::all();
        $genres = Genre::all();
        $representatives = User::role('representative')->get(); // 代表者を取得

        // ビューにデータを渡す
        return view('admin.shops.create', compact('areas', 'genres', 'representatives'));
    }

    public function store(Request $request)
    {
        // 新しい店舗を保存
        $validatedData = $request->validate([
        'name' => 'required|string|max:50',
        'area_id' => 'required|exists:areas,id',
        'genre_id' => 'required|exists:genres,id',
        'description' => 'required|string|max:400',
        'image_url' => ['required', 'url', 'regex:/\.(jpg|jpeg|png)$/i'],
        'representative_id' => 'nullable|exists:users,id',
    ]);

        Shop::create($validatedData);

        return redirect()->route('shops.index')->with('success', '店舗が作成されました');
    }

    public function edit(Shop $shop)
    {
        // 店舗編集フォーム表示
        $areas = Area::all();
        $genres = Genre::all();
        $representatives = User::role('representative')->get();
        
        return view('admin.shops.edit', compact('shop', 'areas', 'genres', 'representatives'));
    }

    public function update(Request $request, Shop $shop)
    {
        // 店舗情報を更新
        $validatedData = $request->validate([
            'name' => 'required|string|max:50',
            'area_id' => 'required|exists:areas,id',
            'genre_id' => 'required|exists:genres,id',
            'description' => 'required|string|max:400',
            'image_url' => 'required|url|regex:/\.(jpg|jpeg|png)$/i',
            'representative_id' => 'nullable|exists:users,id',
        ]);

        $shop->update($validatedData);

        return redirect()->route('shops.index')->with('success', '店舗情報が更新されました');
    }

    public function destroy(Shop $shop)
    {
        // 店舗削除
        $shop->delete();
        return redirect()->route('shops.index')->with('success', '店舗が削除されました');
    }

    public function importCsv(Request $request)
    {
        // ファイルのバリデーション
        $validator = Validator::make($request->all(), [
            'csv_file' => 'required|file|mimes:csv,txt|max:2048',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // CSVファイルの読み込み
        $file = $request->file('csv_file');
        $data = array_map('str_getcsv', file($file->getRealPath()));
        
        // ヘッダー行を削除
        $header = array_shift($data);

        // データの検証と保存
        foreach ($data as $row) {
            $shopData = array_combine($header, $row);

            // データバリデーション
            $validator = Validator::make($shopData, [
                '店舗名' => 'required|string|max:50',
                '地域' => 'required|in:東京都,大阪府,福岡県',
                'ジャンル' => 'required|in:寿司,焼肉,イタリアン,居酒屋,ラーメン',
                '店舗概要' => 'required|string|max:400',
                '画像URL' => 'required|url|regex:/\.(jpg|jpeg|png)$/i',
            ]);

            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            // 地域とジャンルのID取得
            $area = Area::where('name', $shopData['地域'])->first();
            $genre = Genre::where('name', $shopData['ジャンル'])->first();

            if (!$area || !$genre) {
                return redirect()->back()->with('error', '地域またはジャンルが無効です。');
            }

            // 店舗データの作成
            Shop::create([
                'name' => $shopData['店舗名'],
                'area_id' => $area->id,
                'genre_id' => $genre->id,
                'description' => $shopData['店舗概要'],
                'image_url' => $shopData['画像URL'],
            ]);
        }

        return redirect()->route('shops.index')->with('success', 'CSVファイルから店舗情報をインポートしました。');
    }
}
