<div class="modal modal-blur fade" id="modal-suppliers-{{$item->id}}" tabindex="-1" role="dialog" aria-hidden="true">
    <form class="modal-dialog modal-lg modal-dialog-centered" role="document"
          action="{{route('admin.suppliers.save', ['supplier' => $item])}}" method="POST">
        @csrf
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    @if($item->id))
                        {{$item->name}} - tahrirlash
                    @else
                        Yangi yetkazib beruvchi kiritish
                    @endif
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row mb-3">
                    <div class="col-lg-12">
                        <label class="form-label">Ismi / Korxona nomi</label>
                        <input type="text" class="form-control" name="name" required minlength="3" value="{{$item->name}}">
                    </div>
                </div>
                <div class="mb-3 row">
                    <div class="mx-auto w-100">
                        <div class="mb-3">
                            <label class="form-check form-switch">
                                <input @if($item->active === null || $item->active) checked @endif name="active" value="1" class="form-check-input" type="checkbox">
                                <span class="form-check-label">Aktiv</span>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-6">
                        <div class="mb-3">
                            <label class="form-label">Tel. raqam (ixtiyoriy)</label>
                            <div class="input-group">
                                <button class="btn" type="button">+998 </button>
                                <input type="text" name="phone" value="{{$item->phone}}" class="form-control" data-mask="(00) 000-00-00" data-mask-visible="true" placeholder="(99) 000-00-00" autocomplete="off">
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="mb-3">
                            <label class="form-label">Qo'shimcha tel. raqam (ixtiyoriy)</label>
                            <div class="input-group">
                                <button class="btn" type="button">+998 </button>
                                <input type="text" name="phone2" value="{{$item->phone2}}" class="form-control" data-mask="(00) 000-00-00" data-mask-visible="true" placeholder="(99) 000-00-00" autocomplete="off">
                            </div>
                        </div>
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
