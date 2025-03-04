@foreach($donates as $key => $donate)
    <tr>
        <td>{{ $donate['id'] }}</td>
        <td>{{ $donate['name'] }}</td>
        <td>{{ $donate['address'] }}</td>
        <td>{{number_format((int) $donate['amount'], 0, '', ',')  }}
            {{ $donate['payment_currency'] == 1 ? '$' : 'LL'  }}
        </td>
    </tr>
@endforeach

