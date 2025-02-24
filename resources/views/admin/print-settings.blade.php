@extends('admin.layouts.app')

@section('content')
    <div class="container-xl">
        <div class="page-header d-print-none">
            <h2 class="page-title">Chop etish sozlamalari</h2>
        </div>

        @if ($message = Session::get('success'))
            <div class="alert alert-success alert-dismissible">
                <div class="d-flex">
                    <div>
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon alert-icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                            <path d="M5 12l5 5l10 -10"></path>
                        </svg>
                    </div>
                    <div>{{ $message }}</div>
                </div>
                <a class="btn-close" data-bs-dismiss="alert" aria-label="close"></a>
            </div>
        @endif

        <form action="{{ route('admin.print-settings.save') }}" method="POST" class="card">
            @csrf
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label">Tashkilot</label>
                    <input type="text" name="organization" class="form-control" value="{{ old('organization', $printSetting->organization ?? '') }}">
                </div>

                <div class="mb-3">
                    <label class="form-label">Telefon 1</label>
                    <input type="text" name="phone1" class="form-control" value="{{ old('phone1', $printSetting->phone1 ?? '') }}">
                </div>

                <div class="mb-3">
                    <label class="form-label">Telefon 2</label>
                    <input type="text" name="phone2" class="form-control" value="{{ old('phone2', $printSetting->phone2 ?? '') }}">
                </div>

                <!-- <div class="mb-3">
                    <label class="form-label">Tur</label>
                    <input type="text" name="type" class="form-control" value="{{ old('type', $printSetting->type ?? '') }}">
                </div> -->

                <div class="mb-3">
                    <label class="form-label">Rahmat</label>
                    <textarea name="thanks" class="form-control">{{ old('thanks', $printSetting->thanks ?? '') }}</textarea>
                </div>
            </div>

            <div class="card-footer">
                <button type="submit" class="btn btn-primary">Saqlash</button>
            </div>
        </form>
    </div>
@endsection 