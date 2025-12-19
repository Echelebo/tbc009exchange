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
                                            <div class="ms-4"><h5>Total count of Payouts</h5>
                                                <p>{{ $commissions->count() }}</p></div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="commission d-flex align-items-center justify-content-start">
                                            <div><i class="fa-duotone fa-sack-dollar"></i></div>
                                            <div class="ms-4"><h5>Balance</h5>
                                                <p>${{ number_format($users->balance, 2) }}</p></div>
                                        </div>
                                    </div>

                                </div>
                                <div class="row mt-5">
                            <form method="POST" action="{{ route('payout.fromSubmit') }}"  class="col-md-6 mt-4">
                                @csrf
                                <p class="mt-4">Choose a payout wallet</p>
                                @if($uniqueAddresses->count() > 0)
                                @foreach($uniqueAddresses as $address)
                                        <label class="flex items-center p-4 bg-white border rounded-lg hover:bg-gray-50 cursor-pointer transition
                                            {{ old('address') === $address ? 'ring-2 ring-blue-600 bg-blue-50 border-blue-600' : '' }}">

                                            <input type="radio" name="address" value="{{ $address }}" class="w-5 h-5 text-blue-600 focus:ring-blue-500" {{ old('address') === $address ? 'checked' : '' }} required>

                                            <span class="ml-4 font-mono text-lg break-all">
                                                {{ $address }}
                                            </span>

                                            <!-- Optional: Show shortened version -->
                                            <span class="ml-4 text-sm text-gray-500">
                                                ({{ Str::substr($address, 0, 8) }}...{{ Str::substr($address, -6) }})
                                            </span>
                                        </label>
                                    @endforeach
                                @else
                                    <p class="text-gray-500 text-center py-8">No wallet addresses found.</p>
                                @endif

                                @error('address')
                                    <div class="text-red-600 text-sm mt-2">{{ $message }}</div>
                                @enderror

                                <label id="method" class="mt-4">Payout Method</label>
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
                                                              ${{ $payout->amount }}
                                                        </td>

                                                        <td data-label="Method">
                                                            {{ $payout->method }}
                                                        </td>
                                                        <td data-label="Status">
                                                            @if ($payout->status == 0)
                                                                <span class="badge bg-warning">Pending</span>
                                                            @elseif ($payout->status == 1)
                                                                <span class="badge bg-success">Approved</span>
                                                            @endif
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
