<?php

use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Http\Controllers\AuthenticatedSessionController;
use Laravel\Fortify\Http\Controllers\RegisteredUserController;
use App\Http\Controllers\ShopController;



Route::get('/', [ShopController::class, 'showShopList'])->name('home');
Route::get('/detail/{shop_id}', [ShopController::class, 'showShopDetail'])->name('shop.detail');