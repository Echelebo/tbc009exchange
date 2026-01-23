<!-- Feature section start -->
@if (isset($feature['single']))
    <section class="feature-section">
        <div class="feature-section-inner">
            <div class="container">
                <div class="row g-4 g-xxl-5 align-items-center">
                    <div class="col-lg-6 order-2 order-lg-1">
                        <div class="section-header">
                            <h2 class="section-title"><span class="highlight">@lang('Latest')</span> @lang('Exchange')
                            </h2>

                        </div>

                            <div class="row g-3">

                            </div>

                    </div>
                    <div class="col-lg-6 order-1 order-lg-2">
                        <div class="feature-thumbs">
                            {{-- <img src="{{getFile(@$feature['single']['media']->image->driver,@$feature['single']['media']->image->path)}}" alt="..."> --}}
                            <div class="orbit">
                                <img src="./assets/upload/contents/orbit.png" alt="">
                            </div>
                            <div class="bitcoin">
                                <img src="./assets/upload/contents/bitcoin.png" alt="">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-gradiend1">
            <div class="bg-gradiend1-inner"></div>
        </div>
        <div class="shape shape3 opacity-100">
            <img src="{{ asset($themeTrue . 'img/coin/5.png') }}" alt="...">
        </div>
    </section>
@endif
<!-- Feature section end -->
