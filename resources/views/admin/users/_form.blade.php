<div class="modal modal-blur fade" id="user-form-{{$item->id}}" tabindex="-1" aria-modal="true" role="dialog">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <form action="{{route('admin.users.save', $item->id)}}" class="modal-content" method="POST">
            @csrf
            <input type="hidden" name="customer_id" value="{{$item->id}}">
            <div class="modal-header">
                <h5 class="modal-title">{{$item->name ?: "Yangi hodim kiritish"}}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label required">Ism</label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                               placeholder="Ism Familiya" value="{{ old('name', $item->name) }}" required>
                        @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label required">Login (telefon)</label>
                        <div class="input-group">
                            <button class="btn" type="button">+998</button>
                            <input type="text" name="phone"
                                   value="{{ old('phone', $item->phone) }}"
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
                </div>

                @if($item->isAdmin())
                    <div class="mb-3">
                        <label class="form-label">Роль</label>
                        <select type="text" name="role" class="form-select" required>
                            <option selected value="{{\App\Models\User::ADMIN}}">Admin</option>
                        </select>
                    </div>
                @else
                    <div class="row">
                        <div class="mb-3 col-lg-6">
                            <label class="form-label">Роль</label>
                            <select type="text" name="role" class="form-select">
                                <option value="">Maqomsiz</option>
                                <option
                                    value="{{\App\Models\User::WORKER}}"
                                    @if($item->role == \App\Models\User::WORKER) selected @endif
                                >Xodim
                                </option>
                            </select>
                        </div>
                        @endif
                        <div class="mb-3 col-lg-6">
                            <label class="form-label required">Email (parolni qayta tiklash uchun)</label>
                            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                                   placeholder="user@gmail.com" value="{{ old('email', $item->email) }}">
                            @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="row mb-3" x-data="passwordInput">
                        <div class="col-lg-6">
                            <label class="form-label required">Yangi parol</label>
                            <div class="input-group input-group-flat">
                                <input :type="getInputType()" name="password" minlength="6" value=""
                                       class="form-control @error('password') is-invalid @enderror"
                                       placeholder="Yangi parol" @if(!$item->id) required @endif>
                                <span class="input-group-text">
                                <a href="#" @click.prevent="toggle()" x-cloak x-show="!showPassword"
                                   class="link-secondary" data-bs-toggle="tooltip" aria-label="Parolni ko'rsatish"
                                   data-bs-original-title="Parolni ko'rsatish">
                                    <x-svg.eye></x-svg.eye>
                                </a>
                                <a href="#" type="button" @click.prevent="toggle()" x-cloak x-show="showPassword"
                                   class="link-secondary" data-bs-toggle="tooltip" aria-label="Parolni berkitish"
                                   data-bs-original-title="Parolni berkitish">
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
                                <input :type="getInputType()" name="password_confirmation" minlength="6"
                                       class="form-control @error('password_confirmation') is-invalid @enderror"
                                       placeholder="Yangi parolni takrorlang" autocomplete="new-password"
                                       @if(!$item->id) required @endif>
                                <span class="input-group-text">
                                <a href="#" @click.prevent="toggle()" x-cloak x-show="!showPassword"
                                   class="link-secondary" data-bs-toggle="tooltip" aria-label="Parolni ko'rsatish"
                                   data-bs-original-title="Parolni ko'rsatish">
                                    <x-svg.eye></x-svg.eye>
                                </a>
                                <a href="#" type="button" @click.prevent="toggle()" x-cloak x-show="showPassword"
                                   class="link-secondary" data-bs-toggle="tooltip" aria-label="Parolni berkitish"
                                   data-bs-original-title="Parolni berkitish">
                                    <x-svg.lock></x-svg.lock>
                                </a>
                            </span>
                            </div>
                        </div>
                        @error('password_confirmation')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    @if($item->isWorker() && !$item->isAdmin())
                        <div class="row">
                            @foreach($warehouses as $warehouse)
                                <h4 class="col-md-12 mb-3">{{$warehouse->name}}</h4>
                                <div class="col-md-6 mb-2">
                                    <label class="form-check form-switch">
                                        <input
                                            name="warehouses[{{$warehouse->id}}][]"
                                            @checked($item->warehouses->where('id', $warehouse->id)->where('pivot.action', App\Models\Warehouse\Warehouse::ACTION_IN)->first())
                                            value="{{ App\Models\Warehouse\Warehouse::ACTION_IN }}"
                                            class="form-check-input" type="checkbox"
                                        >
                                        <span class="form-check-label">Kirim uchun ruxsat</span>
                                    </label>
                                </div>
                                <div class="col-md-6 mb-2">
                                    <label class="form-check form-switch">
                                        <input
                                            name="warehouses[{{$warehouse->id}}][]"
                                            @checked($item->warehouses->where('id', $warehouse->id)->where('pivot.action', App\Models\Warehouse\Warehouse::ACTION_OUT)->first())
                                            value="{{ App\Models\Warehouse\Warehouse::ACTION_OUT }}"
                                            class="form-check-input" type="checkbox"
                                        >
                                        <span class="form-check-label">Chiqim uchun ruxsat</span>
                                    </label>
                                </div>
                                @if(!$loop->last)
                                    <hr class="mb-3">
                                @endif
                            @endforeach
                        </div>
                    @endif
            </div>
            <div class="modal-footer">
                <a href="#" class="btn btn-link link-secondary" data-bs-dismiss="modal">
                    Bekor qilish
                </a>
                <button class="btn btn-primary ms-auto">
                    <!-- Download SVG icon from http://tabler-icons.io/i/plus -->
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24"
                         stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round"
                         stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                        <path d="M12 5l0 14"></path>
                        <path d="M5 12l14 0"></path>
                    </svg>
                    Saqlash
                </button>
            </div>
        </form>
    </div>
</div>
