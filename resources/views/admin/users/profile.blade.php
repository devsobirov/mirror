@extends('admin.layouts.app')

@section('content')
    <div class="container-xl">
        <!-- Page title -->
        <div class="page-header d-print-none">
            <div class="row align-items-center">
                <div class="col">
                <!-- Page pre-title -->
                <div class="page-pretitle">
                    {{ config('app.name') }}
                </div>
                    <h2 class="page-title">
                        Mening ma'lumotlarim
                    </h2>
                </div>
            </div>
        </div>
    </div>
    <div class="page-body">
        <div class="container-xl">

            @if ($message = Session::get('success'))
            <div class="alert alert-success alert-dismissible">
                <div class="d-flex">
                    <div>
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon alert-icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                            <path d="M5 12l5 5l10 -10"></path>
                        </svg>
                    </div>
                    <div>
                        {{ $message }}
                    </div>
                  </div>
                <a class="btn-close" data-bs-dismiss="alert" aria-label="close"></a>
            </div>
            @endif

            <form action="{{ route('profile.update') }}" method="POST" class="card" autocomplete="off">
                @csrf
                @method('PUT')

                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label required">Ism Familiya</label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" placeholder="Имя" value="{{ old('name', auth()->user()->name) }}" required>
                    </div>
                    @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror

                    <div class="row">
                        <div class="col-lg-6 mb-3">
                            <label class="form-label required">Login (telefon)</label>
                            <div class="input-group">
                                <button class="btn" type="button">+998 </button>
                                <input type="text" name="phone"
                                       value="{{ old('phone', auth()->user()->phone) }}"
                                       class="form-control @error('phone') is-invalid @enderror"
                                       data-mask="(00) 000-00-00" data-mask-visible="true"
                                       placeholder="(99) 000-00-00" autocomplete="off"
                                       required
                                >
                            </div>
                            @error('phone')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-lg-6 mb-3">
                            <label class="form-label">Email (parolni qayta tiklash uchun)</label>
                            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" placeholder="user@gmail.com" value="{{ old('email', auth()->user()->email) }}">
                            @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="row mb-3" x-data="passwordInput">
                        <div class="col-lg-6">
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

                        <div class="col-lg-6">
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
                </div>

                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">Saqlash</button>
                </div>

            </form>

        </div>
    </div>

@endsection
