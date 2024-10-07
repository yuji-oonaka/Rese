<?php

use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Http\Controllers\AuthenticatedSessionController;
use Laravel\Fortify\Http\Controllers\RegisteredUserController;


Route::get('/login', function () {
        return view('auth.login');
    })->name('login');

Route::get('/register', function () {
        return view('auth.register');
    })->name('register');