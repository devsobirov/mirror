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
             <th>#</th>
             <th>Tovar</th>
             <th>Sana</th>
             <th>Sklad</th>
             <th>Miqdor (dona)</th>
             <th>Narx (dona - USD)</th>
             <th>Narx (summa - USD)</th>
             <th>Mijoz</th>
             <th>Tel</th>
             <th>Xudud</th>
             <th>Ma'sul xodim</th>
         </tr>
        </thead>
        <tbody>
        @php $total = $report->count(); @endphp
        @foreach($report as $item)
            <tr>
                <td>{{$total}}</td>
                <td>{{$item->good?->full_title ?? '-'}}</td>
                <td>{{$item->created_at?->format('Y-m-d H:i') ?? '-'}}</td>
                <td>{{$item->warehouse?->name ?? '-'}}</td>
                <td>{{$item->amount}}</td>
                <td>{{round($item->price_usd / $item->amount, 2)}}</td>
                <td>{{$item->price_usd}}</td>
                <td>{{$item->client?->name ?? '-'}}</td>
                <td>
                    {{$item->client?->phone ? $item->client?->phone_number : '-'}}
                    @if($item->client?->phone2)<br>  {{$item->client?->phone_number2}}@endif
                </td>
                <td>{{$item->client?->address ?? '-'}}</td>
                <td>{{$item->user?->name ?? '-'}}</td>
            </tr>
            @php $total = $total - 1; @endphp
        @endforeach
        </tbody>
    </table>
</body>
</html>
