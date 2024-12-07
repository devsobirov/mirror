@extends('admin.layouts.app')

@section('content')
    <div class="container-xl">
        <!-- Page title -->
        <div class="page-header d-print-none">
            <h2 class="page-title">
                Kirim tarixi ({{$items->total()}})
            </h2>
            <div class="col-auto ms-auto d-print-none">
                <form action="{{route('sklad.incomes.index')}}" class="d-md-flex d-sm-block align-items-center gap-1">
                    <input type="date" class="form-control" name="from" value="{{request('from')}}" min="{{now()->startOfYear()->format('Y-m-d')}}">
                    <input type="date" class="form-control" name="to" value="{{request('to')}}" max="{{now()->format('Y-m-d')}}">
                    <select class="form-select mr-2" name="supplier_id">
                        <option value="">Barcha yetkazib beruvchilar</option>
                        @foreach($suppliers as $supplier)
                            <option value="{{$supplier->id}}" @if(request('supplier_id') == $supplier->id) selected @endif>{{$supplier->name}}</option>
                        @endforeach
                    </select>
                    <select class="form-select mr-2" name="warehouse_id">
                        <option value="">Barcha skladlar</option>
                        @foreach($warehouses as $id => $name)
                            <option value="{{$id}}" @if(request('warehouse_id') == $id) selected @endif>{{$name}}</option>
                        @endforeach
                    </select>
                    <select class="form-select mr-2" name="category_id">
                        <option value="">Barcha kategoriyalar</option>
                        @foreach($categories as $category)
                            <option value="{{$category->id}}" @if(request('category_id') == $category->id) selected @endif>{{$category->title}}</option>
                        @endforeach
                    </select>
                    <input type="search" class="form-control d-inline-block w-12 me-1" minlength="2" placeholder="Tovar nomi ..." name="search" value="{{request('search')}}">
                    <button class="btn me-1 btn-icon btn-azure" title="Izlash"><x-svg.search></x-svg.search></button>
                    @if(auth()->user()->isAdmin())
                        <button type="button" class="btn me-1 btn-icon btn-green" data-bs-toggle="modal" data-bs-target="#modal-income-export">
                            <x-svg.excel></x-svg.excel>
                        </button>
                    @endif
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
                            <th>Tovar</th>
                            <th>Sana</th>
                            <th>Miqdor (dona)</th>
                            @if(auth()->user()->isAdmin())
                                <th>Narx (summa/dona - USD)</th>
                            @endif
                            <th>Sklad</th>
                            <th>Yetkazib beruvchi</th>
                            <th>Xodim</th>
                            @if(auth()->user()->isAdmin())
                                <th></th>
                            @endif
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($items as $item)
                            <tr>
                                <td data-balance-id="{{$item->id}}">
                                    <div class="d-flex align-items-center gap-2">
                                        <a data-fslightbox href="{{$item->good?->image_url}}">
                                            <span class="avatar" style="background-image: url({{$item->good?->image_url}})"></span>
                                        </a>
                                        <a href="{{route('sklad.good', ['good' => $item->good?->id ?? 0])}}">
                                            <p style="font-weight: 500" class="m-0">{{ $item->good?->full_title }}</p>
                                        </a>
                                    </div>
                                </td>
                                <td>
                                    <span class="text-secondary"><x-svg.calendar></x-svg.calendar></span>
                                    {{ $item->created_at?->format('d M-Y H:i') ?? '-' }}
                                </td>
                                <td>
                                    <span class="status status-green">
                                      <span class="status-dot status-dot-animated"></span>
                                      {{ $item->amount }} dona
                                    </span>
                                </td>
                                @if(auth()->user()->isAdmin())
                                    <td>
                                        @if($item->price_usd && $item->amount)
                                            $ {{($item->price_usd)}} / $ {{round($item->price_usd/$item->amount, 1)}}
                                        @else
                                            -
                                        @endif
                                    </td>
                                @endif
                                <td>{{ $item->warehouse?->name ?? '-' }}</td>
                                <td>{{$item->supplier?->name ?? '-'}}</td>
                                <td>{{$item->user?->name ?? '-'}}</td>
                                @if(auth()->user()->isAdmin())
                                    <td>
                                        <div class="d-flex gap-1">
                                            <a class="btn btn-icon btn-success" href="{{route('sklad.incomes.edit', ['balance' => $item->id])}}">
                                                <x-svg.pen></x-svg.pen>
                                            </a>
                                            <form
                                                action="{{route('sklad.incomes.delete', ['balance' => $item->id])}}"
                                                method="POST"
                                                onsubmit="return confirm('Ma\'lumotlar o\'chiriladi va qayta tiklab bo\'lmaydi, davom etilsinmi?')"
                                            >
                                                @csrf @method('DELETE')
                                                <button class="btn btn-icon btn-danger">
                                                    <x-svg.trash></x-svg.trash>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                @endif
                            </tr>
                        @endforeach
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
    @if(auth()->user()->isAdmin())
        @include('sklad.modal-income-export')
    @endif
@endsection
