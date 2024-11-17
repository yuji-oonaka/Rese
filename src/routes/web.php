<?php

use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Http\Controllers\AuthenticatedSessionController;
use Laravel\Fortify\Http\Controllers\RegisteredUserController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\FavoriteController;



Route::get('/', [ShopController::class, 'showShopList'])->name('shop.list');
Route::get('/detail/{shop_id}', [ShopController::class, 'showShopDetail'])->name('shop.detail');

Route::middleware(['auth'])->group(function () {
    Route::get('/mypage', [UserController::class, 'showUserPage'])->name('mypage');
    Route::post('/shop/{shop}/favorite', [ShopController::class, 'toggleFavorite'])->name('shop.favorite');
    Route::post('/favorite', [FavoriteController::class, 'makeFavorite']);
    Route::post('/favorite/delete', [FavoriteController::class, 'deleteFavorite']);
    Route::post('/reserve/{shop_id}', [ReservationController::class, 'makeReservation'])->name('reservation.store');
    Route::delete('/reserve/{reservation_id}', [ReservationController::class, 'deleteReservation'])->name('reservation.delete');
    Route::get('/reserve/done', [ReservationController::class, 'showReservation'])->name('reservation.show');
    Route::get('/thanks', function () {
        return view('thanks');
    })->name('thanks');
    // 予約編集ページ表示用ルート
    Route::get('/reserve/{reservation_id}/edit', [ReservationController::class, 'editReservation'])->name('reservation.edit');

    // 予約更新処理用ルート
    Route::put('/reserve/{reservation_id}', [ReservationController::class, 'updateReservation'])->name('reservation.update');
    });