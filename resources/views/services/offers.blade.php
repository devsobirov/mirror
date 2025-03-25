@extends('admin.layouts.app')

@section('content')
    <div class="container-xl">
        <!-- Sahifa sarlavhasi -->
        <div class="page-header d-print-none">
            <h2 class="page-title">
                Buyurtmalar ({{$offers->total()}})
            </h2>
            <div class="col-auto ms-auto d-print-none">
                <form action="{{ route('offers.index') }}" class="d-md-flex d-sm-block align-items-center gap-1">
                    <input type="date" class="form-control d-inline-block w-12 me-1" name="date_from" value="{{ request('date_from') }}">
                    <input type="date" class="form-control d-inline-block w-12 me-1" name="date_to" value="{{ request('date_to') }}">
                    <select class="form-select d-inline-block w-12 me-1" name="status">
                        <option value="">Barcha holatlar</option>
                        @foreach(\App\Enums\Offer\OfferStatusEnum::list() as $value => $label)
                            <option value="{{ $value }}" @selected(request('status') == $value)>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                    <input type="search" class="form-control d-inline-block w-12 me-1" minlength="2" 
                           placeholder="Qidirish..." name="search" value="{{ request('search') }}">
                    <button class="btn me-1 btn-icon btn-azure" title="Qidirish">
                        <x-svg.search></x-svg.search>
                    </button>
                    @if(request()->hasAny(['date_from', 'date_to', 'status', 'search']))
                        <a href="{{ route('offers.index') }}" class="btn btn-outline-secondary me-1" title="Filtrlarni tozalash">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-refresh" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                <path d="M20 11a8.1 8.1 0 0 0 -15.5 -2m-.5 -4v4h4" />
                                <path d="M4 13a8.1 8.1 0 0 0 15.5 2m.5 4v-4h-4" />
                            </svg>
                            Tozalash
                        </a>
                    @endif
                    <a href="{{ route('offers.form') }}" class="btn btn-primary">
                        Buyurtma yaratish
                    </a>
                </form>
            </div>
        </div>
    </div>

    <div class="page-body">
        <div class="container-xl">
            <!-- Статистика -->
            @if(auth()->user()->isAdmin() && $stats)
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Jami</h5>
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="p-3 border rounded bg-light text-center">
                                            <h6 class="mb-0">Jami buyurtmalar (to'langan va yakunlangan)</h6>
                                            <h3 class="mt-2">{{ number_format($stats['total'], 0, ',', ' ') }}</h3>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="p-3 border rounded bg-light text-center">
                                            <h6 class="mb-0">Jami chegirmalar</h6>
                                            <h3 class="mt-2">{{ number_format($stats['total_discount'], 0, ',', ' ') }} UZS</h3>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="p-3 border rounded bg-light text-center">
                                            <h6 class="mb-0">Jami kirim</h6>
                                            <h3 class="mt-2">{{ number_format($stats['total_cost'], 0, ',', ' ') }} UZS</h3>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <div class="card">
                <div class="table-responsive">
                    <table class="table" id="dataTable">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Mijoz</th>
                                <th>Telefon</th>
                                <th>Summa</th>
                                <th>Chegirma</th>
                                <th>Holat</th>
                                <th>Menejer</th>
                                <th>Sana</th>
                                <th class="w-1"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($offers as $offer)
                                <tr>
                                    <td>{{ $offer->id }}</td>
                                    <td>{{ $offer->client_name }}</td>
                                    <td>
                                        {{ $offer->phone_number }}
                                        @if($offer->phone_number2)
                                            <br>
                                            <small class="text-muted">{{ $offer->phone_number2 }}</small>
                                        @endif
                                    </td>
                                    <td>{{ $offer->cost }}</td>
                                    <td>{{ $offer->discount }}</td>
                                    <td>
                                        <span class="badge bg-{{ $offer->status_color }}">
                                            {{ $offer->status_name }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($offer->manager)
                                            {{ $offer->manager['name'] }}
                                        @else
                                            <span class="text-muted">Belgilanmagan</span>
                                        @endif
                                    </td>
                                    <td>{{ $offer->created_at }}</td>
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
                                            @if(!$offer->status || $offer->status === \App\Enums\Offer\OfferStatusEnum::Draft->value || auth()->user()->isAdmin())
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
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center">Buyurtmalar topilmadi</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($offers->hasPages())
                    <div class="card-footer pb-0">
                        {{ $offers->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Holatni tahrirlash modal oynasi -->
    <div class="modal modal-blur fade" id="modal-edit-status" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Buyurtma holatini o'zgartirish</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    @if(auth()->user()->isAdmin())
                        <div class="mb-3">
                            <label class="form-label">Mijoz ismi</label>
                            <input type="text" class="form-control" id="client-name-input" placeholder="Mijoz ismini kiriting">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Telefon raqami</label>
                            <div class="input-group">
                                <span class="input-group-text">+998</span>
                                <input type="text" class="form-control" id="phone-input" placeholder="(99) 500-00-00">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Qo'shimcha telefon raqami</label>
                            <div class="input-group">
                                <span class="input-group-text">+998</span>
                                <input type="text" class="form-control" id="phone2-input" placeholder="(99) 500-00-00">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Buyurtma sanasi</label>
                            <input type="date" class="form-control" id="order-date-input">
                        </div>
                    @endif
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

    async function editStatus(offerId, currentStatus) {
        currentOfferId = offerId;
        document.getElementById('status-select').value = currentStatus;

        if (document.getElementById('client-name-input')) {
            try {
                const response = await fetch(`/offers/${offerId}`, {
                    headers: {
                        'Accept': 'application/json',
                    }
                });
                
                const data = await response.json();
                const offer = data.data;
                document.getElementById('client-name-input').value = offer.client_name || '';
                document.getElementById('phone-input').value = offer.phone || '';
                document.getElementById('phone2-input').value = offer.phone2 || '';
                document.getElementById('order-date-input').value = offer.date || '';
                
            } catch (error) {
                console.error('Error loading offer details:', error);
            }
        }
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
                    status: document.getElementById('status-select').value,
                    ...(document.getElementById('client-name-input') && {
                        client_name: document.getElementById('client-name-input').value,
                        phone: document.getElementById('phone-input').value,
                        phone2: document.getElementById('phone2-input').value,
                        created_at: document.getElementById('order-date-input').value
                    })
                })
            });

            const result = await response.json();

            if (result.success) {
                window.location.reload();
            } else {
                alert(result.message);
            }
        } catch (error) {
            console.error('Xato:', error);
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
            console.error('Xato:', error);
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
                        <td class="text-end">${item.item_total}</td>
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

    async function printOffer(offer) {
        try {
            // Запрашиваем HTML чека с сервера
            const response = await fetch(`/offers/getPrint/${offer.id}`, {
                method: 'GET',
                headers: {
                    'Accept': 'text/html',
                },
            });

            if (!response.ok) {
                throw new Error('Chekni yuklashda xatolik yuz berdi');
            }

            const receiptHTML = await response.text();

            // Открываем новое окно для печати
            const printWindow = window.open('', '_blank');
            printWindow.document.write(receiptHTML);
            printWindow.document.close();

            // Даем время на загрузку контента перед печатью
            setTimeout(() => {
                printWindow.print();
                printWindow.close();
            }, 100);
        } catch (error) {
            console.error('Xatolik:', error);
            alert('Chekni chop etishda xatolik yuz berdi');
        }
    }
</script>
@endsection