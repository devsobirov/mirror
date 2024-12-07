@extends('admin.layouts.app')
@section('content')
    <div class="container-xl">
        <!-- Page title -->
        <div class="page-header d-print-none">
            <div class="d-flex justify-content-between align-items-center">
                <div class="col">
                    <!-- Page pre-title -->
                    <div class="page-pretitle">
                        Kirim
                    </div>
                    <h2 class="page-title">
                        #{{$balance->id}} - {{$balance->good?->full_title}}
                    </h2>
                </div>
            </div>
        </div>
    </div>
    <div class="page-body">
        <div class="container-xl">

            <form action="{{route('sklad.incomes.update', ['balance' => $balance->id])}}" method="POST" class="card">
                @csrf
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-lg-6">
                            <label class="form-label">Tovar</label>
                            <div class="input-group">
                                <a data-fslightbox href="{{$balance->good?->image_url}}">
                                    <span class="avatar" style="background-image: url({{$balance->good?->image_url}})"></span>
                                </a>
                                <input type="text" class="form-control" disabled value="{{$balance->good?->full_title}}">
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <label class="form-label">Miqdori (шт)</label>
                            <input type="number" name="amount" class="form-control" min="0" value="{{$balance->amount}}" required>
                        </div>
                        <div class="col-lg-3">
                            <label class="form-label">Narx (summa - USD)</label>
                            <div class="input-group">
                                <input type="number" name="price_usd" class="form-control" min="0" value="{{$balance->price_usd}}">
                                <button type="button" class="btn">USD</button>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-6">
                            <div class="mb-3">
                                <label class="form-label">Sana</label>
                                <input type="date" name="created_at" class="form-control" value="{{$balance->created_at?->format('Y-m-d') ?? now()->format('Y-m-d')}}" required>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="mb-3">
                                <label class="form-label">Yetkazib beruvchi</label>
                                <select name="supplier_id" class="form-select">
                                    <option value="">-</option>
                                    @foreach($suppliers as $supplier)
                                        <option value="{{$supplier->id}}" @selected($supplier->id === $balance->supplier_id)>{{$supplier->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <label class="form-label">Skladni tanlang</label>
                    <div class="form-selectgroup-boxes row mb-3">
                        @foreach($warehouses as $skladId => $sklad)
                            <div class="col-lg-4 mb-2">
                                <label class="form-selectgroup-item">
                                    <input type="radio" name="warehouse_id" @checked($skladId === $balance->warehouse_id) value="{{$skladId}}" class="form-selectgroup-input" @checked(count($warehouses) === 1) required>
                                    <span class="form-selectgroup-label d-flex align-items-center p-3">
                                <span class="me-2">
                                  <span class="form-selectgroup-check"></span>
                                </span>
                                <span class="form-selectgroup-label-content">
                                  <span class="form-selectgroup-title strong mb-1">{{$sklad}}</span>
                                </span>
                              </span>
                                </label>
                            </div>
                        @endforeach
                    </div>
                    <div class="col-lg-12 mb-3">
                        <div>
                            <label class="form-label">Izoh</label>
                            <textarea class="form-control" rows="3" name="description" maxlength="255">{{$balance->description}}</textarea>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary ms-auto">
                        <!-- Download SVG icon from http://tabler-icons.io/i/plus -->
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><path d="M12 5l0 14"></path><path d="M5 12l14 0"></path></svg>
                        Saqlash
                    </button>
                </div>
            </form>

        </div>
    </div>
@endsection
