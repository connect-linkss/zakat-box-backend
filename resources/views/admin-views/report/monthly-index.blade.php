@extends('layouts.admin.app')

@section('title', translate('monthlyearning Report'))

@section('content')
    <div class="content container-fluid">
        <form method="GET" action="{{ route('admin.report.monthlyearning') }}">
            <div class="row">
                <div class="col-md-3">
                    <input type="month" name="month" class="form-control" value="{{ request()->get('month', now()->format('Y-m')) }}" />
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary">{{ translate('Change Month') }}</button>
                </div>
            </div>
        </form>


        <div class="table-responsive datatable-custom mt-4 monthly">
                <table class="table table-hover table-borderless table-thead-bordered table-nowrap table-align-middle card-table">
                    <thead class="thead-light">
                        <tr>
                            <th>{{ translate('Day ') }}</th>
                            <th>{{ translate('order count') }}</th>
                            <th>{{translate('No. of orders delivery')}}</th>
                            <th>{{ translate('Total') }}</th>
                            <th>{{ translate('paid') }}</th>
                            <th>{{ translate('due') }}</th>
                            <th>{{ translate('total expence') }}</th>
                            <!-- <th>{{ translate('print') }}</th> -->
                        </tr>
                    </thead>
                    <tbody id="set-rows">
                        @php
                            $totalpaid = 0;
                            $total = 0;
                            $order_count = 0;
                            $expense = 0;
                        @endphp
                        @foreach($monthlySummary as $summary)
                            @php
                                $totalpaid += $summary['paid'];
                                $total += $summary['total'];
                                $order_count += $summary['order_count'];
                                $expense += $summary['expense'];
                            @endphp
                        @endforeach
                        <tr class="monthly-total">
                            <td><strong>{{ translate('Total') }}</strong></td>
                            <td><strong>{{ number_format($order_count, 0) }}</strong></td>
                            <td><strong>{{ number_format($order_count, 0) }}</strong></td>
                            <td><strong>{{ number_format($total, 2) }} $</strong></td>
                            <td><strong>{{ number_format($totalpaid, 2) }} $</strong></td>
                            <td><strong>{{ number_format($total - $totalpaid, 2) }} $</strong></td>
                            <td><strong>{{ number_format($expense, 2) }} $</strong></td>
                            <!-- <td><strong>--</strong></td> -->
                        </tr>
                        @foreach($monthlySummary as $summary)
                            <tr>
                                <td>{{$summary['day']}}</td>
                                <td>{{ number_format($summary['order_count'], 0) }}</td>
                                <td>{{ number_format($summary['order_count'], 0) }}</td>
                                <td>{{ number_format($summary['total'], 2) }}$</td>
                                <td>{{ number_format($summary['paid'], 2) }}$</td>
                                <td>{{ number_format($summary['total'] - $summary['paid'], 2) }}$</td>
                                <td>{{ number_format($summary['expense'], 2) }}$</td>
                                <!-- <td>
                                    <button class="btn btn-info btn-sm invoice-printing" target="_blank" type="button" data-date="{{$summary['date']}}">
                                        <i class="tio-print"></i>
                                    </button>
                                </td> -->
                            </tr>
                        @endforeach
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

