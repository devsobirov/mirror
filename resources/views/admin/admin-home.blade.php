@extends('admin.layouts.app')

@section('content')
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <h2 class="page-title">
                    Панель управления
                </h2>
            </div>
        </div>
    </div>
</div>

<div class="page-body">
    <div class="container-xl">
        <!-- Блок офферов -->
        <div class="card mb-4">
            <div class="card-header">
                <h3 class="card-title">Статистика офферов</h3>
                <div class="card-actions d-flex align-items-center">
                    <!-- Фильтры для статистики -->
                    <form id="statistics-filter" class="mb-3">
                        <div class="d-flex gap-2">
                            <select class="form-select w-auto" name="period" id="period">
                                <option value="today" selected>Сегодня</option>
                                <option value="yesterday">Вчера</option>
                                <option value="week">Текущая неделя</option>
                                <option value="month">Текущий месяц</option>
                                <option value="custom">Произвольный период</option>
                            </select>
                            <input type="date" class="form-control w-auto" name="start_date" id="start_date" style="display: none;">
                            <input type="date" class="form-control w-auto" name="end_date" id="end_date" style="display: none;">
                            <button type="submit" class="btn btn-primary">Применить</button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-sm-6 col-lg-4">
                        <div class="card card-sm">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col-auto">
                                        <span class="bg-primary text-white avatar">
                                            <i class="fas fa-money-bill-wave"></i>
                                        </span>
                                    </div>
                                    <div class="col">
                                        <div class="font-weight-medium" id="total-income">
                                        {{ number_format($statistics['total_income'], 0, ',', ' ') }} UZS
                                        </div>
                                        <div class="text-muted">
                                            Общий доход
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-lg-4">
                        <div class="card card-sm">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col-auto">
                                        <span class="bg-green text-white avatar">
                                            <i class="fas fa-file-contract"></i>
                                        </span>
                                    </div>
                                    <div class="col">
                                        <div class="font-weight-medium" id="offers-count">
                                        {{ $statistics['total_count'] }}
                                        </div>
                                        <div class="text-muted">
                                            Количество офферов

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-lg-4">
                        <div class="card card-sm">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col-auto">
                                        <span class="bg-yellow text-white avatar">
                                            <i class="fas fa-chart-line"></i>
                                        </span>
                                    </div>
                                    <div class="col">
                                        <div class="font-weight-medium" id="average-check">
                                            {{ number_format($statistics['average_check'], 0, ',', ' ') }} UZS
                                        </div>
                                        <div class="text-muted">
                                            Средний чек
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <!-- Фильтры для чарта -->
                        <div class="d-flex align-items-center mb-3">
                            <div class="btn-group">
                                <button type="button" class="btn" data-bs-toggle="dropdown" data-selected-period="week">
                                    <i class="fas fa-calendar me-2"></i>График за неделю
                                </button>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item" href="#" data-chart-period="week">График за неделю</a>
                                    <a class="dropdown-item" href="#" data-chart-period="last_week">График за прошлую неделю</a>
                                    <a class="dropdown-item" href="#" data-chart-period="month">График за месяц</a>
                                    <a class="dropdown-item" href="#" data-chart-period="months">График по месяцам</a>
                                    <a class="dropdown-item" href="#" data-chart-period="years">График по годам</a>
                                </div>
                            </div>

                            <div class="btn-group ms-2">
                                <button type="button" class="btn" data-bs-toggle="dropdown" data-selected-category="all">
                                    <i class="fas fa-filter me-2"></i>Все категории
                                </button>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item" href="#" data-chart-category="all">Все категории</a>
                                    <a class="dropdown-item" href="#" data-chart-category="1">Категория 1</a>
                                    <a class="dropdown-item" href="#" data-chart-category="2">Категория 2</a>
                                </div>
                            </div>

                            <button type="button" class="btn btn-primary ms-2" id="apply-chart-filters">
                                <i class="fas fa-check me-2"></i>Применить
                            </button>
                        </div>

                        <div id="chart-offers" style="height: 300px"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Блок долгов -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Статистика долгов</h3>
                <div class="card-actions d-flex align-items-center">
                    <!-- Период -->
                    <div class="btn-group">
                        <button type="button" class="btn" data-bs-toggle="dropdown">
                            <i class="fas fa-calendar me-2"></i>Период
                        </button>
                        <div class="dropdown-menu">
                            <a class="dropdown-item" href="#" data-debt-period="today">Сегодня</a>
                            <a class="dropdown-item" href="#" data-debt-period="week">Неделя</a>
                            <a class="dropdown-item" href="#" data-debt-period="month">Месяц</a>
                            <a class="dropdown-item" href="#" data-debt-period="custom">Выбрать период</a>
                        </div>
                    </div>
                    
                    <!-- Датапикеры для долгов (изначально скрыты) -->
                    <div class="btn-group ms-2" id="debt-date-range-picker" style="display: none;">
                        <input type="date" class="form-control form-control-sm" id="debt-start-date">
                        <span class="mx-1">-</span>
                        <input type="date" class="form-control form-control-sm" id="debt-end-date">
                    </div>

                    <!-- Тип оплаты -->
                    <div class="btn-group ms-2">
                        <button type="button" class="btn" data-bs-toggle="dropdown">
                            <i class="fas fa-credit-card me-2"></i>Тип оплаты
                        </button>
                        <div class="dropdown-menu">
                            <a class="dropdown-item" href="#">Все типы</a>
                            <a class="dropdown-item" href="#">Наличные</a>
                            <a class="dropdown-item" href="#">Безналичные</a>
                        </div>
                    </div>

                    <!-- Кнопка применить -->
                    <button type="button" class="btn btn-primary ms-2" id="apply-debts-filters">
                        <i class="fas fa-check me-2"></i>Применить
                    </button>
                </div>
            </div>
            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-sm-6 col-lg-3">
                        <div class="card card-sm">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col-auto">
                                        <span class="bg-red text-white avatar">
                                            <i class="fas fa-hand-holding-usd"></i>
                                        </span>
                                    </div>
                                    <div class="col">
                                        <div class="font-weight-medium">
                                            850,000 ₸
                                        </div>
                                        <div class="text-muted">
                                            Выдано в долг
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-lg-3">
                        <div class="card card-sm">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col-auto">
                                        <span class="bg-green text-white avatar">
                                            <i class="fas fa-money-bill-wave"></i>
                                        </span>
                                    </div>
                                    <div class="col">
                                        <div class="font-weight-medium">
                                            650,000 ₸
                                        </div>
                                        <div class="text-muted">
                                            Получено платежей
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-lg-3">
                        <div class="card card-sm">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col-auto">
                                        <span class="bg-orange text-white avatar">
                                            <i class="fas fa-clock"></i>
                                        </span>
                                    </div>
                                    <div class="col">
                                        <div class="font-weight-medium">
                                            200,000 ₸
                                        </div>
                                        <div class="text-muted">
                                            Осталось собрать
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-lg-3">
                        <div class="card card-sm">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col-auto">
                                        <span class="bg-purple text-white avatar">
                                            <i class="fas fa-users"></i>
                                        </span>
                                    </div>
                                    <div class="col">
                                        <div class="font-weight-medium">
                                            45
                                        </div>
                                        <div class="text-muted">
                                            Должников
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <div id="chart-debts" style="height: 300px"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('custom_styles')
    <!-- ... existing styles ... -->
