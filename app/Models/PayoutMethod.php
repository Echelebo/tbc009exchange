<?php

namespace App\Models;

use App\Traits\Status;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Prunable;
use Illuminate\Database\Eloquent\SoftDeletes;


class PayoutMethod extends Model
{
    use HasFactory, SoftDeletes, Status, Prunable;

    protected $fillable = ['user_id', 'crypto', 'address'];

    protected $appends = ['tracking_status', 'admin_status', 'user_status'];

    

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id')->withTrashed();
    }

}
