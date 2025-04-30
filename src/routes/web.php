<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\Admin\ShopController as AdminShopController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\RepresentativeController as AdminRepresentativeController;
use App\Http\Controllers\Admin\NoticeMailController as AdminNoticeMailController;
use App\Http\Controllers\Admin\AdminProfileController;
use App\Http\Controllers\Representative\DashboardController as RepresentativeDashboardController;
use App\Http\Controllers\Representative\ShopController as RepresentativeShopController;
use App\Http\Controllers\Representative\ReservationController as RepresentativeReservationController;
use App\Http\Controllers\Representative\ProfileController as RepresentativeProfileController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;

/*------------------------------------------
  公開ルート（認証不要）
--------------------------------------------*/
Route::get('/', [ShopController::class, 'showShopList'])->name('shop.list');
Route::get('/detail/{shop_id}', [ShopController::class, 'showShopDetail'])->name('shop.detail');
Route::get('/shop/{shop_id}/reviews', [ShopController::class, 'showReviews'])->name('shop.reviews');
Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();
    return redirect()->route('thanks');
})->middleware(['auth', 'signed'])->name('verification.verify');

/*------------------------------------------
  認証済み一般ユーザー用ルート
--------------------------------------------*/
Route::middleware(['auth'])->group(function () {
    // ユーザー関連
    Route::get('/mypage', [UserController::class, 'showUserPage'])->name('mypage');

    // お気に入り
    Route::post('/shop/{shop}/favorite', [ShopController::class, 'toggleFavorite'])->name('shop.favorite');

    // 予約管理
    Route::prefix('reserve')->group(function () {
        Route::post('/{shop_id}', [ReservationController::class, 'makeReservation'])->name('reservation.store');
        Route::delete('/{reservation_id}', [ReservationController::class, 'deleteReservation'])->name('reservation.delete');
        Route::get('/done', [ReservationController::class, 'showReservation'])->name('reservation.show');
        Route::get('/{reservation_id}/edit', [ReservationController::class, 'editReservation'])->name('reservation.edit');
        Route::put('/{reservation_id}', [ReservationController::class, 'updateReservation'])->name('reservation.update');
    });

    Route::get('/reservation/verify/{id}', [ReservationController::class, 'verify'])->name('reservation.verify');

    // レビュー管理
    Route::prefix('review')->name('review.')->group(function () {
        Route::get('/create/{shop_id}', [ReviewController::class, 'create'])->name('create');
        Route::get('/{reservation_id}/edit', [ReviewController::class, 'edit'])->name('edit');
        Route::put('/{reservation_id}', [ReviewController::class, 'update'])->name('update');
        Route::delete('/{reservation_id}', [ReviewController::class, 'destroy'])->name('destroy');
    });

    Route::get('/reservation/history', [ReservationController::class, 'showHistory'])->name('reservation.history');
    Route::post('/review/submit', [ReviewController::class, 'submit'])->name('review.submit');
    Route::view('/thanks', 'thanks')->name('thanks');
});

/*------------------------------------------
  管理者用ルート（adminミドルウェアグループ）
--------------------------------------------*/
Route::prefix('admin')->middleware(['auth', 'admin'])->group(function () {
    // ダッシュボード
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');

    // 店舗管理
    Route::prefix('shops')->name('shops.')->group(function () {
        Route::get('/', [AdminShopController::class, 'index'])->name('index');
        Route::get('/create', [AdminShopController::class, 'create'])->name('create');
        Route::post('/', [AdminShopController::class, 'store'])->name('store');
        Route::get('/{shop}/edit', [AdminShopController::class, 'edit'])->name('edit');
        Route::put('/{shop}', [AdminShopController::class, 'update'])->name('update');
        Route::delete('/{shop}', [AdminShopController::class, 'destroy'])->name('destroy');

        // CSVインポート
        Route::post('/import', [AdminShopController::class, 'importCsv'])->name('import');
        Route::get('/import/confirm', [AdminShopController::class, 'showImportConfirm'])->name('import.confirm');
        Route::post('/import/process', [AdminShopController::class, 'processImport'])->name('import.process');
    });

    // 代表者管理
    Route::prefix('representatives')->name('representatives.')->group(function () {
        Route::get('/', [AdminRepresentativeController::class, 'index'])->name('index');
        Route::get('/create', [AdminRepresentativeController::class, 'create'])->name('create');
        Route::post('/', [AdminRepresentativeController::class, 'store'])->name('store');
        Route::get('/{representative}/edit', [AdminRepresentativeController::class, 'edit'])->name('edit');
        Route::put('/{representative}', [AdminRepresentativeController::class, 'update'])->name('update');
        Route::delete('/{representative}', [AdminRepresentativeController::class, 'destroy'])->name('destroy');
    });

    // レビュー管理
    Route::delete('/reviews/{review}', [ReviewController::class, 'adminDestroy'])->name('admin.reviews.destroy');

    // お知らせメール
    Route::prefix('notice-mail')->group(function () {
        Route::get('/', [AdminNoticeMailController::class, 'showForm'])->name('admin.notice_mail.form');
        Route::post('/', [AdminNoticeMailController::class, 'send'])->name('admin.notice_mail.send');
    });

    // プロフィール管理
    Route::prefix('profile')->group(function () {
        Route::get('/edit', [AdminProfileController::class, 'edit'])->name('admin.profile.edit');
        Route::post('/update', [AdminProfileController::class, 'update'])->name('admin.profile.update');
        Route::post('/password', [AdminProfileController::class, 'updatePassword'])->name('admin.profile.password');
    });
});

/*------------------------------------------
  店舗代表者用ルート（representativeミドルウェアグループ）
--------------------------------------------*/
Route::prefix('representative')
    ->middleware(['auth', 'representative'])
    ->name('representative.')
    ->group(function () {
        // ダッシュボード
        Route::get('/dashboard', [RepresentativeDashboardController::class, 'index'])->name('dashboard');

        // 店舗管理
        Route::prefix('shops')->group(function () {
            Route::get('/{shop}/edit', [RepresentativeShopController::class, 'edit'])->name('shops.edit');
            Route::put('/{shop}', [RepresentativeShopController::class, 'update'])->name('shops.update');
        });

        // 予約管理
        Route::prefix('reservations')->group(function () {
            Route::get('/', [RepresentativeReservationController::class, 'index'])->name('reservations.index');
            Route::get('/{reservation}', [RepresentativeReservationController::class, 'show'])->name('reservations.show');
        });

        // レビュー確認（修正: zzReviewController → ReviewController）
        Route::get('/shop/{shop_id}/reviews', [ReviewController::class, 'index'])->name('shop.reviews');

        // パスワード管理
        Route::prefix('password')->group(function () {
            Route::get('/edit', [RepresentativeProfileController::class, 'editPassword'])->name('password.edit');
            Route::post('/update', [RepresentativeProfileController::class, 'updatePassword'])->name('password.update');
        });
    });
