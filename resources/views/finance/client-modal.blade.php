<div class="modal modal-blur fade" id="client-{{$item->id}}" tabindex="-1" role="dialog" aria-hidden="true">
    <form action="{{route('finance.clients.save', ['client' => $item->id])}}"
          class="modal-dialog modal-lg modal-dialog-centered"
          method="POST" role="document"
    >
        @csrf
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    @if($item->id)
                        {{$item->name}}ni tahrirlash
                    @else
                        Mijoz qo'shish
                    @endif
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="mb-3">
                            <label class="form-label">Mijoz FIO</label>
                            <input type="text" name="name" value="{{$item->name}}" class="form-control" placeholder="Mijozning ism familiyasi yoki Korxona nomi">
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="mb-3">
                            <label class="form-label">Telefon raqami</label>
                             <div class="input-group">
                                 <button type="button" class="btn">+998</button>
                                <input type="text" name="phone" value="{{$item->phone_number}}" class="form-control" data-mask="(00) 000-00-00" data-mask-visible="true" placeholder="(99) 000-00-00" autocomplete="off">
                             </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="mb-3">
                            <label class="form-label">Qo'shimcha raqam</label>
                             <div class="input-group">
                                 <button type="button" class="btn">+998</button>
                                <input type="text" name="phone2" value="{{$item->phone_number2}}" class="form-control" data-mask="(00) 000-00-00" data-mask-visible="true" placeholder="(99) 000-00-00" autocomplete="off">
                             </div>
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div class="mb-3">
                            <label class="form-label">Manzil</label>
                            <input type="text" name="address" value="{{$item->address}}" class="form-control" placeholder="Mijoz manzili...">
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
