<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use App\Models\Shop;
use App\Models\User;
use App\Models\Area;
use App\Models\Genre;
use Illuminate\Support\Facades\Log;

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
        $validatedData = $request->validate([
            'name' => 'required|string|max:50',
            'area_id' => 'required|exists:areas,id',
            'genre_id' => 'required|exists:genres,id',
            'description' => 'required|string|max:400',
            'image' => 'required|image|mimes:jpg,jpeg,png|max:2048',
            'representative_id' => 'nullable|exists:users,id',
        ]);

        // 画像ファイルの保存
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('shops', 'public');
            $validatedData['image_url'] = asset('storage/' . $path);
        }

        unset($validatedData['image']); // image_urlに変換したのでimageは削除

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
        $csvData = file($file->getRealPath());
        $header = array_map('trim', str_getcsv(array_shift($csvData)));

        // ヘッダー検証
        $expectedHeader = ['店舗名', '地域', 'ジャンル', '店舗概要', '画像URL'];
        if ($header !== $expectedHeader) {
            return redirect()->back()
                ->with('error', 'CSVフォーマットが不正です。必要な列: ' . implode(', ', $expectedHeader))
                ->withInput();
        }

        // データ処理
        $errors = [];
        $importData = [];

        foreach ($csvData as $lineNumber => $line) {
            $rowNumber = $lineNumber + 2;
            $row = str_getcsv(trim($line));
            
            // 列数チェック
            if (count($row) !== count($header)) {
                $errors[] = "行{$rowNumber}: 列数が一致しません";
                continue;
            }

            $shopData = array_combine($header, $row);

            // データバリデーション
            $validator = Validator::make($shopData, [
                '店舗名' => 'required|string|max:50',
                '地域' => 'required|in:東京都,大阪府,福岡県',
                'ジャンル' => 'required|in:寿司,焼肉,イタリアン,居酒屋,ラーメン',
                '店舗概要' => 'required|string|max:400',
                '画像URL' => [
                    'required',
                    'url',
                    'regex:/\.(jpe?g|png)$/i',
                ],
            ], [
                '画像URL.regex' => '対応形式: jpg/jpeg/png'
            ]);

            if ($validator->fails()) {
                foreach ($validator->errors()->all() as $message) {
                    $errors[] = "行{$rowNumber}: {$message}";
                }
                continue;
            }

            // 地域とジャンルの存在チェック
            $area = Area::where('name', $shopData['地域'])->first();
            $genre = Genre::where('name', $shopData['ジャンル'])->first();

            if (!$area || !$genre) {
                $errors[] = "行{$rowNumber}: 無効な地域またはジャンル";
                continue;
            }

            $importData[] = $shopData;
        }

        if (!empty($errors)) {
            return redirect()->back()
                ->with('import_errors', $errors)
                ->withInput();
        }

        // セッションにデータを保存して確認画面へ
        Session::put('csv_import_data', $importData);
        return redirect()->route('shops.import.confirm');
    }

    public function showImportConfirm()
    {
        $csvData = Session::get('csv_import_data', []);
        
        if (empty($csvData)) {
            return redirect()->route('shops.create')->with('error', 'インポートデータがありません');
        }

        $shops = [];
        foreach ($csvData as $data) {
            $shops[] = [
                'name' => $data['店舗名'],
                'area' => $data['地域'],
                'genre' => $data['ジャンル'],
                'description' => $data['店舗概要'],
                'image_url' => $data['画像URL']
            ];
        }

        return view('admin.shops.confirm', compact('shops'));
    }

    public function processImport()
    {
        $csvData = Session::get('csv_import_data', []);

        if (empty($csvData)) {
            return redirect()->route('shops.create')->with('error', 'インポートデータがありません');
        }

        DB::beginTransaction();
        try {
            $importCount = 0;
            
            foreach ($csvData as $data) {
                $area = Area::where('name', $data['地域'])->first();
                $genre = Genre::where('name', $data['ジャンル'])->first();

                Shop::create([
                    'name' => $data['店舗名'],
                    'area_id' => $area->id,
                    'genre_id' => $genre->id,
                    'description' => $data['店舗概要'],
                    'image_url' => $data['画像URL'],
                ]);
                $importCount++;
            }

            DB::commit();
            Session::forget('csv_import_data');

            return redirect()->route('shops.index')
                ->with('success', "{$importCount}件の店舗情報をインポートしました");

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('CSVインポートエラー: ' . $e->getMessage());
            return redirect()->route('shops.create')
                ->with('error', 'インポート中にエラーが発生しました: ' . $e->getMessage());
        }
    }
}
