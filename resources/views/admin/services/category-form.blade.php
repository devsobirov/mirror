<div class="modal modal-blur fade" id="modal-category-{{$item->id}}" tabindex="-1" role="dialog" aria-hidden="true">
    <form class="modal-dialog modal-lg modal-dialog-centered" role="document"
          action="{{route('admin.services.categories.save', ['serviceCategory' => $item->id])}}" method="POST">
        @csrf
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    @if($item->id)
                        {{$item->title}} - tahrirlash
                    @else
                        Yangi xizmat turi (kategoriya) kiritish
                    @endif
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row mb-3">
                    <div class="col-lg-12">
                        <label class="form-label">Nomi</label>
                        <input type="text" class="form-control" name="title" required minlength="3" value="{{$item->title}}">
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
