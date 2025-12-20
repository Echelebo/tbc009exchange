<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\User\ExchangeController;
use App\Http\Controllers\User\BuyController;
use App\Http\Controllers\User\SellController;
use App\Http\Controllers\User\TradeHistrotyController;
use App\Http\Controllers\FrontendController;
use App\Http\Controllers\User\HomeController;

$basicControl = basicControl();

Route::group(['middleware' => ['maintenanceMode']], function () use ($basicControl) {

    Route::get('get-exchange/currency', [ExchangeController::class, 'getExchangeCurrency'])->name('getExchangeCurrency');
    Route::get('get-buy/currency', [BuyController::class, 'getBuyCurrency'])->name('getBuyCurrency');
    Route::get('get-sell/currency', [SellController::class, 'getSellCurrency'])->name('getSellCurrency');
    Route::get('track/trade', [FrontendController::class, 'tracking'])->name('tracking');
    Route::post('track/trade', [FrontendController::class, 'trackingx'])->name('trackingx');

    Route::post('exchange/auto-rate', [ExchangeController::class, 'exchangeAutoRate'])->name('exchangeAutoRate');
    Route::get('exchange/get-status/{utr}', [ExchangeController::class, 'exchangeGetStatus'])->name('exchangeGetStatus');
    Route::post('exchange/request', [ExchangeController::class, 'exchangeRequest'])->name('exchangeRequest');
    Route::any('exchange/processing/{utr}', [ExchangeController::class, 'exchangeProcessing'])->name('exchangeProcessing');
    Route::get('exchange/processing-overview/{utr}', [ExchangeController::class, 'exchangeProcessingOverview'])->name('exchangeProcessingOverview');
    Route::any('exchange/initiate-payment/{utr}', [ExchangeController::class, 'exchangeInitPayment'])->name('exchangeInitPayment');
    Route::get('exchange/final/{utr}', [ExchangeController::class, 'exchangeFinal'])
    ->name('exchangeFinal');

    Route::controller(BuyController::class)->group(function () {
        Route::get('get-buy/currency', 'getBuyCurrency')->name('getBuyCurrency');
        Route::post('buy/auto-rate', 'buyAutoRate')->name('buyAutoRate');
        Route::get('buy/get-status/{utr}', 'buyGetStatus')->name('buyGetStatus');

        Route::post('buy/request', 'buyRequest')->name('buyRequest');
        Route::any('buy/processing/{utr}', 'buyProcessing')->name('buyProcessing');
        Route::get('buy/processing-overview/{utr}', 'buyProcessingOverview')->name('buyProcessingOverview');
        Route::any('buy/initiate-payment/{utr}', 'buyInitPayment')->name('buyInitPayment');
        Route::get('buy/final/{utr}', 'buyFinal')->name('buyFinal');
    });

    Route::controller(SellController::class)->group(function () {
        Route::get('get-sell/currency', 'getSellCurrency')->name('getSellCurrency');
        Route::post('sell/auto-rate', 'sellAutoRate')->name('sellAutoRate');
        Route::get('sell/get-status/{utr}', 'sellGetStatus')->name('sellGetStatus');
        Route::get('sell-currency/method-info', 'getSellCurrencyMethodInfo')->name('getSellCurrencyMethodInfo');

        Route::post('sell/request', 'sellRequest')->name('sellRequest');
        Route::any('sell/processing/{utr}', 'sellProcessing')->name('sellProcessing');
        Route::get('sell/processing-overview/{utr}', 'sellProcessingOverview')->name('sellProcessingOverview');
        Route::any('sell/initiate-payment/{utr}', 'sellInitPayment')->name('sellInitPayment');
        Route::get('sell/final/{utr}', 'sellFinal')->name('sellFinal');
    });

    Route::group(['middleware' => ['auth', 'verifyUser'], 'prefix' => 'user', 'as' => 'user.'], function () {

    // TradeHistrotyController Routes
    Route::get('exchange-request/list', [TradeHistrotyController::class, 'exchangeList'])
        ->name('exchangeList');

    Route::get('exchange-request/details/{utr}', [TradeHistrotyController::class, 'exchangeDetails'])
        ->name('exchangeDetails');

    Route::get('exchange-request/rate-floating/{utr}', [TradeHistrotyController::class, 'exchangeRateFloating'])
        ->name('exchangeRateFloating');

    Route::get('buy-request/list', [TradeHistrotyController::class, 'buyList'])
        ->name('buyList');

    Route::get('buy-request/details/{utr}', [TradeHistrotyController::class, 'buyDetails'])
        ->name('buyDetails');

    Route::get('sell-request/list', [TradeHistrotyController::class, 'sellList'])
        ->name('sellList');

    Route::get('sell-request/details/{utr}', [TradeHistrotyController::class, 'sellDetails'])
        ->name('sellDetails');

    // HomeController Chart & Records Routes
    Route::get('getRecords', [HomeController::class, 'getRecords'])
        ->name('getRecords');

    Route::get('chartExchangeFigures', [HomeController::class, 'chartExchangeFigures'])
        ->name('chartExchangeFigures');

    Route::get('chartTopUpFigures', [HomeController::class, 'chartTopUpFigures'])
        ->name('chartTopUpFigures');

    Route::get('chartPayoutFigures', [HomeController::class, 'chartPayoutFigures'])
        ->name('chartPayoutFigures');

    Route::get('chartExchangeMovements', [HomeController::class, 'chartExchangeMovements'])
        ->name('chartExchangeRecords'); // matches your original name

    Route::get('chartTopUpMovements', [HomeController::class, 'chartTopUpMovements'])
        ->name('chartTopUpRecords'); // matches your original name

    Route::get('chartPayoutMovements', [HomeController::class, 'chartPayoutMovements'])
        ->name('chartPayoutRecords'); // matches your original name
});
});