@endsection

@section('custom_scripts')
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
<script>
    const csrfToken = '{{ csrf_token() }}';
    let stats = {
        total_income: 0,
        total_count: 0,
        average_check: 0
    }
    
    // Инициализация графика
    var offersOptions = {
        series: [{
            name: 'Доход',
            data: @json($chartData['data'])
        }, {
            name: 'Количество заказов',
            data: @json($chartData['orders_count'])
        }],
        chart: {
            type: 'area',
            height: 300,
            toolbar: {
                show: false
            }
        },
        dataLabels: {
            enabled: false
        },
        stroke: {
            curve: 'smooth'
        },
        xaxis: {
            categories: @json($chartData['categories'])
        },
        yaxis: [{
            title: {
                text: 'Доход (UZS)'
            },
            labels: {
                formatter: function(val) {
                    return Math.round(val).toLocaleString('ru-RU') + ' UZS'
                }
            }
        }, {
            title: {
                text: 'Количество заказов'
            },
            opposite: true,
            labels: {
                formatter: function(val) {
                    return Math.round(val)
                }
            }
        }],
        tooltip: {
            y: [{
                formatter: function(val) {
                    return Math.round(val).toLocaleString('ru-RU') + ' UZS'
                }
            }, {
                formatter: function(val) {
                    return Math.round(val) + ' заказов'
                }
            }]
        }
    };

    var offersChart = new ApexCharts(document.querySelector("#chart-offers"), offersOptions);
    offersChart.render();

    // Обработчик фильтров статистики
    document.addEventListener('DOMContentLoaded', function() {
        const statisticsForm = document.getElementById('statistics-filter');
        const statisticsPeriod = document.getElementById('period');
        const startDateInput = document.getElementById('start_date');
        const endDateInput = document.getElementById('end_date');

        if (statisticsForm && statisticsPeriod) {
            // Показ/скрытие полей дат при выборе периода
            statisticsPeriod.addEventListener('change', function() {
                const isCustomPeriod = this.value === 'custom';
                startDateInput.style.display = isCustomPeriod ? 'block' : 'none';
                endDateInput.style.display = isCustomPeriod ? 'block' : 'none';
            });

            statisticsForm.addEventListener('submit', function(e) {
                e.preventDefault();
                const period = statisticsPeriod.value;
                let data = { period };

                if (period === 'custom') {
                    if (!startDateInput.value || !endDateInput.value) {
                        alert('Выберите даты для произвольного периода');
                        return;
                    }
                    data.start_date = startDateInput.value;
                    data.end_date = endDateInput.value;
                }

                fetch('/admin/statistics-data', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify(data)
                })
                .then(response => response.json())
                .then(data => {
                    stats = data.statistics;
                    console.log(stats);
                    
                    document.getElementById('total-income').textContent = parseInt(stats.total_income) + ' UZS';
                    document.getElementById('offers-count').textContent = stats.total_count;
                    document.getElementById('average-check').textContent = parseInt(stats.average_check) + ' UZS';

                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Произошла ошибка при получении данных');
                });
            });
        }
    });

    function updateStatistics(filterData) {
        fetch('/admin/statistics-data', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify(filterData)
        })
        .then(response => response.json())
        .then(data => {
            const stats = data.statistics;
            
            const elements = {
                totalIncome: document.getElementById('total-income'),
                offersCount: document.getElementById('offers-count'),
                averageCheck: document.getElementById('average-check'),
                incomeGrowth: document.getElementById('income-growth')
            };

            if (elements.totalIncome) {
                elements.totalIncome.textContent = new Intl.NumberFormat('ru-RU').format(stats.total_income) + ' ₸';
            }
            if (elements.offersCount) {
                elements.offersCount.textContent = new Intl.NumberFormat('ru-RU').format(stats.total_count);
            }
            if (elements.averageCheck) {
                elements.averageCheck.textContent = new Intl.NumberFormat('ru-RU').format(stats.average_check) + ' ₸';
            }
            if (elements.incomeGrowth) {
                elements.incomeGrowth.textContent = (stats.growth > 0 ? '+' : '') + stats.growth + '%';
            }
        })
        .catch(error => console.error('Error:', error));
    }

    function updateChartData(startDate, endDate, categoryId = null, period = 'week') {
        fetch('/admin/chart-data', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify({
                start_date: startDate,
                end_date: endDate,
                category_id: categoryId,
                period: period
            })
        })
        .then(response => response.json())
        .then(data => {
            if (offersChart) {
                offersChart.updateOptions({
                    xaxis: {
                        categories: data.chartData.categories
                    }
                });
                
                offersChart.updateSeries([{
                    name: 'Доход',
                    data: data.chartData.data.map(val => Math.round(val))
                }, {
                    name: 'Количество заказов',
                    data: data.chartData.orders_count
                }]);
            }
        })
        .catch(error => console.error('Error:', error));
    }

    // Обработчики для фильтров чарта
    document.querySelectorAll('[data-chart-period]').forEach(item => {
        item.addEventListener('click', function(e) {
            e.preventDefault();
            const periodText = this.textContent;
            const periodButton = this.closest('.btn-group').querySelector('button');
            periodButton.innerHTML = `<i class="fas fa-calendar me-2"></i>${periodText}`;
            periodButton.dataset.selectedPeriod = this.dataset.chartPeriod;
        });
    });

    document.querySelectorAll('[data-chart-category]').forEach(item => {
        item.addEventListener('click', function(e) {
            e.preventDefault();
            const categoryText = this.textContent;
            const categoryButton = this.closest('.btn-group').querySelector('button');
            categoryButton.innerHTML = `<i class="fas fa-filter me-2"></i>${categoryText}`;
            categoryButton.dataset.selectedCategory = this.dataset.chartCategory;
        });
    });

    document.getElementById('apply-chart-filters')?.addEventListener('click', function() {
        const periodButton = document.querySelector('[data-bs-toggle="dropdown"][data-selected-period]');
        const categoryButton = document.querySelector('[data-bs-toggle="dropdown"][data-selected-category]');
        
        const period = periodButton?.dataset.selectedPeriod || 'week';
        const categoryId = categoryButton?.dataset.selectedCategory === 'all' ? null : categoryButton?.dataset.selectedCategory;

        let startDate, endDate;
        switch(period) {
            case 'week':
                startDate = moment().subtract(6, 'days');
                endDate = moment();
                break;
            case 'last_week':
                startDate = moment().subtract(2, 'weeks').startOf('week').add(1, 'day'); // Понедельник прошлой недели
                endDate = moment().subtract(1, 'week').endOf('week').add(1, 'day');     // Воскресенье прошлой недели
                break;
            case 'month':
                startDate = moment().subtract(1, 'month');
                endDate = moment();
                break;
            case 'months':
                startDate = moment().subtract(11, 'months').startOf('month');
                endDate = moment().endOf('month');
                break;
            case 'years':
                startDate = moment().subtract(5, 'years').startOf('year');
                endDate = moment().endOf('year');
                break;
        }

        updateChartData(
            startDate.format('YYYY-MM-DD'),
            endDate.format('YYYY-MM-DD'),
            categoryId,
            period
        );
    });
</script>
@endsection
