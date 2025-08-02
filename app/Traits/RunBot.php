<?php

//give profit for running bot

use App\Models\ExchangeRequest;
use App\Traits\SendNotification;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Facades\App\Services\BasicService;

//update daily timestamp
function updateTimestamp()
{

    // Generate timestamp for the new day
    $togrow = Carbon::now()->subHours(24)->timestamp;

    // Chunk the records
    ExchangeRequest::where('daily_timestamp', '<=', $togrow)
        ->where('status', 8)
        ->chunk(100, function ($bot_activations) {
            //update these records
            foreach ($bot_activations as $act) {

                // credit the user the amount that was realized for that day

                $user = User::find($act->user_id);
                $user->return = $user->return + $act->profit;
                $user->available_stake = $user->available_stake + ($act->send_amount * 10) / 4;
                $user->stake = $user->stake - ($act->send_amount * 10) / 4;
                $user->save();

                BasicService::makeTransaction(
                    $act->profit,
                    0,
                    '-',
                    'Exchange Return',
                    $act->id,
                    ExchangeRequest::class,
                    $act->user_id,
                    $act->send_amount,
                    optional($act->sendCurrency)->code
                );


                //update timestamp
                $update = ExchangeRequest::find($act->id);
                $update->daily_timestamp = time();
                $update->save();
            }
        });

    return true;
}

//change the status of all completed bots
function endBot()
{
    ExchangeRequest::where('status', 8)
        ->where('expires_in', '<', time())
        ->chunk(100, function ($bot_activations) {
            foreach ($bot_activations as $act) {
                $update = ExchangeRequest::find($act->id);
                $update->status = 9;
                $update->save();

                //credit the user
                $user = $act->user_id;
                $credit = User::find($user);
                $credit->return = $user->return  + $act->profit;
                $credit->available_stake = $user->available_stake + ($act->send_amount * 10) / 4;
                $credit->save();

                //record transaction
                BasicService::makeTransaction(
                    $act->profit,
                    0,
                    '-',
                    'Exchange Expired',
                    $act->id,
                    ExchangeRequest::class,
                    $act->user_id,
                    $act->send_amount,
                    optional($act->sendCurrency)->code
                );
            }
        });

    return true;
}
