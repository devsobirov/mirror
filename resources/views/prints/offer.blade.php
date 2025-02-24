<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chek #{{ $offer['id'] }}</title>
    <style>
        @media print {
            @page {
                margin: 2mm;
                size: auto;
            }
            body {
                margin: 0 !important;
                padding: 0 !important;
            }
        }
        body {
            margin: 0;
            padding: 0;
            -webkit-print-color-adjust: exact;
            font-family: 'Arial Narrow', sans-serif;
            font-size: 12px;
        }
        .receipt {
            width: 80mm;
            min-height: 100vh;
            margin: 0 auto;
            padding: 2mm;
            box-sizing: border-box;
        }
        .receipt h2 {
            text-align: center;
            margin: 2mm 0;
            font-size: 14px;
        }
        .receipt table {
            width: 100%;
            border-collapse: collapse;
            margin: 0;
        }
        .receipt th,
        .receipt td {
            border-bottom: 1px dashed #000;
            padding: 2mm 1mm;
        }
        .receipt th {
            text-align: left;
        }
        .receipt td {
            vertical-align: top;
        }
        .receipt .text-center {
            text-align: center;
        }
        .receipt .text-right {
            text-align: right;
        }
        .receipt .text-bold {
            font-weight: bold;
        }
        .receipt .text-muted {
            color: #666;
        }
    </style>
    @php
        $org = $settings->organization;
        $phone = $settings->phone1;
        if ($p2 = $settings->phone2) {
            $phone .= ", $p2";
        }
        $thanks = $settings->thanks;
    @endphp
</head>
<body>
    <div class="receipt">
        <h1 style="text-align:center">{{$org}}</h1>
        <h2 style="text-align:center">{{$phone}}</h2>
        <div style="margin-bottom: 3mm;">
            <table style="width: 100%;">
                <tr>
                    <td style="text-align: left; border-bottom:none; padding-bottom:0">Buyurtma ID: #{{ $offer['id'] }}</td>
                    <td style="text-align: right; border-bottom:none; padding-bottom:0">Sana: {{ $offer['created_at'] }}</td>
                </tr>
                <tr>
                    <td style="text-align: left; border-bottom:none; padding-bottom:0">Mijoz: {{ $offer['client_name'] }}</td>
                    <td style="text-align: right; border-bottom:none; padding-bottom:0">
                        Tel: {{ $offer['phone_number'] ?? '-' }}@if($ph2 = $offer['phone_number2'] ?? null),<br> {{$ph2}}@endif
                    </td>
                </tr>
            </table>
        </div>

        <table>
            <thead>
                <tr>
                    <th style="width: 8%">№</th>
                    <th>Xizmat</th>
                    <th style="width: 12%; text-align: center">Miqdor</th>
                    <th style="width: 20%; text-align: right">Jami</th>
                </tr>
            </thead>
            <tbody>
                @foreach($offer['offer_items'] as $index => $item)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>
                        <div class="text-bold">{{ $item['service_name'] }}</div>
                        @if($item['memo'])
                        <div style="font-size: 14px; margin-top: 1mm;">{{ $item['memo'] }}</div>
                        @endif
                    </td>
                    <td class="text-center">x {{ $item['quantity'] }}</td>
                    <td class="text-right">{{ $item['total'] }} UZS</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div style="margin-top: 4mm; padding-top: 3mm; border-top: 1px dashed #000;">
            <div style="display: flex; justify-content: space-between; margin: 1mm 0">
                <span>Jami:</span>
                <span>{{ $offer['cost'] }} UZS</span>
            </div>
            <div style="display: flex; justify-content: space-between; margin: 1mm 0">
                <span>Chegirma:</span>
                <span>{{ $offer['discount_amount'] }} UZS</span>
            </div>
            <div style="display: flex; justify-content: space-between; margin: 1mm 0; font-weight: bold">
                <span>To'lovga:</span>
                <span>{{ $offer['final_cost'] }} UZS</span>
            </div>
        </div>

        <p style="text-align: center; margin: 5mm 0 2mm; font-size: 12px; color: #000; font-weight: bold;">
            {{$thanks}}
        </p>
    </div>
</body>
</html>