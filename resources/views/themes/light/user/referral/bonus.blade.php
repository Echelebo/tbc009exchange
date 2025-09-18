@extends($theme.'layouts.user')
@section('page_title',__('Ref. Bonus List'))
@section('content')
<div class="section dashboard">
        <div class="row">
            <div class="col-12">
                
                                    <div class="user-wrapper">
                        <div class="user-table">
                            <div class="card">
                                <div class="card-body">
                                    <div class="cmn-table skltbs-panel">
                                        <div class="table-responsive">
                                            
                                            <table class="table align-middle">
                                                <thead>
                                                <tr>
                                                    <th scope="col">Transaction ID</th>
                                                    <th scope="col">User</th>
                                                    <th scope="col">Amount($)</th>
                                                    <th scope="col">Time</th>
                                                </tr>
                                                </thead>
                                               
                                                
                                                    <tbody class="block-statistics">
                                                        @if(count($referrals) > 0)
                                                        @foreach ( $referrals as $referral)
                                                        <tr id="user-{{ $referral->id}}" data-level="0" data-loaded="false">
                                                        <td data-label="Transaction ID">
                                                            <a href="javascript:void(0)" class="" data-id="{{ $referral->id }}">
                                                              {{ $referral->trx_id }}
                                                            </a>
                                                        </td>

                                                        <td data-label="Remarks">
                                                            {{ $referral->remarks }}
                                                        </td>

                                                        <td data-label="Amount">
                                                            <h6 class="text-success">{{ $referral->amount }}$
                                                                | <sup
                                                    class="baseColor">{{number_format($referral->transaction_amount, 2)}} USD</sup>
                                                            </h6>
                                                        </td>
                                                        
                                                        <td data-label="Time">
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