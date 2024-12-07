@extends('admin.layouts.app')

@section('content')
    <div class="container-xl">
        <!-- Page title -->
        <div class="page-header d-print-none">
            <h2 class="page-title">
                Yetkazib beruvchilar ({{count($suppliers)}})
            </h2>
            <div class="col-auto ms-auto d-print-none">
                <a href="#" data-bs-toggle="modal" data-bs-target="#modal-suppliers-" class="btn btn-primary">Yangi
                    kiritish</a>
                @include('admin.suppliers.form', ['item' => new \App\Models\Warehouse\Supplier()])
            </div>
        </div>
    </div>
    <div class="page-body">
        <div class="container-xl">

            <div class="card">
                <div class="table-responsive">
                    <table class="table" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nomi</th>
                            <th>Tel</th>
                            <th>Status</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($suppliers as $item)
                            <tr>
                                <td>{{ $item->id }}</td>
                                <td>{{ $item->name }}</td>
                                <td>
                                    @if($item->phone)
                                        {{$item->phone_number}}<br>
                                    @endif
                                    @if($item->phone2)
                                        {{$item->phone_number2}}
                                    @endif
                                    @if(!$item->phone && !$item->phone2)
                                        -
                                    @endif
                                </td>
                                <td>
                                    @if($item->active)
                                        <span class="badge bg-green text-green-fg">Aktiv</span>
                                    @else
                                        <span class="badge bg-red text-red-fg">O'chirilgan</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="#" class="btn btn-icon btn-success"
                                       data-bs-target="#modal-suppliers-{{$item->id}}" data-bs-toggle="modal">
                                        <x-svg.pen></x-svg.pen>
                                    </a>
                                    @include('admin.suppliers.form', ['item' => $item])
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center">Yetkazib beruvchilar topilmadi</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
