@extends($theme.'layouts.user')
@section('page_title',__('Referral Bonus'))
@section('content')
    <!-- main -->
    
    <div class="card mt-50">
        <div class="card-body">
            <div class="cmn-table">
                <div class="table-responsive overflow-visible">
                    <table class="table align-middle table-striped">
                        <thead>
                        <tr>
                            <th scope="col">SL</th>
                            <th scope="col">Amount</th>
                            <th scope="col">Remarks</th>
                            <th scope="col">Date</th>
                        </tr>
                        </thead>
                        <tbody>
                        @if(count($transactions) > 0)
                            @foreach($transactions as $key => $value)
                                <tr>
                                    <td data-label="@lang('Transaction ID')">{{ $value->trx_id }}</td>
                                    <td data-label="Amount"> {{ currencyPosition($value->amount) }}
                                    </td>
                                    <td data-label="@lang('Remarks')">{{ $value->remarks}}</td>
                                    <td data-label="@lang('Created time')"> {{ dateTime($value->created_at,basicControl()->date_time_format)}} </td>
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
    {{ $transactions->appends($_GET)->links($theme.'partials.user.pagination') }}
@endsection