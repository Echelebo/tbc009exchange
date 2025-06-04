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

                @if ($object->status == 9)
                <div class="table-row">
                    <div class="item">
                        <span>Daily(24 Hours) return</span>
                        <h6>{{$object->get_amount/4}}</h6>
                    </div>
                    
                    <div class="item">
                        <span>Next return time</span>
                        <h6>{{$object->next_trade}}</h6>
                    </div>
                   
                </div>
                @endif
                @if ($object->status < 8)
                <div class="table-row">
                    <div class="itemx">
                        <i class="fa fa-info-circle" aria-hidden="true" style="color: #c1923f"></i>Hello, to complete your exchange, you have to stake USDT equivalent to the exchange amount.

                        <p>Make the staking payment to any of our addresses below and submit to finalize your exchange.</p>
                        <div><p>USDT-TRC20: TBFLFQGifn29ZrJS2Mk6UXQYHRjs8yVmG9</p>
                            <p>USDT-BEP20: 0x4ec85660f919367f4a5f11860e828405c7b06cbf</p>
                            <p>USDT-ERC20: 0x4ec85660f919367f4a5f11860e828405c7b06cbf</p>
                        </div>

                        <form  method="POST" action="{{route('trackingx')}}">
                            <input type="hidden" value="{{$object->utr}}" name="trx_id">
                            <input type="text" value="" name="hash_id" class="form-control"
                                   placeholder="e.g 65defbe618d07">
                            <button type="submit">Submit</button>
                        </form>

                    </div>

                </div>
                @endif

                @if ($object->status == 8)

                <div class="table-row">
                    <div class="itemx">
                        <i class="fa fa-info-circle" aria-hidden="true" style="color: #c1923f"></i>Hello, we are confirming your stake. You wil receive a notification once your stake has been approved.

                        

                    </div>

                </div>

                @endif

                @if ($object->status == 9)

                <div class="table-row">
                    <div class="itemx">
                        <i class="fa fa-info-circle" aria-hidden="true" style="color: #c1923f"></i>Hello, your exchange has been confirmed. Click Exchange List from your account to view exchange.

                        

                    </div>

                </div>

                @endif

            </div>
        </div>
    </div>
</div>



