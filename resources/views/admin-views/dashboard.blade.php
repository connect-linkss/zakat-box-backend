@extends('layouts.admin.app')

@section('title', translate('Dashboard'))

@push('css_or_js')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')
    <div class="content container-fluid">
        <div class="card mb-3">
            <div class="card-body">
                <div class="row justify-content-between align-items-center g-2 mb-3">
                    <div class="col-auto">
                        <h4 class="d-flex align-items-center gap-10 mb-0">
                            <img width="20" src="{{asset('public/assets/admin/img/icons/business_analytics.png')}}" alt="{{ translate('Business Analytics') }}">
                            {{translate('Business_Analytics_for_this_year')}}
                        </h4>
                    </div>
                    <!-- <button class="btn btn-info btn-sm invoice-printing" target="_blank" type="button" >
                        <i class="tio-print"></i> Printing All Orders Today
                    </button> -->
                </div>
                <div class="row g-2" id="order_stats">
                    @include('admin-views.partials._dashboard-order-stats',['data'=>$data])
                </div>
            </div>
        </div>
        <div class="row g-2">
            <div class="col-lg-7">
                <div class="card h-100">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="d-flex align-items-center text-capitalize gap-10 mb-0">
                            <img width="20" src="{{ asset('public/assets/admin/img/icons/business_overview.png') }}" alt="{{ translate('business overview') }}">
                            {{ translate('this_week_order') }}
                        </h4>
                        <div>
                            <select id="status-filter" class="form-select form-control form-select-sm" aria-label="Filter by Status">
                                <option value="all">{{ translate('All Status') }}</option>
                                <option value="1">{{ translate('pending') }}</option>
                                <option value="2">{{ translate('processing') }}</option>
                                <option value="3">{{ translate('stored') }}</option>
                                <option value="4">{{ translate('finished') }}</option>
                                <option value="5">{{ translate('delivered') }}</option>
                                <option value="6">{{ translate('cancel') }}</option>
                            </select>
                        </div>
                    </div>
                    <div class="card-body" id="this_week_order" style="height: 20px; overflow-y: scroll;">
                        @foreach($week_order as $order)
                            <div class="order-item  justify-content-between align-items-center" data-status="{{ $order->status }}">
                                <div class="d-flex justify-content-between align-items-center">

                                    <div class="col-sm mb-2 mb-sm-0">
                                        <h2 class="font-weight-normal mb-1">#{{$order->custom_id}} <small
                                                class="font-size-sm text-body text-uppercase">{{translate('id')}}</small>
                                        </h2>
                                        <h5 class="text-hover-primary mb-1">{{translate('customer')}} {{translate('name')}}
                                            : {{ $order->user->name ?? 'Deleted' }}</h5>
                                        <h6 class="text-hover-primary mb-0">{{translate('order')}} {{translate('amount')}}
                                            : {{ $order->total }}</h6>
                                        <small
                                            class="text-body">{{date('d M Y',strtotime($order->created_at))}}</small>
                                    </div>

                                    <div style="display: flex; flex-direction: column; justify-content: space-between; align-items: end; height: 100px;">
                                        <a style="width: fit-content;" class="btn btn-primary btn-sm" href="{{ route('admin.order.view', [$order['id']]) }}">
                                            <i class="tio-visible"></i>
                                        </a>
                                        <div>
                                                {{translate('status')}}
                                                <strong> :
                                                    @if($order['status']==1)
                                                        pending
                                                    @elseif($order['status']==2)
                                                        processing
                                                    @elseif($order['status']==3)
                                                        finished
                                                    @elseif($order['status']==4)
                                                        stored
                                                    @elseif($order['status']==5)
                                                        delivered
                                                    @else
                                                        cancel
                                                    @endif
                                                <br></strong>
                                            </div>
                                    </div>


                                </div>

                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="col-lg-5">
                <div class="card h-100">
                    <div class="card-header">
                        <h4 class="d-flex align-items-center text-capitalize gap-10 mb-0">
                            <img width="20" src="{{asset('public/assets/admin/img/icons/business_overview.png')}}" alt="{{ translate('business overview') }}">
                            {{ translate('Total Business Overview') }}
                        </h4>
                    </div>
                    <div class="card-body" id="business-overview-board">
                        <div class="chartjs-custom position-relative h-400">
                            <canvas id="business-overview"></canvas>
                        </div>
                    </div>
                </div>
            </div>
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
                    <div class="row" id="printableArea">

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('script')
    <script src="{{asset('public/assets/admin/vendor/chart.js/dist/Chart.min.js')}}"></script>
@endpush
@push('script_2')
    <script>
        'use strict';
        let ctx = document.getElementById('business-overview');
        let myChart = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: [
                    'pending ( {{$data['pending']}} )',
                    'processing( {{$data['processing']}} )',
                    'finished ( {{$data['finished']}} )',
                    'stored ( {{$data['stored']}} )',
                    'delivered ( {{$data['delivered']}} )',
                    'cancel ( {{$data['cancel']}} )',
                ],
                datasets: [{
                    label: 'Business',
                    data: ['{{$data['pending']}}', '{{$data['processing']}}', '{{$data['finished']}}', '{{$data['stored']}}', '{{$data['delivered']}}', '{{$data['cancel']}}'],
                    backgroundColor: [
                        '#b8b8b8',
                        '#f5ca99',
                        '#000000FF',
                        '#00c9a7',
                        '#00c9db',
                        '#ed4c78',
                    ],
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                "legend": {
                    "display": true,
                    "position": "bottom",
                    "align": "center",
                    "labels": {
                        "fontColor": "#758590",
                        "fontSize": 14,
                        padding: 20
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    },
                }
            }
        });
        document.addEventListener('DOMContentLoaded', function() {
            const statusFilter = document.getElementById('status-filter');
            const orders = document.querySelectorAll('.order-item');
            function filterOrders() {
                console.log(9)
                const status = statusFilter.value;
                orders.forEach(order => {
                    const orderStatus = order.getAttribute('data-status');
                    const statusMatch = status === 'all' || orderStatus === status;
                    order.style.display =   statusMatch ? '' : 'none';
                });
            }
            statusFilter.addEventListener('change', filterOrders);
        });

        $('.invoice-printing').on('click', function (){
            // let orderId = $(this).data('id');
            print_invoice();
        })

        function print_invoice() {
            $.get({
                url: '{{route('admin.generate-invoice')}}/',
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

        $('.print-div-button').on('click', function (){
            let name = $(this).data('name');
            printDiv(name);
        })

        function printDiv(divName) {
            let printContents = document.getElementById(divName).innerHTML;
            document.body.innerHTML = printContents;
            window.print();
            location.reload();
        }
    </script>
@endpush
