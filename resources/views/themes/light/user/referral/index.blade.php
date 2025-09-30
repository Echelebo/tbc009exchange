@extends($theme.'layouts.user')
@section('page_title',__('Referral'))
@section('content')
<div class="section dashboard">
        <div class="row">
            <div class="col-12">
                <div class="row mb-3">
                    <div class="col-xl-6 col-lg-12 mb-5">
                        <div class="card">
                            <div class="card-body">
                                <div class="row g-4">
                                    <div class="col-md-6">
                                        <div class="commission d-flex align-items-center justify-content-start">
                                            <div><i class="fa-duotone fa-sack-dollar"></i></div>
                                            <div class="ms-4"><h5>Total Commission</h5>
                                                <p>${{ number_format($commission, 2) }}</p></div>
                                        </div>
                                    </div>
                                    
                                </div>
                                <div class="qna mt-4">
                                    <h5>Referral bonus is 5% of your downlines staked amount.</h5>
                                    <p>Refer TBCians and earn more commission</p>
                                </div>
                                <div class=" share_link d-flex align-items-center mt-4">
                                    <i class="fa-sharp fa-regular fa-share-nodes"></i> &nbsp; &nbsp; Referral ID: 
                                    <input type="text" class="input border-0" style="background-color: #2e403e; color: #ffffff; width: 50px;" id="referralURL" value=" {{ $userId }} " readonly="">
                                    <button class="copy_btn" onclick="copyFunction()"><i class="fa-regular fa-copy" style="color: #ffffff;"></i>
                                    </button>
                                </div>
                                <p class="refurlText mt-4">Copy your referral ID and share with your friends</p>

                                
                            </div>
                        </div>
                    </div>
                    
                </div>
                                    <div class="user-wrapper">
                        <div class="user-table">
                            <div class="card">
                                <div class="card-body">
                                    <div class="cmn-table skltbs-panel">
                                        <div class="table-responsive">
                                            <h6>Parent</h6>
                                            <p class="mt-4"> 
                                            @if($upline)
                                                {{ $upline->referral_by->username }}
                                            @else
                                                No Referrer
                                            @endif</p>
                                            <table class="table align-middle">
                                                <thead>
                                                <tr>
                                                    <th scope="col">Username</th>
                                                    <th scope="col">Joined At</th>
                                                </tr>
                                                </thead>
                                               
                                                
                                                    <tbody class="block-statistics">
                                                        @if(count($referrals) > 0)
                                                        @foreach ( $referrals as $referral)
                                                        <tr id="user-{{ $referral->id}}" data-level="0" data-loaded="false">
                                                        <td data-label="Username">
                                                            <a href="javascript:void(0)" class="" data-id="{{ $referral->id }}">
                                                              {{ $referral->username }}
                                                            </a>
                                                        </td>
                                                        
                                                        <td data-label="Joined At">
                                                            {{ dateTime($referral->created_at,basicControl()->date_time_format) }}
                                                        </td>

                                                    </tr>
                                                    @endforeach
                                                    @else
                                                    @include('empty')
                                                    @endif
                                                                                                </tbody>
                                                
                                                
                                            </table>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                            </div>
        </div>
    </div>
    @endsection
    @push('extra_scripts')
    
    <script>
        
        function copyFunction() {
            var copyText = document.getElementById("referralURL");
            copyText.select();
            copyText.setSelectionRange(0, 99999);
            /*For mobile devices*/
            document.execCommand("copy");
            Notiflix.Notify.success(`Copied: ${copyText.value}`);
        }

    </script>

    @endpush