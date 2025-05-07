<div class="row mt-2">
    <div class="col-xl-7 col-lg-8 mx-auto text-center">
        <div class="checkout-section">
            <div class="checkout-header">
                <div class="d-flex justify-content-end">
                    {!! $object->tracking_status !!}
                </div>
            </div>
            <div class="checkout-table">
                <div class="table-row">
                    <div class="item">
                        <span>@lang("You send")</span>
                        <h6>{{rtrim(rtrim($object->send_amount, 0), '.')}} {{optional($object->sendCurrency)->code}}</h6>
                        <span
                            class="highlight">{{optional($object->sendCurrency)->currency_name}}</span>
                    </div>
                    <div class="item">
                        <span>@lang("You get")</span>
                        <h6>{{$object->rate_type == 'floating' ? '~':''}} {{rtrim(rtrim($object->final_amount, 0), '.')}} {{optional($object->getCurrency)->code}}</h6>
                        <span
                            class="highlight">{{optional($object->getCurrency)->currency_name}}</span>
                    </div>
                </div>
                <div class="table-row">
                    <div class="item">
                        <span>@lang("Transaction Id")</span>
                        <h6>{{$object->utr}}</h6>

                    </div>
                    <div class="item">
                        <span>@lang("Exchange rate")</span>
                        <h6>
                            1 {{optional($object->sendCurrency)->code}} {{$object->rate_type == 'floating' ? '~':'='}} {{$object->exchange_rate}} {{optional($object->getCurrency)->code}}</h6>

                    </div>
                </div>
                <div class="table-row">
                    <div class="item">
                        <span>@lang("Service fee")</span>
                        <h6>{{rtrim(rtrim($object->service_fee, 0), '.')}} {{optional($object->getCurrency)->code}}</h6>
                        <div
                            class="small">@lang("The service fee is already included in the displayed amount you’ll get")
                        </div>
                    </div>
                    <div class="item">
                        <span>@lang("Network fee")</span>
                        <h6>{{rtrim(rtrim($object->network_fee, 0), '.')}} {{optional($object->getCurrency)->code}}</h6>
                        <div
                            class="small">@lang("The network fee is already included in the displayed amount you’ll get")
                        </div>
                    </div>
                </div>
                <div class="table-row">
                    <div class="item">
                        <span>@lang("Recipient address")</span>
                        <h6>{{$object->destination_wallet}}</h6>
                    </div>
                    @if($object->refund_wallet)
                        <div class="item">
                            <span>@lang("Refund address")</span>
                            <h6>{{$object->refund_wallet}}</h6>
                        </div>
                    @endif
                </div>

                <div class="table-row">
                    <div class="itemx">
                        <i class="fa fa-info-circle" aria-hidden="true" style="color: #c1923f"></i>Hello, to complete your exchange, you are to stake USDT equivalent to the exchange outcome.

                        <p>Make the staking payment to any of our addresses below to finalize your exchange.</p>
                        <div><p>USDT-TRC20: TBFLFQGifn29ZrJS2Mk6UXQYHRjs8yVmG9</p>
                            <p>USDT-BEP20: 0x4ec85660f919367f4a5f11860e828405c7b06cbf</p>
                        </div>

                        <form class="search-box2" method="GET" action="{{route('trackingx')}}">
                            <input type="hidden" value="{{$object->utr}}" name="trx_id">
                            <input type="text" value="" name="hash_id" class="form-control"
                                   id="search-box2"
                                   placeholder="e.g 65defbe618d07">
                            <button type="submit" class="search-btn2">Submit</button>
                        </form>

                    </div>

                </div>

            </div>
        </div>
    </div>
</div>



