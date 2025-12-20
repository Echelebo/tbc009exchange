@extends('admin.layouts.app')
@section('page_title',__('Payout Details'))
@section('content')
    <div class="content container-fluid">
        <div class="page-header">
            <div class="row align-items-end">
                <div class="col-sm mb-2 mb-sm-0">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb breadcrumb-no-gutter">
                            <li class="breadcrumb-item"><a class="breadcrumb-link"
                                                           href="javascript:void(0);">@lang('Dashboard')</a></li>
                            <li class="breadcrumb-item"><a class="breadcrumb-link"
                                                           href="javascript:void(0);">@lang('Payout')</a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">@yield('page_title')</li>
                        </ol>
                    </nav>
                    <h1 class="page-header-title">@yield('page_title')</h1>
                </div>
            </div>
        </div>
        @if($payout->status == 0)
            <div class="row mx-4">
                <div class="d-flex justify-content-end gap-2">
                    <button type="button" class="btn btn-soft-success" id="complete" data-bs-target="#confirmation"
                            data-bs-toggle="modal"><i class="fas fa-check"></i> @lang("Complete")
                    </button>
                    <button type="button" class="btn btn-soft-danger" id="cancel" data-bs-target="#confirmation"
                            data-bs-toggle="modal"><i class="fas fa-times"></i> @lang('Cancel')
                    </button>
                </div>
            </div>
        @endif
        <div class="content container-fluid">
            <div class="row justify-content-lg-center">
                <div class="col-lg-4">
                    <div class="d-grid gap-3 gap-lg-5">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between">
                                <h4 class="card-title mt-2">@lang("Payout Information's")</h4>
                                <div>
                                    @if ($payout->status == 0)
                                        <span class="legend-indicator bg-warning"></span>@lang("Pending")
                                    @elseif ($payout->status == 1)
                                        <span class="legend-indicator bg-success"></span>@lang("Completed")
                                    @elseif ($payout->status == 2)
                                        <span class="legend-indicator bg-danger"></span>@lang("Cancelled")
                                    @endif
                                </div>
                            </div>
                            <div class="card-body mt-2">
                                <div class="col-sm">
                                    <!-- List Checked -->
                                    <ul class="list-checked list-checked-lg list-checked-soft-bg-primary">
                                        <li class="list-checked-item">@lang('Trx ID') : <strong
                                                class="text-dark font-weight-bold">{{$payout->utr}}</strong></li>
                                        <li class="list-checked-item">@lang('Service Fees') : <strong
                                                class="text-dark font-weight-bold">{{rtrim(rtrim(getAmount($payout->service_fee,8),0),'.')}} {{optional($payout->currency)->code}}</strong>
                                        </li>
                                        <li class="list-checked-item">@lang('Network Fees') : <strong
                                                class="text-dark font-weight-bold">{{rtrim(rtrim(getAmount($payout->network_fee,8),0),'.')}} {{optional($payout->currency)->code}}</strong>
                                        </li>
                                        <li class="list-checked-item">@lang('Payment Method')
                                            : {{optional($payout->method)->name}}
                                            @if(optional($payout->method)->is_automatic)
                                                <span
                                                    class="badge bg-soft-success text-success">@lang("Automatic")</span>
                                            @else
                                                <span
                                                    class="badge bg-soft-secondary text-danger">@lang('manual')</span>
                                            @endif
                                        </li>
                                        <li class="list-checked-item">@lang('Requester') : <a
                                                href="{{$payout->user_id ? route('admin.user.edit',$payout->user_id) : 'javascript:void(0)'}}">{{optional($payout->user)->fullname??'Anonymous'}}</a>
                                        </li>
                                    </ul>
                                    <!-- End List Checked -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="d-grid gap-3 gap-lg-5">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between">
                                <h4 class="card-title mt-2">@lang("Currency Information's")</h4>
                                <span>{{dateTime($payout->created_at,basicControl()->date_time_format)}}</span>
                            </div>
                            <div class="card-body mt-2">
                                <div class="col-sm">
                                    <ul class="list-checked list-checked-lg list-checked-soft-bg-secondary">
                                        <li class="list-checked-item">@lang('Currency') : <strong
                                                class="text-dark font-weight-bold">{{optional($payout->currency)->currency_name}}</strong>
                                        </li>
                                        <li class="list-checked-item">@lang('Amount') : <strong
                                                class="text-dark font-weight-bold">{{rtrim(rtrim($payout->amount,0),'.')}} {{optional($payout->currency)->code}}</strong>
                                        </li>
                                        <li class="list-checked-item">@lang('Payable Amount') : <strong
                                                class="text-danger font-weight-bold">{{rtrim(rtrim(getAmount($payout->final_amount,8),0),'.')}} {{optional($payout->currency)->code}}</strong>
                                        </li>
                                    </ul>
                                </div>
                                <div class="alert alert-soft-secondary" role="alert">
                                    @lang("The service fee and network fee are already included in the displayed payable amount.")
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="d-grid gap-3 gap-lg-5">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between">
                                <h4 class="card-title mt-2">@lang("Address Information's")</h4>
                            </div>
                            <div class="card-body mt-2">
                                <div class="col-sm">
                                    <ul class="list-checked list-checked-lg list-checked-soft-bg-warning">
                                        <li class="list-checked-item">@lang('Destination address')
                                            ({{optional($payout->currency)->code}}) :
                                            <a href="javascript:void(0)"
                                               onclick="copyDestinationAddress()"
                                               data-bs-toggle="tooltip"
                                               data-bs-placement="top"
                                               title="@lang("copy to clipboard")"><i
                                                    class="fas fa-copy"></i></a><strong
                                                class="text-dark font-weight-bold"
                                                id="destinationId">{{$payout->wallet}}</strong>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="confirmation" data-bs-backdrop="static" tabindex="-1" role="dialog"
         aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalHeader">@lang('Confirmation!')</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p id="deleteModalBody">@lang('Are you certain you want to proceed with the action?')</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-white" data-bs-dismiss="modal">@lang('Close')</button>
                    <form action="" method="post" class="deleteModalRoute">
                        @csrf
                        @if(optional($payout->method)->code == 'coin_payment')
                            <button type="submit" name="btnValue" class="btn btn-soft-primary"
                                    value="automatic">@lang('Complete Automatic')</button>
                        @endif
                        <button type="submit" name="btnValue" class="btn btn-soft-success"
                                value="manual">@lang('Yes')</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('css-lib')
