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
                                                    <th scope="col">Trx ID</th>
                                                    <th scope="col">Amount ($)</th>
                                                    <th scope="col">Remarks</th>
                                                    <th scope="col">Time</th>
                                                </tr>
                                                </thead>
                                               
                                                
                                                    <tbody class="block-statistics">
                                                        @if(count($referrals) > 0)
                                                        @foreach ( $referrals as $referral)
                                                        <tr>
                                                        <td data-label="Trx ID">
                                                            
                                                              {{ $referral->trx_id }}
                                                            
                                                        </td>

                                                        <td data-label="Amount">
                                                            <h6 class="text-success">{{ $referral->amount }}$
                                                            </h6>
                                                        </td>

                                                        <td data-label="Remarks">
                                                            {{ $referral->remarks }}
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
    
   

    @endpush