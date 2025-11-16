@extends('admin.layouts.app')
@section('page_title',__('Top Ups'))
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
                                                           href="javascript:void(0);">@lang('Top Ups')</a></li>
                            <li class="breadcrumb-item active" aria-current="page">@lang('Top Ups')</li>
                        </ol>
                    </nav>
                    <h1 class="page-header-title">@lang('Top Up List')</h1>
                </div>
            </div>
        </div>

        <div class="row">
            

            

        <!-- Card -->
        <div class="card">

            <div class=" table-responsive datatable-custom  ">
                <table id="datatable"
                       class="js-datatable table table-borderless table-thead-bordered table-nowrap table-align-middle card-table"
                       data-hs-datatables-options='{
                       "columnDefs": [{
                          "targets": [0, 5],
                          "orderable": false
                        }],
                       "order": [],
                       "info": {
                         "totalQty": "#datatableWithPaginationInfoTotalQty"
                       },
                       "search": "#datatableSearch",
                       "entries": "#datatableEntries",
                       "pageLength": 15,
                       "isResponsive": false,
                       "isShowPaging": false,
                       "pagination": "datatablePagination"
                     }'>
                    <thead class="thead-light">
                    <tr>
                       <th>@lang('No.')</th>
                        <th class="table-column-ps-0">@lang('Full Name')</th>
                        <th>@lang('Amount')</th>
                        <th>@lang('Status')</th>
                        <th>@lang('Action')</th>
                    </tr>
                    </thead>

                    <tbody>

                    </tbody>
                </table>
            </div>


            <div class="card-footer">
                <div class="row justify-content-center justify-content-sm-between align-items-sm-center">
                    <div class="col-sm mb-2 mb-sm-0">
                        <div class="d-flex justify-content-center justify-content-sm-start align-items-center">
                            <span class="me-2">@lang('Showing:')</span>
                            <div class="tom-select-custom">
                                <select id="datatableEntries"
                                        class="js-select form-select form-select-borderless w-auto" autocomplete="off"
                                        data-hs-tom-select-options='{
                                            "searchInDropdown": false,
                                            "hideSearch": true
                                          }'>
                                    <option value="10">10</option>
                                    <option value="15" selected>15</option>
                                    <option value="20">20</option>
                                </select>
                            </div>
                            <span class="text-secondary me-2">of</span>
                            <span id="datatableWithPaginationInfoTotalQty"></span>
                        </div>
                    </div>
                    <div class="col-sm-auto">
                        <div class="d-flex  justify-content-center justify-content-sm-end">
                            <nav id="datatablePagination" aria-label="Activity pagination"></nav>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection


@push('css-lib')
    <link rel="stylesheet" href="{{ asset('assets/admin/css/tom-select.bootstrap5.css') }}">
@endpush


@push('js-lib')
    <script src="{{ asset('assets/admin/js/hs-file-attach.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/tom-select.complete.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/select.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/appear.min.js') }}"></script>
    <script src="{{ asset("assets/admin/js/hs-counter.min.js") }}"></script>
@endpush


@push('script')
    <script>
        $(document).on('ready', function () {
            new HSCounter('.js-counter')
            new HSFileAttach('.js-file-attach')
            HSCore.components.HSTomSelect.init('.js-select', {
                maxOptions: 250,
            })

            HSCore.components.HSDatatables.init($('#datatable'), {
                processing: true,
                serverSide: true,

                ajax: {
                    url: "{{ route("admin.topups.search") }}",

                },
                columns: [
                    {data: 'no', name: 'no'},
                    {data: 'name', name: 'name'},
                    {data: 'amount', name: 'amount'},
                    {data: 'status', name: 'status'},
                    {data: 'action', name: 'action'},
                ],
                select: {
                    style: 'multi',
                    selector: 'td:first-child input[type="checkbox"]',
                    classMap: {
                        checkAll: '#datatableCheckAll',
                        counter: '#datatableCounter',
                        counterInfo: '#datatableCounterInfo'
                    }
                },
                language: {
                    zeroRecords: `<div class="text-center p-4">
                    <img class="dataTables-image mb-3" src="{{ asset('assets/admin/img/oc-error.svg') }}" alt="Image Description" data-hs-theme-appearance="default">
                    <img class="dataTables-image mb-3" src="{{ asset('assets/admin/img/oc-error-light.svg') }}" alt="Image Description" data-hs-theme-appearance="dark">
                    <p class="mb-0">No data to show</p>
                    </div>`,
                    processing: `<div><div></div><div></div><div></div><div></div></div>`
                },
            })


            $(document).on('click', '#filter_button', function () {
                let filterSelectedStatus = $('#select_status').val();
                let filterName = $('#username_filter_input').val();
                let filterSelectedLocation = $('#location_filter_select option:selected').val();

                let emailVerifiedFilter = $('#emailFilterCheckAll').is(':checked');
                let SMSVerifiedFilter = $('#SMSFilterCheckAll').is(':checked');
                let TwoFaFilter = $('#TwoFaFilterCheckAll').is(':checked');

                emailVerifiedFilter = emailVerifiedFilter ? 1 : '';
                SMSVerifiedFilter = SMSVerifiedFilter ? 1 : '';
                TwoFaFilter = TwoFaFilter ? 1 : '';


                const datatable = HSCore.components.HSDatatables.getItem(0);

                datatable.ajax.url("{{ route('admin.users.search') }}" + "?filterStatus=" + filterSelectedStatus +
                    "&filterName=" + filterName + "&filterLocation=" + filterSelectedLocation + "&filterEmailVerification=" +
                    emailVerifiedFilter + "&filterSMSVerification=" + SMSVerifiedFilter + "&filterTwoFaVerification=" + TwoFaFilter).load();
            });

            $.fn.dataTable.ext.errMode = 'throw';

            $(document).on('click', '.loginAccount', function () {
                let route = $(this).data('route');
                $('.loginAccountAction').attr('action', route)
            });

            $(document).on('click', '#datatableCheckAll', function () {
                $('input:checkbox').not(this).prop('checked', this.checked);
            });

            $(document).on('change', ".row-tic", function () {
                let length = $(".row-tic").length;
                let checkedLength = $(".row-tic:checked").length;
                if (length == checkedLength) {
                    $('#check-all').prop('checked', true);
                } else {
                    $('#check-all').prop('checked', false);
                }
            });

            $(document).on('click', '.delete-multiple', function (e) {
                e.preventDefault();
                let all_value = [];
                $(".row-tic:checked").each(function () {
                    all_value.push($(this).attr('data-id'));
                });
                let strIds = all_value;
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: "{{ route('admin.user.delete.multiple') }}",
                    data: {strIds: strIds},
                    datatType: 'json',
                    type: "post",
                    success: function (data) {
                        location.reload();
                    },
                });
            });

            $(document).on('click', '.addBalance', function () {
                $('.setBalanceRoute').attr('action', $(this).data('route'));
                $('.user-contact-balance').text(new Intl.NumberFormat('en-US').format($(this).data('contacts')));
                $('.user-email-balance').text(new Intl.NumberFormat('en-US').format($(this).data('emails')));
            })


        });

    </script>

@endpush




