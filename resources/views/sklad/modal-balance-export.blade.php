<div class="modal modal-blur fade" id="modal-balance-export" tabindex="-1" role="dialog" aria-hidden="true">
    <form class="modal-dialog modal-lg modal-dialog-centered" role="document" action="{{route('sklad.export')}}">
        @csrf
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Sklad balansini excelga yuklash</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row mb-3">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Fayl nomini kiriting</label>
                        <input type="text" class="form-control" name="filename" value="sklad-{{now()->format('d-M-Y')}}" required placeholder="kirim-31-dekabr">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Skladni tanlang (agar zarur bo'lsa)</label>
                        <select class="form-select mr-2" name="export_warehouse_id">
                            <option value="">Barcha skladlar</option>
                            @foreach($warehouses as $id => $name)
                                <option value="{{$id}}">{{$name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="" class="form-label">Kategoriyani tanlang (agar zarur bo'lsa)</label>
                        <select class="form-select mr-2" name="export_category_id">
                            <option value="">Barcha kategoriyalar</option>
                            @foreach($categories as $category)
                                <option value="{{$category->id}}">{{$category->title}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <a href="#" class="btn btn-link link-secondary" data-bs-dismiss="modal">
                    Bekor qilish
                </a>
                <button type="submit" class="btn btn-green ms-auto">
                    <x-svg.download></x-svg.download> <span class="ms-2">Yuklab olish</span>
                </button>
            </div>
        </div>
    </form>
</div>
