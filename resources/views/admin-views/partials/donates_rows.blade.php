@foreach($donates as $key => $donate)
    <tr>
        <td>{{ $donate['name'] }}</td>
        <td>
            <p>{{ $donate['address'] }}</p>
            <p>{{ $donate['phone'] }}</p>
        </td>
        <td>{{ $donate['amount']  }} {{ $donate['payment_currency'] == 1 ? '$' : 'LL'  }}</td>
        <td>{{ $donate['payment_type'] == 1 ? 'now' : 'later' }}</td>
        <td>{{ $donate['whatsaspp'] == 1 ? 'no' : 'yes' }}</td>
    </tr>
@endforeach

