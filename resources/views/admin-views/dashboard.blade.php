@extends('layouts.admin.app')

@section('title', translate('Dashboard'))

@push('css_or_js')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')
    <div class="content container-fluid">
        <div class="card" style="text-align: center">
            <div class="px-20 py-3 d-flex flex-wrap gap-3 justify-content-between">
                <h5 class="d-flex align-items-center gap-2 mb-0">
                    {{translate('donates_List')}}
                </h5>
                <div>
                    <input type="text" id="search" class="form-control" placeholder="{{ translate('بحث') }}">
                </div>
                <div>
                    <select id="statusFilter" class="form-control">
                        <option value="0">{{ translate('جميع الحالات') }}</option>
                        <option value="1">{{ translate('مكتمل') }}</option>
                        <option value="2">{{ translate('ينتظر') }}</option>
                    </select>
                </div>
                <div class="billing">
                    <div>
                        <span class="title">{{ translate('دولار') }}: </span>
                        <span class="value bold" id="total_sum_dollar">{{$dollar_some }} $</span>
                    </div>
                    <div>|</div>
                    <div>
                        <span class="title">{{ translate('لبناني') }}: </span>
                        <span class="value bold" id="total_sum_ll">{{ $ll_some }} LL</span>
                    </div>
                </div>
            </div>
            <div class="table-responsive mt-3 datatable-custom">
                <table class="table table-hover">
                    <thead class="thead-light">
                        <tr>
                            <th>{{translate('الرقم')}}</th>
                            <th>{{translate('الاسم الثلاثي')}}</th>
                            <th>{{translate('معلومات')}}</th>
                            <th>{{translate('المبلغ')}}</th>
                            <th>{{translate('طريقة الدفع')}}</th>
                            <th>{{translate('status')}}</th>
                        </tr>
                    </thead>
                    <tbody id="set-rows">
                        @foreach($donates as $donate)
                            <tr>
                                <td>{{ $donate['id'] }}</td>
                                <td>{{ $donate['name'] }}</td>
                                <td>
                                    <p>{{ $donate['address'] }}</p>
                                    <p>{{ $donate['phone'] }}</p>
                                </td>
                                <td>{{ $donate['amount'] }} {{ $donate['payment_currency'] == 1 ? '$' : 'LL' }}</td>
                                <td>{{ $donate['payment_type'] == 1 ? translate('نقدا') : translate('مراجعة لاحقا')}}</td>
                                <td>
                                    @if($donate['status'] == 1)
                                        <label class="switcher">
                                            <input type="checkbox" class="switcher_input change-status" checked
                                                data-route="{{route('admin.donate.status', [$donate['id'], 2])}}">
                                            <span class="switcher_control"></span>
                                        </label>
                                    @else
                                        <label class="switcher">
                                            <input type="checkbox" class="switcher_input change-status"
                                                data-route="{{route('admin.donate.status', [$donate['id'], 1])}}">
                                            <span class="switcher_control"></span>
                                        </label>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @if($donates->count() == 0)
                <div class="text-center p-4">
                    <img class="mb-3 width-7rem" src="{{asset('public/assets/admin/svg/illustrations/sorry.svg')}}"
                        alt="{{ translate('image') }}">
                    <p class="mb-0">{{ translate('No data to show') }}</p>
                </div>
            @endif
        </div>
    </div>
@endsection

@push('script_2')
    <script>
        function fetchData() {
            let search = $('#search').val();
            let status = $('#statusFilter').val();

            $.ajax({
                url: "{{ route('admin.donate.filter') }}",
                method: "GET",
                data: { search: search, status: status },
                success: function (response) {
                    $('#set-rows').html(response.customersHTML);
                    $('#total_sum_dollar').text(response.dollar_some + ' $');
                    $('#total_sum_ll').text(response.ll_some + ' LL');
                },
                error: function () {
                    console.error('Error fetching data');
                }
            });
        }

        $(document).ready(function () {
            $('#search, #statusFilter').on('input change', fetchData);
            fetchData();
        });
    </script>
@endpush