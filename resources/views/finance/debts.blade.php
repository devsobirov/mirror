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
        <!-- Page title -->
        <div class="page-header d-print-none">
            <h2 class="page-title">
                Aktiv qarzlar ({{$items->total()}})
            </h2>
            <div class="col-auto ms-auto d-print-none">
                <form action="{{route('sklad.expenses.index')}}" class="d-md-flex d-sm-block align-items-center gap-1">
                    <input type="date" class="form-control" name="from" value="{{request('from')}}" min="{{now()->startOfYear()->format('Y-m-d')}}">
                    <input type="date" class="form-control" name="to" value="{{request('to')}}" max="{{now()->format('Y-m-d')}}">
                    <select class="form-select mr-2" name="warehouse_id">
                        <option value="">Oldin eski qarzlar</option>
                        <option value="">Oldin yangi qarzlar</option>
                    </select>
                    <input type="search" class="form-control d-inline-block w-12 me-1" minlength="2" placeholder="Mijoz ismi, tel ..." name="search_client" value="{{request('search_client')}}">
                    <button class="btn me-1 btn-icon btn-azure" title="Izlash"><x-svg.search></x-svg.search></button>
                </form>
            </div>
        </div>
    </div>
    <div class="page-body">
        <div class="container-xl">

            <div class="card">
                <div class="table-responsive">
                    <table class="table" id="dataTable">
                        <thead>
                        <tr>
                            <th>Mijoz</th>
                            <th>Olingan Sana</th>
                            <th>To'lov sanasi</th>
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
                                        <a href="#" class="mb-2 d-inline-block" data-bs-toggle="modal" data-bs-target="#modal-client-{{$client->id}}">{{$client->name}}</a>
                                        <p class="mb-1">Tel: {{$client->phone_number ?? '-'}}</p>
                                        <p class="mb-1">Tel2: {{$client->phone_number2 ?? '-'}}</p>
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>
                                    <span class="text-secondary"><x-svg.calendar></x-svg.calendar></span>
                                    {{ $item->created_at?->format('d F-Y') ?? '-' }}
                                    <p class="mt-2"> ({{$item->created_at?->diffForHumans()}})</p>
                                </td>
                                <td>
                                    <span class="text-secondary"><x-svg.calendar></x-svg.calendar></span>
                                    {{ $item->payment_date?->format('d M-Y') ?? '-' }}
                                    <p class="mt-2"> ({{$item->payment_date?->diffForHumans()}})</p>
                                </td>
                                <td>
                                    <span class="status status-primary">
                                      <span class="status-dot status-dot-animated"></span>
                                      {{ number_format($item->amount, 0, ',', ' ') }} UZS
                                    </span>
                                </td>
                                <td>
                                    <span class="status status-success">
                                      <span class="status-dot status-dot-animated"></span>
                                      {{ number_format($item->paid_amount, 0, ',', ' ') }} UZS
                                    </span>
                                </td>
                                <td>
                                    <span class="status status-warning">
                                      <span class="status-dot status-dot-animated"></span>
                                      {{ number_format($item->left_amount, 0, ',', ' ') }} UZS
                                    </span>
                                </td>
                                <td>
                                    <span class="status status-{{ $statusColors[$item->status] ?? 'secondary' }}">
                                      <span class="status-dot status-dot-animated"></span>
                                      {{ $item->status_name }}
                                    </span>
                                </td>
                                <td>
                                    <a href="#" class="btn btn-success" title="Tahrirlash"
                                       data-bs-target="#payment-{{$item->id}}" data-bs-toggle="modal">
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
        </div>
    </div>
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