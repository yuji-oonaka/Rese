<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use App\Models\Review;
use App\Models\Shop;
use App\Models\Reservation;

class ReviewController extends Controller
{

    public function create($shop_id)
    {
        $shop = Shop::findOrFail($shop_id);

        $isFavorite = $shop->favorites()->where('user_id', auth()->id())->exists();

        // 予約済み&来店済みか確認
        $pastReservations = Reservation::where('user_id', auth()->id())
            ->where('shop_id', $shop_id)
            ->where(function($query) {
                $query->where('date', '<', now()->toDateString())
                    ->orWhere(function($q) {
                        $q->where('date', '=', now()->toDateString())
                            ->where('time', '<', now()->toTimeString());
                    });
            })
            ->exists();
            
        if (!$pastReservations) {
            return redirect()->route('shop.detail', ['shop_id' => $shop_id])
                ->with('error', 'レビューを投稿するには、予約・来店済みである必要があります。');
        }
        
        // すでにレビュー投稿済みか確認
        $existingReview = Review::where('user_id', auth()->id())
            ->where('shop_id', $shop_id)
            ->first();
            
        if ($existingReview) {
            return redirect()->route('shop.detail', ['shop_id' => $shop_id])
                ->with('error', 'この店舗には既にレビューを投稿済みです。');
        }
        
        return view('reviews.create', compact('shop', 'isFavorite'));

    }

    public function submit(Request $request)
    {
        // バリデーション
        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|max:400',
            'image' => 'nullable|image|mimes:jpeg,png|max:2048',
        ]);

        // 予約情報を取得
        $reservation = Reservation::findOrFail($request->reservation_id);

        // ユーザーが予約者本人であることを確認
        if ($reservation->user_id !== auth()->id()) {
            return redirect()->back()->with('error', 'この予約の評価を送信する権限がありません。');
        }

        // 予約日時が過去であることを確認
        if ($reservation->date > now()->toDateString() ||
            ($reservation->date == now()->toDateString() && $reservation->time > now()->toTimeString())) {
            return redirect()->back()->with('error', '予約日時が過ぎてからレビューを投稿してください。');
        }

        // 画像処理
        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('reviews', 'public');
        }

        // レビューの作成または更新
        Review::updateOrCreate(
            ['reservation_id' => $request->reservation_id],
            [
                'user_id' => auth()->id(),
                'shop_id' => $reservation->shop_id,
                'rating' => $request->rating,
                'comment' => $request->comment,
                'image_path' => $imagePath,
            ]
        );

        // 投稿後に口コミ一覧ページへリダイレクト
        return redirect()->route('shop.reviews', ['shop_id' => $reservation->shop_id])
                        ->with('success', '口コミが投稿されました！');
    }

    // 既存のレビューを編集するメソッド
    public function edit($reservation_id)
    {
        $review = Review::where('reservation_id', $reservation_id)->firstOrFail();
        
        // 権限チェック
        if ($review->user_id !== auth()->id()) {
            return redirect()->back()->with('error', '自分のレビューのみ編集できます');
        }
        
        $reservation = Reservation::findOrFail($reservation_id);
        
        return view('reviews.edit', compact('review', 'reservation'));
    }
    
    // レビュー更新処理
    public function update(Request $request, $reservation_id)
    {
        $review = Review::where('reservation_id', $reservation_id)->firstOrFail();
        
        // 権限チェック
        if ($review->user_id !== auth()->id()) {
            return redirect()->back()->with('error', '自分のレビューのみ編集できます');
        }
        
        $validator = Validator::make($request->all(), [
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:400',
            'image' => 'nullable|image|mimes:jpeg,png|max:2048',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        
        // 画像処理
        if ($request->hasFile('image')) {
            // 古い画像を削除
            if ($review->image_path) {
                Storage::disk('public')->delete($review->image_path);
            }
            $imagePath = $request->file('image')->store('reviews', 'public');
            $review->image_path = $imagePath;
        }
        
        $review->rating = $request->rating;
        $review->comment = $request->comment;
        $review->save();
        
        return redirect()->route('reservation.history')->with('success', 'レビューが更新されました');
    }
    
    // レビュー削除処理
    public function destroy($reservation_id)
    {
        $review = Review::where('reservation_id', $reservation_id)->firstOrFail();
        
        // 権限チェック（自分のレビューまたは管理者のみ）
        if (!auth()->user()->hasRole('admin') && $review->user_id !== auth()->id()) {
            return redirect()->back()->with('error', '権限がありません');
        }
        
        // 画像ファイルの削除
        if ($review->image_path) {
            Storage::disk('public')->delete($review->image_path);
        }
        
        $review->delete();
        
        return redirect()->back()->with('success', 'レビューが削除されました');
    }
}
