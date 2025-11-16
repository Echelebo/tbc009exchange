public function payout($payout): void
    {
        $params = [
            'user' => optional($payout->user)->username ?? 'Anonymous',
            'sendAmount' => rtrim(rtrim($payout->send_amount, 0), '.'),
            'getAmount' => rtrim(rtrim($payout->get_amount, 0), '.'),
            'sendCurrency' => optional($payout->sendCurrency)->code,
            'getCurrency' => optional($payout->getCurrency)->code,
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