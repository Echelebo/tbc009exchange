@extends($theme.'layouts.user')
@section('page_title',__('Wallets'))
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
                                        <div class="ms-4">
                                            <h5>Wallets</h5>
                                            <p>Available Stake: ${{ number_format($commission, 2) }}</p>
                                            <p>Ref. Bonus: ${{ number_format($commission, 2) }}</p>
                                            <p>Availabvle Return: ${{ number_format($commission, 2) }}</p>
                                        </div>
                                    </div>
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