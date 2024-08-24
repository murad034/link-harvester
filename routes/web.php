<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Backend\UrlController;

Route::get('/home', function () {
    return view('welcome');
});

Route::get('/', function () {
    return redirect()->route('url.create');
});

Route::group(['prefix' => 'url'], function () {
    Route::get('/create', [UrlController::class, 'create'])->name('url.create');
    Route::post('/store', [UrlController::class, 'store'])->name('url.store');
    Route::get('/list', [UrlController::class, 'list'])->name('url.list');
});