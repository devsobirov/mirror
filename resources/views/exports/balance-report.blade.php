<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
<table>
    <thead style="background-color: gold">
    <tr>
        <th colspan="{{$warehouse ? 4 : ($warehouses->count() + 3)}}" style="text-align: center">
            {{now()->format('d-m/Y H:i')}} holatiga ko'ra tovarlar balansi
        </th>
    </tr>
    <tr>
        <th>#</th>
        <th>Tovar</th>
        @if($warehouse)
            <th>Qoldiq - {{$warehouse->name}}</th>
        @else
            <th>Qoldiq (jami)</th>
            @foreach($warehouses as $ws)
                <th>Qoldiq - {{$ws->name}}</th>
            @endforeach
        @endif
    </tr>
    </thead>
    <tbody>
    @php $total = $report->count(); @endphp
    @foreach($report as $item)
        <tr>
            <td>{{$total}}</td>
            <td>{{$item->full_title}}</td>
            <td>{{$item->current_amount ?? 0}}</td>
            @if(!$warehouse)
                @foreach($warehouses as $w)
                    <td>{{$item->balanceStat->where('warehouse_id', $w->id)->first()?->amount ?? 0}}</td>
                @endforeach
            @endif
        </tr>
        @php $total = $total - 1; @endphp
    @endforeach
    </tbody>
</table>
</body>
</html>
