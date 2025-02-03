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
                            <input type="text" class="form-control form-control-sm" placeholder="Поиск услуги...">
                        </div>
                    </div>
                    <div class="card-body p-0">
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
                                                        <button class="btn btn-primary btn-sm d-flex align-items-center">
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
                                    <tr>
                                        <td>1</td>
                                        <td>
                                            <div class="d-flex align-items-center gap-2">
                                                <a data-fslightbox="order1" href="assets/images/no-image.jpg">
                                                    <span class="avatar" style="background-image: url(assets/images/no-image.jpg)"></span>
                                                </a>
                                                <div>
                                                    Компьютерная диагностика
                                                    <small class="text-muted d-block">за 1 точку</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <input type="number" class="form-control form-control-sm" style="width: 80px" value="1" min="1">
                                        </td>
                                        <td>15000 ₸</td>
                                        <td>15000 ₸</td>
                                        <td>
                                            <button class="btn btn-sm btn-danger">
                                                <x-svg.trash class="icon"></x-svg.trash>
                                            </button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>2</td>
                                        <td>
                                            <div class="d-flex align-items-center gap-2">
                                                <a data-fslightbox="order2" href="assets/images/no-image.jpg">
                                                    <span class="avatar" style="background-image: url(assets/images/no-image.jpg)"></span>
                                                </a>
                                                <div>
                                                    Замена масла в двигателе
                                                    <small class="text-muted d-block">за 1 замену</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <input type="number" class="form-control form-control-sm" style="width: 80px" value="1" min="1">
                                        </td>
                                        <td>5000 ₸</td>
                                        <td>5000 ₸</td>
                                        <td>
                                            <button class="btn btn-sm btn-danger">
                                                <x-svg.trash class="icon"></x-svg.trash>
                                            </button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>3</td>
                                        <td>
                                            <div class="d-flex align-items-center gap-2">
                                                <a data-fslightbox="order3" href="assets/images/no-image.jpg">
                                                    <span class="avatar" style="background-image: url(assets/images/no-image.jpg)"></span>
                                                </a>
                                                <div>
                                                    Замена колодок
                                                    <small class="text-muted d-block">за 1 ось</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <input type="number" class="form-control form-control-sm" style="width: 80px" value="2" min="1">
                                        </td>
                                        <td>8000 ₸</td>
                                        <td>16000 ₸</td>
                                        <td>
                                            <button class="btn btn-sm btn-danger">
                                                <x-svg.trash class="icon"></x-svg.trash>
                                            </button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>4</td>
                                        <td>
                                            <div class="d-flex align-items-center gap-2">
                                                <a data-fslightbox="order4" href="assets/images/no-image.jpg">
                                                    <span class="avatar" style="background-image: url(assets/images/no-image.jpg)"></span>
                                                </a>
                                                <div>
                                                    Развал-схождение
                                                    <small class="text-muted d-block">за 1 проверку</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <input type="number" class="form-control form-control-sm" style="width: 80px" value="1" min="1">
                                        </td>
                                        <td>12000 ₸</td>
                                        <td>12000 ₸</td>
                                        <td>
                                            <button class="btn btn-sm btn-danger">
                                                <x-svg.trash class="icon"></x-svg.trash>
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="4" class="text-end"><strong>Итого:</strong></td>
                                        <td><strong>48000 ₸</strong></td>
                                        <td></td>
                                    </tr>
                                </tfoot>
                            </table>
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
            
            init() {
                this.services = @json($categories ?? []);       
                this.debug();
            },

            // Проверяет, находится ли услуга в заказе
            isServiceInOrder(serviceId) {
                return this.offerItems.some(item => item.id === serviceId);
            },

            // Получает доступные (не в заказе) услуги для категории
            getAvailableServices(category) {
                return category.services.filter(service => !this.isServiceInOrder(service.id));
            },

            // Получает количество доступных услуг для категории
            getAvailableServicesCount(category) {
                return this.getAvailableServices(category).length;
            },

            // Фильтрует категории, у которых есть доступные услуги
            getAvailableCategories() {
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
