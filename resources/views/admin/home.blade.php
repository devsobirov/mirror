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
                <div class="card p-3 mb-3">Welcome, {{auth()->user()->name}}</div>

                <div class="row">
                    <div class="col-lg-8">
                        <div class="card">
                            <h3 class="p-3 mb-0">Main block</h3>
                            <div class="table-responsive">

                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4">
                        <div class="card">
                            <h3 class="p-3 mb-0">Side-block</h3>
                            <div class="table-responsive">

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
