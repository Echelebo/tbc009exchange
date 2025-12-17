<?php

namespace App\Traits;

trait SendNotification
{
    use Notify;

    public function sendAdminNotification($object, $type): void
    {
        $this->{$type}($object);
    }

    public function sendUserNotification($object, $type, $templateKey): void
    {
        $this->{$type}($object, $templateKey);
    }

    public function userExchange($exchangeRequest, $templateKey): void
    {
        if ($exchangeRequest->user_id && $exchangeRequest->user) {
            $params = [
                'user' => optional($exchangeRequest->user)->username ?? 'Anonymous',
                'sendAmount' => rtrim(rtrim($exchangeRequest->send_amount, 0), '.'),
                'getAmount' => rtrim(rtrim($exchangeRequest->get_amount, 0), '.'),
                'sendCurrency' => optional($exchangeRequest->sendCurrency)->code,
                'getCurrency' => optional($exchangeRequest->getCurrency)->code,
                'transaction' => $exchangeRequest->utr,
            ];

            $action = [
                "link" => '#',
                "icon" => "fa fa-money-bill-alt text-white"
            ];
            $this->sendMailSms($exchangeRequest->user, $templateKey, $params);
            $this->userPushNotification($exchangeRequest->user, $templateKey, $params, $action);
            $this->userFirebasePushNotification($exchangeRequest->user, $templateKey, $params);
        }
    }

    public function userExchangeActivation($exchangeActivation, $templateKey): void
    {
        if ($exchangeActivation->user_id && $exchangeActivation->user) {
            $params = [
                'user' => optional($exchangeActivation->user)->username ?? 'Anonymous',
                'dailyReturn' => rtrim(rtrim($exchangeActivation->daily_return, 0), '.'),
                'dailyStake' => rtrim(rtrim($exchangeActivation->stake_daily_release, 0), '.'),
                'exchangeAmount' => rtrim(rtrim($exchangeActivation->send_amount, 0), '.'),
                'transaction' => $exchangeActivation->txn_id,
            ];

            $action = [
                "link" => '#',
                "icon" => "fa fa-money-bill-alt text-white"
            ];
            $this->sendMailSms($exchangeActivation->user, $templateKey, $params);
            $this->userPushNotification($exchangeActivation->user, $templateKey, $params, $action);
            $this->userFirebasePushNotification($exchangeActivation->user, $templateKey, $params);
        }
    }

    public function userBasic($userBasic, $templateKey): void
    {
        if ($userBasic->id) {
            $params = [
                'username' => optional($userBasic->referrer)->username ?? 'Anonymous',
                'dowlineFirstname' => $userBasic->firstname ?? 'Anonymous',
                'downlineLastname' => $userBasic->lastname ?? 'Anonymous',
                'accountLevel' => $userBasic->account_level,
            ];

            $action = [
                "link" => '#',
                "icon" => "fa fa-money-bill-alt text-white"
            ];
            $this->sendMailSms($userBasic->user, $templateKey, $params);
            $this->userPushNotification($userBasic->user, $templateKey, $params, $action);
            $this->userFirebasePushNotification($userBasic->user, $templateKey, $params);
        }
    }

    public function userTopup($topup, $templateKey): void
    {
        if ($topup->user_id && $topup->user) {
            $params = [
                'user' => optional($topup->user)->username ?? 'Anonymous',
                'sendAmount' => rtrim(rtrim($topup->amount, 0), '.'),
                'sendCurrency' => optional($topup->method),
                'transaction' => $topup->utr,
            ];

            $action = [
                "link" => '#',
                "icon" => "fa fa-money-bill-alt text-white"
            ];
            $this->sendMailSms($topup->user, $templateKey, $params);
            $this->userPushNotification($topup->user, $templateKey, $params, $action);
            $this->userFirebasePushNotification($topup->user, $templateKey, $params);
        }
    }

