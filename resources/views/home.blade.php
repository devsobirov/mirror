@extends('admin.layouts.app')

@section('content')
<div class="container">
    <h1>Сводка за день</h1>

    <div class="row mb-3">
        <div class="col-md-4">
            <form action="{{ route('home') }}" method="GET">
                <div class="form-group">
                    <label for="period">Период:</label>
                    <select name="period" id="period" class="form-control">
                        <option value="today" @if(request('period') == 'today') selected @endif>Сегодня</option>
                        <option value="week" @if(request('period') == 'week') selected @endif>Неделя</option>
                        <option value="month" @if(request('period') == 'month') selected @endif>Текущий месяц</option>
                        <option value="custom" @if(request('period') == 'custom') selected @endif>Произвольный</option>
                    </select>
                </div>
                <div class="form-group" id="custom_dates" style="@if(request('period') != 'custom') display: none; @endif">
                    <label for="start_date">Начальная дата:</label>
                    <input type="date" name="start_date" id="start_date" class="form-control" value="{{ request('start_date') }}">
                    <label for="end_date">Конечная дата:</label>
                    <input type="date" name="end_date" id="end_date" class="form-control" value="{{ request('end_date') }}">
                </div>
                <button type="submit" class="btn btn-primary">Применить</button>
            </form>
        </div>
    </div>

    <div class="row">
        <div class="col-md-3 mb-4">
            <div class="card shadow-sm h-100 bg-primary text-white animate__animated animate__fadeIn">
                <div class="card-body">
                    <h5 class="card-title"><i class="fas fa-wallet me-2"></i>Доход</h5>
                    <p class="card-text display-6">{{ number_format($income, 0, ',', ' ') }} ₸</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-4">
            <div class="card shadow-sm h-100 bg-success text-white animate__animated animate__fadeIn">
                <div class="card-body">
                    <h5 class="card-title"><i class="fas fa-hand-holding-usd me-2"></i>Выдано в долг</h5>
                    <p class="card-text display-6">{{ number_format($debtsGiven, 0, ',', ' ') }} ₸</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-4">
            <div class="card shadow-sm h-100 bg-info text-white animate__animated animate__fadeIn">
                <div class="card-body">
                    <h5 class="card-title"><i class="fas fa-coins me-2"></i>Получено платежей</h5>
                    <p class="card-text display-6">{{ number_format($paymentsReceived, 0, ',', ' ') }} ₸</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-4">
            <div class="card shadow-sm h-100 bg-warning text-dark animate__animated animate__fadeIn">
                <div class="card-body">
                    <h5 class="card-title"><i class="fas fa-file-contract me-2"></i>Офферов</h5>
                    <p class="card-text display-6">{{ number_format($offersCount, 0, ',', ' ') }}</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById('period').addEventListener('change', function() {
        if (this.value === 'custom') {
            document.getElementById('custom_dates').style.display = 'block';
        } else {
            document.getElementById('custom_dates').style.display = 'none';
        }
    });
</script>

<style>
    .card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        border-radius: 15px;
    }
    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.2);
    }
    #custom_dates {
        transition: all 0.3s ease-in-out;
    }
</style>
@endsection
