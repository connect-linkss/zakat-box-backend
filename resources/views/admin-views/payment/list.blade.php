@extends('layouts.admin.app')

@section('title', translate('transactions List'))

@section('content')
    <div class="content container-fluid">
        <div class="mb-3" style="display: flex; width: 100%;justify-content: space-between">
            <h2 class="text-capitalize mb-0 d-flex align-items-center gap-2">
                <img width="20" src="{{asset('public/assets/admin/img/icons/customer.png')}}" alt="{{ translate('expense') }}">
                {{translate('transactions')}}
            </h2>
        </div>

        <div class="card">
            <div class="px-20 py-3 d-flex flex-wrap gap-3 justify-content-between">
                <h5 class="d-flex align-items-center gap-2 mb-0">
                    {{translate('transactions_List')}}
                    <span class="badge badge-soft-dark rounded-50 fz-12">{{ $customers->total() }}</span>
                </h5>
                <form action="{{url()->current()}}" method="GET">
                    <div class="input-group">
                        <input id="datatableSearch_" type="search" name="search"
                            class="form-control"
                            placeholder="{{translate('Search by Name')}}" aria-label="Search"
                            value="{{$search}}" required autocomplete="off">
                        <div class="input-group-append">
                            <button type="submit" class="btn btn-primary">{{translate('search')}}
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <div class="table-responsive datatable-custom">
                <table class="table table-hover table-borderless table-thead-bordered table-nowrap table-align-middle card-table">
                    <thead class="thead-light">
                        <tr>
                            <th>{{translate('SL')}}</th>
                            <th>{{translate('client')}}</th>
                            <th>{{translate('order details')}}</th>
                            <th>{{translate('price')}}</th>
                            <th>{{translate('date')}}</th>
                            <th  class="text-center">{{translate('actions')}}</th>
                        </tr>
                    </thead>

                    <tbody id="set-rows">
                    @foreach($customers as $key=>$customer)
                        <tr>
                            <td>
                                {{$customers->firstitem()+$key}}
                            </td>
                            <td>
                                <div class="media-body">{{ $customer->user != null ? $customer->user['name'] :  translate('walk_Customer')}}</div>
                            </td>

                            <td>
                                <div class="media-body">total:{{ $customer->order != null ? $customer->order['total'] .'$' :  translate('null')}}</div>
                                <div class="media-body">paid:{{ $customer->order != null ? $customer->order['paid'] .'$' :  translate('null')}}</div>
                                <div class="media-body">id:{{ $customer->order != null ? $customer->order['custom_id'] :  translate('null')}}</div>
                            </td>
                            <td>
                                <div>{{$customer['price']}} $</div>
                            </td>
                            <td>
                                <div>{{ \Carbon\Carbon::parse($customer['created_at'])->format('d-m-y H:i') }}</div>
                            </td>
                            <td >
                                <div class="d-flex justify-content-center gap-2">
                                    <button class="btn btn-info btn-sm invoice-printing" target="_blank" type="button"
                                            data-id="{{$customer['id']}}">
                                        <i class="tio-print"></i>
                                    </button>
                                    @if($customer['order_id'] !=null)
                                    <a class="btn btn-primary btn-sm " href="{{ route('admin.order.view', [$customer['order_id']]) }}">
                                        <i class="tio-visible"></i>
                                    </a>
                                    @endif
                                    <button class="btn btn-success btn-sm" onclick="fixModelChange2('{{$customer['id']}}', '{{$customer['amount']}}', '{{$customer['description']}}')" type="button" data-toggle="modal" data-target="#edit-customer" title="change password">
                                        <i class="tio-edit"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>

            <div class="table-responsive mt-4 px-3">
                <div class="d-flex justify-content-end">
                    {!! $customers->links() !!}
                </div>
            </div>
            @if(count($customers)==0)
                <div class="text-center p-4">
                    <img class="mb-3 width-7rem" src="{{asset('public/assets/admin//svg/illustrations/sorry.svg')}}" alt="{{ translate('image') }}">
                    <p class="mb-0">{{ translate('No data to show') }}</p>
                </div>
            @endif
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
@push('script_2')
    <script>
        "use strict"
    function fixModelChange(name) {
        document.getElementById('description_show').innerHTML = name;
    }

    function fixModelChange2(id,amount,desc) {
        document.getElementById('edit_id').value = id;
        document.getElementById('edit_amount').value = amount;
        document.getElementById('edit_description').value = desc;
    }

    $('.invoice-printing').on('click', function (){
        let orderId = $(this).data('id');
        print_invoice(orderId);
    })

    function print_invoice(order_id) {
        $.get({
            url: '{{route('admin.payment.invoice')}}/'+order_id,
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
