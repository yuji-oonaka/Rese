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

        // ソートオプションを取得（デフォルトはランダム）
        $sortOption = $request->get('sort', 'random');

        $isSearched = $request->filled('area') || $request->filled('genre') || $request->filled('search');

        if ($request->filled('area')) {
            $query->where('area_id', $request->area);
        }

        if ($request->filled('genre')) {
            $query->where('genre_id', $request->genre);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $search = str_replace('　', ' ', $search);
            $search = preg_replace('/\s+/', ' ', $search);
            $searchTerms = explode(' ', $search);

            $query->where(function ($q) use ($searchTerms) {
                foreach ($searchTerms as $term) {
                    $q->where(function ($subQ) use ($term) {
                        $subQ->where('name', 'like', '%' . $term . '%')
                            ->orWhereHas('area', function ($areaQ) use ($term) {
                                $areaQ->where('name', 'like', '%' . $term . '%')
                                    ->orWhere('name_kana', 'like', '%' . $term . '%')
                                    ->orWhere('name_katakana', 'like', '%' . $term . '%');
                            })
                            ->orWhereHas('genre', function ($genreQ) use ($term) {
                                $genreQ->where('name', 'like', '%' . $term . '%');
                            });
                    });
                }
            });
        }

        $userId = auth()->id();

        // 基本クエリ構築
        $query = $query->with(['area', 'genre', 'reviews'])
            ->withCount(['favorites' => function ($query) use ($userId) {
                $query->where('user_id', $userId);
            }])
            ->withAvg('reviews', 'rating')
            ->withCount('reviews');

        // ソート適用
        switch ($sortOption) {
            case 'rating_high':
                // 評価が高い順、NULL値は最後
                $query->orderByRaw('CASE WHEN reviews_avg_rating IS NULL THEN 0 ELSE 1 END DESC')
                    ->orderBy('reviews_avg_rating', 'DESC');
                break;

            case 'rating_low':
                // 評価が低い順、NULL値は最後
                $query->orderByRaw('CASE WHEN reviews_avg_rating IS NULL THEN 0 ELSE 1 END DESC')
                    ->orderBy('reviews_avg_rating', 'ASC');
                break;

            case 'random':
            default:
                // ランダム順
                $query->inRandomOrder();
                break;
        }

        // 結果取得とページネーション
        $shops = $query->paginate(12);

        $shops->each(function ($shop) {
            $shop->averageRating = $shop->reviews_avg_rating ?? 0;
            $shop->fullStars = floor($shop->averageRating);
            $shop->halfStar = $shop->averageRating - $shop->fullStars >= 0.5;
        });

        $areas = Area::all();
        $genres = Genre::all();

        return view('shop_list', compact('shops', 'areas', 'genres', 'isSearched', 'sortOption'));
    }


    public function showShopDetail($shop_id)
    {
        $shop = Shop::with(['area', 'genre', 'reviews.user'])
            ->withAvg('reviews', 'rating')
            ->withCount('reviews')
            ->findOrFail($shop_id);

        // ログインユーザーの口コミ有無チェック
        $hasReviewed = auth()->check() 
            ? $shop->reviews()->where('user_id', auth()->id())->exists()
            : false;

        return view('shop_detail', compact('shop', 'hasReviewed'));
    }

    public function showReviews($shop_id)
    {
        $shop = Shop::with(['reviews.user'])->findOrFail($shop_id);
        $reviews = $shop->reviews()
            ->with('user')
            ->latest()
            ->paginate(5);

        return view('shop_reviews', compact('shop', 'reviews'));
    }

    public function toggleFavorite(Request $request, $shop_id)
    {
        try {
            $user = auth()->user();
            $shop = Shop::findOrFail($shop_id);

            $favorite = $user->favorites()->where('shop_id', $shop_id)->first();
            $status = false;

            if ($favorite) {
                $favorite->delete();
                $message = 'お気に入りを解除しました';
            } else {
                $user->favorites()->create(['shop_id' => $shop_id]);
                $status = true;
                $message = 'お気に入りに追加しました';
            }

            return response()->json([
                'success' => true,
                'status' => $status,
                'message' => $message
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'エラーが発生しました'
            ], 500);
        }
    }
}