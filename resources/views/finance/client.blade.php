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
                            <th>Berilgan sana</th>
                            <th>Yopish sanani</th>
                            <th>Yopilgan sana</th>
                            <th>Qarz miqdori</th>
                            <th>Qoldiq</th>
                            <th>To'lovlar</th>
                            <th>Reyting</th>
                            <th></th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($debts as $debt)
                            <tr>
                                <td>{{ $debt->created_at?->format('d.m.Y') ?? '-' }}</td>
                                <td>{{ $debt->payment_date?->format('d.m.Y') ?? '-' }}</td>
                                <td>{{ $debt->closed_at?->format('d.m.Y') ?? '-'}}</td>
                                <td>{{ number_format($debt->amount, 0, '.', ' ') }}</td>
                                <td>
                                    {{ number_format($debt->amount - $debt->payments->sum('amount'), 0, '.', ' ') }}
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
                                <td>
                                    @if($debt->amount > $debt->payments->sum('amount'))
                                        <a href="#" class="btn btn-success" title="To'lov yaratish"
                                           data-bs-target="#payment-{{$debt->id}}" data-bs-toggle="modal">
                                            To'lov yaratish
                                        </a>
                                        @include('finance.payment-modal', ['item' => $debt])
                                    @endif
                                </td>
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
                            <td colspan="3"><strong>Jami:</strong></td>
                            <td>{{ number_format($total_debt, 0, '.', ' ') }}</td>
                            <td>{{ number_format($total_debt_remaining, 0, '.', ' ') }}</td>
                            <td>{{ number_format($total_payments, 0, '.', ' ') }}</td>
                            <td><x-rating :rating="$rating"></x-rating></td>
                            <td>
                            <a href="#" class="btn btn-pink" title="Qarz berish"
                                data-bs-target="#debt-{{$client->id}}" data-bs-toggle="modal">
                                Qarz berish
                            </a>
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>
@include('finance.debt-modal', ['item' => $client])
@endsection

<script>
function ratingData() {
    return {
        rating: 0,
        colors: {
            1: '#d63939', // red
            2: '#e85d04', // orange-red
            3: '#f8c200', // yellow
            4: '#74b816', // light green
            5: '#2fb344'  // green
        },
        getColor(starRating) {
            return starRating <= this.rating ? this.colors[this.rating] : '#dadfe5'
        },
        setRating(value) {
            this.rating = value;
        }
    }
}
function formatAmount() {
    return {
        amount: '',
        amountInWords: '',
        formatNumber(value) {
            if (!value) return '';
            
            value = parseInt(value);
            
            if (value >= 1000000) {
                const millions = Math.floor(value / 1000000);
                const thousands = Math.floor((value % 1000000) / 1000);
                
                let text = millions + ' mln';
                if (thousands > 0) {
                    text += ' ' + thousands + ' ming';
                }
                return text + ' UZS';
            } else if (value >= 1000) {
                const thousands = Math.floor(value / 1000);
                return thousands + ' ming UZS';
            }
            
            return value + ' UZS';
        },
        init() {
            this.$watch('amount', (value) => {
                this.amountInWords = this.formatNumber(value);
                
                // Update the small text below input
                const input = this.$el.querySelector('input[name="amount"]');
                const small = input.nextElementSibling;
                small.textContent = this.amountInWords;
            });
        }
    }
}
</script>