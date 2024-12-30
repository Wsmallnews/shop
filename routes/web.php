<?php

use Illuminate\Support\Facades\Route;
use Wsmallnews\Shop\Pages\Auth\Login;
use Wsmallnews\Shop\Pages\Auth\Register;
use Wsmallnews\Shop\Pages\Index;
use Wsmallnews\Shop\Pages\Order\Confirm;
use Wsmallnews\Shop\Pages\Pay\Cashier;
use Wsmallnews\Shop\Pages\Product\Detail;

Route::prefix('shop')->middleware('web')->group(function () {
    Route::get('index', Index::class)->name('sn-shop.index');

    Route::get('login', Login::class)->name('sn-shop.login');

    Route::get('register', Register::class)->name('sn-shop.register');

    Route::get('product-detail/{id}', Detail::class)->name('sn-shop.product.detail');

    Route::middleware('auth')->group(function () {
        Route::get('order-confirm', Confirm::class)->name('sn-shop.order.confirm');

        Route::get('pay-cashier', Cashier::class)->name('sn-shop.pay.cashier');
    });
});
