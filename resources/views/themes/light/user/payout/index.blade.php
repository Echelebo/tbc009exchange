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
                                            <div class="ms-4"><h5>Total Payout amount ($)</h5>
                                                <p>${{ number_format($commission, 2) }}</p></div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="commission d-flex align-items-center justify-content-start">
                                            <div><i class="fa-duotone fa-sack-dollar"></i></div>
                                            <div class="ms-4"><h5>Total count of Payouts.</h5>
                                                <p>${{ number_format($commission, 2) }}</p></div>
                                        </div>
                                    </div>
                                    
                                </div>
                               <!-- <div class="qna mt-4">
                                    <h5>Referral bonus is 5% of your downlines staked amount.</h5>
                                    <p>Refer TBCians and earn more commission</p>
                                </div> -->
                               
                               <div class=" share_link d-flex align-items-center mt-4">
                                <p>Choose a payout wallet</p>
                                <label id="usdttrc20">USDT TRC-20</label>
                                    <input type="text" class="input border-0" style="background-color: #2e403e; color: #ffffff; width: 50px;" id="usdttrc20" value="" readonly="">
                                    <button class="copy_btn" onclick="copyFunction()"><i class="fa-regular fa-copy" style="color: #ffffff;"></i>
                                    </button>
                                </div>

                                

                                <p class="refurlText">Copy your preferred wallet to Top Up your balance.</p>

                            <form method="POST" action="{{route('...')}}"  class="mt-4">

                                <select name="topUpMethod" class="form-control" required>
                                    <option value="">Select Top Up method</option>
                                    <option value="usdttrc20">USDT TRC-20</option>
                                    <option value="usdterc20">USDT ERC-20</option>
                                    <option value="usdtbep20">USDT BEP-20</option>
                                </select>
                                
                                <label id="amount">Amount ($)</label>
                                <input type="text" class="input" style="background-color: #2e403e; color: #ffffff; width: 50px;" name="amount" id="amount" value="" required>

                                <label id="hash_id">Hash Id (paste the hash Id of the usdt payment, for fast confirmation.)</label>
                                <input type="text" class="input" style="background-color: #2e403e; color: #ffffff; width: 50px;" name="hash_id" id="hash_id" value="" required>


                                <p class="mt-4">Make sure you have sent USDT payment before pressing submit.</p>



                                <button type="submit" class="search-btn2 mt-4 px-8" style="background-color:#c1923f; border-radius: 15px; padding: 10px; font-size: 15px; color: #ffffff; ">Submit Top Up
                                    </button>

                            </form>
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
                                            
                                            <table class="table align-middle">
                                                <thead>
                                                <tr>
                                                    <th scope="col">Amount</th>
                                                    <th scope="col">Method</th>
                                                    <th scope="col">Status</th>
                                                    <th scope="col">TIme</th>
                                                </tr>
                                                </thead>
                                               
                                                
                                                    <tbody class="block-statistics">
                                                        @if(count($topups) > 0)
                                                        @foreach ( $topups as $topup)
                                                        <tr >
                                                        <td data-label="Amount">  
                                                              {{ $topup->amount }}
                                                        </td>
                                                        
                                                        <td data-label="Method">
                                                            {{ $topup->method }}
                                                        </td>
                                                        <td data-label="Status">
                                                            {{ $topup->status }}
                                                        </td>
                                                        <td data-label="Time">
                                                            {{ dateTime($topup->created_at,basicControl()->date_time_format) }}
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