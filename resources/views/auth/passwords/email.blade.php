@extends('admin.layouts.guest')

@section('content')

    @if (session('status'))
    <div class="alert alert-success" role="alert">
        {{ session('status') }}
    </div>
    @endif

    <form class="card card-md" action="{{ route('password.email') }}" method="post" autocomplete="off">
        @csrf

        <div class="card-body">
            <h2 class="card-title text-center mb-4">Parolni qayta tiklash</h2>

            <p class="text-muted mb-4">Parolni yangilash havolasini olish uchun tizimda saqlangan email-adresingizni kiriting</p>

            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" name="email" value="{{ old('email') }}" class="form-control form-control-user @error('email') is-invalid @enderror" placeholder="user@gmail.com">
                @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-footer">
                <button type="submit" class="btn btn-primary w-100">
                    Havolani yuborish
                </button>
            </div>
        </div>
    </form>

    @if (Route::has('login'))
    <div class="text-center text-muted mt-3">
        Parolni esladingizmi? <a href="{{ route('login') }}" tabindex="-1">Tizimga kirish</a>
    </div>
    @endif

@endsection
