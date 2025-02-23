@extends('admin.layouts.app')

@section('content')
<div class="page-wrapper">
    <div class="container-xl">
        <!-- Page title -->
        <div class="page-header d-print-none">
            <div class="row align-items-center">
                <div class="col">
                    <h2 class="page-title">
                        Buyurtmalar
                    </h2>
                </div>
                <div class="col-auto ms-auto d-print-none">
                    <form action="{{ route('finance.clients.index') }}" class="d-md-flex d-sm-block align-items-center gap-1">
                        <input type="search" 
                               class="form-control d-inline-block w-12 me-1" 
                               minlength="2" 
                               placeholder="Mijoz ismi, tel ..." 
                               name="search_client" 
                               value="{{ request('search_client') }}">
                        <button class="btn me-1 btn-icon btn-azure" title="Izlash">
                            <x-svg.search></x-svg.search>
                        </button>
                        <a href="{{ route('offers.form') }}" class="btn btn-primary">
                            Buyurtma yaratish
                        </a>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="page-body">
        <div class="container-xl">
            @if($todayDebts->isNotEmpty())
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Bugungi to'lanadigan qarzlar</h3>
                            <div class="card-actions">
                                <span class="badge bg-red">{{ $todayDebts->count() }} qarzlar</span>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row mb-3">
                                <div class="col-sm-6">
                                    <div class="card card-sm">
                                        <div class="card-body">
                                            <div class="row align-items-center">
                                                <div class="col-auto">
                                                    <span class="bg-blue text-white avatar">
                                                        <i class="fas fa-money-check-alt"></i>
                                                    </span>
                                                </div>
                                                <div class="col">
                                                    <div class="font-weight-medium">
                                                        {{ number_format($todayDebtsTotal, 0, ',', ' ') }} UZS
                                                    </div>
                                                    <div class="text-muted">
                                                        Umumiy summa
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="card card-sm">
                                        <div class="card-body">
                                            <div class="row align-items-center">
                                                <div class="col-auto">
                                                    <span class="bg-green text-white avatar">
                                                        <i class="fas fa-check"></i>
                                                    </span>
                                                </div>
                                                <div class="col">
                                                    <div class="font-weight-medium">
                                                        {{ number_format($todayDebtsReceived, 0, ',', ' ') }} UZS
                                                    </div>
                                                    <div class="text-muted">
                                                        Qabul qilindi
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-vcenter card-table">
                                    <thead>
                                        <tr>
                                            <th>Mijoz</th>
                                            <th>Olingan Sana</th>
                                            <th>To'lov sanasi</th>
                                            <th>Qarz miqdori (UZS)</th>
                                            <th>To'langan (UZS)</th>
                                            <th>Qoldiq (UZS)</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($todayDebts as $debt)
                                        <tr>
                                            <td>
                                                <div class="d-flex py-1 align-items-center">
                                                    <div class="flex-fill">
                                                        <div class="font-weight-medium">
                                                            <a href="{{ route('finance.clients.show', $debt->client->id) }}">
                                                                {{ $debt->client->name }}
                                                            </a>
                                                        </div>
                                                        <div class="text-muted">Tel: {{ $debt->client->phone_number ?? '-' }}</div>
                                                        <div class="text-muted">Tel2: {{ $debt->client->phone_number2 ?? '-' }}</div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="text-secondary"><x-svg.calendar></x-svg.calendar></span>
                                                {{ $debt->created_at?->format('d F-Y') ?? '-' }}
                                                <p class="mt-2">({{$debt->created_at?->diffForHumans()}})</p>
                                            </td>
                                            <td>
                                                <span class="text-secondary"><x-svg.calendar></x-svg.calendar></span>
                                                {{ $debt->payment_date?->format('d M-Y') ?? '-' }}
                                                <p class="mt-2">({{$debt->payment_date?->diffForHumans()}})</p>
                                            </td>
                                            <td>
                                                <span class="status status-primary">
                                                    <span class="status-dot status-dot-animated"></span>
                                                    {{ number_format($debt->amount, 0, ',', ' ') }} UZS
                                                </span>
                                            </td>
                                            <td>
                                                <span class="status status-success">
                                                    <span class="status-dot status-dot-animated"></span>
                                                    {{ number_format($debt->paid_amount, 0, ',', ' ') }} UZS
                                                </span>
                                            </td>
                                            <td>
                                                <span class="status status-warning">
                                                    <span class="status-dot status-dot-animated"></span>
                                                    {{ number_format($debt->left_amount, 0, ',', ' ') }} UZS
                                                </span>
                                            </td>
                                            <td>
                                                <a href="#" class="btn btn-success" title="To'lov yaratish"
                                                   data-bs-target="#payment-{{$debt->id}}" data-bs-toggle="modal">
                                                    To'lov yaratish
                                                </a>
                                                @include('finance.payment-modal', ['item' => $debt])
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Oxirgi buyurtmalar</h3>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-vcenter card-table">
                                <thead>
                                    <tr>
                                        <th>№ Buyurtma</th>
                                        <th>Mijoz</th>
                                        <th>Xizmatlar</th>
                                        <th>Summa</th>
                                        <th>Holat</th>
                                        <th class="w-1"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($latestOffers as $offer)
                                    <tr>
                                        <td>{{ $offer->id }}</td>
                                        <td>
                                            <div class="d-flex py-1 align-items-center">
                                                <div class="flex-fill">
                                                    <div class="font-weight-medium">{{ $offer->client_name }}</div>
                                                    <div class="text-muted">{{ $offer->phone_number }}</div>
                                                    @if($offer->phone_number2)
                                                        <div class="text-muted">{{ $offer->phone_number2 }}</div>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            @foreach($offer->offerItems as $item)
                                                <div>{{ $item->service->name }}</div>
                                            @endforeach
                                        </td>
                                        <td>{{ number_format($offer->cost, 0, ',', ' ') }} UZS</td>
                                        <td>
                                            <span class="badge bg-{{ $offer->status_color }}">
                                                {{ $offer->status_name }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="btn-list flex-nowrap">
                                                <a href="#" class="btn btn-icon" title="Buyurtmani ko'rish"
                                                   onclick="showOfferDetails({{ $offer->id }})"
                                                   data-bs-toggle="modal" 
                                                   data-bs-target="#modal-offer-details">
                                                    <x-svg.eye></x-svg.eye>
                                                </a>
                                                <a href="{{route('offers.copy', $offer->id)}}" class="btn btn-icon" title="Nusxalash">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-copy" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                                        <rect x="8" y="8" width="12" height="12" rx="2" />
                                                        <path d="M16 8v-2a2 2 0 0 0 -2 -2h-8a2 2 0 0 0 -2 2v8a2 2 0 0 0 2 2h2" />
                                                    </svg>
                                                </a>
                                                <a href="#" 
                                                   class="btn btn-icon" 
                                                   title="Holatni tahrirlash"
                                                   onclick="editStatus({{ $offer->id }}, {{ $offer->status }})"
                                                   data-bs-toggle="modal" 
                                                   data-bs-target="#modal-edit-status">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-edit" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                                        <path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1"></path>
                                                        <path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z"></path>
                                                        <path d="M16 5l3 3"></path>
                                                    </svg>
                                                </a>
                                                @if(!$offer->status || $offer->status === \App\Enums\Offer\OfferStatusEnum::Draft->value)
                                                    <a href="#" 
                                                       class="btn btn-icon" 
                                                       title="O'chirish"
                                                       onclick="deleteOffer({{ $offer->id }})">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-trash" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                                            <line x1="4" y1="7" x2="20" y2="7" />
                                                            <line x1="10" y1="11" x2="10" y2="17" />
                                                            <line x1="14" y1="11" x2="14" y2="17" />
                                                            <path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" />
                                                            <path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" />
                                                        </svg>
                                                    </a>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Добавляем модальные окна -->
<!-- Модальное окно редактирования статуса -->
<div class="modal modal-blur fade" id="modal-edit-status" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Buyurtma holatini o'zgartirish</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Holat</label>
                    <select class="form-select" id="status-select">
                        @foreach(\App\Enums\Offer\OfferStatusEnum::list() as $value => $label)
                            <option value="{{ $value }}">{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn me-auto" data-bs-dismiss="modal">Bekor qilish</button>
                <button type="button" class="btn btn-primary" id="save-status">Saqlash</button>
            </div>
        </div>
    </div>
</div>

<!-- Buyurtma tafsilotlarini ko'rish modal oynasi -->
<div class="modal modal-blur fade" id="modal-offer-details" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Buyurtma tafsilotlari #<span id="offer-number"></span></h5>
                    <div class="ms-auto">
                        <a href="#" class="btn btn-icon" title="Chop etish" id="print-offer" onclick="printOffer(currentOffer)">
                            <x-svg.printer></x-svg.printer>
                        </a>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                </div>
                <div class="modal-body">
                    <div class="row mb-3">
                        <div class="col-lg-6">
                            <p class="mb-1"><strong>Mijoz:</strong> <span id="client-name"></span></p>
                            <p class="mb-1"><strong>Telefon:</strong> <span id="phone_number"></span></p>
                            <p class="mb-1"><strong>Qo'shimcha telefon:</strong> <span id="phone_number2"></span></p>
                            <p class="mb-1"><strong>Izoh:</strong> <span id="client-comment"></span></p>
                        </div>
                        <div class="col-lg-6 text-end">
                            <p class="mb-1"><strong>Sana:</strong> <span id="order-date"></span></p>
                            <p class="mb-1"><strong>Holat:</strong> <span id="order-status"></span></p>
                            <p class="mb-1"><strong>Menejer:</strong> <span id="order-manager"></span></p>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-vcenter" id="services-table">
                            <thead>
                                <tr>
                                    <th>Xizmat</th>
                                    <th>O'lchov</th>
                                    <th class="text-end">Miqdori</th>
                                    <th class="text-end">Jami</th>
                                    <th class="text-end">Narx</th>
                                    <th class="text-end">Summa</th>
                                </tr>
                            </thead>
                            <tbody id="services-list"></tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="5" class="text-end"><strong>Jami:</strong></td>
                                    <td class="text-end" id="total-cost"></td>
                                </tr>
                                <tr>
                                    <td colspan="5" class="text-end"><strong>Chegirma:</strong></td>
                                    <td class="text-end" id="discount-amount"></td>
                                </tr>
                                <tr>
                                    <td colspan="5" class="text-end"><strong>To'lovga:</strong></td>
                                    <td class="text-end" id="final-cost"></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('custom_scripts')
<script>
let currentOfferId = null;
    let currentOffer = null;
// Функции для форматирования суммы
function formatAmount() {
    return {
        amount: '',
        amountInWords: '',
        init() {
            this.$watch('amount', value => {
                this.amountInWords = this.numberToWords(value);
            });
        },
        numberToWords(num) {
            if (!num) return '';
            // Здесь можно добавить конвертацию числа в слова
            return num + ' сум';
        }
    }
}

// Функции для рейтинга
function ratingData() {
    return {
        rating: 0,
        getColor(star) {
            return {
                'text-yellow': this.rating >= star,
                'text-muted': this.rating < star
            }
        }
    }
}

// Основные функции для работы с заказами
function editStatus(offerId, currentStatus) {
    currentOfferId = offerId;
    document.getElementById('status-select').value = currentStatus;
}

document.getElementById('save-status').addEventListener('click', async function() {
    if (!currentOfferId) return;

    try {
        const response = await fetch(`/offers/${currentOfferId}/update-status`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                status: document.getElementById('status-select').value
            })
        });

        const result = await response.json();

        if (result.success) {
            window.location.reload();
        } else {
            alert(result.message);
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Holatni yangilashda xato yuz berdi');
    }
});

