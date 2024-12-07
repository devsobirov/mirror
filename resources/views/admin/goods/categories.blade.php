@extends('admin.layouts.app')

@section('content')
    <div class="container-xl">
        <!-- Page title -->
        <div class="page-header d-print-none">
            <h2 class="page-title">
                Kategoriyalar ({{count($categories)}})
            </h2>
            <div class="col-auto ms-auto d-print-none">
                <a href="#" data-bs-toggle="modal" data-bs-target="#modal-category-" class="btn btn-primary">Yangi
                    kiritish</a>
                @include('admin.goods.category-form', ['item' => new \App\Models\Warehouse\Category()])
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
                            <th>Tovarlar soni</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($categories as $item)
                            <tr>
                                <td>{{ $item->id }}</td>
                                <td>{{ $item->title }}</td>
                                <td>{{ $item->goods_count }}</td>
                                <td>
                                    <a href="#" class="btn btn-icon btn-success"
                                       data-bs-target="#modal-category-{{$item->id}}" data-bs-toggle="modal">
                                        <x-svg.pen></x-svg.pen>
                                    </a>
                                    @include('admin.goods.category-form', ['item' => $item])
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
