<?php

use Illuminate\Support\Facades\Route;

// Dashboard - Protected by auth middleware
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard.pages.dashboard.index');
    })->name('dashboard');

    // components
    Route::get('/buttons', function () {
        return view('dashboard.pages.components.buttons');
    })->name('buttons.page');
    Route::get('/cards', function () {
        return view('dashboard.pages.components.cards');
    })->name('cards.page');

    // utilities
    Route::get('/colors', function () {
        return view('dashboard.pages.utilities.colors');
    })->name('colors.page');
    Route::get('/borders', function () {
        return view('dashboard.pages.utilities.borders');
    })->name('borders.page');
    Route::get('/animations', function () {
        return view('dashboard.pages.utilities.animations');
    })->name('animations.page');
    Route::get('/other-utilities', function () {
        return view('dashboard.pages.utilities.other');
    })->name('other-utilities.page');

    // authentication
    // Route::get('/login', function () {
    //     return view('dashboard.pages.auth.login');
    // })->name('login.page');
    // Route::get('/register', function () {
    //     return view('dashboard.pages.auth.register');
    // })->name('register.page');
    // Route::get('/forgot-password', function () {
    //     return view('dashboard.pages.auth.forgot-password');
    // })->name('forgot-password.page');

    // Other Pages
    Route::get('/404', function () {
        return view('dashboard.pages.404');
    })->name('404.page');
    Route::get('/blank', function () {
        return view('dashboard.pages.blank');
    })->name('blank.page');
    Route::get('/charts', function () {
        return view('dashboard.pages.charts');
    })->name('charts.page');
    Route::get('/tables', function () {
        return view('dashboard.pages.tables');
    })->name('tables.page');
});
