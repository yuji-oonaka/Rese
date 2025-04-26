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
use App\Models\Reservation;
use App\Http\Requests\Admin\StoreShopRequest;
use App\Http\Requests\Admin\CsvImportFormRequest;
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

    public function store(StoreShopRequest $request)
    {
        $validatedData = $request->validated();

        // 画像ファイルの保存
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('shops', 'public');
            $validatedData['image_url'] = asset('storage/' . $path);
        }

        unset($validatedData['image']);

        Shop::create($validatedData);

        return redirect()->route('shops.index')->with('success', '店舗が作成されました');
    }

    public function edit(Shop $shop)
    {
        // 店舗編集フォーム表示
        $areas = Area::all();
        $genres = Genre::all();
        $representatives = User::role('representative')->get();

        if ($representatives->isEmpty()) {
            return redirect()->route('representatives.create')
                ->with('info', '店舗に紐づける代表者がいません。まず代表者を作成してください。');
        }

        return view('admin.shops.edit', compact('shop', 'areas', 'genres', 'representatives'));
    }

    public function update(Request $request, Shop $shop)
    {
        $validated = $request->validate([
            'representative_id' => 'required|exists:users,id',
        ]);

        $shop->update([
            'representative_id' => $validated['representative_id'],
        ]);

        return redirect()->route('shops.index')->with('success', '代表者が変更されました');
    }

    public function destroy(Shop $shop)
    {
        // トランザクション開始
        DB::beginTransaction();

        try {
            // 関連予約を削除
            $shop->reservations()->delete();
            
            // 店舗削除
            $shop->delete();

            DB::commit();

            return redirect()->route('shops.index')
                ->with('success', '店舗と関連予約を削除しました');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('店舗削除エラー: ' . $e->getMessage());
            
            return redirect()->route('shops.index')
                ->with('error', '削除中にエラーが発生しました');
        }
    }

    public function importCsv(CsvImportFormRequest $request)
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
                '店舗名.required' => '店舗名は必須です。',
                '店舗名.max' => '店舗名は50文字以内で入力してください。',
                '地域.required' => '地域は必須です。',
                '地域.in' => '地域は「東京都」「大阪府」「福岡県」から選択してください。',
                'ジャンル.required' => 'ジャンルは必須です。',
                'ジャンル.in' => 'ジャンルは「寿司」「焼肉」「イタリアン」「居酒屋」「ラーメン」から選択してください。',
                '店舗概要.required' => '店舗概要は必須です。',
                '店舗概要.max' => '店舗概要は400文字以内で入力してください。',
                '画像URL.required' => '画像URLは必須です。',
                '画像URL.url' => '画像URLが正しくありません。',
                '画像URL.regex' => '画像URLはjpg/jpeg/png形式で指定してください。',
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
