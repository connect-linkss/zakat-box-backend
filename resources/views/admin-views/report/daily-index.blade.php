@extends('layouts.admin.app')

@section('title', translate('daily Report'))

@section('content')
    <div class="content container-fluid">
        <form method="GET" action="{{ route('admin.report.daily') }}">
            <div class="row">
                <div class="col-md-3">
                    <input
                        type="date"
                        name="date"
                        class="form-control"
                        value="{{ request()->get('date', now()->format('Y-m-d')) }}"
                        max="{{ now()->format('Y-m-d') }}"
                    />
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary">{{ translate('Change Month') }}</button>
                </div>
            </div>
        </form>

        <div class="table-responsive datatable-custom mt-4">
            <table class="table table-hover table-borderless table-thead-bordered table-nowrap table-align-middle card-table">
                <thead class="thead-light">
                    <tr>
                        <th style="width: 50%">{{ translate('Particulars') }}</th>
                        <th>{{ translate('Value') }}</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>{{ translate('Orders') }}</td>
                        <td style="font-weight: bolder; color: #cead0c;">{{ $orderCount }}</td>
                    </tr>
                    <tr>
                        <td>{{ translate('No. of orders delivered') }}</td>
                        <td style="font-weight: bolder; color: #06218DFF;">{{ $deliveredOrdersCount }}</td>
                    </tr>
                    <tr>
                        <td>{{ translate('Total payment') }}</td>
                        <td style="font-weight: bolder; color: #3347E6FF;">{{ number_format($totalPayment, 2) }}$</td>
                    </tr>
                    <tr>
                        <td>{{ translate('Total expenses') }}</td>
                        <td style="font-weight: bolder; color: #ed4c78;">{{ number_format($totalExpenses, 2) }}$</td>
                    </tr>
                </tbody>
            </table>
        </div>



    </div>
    <div class="modal fade" id="print-invoice" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{translate('print_invoice')}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="d-flex align-items-center gap-2 justify-content-center">
                        <input type="button" class="btn btn-primary non-printable print-div-button"
                               data-name="printableArea"
                            value="Proceed, If thermal printer is ready."/>
                        <a href="{{ route('admin.order.list') }}" class="btn btn-danger non-printable">{{ translate('Back') }}</a>
                    </div>
                    <hr class="non-printable">
                    <div class="row" id="printableArea"></div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('script_2')
    <script>
        'use strict';
        $('.invoice-printing').on('click', function () {
            let date = $(this).data('date');
            print_invoice(date);
        });

        function print_invoice(date) {
            $.get({
                url: '{{ route('admin.generate-invoice') }}',
                data: { date: date },
                dataType: 'json',
                beforeSend: function () {
                    $('#loading').show();
                },
                success: function (data) {
                    $('#print-invoice').modal('show');
                    $('#printableArea').empty().html(data.view);
                },
                complete: function () {
                    $('#loading').hide();
                },
            });
        }

        $('.print-div-button').on('click', function () {
            let name = $(this).data('name');
            printDiv(name);
        });

        function printDiv(divName) {
            let printContents = document.getElementById(divName).innerHTML;
            document.body.innerHTML = printContents;
            window.print();
            location.reload();
        }

    </script>
@endpush

