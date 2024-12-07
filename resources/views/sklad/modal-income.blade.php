<div class="modal modal-blur fade" id="modal-income-{{$good->id}}" tabindex="-1" role="dialog" aria-hidden="true">
    <form class="modal-dialog modal-lg modal-dialog-centered" role="document"
          action="{{route('sklad.incomes.income')}}" method="POST">
        @csrf
        <div class="modal-content">
            <input type="hidden" name="good_id" value="{{$good->id}}">
            <div class="modal-header">
                <h5 class="modal-title">Kirim - {{$good->full_title}}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row mb-3">
                    <div class="{{$isAdmin ? 'col-md-6' : 'col-lg-8'}}">
                        <label class="form-label">Tovar</label>
                        <input type="text" class="form-control" disabled value="{{$good->full_title}}">
                    </div>
                    <div class="{{$isAdmin ? 'col-md-3' : 'col-lg-4'}}">
                        <label class="form-label">Miqdori (dona)</label>
                        <input type="number" name="amount" class="form-control" min="1" required>
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
                            <label class="form-label">Yetkazib beruvchi</label>
                            <select name="supplier_id" class="form-select">
                                <option value="">-</option>
                                @foreach($suppliers as $supplier)
                                    <option value="{{$supplier->id}}">{{$supplier->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <label class="form-label">Skladni tanlang</label>
                <div class="form-selectgroup-boxes row mb-3">
                    @foreach($warehousesIn as $skladId => $sklad)
                        <div class="col-lg-6 mb-2">
                            <label class="form-selectgroup-item">
                                <input type="radio" name="warehouse_id" value="{{$skladId}}" class="form-selectgroup-input" @checked(count($warehouses) === 1) required>
                                <span class="form-selectgroup-label d-flex align-items-center p-3">
                                <span class="me-2">
                                  <span class="form-selectgroup-check"></span>
                                </span>
                                <span class="form-selectgroup-label-content">
                                  <span class="form-selectgroup-title strong mb-1">{{$sklad}}</span>
                                </span>
                              </span>
                            </label>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="modal-body">
                <div class="col-lg-12">
                    <div>
                        <label class="form-label">Izoh</label>
                        <textarea class="form-control" rows="3" name="description" maxlength="255"></textarea>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <a href="#" class="btn btn-link link-secondary" data-bs-dismiss="modal">
                    Bekor qilish
                </a>
                <button type="submit" class="btn btn-primary ms-auto">
                    <!-- Download SVG icon from http://tabler-icons.io/i/plus -->
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><path d="M12 5l0 14"></path><path d="M5 12l14 0"></path></svg>
                    Saqlash
                </button>
            </div>
        </div>
    </form>
</div>
