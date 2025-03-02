@foreach($donates as $key => $donate)
    <tr>
        <td>{{ $donate['name'] }}</td>
        <td>{{ $donate['address'] }}</td>
        <td>{{ $donate['amount']  }} {{ $donate['payment_currency'] == 1 ? '$' : 'LL'  }}</td>
    </tr>
@endforeach

