<?php

namespace App\Http\Controllers\Cron;

use App\Http\Controllers\Controller;
use App\Models\CronJob;
use App\Traits\RunBot;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;

class BaseCronController extends Controller
{
    use RunBot;

    public function botCronOne()
{
    // Lock for 5 minutes (enough time to finish)
    if (!Cache::lock('bot-cron-one', 300)->get()) {
        // Already running → skip
        return 'Already running';
    }

    // Your normal logic
    $this->endBot();
    $this->updateTimestamp();

    // Update last_run
    $job = CronJob::where('name', 'bot-cron-one')->first();
    if ($job) {
        $job->last_run = time();
        $job->save();
    }

    // Release lock
    Cache::lock('bot-cron-one')->release();
}

}
