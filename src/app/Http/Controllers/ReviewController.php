<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreReviewRequest;
use App\Http\Requests\UpdateReviewRequest;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use App\Models\Review;
use App\Models\Shop;
use App\Models\Reservation;

class ReviewController extends Controller
{
    // 権限チェック用メソッド
    private function checkPermissions()
    {
        // 店舗ユーザーは全操作禁止
        if (auth()->user()->hasRole('representative')) {
            return redirect()->back()
                ->with('error', '店舗代表者は口コミ操作できません');
        }
        
        // 管理者は投稿/編集禁止（DELETEメソッド以外をブロック）
        if (auth()->user()->hasRole('admin') && !request()->isMethod('delete')) {
            return redirect()->back()
                ->with('error', '管理者は投稿・編集できません');
        }

        return null; // 権限チェックOK
    }

    public function create($shop_id, Request $request)
    {
        if ($permissionResponse = $this->checkPermissions()) {
            return $permissionResponse;
        }

        $shop = Shop::findOrFail($shop_id);

        // 予約済み＆来店済みか確認
        $pastReservations = Reservation::where('user_id', auth()->id())
            ->where('shop_id', $shop_id)
            ->where(function ($query) {
                $query->where('date', '<', now()->toDateString())
                    ->orWhere(function ($q) {
                        $q->where('date', '=', now()->toDateString())
                            ->where('time', '<', now()->toTimeString());
                    });
            })
            ->orderByDesc('date')
            ->orderByDesc('time')
            ->first();

        if (!$pastReservations) {
            return redirect()->route('shop.detail', ['shop_id' => $shop_id])
                ->with('error', '口コミを投稿するには、予約・来店済みである必要があります。');
        }

        // すでにレビュー投稿済みか確認
        $existingReview = Review::where('user_id', auth()->id())
            ->where('shop_id', $shop_id)
            ->first();

        if ($existingReview) {
            return redirect()->route('shop.detail', ['shop_id' => $shop_id])
                ->with('error', 'この店舗には既に口コミを投稿済みです。');
        }

        return view('reviews.create', [
            'shop' => $shop,
            'reservation_id' => $pastReservations->id,
        ]);
    }

    public function submit(StoreReviewRequest $request)
    {
        if ($permissionResponse = $this->checkPermissions()) {
            return $permissionResponse;
        }

        try {
            $validated = $request->validated();
            $reservation = Reservation::with('shop')->findOrFail($validated['reservation_id']);

            // 画像保存処理
            $imagePath = $request->hasFile('image') 
                ? $request->file('image')->store('reviews', 'public')
                : null;

            Review::create([
                'user_id' => auth()->id(),
                'shop_id' => $reservation->shop_id,
                'reservation_id' => $validated['reservation_id'],
                'rating' => $validated['rating'],
                'comment' => $validated['comment'],
                'image_path' => $imagePath,
            ]);

            return redirect()->route('reservation.history')
                ->with('success', '口コミを投稿しました');

        } catch (\Exception $e) {
            \Log::error('投稿エラー: '.$e->getMessage());
            return redirect()->back()
                ->with('error', '投稿に失敗しました');
        }
    }

    public function edit($reservation_id)
    {
        if ($permissionResponse = $this->checkPermissions()) {
            return $permissionResponse;
        }

        $review = Review::where('reservation_id', $reservation_id)->firstOrFail();

        if ($review->user_id !== auth()->id()) {
            return redirect()->back()->with('error', '自分のレビューのみ編集できます');
        }

        $reservation = Reservation::findOrFail($reservation_id);

        return view('reviews.edit', compact('review', 'reservation'));
    }

    public function update(UpdateReviewRequest $request, $reservation_id)
    {
        if ($permissionResponse = $this->checkPermissions()) {
            return $permissionResponse;
        }

        $review = Review::where('reservation_id', $reservation_id)->firstOrFail();

        if ($review->user_id !== auth()->id()) {
            return redirect()->back()->with('error', '自分のレビューのみ編集できます');
        }

        try {
            $validated = $request->validated(); // バリデーション済みデータ取得

            // 画像処理（既存ロジック維持）
            if ($request->hasFile('image')) {
                if ($review->image_path) {
                    Storage::disk('public')->delete($review->image_path);
                }
                $imagePath = $request->file('image')->store('reviews', 'public');
                $review->image_path = $imagePath;
            }

            // 更新処理（既存ロジック維持）
            $review->update([
                'rating' => $validated['rating'],
                'comment' => $validated['comment']
            ]);

            return redirect()->route('reservation.history')
                ->with('success', 'レビューが更新されました');

        } catch (\Exception $e) {
            \Log::error('更新エラー: '.$e->getMessage());
            return redirect()->back()
                ->with('error', '更新に失敗しました');
        }
    }

    public function destroy($reservation_id)
    {
        if ($permissionResponse = $this->checkPermissions()) {
            return $permissionResponse;
        }

        // 管理者は専用ルートを使用させる
        if (auth()->user()->hasRole('admin')) {
            return redirect()->back()
                ->with('error', '管理者は専用の削除機能を使用してください');
        }

        $review = Review::where('reservation_id', $reservation_id)->firstOrFail();
        
        if ($review->user_id !== auth()->id()) {
            return redirect()->back()->with('error', '権限がありません');
        }
        
        if ($review->image_path) {
            Storage::disk('public')->delete($review->image_path);
        }
        
        $review->delete();
        
        return redirect()->back()->with('success', 'レビューが削除されました');
    }

    public function adminDestroy(Review $review)
    {
        if (!auth()->user()->hasRole('admin')) {
            abort(403);
        }

        if ($review->image_path) {
            Storage::disk('public')->delete($review->image_path);
        }

        $review->delete();

        return redirect()->back()->with('success', '管理者権限で口コミを削除しました');
    }
}
