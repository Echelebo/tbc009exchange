@php
    $announces = \App\Models\CoinAnnounce::where('status',1)->get();
@endphp
    <!-- Hero section start -->
<div class="hero-section">
    <div class="hero-section-inner">
        <div class="container">
            <div class="row g-4 g-sm-5 justify-content-between align-items-center">
                @if(isset($hero['single']))
                    <div class="col-xxl-6 col-lg-6">
                        <div class="hero-content">
                            <h1 class="hero-title">@lang(wordSplice(@$hero['single']['heading'],4)['exceptTwo']) <span
                                    class="highlight">@lang(wordSplice(@$hero['single']['heading'],4)['lastTwo'])</span>
                            </h1>
                            <p class="hero-description">@lang(@$hero['single']['sub_heading'])</p>
                            <div class="btn-area">
                                <a href="{{@$hero['single']['media']->my_link}}"
                                   class="cmn-btn">@lang(@$hero['single']['button_name'])</a>
                                <a href="{{@$hero['single']['media']->video_link}}" class="cmn-btn2 text-with-icon"><i
                                        class="fa-regular fa-circle-play"></i>@lang(@$hero['single']['video_button_name'])
                                </a>
                            </div>
                        </div>
                    </div>
                @endif

                <div class="col-xxl-5 col-lg-6">
                    <div class="calculator-section">
                        <form class="calculator" action="{{route('exchangeRequest')}}" method="POST"
                              id="submitFormId">
                            @csrf
                            <div class="autoplay" data-bs-toggle="modal" data-bs-target="#exampleModal">
                                @if(count($announces)>0)
                                    @foreach($announces as $announce)
                                        <div class="calculator-banner announceClass"
                                             data-heading="{{$announce->heading}}"
                                             data-des="{!! $announce->description !!}">
                                            <div class="calculator-banner-wrapper">
                                                <div class="left-side">
                                                    <div class="image-area">
                                                        <img src="{{getFile($announce->driver,$announce->image)}}"
                                                             alt="...">
                                                    </div>
                                                    <div class="text-area">
                                                        <p class="fw-bold mb-0">@lang(\Illuminate\Support\Str::limit($announce->heading,55))</p>
                                                    </div>
                                                </div>
                                                <div class="right-side">
                                                    <i class="fa-regular fa-angle-right"></i>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                @endif
                            </div>

                            <div class="calculator-body">

                                <div>
                                    <div class="tab-content" id="pills-tabContent">
                                        <div class="tab-pane fade show active" id="pills-exchange" role="tabpanel"
                                             aria-labelledby="pills-exchange-tab" tabindex="0">
                                            @include($theme.'partials.exchange-module.exchange')
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="col-xxl-12 col-lg-12 mt-4">
                    <h5>Top Exchangers</h5>
                </div>
                <div class="col-xxl-12 col-lg-12 mt-4">
                    <h5>Latest Exchange</h5>
                </div>

            </div>
        </div>
    </div>
    <div class="shape shape1">
        <img src="{{$themeTrue.'img/coin/coin-2.png'}}" alt="...">
    </div>
</div>
@include($theme.'partials.modal')


