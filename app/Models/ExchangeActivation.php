<?php

namespace App\Models;

use App\Traits\Status;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Prunable;
use Illuminate\Database\Eloquent\SoftDeletes;


class ExchangeActivation extends Model
{
    use HasFactory, SoftDeletes, Status, Prunable;

    protected $fillable = ['user_id', 'account_level', 'send_amount', 'locked_stake', 'released_stake',
        'expires_in', 'stake_daily_release', 'daily_return', 'daily_timestamp', 'released_return', 'status', 'created_at', 'updated_at', 'deleted_at', 'total_return', 'txn_id'];

    protected $appends = ['tracking_status', 'admin_status', 'user_status'];

    public function sendCurrency()
    {
        return $this->belongsTo(CryptoCurrency::class, 'send_currency_id')->withTrashed();
    }

    public function getCurrency()
    {
        return $this->belongsTo(CryptoCurrency::class, 'get_currency_id')->withTrashed();
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id')->withTrashed();
    }

    public function cryptoMethod()
    {
        return $this->belongsTo(CryptoMethod::class, 'crypto_method_id');
    }

    public function prunable(): Builder
    {
        return static::where('created_at', '<=', now()->subDays(2))->where('status', 0);
    }

}
