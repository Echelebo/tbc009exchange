<?php

namespace App\Traits;

use App\Models\ExchangeActivation;
use App\Models\ExchangeRequest;
use Facades\App\Services\BasicService;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;


trait RunBot
{

use SendNotification;

public function updateTimestamp()
    {
        $cutoff = Carbon::now()->subHours(24)->timestamp;

        // Get only active ones that are exactly 24+ hours old
        ExchangeActivation::where('status', 'active')
            ->where('daily_timestamp', '<=', $cutoff)
            ->chunk(100, function ($activations) {
                foreach ($activations as $act) {
                    DB::transaction(function () use ($act) {
                        $return = $act->daily_return + $act->stake_daily_release;
                        // Credit the user
                        $user = User::find($act->user_id);
                        if ($user) {
                            $user->increment('balance', $return);
                        }

                        $act->locked_stake -= $act->stake_daily_release;
                        $act->released_stake += $act->stake_daily_release;
                        $act->released_return += $act->daily_return;
                        // Update timestamp to now - prevents double credit
                        $act->daily_timestamp = now()->timestamp;
                        $act->save();
                    });

                    //record transaction
                BasicService::makeTransaction(
                    $act->daily_return,
                    0,
                    '+',
                    'Exchange Daily Return',
                    $act->id,
                    ExchangeActivation::class,
                    $act->user_id,
                    $act->daily_return,
                    'USDT'
                );

                //record transaction
                BasicService::makeTransaction(
                    $act->stake_daily_release,
                    0,
                    '+',
                    'Daily Stake Release',
                    $act->id,
                    ExchangeActivation::class,
                    $act->user_id,
                    $act->stake_daily_release,
                    'USDT'
                );

                $this->sendUserNotification($act, 'userExchangeActivation', 'EXCHANGE_DAILY_RETURN');
                $this->sendUserNotification($act, 'userExchangeActivation', 'EXCHANGE_DAILY_STAKE');
                }
            });
    }

    public function endBot()
    {
        $now = now()->timestamp;

        ExchangeActivation::where('status', 'active')
            ->where('expires_in', '<', $now)
            ->chunk(100, function ($activations) {
                foreach ($activations as $act) {
                    DB::transaction(function () use ($act) {
                        $return = $act->daily_return + $act->stake_daily_release;
                        // Credit the user
                        $user = User::find($act->user_id);
                        if ($user) {
                            $user->increment('balance', $return);
                        }

                        $act->locked_stake -= $act->stake_daily_release;
                        $act->released_stake += $act->stake_daily_release;
                        $act->released_return += $act->daily_return;
                        $act->status = 'expired';

                        $act->save();
                    });

                    $exchangeRequest = ExchangeRequest::where('utr', $act->txn_id)->first();
                    $exchangeRequest->status = 9;
                    $exchangeRequest->save();

                    //record transaction
                BasicService::makeTransaction(
                    $act->daily_return,
                    0,
                    '+',
                    'Exchange Daily Return - Expired',
                    $act->id,
                    ExchangeActivation::class,
                    $act->user_id,
                    $act->daily_return,
                    'USDT'
                );

                //record transaction
                BasicService::makeTransaction(
                    $act->stake_daily_release,
                    0,
                    '+',
                    'Daily Stake Release - Expired',
                    $act->id,
                    ExchangeActivation::class,
                    $act->user_id,
                    $act->stake_daily_release,
                    'USDT'
                );

                $this->sendUserNotification($act, 'userExchangeActivation', 'EXCHANGE_DAILY_RETURN');
                $this->sendUserNotification($act, 'userExchangeActivation', 'EXCHANGE_DAILY_STAKE');
                $this->sendUserNotification($act, 'userExchangeActivation', 'EXCHANGE_EXPIRES');
                }
            });
    }
}
