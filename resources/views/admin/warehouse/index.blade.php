@extends('admin.layouts.app')

@section('content')
    <div class="container-xl">
        <!-- Page title -->
        <div class="page-header d-print-none">
            <h2 class="page-title">
                Omborlar
            </h2>
            <div class="col-auto ms-auto d-print-none">
                <a href="#" data-bs-toggle="modal" data-bs-target="#modal-warehouse-" class="btn btn-primary">Yangi
                    kiritish</a>
                @include('admin.warehouse.form', ['item' => new \App\Models\Warehouse\Warehouse()])
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
                            <th>Status</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($warehouses as $warehouse)
                            <tr>
                                <td>{{ $warehouse->id }}</td>
                                <td>
                                    {{ $warehouse->name }}
                                    <div class="text-secondary">{{$warehouse->comment}}</div>
                                </td>
                                <td>
                                    @if($warehouse->active)
                                        <span class="badge bg-green text-green-fg">Aktiv</span>
                                    @else
                                        <span class="badge bg-red text-red-fg">O'chirilgan</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="#" class="btn btn-icon btn-success"
                                       data-bs-target="#modal-warehouse-{{$warehouse->id}}" data-bs-toggle="modal">
                                        <x-svg.pen></x-svg.pen>
                                    </a>
                                    @include('admin.warehouse.form', ['item' => $warehouse])
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
