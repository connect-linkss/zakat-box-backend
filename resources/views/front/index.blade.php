@extends('layouts.front.app')

@section('title', translate('product List'))
@section('content')
<div class="content container-fluid" style=" padding-top: 0px !important">
    <div class="d-flex justify-content-center mb-1">
        @php($logo = \app\CentralLogics\Helpers::get_business_settings('logo'))
        <img style="width: 180px;" src="{{'/public/images/' . $logo}}">
    </div>
    <div class="card" style="text-align: center">
        <div class="need_resize_padding  py-3 d-flex gap-3 justify-content-between ">
            <div class="d-flex align-items-center gap-4">
                <h5 class="d-flex align-items-center gap-2 mb-0 text_count_donate" style="font-size: 20px;
}">
                    {{translate('عدد المتبرعين')}}
                </h5>
                <h4 style="margin: auto;background: #084b66;padding: 5px;border-radius: 6px;color: white;"
                    id="total_scount_dollar">
                    {{$donateCount }}
                </h4>
            </div>


            <div class="billing">
                <div>
                    <span class="title">{{ translate('دولار') }}: </span>
                    <span class="value bold" id="total_sum_dollar">
                        {{$dollar_some }} $
                    </span>
                </div>
                <div class="billing_seperate">|</div>
                <div>
                    <span class="title">{{ translate('لبناني') }}: </span>
                    <span class="value bold" id="total_sum_ll">
                        {{ $ll_some }} LL
                    </span>
                </div>
            </div>
        </div>
        <div class="table-responsive datatable-custom"
            style="max-height: 600px; overflow-y: auto; scrollbar-width: thin; scrollbar-color: #c4c4c4 #f1f1f1;">
            <table class="table table-hover text-center table-bordered">
                <thead class="thead-light">
                    <tr>
                        <th>{{translate('الرقم')}}</th>
                        <th>{{translate('الاسم الثلاثي')}}</th>
                        <th>{{translate('العنوان')}}</th>
                        <th>{{translate('المبلغ')}}</th>
                    </tr>
                </thead>
                <tbody id="set-rows">
                    @foreach($donates as $donate)
                        <tr>
                            <td>{{ $donate['id'] }}</td>
                            <td>{{ $donate['name'] }}</td>
                            <td>{{ $donate['address'] }}</td>
                            <td>{{number_format((int) $donate['amount'], 0, '', ',')  }}
                                {{ $donate['payment_currency'] == 1 ? '$' : 'LL'  }}
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
<style>
    .highlight {
        background-color: #c8e6c9;
        /* Light green */
        transition: background-color 2s ease-in-out;
    }

    tr {
        transition: opacity 0.5s ease-in-out, transform 0.5s ease-in-out;
    }

    .need_resize_padding {
        padding-inline-start: 1rem !important;
        padding-inline-end: 1rem !important;
    }

    @media (max-width: 768px) {
        .table-responsive {
            overflow-x: auto;
        }

        .billing {
            flex-direction: column;
            font-size: 12px;
            gap: 5px !important;
        }

        .billing div {
            justify-content: center;
        }

        .billing_seperate {
            display: none
        }

        .table th,
        .table td {
            font-size: 12px;
            padding: 5px;
        }

        .text_count_donate {
            font-size: 12px;
            padding: 5px;
        }

        #total_scount_dollar,
        #total_sum_dollar,
        #total_sum_ll {
            font-size: 12px;
            padding: 5px;
        }

        .need_resize_padding {
            padding: 3px !important;
            padding-inline-start: 0.4rem !important;
            padding-inline-end: 0.4rem !important;
        }
    }
</style>
@push('script_2')
    <script>
        let lastId = {{ $last_id }};
        function fetchData() {
            $.ajax({
                url: "{{ route('data') }}",
                method: "GET",
                data: { last_id: lastId },
                success: function (response) {
                    if (response.addNew) {
                        let newRows = $(response.customersHTML);
                        newRows.hide().prependTo('#set-rows').slideDown(500).addClass('highlight');
                        lastId = response.last_id;
                        $('#total_sum_dollar').text(response.dollar_some + ' $');
                        $('#total_sum_ll').text(response.ll_some + ' LL');
                        $('#total_scount_dollar').text(response.donateCount);
                        setTimeout(() => newRows.removeClass('highlight'), 2000);
                    }
                },
                error: function () {
                    setTimeout(fetchData, 3500);
                },
                complete: function () {
                    setTimeout(fetchData, 3500);
                }
            });
        }
        document.addEventListener('DOMContentLoaded', function () {
            setTimeout(fetchData, 3500);
        });
    </script>
@endpush