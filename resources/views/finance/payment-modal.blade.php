<div class="modal modal-blur fade" id="payment-{{$item->id}}" tabindex="-1" role="dialog" aria-hidden="true">
    <form action="{{route('finance.debts.pay', $item)}}"
          class="modal-dialog modal-lg modal-dialog-centered"
          method="POST" role="document"
    >
        @csrf
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    To'lov yaratish
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="mb-3" x-data="formatAmount()">
                            <label class="form-label">To'lov summasi</label>
                            <input type="number" 
                                max="{{$item->left_amount}}" 
                                placeholder="To'lov summasi {{number_format($item->left_amount, 0, ',', ' ')}} UZS gacha" 
                                name="amount" 
                                class="form-control"
                                x-model="amount">
                            <small class="amount-in-words form-text text-muted" x-text="amountInWords"></small>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="mb-3">
                            <label class="form-label">To'lov turi</label>
                            <select name="type" class="form-select">
                                <option value="" selected disabled>To'lov turini tanlash</option>
                                @foreach(\App\Enums\Finance\PaymentTypeEnum::list() as $key => $value)
                                    <option value="{{$key}}">{{$value}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="mb-3">
                            <label class="form-label">To'lov sanasi</label>
                            <input type="date" 
                                name="payment_date" 
                                value="{{date('Y-m-d')}}"
                                class="form-control">
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div class="mb-3">
                            <label class="form-label required">To'lov reytingi</label>
                            <div x-data="ratingData()">
                                <div class="form-selectgroup form-selectgroup-rating">
                                    <label class="form-selectgroup-item">
                                        <input type="radio" name="rating" value="1" class="form-selectgroup-input rating-input" required @click="setRating(1)">
                                        <span class="form-selectgroup-label rating-star" data-rating="1" :style="{ color: getColor(1) }">
                                            <x-svg.star></x-svg.star>
                                        </span>
                                    </label>
                                    <label class="form-selectgroup-item">
                                        <input type="radio" name="rating" value="2" class="form-selectgroup-input rating-input" required @click="setRating(2)">
                                        <span class="form-selectgroup-label rating-star" data-rating="2" :style="{ color: getColor(2) }">
                                            <x-svg.star></x-svg.star>
                                        </span>
                                    </label>
                                    <label class="form-selectgroup-item">
                                        <input type="radio" name="rating" value="3" class="form-selectgroup-input rating-input" required @click="setRating(3)">
                                        <span class="form-selectgroup-label rating-star" data-rating="3" :style="{ color: getColor(3) }">
                                            <x-svg.star></x-svg.star>
                                        </span>
                                    </label>
                                    <label class="form-selectgroup-item">
                                        <input type="radio" name="rating" value="4" class="form-selectgroup-input rating-input" required @click="setRating(4)">
                                        <span class="form-selectgroup-label rating-star" data-rating="4" :style="{ color: getColor(4) }">
                                            <x-svg.star></x-svg.star>   
                                        </span>
                                    </label>
                                    <label class="form-selectgroup-item">
                                        <input type="radio" name="rating" value="5" class="form-selectgroup-input rating-input" required @click="setRating(5)">
                                        <span class="form-selectgroup-label rating-star" data-rating="5" :style="{ color: getColor(5) }">
                                            <x-svg.star></x-svg.star>
                                        </span>
                                    </label>
                                </div>
                            </div>
                            <small class="form-hint">
                                To'lovni baholash uchun yulduzlarni tanlang. 1 - juda yomon (to'lov kechikkan), 5 - a'lo (o'z vaqtida to'langan)
                            </small>
                        </div>
                    </div>
                    <input type="hidden" name="client_id" value="{{$item->client_id}}">
                    <input type="hidden" name="debt_id" value="{{$item->id}}">
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
