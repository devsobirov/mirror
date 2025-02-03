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
        <div class="row mt-4">
            <!-- Левая панель со списком услуг -->
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">Каталог услуг</h5>
                        <div class="ms-2 flex-grow-1" style="max-width: 200px;">
                            <input type="text" 
                                   class="form-control form-control-sm" 
                                   placeholder="Поиск услуги..."
                                   x-model.debounce.300ms="searchQuery"
                                   @input="handleSearch">
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <!-- Блок результатов поиска -->
                        <template x-if="isSearching">
                            <div class="p-3">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h6 class="m-0">Результаты поиска: <span class="text-muted" x-text="searchQuery"></span></h6>
                                    <button class="btn btn-sm btn-outline-secondary" @click="clearSearch">
                                        Очистить поиск
                                    </button>
                                </div>
                                
                                <template x-if="searchResults.length === 0">
                                    <div class="text-center py-4 text-muted">
                                        <p>По запросу "<span x-text="searchQuery"></span>" ничего не найдено</p>
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

                        <!-- Основной каталог -->
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

            <!-- Правая панель с выбранными услугами -->
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">Выбранные услуги</h5>
                        <button class="btn btn-primary">
                            Сохранить заказ
                        </button>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <template x-if="offerItems.length === 0">
                                <div class="text-center p-4 text-muted">
                                    <h3>Услуги не выбраны</h3>
                                    <p>Выберите услуги из каталога слева</p>
                                </div>
                            </template>
                            <template x-if="offerItems.length > 0">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th style="width: 40px">#</th>
                                            <th>Услуга</th>
                                            <th>Количество</th>
                                            <th>Цена за ед.</th>
                                            <th>Сумма</th>
                                            <th style="width: 40px"></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <template x-for="(item, index) in offerItems" :key="item.id">
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
                                                            <small class="text-muted d-block" x-text="'за 1 ' + item.unit"></small>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <input type="number" 
                                                           class="form-control form-control-sm" 
                                                           style="width: 80px" 
                                                           :value="item.quantity" 
                                                           @input="updateQuantity(item.id, $event.target.value)"
                                                           min="1">
                                                </td>
                                                <td x-text="item.full_price + ' UZS'"></td>
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
                                            <td colspan="4" class="text-end"><strong>Итого:</strong></td>
                                            <td><strong x-text="getTotalSum() + ' UZS'"></strong></td>
                                            <td></td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </template>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('custom_scripts')
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('OfferFormData', () => ({
            services: [],         
            offerItems: [],
            searchQuery: '',
            searchResults: [],
            isSearching: false,
            
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
                const numQuantity = parseInt(quantity);
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

            getItemTotal(item) {
                if (!item || !item.full_price) return 0;
                return (item.full_price * (item.quantity || 1));
            },

            getTotalSum() {
                if (!this.offerItems.length) return 0;
                return this.offerItems.reduce((sum, item) => sum + this.getItemTotal(item), 0);
            },

            addToOrder(service) {
                console.log('Adding service:', service);
                if (!service || !service.id) {
                    console.error('Invalid service object:', service);
                    return;
                }

                if (!this.isServiceInOrder(service.id)) {
                    this.offerItems.push({
                        id: service.id,
                        name: service.name,
                        unit: service.unit,
                        image: service.image,
                        full_price: service.full_price,
                        quantity: 1
                    });
                    console.log('Updated offerItems:', this.offerItems);
                }
            },

            isServiceInOrder(serviceId) {
                if (!serviceId) return false;
                return this.offerItems.some(item => item.id === serviceId);
            },

            getAvailableServices(category) {
                if (!category || !category.services) return [];
                return category.services.filter(service => !this.isServiceInOrder(service.id));
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

            debug() {
                console.log('Services:', this.services);
                console.log('Offer Items:', this.offerItems);
            }
        }));
    });     
</script>
@endsection
