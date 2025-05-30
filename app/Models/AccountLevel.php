<?php

namespace App\Models;

use App\Traits\Status;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Prunable;
use Illuminate\Database\Eloquent\SoftDeletes;


class AccountLevel extends Model
{
    use HasFactory, SoftDeletes, Status, Prunable;

    protected $fillable = ['level_name', 'min_exchange', 'max_exchange', 'max_count_exchange', 'active_plan_id', 'max_running_plan'];

    protected $appends = ['tracking_status', 'admin_status', 'user_status'];

    

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id')->withTrashed();
    }

}
