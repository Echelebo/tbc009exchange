<?php

namespace App\Models;

use App\Traits\Status;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Prunable;
use Illuminate\Database\Eloquent\SoftDeletes;


class ExchangeRate extends Model
{
    use HasFactory, SoftDeletes, Status, Prunable;

    protected $fillable = ['name', 'min', 'max', 'total_return'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id')->withTrashed();
    }

}
