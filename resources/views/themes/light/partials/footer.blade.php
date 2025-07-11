<!-- Footer Section start -->
<section class="footer-section pb-50">
    <div class="footer-inner-section">
        <div class="container">
            <div class="row g-4 g-sm-5">
                <div class="col-lg-4 col-sm-6">
                    <div class="footer-widget">
                        <div class="widget-logo mb-30">
                            <a href="{{route('page')}}"><img class="logo" src="{{getFile(basicControl()->dark_logo_driver,basicControl()->dark_logo)}}" width="50" alt="..."></a>
                        </div>



                    </div>
                </div>
                <div class="col-lg-2 col-sm-6">
                    <div class="footer-widget">
                        <h5 class="widget-title">@lang('Useful Links')</h5>
                        <ul>
                            @if(getFooterMenuData('useful_link') != null)
                                @foreach(getFooterMenuData('useful_link') as $list)
                                    {!! $list !!}
                                @endforeach
                            @endif
                            <li><a class="widget-link" href="https://tbc009.org">TBC009 Wallet</a></li>
                            <li><a class="widget-link" href="{{route('tracking')}}">@lang('Tracking')</a></li>
                        </ul>
                    </div>
                </div>

                @if(isset($extraInfo['contact'][0]->description))
                    <div class="col-lg-3 col-sm-6 pt-sm-0 pt-3">
                        <div class="footer-widget">
                            <h5 class="widget-title">@lang('Contact Us')</h5>

                            <p class="contact-item"><i
                                    class="fa-regular fa-envelope"></i> {{@$extraInfo['contact'][0]->description->email}}
                            </p>
                            <p class="contact-item"><i
                                    class="fa-brands fa-telegram"></i> <a href="https://t.me/tbc009updateofficial" target="_blank">Join Telegram Community</a>
                            </p>

                        </div>
                    </div>
                @endif
            </div>
            <hr class="cmn-hr">
            <!-- Copyright-area-start -->
            <div class="copyright-area">
                <div class="row gy-4">
                    <div class="col-sm-6">
                        <p>@lang('Copyright') ©{{date('Y')}} <a
                                                                href="javascript:void(0)">@lang(basicControl()->site_title)</a> @lang('All Rights Reserved')
                        </p>
                    </div>
                    
                </div>
            </div>
            <!-- Copyright-area-end -->
        </div>
    </div>

</section>
<!-- Footer Section end -->
