@foreach($donates as $key => $donate)
    <tr>
        <td>{{ $donate['id'] }}</td>
        <td>{{ $donate['name'] }}</td>
        <td>
            <p>{{ $donate['address'] }}</p>
            <p>{{ $donate['phone'] }}</p>
        </td>
        <td>{{ $donate['amount'] }} {{ $donate['payment_currency'] == 1 ? '$' : 'LL' }}</td>
        <td>{{ $donate['payment_type'] == 1 ? translate('نقدا') : translate('مراجعة لاحقا')}}</td>
        <td>
            @if($customer['status'] == 1)
                <label class="switcher">
                    <input type="checkbox" class="switcher_input change-status" checked
                        data-route="{{route('admin.donate.status', [$customer['id'], 2])}}">
                    <span class="switcher_control"></span>
                </label>
            @else
                <label class="switcher">
                    <input type="checkbox" class="switcher_input change-status"
                        data-route="{{route('admin.donate.status', [$customer['id'], 1])}}">
                    <span class="switcher_control"></span>
                </label>
            @endif
        </td>
    </tr>
@endforeach

