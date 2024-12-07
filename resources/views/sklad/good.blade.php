@extends('admin.layouts.app')
@section('content')
    <div class="container-xl">
        <!-- Page title -->
        <div class="page-header d-print-none">
            <div class="d-flex justify-content-between align-items-center">
                <div class="col">
                    <!-- Page pre-title -->
                    <div class="page-pretitle">
                        {{$good->full_title}}
                    </div>
                    <h2 class="page-title d-flex align-items-center g-3 mt-2">
                        <a href="{{$good->image_url}}" data-fslightbox class="d-block px-2">
                            <img src="{{$good->image_url}}" alt="" style="width: 50px">
                        </a>
                        <span>#{{$good->id}} - {{$good->name}}</span>
                    </h2>
                </div>
                @if(auth()->user()->isAdmin())
                    <div class="d-flex gap-1 ms-auto">
                        <button type="button" class="btn me-1 d-flex gap-2 btn-green d-block" data-bs-toggle="modal" data-bs-target="#modal-income-export">
                            <span>Kirim tarixini yuklash</span> <x-svg.excel></x-svg.excel>
                        </button>
                        <button type="button" class="btn me-1 bd-flex gap-2 btn-danger d-block" data-bs-toggle="modal" data-bs-target="#modal-expense-export">
                            <span>Chiqim tarixini yuklash</span> <x-svg.excel></x-svg.excel>
                        </button>
                    </div>
                @endif
            </div>
        </div>
    </div>
    <div class="page-body">
        <div class="container-xl">

            <h3 class="card-title">Kirim/Chiqim</h3>
            <div class="card mb-4">
                <div class="table-responsive">
                    <div class="card-table table-responsive">
                        <table class="table table-vcenter">
                            <thead>
                                <tr>
                                @foreach($fullStat as $yearlyStat)
                                    <th>Qoldiq ({{$yearlyStat->year}})</th>
                                    <th>Kirim ({{$yearlyStat->year}})</th>
                                    <th>Chiqim ({{$yearlyStat->year}})</th>
                                @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                @foreach($fullStat as $yearlyStat)
                                    <td>{{$balanceStat->where('year', $yearlyStat->year)->first()->amount ?? 0}} dona</td>
                                    <td>{{$yearlyStat->income_sum}} dona</td>
                                    <td>{{$yearlyStat->expense_sum}} dona</td>
                                @endforeach
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            @if(auth()->user()->isAdmin())
                <h3 class="card-title">Narx</h3>
                <div class="card mb-4">
                    <div class="table-responsive">
                        <div class="card-table table-responsive">
                            <table class="table table-vcenter">
                                <thead>
                                <tr>
                                    @foreach($fullStat as $yearlyStat)
                                        <th>Profit - USD ({{$yearlyStat->year}})</th>
                                        <th>Kirim - summa USD ({{$yearlyStat->year}})</th>
                                        <th>Kirim - dona USD ({{$yearlyStat->year}})</th>
                                        <th>Chiqim - summa USD ({{$yearlyStat->year}})</th>
                                        <th>Chiqim - dona USD ({{$yearlyStat->year}})</th>
                                    @endforeach
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    @foreach($fullStat as $yearlyStat)
                                        @php
                                        $in = $yearlyStat->income_price;
                                        $out = $yearlyStat->expense_price;
                                        $countIn = $yearlyStat->income_price_amount ?: 1;
                                        $countOut = $yearlyStat->expense_price_amount ?: 1;
                                        @endphp
                                        <td>${{$out - $in}}</td>
                                        <td>${{$in}}</td>
                                        <td>${{round($in/$countIn)}}</td>
                                        <td>${{$out}}</td>
                                        <td>${{round($out/$countOut)}}</td>
                                    @endforeach
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
    @include('sklad.modal-income-export-good', ['good' => $good])
    @include('sklad.modal-expense-export-good', ['good' => $good])
@endsection
