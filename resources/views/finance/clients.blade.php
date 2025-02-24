@extends('admin.layouts.app')

@section('content')
    <div class="container-xl">
        <!-- Page title -->
        <div class="page-header d-print-none">
            <h2 class="page-title">
                Mijozlar ({{$items->total()}})
            </h2>
            <div class="col-auto ms-auto d-print-none">
                <form action="{{route('finance.clients.index')}}" class="d-md-flex d-sm-block align-items-center gap-1">
                    <input type="search" class="form-control d-inline-block w-12 me-1" minlength="2" placeholder="Mijoz ismi, tel ..." name="search_client" value="{{request('search_client')}}">
                    <button class="btn me-1 btn-icon btn-azure" title="Izlash"><x-svg.search></x-svg.search></button>
                    <a href="#" data-bs-toggle="modal" data-bs-target="#client-" class="btn btn-primary">Yangi kiritish</a>
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
                            <th>FIO/Manzil</th>
                            <th>Tel</th>
                            <th>Qarzdorlik</th>
                            <th>Rating</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($items as $item)
                            <tr>
                                <td>
                                    <a href="{{route('finance.clients.show', $item)}}">
                                        {{$item->name}}
                                        <p class="mb-0 text-secondary">{{$item->address}}</p>
                                    </a>
                                </td>
                                <td>
                                    <p class="mb-1">Tel : {{$item->phone_number ?? '-'}}</p>
                                    <p class="mb-1">Tel2: {{$item->phone_number2 ?? '-'}}</p>
                                </td>
                                <td>{{$item->debt ? (number_format($item->debt) ." UZS") : "Yo'q"}}</td>
                                <td><x-rating :rating="$item->rating"></x-rating></td>
                                <td>
                                    <!-- <a href="#" class="btn btn-icon btn-azure" title="Buyurtma yaratish"
                                       data-bs-target="#client-{{$item->id}}" data-bs-toggle="modal">
                                        <x-svg.plus></x-svg.plus>
                                    </a> -->
                                    <a href="#" class="btn btn-icon btn-pink" title="Qarz berish"
                                       data-bs-target="#debt-{{$item->id}}" data-bs-toggle="modal">
                                       <x-svg.bills></x-svg.bills>
                                    </a>
                                    <a href="#" class="btn btn-icon btn-success" title="Tahrirlash"
                                       data-bs-target="#client-{{$item->id}}" data-bs-toggle="modal">
                                        <x-svg.pen></x-svg.pen>
                                    </a>
                                    @include('finance.client-modal', ['item' => $item])
                                    @include('finance.debt-modal', ['item' => $item])
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center">Mijozlar topilmadi</td>
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
    @include('finance.client-modal', ['item' => new \App\Models\Client()])
@endsection
