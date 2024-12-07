@extends('admin.layouts.guest')

@section('content')
    <form class="card card-md" action="{{ route('password.update') }}" method="post" autocomplete="off">
        @csrf

        <input type="hidden" name="token" value="{{ $token }}">

        <div class="card-body">
            <h2 class="card-title text-center mb-4">Parolni yangilash</h2>

            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" name="email" value="{{ old('email', request('email')) }}" class="form-control @error('email') is-invalid @enderror" placeholder="user@email.com">
                @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3" x-data="passwordInput">
                <div class="col-lg-12">
                    <label class="form-label required">Yangi parol</label>
                    <div class="input-group input-group-flat">
                        <input :type="getInputType()" name="password" minlength="6" value="" class="form-control @error('password') is-invalid @enderror" placeholder="Yangi parolni kiriting">
                        <span class="input-group-text">
                                <a href="#" @click.prevent="toggle()" x-cloak x-show="!showPassword" class="link-secondary" data-bs-toggle="tooltip" aria-label="Parolni ko'rsatish" data-bs-original-title="Parolni ko'rsatish">
                                    <x-svg.eye></x-svg.eye>
                                </a>
                                <a href="#" type="button" @click.prevent="toggle()" x-cloak x-show="showPassword" class="link-secondary" data-bs-toggle="tooltip" aria-label="Parolni berkitish" data-bs-original-title="Parolni berkitish">
                                    <x-svg.lock></x-svg.lock>
                                </a>
                            </span>
                    </div>
                </div>
                @error('password')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror

                <div class="col-lg-12">
                    <label class="form-label required">Yangi parolni takrorlang</label>
                    <div class="input-group input-group-flat">
                        <input :type="getInputType()" name="password_confirmation" minlength="6" class="form-control @error('password_confirmation') is-invalid @enderror" placeholder="Yangi parolni takrorlang" autocomplete="new-password">
                        <span class="input-group-text">
                            <a href="#" @click.prevent="toggle()" x-cloak x-show="!showPassword" class="link-secondary" data-bs-toggle="tooltip" aria-label="Parolni ko'rsatish" data-bs-original-title="Parolni ko'rsatish">
                                <x-svg.eye></x-svg.eye>
                            </a>
                            <a href="#" type="button" @click.prevent="toggle()" x-cloak x-show="showPassword" class="link-secondary" data-bs-toggle="tooltip" aria-label="Parolni berkitish" data-bs-original-title="Parolni berkitish">
                                <x-svg.lock></x-svg.lock>
                            </a>
                        </span>
                    </div>
                </div>
                @error('password_confirmation')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-footer">
                <button type="submit" class="btn btn-primary w-100">
                    Parolni yangilash
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
