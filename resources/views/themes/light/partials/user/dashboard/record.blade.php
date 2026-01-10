<div class="col-12 d-none d-lg-block">
    <h5 class="mb-10 mt-4"> @lang('Exchange Statistics')</h5>
    <div class="row g-4">

        <div class="col-xxl-3 col-sm-6 box-item">
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
        <div class="col-xxl-3 col-sm-6 box-item">
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
        <div class="col-xxl-3 col-sm-6 box-item">
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
        <div class="col-xxl-3 col-sm-6 box-item">
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

<div class="col-12 d-none d-lg-block mt-30">
    <h5 class="mb-10"> @lang('Top Up Statistics')</h5>
    <div class="row g-4">
        <div class="col-xxl-3 col-sm-6 box-item">
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
                                class="last30DaysPendingPercentageTopUp"></span>
                            %</p>
                        <div class="time">@lang('last 30 days')</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xxl-3 col-sm-6 box-item">
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
                                class="last30DaysCompletePercentageTopUp"></span>
                            %</p>
                        <div class="time">@lang('last 30 days')</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xxl-3 col-sm-6 box-item">
            <div class="box-card grayish-blue-card exchangeRecord">
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
                                class="last30DaysCancelPercentageTopUp"></span>
                            %</p>
                        <div class="time">@lang('last 30 days')</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xxl-3 col-sm-6 box-item">
            <div class="box-card grayish-custom-card exchangeRecord">
                <div class="box-card-header">
                    <h5 class="box-card-title"><i
                            class="fa-light fas fa-undo-alt"></i>@lang('Total Completed Top Up($)')</h5>
                </div>
                <div class="box-card-body">
                    <h4 class="mb-0"> $<span class="totalCompletedTopUp"></span>

                    </h4>

                </div>
            </div>
        </div>
    </div>
</div>
<div class="col-12 d-none d-lg-block mt-30">
    <h5 class="mb-10"> @lang('Payout Statistics')</h5>
    <div class="row g-4">
        <div class="col-xxl-3 col-sm-6 box-item">
            <div class="box-card grayish-custom-card exchangeRecord">
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
        <div class="col-xxl-3 col-sm-6 box-item">
            <div class="box-card grayish-blue-card exchangeRecord">
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
        <div class="col-xxl-3 col-sm-6 box-item">
            <div class="box-card strong-orange-card exchangeRecord">
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
        <div class="col-xxl-3 col-sm-6 box-item">
            <div class="box-card grayish-green-card exchangeRecord">
                <div class="box-card-header">
                    <h5 class="box-card-title"><i
                            class="fa-light fas fa-undo-alt"></i>@lang('Total Completed Payout($)')</h5>
                </div>
                <div class="box-card-body">
                    <h4 class="mb-0"> $<span class="totalCompletedPayout"></span>
                        </h4>

                </div>
            </div>
        </div>
    </div>
</div>

@push('extra_scripts')
    <script>
        'use strict';
        Notiflix.Block.standard('.exchangeRecord', {
            backgroundColor: loaderColor,
        });

        axios.get("{{route('user.getRecords')}}")
            .then(function (res) {
                $('.userBalance').text(res.data.userBalance);
                $('.totalExchanged').text(res.data.totalExchanged);
                $('.totalStaked').text(res.data.totalStaked);
                $('.totalStakedAmount').text(res.data.totalStakedAmount);
                $('.totalExchange').text(res.data.totalExchange);
                $('.pendingExchange').text(res.data.pendingExchange);
                $('.last30DaysPendingPercentage').text(res.data.last30DaysPendingPercentage);
                $('.completeExchange').text(res.data.completeExchange);
                $('.last30DaysCompletePercentage').text(res.data.last30DaysCompletePercentage);
                $('.activeExchange').text(res.data.activeExchange);
                $('.last30DaysActivePercentage').text(res.data.last30DaysActivePercentage);
                $('.refundExchange').text(res.data.refundExchange);
                $('.last30DaysRefundPercentage').text(res.data.last30DaysRefundPercentage);
                $('.totalTopUp').text(res.data.totalTopUp);
                $('.pendingTopUp').text(res.data.pendingTopUp);
                $('.last30DaysPendingPercentageTopUp').text(res.data.last30DaysPendingPercentageTopUp);
                $('.completeTopUp').text(res.data.completeTopUp);
                $('.last30DaysCompletePercentageTopUp').text(res.data.last30DaysCompletePercentageTopUp);
                $('.cancelTopUp').text(res.data.cancelTopUp);
                $('.last30DaysCancelPercentageTopUp').text(res.data.last30DaysCancelPercentageTopUp);
                $('.totalCompletedTopUp').text(res.data.totalCompletedTopUp);
                $('.totalPayout').text(res.data.totalPayout);
                $('.pendingPayout').text(res.data.pendingPayout);
                $('.last30DaysPendingPercentagePayout').text(res.data.last30DaysPendingPercentagePayout);
                $('.completePayout').text(res.data.completePayout);
                $('.last30DaysCompletePercentagePayout').text(res.data.last30DaysCompletePercentagePayout);
                $('.cancelPayout').text(res.data.cancelPayout);
                $('.last30DaysCancelPercentagePayout').text(res.data.last30DaysCancelPercentagePayout);
                $('.totalCompletedPayout').text(res.data.totalCompletedPayout);

                Notiflix.Block.remove('.exchangeRecord');
            })
            .catch(function (error) {
                Notiflix.Notify.failure('Error loading stats');
                Notiflix.Block.remove('.exchangeRecord');
            });
    </script>
@endpush
