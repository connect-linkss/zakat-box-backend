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
                        <span class="title">{{ translate('dollar') }}: </span>
                        <span class="value bold" id="total_sum_dollar">
                            {{ App\Models\Donate::getTodayTotalByType()['dollar'] }} $
                        </span>
                    </div>
                    <div>|</div>
                    <div>
                        <span class="title">{{ translate('ll') }}: </span>
                        <span class="value bold" id="total_sum_ll">
                            {{ App\Models\Donate::getTodayTotalByType()['lebanon'] }} $
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
                            <th>{{translate('name')}}</th>
                            <th>{{translate('address')}}</th>
                            <th>{{translate('amount')}}</th>
                        </tr>
                    </thead>
                    <tbody id="set-rows">
                        @foreach($donates as $donate)
                            <tr>
                                <td>{{ $donate['name'] }}</td>
                                <td>{{ $donate['address'] }}</td>
                                <td>{{ $donate['amount']  }} {{ $donate['payment_currency'] == 1 ? '$' : 'LL'  }}</td>
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
