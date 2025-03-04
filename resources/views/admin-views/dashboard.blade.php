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
                <div class="billing">
                    <div>
                        <span class="title">{{ translate('دولار') }}: </span>
                        <span class="value bold" id="total_sum_dollar">
                            {{$dollar_some }} $
                        </span>
                    </div>
                    <div>|</div>
                    <div>
                        <span class="title">{{ translate('لبناني') }}: </span>
                        <span class="value bold" id="total_sum_ll">
                            {{ $ll_some }} LL
                        </span>
                    </div>
                </div>
            </div>
            <div class="table-responsive mt-3 datatable-custom"
                style="max-height: 600px; overflow-y: auto; scrollbar-width: thin; scrollbar-color: #c4c4c4 #f1f1f1;">
                <table
                    class="table table-hover table-borderless table-thead-bordered table-nowrap table-align-middle card-table">
                    <thead class="thead-light">
                        <tr>
                            <th>{{translate('الرقم')}}</th>

                            <th>{{translate('الاسم الثلاثي')}}</th>
                            <th>{{translate('معلومات')}}</th>
                            <th>{{translate('المبلغ')}}</th>
                            <th>{{translate('طريقة الدفع')}}</th>
                            {{-- <th>{{translate('whatsaspp')}}</th> --}}
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
                                <td>{{ $donate['amount']  }} {{ $donate['payment_currency'] == 1 ? '$' : 'LL'  }}</td>
                                <td>{{ $donate['payment_type'] == 1 ? translate('نقدا') : translate('مراجعة لاحقا')}}</td>
                                {{-- <td>{{ $donate['whatsaspp'] == 1 ? 'no' : 'yes' }}</td> --}}
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
        let lastId = {{ $last_id }};
        function fetchData() {
            $.ajax({
                url: "{{ route('admin.donate.filter') }}",
                method: "GET",
                data: { last_id: lastId },
                success: function (response) {
                    if (response.addNew) {
                        $('#set-rows').prepend(response.customersHTML);
                        lastId = response.last_id;
                        $('#total_sum_dollar').text(response.dollar_some + ' $');
                        $('#total_sum_ll').text(response.ll_some + ' LL');
                    }
                },
                error: function () {
                    console.error('Error fetching data');
                }
            });
            setTimeout(fetchData, 2000);
        }
        document.addEventListener('DOMContentLoaded', fetchData);
    </script>
@endpush