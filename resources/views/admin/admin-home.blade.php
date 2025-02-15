@extends('admin.layouts.app')

@section('content')
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <div class="d-flex align-items-center justify-content-between gap-2">
                    <h2 class="page-title">
                        Boshqaruv paneli
                    </h2>

                    <a href="{{ route('worker.home') }}" class="btn btn-outline-primary btn-icon" title="Buyurtmalar paneli">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-clipboard-list" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                            <path d="M9 5h-2a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-12a2 2 0 0 0 -2 -2h-2" />
                            <path d="M9 3m0 2a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v0a2 2 0 0 1 -2 2h-2a2 2 0 0 1 -2 -2z" />
                            <path d="M9 12l.01 0" />
                            <path d="M13 12l2 0" />
                            <path d="M9 16l.01 0" />
                            <path d="M13 16l2 0" />
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="page-body">
    <div class="container-xl">
        <!-- Блок офферов -->
        <div class="card mb-4">
            <div class="card-header">
                <h3 class="card-title">Buyurtmalar statistikasi</h3>
                <div class="card-actions d-flex align-items-center">
                    <!-- Фильтры для статистики -->
                    <form id="statistics-filter" class="mb-3">
                        <div class="d-flex gap-2">
                            <select class="form-select w-auto" name="period" id="period">
                                <option value="today" selected>Bugun</option>
                                <option value="yesterday">Kecha</option>
                                <option value="week">Joriy hafta</option>
                                <option value="month">Joriy oy</option>
                                <option value="custom">Ixtiyoriy davr</option>
                            </select>
                            <input type="date" class="form-control w-auto" name="start_date" id="start_date" style="display: none;">
                            <input type="date" class="form-control w-auto" name="end_date" id="end_date" style="display: none;">
                            <button type="submit" class="btn btn-primary">Qo'llash</button>
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
                                            Umumiy daromad
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
                                            Buyurtmalar soni

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
                                            O'rtacha chek
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
                                    <i class="fas fa-calendar me-2"></i>Haftalik grafik
                                </button>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item" href="#" data-chart-period="week">Haftalik grafik</a>
                                    <a class="dropdown-item" href="#" data-chart-period="last_week">O'tgan hafta grafigi</a>
                                    <a class="dropdown-item" href="#" data-chart-period="month">Oylik grafik</a>
                                    <a class="dropdown-item" href="#" data-chart-period="months">Oylar bo'yicha grafik</a>
                                    <a class="dropdown-item" href="#" data-chart-period="years">Yillar bo'yicha grafik</a>
                                </div>
                            </div>

                            <div class="btn-group ms-2">
                                <button type="button" class="btn" data-bs-toggle="dropdown" data-selected-category="all">
                                    <i class="fas fa-filter me-2"></i>Barcha toifalar
                                </button>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item" href="#" data-chart-category="all">Barcha toifalar</a>
                                    <a class="dropdown-item" href="#" data-chart-category="1">Toifa 1</a>
                                    <a class="dropdown-item" href="#" data-chart-category="2">Toifa 2</a>
                                </div>
                            </div>

                            <button type="button" class="btn btn-primary ms-2" id="apply-chart-filters">
                                <i class="fas fa-check me-2"></i>Qo'llash
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
                <h3 class="card-title">Qarzlar statistikasi</h3>
                <div class="card-actions">
                    <form id="debts-filter" class="d-flex gap-2 align-items-center">
                        <!-- Период -->
                        <select class="form-select w-auto" name="period" id="debt-period">
                            <option value="week" selected>Hafta bo'yicha</option>
                            <option value="month">Oy bo'yicha</option>
                            <option value="months">Oylar bo'yicha</option>
                            <option value="years">Yillar bo'yicha</option>
                        </select>

                        <!-- Тип оплаты -->
                        <select class="form-select w-auto" name="payment_type" id="payment-type">
                            <option value="all">Barcha to'lov turlari</option>
                            @foreach(\App\Enums\Finance\PaymentTypeEnum::list() as $value => $name)
                                <option value="{{ $value }}">{{ $name }}</option>
                            @endforeach
                        </select>

                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-check me-2"></i>Qo'llash
                        </button>
                    </form>
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
                                        <div class="font-weight-medium" id="debts-given">
                                            {{ number_format($debtsStatistics['debts_given'], 0, ',', ' ') }} UZS
                                        </div>
                                        <div class="text-muted">
                                            Qarzga berildi
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
                                        <div class="font-weight-medium" id="payments-received">
                                            {{ number_format($debtsStatistics['payments_received'], 0, ',', ' ') }} UZS
                                        </div>
                                        <div class="text-muted">
                                            To'lovlar qabul qilindi
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
                                        <div class="font-weight-medium" id="left-to-collect">
                                            {{ number_format($debtsStatistics['left_to_collect'], 0, ',', ' ') }} UZS
                                        </div>
                                        <div class="text-muted">
                                            Yig'ish kerak
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
                                        <div class="font-weight-medium" id="debtors-count">
                                            {{ $debtsStatistics['debtors_count'] }}
                                        </div>
                                        <div class="text-muted">
                                            Qarzdorlar
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
            name: 'Daromad',
            data: @json($chartData['data'])
        }, {
            name: 'Buyurtmalar soni',
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
                text: 'Daromad (UZS)'
            },
            labels: {
                formatter: function(val) {
                    return Math.round(val).toLocaleString('ru-RU') + ' UZS'
                }
            }
        }, {
            title: {
                text: 'Buyurtmalar soni'
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
                    return Math.round(val) + ' buyurtmasi'
                }
            }]
        }
    };

    var offersChart = new ApexCharts(document.querySelector("#chart-offers"), offersOptions);
    offersChart.render();

    // Инициализация графика долгов
    var debtsOptions = {
        series: [{
            name: 'Qarzga berildi',
            data: [850000, 650000, 450000, 300000, 200000, 150000, 100000]
        }, {
            name: 'To\'lovlar qabul qilindi',
            data: [750000, 550000, 350000, 250000, 150000, 100000, 50000]
        }],
        chart: {
            type: 'bar',
            height: 300,
            toolbar: {
                show: false
            }
        },
        plotOptions: {
            bar: {
                horizontal: false,
                columnWidth: '55%',
                endingShape: 'rounded'
            },
        },
        dataLabels: {
            enabled: false
        },
        stroke: {
            show: true,
            width: 2,
            colors: ['transparent']
        },
        xaxis: {
            categories: ['Dush', 'Sesh', 'Chor', 'Pay', 'Jum', 'Shan', 'Yak'],
        },
        yaxis: {
            title: {
                text: 'Summa (UZS)'
            },
            labels: {
                formatter: function(val) {
                    return Math.round(val).toLocaleString('ru-RU') + ' UZS'
                }
            }
        },
        fill: {
            opacity: 1
        },
        tooltip: {
            y: {
                formatter: function(val) {
                    return Math.round(val).toLocaleString('ru-RU') + ' UZS'
                }
            }
        },
        colors: ['#e74c3c', '#2ecc71'] // Красный для долгов, зеленый для платежей
    };

    var debtsChart = new ApexCharts(document.querySelector("#chart-debts"), debtsOptions);
    debtsChart.render();

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
                        alert('Ixtiyoriy davrni tanlang');
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
                    alert('Ma\'lumotlar olishda xatolik yuz berdi');
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
                    name: 'Daromad',
                    data: data.chartData.data.map(val => Math.round(val))
                }, {
                    name: 'Buyurtmalar soni',
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

    // Обработчик фильтров долгов
    document.getElementById('debts-filter')?.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const period = document.getElementById('debt-period').value;
        const paymentType = document.getElementById('payment-type').value;
        
        let startDate, endDate;
        const now = moment();
        
        switch(period) {
            case 'week':
                startDate = now.clone().subtract(6, 'days');
                endDate = now;
                break;
            case 'month':
                startDate = now.clone().subtract(29, 'days');
                endDate = now;
                break;
            case 'months':
                startDate = now.clone().subtract(11, 'months').startOf('month');
                endDate = now.endOf('month');
                break;
            case 'years':
                startDate = now.clone().subtract(5, 'years').startOf('year');
                endDate = now.endOf('year');
                break;
        }

        // Отправляем запрос на новый роут
        axios.post('/admin/debts-data', {
            start_date: startDate.format('YYYY-MM-DD'),
            end_date: endDate.format('YYYY-MM-DD'),
            period: period,
            payment_type: paymentType !== 'all' ? paymentType : null
        })

        .then(response => {
            const data = response.data;
            
            // Обновляем график
            debtsChart.updateOptions({
                xaxis: {
                    categories: data.chartData.categories
                }
            });
            
            debtsChart.updateSeries([{
                name: 'Qarzga berildi',
                data: data.chartData.debts
            }, {
                name: 'To\'lovlar qabul qilindi',
                data: data.chartData.payments
            }]);

            // Обновляем статистику
            document.getElementById('debts-given').textContent = 
                Number(data.statistics.debts_given).toLocaleString('ru-RU') + ' UZS';
            document.getElementById('payments-received').textContent = 
                Number(data.statistics.payments_received).toLocaleString('ru-RU') + ' UZS';
            document.getElementById('left-to-collect').textContent = 
                Number(data.statistics.left_to_collect).toLocaleString('ru-RU') + ' UZS';
            document.getElementById('debtors-count').textContent = 
                data.statistics.debtors_count;
        })
        .catch(error => {
            console.error('Ma\'lumotlar olishda xatolik yuz berdi:', error);
        });
    });
</script>
@endsection
