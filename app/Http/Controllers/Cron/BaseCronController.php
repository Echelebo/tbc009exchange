<?php

namespace App\Http\Controllers\Cron;

use App\Http\Controllers\Controller;
use App\Models\CronJob;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;

class BaseCronController extends Controller
{
    public function botCronOne()
    {
        //end running bots
        endBot();

        //update trade timestamp

        updateTimestamp();

        // update the last run time
        $job = CronJob::where('name', 'bot-cron-one')->first();
        $update = CronJob::find($job->id);
        $update->last_run = time();
        $update->save();

        // check if the backup and withdrawal cronjobs are triggered
        $is_triggered = CronJob::where('name', 'schedule-run')->first();
        if ($is_triggered) {
            if ($is_triggered->last_run < now()->addHours(-1)->timestamp) {
                Artisan::call('schedule:run');
            }
        }

        return true;
    }
}
