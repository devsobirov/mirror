<div class="modal modal-blur fade" id="modal-expense-{{$good->id}}" tabindex="-1" role="dialog" aria-hidden="true">
    <form class="modal-dialog modal-lg modal-dialog-centered" role="document"
          x-data="expenseModalData()"
          x-init="
            setWarehouses({{$good->balanceStat}});
            initDropzone('dropzone-expense-{{$good->id}}')"
          action="{{route('sklad.expenses.expense')}}" method="POST"
          enctype="multipart/form-data"
    >
        @csrf
        <div class="modal-content">
            <input type="hidden" name="good_id" value="{{$good->id}}">
            <div class="modal-header">
                <h5 class="modal-title">Chiqim - {{$good->full_title}}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <label class="form-label">Skladni tanlang</label>
                <div class="form-selectgroup-boxes row mb-3">
                    <!-- A section to select warehouse (radio buttons) -->
                    <template x-for="wItem in ws" :key="wItem.id">
                        <div class="col-lg-6" x-show="wItem.amount > 0" x-cloak>
                            <label class="form-selectgroup-item">
                                <input
                                    type="radio"
                                    name="warehouse_id"
                                    :value="wItem.id"
                                    class="form-selectgroup-input disabled"
                                    x-model="warehouse_id"
                                    @change="setMaxAmount"
                                    :disabled="wItem.amount <= 0"
                                    required
                                >
                                <span class="form-selectgroup-label d-flex align-items-center p-3">
                        <span class="me-3">
                            <span class="form-selectgroup-check"></span>
                        </span>
                        <span class="form-selectgroup-label-content">
                            <span class="form-selectgroup-title strong mb-1" x-text="`${wItem.name} (${wItem.amount} dona)`"></span>
                        </span>
                    </span>
                            </label>
                        </div>
                    </template>
                </div>

                <div class="row mb-3">
                    <div class="{{$isAdmin ? 'col-md-6' : 'col-lg-8'}}">
                        <label class="form-label">Tovar</label>
                        <input type="text" class="form-control" readonly value="{{$good->full_title}}">
                    </div>
                    <div class="{{$isAdmin ? 'col-md-3' : 'col-lg-4'}}">
                        <label class="form-label">Miqdori (dona)</label>
                        <input type="number" name="amount"
                               :placeholder="warehouse_id ? (maxAmount + ' ta mavjud') : 'Oldin skladni tanlang'"
                               class="form-control"
                               min="1"
                               :max="maxAmount"
                               required
                               :disabled="!warehouse_id"
                        >
                    </div>
                    @if($isAdmin)
                    <div class="col-md-3">
                        <label class="form-label">Narx (summa - USD)</label>
                        <div class="input-group">
                            <input type="number" name="price_usd" class="form-control" min="0" value="0">
                            <button type="button" class="btn">USD</button>
                        </div>
                    </div>
                    @endif
                </div>

                <div class="row">
                    <div class="col-lg-6">
                        <div class="mb-3">
                            <label class="form-label">Sana</label>
                            <input type="date" name="created_at" class="form-control" value="{{now()->format('Y-m-d')}}" required>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="mb-3">
                            <label class="form-label">Mijoz FIO</label>
                            <input type="text" name="client_name" class="form-control" placeholder="Mijozning ism familiyasi yoki Korxona nomi">
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="mb-3">
                            <label class="form-label">Mijoz raqami</label>
                            <input type="text" name="phone" class="form-control" data-mask="(00) 000-00-00" data-mask-visible="true" placeholder="(99) 000-00-00" autocomplete="off">
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="mb-3">
                            <label class="form-label">Mijoz raqami (qo'shimcha)</label>
                            <input type="text" name="phone2" class="form-control" data-mask="(00) 000-00-00" data-mask-visible="true" placeholder="(99) 000-00-00" autocomplete="off">
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div class="mb-3">
                            <label class="form-label">Mijoz manzili</label>
                            <input type="text" name="address" class="form-control" placeholder="Mijoz manzili...">
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <h3 class="card-title">Rasmlar bo'lsa yuklang</h3>
                                <div class="dropzone" id="dropzone-expense-{{$good->id}}">
                                    <div class="fallback">
                                        <input  type="file"  multiple  />
                                    </div>
                                </div>
                            </div>
                        </div>
                        <input class="visually-hidden" name="client_images[]" id="dropzone-expense-{{$good->id}}-i" type="file"  multiple  />
                    </div>
                </div>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-lg-12">
                        <div>
                            <label class="form-label">Izoh</label>
                            <textarea class="form-control" rows="2" name="description" maxlength="255"></textarea>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <a href="#" class="btn btn-link link-secondary" data-bs-dismiss="modal">
                    Bekor qilish
                </a>
                <button class="btn btn-primary ms-auto">
                    <!-- Download SVG icon from http://tabler-icons.io/i/plus -->
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><path d="M12 5l0 14"></path><path d="M5 12l14 0"></path></svg>
                    Saqlash
                </button>
            </div>
        </div>
    </form>
</div>
