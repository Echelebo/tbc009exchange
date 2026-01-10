<?

public function getRecords()
    {
        $thirtyDaysAgo = Carbon::now()->subDays(30);

        $exchangeActivationQuery = $this->exchangeActivationQuery()->where('user_id', auth()->id());

        $activationRecord = collect((clone $exchangeActivationQuery)
            ->whereIn('status', ['0', '1', '2'])
            ->selectRaw('COUNT(id) AS totalStaked')
            ->selectRaw('SUM(send_amount) AS totalExchanged')
            ->selectRaw('SUM(total_stake) AS totalStakedAmount')
            ->get()
            ->makeHidden(['tracking_status', 'admin_status', 'user_status'])
            ->toArray())->collapse();

        $exchangeRequestQuery = $this->exchangeRequestQuery()->where('user_id', auth()->id());

        $exchangeRecord = collect((clone $exchangeRequestQuery)
            ->whereIn('status', ['2', '4', '5', '6', '7', '8', '9'])
            ->selectRaw('COUNT(id) AS totalExchange')
            ->selectRaw('(COUNT(CASE WHEN status IN (2, 4, 7) THEN id END)) AS pendingExchange')
            ->selectRaw('(COUNT(CASE WHEN status IN (2, 4, 7) AND created_at >= ? THEN id END) / COUNT(CASE WHEN created_at >= ? THEN id END)) * 100 AS last30DaysPendingPercentage', [$thirtyDaysAgo, $thirtyDaysAgo])
            ->selectRaw('(COUNT(CASE WHEN status = 6 THEN id END)) AS refundExchange')
            ->selectRaw('(COUNT(CASE WHEN status = 6 AND created_at >= ? THEN id END) / COUNT(CASE WHEN created_at >= ? THEN id END)) * 100 AS last30DaysRefundPercentage', [$thirtyDaysAgo, $thirtyDaysAgo])
            ->selectRaw('(COUNT(CASE WHEN status = 9 THEN id END)) AS completeExchange')
            ->selectRaw('(COUNT(CASE WHEN status = 9 AND created_at >= ? THEN id END) / COUNT(CASE WHEN created_at >= ? THEN id END)) * 100 AS last30DaysCompletePercentage', [$thirtyDaysAgo, $thirtyDaysAgo])
            ->selectRaw('(COUNT(CASE WHEN status = 8 THEN id END)) AS activeExchange')
            ->selectRaw('(COUNT(CASE WHEN status = 8 AND created_at >= ? THEN id END) / COUNT(CASE WHEN created_at >= ? THEN id END)) * 100 AS last30DaysActivePercentage', [$thirtyDaysAgo, $thirtyDaysAgo])
            ->get()
            ->makeHidden(['tracking_status', 'admin_status', 'user_status'])
            ->toArray())->collapse();

        $topupRequestQuery = $this->topupRequestQuery();

        $topupRecord = collect((clone $topupRequestQuery)
            ->where('user_id', auth()->id())
            ->whereIn('status', ['0', '1', '2'])
            ->selectRaw('COUNT(id) AS totalTopUp')
            ->selectRaw('SUM(amount) AS totalCompletedTopUp')
            ->selectRaw('(COUNT(CASE WHEN status = 0 THEN id END)) AS pendingTopUp')
            ->selectRaw('(COUNT(CASE WHEN status = 0 AND created_at >= ? THEN id END) / COUNT(CASE WHEN created_at >= ? THEN id END)) * 100 AS last30DaysPendingPercentage', [$thirtyDaysAgo, $thirtyDaysAgo])
            ->selectRaw('(COUNT(CASE WHEN status = 1 THEN id END)) AS completeTopUp')
            ->selectRaw('(COUNT(CASE WHEN status = 1 AND created_at >= ? THEN id END) / COUNT(CASE WHEN created_at >= ? THEN id END)) * 100 AS last30DaysCompletePercentage', [$thirtyDaysAgo, $thirtyDaysAgo])
            ->selectRaw('(COUNT(CASE WHEN status = 2 THEN id END)) AS cancelTopUp')
            ->selectRaw('(COUNT(CASE WHEN status = 2 AND created_at >= ? THEN id END) / COUNT(CASE WHEN created_at >= ? THEN id END)) * 100 AS last30DaysCancelPercentage', [$thirtyDaysAgo, $thirtyDaysAgo])
            ->get()
            ->makeHidden(['tracking_status', 'admin_status', 'user_status'])
            ->toArray())->collapse();

        $payoutRequestQuery = $this->payoutRequestQuery();

        $payoutRecord = collect((clone $payoutRequestQuery)
            ->where('user_id', auth()->id())
            ->whereIn('status', ['0', '1', '2'])
            ->selectRaw('COUNT(id) AS totalPayout')
            ->selectRaw('SUM(amount) AS totalCompletedPayout')
            ->selectRaw('(COUNT(CASE WHEN status = 0 THEN id END)) AS pendingPayout')
            ->selectRaw('(COUNT(CASE WHEN status = 0 AND created_at >= ? THEN id END) / COUNT(CASE WHEN created_at >= ? THEN id END)) * 100 AS last30DaysPendingPercentage', [$thirtyDaysAgo, $thirtyDaysAgo])
            ->selectRaw('(COUNT(CASE WHEN status = 1 THEN id END)) AS completePayout')
            ->selectRaw('(COUNT(CASE WHEN status = 1 AND created_at >= ? THEN id END) / COUNT(CASE WHEN created_at >= ? THEN id END)) * 100 AS last30DaysCompletePercentage', [$thirtyDaysAgo, $thirtyDaysAgo])
            ->selectRaw('(COUNT(CASE WHEN status = 2 THEN id END)) AS cancelPayout')
            ->selectRaw('(COUNT(CASE WHEN status = 2 AND created_at >= ? THEN id END) / COUNT(CASE WHEN created_at >= ? THEN id END)) * 100 AS last30DaysCancelPercentage', [$thirtyDaysAgo, $thirtyDaysAgo])
            ->get()
            ->makeHidden(['tracking_status', 'admin_status', 'user_status'])
            ->toArray())->collapse();

        $balanceRequestQuery = $this->balanceRequestQuery();

        $balanceRecord = collect((clone $balanceRequestQuery)
            ->where('id', auth()->id())
            ->selectRaw('balance AS userBalance')
            ->get()
            ->toArray())->collapse();

        return response()->json([
            'userBalance' => fractionNumber($balanceRecord['userBalance'], false),

            'totalExchanged' => fractionNumber($activationRecord['totalExchanged'], false),
            'totalStaked' => fractionNumber($activationRecord['totalStaked'], false),
            'totalStakedAmount' => fractionNumber($activationRecord['totalStakedAmount'], false),

            'totalExchange' => fractionNumber($exchangeRecord['totalExchange'], false),
            'pendingExchange' => fractionNumber($exchangeRecord['pendingExchange'], false),
            'last30DaysPendingPercentage' => fractionNumber($exchangeRecord['last30DaysPendingPercentage']),
            'completeExchange' => fractionNumber($exchangeRecord['completeExchange'], false),
            'last30DaysCompletePercentage' => fractionNumber($exchangeRecord['last30DaysCompletePercentage']),
            'activeExchange' => fractionNumber($exchangeRecord['activeExchange'], false),
            'last30DaysActivePercentage' => fractionNumber($exchangeRecord['last30DaysActivePercentage']),
            'refundExchange' => fractionNumber($exchangeRecord['refundExchange'], false),
            'last30DaysRefundPercentage' => fractionNumber($exchangeRecord['last30DaysRefundPercentage']),

            'totalTopUp' => fractionNumber($topupRecord['totalTopUp'], false),
            'pendingTopUp' => fractionNumber($topupRecord['pendingTopUp'], false),
            'last30DaysPendingPercentageTopUp' => fractionNumber($topupRecord['last30DaysPendingPercentage']),
            'completeTopUp' => fractionNumber($topupRecord['completeTopUp'], false),
            'last30DaysCompletePercentageTopUp' => fractionNumber($topupRecord['last30DaysCompletePercentage']),
            'cancelTopUp' => fractionNumber($topupRecord['cancelTopUp'], false),
            'last30DaysCancelPercentageTopUp' => fractionNumber($topupRecord['last30DaysCancelPercentage']),
            'totalCompletedTopUp' => fractionNumber($topupRecord['totalCompletedTopUp'], false),

            'totalPayout' => fractionNumber($payoutRecord['totalPayout'], false),
            'pendingPayout' => fractionNumber($payoutRecord['pendingPayout'], false),
            'last30DaysPendingPercentagePayout' => fractionNumber($payoutRecord['last30DaysPendingPercentage']),
            'completePayout' => fractionNumber($payoutRecord['completePayout'], false),
            'last30DaysCompletePercentagePayout' => fractionNumber($payoutRecord['last30DaysCompletePercentage']),
            'cancelPayout' => fractionNumber($payoutRecord['cancelPayout'], false),
            'last30DaysCancelPercentagePayout' => fractionNumber($payoutRecord['last30DaysCancelPercentage']),
            'totalCompletedPayout' => fractionNumber($payoutRecord['totalCompletedPayout'], false),
        ]);
    }
