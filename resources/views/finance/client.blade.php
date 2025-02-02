@extends('admin.layouts.app')

@section('content')
<div class="container-xl">
        <!-- Page title -->
        <div class="page-header d-print-none">
            <h2 class="page-title">
                Mijoz ma'lumotlari
            </h2>
            <div class="col-auto ms-auto d-print-none">
                <a href="{{ route('finance.clients.index') }}" class="btn btn-secondary">Orqaga</a>
            </div>
        </div>
    </div>
    <div class="page-body">
        <div class="container-xl">

            <h5 class="mb-3">Mijoz ma'lumotlari</h5>
            <div class="card mb-4">
                <div class="card-body">
                    <p><strong>Ism:</strong> {{ $client->name }}</p>
                    <p><strong>Telefon:</strong> {{ $client->phone_number }}</p>
                    <p><strong>Telefon:</strong> {{ $client->phone_number2 }}</p>

                    <p><strong>Manzil:</strong> {{ $client->address }}</p>
                </div>
            </div>


            <h5 class="mb-3">Qarzlar ro'yxati ({{$debts->count()}})</h5>
            <div class="card mb-4">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Sana</th>
                                <th>Qarz miqdori</th>
                                <th>Qoldiq</th>
                                <th>To'lovlar</th>
                                <th>Reyting</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach($debts as $debt)
                                <tr>
                                    <td>{{ $debt->payment_date->format('d.m.Y') }}</td>
                                    <td>{{ number_format($debt->amount, 0, '.', ' ') }}</td>
                                    <td>
                                        {{ number_format($debt->debt, 0, '.', ' ') }}
                                    </td>
                                    <td>
                                        @if($debt->payments->isNotEmpty())
                                            <ul class="list-unstyled mb-0">
                                                @foreach($debt->payments as $payment)
                                                    <li class="mb-1">
                                                        {{ $payment->created_at->format('d.m.Y') }} - 
                                                        {{ number_format($payment->amount, 0, '.', ' ') }}
                                                        <span class="badge bg-secondary">{{ $payment->type_name }}</span>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td><x-rating :rating="$debt->rating" /></td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                @php $rating = $debts->avg('rating') ?? 0; 
                                    $total_debt = $debts->flatten()->sum('amount');
                                    $total_payments = $debts->flatten()->flatMap->payments->sum('amount');
                                    $total_debt_remaining = $total_debt - $total_payments;
                                @endphp
                                <td><strong>Jami:</strong></td>
                                <td>{{ number_format($total_debt, 0, '.', ' ') }}</td>
                                <td>{{ number_format($total_debt_remaining, 0, '.', ' ') }}</td>
                                <td>{{ number_format($total_payments, 0, '.', ' ') }}</td>
                                <td><x-rating :rating="$rating"></x-rating></td>
                            </tr>

                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection