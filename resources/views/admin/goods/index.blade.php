@extends('admin.layouts.app')

@section('content')
    <div class="container-xl">
        <!-- Page title -->
        <div class="page-header d-print-none">
            <h2 class="page-title">
                Tovarlar ({{count($goods)}})
            </h2>
            <div class="col-auto ms-auto d-print-none">
                <form action="{{route('admin.goods.index')}}" class="d-md-flex d-sm-block align-items-center">
                    <select class="form-select mr-2" name="category_id">
                        <option value="">Barcha kategoriyalar</option>
                        @foreach($categories as $category)
                            <option value="{{$category->id}}" @if(request('category_id') == $category->id) selected @endif>{{$category->title}}</option>
                        @endforeach
                    </select>
                    <input type="search" class="form-control d-inline-block w-12 me-1" minlength="2" placeholder="Tovar nomi ..." name="search" value="{{request('search')}}">
                    <button class="btn mx-1 btn-icon btn-azure" title="Izlash"><x-svg.search></x-svg.search></button>
                    <a href="{{route('admin.goods.form')}}" class="btn btn-primary">Yangi tovar</a>
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
                                <th>ID</th>
                                <th>Nomi</th>
                                <th>Status</th>
                                <th>Yaratilgan</th>
                                <th>Tahrirlangan</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($goods as $good)
                            <tr>
                                <td>{{ $good->id }}</td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <a data-fslightbox href="{{$good->image_url}}">
                                            <span class="avatar" style="background-image: url({{$good->image_url}})"></span>
                                        </a>
                                        <p style="font-weight: 500" class="m-0">{{ $good->full_title }}</p>
                                    </div>
                                </td>
                                <td>
                                    @if($good->status)
                                        <span class="badge bg-green text-green-fg">Aktiv</span>
                                    @else
                                        <span class="badge bg-red text-red-fg">O'chirilgan</span>
                                    @endif
                                </td>
                                <td>{{ $good->created_at->diffForhumans() }}</td>
                                <td>{{ $good->updated_at->diffForhumans() }}</td>
                                <td>
                                    <a href="{{route('admin.goods.form', ['good' => $good->id])}}" class="btn btn-icon btn-success">
                                        <x-svg.pen></x-svg.pen>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                @if( $goods->hasPages() )
                <div class="card-footer pb-0">
                    {{ $goods->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>
@endsection
