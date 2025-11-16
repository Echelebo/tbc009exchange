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

    public function userReferrer($referrer, $templateKey): void
    {
        if ($referrer->user_id && $referrer->user) {
            $params = [
                'user' => optional($referrer->user)->username ?? 'Anonymous',
                'sendAmount' => rtrim(rtrim($referrer->send_amount, 0), '.'),
                'getAmount' => rtrim(rtrim($referrer->get_amount, 0), '.'),
                'sendCurrency' => optional($referrer->sendCurrency)->code,
                'getCurrency' => optional($referrer->getCurrency)->code,
                'transaction' => $referrer->utr,
            ];

            $action = [
                "link" => '#',
                "icon" => "fa fa-money-bill-alt text-white"
            ];
            $this->sendMailSms($referrer->user, $templateKey, $params);
            $this->userPushNotification($referrer->user, $templateKey, $params, $action);
            $this->userFirebasePushNotification($referrer->user, $templateKey, $params);
        }
    }

    public function userRegisterReferrer($referrer, $templateKey): void
    {
        if ($referrer->user_id && $referrer->user) {
            $params = [
                'user' => optional($referrer->user)->username ?? 'Anonymous',
                'sendAmount' => rtrim(rtrim($referrer->send_amount, 0), '.'),
                'getAmount' => rtrim(rtrim($referrer->get_amount, 0), '.'),
                'sendCurrency' => optional($referrer->sendCurrency)->code,
                'getCurrency' => optional($referrer->getCurrency)->code,
                'transaction' => $referrer->utr,
            ];

            $action = [
                "link" => '#',
                "icon" => "fa fa-money-bill-alt text-white"
            ];
            $this->sendMailSms($referrer->user, $templateKey, $params);
            $this->userPushNotification($referrer->user, $templateKey, $params, $action);
            $this->userFirebasePushNotification($referrer->user, $templateKey, $params);
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

    public function userStaking($staking, $templateKey): void
    {
        if ($staking->user_id && $staking->user) {
            $params = [
                'user' => optional($staking->user)->username ?? 'Anonymous',
                'sendAmount' => rtrim(rtrim($staking->send_amount, 0), '.'),
                'getAmount' => rtrim(rtrim($staking->get_amount, 0), '.'),
                'sendCurrency' => optional($staking->sendCurrency)->code,
                'getCurrency' => optional($staking->getCurrency)->code,
                'transaction' => $staking->utr,
            ];

            $action = [
                "link" => '#',
                "icon" => "fa fa-money-bill-alt text-white"
            ];
            $this->sendMailSms($staking->user, $templateKey, $params);
            $this->userPushNotification($staking->user, $templateKey, $params, $action);
            $this->userFirebasePushNotification($staking->user, $templateKey, $params);
        }
    }

    public function userBuy($buyRequest, $templateKey): void
    {
        if ($buyRequest->user_id && $buyRequest->user) {
            $params = [
                'user' => optional($buyRequest->user)->username ?? 'Anonymous',
                'sendAmount' => number_format($buyRequest->send_amount, 2),
                'getAmount' => rtrim(rtrim($buyRequest->get_amount, 0), '.'),
                'sendCurrency' => optional($buyRequest->sendCurrency)->code,
                'getCurrency' => optional($buyRequest->getCurrency)->code,
                'transaction' => $buyRequest->utr,
            ];

            $action = [
                "link" => '#',
                "icon" => "fa fa-money-bill-alt text-white"
            ];
            $this->sendMailSms($buyRequest->user, $templateKey, $params);
            $this->userPushNotification($buyRequest->user, $templateKey, $params, $action);
            $this->userFirebasePushNotification($buyRequest->user, $templateKey, $params);
        }
    }

    public function userSell($sellRequest, $templateKey): void
    {
        if ($sellRequest->user_id && $sellRequest->user) {
            $params = [
                'user' => optional($sellRequest->user)->username ?? 'Anonymous',
                'sendAmount' => rtrim(rtrim($sellRequest->send_amount, 0), '.'),
                'getAmount' => number_format($sellRequest->get_amount, 2),
                'sendCurrency' => optional($sellRequest->sendCurrency)->code,
                'getCurrency' => optional($sellRequest->getCurrency)->code,
                'transaction' => $sellRequest->utr,
            ];

            $action = [
                "link" => '#',
                "icon" => "fa fa-money-bill-alt text-white"
            ];
            $this->sendMailSms($sellRequest->user, $templateKey, $params);
            $this->userPushNotification($sellRequest->user, $templateKey, $params, $action);
            $this->userFirebasePushNotification($sellRequest->user, $templateKey, $params);
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

    public function topup($topup): void
    {
        $params = [
            'user' => optional($topup->user)->username ?? 'Anonymous',
            'sendAmount' => rtrim(rtrim($topup->amount, 0), '.'),
            'sendCurrency' => optional($topup->method),
            'transaction' => $topup->utr,
        ];

        $action = [
            "link" => route('admin.topupView') . '?id=' . $topup->id,
            "icon" => "fa fa-money-bill-alt text-white"
        ];

        $this->adminMail('TOPUP', $params);
        $this->adminPushNotification('TOPUP', $params, $action);
        $this->adminFirebasePushNotification('TOPUP', $params, $action);
    }

    public function payout($payout): void
    {
        $params = [
            'user' => optional($payout->user)->username ?? 'Anonymous',
            'sendAmount' => rtrim(rtrim($payout->amount, 0), '.'),
            'sendCurrency' => optional($payout->method),
            'transaction' => $payout->utr,
        ];

        $action = [
            "link" => route('admin.payoutView') . '?id=' . $payout->id,
            "icon" => "fa fa-money-bill-alt text-white"
        ];

        $this->adminMail('PAYOUT', $params);
        $this->adminPushNotification('PAYOUT', $params, $action);
        $this->adminFirebasePushNotification('PAYOUT', $params, $action);
    }

    public function buy($buyRequest): void
    {
        $params = [
            'user' => optional($buyRequest->user)->username ?? 'Anonymous',
            'sendAmount' => number_format($buyRequest->send_amount, 2),
            'getAmount' => rtrim(rtrim($buyRequest->get_amount, 0), '.'),
            'sendCurrency' => optional($buyRequest->sendCurrency)->code,
            'getCurrency' => optional($buyRequest->getCurrency)->code,
            'transaction' => $buyRequest->utr,
        ];

        $action = [
            "link" => route('admin.buyView') . '?id=' . $buyRequest->id,
            "icon" => "fa fa-money-bill-alt text-white"
        ];

        $this->adminMail('BUY_REQUEST', $params);
        $this->adminPushNotification('BUY_REQUEST', $params, $action);
        $this->adminFirebasePushNotification('BUY_REQUEST', $params, $action);
    }

    public function sell($sellRequest): void
    {
        $params = [
            'user' => optional($sellRequest->user)->username ?? 'Anonymous',
            'sendAmount' => rtrim(rtrim($sellRequest->send_amount, 0), '.'),
            'getAmount' => number_format($sellRequest->get_amount, 2),
            'sendCurrency' => optional($sellRequest->sendCurrency)->code,
            'getCurrency' => optional($sellRequest->getCurrency)->code,
            'transaction' => $sellRequest->utr,
        ];

        $action = [
            "link" => route('admin.sellView') . '?id=' . $sellRequest->id,
            "icon" => "fa fa-money-bill-alt text-white"
        ];

        $this->adminMail('SELL_REQUEST', $params);
        $this->adminPushNotification('SELL_REQUEST', $params, $action);
        $this->adminFirebasePushNotification('SELL_REQUEST', $params, $action);
    }

}
