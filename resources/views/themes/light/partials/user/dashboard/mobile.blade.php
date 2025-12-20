<div class="tab-mobile-view-carousel-section mb-30 d-lg-none">
    <div class="row">
        <div class="col-12">
            <h5 class="mb-10"> @lang('Exchange Statistics')</h5>

            <div class="owl-carousel owl-theme carousel-1">
                <div class="item">
                    <div class="box-card grayish-custom-card exchangeRecord">
                        <div class="box-card-header">
                            <h5 class="box-card-title"><i
                                    class="fa-light fas fa-spinner"></i>@lang('Pending Exchange')
                            </h5>
                        </div>
                        <div class="box-card-body">
                            <h4 class="mb-0"><span class="pendingExchange"></span>
                                <sub><small>@lang('from') <span
                                            class="totalExchange"></span></small></sub>
                            </h4>
                            <div class="statistics">
                                <p class="growth"><i
                                        class="fa-light fa-chart-line-up"></i><span
                                        class="last30DaysPendingPercentage"></span>
                                    %</p>
                                <div class="time">@lang('last 30 days')</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="item">
                    <div class="box-card grayish-blue-card exchangeRecord">
                        <div class="box-card-header">
                            <h5 class="box-card-title"><i
                                    class="fa-light fas fa-check"></i>@lang('Active Exchange')
                            </h5>
                        </div>
                        <div class="box-card-body">
                            <h4 class="mb-0"><span class="activeExchange"></span>
                                <sub><small>@lang('from') <span
                                            class="totalExchange"></span></small></sub>
                            </h4>
                            <div class="statistics">
                                <p class="growth"><i
                                        class="fa-light fa-chart-line-up"></i><span
                                        class="last30DaysActivePercentage"></span>
                                    %</p>
                                <div class="time">@lang('last 30 days')</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="item">
                    <div class="box-card grayish-green-card exchangeRecord">
                        <div class="box-card-header">
                            <h5 class="box-card-title"><i
                                    class="fa-light fa-exclamation-triangle"></i>@lang('Expired Exchange')
                            </h5>
                        </div>
                        <div class="box-card-body">
                            <h4 class="mb-0"><span class="completeExchange"></span>
                                <sub><small>@lang('from') <span
                                            class="totalExchange"></span></small></sub>
                            </h4>
                            <div class="statistics">
                                <p class="growth down"><i
                                        class="fa-light fa-chart-line-down"></i><span
                                        class="last30DaysCompletePercentage"></span>
                                    %</p>
                                <div class="time">@lang('last 30 days')</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="item">
                    <div class="box-card strong-orange-card exchangeRecord">
                        <div class="box-card-header">
                            <h5 class="box-card-title"><i
                                    class="fa-light fas fa-undo-alt"></i>@lang('Refund Exchange')</h5>
                        </div>
                        <div class="box-card-body">
                            <h4 class="mb-0"><span class="refundExchange"></span>
                                <sub><small>@lang('from') <span
                                            class="totalExchange"></span></small></sub>
                            </h4>
                            <div class="statistics">
                                <p class="growth"><i
                                        class="fa-light fa-chart-line-up"></i><span
                                        class="last30DaysRefundPercentage"></span>
                                    %</p>
                                <div class="time">@lang('last 30 days')</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="tab-mobile-view-carousel-section mb-30 d-lg-none">
    <div class="row">
        <div class="col-12">
            <h5 class="mb-10"> @lang('Top Up Statistics')</h5>
            <div class="owl-carousel owl-theme carousel-1">
                <div class="item">
                    <div class="box-card strong-orange-card  exchangeRecord">
                        <div class="box-card-header">
                            <h5 class="box-card-title"><i
                                    class="fa-light fas fa-spinner"></i>@lang('Pending Top Up')
                            </h5>
                        </div>
                        <div class="box-card-body">
                            <h4 class="mb-0"><span class="pendingTopUp"></span>
                                <sub><small>@lang('from') <span
                                            class="totalTopUp"></span></small></sub>
                            </h4>
                            <div class="statistics">
                                <p class="growth"><i
                                        class="fa-light fa-chart-line-up"></i><span
                                        class="last30DaysPendingPercentage"></span>
                                    %</p>
                                <div class="time">@lang('last 30 days')</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="item">
                    <div class="box-card grayish-green-card exchangeRecord">
                        <div class="box-card-header">
                            <h5 class="box-card-title"><i
                                    class="fa-light fas fa-check"></i>@lang('Complete Top Up')
                            </h5>
                        </div>
                        <div class="box-card-body">
                            <h4 class="mb-0"> <span class="completeTopUp"></span>
                                <sub><small>@lang('from') <span
                                            class="totalTopUp"></span></small></sub>
                            </h4>
                            <div class="statistics">
                                <p class="growth"><i
                                        class="fa-light fa-chart-line-up"></i><span
                                        class="last30DaysCompletePercentage"></span>
                                    %</p>
                                <div class="time">@lang('last 30 days')</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="item">
                    <div class="box-card grayish-custom-card exchangeRecord">
                        <div class="box-card-header">
                            <h5 class="box-card-title"><i
                                    class="fa-light fa-exclamation-triangle"></i>@lang('Cancel Top Up')
                            </h5>
                        </div>
                        <div class="box-card-body">
                            <h4 class="mb-0"> <span class="cancelTopUp"></span>
                                <sub><small>@lang('from') <span
                                            class="totalTopUp"></span></small></sub>
                            </h4>
                            <div class="statistics">
                                <p class="growth down"><i
                                        class="fa-light fa-chart-line-down"></i><span
                                        class="last30DaysCancelPercentage"></span>
                                    %</p>
                                <div class="time">@lang('last 30 days')</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="item">
                    <div class="box-card strong-orange-card exchangeRecord">
                        <div class="box-card-header">
                            <h5 class="box-card-title"><i
                                    class="fa-light fas fa-undo-alt"></i>@lang('Total Top Up')</h5>
                        </div>
                        <div class="box-card-body">
                            <h4 class="mb-0"> <span class="totalTopUp"></span>
                                <sub><small>@lang('from') <span
                                            class="totalTopUp"></span></small></sub>
                            </h4>
                            <div class="statistics">
                                <p class="growth"><i
                                        class="fa-light fa-chart-line-up"></i><span
                                        class="last30DaysTotalPercentage"></span>
                                    %</p>
                                <div class="time">@lang('last 30 days')</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="tab-mobile-view-carousel-section mb-30 d-lg-none">
    <div class="row">
        <div class="col-12">
            <h5 class="mb-10"> @lang('Payout Statistics')</h5>

            <div class="owl-carousel owl-theme carousel-1">
                <div class="item">
                    <div class="box-card strong-orange-card exchangeRecord">
                        <div class="box-card-header">
                            <h5 class="box-card-title"><i
                                    class="fa-light fas fa-spinner"></i>@lang('Pending Payout')
                            </h5>
                        </div>
                        <div class="box-card-body">
                            <h4 class="mb-0"> <span class="pendingPayout"></span>
                                <sub><small>@lang('from') <span
                                            class="totalPayout"></span></small></sub>
                            </h4>
                            <div class="statistics">
                                <p class="growth"><i
                                        class="fa-light fa-chart-line-up"></i><span
                                        class="last30DaysPendingPercentagePayout"></span>
                                    %</p>
                                <div class="time">@lang('last 30 days')</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="item">
                    <div class="box-card grayish-green-card exchangeRecord">
                        <div class="box-card-header">
                            <h5 class="box-card-title"><i
                                    class="fa-light fas fa-check"></i>@lang('Complete Payout')
                            </h5>
                        </div>
                        <div class="box-card-body">
                            <h4 class="mb-0"> <span class="completePayout"></span>
                                <sub><small>@lang('from') <span
                                            class="totalPayout"></span></small></sub>
                            </h4>
                            <div class="statistics">
                                <p class="growth"><i
                                        class="fa-light fa-chart-line-up"></i><span
                                        class="last30DaysCompletePercentagePayout"></span>
                                    %</p>
                                <div class="time">@lang('last 30 days')</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="item">
                    <div class="box-card grayish-blue-card exchangeRecord">
                        <div class="box-card-header">
                            <h5 class="box-card-title"><i
                                    class="fa-light fa-exclamation-triangle"></i>@lang('Cancel Payout')
                            </h5>
                        </div>
                        <div class="box-card-body">
                            <h4 class="mb-0"> <span class="cancelPayout"></span>
                                <sub><small>@lang('from') <span
                                            class="totalPayout"></span></small></sub>
                            </h4>
                            <div class="statistics">
                                <p class="growth down"><i
                                        class="fa-light fa-chart-line-down"></i><span class="last30DaysCancelPercentagePayout"></span>
                                    %</p>
                                <div class="time">@lang('last 30 days')</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="item">
                    <div class="box-card grayish-custom-card exchangeRecord">
                        <div class="box-card-header">
                            <h5 class="box-card-title"><i
                                    class="fa-light fas fa-undo-alt"></i>@lang('Total Payout')</h5>
                        </div>
                        <div class="box-card-body">
                            <h4 class="mb-0"> <span class="totalPayout"></span>
                                <sub><small>@lang('from') <span
                                            class="totalPayout"></span>
                                    </small></sub></h4>
                            <div class="statistics">
                                <p class="growth"><i
                                        class="fa-light fa-chart-line-up"></i><span
                                        class="last30DaysTotalPercentagePayout"></span>
                                    %</p>
                                <div class="time">@lang('last 30 days')</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="tab-mobile-view-carousel-section mb-30 d-lg-none">
    <div class="row">
        <div class="col-12">
            <h5 class="mb-10"> @lang('Other Wallet Statistics')</h5>

            <div class="owl-carousel owl-theme carousel-1">
                <div class="item">
                    <div class="box-card strong-orange-card exchangeRecord">
                        <div class="box-card-header">
                            <h5 class="box-card-title"><i
                                    class="fa-light fas fa-spinner"></i>@lang('Balance')
                            </h5>
                        </div>
                        <div class="box-card-body">
                            <h4 class="mb-0"> <span class="totalBalance"></span>
                                <sub><small>@lang('from') <span
                                            class="totalBalance"></span></small></sub>
                            </h4>

                        </div>
                    </div>
                </div>

                <div class="item">
                    <div class="box-card grayish-green-card exchangeRecord">
                        <div class="box-card-header">
                            <h5 class="box-card-title"><i
                                    class="fa-light fas fa-check"></i>@lang('Referral Bonus')
                            </h5>
                        </div>
                        <div class="box-card-body">
                            <h4 class="mb-0"> <span class="totalReferralBonus"></span>
                                <sub><small>@lang('from') <span
                                            class="totalReferralBonus"></span></small></sub>
                            </h4>
                            <div class="statistics">
                                <p class="growth"><i
                                        class="fa-light fa-chart-line-up"></i>$<span
                                        class="totalSumReferralBonus"></span>
                                    </p>
                                <div class="time">@lang('in USD')</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="item">
                    <div class="box-card grayish-blue-card exchangeRecord">
                        <div class="box-card-header">
                            <h5 class="box-card-title"><i
                                    class="fa-light fa-exclamation-triangle"></i>@lang('Total Return')
                            </h5>
                        </div>
                        <div class="box-card-body">
                            <h4 class="mb-0"> <span class="totalReturn"></span>
                                <sub><small>@lang('from') <span
                                            class="totalReturn"></span></small></sub>
                            </h4>
                            <div class="statistics">
                                <p class="growth down"><i
                                        class="fa-light fa-chart-line-down"></i>$<span class="totalSumReturn"></span>
                                    </p>
                                <div class="time">@lang('in USD')</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="item">
                    <div class="box-card grayish-custom-card exchangeRecord">
                        <div class="box-card-header">
                            <h5 class="box-card-title"><i
                                    class="fa-light fas fa-undo-alt"></i>@lang('Total Exchange')</h5>
                        </div>
                        <div class="box-card-body">
                            <h4 class="mb-0"> <span class="totalExchange"></span>
                                <sub><small>@lang('from') <span
                                            class="totalExchange"></span>
                                    </small></sub></h4>
                            <div class="statistics">
                                <p class="growth"><i
                                        class="fa-light fa-chart-line-up"></i>$<span
                                        class="totalSumExchange"></span>
                                    </p>
                                <div class="time">@lang('in USD')</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