@endpush
@push('js-lib')
@endpush
@push('script')
    <script>
        'use strict';
        $(document).on("click", "#complete", function () {
            let route = "{{route("admin.payoutComplete",$payout->utr)}}";
            $("#deleteModalHeader").text(`Complete Confirmation`);
            $("#deleteModalBody").text(`Do you wish to proceed with completing the payout?`);
            $(".deleteModalRoute").attr('action', route);
        });
        $(document).on("click", "#cancel", function () {
            let route = "{{route("admin.payoutCancel",$payout->utr)}}";
            $("#deleteModalHeader").text(`Cancel Confirmation`);
            $("#deleteModalBody").text(`Do you wish to proceed with cancel the payout?`);
            $(".deleteModalRoute").attr('action', route);
        });
        function copyDestinationAddress() {
            var textToCopy = document.getElementById('destinationId').innerText;
            copyExe(textToCopy);
        }
        function copyExe(textToCopy) {
            var tempTextArea = document.createElement('textarea');
            tempTextArea.value = textToCopy;
            document.body.appendChild(tempTextArea);
            tempTextArea.select();
            document.execCommand('copy');
            document.body.removeChild(tempTextArea);
            Notiflix.Notify.success('Text copied to clipboard: ' + textToCopy);
        }
    </script>
@endpush
