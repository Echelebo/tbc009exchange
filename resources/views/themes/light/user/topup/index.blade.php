@extends($theme.'layouts.user')
@section('page_title',__('Top Up'))
@section('content')
<div class="section dashboard">
        <div class="row">
            <div class="col-12">
                <div class="row mb-3">
                    <div class="col-xl-12 col-lg-12 mb-5">
                        <div class="card">
                            <div class="card-body">
                                <div class="row g-4">
                                    <div class="col-md-6">
                                        <div class="commission d-flex align-items-center justify-content-start">
                                            <div><i class="fa-duotone fa-sack-dollar"></i></div>
                                            <div class="ms-4"><h5>Total Top Up amount ($)</h5>
                                                <p>${{ number_format($commission, 2) }}</p></div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="commission d-flex align-items-center justify-content-start">
                                            <div><i class="fa-duotone fa-sack-dollar"></i></div>
                                            <div class="ms-4"><h5>Total count of Top Up.</h5>
                                                <p>{{ $commissions->count() }}</p></div>
                                        </div>
                                    </div>
                                    
                                </div>
                               <!-- <div class="qna mt-4">
                                    <h5>Referral bonus is 5% of your downlines staked amount.</h5>
                                    <p>Refer TBCians and earn more commission</p>
                                </div> -->

                                <p class="refurlText mt-4">Copy your preferred wallet to Top Up your balance.</p>
                               
                               <div class=" share_link d-flex align-items-center mt-4">
                                <label id="usdttrc20">USDT TRC-20: </label>
                                    <input type="text" class="input form-control border-0" style="background-color: #2e403e; color: #ffffff; width: 50%;" id="usdttrc20" value="TBFLFQGifn29ZrJS2Mk6UXQYHRjs8yVmG9" readonly="">
                                    <button class="copy_btn" onclick="copyFunction()"><i class="fa-regular fa-copy" style="color: #ffffff;"></i>
                                    </button>
                                </div>

                                <div class=" share_link d-flex align-items-center mt-4">
                                    <label id="usdterc20">USDT ERC-20: </label>
                                    <input type="text" class="input form-control border-0" style="background-color: #2e403e; color: #ffffff; width: 50%;" id="usdterc20" value="0x4ec85660f919367f4a5f11860e828405c7b06cbf" readonly="">
                                    <button class="copy_btn" onclick="copyFunction2()"><i class="fa-regular fa-copy" style="color: #ffffff;"></i>
                                    </button>
                                </div>

                                <div class=" share_link d-flex align-items-center mt-4">
                                    <label id="usdtbep20">USDT BEP-20: </label>
                                    <input type="text" class="input form-control border-0" style="background-color: #2e403e; color: #ffffff; width: 50%;" id="usdtbep20" value="0x4ec85660f919367f4a5f11860e828405c7b06cbf" readonly="">
                                    <button class="copy_btn" onclick="copyFunction3()"><i class="fa-regular fa-copy" style="color: #ffffff;"></i>
                                    </button>
                                </div>

                                

                            <form method="POST" action="{{ route('topup.fromSubmit') }}"  class="col-md-6 mt-4">
                                @csrf
                                <select name="method" class="form-control" style="width: 50%" required>
                                    <option value="">Select Top Up method</option>
                                    <option value="usdttrc20">USDT TRC-20</option>
                                    <option value="usdterc20">USDT ERC-20</option>
                                    <option value="usdtbep20">USDT BEP-20</option>
                                </select>
                                
                                
                                <label id="amount" class="mt-4">Amount ($)</label>
                                <input type="text" class="input form-control" style="color: #ffffff; width: 50%" name="amount" id="amount" value="" required>
                                
                                
                                <label id="hash" class="mt-4">Hash Id (paste the hash Id of the usdt payment, for fast confirmation.)</label>
                                <input type="text" class="input form-control" style="color: #ffffff; width: 80%" name="hash" id="hash_id" value="" required>
                                

                                <p class="mt-4">Make sure you have sent USDT payment before clicking submit.</p>



                                <button type="submit" class="search-btn2 mt-4 px-8" style="background-color:#c1923f; border-radius: 15px; padding: 10px; font-size: 15px; color: #ffffff; ">Submit
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
                                                            ${{ $topup->amount }}
                                                        </td>
                                                        
                                                        <td data-label="Method">
                                                            {{ $topup->method }}
                                                        </td>
                                                        <td data-label="Status">
                                                            @if ($topup->status == 0)
                                                                <span class="badge bg-warning">Pending</span>
                                                            @elseif ($topup->status == 1)
                                                                <span class="badge bg-success">Approved</span>
                                                            @endif
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
            var copyText = document.getElementById("usdttrc20");
            copyText.select();
            copyText.setSelectionRange(0, 99999);
            /*For mobile devices*/
            document.execCommand("copy");
            Notiflix.Notify.success(`Copied: ${copyText.value}`);
        }

        function copyFunction2() {
            var copyText = document.getElementById("usdterc20");
            copyText.select();
            copyText.setSelectionRange(0, 99999);
            /*For mobile devices*/
            document.execCommand("copy");
            Notiflix.Notify.success(`Copied: ${copyText.value}`);
        }

        function copyFunction3() {
            var copyText = document.getElementById("usdtbep20");
            copyText.select();
            copyText.setSelectionRange(0, 99999);
            /*For mobile devices*/
            document.execCommand("copy");
            Notiflix.Notify.success(`Copied: ${copyText.value}`);
        }

    </script>

    @endpush