function deleteOffer(offerId) {
    if (!confirm('Siz ushbu buyurtmani o\'chirishni xohlaysizmi?')) return;

    fetch(`/offers/${offerId}`, {
        method: 'DELETE',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(response => response.json())
    .then(result => {
        if (result.success) {
            window.location.reload();
        } else {
            alert(result.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Buyurtmani o\'chirishda xato yuz berdi');
    });
}

async function showOfferDetails(offerId) {
        try {
            const response = await fetch(`/offers/${offerId}`, {
                headers: {
                    'Accept': 'application/json',
                }
            });
            
            const data = await response.json();
            const offer = data.data;
            currentOffer = offer;

            // Asosiy ma'lumotlarni to'ldirish
            document.getElementById('offer-number').textContent = offer.id;
            document.getElementById('client-name').textContent = offer.client_name || '-';
            document.getElementById('phone_number').textContent = offer.phone_number || '-';
            document.getElementById('phone_number2').textContent = offer.phone_number2 || '-';
            document.getElementById('client-comment').textContent = offer.comment || '-';
            document.getElementById('order-date').textContent = offer.created_at || '-';
            document.getElementById('order-status').textContent = offer.status_name || '-';
            document.getElementById('order-manager').textContent = offer.manager?.name || 'Belgilanmagan';
            
            // Xizmatlar jadvalini to'ldirish
            const servicesList = document.getElementById('services-list');
            servicesList.innerHTML = '';
            
            offer.offer_items?.forEach(item => {
                const row = `
                    <tr>
                        <td>
                            <div class="d-flex align-items-center">
                                ${item.service_image ? `<span class="avatar me-2" style="background-image: url(${item.service_image})"></span>` : ''}
                                ${item.service_name}
                            </div>
                            <small class="text-muted d-block">${item.memo}</small>
                        </div>
                        </td>
                        <td>${item.dimention_amount} ${item.unit || ''}</td>
                        <td class="text-end">${item.quantity}</td>
                        <td>${item.total_amount}</td>
                        <td class="text-end">
                            ${item.has_discount 
                                ? `<s class="text-muted">${item.original_price}</s><br>${item.price}`
                                : item.price
                            }
                        </td>
                        <td class="text-end">${item.total}</td>
                    </tr>
                `;
                servicesList.innerHTML += row;
            });

            // Yakunlarni to'ldirish (tayyor formatlangan qiymatlarni resursdan foydalanamiz)
            document.getElementById('total-cost').textContent = offer.cost + ' UZS';
            document.getElementById('discount-amount').textContent = offer.discount_amount + ' UZS';
            document.getElementById('final-cost').textContent = offer.final_cost + ' UZS';

        } catch (error) {
            console.error('Xato:', error);
            alert('Buyurtma ma\'lumotlarini yuklashda xato yuz berdi');
        }
    }
function printOffer(offer) {
    // Создаем HTML для печати
    let printContent = `
        <div style="width: 80mm; min-height: 100vh; margin: 0 auto; padding: 2mm; box-sizing: border-box; font-family: 'Arial Narrow', sans-serif; font-size: 12px;">
            <h2 style="text-align: center; margin: 2mm 0; font-size: 14px;">Xonqa-tech</h2>
            <div style="text-align: center; margin-bottom: 3mm;">
                <div>Buyurtma ID: ${offer.id}</div>
                <div>Sana: ${offer.created_at}</div>
            </div>

            <table style="width: 100%; border-collapse: collapse; margin: 0;">
                <thead>
                    <tr>
                        <th style="border-bottom: 1px dashed #000; padding: 2mm 1mm; width: 8%">№</th>
                        <th style="border-bottom: 1px dashed #000; padding: 2mm 1mm; text-align: left">Xizmat</th>
                        <th style="border-bottom: 1px dashed #000; padding: 2mm 1mm; width: 12%; text-align: center">Miqdor</th>
                        <th style="border-bottom: 1px dashed #000; padding: 2mm 1mm; width: 20%; text-align: right">Narx</th>
                        <th style="border-bottom: 1px dashed #000; padding: 2mm 1mm; width: 20%; text-align: right">Jami</th>
                    </tr>
                </thead>
                <tbody>`;

    // Заполняем таблицу данными
    offer.offer_items.forEach((item, index) => {
        printContent += `
            <tr>
                <td style="padding: 2mm 1mm; text-align: center; vertical-align: top">${index + 1}</td>
                <td style="padding: 2mm 1mm; text-align: left; vertical-align: top">
                    <div style="font-weight: 500">${item.service_name}</div>
                    ${item.memo ? `<div style="font-size: 14px; margin-top: 1mm;">${item.memo}</div>` : ''}
                </td>
                <td style="padding: 2mm 1mm; text-align: center; vertical-align: top">x ${item.quantity}</td>
                <td style="padding: 2mm 1mm; text-align: right; vertical-align: top">${item.price} UZS</td>
                <td style="padding: 2mm 1mm; text-align: right; vertical-align: top">${item.total} UZS</td>
            </tr>`;
    });

    printContent += `
                </tbody>
            </table>

            <div style="margin-top: 4mm; padding-top: 3mm; border-top: 1px dashed #000;">
                <div style="display: flex; justify-content: space-between; margin: 1mm 0">
                    <span>Jami:</span>
                    <span>${offer.cost} UZS</span>
                </div>
                <div style="display: flex; justify-content: space-between; margin: 1mm 0">
                    <span>Chegirma:</span>
                    <span>${offer.discount_amount} UZS</span>
                </div>
                <div style="display: flex; justify-content: space-between; margin: 1mm 0; font-weight: bold">
                    <span>To'lovga:</span>
                    <span>${offer.final_cost} UZS</span>
                </div>
            </div>

            <p style="text-align: center; margin: 5mm 0 2mm; font-size: 12px; color: #000; font-weight: bold;">
                Xarid qilganingiz uchun rahmat!
            </p>
        </div>`;

    // Открываем новое окно для печати
    const printWindow = window.open('', '_blank');
    printWindow.document.write(`
        <html>
            <head>
                <title>Chek #${offer.id}</title>
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
                    }
                </style>
            </head>
            <body>
                ${printContent}
            </body>
        </html>
    `);
    printWindow.document.close();
    
    // Даем время на загрузку контента перед печатью
    setTimeout(() => {
        printWindow.print();
        printWindow.close();
    }, 100);
}
</script>
@endsection
