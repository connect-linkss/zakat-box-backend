@extends('layouts.admin.app')
@section('title', translate('POS'))
@section('content')
    <div class="content container-fluid" style="padding-top: 5px">
        <div class="row gy-3 gx-2">
            <div class="col-lg-6">
                <div class="card overflow-hidden" style="height: 100%;">
                    <div class="pos-title"
                        style="display: flex;align-items: center;justify-content: space-between;padding: 8px;">
                        <div class="d-flex gap-3" style="width: 100%;">
                            <div class="input-group">
                                <input id="dataSearch_" type="text" name="search" class="form-control"
                                    placeholder="{{translate('Search by Name or code')}}" aria-label="Search" value=""
                                    required autocomplete="off">
                            </div>
                            <select id="brandFilter" class="form-control">
                                <option value="">{{translate('All Brands')}}</option>
                                <option value="1">bajaj</option>
                                <option value="2">atul</option>
                                <option value="3">piaggio</option>
                                <option value="4">tvs</option>
                                <option value="5">decore</option>
                            </select>
                        </div>
                    </div>
                    <div class="card-body" id="items">
                        <div id="pos-item-wrap" class="pos-item-wrap justify-content-center;"
                            style="scrollbar-width: thin;scrollbar-color: #dedede #f1f1f1;">
                            @foreach($products as $product)
                                @include('admin-views.pos._single_product', ['product' => $product])
                            @endforeach
                        </div>
                    </div>
                    @if(count($products) == 0)
                        <div class="text-center p-4">
                            <img class="mb-3 width-7rem" src="{{asset('public/assets/admin/svg/illustrations/sorry.svg')}}"
                                alt="{{ translate('image') }}">
                            <p class="mb-0">{{ translate('No data to show') }}</p>
                        </div>
                    @endif
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card billing-section-wrap" style="max-block-size:calc(100vh - 80px)">
                    <div class="pos-title " style="display: flex;justify-content: space-between;align-items: center;">
                        <h4 class="mb-0">{{translate('Billing_Section')}}</h4>
                    </div>
                    <div class="p-2 p-sm-4" style="padding-top: 7px !important;">
                        <div class="row pl-2">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <input type="text" name="customer_name" id="customer_name" class="form-control" value=""
                                        placeholder="{{ translate('customer') }}" required="">
                                </div>
                            </div>
                        </div>
                        <div id="cart">
                            <div class="table-responsive mt-3 border-primary-light pos-cart-table rounded">
                                <table class="table table-align-middle mb-0">
                                    <thead class="bg-primary-light text-dark">
                                        <tr>
                                            <th class="border-bottom-0">{{translate('item')}}</th>
                                            <th class="border-bottom-0">{{translate('qty')}}</th>
                                            <th class="border-bottom-0">{{translate('price')}}</th>
                                            <th class="border-bottom-0">{{translate('service')}}</th>
                                            <th class="border-bottom-0">{{translate('total')}}</th>
                                            <th class="border-bottom-0 text-center">{{translate('delete')}}</th>
                                        </tr>
                                    </thead>
                                    <tbody id="gold-content">
                                    </tbody>
                                </table>
                            </div>
                            <div class="box p-3">
                                <dl class="row mb-2">
                                    <dt class="col-6">{{translate('items_price')}} :</dt>
                                    <dd class="col-6 text-right" id="items_price_total">0</dd>
                                    <dt class="col-6">{{translate('service_price')}} :</dt>
                                    <dd class="col-6 text-right" id="service_price_total">0</dd>
                                    <dt class="col-9 font-weight-bold fs-16 border-top pt-2">{{translate('total')}} :</dt>
                                    <dd class="col-3 text-right font-weight-bold fs-16 border-top pt-2" id="total_amount">0
                                    </dd>
                                    <dt class="col-6 font-weight-bold fs-16">{{translate('lb_total')}} :</dt>
                                    <dd class="col-6 text-right" id="lebanon_price_total">0</dd>
                                </dl>
                                @csrf
                                <div class="row g-2">
                                    <div class="col-sm-6">
                                        <div class="btn btn-danger btn-block pos-empty-cart">
                                            <i class="fa fa-times-circle"></i> {{translate('Cancel_Order')}}
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <form action="javascript:" id='order_place' method="post">
                                            <button type="submit" class="btn  btn-primary btn-block"><i
                                                    class="fa fa-shopping-bag"></i>
                                                {{translate('Place_Order')}} </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <table style="display: none">
        <tbody id="content-to-copy2" style="display: none">
            <tr class="parent_item_list">
                <td>
                    <input type="hidden" name="product_id" class="product_id" value="">
                    <input type="hidden" name="price" class="product_price" value="0">
                    <input type="hidden" name="service_price" class="service_price" value="0">
                    <input type="hidden" name="stock" class="service_stock" value="0">
                    <h5 class="mb-0 item_name"></h5>
                    <small></small>
                </td>
                <td>
                    <input type="numeric" name="quantity_input" style="padding: 0" oninput="calculate()"
                        class="form-control quantity_input" value="1" min="1">
                </td>
                <td>
                    <div class="fs-15 item_price">0</div>
                </td>
                <td>
                    <input type="checkbox" name="is_service" class="is_service" onchange="calculate()">
                    <div class="fs-15 item_service">0</div>
                </td>
                <td>
                    <div class="fs-15 item_total">0</div>
                </td>
                <td class="text-center">
                    <div onclick="deleteRow(this)" class="delete_gold_buttom">
                        <i class="tio-delete-outlined"></i>
                    </div>
                </td>
            </tr>
        </tbody>
    </table>
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
                    <div class="row" id="printableArea">
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
@push('script_2')
    <script>
        "use strict";
        function handleProductSelection(product_id, price, service, stock, name, is_oil = 1) {
            console.log(is_oil)
            if (product_id && product_id > 0) {
                var existingRow = $('#gold-content').find(`[id='${product_id}']`);
                if (existingRow.length === 0) {
                    var content = document.getElementById('content-to-copy2').innerHTML;
                    document.getElementById('gold-content').insertAdjacentHTML('beforeend', content);
                    var lastRow = $('#gold-content > :last-child');
                    lastRow.attr('id', product_id);
                    lastRow.find('.product_id').val(product_id);
                    lastRow.find('.product_price').val(price);
                    lastRow.find('.service_price').val(service);
                    lastRow.find('.stock').val(stock);
                    lastRow.find('.item_name').text(name);
                    if (is_oil == 4) {
                        console.log('test')
                        lastRow.find('.is_service').prop('checked', true);
                    }
                } else if (existingRow.length > 0) {
                    let quantityInput = existingRow.find('.quantity_input');
                    let currentQuantity = parseInt(quantityInput.val()) || 0;
                    quantityInput.val(currentQuantity + 1);
                }
            }
            calculate();

        }
        $('.pos-single-product-card').on('click', function () {
            let product_id = $(this).data('id');
            let is_oil = $(this).data('oil');
            var price = $(this).data('price');
            var service = $(this).data('service');
            var stock = $(this).data('stock');
            var name = $(this).data('name');
            handleProductSelection(product_id, price, service, stock, name, is_oil)
        });
        function calculate() {
            var items_price = 0;
            var service_price = 0;
            var subtotal = 0;
            let items = document.querySelectorAll('#gold-content .parent_item_list');
            items.forEach(function (item) {
                var testPrice = parseFloat(item.querySelector('.product_price').value) || 0;
                var ServicePrice = parseFloat(item.querySelector('.service_price').value) || 0;
                var testquantity = parseFloat(item.querySelector('.quantity_input').value) || 1;
                let is_service = item.querySelector('.is_service').checked;
                var total = testPrice * testquantity;
                if (is_service) {
                    total += ServicePrice * testquantity;
                    service_price += ServicePrice * testquantity;
                }
                items_price += testPrice * testquantity;
                subtotal += total;
                item.querySelector('.item_price').innerHTML = testPrice.toFixed(2);
                item.querySelector('.item_service').innerHTML = ServicePrice.toFixed(2);
                item.querySelector('.item_total').innerHTML = total.toFixed(2);
            });
            $('#items_price_total').text(items_price.toFixed(2) + '$');
            $('#service_price_total').text(service_price.toFixed(2) + '$');
            $('#total_amount').text(subtotal.toFixed(2) + '$');
            let lb = subtotal * 90000;
            let formattedLb = lb.toLocaleString('en-US');
            $('#lebanon_price_total').text(formattedLb + 'LL');
        }
        function deleteRow(item) {
            var row = item.closest('.parent_item_list');
            if (row) {
                row.remove();
                calculate();
            }
        }

        document.getElementById('order_place').addEventListener('submit', function (e) {
            e.preventDefault();
            let formIsValid = true;
            let data = {
                customer_name: $('#customer_name').val(),
                items: []
            };
            document.querySelectorAll('#gold-content .parent_item_list').forEach(function (item) {
                let itemData = {
                    product_id: item.querySelector('.product_id').value,
                    quantity: parseInt(item.querySelector('.quantity_input').value),
                    is_service: item.querySelector('.is_service').checked ? 1 : 0
                };
                if (!itemData.product_id || itemData.quantity <= 0) {
                    toastr.error('{{ translate("Quantity or product ID is not valid.") }}', { CloseButton: true, ProgressBar: true });
                    formIsValid = false;
                    return;
                } else {
                    data.items.push(itemData);
                }
            });
            if (data.items.length === 0) {
                formIsValid = false;
            }
            if (formIsValid) {
                $.ajax({
                    url: '{{ route('admin.pos.store') }}',
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    data: JSON.stringify(data),
                    contentType: 'application/json',
                    processData: false,
                    success: function (response) {
                        if (response.errors) {
                            toastr.error('{{ translate("Error occurred while saving order.") }}', { CloseButton: true, ProgressBar: true });
                        } else {
                            toastr.success('{{ translate("Order saved successfully!") }}', { CloseButton: true, ProgressBar: true });
                            print_invoice(response.id);
                        }
                    },
                    error: function (xhr) {
                        let response = xhr.responseJSON;
                        toastr.error(response?.message || '{{ translate("An error occurred.") }}', { CloseButton: true, ProgressBar: true });
                    }
                });
            } else {
                toastr.error('{{ translate("Please fill all required fields.") }}', { CloseButton: true, ProgressBar: true });
            }
        });
        $('.print-div-button').on('click', function () {
            let name = $(this).data('name');
            printDiv(name);
        });
        $(".pos-empty-cart").on('click', function () {
            document.getElementById('gold-content').innerHTML = '';
            calculate()
        });
        function print_invoice(order_id) {
            $.get({
                url: '{{url('/')}}/admin/pos/invoice/' + order_id,
                dataType: 'json',
                beforeSend: function () {
                    $('#loading').show();
                },
                success: function (data) {
                    $('#print-invoice').modal('show');
                    $('#printableArea').empty().html(data.view);
                    document.getElementById('gold-content').innerHTML = '';
                    calculate()
                },
                complete: function () {
                    $('#loading').hide();
                },
            });
        }

        function printDiv(divName) {
            let printArea = document.getElementById('printableArea');
            let firstChild = printArea.firstElementChild;
            if (firstChild) {
                firstChild.style.transform = "scale(2.5)";
                firstChild.style.transformOrigin = "center";
                firstChild.style.margin = "0";
                firstChild.style.position = "absolute";
                firstChild.style.top = "50%";
                firstChild.style.left = "50%";
                // firstChild.style.paddingLeft = "5px";
                // firstChild.style.paddingRight = "5px";
                firstChild.style.transform = "translate(-50%, -50%) scale(3)";
            }
            let printContents = printArea.innerHTML;
            // let originalContents = document.body.innerHTML;
            document.body.innerHTML = printContents;
            window.print();
            // document.body.innerHTML = originalContents;
            location.reload();
        }
        document.addEventListener('DOMContentLoaded', function () {
            const rows = document.querySelectorAll('.pos-single-product-card');
            const searchInput = document.getElementById('dataSearch_');
            const brandFilter = document.getElementById('brandFilter');
            const filterRows = () => {
                var searchTerm = $('#dataSearch_').val();
                var brand = $('#brandFilter').val();
                $.ajax({
                    url: "{{ route('admin.pos.filter') }}",
                    method: "GET",
                    data: {
                        search: searchTerm,
                        brand: brand,
                    },
                    success: function (response) {
                        $('#pos-item-wrap').html(response.customersHTML);
                        $('.pos-single-product-card').on('click', function () {
                            let product_id = $(this).data('id');
                            let is_oil = $(this).data('oil');
                            var price = $(this).data('price');
                            var stock = $(this).data('stock');
                            var name = $(this).data('name');
                            var service = $(this).data('service');
                            handleProductSelection(product_id, price, service, stock, name, is_oil)
                        });
                    },
                    error: function () {
                        alert('Something went wrong. Please try again.');
                    }
                });
            };
            function debounce(func, delay) {
                let debounceTimer;
                return function () {
                    const context = this;
                    const args = arguments;
                    clearTimeout(debounceTimer);
                    debounceTimer = setTimeout(() => func.apply(context, args), delay);
                };
            }
            searchInput.addEventListener('input', debounce(filterRows, 300));
            brandFilter.addEventListener('change', filterRows);
        });
    </script>
    <script>
        if (/MSIE \d|Trident.*rv:/.test(navigator.userAgent)) document.write('<script src="{{asset('public/assets/admin')}}/vendor/babel-polyfill/polyfill.min.js"><\/script>');
    </script>
@endpush