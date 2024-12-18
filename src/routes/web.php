<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\ReviewController;

// 公開ルート
Route::get('/', [ShopController::class, 'showShopList'])->name('shop.list');
Route::get('/detail/{shop_id}', [ShopController::class, 'showShopDetail'])->name('shop.detail');

// 誰でもアクセス可能なレビュー関連
Route::get('/shop/{shop_id}/reviews', [ShopController::class, 'showReviews'])->name('shop.reviews');

// 認証済みユーザー用ルート
Route::middleware(['auth'])->group(function () {
    // ユーザー関連
    Route::get('/mypage', [UserController::class, 'showUserPage'])->name('mypage');

    // お気に入り関連
    Route::post('/shop/{shop}/favorite', [ShopController::class, 'toggleFavorite'])->name('shop.favorite');
    Route::post('/favorite', [FavoriteController::class, 'makeFavorite']);
    Route::post('/favorite/delete', [FavoriteController::class, 'deleteFavorite']);

    // 予約関連
    Route::prefix('reserve')->group(function () {
        Route::post('/{shop_id}', [ReservationController::class, 'makeReservation'])->name('reservation.store');
        Route::delete('/{reservation_id}', [ReservationController::class, 'deleteReservation'])->name('reservation.delete');
        Route::get('/done', [ReservationController::class, 'showReservation'])->name('reservation.show');
        Route::get('/{reservation_id}/edit', [ReservationController::class, 'editReservation'])->name('reservation.edit');
        Route::put('/{reservation_id}', [ReservationController::class, 'updateReservation'])->name('reservation.update');
    });

    Route::get('/reservation/verify/{id}', [ReservationController::class, 'verify'])->name('reservation.verify');

    // レビュー関連
    Route::get('/reservation/history', [ReservationController::class,   'showHistory'])->name('reservation.history');
    Route::post('/review/submit', [ReviewController::class, 'submit'])->name('review.submit');

    // その他
    Route::view('/thanks', 'thanks')->name('thanks');
});