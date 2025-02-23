@extends('admin.layouts.app')
@section('custom_styles')
<style>
    .accordion-body {
        padding: 0;
    }
    .list-group {
        border-radius: 0;
    }
    .list-group-item {
        border-left: none;
        border-right: none;
    }
</style>
@endsection
@section('content')
    <div class="container-xl" x-data="OfferFormData">
        <!-- CSRF токен qo'shish -->
        <meta name="csrf-token" content="{{ csrf_token() }}">
        
        <form @submit.prevent="saveOffer">
            <div class="row mt-4">
                <!-- Xizmatlar ro'yxati uchun chap panel -->
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-0">Xizmatlar katalogi</h5>
                            <div class="ms-2 flex-grow-1" style="max-width: 200px;">
                                <input type="text" 
                                       class="form-control form-control-sm" 
                                       placeholder="Xizmatni qidirish..."
                                       x-model.debounce.300ms="searchQuery"
                                       @input="handleSearch">
                            </div>
                        </div>
                        <div class="card-body p-0">
                            <!-- Qidiruv natijalari bloki -->
                            <template x-if="isSearching">
                                <div class="p-3">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <h6 class="m-0">Qidiruv natijalari: <span class="text-muted" x-text="searchQuery"></span></h6>
                                        <button class="btn btn-sm btn-outline-secondary" @click="clearSearch">
                                            Qidiruvni tozalash
                                        </button>
                                    </div>
                                    
                                    <template x-if="searchResults.length === 0">
                                        <div class="text-center py-4 text-muted">
                                            <p>Qidiruvga "<span x-text="searchQuery"></span>" mos keladigan hech narsa topilmadi</p>
                                        </div>
                                    </template>
                                    
                                    <template x-if="searchResults.length > 0">
                                        <div class="list-group">
                                            <template x-for="service in searchResults" :key="service.id">
                                                <div class="list-group-item d-flex justify-content-between align-items-center">
                                                    <div class="d-flex align-items-center gap-2">
                                                        <a :data-fslightbox="service.id" 
                                                           :href="service.image"
                                                           x-init="refreshFsLightbox()">
                                                            <span class="avatar" 
                                                                  :style="'background-image: url(' + service.image + ')'">
                                                            </span>
                                                        </a>
                                                        <div>
                                                            <span x-text="service.name"></span>
                                                            <small class="text-muted d-block" x-text="'1 ' + service.unit + ' uchun'"></small>
                                                        </div>
                                                    </div>
                                                    <button class="btn btn-primary btn-sm d-flex align-items-center" 
                                                            @click.prevent="addToOrder(service)">
                                                        <span class="me-2" x-text="service.full_price + ' UZS'"></span>
                                                        <span class="d-flex">
                                                            <x-svg.plus class="icon"></x-svg.plus>
                                                        </span>
                                                    </button>
                                                </div>
                                            </template>
                                        </div>
                                    </template>
                                </div>
                            </template>

                            <!-- Asosiy katalog -->
                            <template x-if="!isSearching">
                                <div class="accordion" id="servicesAccordion">
                                    <template x-for="(category, index) in getAvailableCategories()" :key="category.id">
                                        <div class="accordion-item">
                                            <h2 class="accordion-header">
                                                <button class="accordion-button" type="button" 
                                                        data-bs-toggle="collapse" 
                                                        :data-bs-target="'#category' + category.id" 
                                                        data-bs-parent="">
                                                    <span x-text="category.name + ' (' + getAvailableServicesCount(category) + ')'"></span>
                                                </button>
                                            </h2>
                                            <div :id="'category' + category.id" 
                                                 class="accordion-collapse collapse" 
                                                 :class="{ 'show': index === 0 }">
                                                <div class="accordion-body">
                                                    <div class="list-group">
                                                        <template x-for="service in getAvailableServices(category)" :key="service.id">
                                                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                                                <div class="d-flex align-items-center gap-2">
                                                                    <a :data-fslightbox="service.id" 
                                                                       :href="service.image"
                                                                       x-init="refreshFsLightbox()">
                                                                        <span class="avatar" 
                                                                              :style="'background-image: url(' + service.image + ')'">
                                                                        </span>
                                                                    </a>
                                                                    <div>
                                                                        <span x-text="service.name"></span>
                                                                        <small class="text-muted d-block" x-text="'1 ' + service.unit + ' uchun'"></small>
                                                                    </div>
                                                                </div>
                                                                <button class="btn btn-primary btn-sm d-flex align-items-center" 
                                                                        @click.prevent="addToOrder(service)">
                                                                    <span class="me-2" x-text="service.full_price + ' UZS'"></span>
                                                                    <span class="d-flex">
                                                                        <x-svg.plus class="icon"></x-svg.plus>
                                                                    </span>
                                                                </button>
                                                            </div>
                                                        </template>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </template>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>

                <!-- Tanlangan xizmatlar bilan o'ng panel -->
                <div class="col-md-8">
                    <form @submit.prevent="saveOffer">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h5 class="card-title mb-0">Tanlangan xizmatlar</h5>
                                <button type="submit" 
                                        class="btn btn-primary" 
                                        :disabled="!isFormValid || isSubmitting">
                                    <span x-show="isSubmitting" class="spinner-border spinner-border-sm me-2" role="status"></span>
                                    Buyurtmani saqlash
                                </button>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <template x-if="offerItems.length === 0">
                                        <div class="text-center p-4 text-muted">
                                            <h3>Xizmatlar tanlanmagan</h3>
                                            <p>Chapdagi katalogdan xizmatlarni tanlang</p>
                                        </div>
                                    </template>
                                    <template x-if="offerItems.length > 0">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th style="width: 40px">#</th>
                                                    <th>Xizmat</th>
                                                    <th style="min-width: 80px">O'lcham</th>
                                                    <th>Miqdori</th>
                                                    <th>Narx</th>
                                                    <th>Summa</th>
                                                    <th style="width: 40px"></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <template x-for="(item, index) in offerItems" :key="item.inner_id">
                                                    <tr>
                                                        <td x-text="index + 1"></td>
                                                        <td>
                                                            <div class="d-flex align-items-center gap-2">
                                                                <a :data-fslightbox="'order' + item.id" 
                                                                   :href="item.image"
                                                                   x-init="refreshFsLightbox()">
                                                                    <span class="avatar" 
                                                                          :style="'background-image: url(' + item.image + ')'">
                                                                    </span>
                                                                </a>
                                                                <div>
                                                                    <span x-text="item.name"></span>
                                                                    <small class="text-muted d-block" x-text="getItemMemo(item)"></small>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div>
                                                                <template x-if="serviceHasCalculator(item)">
                                                                    <div class="d-flex align-items-center">
                                                                        <p x-show="!item.isEditingDimension" x-text="`${getSumDimensionAmount(item)} ${item.unit}`"></p>
                                                                        <input type="number" 
                                                                               class="form-control form-control-sm" 
                                                                               style="min-width: 80px" 
                                                                               x-show="item.isEditingDimension" 
                                                                               x-model="item.dimension_amount" 
                                                                               @blur="item.isEditingDimension = false" 
                                                                               min="0" 
                                                                               step="0.01">
                                                                        <button type="button" @click.prevent="openCalculator(item)" class="btn btn-icon btn-sm btn-ghost-secondary mx-1">
                                                                            <x-svg.calc class="icon"></x-svg.calc>
                                                                        </button>
                                                                    </div>
                                                                </template>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <input type="number" 
                                                                   class="form-control form-control-sm" 
                                                                   style="width: 80px" 
                                                                   :value="item.quantity" 
                                                                   @input="updateQuantity(item.id, $event.target.value)"
                                                                   min="1"
                                                                   step="1">
                                                        </td>
                                                        <td>
                                                            <div class="d-flex align-items-center gap-2">
                                                                <template x-if="!item.isEditingPrice">
                                                                    <div>
                                                                        <template x-if="item.custom_price">
                                                                            <div class="d-flex flex-column">
                                                                                <small class="text-decoration-line-through text-muted" 
                                                                                       x-text="item.full_price + ' UZS'">
                                                                                </small>
                                                                                <span x-text="item.custom_price + ' UZS'"></span>
                                                                            </div>
                                                                        </template>
                                                                        <template x-if="!item.custom_price">
                                                                            <span x-text="item.full_price + ' UZS'"></span>
                                                                        </template>
                                                                    </div>
                                                                </template>
                                                                <template x-if="item.isEditingPrice">
                                                                    <input type="number" 
                                                                           class="form-control form-control-sm" 
                                                                           style="width: 120px"
                                                                           :value="item.custom_price || item.full_price"
                                                                           @keyup.enter="saveCustomPrice(item.id, $event.target.value)"
                                                                           @keyup.esc="cancelEditPrice(item.id)"
                                                                           @blur="saveCustomPrice(item.id, $event.target.value)">
                                                                </template>
                                                                <button class="btn btn-icon btn-sm" 
                                                                        :class="item.isEditingPrice ? 'btn-success' : 'btn-ghost-secondary'"
                                                                        @click.prevent="toggleEditPrice(item.id)">
                                                                    <template x-if="!item.isEditingPrice">
                                                                        <x-svg.pen class="icon"></x-svg.pen>
                                                                    </template>
                                                                    <template x-if="item.isEditingPrice">
                                                                        <x-svg.check class="icon"></x-svg.check>
                                                                    </template>
                                                                </button>
                                                            </div>
                                                        </td>
                                                        <td x-text="getItemTotal(item) + ' UZS'"></td>
                                                        <td>
                                                            <button class="btn btn-sm btn-danger" @click="removeItem(item.id)">
                                                                <x-svg.trash class="icon"></x-svg.trash>
                                                            </button>
                                                        </td>
                                                    </tr>
                                                </template>
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <td colspan="4" class="text-end"><strong>Summa:</strong></td>
                                                    <td><strong x-text="getTotalSum() + ' UZS'"></strong></td>
                                                    <td></td>
                                                </tr>
                                                <tr>
                                                    <td colspan="4" class="text-end">
                                                        <div class="d-flex align-items-center justify-content-end gap-2">
                                                            <strong>Chegirma:</strong>
                                                            <template x-if="!isEditingDiscount">
                                                                <div class="d-flex align-items-center gap-2">
                                                                    <span x-text="discount + ' UZS'"></span>
                                                                    <button class="btn btn-icon btn-sm btn-ghost-secondary" @click="toggleEditDiscount">
                                                                        <x-svg.pen class="icon"></x-svg.pen>
                                                                    </button>
                                                                </div>
                                                            </template>
                                                            <template x-if="isEditingDiscount">
                                                                <div class="d-flex align-items-center gap-2">
                                                                    <input type="number" 
                                                                           class="form-control form-control-sm" 
                                                                           style="width: 120px"
                                                                           :value="discount"
                                                                           :max="getTotalSum()"
                                                                           min="0"
                                                                           @keyup.enter="saveDiscount($event.target.value)"
                                                                           @keyup.esc="cancelEditDiscount"
                                                                           @blur="saveDiscount($event.target.value)">
                                                                    <button class="btn btn-icon btn-sm btn-success" @click="toggleEditDiscount">
                                                                        <x-svg.check class="icon"></x-svg.check>
                                                                    </button>
                                                                </div>
                                                            </template>
                                                        </div>
                                                    </td>
                                                    <td><strong x-text="discount + ' UZS'"></strong></td>
                                                    <td></td>
                                                </tr>
                                                <tr>
                                                    <td colspan="4" class="text-end"><strong>Jami chegirma bilan:</strong></td>
                                                    <td><strong x-text="getFinalTotal() + ' UZS'"></strong></td>
                                                    <td></td>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </template>
                                </div>

                                <!-- Mijoz ma'lumotlari formasi -->
                                <template x-if="offerItems.length > 0">
                                    <div class="mt-4">
                                        <h3 class="mb-3">Mijoz haqida ma'lumot</h3>
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <div class="mb-3">
                                                    <label class="form-label required">Mijoz F.I.O</label>
                                                    <input type="text" 
                                                           x-model="client.name" 
                                                           class="form-control" 
                                                           placeholder="Mijoz F.I.O yoki tashkilot nomi"
                                                           required>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="mb-3">
                                                    <label class="form-label required">Telefon raqami</label>
                                                    <div class="input-group">
                                                        <span class="input-group-text">+998</span>
                                                        <input type="text"
                                                               maxlength="14"
                                                               minlength="14"
                                                               x-model="client.phone_number" 
                                                               class="form-control" 
                                                               placeholder="(00) 000-00-00"
                                                               x-init="() => {
                                                                   $nextTick(() => {
                                                                       new IMask($el, {
                                                                           mask: '(00) 000-00-00'
                                                                       });
                                                                   });
                                                               }"
                                                        >
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="mb-3">
                                                    <label class="form-label">Qo'shimcha raqam</label>
                                                    <div class="input-group">
                                                        <span class="input-group-text">+998</span>
                                                        <input type="text"
                                                               maxlength="14"
                                                               minlength="14"
                                                               x-model="client.phone_number2" 
                                                               class="form-control" 
                                                               placeholder="(00) 000-00-00"
                                                               x-init="() => {
                                                                   $nextTick(() => {
                                                                       new IMask($el, {
                                                                           mask: '(00) 000-00-00'
                                                                       });
                                                                   });
                                                               }">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-12">
                                                <div class="mb-3">
                                                    <label class="form-label">Izoh</label>
                                                    <textarea x-model="client.note" 
                                                              class="form-control" 
                                                              rows="3" 
                                                              placeholder="Qo'shimcha ma'lumot..."></textarea>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="mb-3">
                                                    <label class="form-label">Holat</label>
                                                    <select class="form-select" x-model="status">
                                                        @foreach(\App\Enums\Offer\OfferStatusEnum::list() as $value => $label)
                                                            <option value="{{ $value }}">{{ $label }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <!-- Добавляем поле для выбора даты заказа -->
                                            <div class="col-lg-6">
                                                <div class="mb-3">
                                                    <label class="form-label required">Sana</label>
                                                    <input type="date" 
                                                           x-model="orderDate" 
                                                           class="form-control" 
                                                           >
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </form>
        <!-- Модальное окно для калькулятора -->
        <div class="modal fade" id="calculatorModal" tabindex="-1" role="dialog" aria-labelledby="calculatorModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="calculatorModalLabel">Kalkulyator</h5>
                        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true" @click="calcModal.hide()">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="form-group col-md-4">
                                <label for="width">Eni (sm)</label>
                                <input type="number" id="calc-width" x-model="calcItem.x" class="form-control" placeholder="Kenglikni kiriting" required>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="height">Bo'yi (sm)</label>
                                <input type="number" id="calc-height" x-model="calcItem.y" class="form-control" placeholder="Balandlikni kiriting" required>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="quantity">Soni</label>
                                <input type="number" id="calc-quantity" x-model="calcItem.quantity" class="form-control" placeholder="Miqdor kiriting" min="1" value="1">
                            </div>
                        </div>
                        <!-- Добавляем поле для отображения dimension_amount -->
                        <div class="row mt-3">
                            <div class="form-group col-md-12">
                                <label for="dimension-amount">O'lcham:</label>
                                <div class="input-group">
                                    <input type="text" id="dimension-amount" :value="calc() ? `${calcItem.dimension_amount} ${calcItem.unit} x ${calcItem.quantity} шт = ${calc()}` : calcItem.dimension_amount" class="form-control" readonly>
                                    <span class="input-group-text" x-text="calcItem.unit"></span>
                                </div>
                                
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Bekor qilish</button>
                        <button type="button" class="btn btn-primary" :disabled="!calc()" @click.prevent="applyValues()">Qo'shish</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('custom_scripts')
<script>
    const calc_m2 = "{{\App\Enums\Service\ServiceCalculatorTypeEnum::SquareMeter->value}}";
    const calc_perimeter = "{{\App\Enums\Service\ServiceCalculatorTypeEnum::Perimeter->value}}";
    const calcModal = new bootstrap.Modal(document.getElementById('calculatorModal'));
    document.addEventListener('alpine:init', () => {
        Alpine.data('OfferFormData', () => ({
            services: [],         
            offerItems: [],
            searchQuery: '',
            searchResults: [],
            isSearching: false,
            discount: 0,
            isEditingDiscount: false,
            isSubmitting: false,
            calcItem: {
            },
            client: {
                name: '',
                phone_number: '',
                phone_number2: '',
                note: ''
            },
            status: {{ \App\Enums\Offer\OfferStatusEnum::Draft->value }},
            orderDate: '',
            
            get isFormValid() {
                return this.offerItems.length > 0 && 
                       this.client.name && 
                       this.client.phone_number;
            },
            
            init() {
                this.services = @json($categories ?? []);       
                this.debug();
            },

            handleSearch() {
                if (this.searchQuery.length >= 2) {
                    this.isSearching = true;
                    this.searchResults = this.searchServices(this.searchQuery);
                } else {
                    this.searchResults = [];
                    this.isSearching = false;
                }
            },

            searchServices(query) {
                query = query.toLowerCase();
                let results = [];
                
                this.services.forEach(category => {
                    category.services.forEach(service => {
                        if (!this.isServiceInOrder(service.id) && 
                            service.name.toLowerCase().includes(query)) {
                            results.push(service);
                        }
                    });
                });
                
                return results;
            },

            clearSearch() {
                this.searchQuery = '';
                this.searchResults = [];
                this.isSearching = false;
            },

            updateQuantity(id, quantity) {
                console.log('updateQuantity:', id, quantity);
                const numQuantity = parseFloat(quantity);
                if (numQuantity <= 0) {
                    this.removeItem(id);
                    return;
                }
                
                const index = this.offerItems.findIndex(item => item.id === id);
                if (index !== -1) {
                    this.offerItems[index].quantity = numQuantity;
                }
            },

            removeItem(id) {
                console.log('removeItem:', id);
                this.offerItems = this.offerItems.filter(item => item.id !== id);
            },

            toggleEditPrice(id) {
                const item = this.offerItems.find(item => item.id === id);
                if (item) {
                    if (item.isEditingPrice) {
                        item.isEditingPrice = false;
                    } else {
                        // Boshqa elementlar uchun tahrirni tozalash
                        this.offerItems.forEach(i => i.isEditingPrice = false);
                        item.isEditingPrice = true;
                    }
                }
            },

            saveCustomPrice(id, value) {
                const item = this.offerItems.find(item => item.id === id);
                if (item) {
                    const newPrice = parseFloat(value);
                    if (newPrice && newPrice > 0) {
                        item.custom_price = newPrice;
                    } else {
                        item.custom_price = null;
                    }
                    item.isEditingPrice = false;
                }
            },

            cancelEditPrice(id) {
                const item = this.offerItems.find(item => item.id === id);
                if (item) {
                    item.isEditingPrice = false;
                }
            },

            getItemTotal(item) {
                if (!item) return 0;
                const price = item.custom_price || item.full_price;
                return parseFloat((price * (item.quantity || 1) * (item.dimension_amount || 1)).toFixed(2));
            },

            getTotalSum() {
                if (!this.offerItems.length) return 0;
                return this.offerItems.reduce((sum, item) => sum + this.getItemTotal(item), 0);
            },

            addToOrder(service) {
                console.log('Adding service:', service);
                this.offerItems.push({
                    inner_id: Math.random(),
                    id: service.id,
                    name: service.name,
                    unit: service.unit,
                    image: service.image,
                    full_price: service.full_price,
                    custom_price: null,
                    isEditingPrice: false,
                    quantity: 1,
                    x: null,
                    y: null,
                    dimension_amount: 1,
                    custom_amount: null,
                    calculator: service.calculator
                });
                console.log('Updated offerItems:', this.offerItems);
            },

            isServiceInOrder(serviceId) {
                if (!serviceId) return false;
                return this.offerItems.some(item => item.id === serviceId);
            },

            getAvailableServices(category) {
                if (!category || !category.services) return [];
                return category.services;
            },

            getAvailableServicesCount(category) {
                if (!category) return 0;
                return this.getAvailableServices(category).length;
            },

            getAvailableCategories() {
                if (!this.services) return [];
                return this.services.filter(category => this.getAvailableServicesCount(category) > 0);
            },

            refreshFsLightbox() {
                if (typeof refreshFsLightbox === 'function') {
                    refreshFsLightbox();
                }
            },

            toggleEditDiscount() {
                this.isEditingDiscount = !this.isEditingDiscount;
            },

            saveDiscount(value) {
                const newDiscount = parseFloat(value);
                if (!isNaN(newDiscount) && newDiscount >= 0) {
                    this.discount = newDiscount;
                }
                this.isEditingDiscount = false;
            },

            cancelEditDiscount() {
                this.isEditingDiscount = false;
            },

            getFinalTotal() {
                return Math.max(0, this.getTotalSum() - this.discount);
            },

            debug() {
                console.log('Xizmatlar:', this.services);
                console.log('Taklif elementlari:', this.offerItems);
                console.log('Mijoz:', this.client);
            },

            prepareOfferItems() {
                return this.offerItems.map(item => ({
                    service_id: item.id,
                    quantity: item.quantity,
                    price: item.custom_price || item.full_price,
                    original_price: item.full_price,
                    cost: this.getItemTotal(item),
                    x: item.x,
                    y: item.y,
                    dimension_amount: item.dimension_amount,
                }));
            },

            async saveOffer() {
                if (!this.isFormValid || this.isSubmitting) return;

                this.isSubmitting = true;
                
                try {
                    console.log('Ma\'lumotlarni yuborish:', {
                        client: {
                            name: this.client.name,
                            phone_number: this.client.phone_number,
                            phone_number2: this.client.phone_number2,
                            note: this.client.note
                        },
                        status: this.status,
                        discount: this.discount,
                        created_at: this.orderDate,
                        offer_items: this.prepareOfferItems()
                    });

                    const response = await fetch('{{ route('offers.save') }}', {
                        method: 'POST',
                        headers: {
                            'Accept': 'application/json',
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            client: {
                                name: this.client.name,
                                phone_number: this.client.phone_number,
                                phone_number2: this.client.phone_number2,
                                note: this.client.note
                            },
                            status: this.status,
                            discount: this.discount,
                            order_date: this.orderDate,
                            offer_items: this.prepareOfferItems()
                        })
                    });

                    console.log('Javob holati:', response.status);
                    
                    const result = await response.json();
                    console.log('Javob ma\'lumotlari:', result);

                    if (result.success) {
                        window.location.href = result.redirect || '{{ route('offers.index') }}';
                    } else {
                        const errors = result.errors || {};
                        Object.keys(errors).forEach(key => {
                            alert(errors[key][0]);
                        });
                    }
                } catch (error) {
                    console.error('Saqlash xatosi:', error);
                    alert('Xatolik yuz berdi. Qaytadan urinib ko\'ring');
                } finally {
                    this.isSubmitting = false;
                }
            },

            serviceHasCalculator(item) {
                // Проверяем, есть ли у услуги поле calculator
                return item.calculator !== null && item.calculator !== undefined;
            },
            getItemMemo(item) {
                let memo = item.dimension_amount + ' ' + item.unit;
                if (item.x || item.y) {
                    let x = item.x ? item.x : '?';
                    let y = item.y ? item.y : '?';
                    memo = `${x}sm x ${y} sm = ` + memo;
                }
                return memo;
            },
            getSumDimensionAmount(item) {
                return item.custom_amount 
                    ? parseFloat(item.custom_amount) 
                    : (parseFloat(item.dimension_amount) * parseInt(item.quantity)).toFixed(2);
            },
            openCalculator(item) {
                this.calcItem = { ...item }; // Клонируем объект item
                calcModal.show(); // Открываем модальное окно
            },
            applyValues() {
                let amount = this.calc();
                if (amount) {
                    let x = this.calcItem.x;
                    let y = this.calcItem.y;
                    let quantity = this.calcItem.quantity;
                    // Найдите offerItem по this.calcItem.inner_id;
                    const offerItem = this.offerItems.find(item => item.inner_id === this.calcItem.inner_id);
                    if (offerItem) {
                        offerItem.x = x;
                        offerItem.y = y;
                        offerItem.quantity = quantity;
                        offerItem.dimension_amount = this.calcItem.dimension_amount; // Обновляем нужные поля
                        calcModal.hide();
                    }
                    calcModal.hide(); // Закрываем модальное окно
                }
                return null;
            },
            calc() {
                let width = this.calcItem.x;
                let height = this.calcItem.y;
                let quantity = this.calcItem.quantity;

                if (width && height && quantity) {
                    if (this.calcItem.calculator === calc_m2) {
                        let dm = (width * height * 0.0001);
                        this.calcItem.dimension_amount = dm.toFixed(2);
                        return (dm * quantity).toFixed(2);
                    } else if (this.calcItem.calculator === calc_perimeter) {
                        let dm = (parseFloat(width) + parseFloat(height)) * 2 * 0.01;
                        console.log(width, height, dm);
                        
                        this.calcItem.dimension_amount = dm.toFixed(2);
                        return (dm * quantity).toFixed(2);
                    }
                    return null;
                }
                return null;
            }
        }));
    });     
</script>
@endsection
