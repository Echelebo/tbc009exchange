<!-- Newsletter section start -->
@if(isset($subscribe))
    <section class="newsletter-section">
        <div class="container">
            <div class="row g-4 align-items-center justify-content-center">
                <div class="col-xl-5 col-lg-7">
                    <div class="content-area">

                            <h3 class="subscribe-normal-text">Track your Exchange, to see the exchange status and details.</h3>


                        <form action="{{ route('tracking') }}" method="GET" class="newsletter-form">
                            @csrf
                            <input type="text" value="{{@request()->trx_id}}" name="trx_id" class="form-control" placeholder="e.g 65defbe618d07"/>
                            <button type="submit" class="subscribe-btn">@lang('Track')</button>
                        </form>

                    </div>
                </div>
                @if(isset($type) && $type == 'exchange')
                @include($theme.'exchangeTruck')
            @endif
            @if(isset($type) && $type == 'buy')
                @include($theme.'buyTruck')
            @endif
            @if(isset($type) && $type == 'sell')
                @include($theme.'sellTruck')
            @endif
            </div>
        </div>
    </section>
@endif
<!-- Newsletter section end -->
