@extends('admin.layouts.app')

@section('content')
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
                                    <span class="status status-pink">
                                      <span class="status-dot status-dot-animated"></span>
                                      {{ $item->amount }} UZS
                                    </span>
                                </td>
                                <td>
                                    <span class="status status-pink">
                                      <span class="status-dot status-dot-animated"></span>
                                      {{ $item->paid_amount }} UZS
                                    </span>
                                </td>
                                <td>
                                    <span class="status status-pink">
                                      <span class="status-dot status-dot-animated"></span>
                                      {{ $item->left_amount }} UZS
                                    </span>
                                </td>
                                <td>
                                    <span class="status status-pink">
                                      <span class="status-dot status-dot-animated"></span>
                                      Kechikkan
                                    </span>
                                </td>
                                <td></td>
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
