@extends('layouts.admin.app')

@section('title', translate('monthlyearning Report'))

@section('content')
    <div class="content container-fluid">
        <div class="d-flex align-items-center justify-content-between">
            <form method="GET" action="{{ route('admin.report.sales') }}">
                <div class="d-flex gap-2">
                    <div class="form-group">
                        <label class="input-label">Start Date<span class="text-danger">*</span></label>
                        <input type="date" id="start_date" name="from" class="form-control"
                            value="{{ request()->get('from', now()->format('Y-m-d')) }}"
                            max="{{ now()->addDay()->format('Y-m-d') }}" onchange="adjustEndDate()" />
                    </div>
                    <div class="form-group">
                        <label class="input-label">End Date<span class="text-danger">*</span></label>
                        <input type="date" id="end_date" name="to" class="form-control"
                            value="{{ request()->get('to', now()->addDay()->format('Y-m-d')) }}"
                            max="{{ now()->addDay()->format('Y-m-d') }}" />
                    </div>

                    <div class="col-lg-4">
                        <label class="input-label">{{translate('branch')}}</label>
                        <select name="branch_id" id="branch_id_select" class="form-control branch_id_select ">
                            <option value="">all</option>
                            <option value="1" {{$branch_id == 1 ? 'selected' : ''}}>Jbeil</option>
                            <option value="2" {{$branch_id == 2 ? 'selected' : ''}}>Jounieh</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="input-label" style="visibility: hidden;">test</label>
                        <button type="submit" class="btn btn-primary">Filter</button>
                    </div>
                </div>
            </form>

            <div class="d-flex align-items-center gap-5 sales-data">
                <div class="form-group d-flex flex-column align-items-center">
                    <label class="input-label">Total Orders</label>
                    <span style="color: #cead0c">{{ $totalOrders }}</span>
                </div>
                <div class="form-group d-flex flex-column align-items-center">
                    <label class="input-label">quantitue of sejade</label>
                    <span style="color: #cead0c">{{ $totalSejadeQuantitySum }}</span>
                </div>
                <div class="form-group d-flex flex-column align-items-center">
                    <label class="input-label">Total Sales</label>
                    <span style="color: #3347E6FF">{{ number_format($totalSales, 2) }}$</span>
                </div>
                <div class="form-group d-flex flex-column align-items-center">
                    <label class="input-label">Total Paid</label>
                    <span style="color: #3347E6FF">{{ number_format($totalPaid, 2) }}$</span>
                </div>
                <div class="form-group d-flex flex-column align-items-center">
                    <label class="input-label">Total Due</label>
                    <span style="color: #ed4c78">{{ number_format($totalDue, 2) }}$</span>
                </div>
            </div>

            <!-- <button class="btn btn-info btn-sm invoice-printing" target="_blank" type="button" >
                                    <i class="tio-print"></i> Printing Orders
                                </button> -->

        </div>


        <div class="table-responsive datatable-custom monthly">
            <table
                class="table table-hover table-borderless table-thead-bordered table-nowrap table-align-middle card-table">
                <thead class="thead-light">
                    <tr>
                        <th>{{ translate('Date ') }}</th>
                        <th>{{ translate('order #') }}</th>
                        <th>{{ translate('Customer')}}</th>
                        <th>{{ translate('Order total') }}</th>
                        <th>{{ translate('paid amount') }}</th>
                        <th>{{ translate('due amount') }}</th>
                    </tr>
                </thead>
                <tbody id="set-rows">
                    @php
                        $totalPaid = 0;
                        $total = 0;
                        $orderCount = 0;
                    @endphp

                    @foreach($orders as $order)
                                    @php
                                        $dueAmount = $order->total - $order->paid;
                                        $totalPaid += $order->paid;
                                        $total += $order->total;
                                        $orderCount++;
                                    @endphp
                                    <tr>
                                        <td>{{ $order->created_at->format('Y-m-d') }}</td>
                                        <td>{{ $order->id }}</td>
                                        <td>{{ $order->user->name ?? 'N/A' }}</td>
                                        <td>{{ number_format($order->total, 2) }}$</td>
                                        <td>{{ number_format($order->paid, 2) }}$</td>
                                        <td>{{ number_format($dueAmount, 2) }}$</td>
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
                            data-name="printableArea" value="Proceed, If thermal printer is ready." />
                        <a href="{{ route('admin.order.list') }}"
                            class="btn btn-danger non-printable">{{ translate('Back') }}</a>
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
        function adjustEndDate() {
            const startDateInput = document.getElementById('start_date');
            const endDateInput = document.getElementById('end_date');

            const startDate = new Date(startDateInput.value);
            const tomorrow = new Date();
            tomorrow.setDate(tomorrow.getDate() + 1);

            // Set max and min constraints for End Date
            endDateInput.max = tomorrow.toISOString().split('T')[0]; // Tomorrow's date
            const minEndDate = new Date(startDate);
            minEndDate.setDate(startDate.getDate() + 1); // One day after Start Date
            endDateInput.min = minEndDate.toISOString().split('T')[0];

            // Adjust End Date if it's invalid
            const endDate = new Date(endDateInput.value);
            if (endDate < minEndDate || endDate > tomorrow) {
                endDateInput.value = minEndDate.toISOString().split('T')[0];
            }
        }

        // Initialize constraints on page load
        document.addEventListener('DOMContentLoaded', adjustEndDate);
    </script>
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