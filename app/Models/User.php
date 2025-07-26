<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Traits\Notify;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Cache;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes, Notify;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $guarded = ['id'];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $appends = ['last-seen-activity', 'fullname'];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'sent_at' => 'date',
        'email_key' => 'array',
        'sms_key' => 'array',
        'push_key' => 'array',
        'in_app_key' => 'array',
        'webhook_url' => 'object'
    ];

    protected $dates = ['deleted_at'];

    protected static function boot()
    {
        parent::boot();
        static::saved(function () {
            Cache::forget('userRecord');
        });
    }


    public function funds()
    {
        return $this->hasMany(Fund::class)->latest()->where('status', '!=', 0);
    }


    public function transaction()
    {
        return $this->hasOne(Transaction::class)->latest();
    }


    public function getLastSeenActivityAttribute()
    {
        if (Cache::has('user-is-online-' . $this->id) == true) {
            return true;
        } else {
            return false;
        }
    }

    public function inAppNotification()
    {
        return $this->morphOne(InAppNotification::class, 'inAppNotificationable', 'in_app_notificationable_type', 'in_app_notificationable_id');
    }

    public function fireBaseToken()
    {
        return $this->morphMany(FireBaseToken::class, 'tokenable');
    }

    public function profilePicture()
    {
        $image = $this->image;
        if (!$image) {
            $active = $this->LastSeenActivity == false ? 'warning' : 'success';
            $firstLetter = substr($this->firstname, 0, 1);
            return '<div class="avatar avatar-sm avatar-soft-primary avatar-circle">
                        <span class="avatar-initials">' . $firstLetter . '</span>
                        <span class="avatar-status avatar-sm-status avatar-status-' . $active . '"></span>
                     </div>';

        } else {
            $url = getFile($this->image_driver, $this->image);
            $active = $this->LastSeenActivity == false ? 'warning' : 'success';
            return '<div class="avatar avatar-sm avatar-circle">
                        <img class="avatar-img" src="' . $url . '" alt="Image Description">
                        <span class="avatar-status avatar-sm-status avatar-status-' . $active . '"></span>
                     </div>';

        }
    }

    public function getFullNameAttribute()
    {
        return $this->firstname . ' ' . $this->lastname;
    }

    public function getPlainPhoneCode()
    {
        return str_replace('+', '', $this->phone_code);
    }

    public function sendPasswordResetNotification($token)
    {
        $this->mail($this, 'PASSWORD_RESET', $params = [
            'message' => '<a href="' . url('password/reset', $token) . '?email=' . $this->email . '" target="_blank">Click To Reset Password</a>'
        ]);
    }

    //referral relationship using the 'referral_by' column
    public function referrer()
    {
        return $this->belongsTo(User::class, 'referral_by', 'id');
    }

    public function referredUsers()
    {
        return $this->hasMany(User::class, 'referral_by', 'id');
    }

    public function getReferralTree($level = 1, $maxLevel = 10)
    {
        $tree = [];

        if ($level > $maxLevel) {
            return $tree;
        }

        $referrals = $this->referredUsers;

        foreach ($referrals as $referral) {
            $referralData = [
                'user' => $referral,
                'level' => $level,
                'children' => $referral->getReferralTree($level + 1, $maxLevel),
            ];

            $tree[] = $referralData;
        }

        return $tree;
    }

    // give referral bonus
    public function giveReferralBonus($depositAmount, $depth = 1)
    {
        if ($depth > 10 || !$this->referrer) {
            return;
        }

        // Calculate and award the bonus to the current upline member
        $percentage_bonus = 5;
        $percentage_bonus = $percentage_bonus[$depth - 1];

        if ($percentage_bonus > 0) {
            $amount = $percentage_bonus / 100 * $depositAmount;
            $this->referrer->balance += $amount;
            $this->referrer->save();

            BasicService::makeTransaction(
                $amount,
                '0',
                'Credit',
                'Referral Bonus',
                uniqid('R'),
                'Stake Trans',
                $this->referrer->id,
                $depositAmount,
                'USD'
            );

            //Notifications goes in here...

            // Recursively call the function for the next upline member
            $this->referrer->giveReferralBonus($depositAmount, $depth + 1);
        }
    }

}
