<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\Admin\ShopController as AdminShopController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use App\Http\Controllers\Admin\NoticeMailController;

/*------------------------------------------
  公開ルート（認証不要）
--------------------------------------------*/
Route::get('/', [ShopController::class, 'showShopList'])->name('shop.list');
Route::get('/detail/{shop_id}', [ShopController::class, 'showShopDetail'])->name('shop.detail');
Route::get('/shop/{shop_id}/reviews', [ShopController::class, 'showReviews'])->name('shop.reviews');
Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();
    return redirect()->route('thanks'); // thanksページへ遷移
})->middleware(['auth', 'signed'])->name('verification.verify');


/*------------------------------------------
  認証済み一般ユーザー用ルート
--------------------------------------------*/
Route::middleware(['auth'])->group(function () {
    // ユーザー関連
    Route::get('/mypage', [UserController::class, 'showUserPage'])->name('mypage');

    // お気に入り関連
    Route::post('/shop/{shop}/favorite', [ShopController::class, 'toggleFavorite'])->name('shop.favorite');
    
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
    Route::get('/reservation/history', [ReservationController::class, 'showHistory'])->name('reservation.history');
    Route::post('/review/submit', [ReviewController::class, 'submit'])->name('review.submit');
    Route::get('/reviews/{reservation}/edit', [ReviewController::class, 'edit'])
    ->name('reviews.edit');


    // レビュー機能拡張
    Route::prefix('review')->name('review.')->group(function () {
        // レビュー作成フォーム表示
        Route::get('/create/{shop_id}', [ReviewController::class, 'create'])->name('create');
        // レビュー編集
        Route::get('/{reservation_id}/edit', [ReviewController::class, 'edit'])->name('edit');
        Route::put('/{reservation_id}', [ReviewController::class, 'update'])->name('update');
        // レビュー削除
        Route::delete('/{reservation_id}', [ReviewController::class, 'destroy'])->name('destroy');
    });

    // その他
    Route::view('/thanks', 'thanks')->name('thanks');
});

/*------------------------------------------
  管理者用ルート（adminミドルウェアグループ）
--------------------------------------------*/
Route::prefix('admin')->middleware(['auth', 'admin'])->group(function () {
    // ダッシュボード
    Route::get('/dashboard', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])
        ->name('admin.dashboard');

    // 店舗管理（リソースルート）
    Route::resource('shops', AdminShopController::class);

    // CSVインポート関連ルート（リソースルート内にグループ化）
    Route::prefix('shops')->group(function () {
        Route::post('/import', [AdminShopController::class, 'importCsv'])
            ->name('shops.import'); // ルート名: shops.import
        
        Route::get('/import/confirm', [AdminShopController::class, 'showImportConfirm'])
            ->name('shops.import.confirm'); // ルート名: shops.import.confirm
        
        Route::post('/import/process', [AdminShopController::class, 'processImport'])
            ->name('shops.import.process'); // ルート名: shops.import.process
    });

    // 代表者管理
    Route::resource('representatives', \App\Http\Controllers\Admin\RepresentativeController::class);

    // レビュー管理
    Route::delete('/reviews/{review}', [ReviewController::class, 'adminDestroy'])
        ->name('admin.reviews.destroy');

    // お知らせメール送信フォーム表示
    Route::get('/notice-mail', [\App\Http\Controllers\Admin\NoticeMailController::class, 'showForm'])
        ->name('admin.notice_mail.form');

    // お知らせメール送信処理
    Route::post('/notice-mail', [\App\Http\Controllers\Admin\NoticeMailController::class, 'send'])
        ->name('admin.notice_mail.send');

    // 管理者プロフィール編集
    Route::get('/profile/edit', [\App\Http\Controllers\Admin\AdminProfileController::class, 'edit'])->name('admin.profile.edit');
    Route::post('/profile/update', [\App\Http\Controllers\Admin\AdminProfileController::class, 'update'])->name('admin.profile.update');
    Route::post('/profile/password', [\App\Http\Controllers\Admin\AdminProfileController::class, 'updatePassword'])->name('admin.profile.password');
});



/*------------------------------------------
  店舗代表者用ルート（representativeミドルウェアグループ）
--------------------------------------------*/
Route::prefix('representative')
    ->middleware(['auth', 'representative'])
    ->name('representative.') // ルート名にプレフィックスを追加
    ->group(function () {
        // ダッシュボード
        Route::get('/dashboard', [\App\Http\Controllers\Representative\DashboardController::class, 'index'])
            ->name('dashboard');

        // 店舗管理（ルート名: representative.shops.edit になる）
        Route::resource('shops', \App\Http\Controllers\Representative\ShopController::class)
            ->only(['edit', 'update']);

        // 予約管理
        Route::get('/reservations', [\App\Http\Controllers\Representative\ReservationController::class, 'index'])
            ->name('reservations');

        // 店舗レビュー確認（閲覧のみ）
        Route::get('/shop/{shop_id}/reviews', [\App\Http\Controllers\Representative\ReviewController::class, 'index'])
            ->name('shop.reviews');

        Route::get('/password/edit', [\App\Http\Controllers\Representative\ProfileController::class, 'editPassword'])->name('password.edit');
        Route::post('/password/update', [\App\Http\Controllers\Representative\ProfileController::class, 'updatePassword'])->name('password.update');

        // 予約確認
        Route::get('/reservations', [\App\Http\Controllers\Representative\ReservationController::class, 'index'])
            ->name('reservations.index');
        // 予約詳細
        Route::get('/reservations/{reservation}', [\App\Http\Controllers\Representative\ReservationController::class, 'show'])
            ->name('reservations.show');
    });
