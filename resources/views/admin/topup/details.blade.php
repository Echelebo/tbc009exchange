@extends('admin.layouts.app')
@section('page_title',__('Top Up Details'))
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
                                                           href="javascript:void(0);">@lang('Top Up')</a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">@yield('page_title')</li>
                        </ol>
                    </nav>
                    <h1 class="page-header-title">@yield('page_title')</h1>
                </div>
            </div>
        </div>
        @if($topup->status == 0)
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
                                <h4 class="card-title mt-2">@lang("Top Up Information's")</h4>
                                <div>
                                    @if ($topup->status == 0)
                                        <span class="legend-indicator bg-warning"></span>@lang("Pending")
                                    @elseif ($topup->status == 1)
                                        <span class="legend-indicator bg-success"></span>@lang("Completed")
                                    @elseif ($topup->status == 2)
                                        <span class="legend-indicator bg-danger"></span>@lang("Cancelled")
                                    @endif
                                </div>
                            </div>
                            <div class="card-body mt-2">
                                <div class="col-sm">
                                    <!-- List Checked -->
                                    <ul class="list-checked list-checked-lg list-checked-soft-bg-primary">
                                        <li class="list-checked-item">@lang('Trx ID') : <strong
                                                class="text-dark font-weight-bold">{{$topup->utr}}</strong></li>

                                        <li class="list-checked-item">@lang('Requester') : <a
                                                href="{{$topup->user_id ? route('admin.user.edit',$topup->user_id) : 'javascript:void(0)'}}">{{optional($topup->user)->fullname??'Anonymous'}}</a>
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
                                <span>{{dateTime($topup->created_at,basicControl()->date_time_format)}}</span>
                            </div>
                            <div class="card-body mt-2">
                                <div class="col-sm">
                                    <ul class="list-checked list-checked-lg list-checked-soft-bg-secondary">
                                        <li class="list-checked-item">@lang('Currency') : <strong
                                                class="text-dark font-weight-bold">USDT</strong>
                                        </li>
                                        <li class="list-checked-item">@lang('Amount') : <strong
                                                class="text-dark font-weight-bold">${{ number_format($topup->amount) }}</strong>
                                        </li>
                                        <li class="list-checked-item">@lang('Payable Amount') : <strong
                                                class="text-danger font-weight-bold">${{ number_format($topup->amount) }}</strong>
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
                                <h4 class="card-title mt-2">@lang("Payment Information's")</h4>
                            </div>
                            <div class="card-body mt-2">
                                <div class="col-sm">
                                    <ul class="list-checked list-checked-lg list-checked-soft-bg-warning">
                                        <li class="list-checked-item">@lang('Hash'):
                                            <strong
                                                class="text-dark font-weight-bold"
                                                id="topuphash">{{$topup->hash}}</strong>
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
                        @if(optional($topup->method)->code == 'coin_payment')
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
            let route = "{{route("admin.topupSend",$topup->utr)}}";
            $("#deleteModalHeader").text(`Complete Confirmation`);
            $("#deleteModalBody").text(`Do you wish to proceed with completing the top up?`);
            $(".deleteModalRoute").attr('action', route);
        });
        $(document).on("click", "#cancel", function () {
            let route = "{{route("admin.topupCancel",$topup->utr)}}";
            $("#deleteModalHeader").text(`Cancel Confirmation`);
            $("#deleteModalBody").text(`Do you wish to proceed with cancel the top up?`);
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
