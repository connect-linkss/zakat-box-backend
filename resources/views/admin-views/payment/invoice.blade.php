<div style="width:410px;" class="mx-auto">
    <div class="text-center pt-2 mb-3">
        <h2>{{ 'مغسل السجاد الآلي' }}</h2>
        <h5>
            زوق مصبح شارع مورية الريس
        </h5> 
        <p class="text-dark">
            {{'76/178888'}}
        </p>
    </div>
    <div class="invoice-border"></div>
    <div class="row pt-3 pb-1">
        <div class="col-6">
            <h5>{{translate('Customer ID')}} : {{$order['user_id']}}</h5>
        </div>
        <div class="col-6">
            <div class="text-right text-dark">
                {{date('d M Y h:i a',strtotime($order['created_at']))}}
            </div>
        </div>
    </div>
    <div class="row pb-2">
        <div class="col-6">
            <h5>{{translate('Customer')}} : {{$order['user']['name']}}</h5>
        </div>
        <div class="col-6">
            <div class="text-right text-dark">
            <h5>{{translate('Order ID')}} : {{$order['order']['custom_id']}}</h5>
            </div>
        </div>
    </div>
    <h5 class="this-paid">{{translate('Transaction Paid')}} : {{$order['price']}}</h5>

    <div class="invoice-border"></div>
    <table class="table table-bordered mt-3 text-dark">
        <thead>
            <tr>
                <th class="border-bottom-0">{{translate('Total')}}</th>
                <th class="border-bottom-0">{{translate('Total Paid')}}</th>
                <th class="border-bottom-0">{{translate('Due')}}</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>
                    {{$order['order']['total']}}
                </td>
                <td>
                    {{$order['order']['paid']}}
                </td>
                <td>
                    {{($order['order']['total'] - $order['order']['paid'] > 0) ? $order['order']['total'] - $order['order']['paid'] . ' $' : translate('full paid') }}
                </td>
            </tr>
        </tbody>
    </table>
    <div class="invoice-border"></div>
    <!-- <dl class="row text-dark mt-2">
        <dt class="col-6">{{translate('Items Price')}}:</dt>
        <dd class="col-6 text-right">0</dd>
        <dt class="col-6 font-weight-bold">{{translate('Total')}}:</dt>
        <dd class="col-6 text-right font-weight-bold">0</dd>
    </dl>
    <div class="invoice-border mt-5"></div>
    <h5 class="text-center mb-0 py-3">
        """{{translate('THANK YOU')}}"""
    </h5>
    <div class="invoice-border"></div> -->
</div>
