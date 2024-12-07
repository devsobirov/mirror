<div class="modal modal-blur fade" id="modal-client-{{$client->id}}" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
        <div class="modal-content">
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            <div class="modal-status bg-success"></div>
            <div class="modal-body text-center py-4">
                <!-- Download SVG icon from http://tabler-icons.io/i/circle-check -->
                <x-svg.tie></x-svg.tie>
                <h3>{{$client->name}}</h3>
                <p>Tel: {{$client->phone_number ?? '-'}}</p>
                <p>Tel2: {{$client->phone_number2 ?? '-'}}</p>
                <p>Manzil: {{$client->address ?? '-'}}</p>
                <div class="d-flex justify-content-center gap-1">
                    @foreach($images as $img)
                        <a data-fslightbox="gallery-{{$client->id}}" href="{{asset($img->filename)}}">
                            <!-- Photo -->
                            <span class="mx-1"><x-svg.image></x-svg.image></span>
                        </a>
                    @endforeach
                </div>
            </div>
            <div class="modal-footer">
                <div class="w-100">
                    <div class="row">
                        <div class="col"><a href="#" class="btn w-100" data-bs-dismiss="modal">OK!</a></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
