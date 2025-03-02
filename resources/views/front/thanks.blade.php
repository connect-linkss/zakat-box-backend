@extends('layouts.front.app')

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
                            <img width="20" src="{{asset('public/assets/admin/img/icons/business_analytics.png')}}"
                                alt="{{ translate('Business Analytics') }}">
                            {{translate('Business_Analytics_for_this_year')}}
                        </h4>
                    </div>
                </div>
                <div class="row g-2" id="order_stats">
                    @include('admin-views.partials._dashboard-order-stats', ['data' => $data])
                </div>
            </div>
        </div>
        <div class="row g-2">
            <div class="col-lg-5">
                <div class="card h-100">
                    <div class="card-header">
                        <h4 class="d-flex align-items-center text-capitalize gap-10 mb-0">
                            <img width="20" src="{{asset('public/assets/admin/img/icons/business_overview.png')}}"
                                alt="{{ translate('business overview') }}">
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
                    'Bajaj ( {{$data['Bajaj']}} )',
                    'Atul( {{$data['Atul']}} )',
                    'Piaggio ( {{$data['Piaggio']}} )',
                    'TVS ( {{$data['TVS']}} )',
                ],
                datasets: [{
                    label: 'Business',
                    data: ['{{$data['Bajaj']}}', '{{$data['Atul']}}', '{{$data['Piaggio']}}', '{{$data['TVS']}}'],
                    backgroundColor: [
                        '#673ab7',
                        '#346751',
                        '#343A40',
                        '#7D5A50',
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
    </script>
@endpush