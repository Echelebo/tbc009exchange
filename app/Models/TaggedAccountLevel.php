<?php

namespace App\Models;

use App\Traits\Status;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Prunable;
use Illuminate\Database\Eloquent\SoftDeletes;


class TaggedAccountLevel extends Model
{
    use HasFactory, SoftDeletes, Status, Prunable;

    protected $fillable = ['level_name', 'target_count_exchange', 'bonus_amount'];

    

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id')->withTrashed();
    }

    

}
