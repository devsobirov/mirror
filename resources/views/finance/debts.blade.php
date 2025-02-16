@extends('admin.layouts.app')

@section('content')
<style>
.rating-star {
    transition: color 0.3s ease;
}
.rating-star svg {
    fill: #dadfe5;
}
.rating-star:hover svg {
    fill: currentColor;
}

/* Hover colors */
.rating-star[data-rating="1"]:hover { color: #d63939; }
.rating-star[data-rating="2"]:hover { color: #f59f00; }
.rating-star[data-rating="3"]:hover { color: #f8c200; }
.rating-star[data-rating="4"]:hover { color: #74b816; }
.rating-star[data-rating="5"]:hover { color: #2fb344; }

/* Selected colors */
.rating-input:checked ~ .rating-star svg { fill: #dadfe5 !important; }
.rating-input:checked + .rating-star svg { fill: currentColor !important; }

/* Color schemes for selected ratings */
.rating-input[value="1"]:checked + .rating-star { color: #d63939; }
.rating-input[value="2"]:checked + .rating-star,
.rating-input[value="2"]:checked + .form-selectgroup-item .rating-star { color: #f59f00; }
.rating-input[value="3"]:checked + .rating-star,
.rating-input[value="3"]:checked + .form-selectgroup-item .rating-star,
.rating-input[value="3"]:checked + .form-selectgroup-item + .form-selectgroup-item .rating-star { color: #f8c200; }
.rating-input[value="4"]:checked + .rating-star,
.rating-input[value="4"]:checked + .form-selectgroup-item .rating-star,
.rating-input[value="4"]:checked + .form-selectgroup-item + .form-selectgroup-item .rating-star,
.rating-input[value="4"]:checked + .form-selectgroup-item + .form-selectgroup-item + .form-selectgroup-item .rating-star { color: #74b816; }
.rating-input[value="5"]:checked + .rating-star,
.rating-input[value="5"]:checked + .form-selectgroup-item .rating-star,
.rating-input[value="5"]:checked + .form-selectgroup-item + .form-selectgroup-item .rating-star,
.rating-input[value="5"]:checked + .form-selectgroup-item + .form-selectgroup-item + .form-selectgroup-item .rating-star,
.rating-input[value="5"]:checked + .form-selectgroup-item + .form-selectgroup-item + .form-selectgroup-item + .form-selectgroup-item .rating-star { color: #2fb344; }
</style>
    <div class="container-xl">
        <!-- Фильтры -->
        <div class="page-header d-print-none">
            <h2 class="page-title">Aktiv qarzlar ({{$items->total()}})</h2>
            <div class="col-auto ms-auto d-print-none">
                <form action="{{ route('finance.debts.index') }}" class="d-md-flex d-sm-block align-items-center gap-1">
                    <input type="date" class="form-control" name="date_from" placeholder="Sana (dan)" value="{{request('date_from')}}">
                    <input type="date" class="form-control" name="date_to" placeholder="Sana (gacha)" value="{{request('date_to')}}">
                    <select class="form-select" name="status">
                        <option value="">Holati bo'yicha</option>
                        @foreach(\App\Enums\Finance\DebtStatusEnum::list() as $value => $label)
                            <option value="{{ $value }}" @selected(request('status') == $value)>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                    <select class="form-select" name="sort">
                        <option value="" @selected(!request('sort'))>Oldin Eski qarzlar</option>
                        <option value="asc" @selected(request('sort') == 'asc')>Oldin Yangi qarzlar</option>
                    </select>
                    <input type="search" class="form-control" placeholder="Mijoz ismi yoki tel..." name="search_client" value="{{ request('search_client') }}">
                    @if(request('date_from') || request('date_to') || request('status') || request('search_client')|| request('sort'))
                        <a href="{{ route('finance.debts.index') }}" class="btn btn-secondary">Сбросить фильтры</a>
                    @endif
                    <button class="btn btn-primary" type="submit">Qidirish</button>
                </form>
            </div>
        </div>
    </div>
    <div class="page-body">
        <div class="container-xl">
            <div class="card mb-4">
                <div class="table-responsive">
                    <table class="table" id="dataTable">
                        <thead>
                            <tr>
                                <th>Mijoz</th>
                                <th>Olingan Sana</th>
                                <th>Yopish sanasi</th>
                                <th>Qarz miqdori (UZS)</th>
                                <th>To'langan (UZS)</th>
                                <th>Qoldiq (UZS)</th>
                                <th>Holati</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($items as $item)
                                <tr>
                                    <td>
                                        @if($client = $item->client)
                                            <a href="{{ route('finance.clients.show', $client->id) }}" class="mb-2 d-inline-block">
                                                {{$client->name}}
                                            </a>
                                            <p class="mb-1">Tel: {{$client->phone_number ?? '-'}}</p>
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>{{ $item->created_at?->format('d F-Y') ?? '-' }}</td>
                                    <td>{{ $item->payment_date?->format('d M-Y') ?? '-' }}</td>
                                    <td>
                                        <span class="status status-primary">
                                            <span class="status-dot status-dot-animated"></span> {{ number_format($item->amount, 0, ',', ' ') }} UZS
                                        </span>
                                    </td>
                                    <td>
                                        @if($item->paid_amount > 0)
                                            <span class="status status-success" data-bs-toggle="modal" data-bs-target="#payment-modal-{{$item->id}}">
                                                <span class="status-dot status-dot-animated"></span>  {{ number_format($item->paid_amount, 0, ',', ' ') }} UZS
                                            </span>
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>
                                        <span class="status status-warning">
                                            <span class="status-dot status-dot-animated"></span> {{ number_format($item->left_amount, 0, ',', ' ') }} UZS
                                        </span>
                                    </td>
                                    <td>
                                        <span class="status status-{{ $item->status_color }}"><span class="status-dot status-dot-animated"></span> {{ $item->status_name }}</span>
                                    </td>
                                    <td>
                                        <a href="#" class="btn btn-success" title="Tahrirlash" data-bs-target="#payment-{{$item->id}}" data-bs-toggle="modal">
                                            To'lov yaratish
                                        </a>
                                        @include('finance.payment-modal', ['item' => $item])
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center">Aktiv qarzlar topilmadi</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($items->hasPages())
                    <div class="card-footer pb-0">
                        {{ $items->links() }}
                    </div>
                @endif
            </div>

            <!-- Блок агрегатов -->
        @if(auth()->user()->isAdmin() && $stats)
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Jami</h5>
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="p-3 border rounded bg-light text-center">
                                        <h6 class="mb-0">Jami qarzlar</h6>
                                        <h3 class="mt-2">{{ number_format($stats['total_debts'], 0, ',', ' ') }}</h3>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="p-3 border rounded bg-light text-center">
                                        <h6 class="mb-0">To'langan summa</h6>
                                        <h3 class="mt-2">{{ number_format($stats['total_paid'], 0, ',', ' ') }} UZS</h3>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="p-3 border rounded bg-light text-center">
                                        <h6 class="mb-0">Qoldiq summa</h6>
                                        <h3 class="mt-2">{{ number_format($stats['total_left'], 0, ',', ' ') }} UZS</h3>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="p-3 border rounded bg-light text-center">
                                        <h6 class="mb-0">Qarzdorlar soni</h6>
                                        <h3 class="mt-2">{{ $stats['debtors_count'] }}</h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
        </div>
    </div>

    <!-- Модальное окно для отображения информации о платежах -->
    @foreach($items as $item)
        <div class="modal fade" id="payment-modal-{{$item->id}}" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">To'lovlar - {{$item->client->name}}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Sana</th>
                                    <th>Miqdor (UZS)</th>
                                    <th>Tur</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($item->payments as $payment)
                                    <tr>
                                        <td>{{ $payment->created_at->format('d F Y') }}</td>
                                        <td>{{ number_format($payment->amount, 0, ',', ' ') }} UZS</td>
                                        <td>{{ $payment->type_name }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
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
function formatAmount(input) {
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