@extends('admin.layouts.app')


@section('content')
    <div class="page-body">
        <div class="container-xl">

            <div class="page-header d-print-none mt-5">
                <div class="row g-2 align-items-center">
                    <div class="col">
                        <h2 class="page-title">Bosh panel</h2>
                    </div>
                </div>
            </div>

            <div class="page-body">
                <div class="card">
                    <div class="card p-3">Welcome, {{auth()->user()->name}}</div>
                </div>
            </div>
        </div>
    </div>
@endsection
