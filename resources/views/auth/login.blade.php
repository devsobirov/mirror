@extends('admin.layouts.guest')

@section('content')
    <form class="card card-md" action="{{ route('login') }}" method="post" autocomplete="off">
        @csrf

        <div class="card-body">
            <h2 class="card-title text-center mb-4">Tizimga kirish</h2>

            <div class="mb-3">
                <label class="form-label">Telefon</label>
                <div class="input-group">
                    <button type="button" class="btn">+998 </button>
                    <input
                        type="text"
                        name="phone"
                        value="{{ old('email', '') }}"
                        class="form-control @error('phone') is-invalid @enderror"
                        data-mask="(00) 000-00-00" data-mask-visible="true"
                        placeholder="(99) 000-00-00" autocomplete="off"
                        required
                        autofocus tabindex="1"
                    >
                </div>
                @error('phone')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3" x-data="passwordInput">
                <label class="form-label">Parol</label>
                <div class="input-group input-group-flat">
                    <input :type="getInputType()" name="password" value="" class="form-control @error('password') is-invalid @enderror" placeholder="Parolingizni kiriting" required tabindex="2">
                    <span class="input-group-text">
                        <a href="#" @click.prevent="toggle()" x-cloak x-show="!showPassword" class="link-secondary" data-bs-toggle="tooltip" aria-label="Parolni ko'rsatish" data-bs-original-title="Parolni ko'rsatish">
                            <x-svg.eye></x-svg.eye>
                        </a>
                        <a href="#" type="button" @click.prevent="toggle()" x-cloak x-show="showPassword" class="link-secondary" data-bs-toggle="tooltip" aria-label="Parolni berkitish" data-bs-original-title="Parolni berkitish">
                            <x-svg.lock></x-svg.lock>
                        </a>
                    </span>
                </div>
                @error('password')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div>
                <label class="form-check">
                    <input type="checkbox" class="form-check-input" tabindex="3" name="remember" />
                    <span class="form-check-label">Ushbu qurilmada eslab qolish</span>
                </label>
            </div>

            <div>
                <a href="{{ route('password.request') }}" class="text-right">
                    Parolni qayta tiklash
                </a>
            </div>

            <div class="form-footer">
                <button type="submit" class="btn btn-primary w-100" tabindex="4">Kirish</button>
            </div>
        </div>
    </form>

@endsection
