<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});


Route::group(['middleware' => ['web']], function () {
    Route::get('/bkash/pay', [BkashController::class, 'pay'])->name('bkash.pay');
    Route::get('/bkash/callback', [BkashController::class, 'callback'])->name('bkash.callback');
    Route::post('/bkash/create-payment', [BkashController::class, 'createPayment'])->name('bkash.create-payment');
    Route::post('/bkash/execute-payment', [BkashController::class, 'executePayment'])->name('bkash.execute-payment');
});