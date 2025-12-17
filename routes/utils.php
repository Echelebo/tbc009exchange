<?php

use App\Http\Controllers\Cron\BaseCronController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\File;

Route::get('bot-cron-1', [BaseCronController::class, 'botCronOne'])
    ->name('bot-cron-one');
