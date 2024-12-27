@extends('admin.layouts.app')

@section('content')
    <div class="container-xl">
        <!-- Page title -->
        <div class="page-header d-print-none">
            <h2 class="page-title">
                Tovarlar ({{count($items)}})
            </h2>
            <div class="col-auto ms-auto d-print-none">
                <form action="{{route('admin.services.index')}}" class="d-md-flex d-sm-block align-items-center">
                    <select class="form-select mr-2" name="category_id">
                        <option value="">Xizmat turi</option>
                        @foreach($categories as $category)
                            <option value="{{$category->id}}" @if(request('category_id') == $category->id) selected @endif>{{$category->title}}</option>
                        @endforeach
                    </select>
                    <input type="search" class="form-control d-inline-block w-12 me-1" minlength="2" placeholder="tarif nomi ..." name="search" value="{{request('search')}}">
                    <button class="btn mx-1 btn-icon btn-azure" title="Izlash"><x-svg.search></x-svg.search></button>
                    <a href="{{route('admin.services.form')}}" class="btn btn-primary">Yangi tarif yaratish</a>
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
                            <th>Birlik</th>
                            <th>Narx - UZS</th>
                            <th>Ustama - %</th>
                            <th>Holati</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($items as $item)
                            <tr>
                                <td>{{ $item->id }}</td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <a data-fslightbox href="{{$item->image_url}}">
                                            <span class="avatar" style="background-image: url({{$item->image_url}})"></span>
                                        </a>
                                        <p style="font-weight: 500" class="m-0">{{ $item->full_title }}</p>
                                    </div>
                                </td>
                                <td>{{$item->unit}}</td>
                                <td>{{$item->price}} UZS</td>
                                <td>{{$item->margin}} %</td>
                                <td>
                                    @if($item->active)
                                        <span class="badge bg-green text-green-fg">Aktiv</span>
                                    @else
                                        <span class="badge bg-red text-red-fg">O'chirilgan</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{route('admin.services.form', ['service' => $item->id])}}" class="btn btn-icon btn-success">
                                        <x-svg.pen></x-svg.pen>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">Xizmat tariflari topilmadi</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
                @if( $items->hasPages() )
                    <div class="card-footer pb-0">
                        {{ $items->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
