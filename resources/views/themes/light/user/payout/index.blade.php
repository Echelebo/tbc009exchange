@extends($theme.'layouts.user')
@section('page_title',__('Payout'))
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
                                            <div class="ms-4"><h5>Total Payout amount ($)</h5>
                                                <p>${{ number_format($commission, 2) }}</p></div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="commission d-flex align-items-center justify-content-start">
                                            <div><i class="fa-duotone fa-sack-dollar"></i></div>
                                            <div class="ms-4"><h5>Total count of Payouts.</h5>
                                                <p>${{ $commissions->count() }}</p></div>
                                        </div>
                                    </div>
                                    
                                </div>
                                <div class="row mt-5">
                            <form method="POST" action="{{route('payout.fromSubmit')}}"  class="mt-4 col-md-6">

                                <p class="mt-4">Choose a payout wallet</p>
                                <div class=" share_link d-flex align-items-center mt-4">
                                <label id="usdttrc20">USDT TRC-20 </label>
                                    <input type="text" class="input border-0 form-control" style="background-color: #2e403e; color: #ffffff; width: 50%;" id="usdttrc20" value="" readonly="">
                                </div>

                                <select name="method" class="form-control" style="width: 50%" required>
                                    <option value="">Select Payout method</option>
                                    <option value="usdttrc20">USDT TRC-20</option>
                                    <option value="usdterc20">USDT ERC-20</option>
                                    <option value="usdtbep20">USDT BEP-20</option>
                                </select>
                                
                                <label id="amount" class="mt-4">Amount ($)</label>
                                <input type="text" class="input form-control" style="color: #ffffff; width: 50%;" name="amount" id="amount" value="" required>

                                <button type="submit" class="search-btn2 mt-4 px-8" style="background-color:#c1923f; border-radius: 15px; padding: 10px; font-size: 15px; color: #ffffff; ">Submit
                                    </button>

                            </form>
                            </div>
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
                                                        @if(count($payouts) > 0)
                                                        @foreach ( $payouts as $payout)
                                                        <tr >
                                                        <td data-label="Amount">  
                                                              {{ $payout->amount }}
                                                        </td>
                                                        
                                                        <td data-label="Method">
                                                            {{ $payout->method }}
                                                        </td>
                                                        <td data-label="Status">
                                                            {{ $payout->status }}
                                                        </td>
                                                        <td data-label="Time">
                                                            {{ dateTime($payout->created_at,basicControl()->date_time_format) }}
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