    public function userPayout($payout, $templateKey): void
    {
        if ($payout->user_id && $payout->user) {
            $params = [
                'user' => optional($payout->user)->username ?? 'Anonymous',
                'sendAmount' => rtrim(rtrim($payout->amount, 0), '.'),
                'sendCurrency' => optional($payout->method),
                'transaction' => $payout->utr,
            ];

            $action = [
                "link" => '#',
                "icon" => "fa fa-money-bill-alt text-white"
            ];
            $this->sendMailSms($payout->user, $templateKey, $params);
            $this->userPushNotification($payout->user, $templateKey, $params, $action);
            $this->userFirebasePushNotification($payout->user, $templateKey, $params);
        }
    }

    public function exchange($exchangeRequest): void
    {
        $params = [
            'user' => optional($exchangeRequest->user)->username ?? 'Anonymous',
            'sendAmount' => rtrim(rtrim($exchangeRequest->send_amount, 0), '.'),
            'getAmount' => rtrim(rtrim($exchangeRequest->get_amount, 0), '.'),
            'sendCurrency' => optional($exchangeRequest->sendCurrency)->code,
            'getCurrency' => optional($exchangeRequest->getCurrency)->code,
            'transaction' => $exchangeRequest->utr,
        ];

        $action = [
            "link" => route('admin.exchangeView') . '?id=' . $exchangeRequest->id,
            "icon" => "fa fa-money-bill-alt text-white"
        ];

        $this->adminMail('EXCHANGE_REQUEST', $params);
        $this->adminPushNotification('EXCHANGE_REQUEST', $params, $action);
        $this->adminFirebasePushNotification('EXCHANGE_REQUEST', $params, $action);
    }

    public function staking($exchange): void
    {
        $params = [
            'user' => optional($exchange->user)->username ?? 'Anonymous',
            'sendAmount' => rtrim(rtrim($exchange->send_amount, 0), '.'),
            'getAmount' => rtrim(rtrim($exchange->get_amount, 0), '.'),
            'sendCurrency' => optional($exchange->sendCurrency)->code,
            'getCurrency' => optional($exchange->getCurrency)->code,
            'transaction' => $exchange->utr,
        ];

        $action = [
            "link" => route('admin.exchangeView') . '?id=' . $exchange->id,
            "icon" => "fa fa-money-bill-alt text-white"
        ];

        $this->adminMail('EXCHANGE_STAKING', $params);
        $this->adminPushNotification('EXCHANGE_STAKING', $params, $action);
        $this->adminFirebasePushNotification('EXCHANGE_STAKING', $params, $action);
    }

    public function admintopup($topup): void
    {
        $params = [
            'user' => optional($topup->user)->username ?? 'Anonymous',
            'sendAmount' => rtrim(rtrim($topup->amount, 0), '.'),
            'sendCurrency' => optional($topup->method),
            'transaction' => $topup->utr,
        ];

        $action = [
            "link" => route('admin.topup') . '?id=' . $topup->id,
            "icon" => "fa fa-money-bill-alt text-white"
        ];

        $this->adminMail('TOPUP', $params);
        $this->adminPushNotification('TOPUP', $params, $action);
        $this->adminFirebasePushNotification('TOPUP', $params, $action);
    }

    public function adminpayout($payout): void
    {
        $params = [
            'user' => optional($payout->user)->username ?? 'Anonymous',
            'sendAmount' => rtrim(rtrim($payout->amount, 0), '.'),
            'sendCurrency' => optional($payout->method),
            'transaction' => $payout->utr,
        ];

        $action = [
            "link" => route('admin.payout') . '?id=' . $payout->id,
            "icon" => "fa fa-money-bill-alt text-white"
        ];

        $this->adminMail('PAYOUT', $params);
        $this->adminPushNotification('PAYOUT', $params, $action);
        $this->adminFirebasePushNotification('PAYOUT', $params, $action);
    }
}
