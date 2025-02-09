<?php

namespace App\Http\Controllers;

use App\Models\Warehouse\Balance;
use App\Models\Finance\Payment;
use App\Enums\Offer\OfferStatusEnum;
use App\Models\Finance\Debt;
use App\Models\Services\Offer;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; // Добавлен недостающий импорт
use Illuminate\Support\Facades\Auth; // Добавлен недостающий импорт


class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        if (Auth::user()->isAdmin()) {
            return $this->adminHome($request);
        }

        $period = $request->input('period', 'today');
        $startDate = null;
        $endDate = null;

        switch ($period) {
            case 'today':
                $startDate = Carbon::today();
                $endDate = Carbon::today();
                break;
            case 'week':
                $startDate = Carbon::now()->startOfWeek();
                $endDate = Carbon::now()->endOfWeek();
                break;
            case 'month':
                $startDate = Carbon::now()->startOfMonth();
                $endDate = Carbon::now()->endOfMonth();
                break;
            case 'custom':
                $startDate = $request->input('start_date') ? Carbon::parse($request->input('start_date')) : null;
                $endDate = $request->input('end_date') ? Carbon::parse($request->input('end_date')) : null;

                if (!$startDate || !$endDate)
                {
                    $startDate = Carbon::today();
                    $endDate = Carbon::today();
                }

                break;
        }
        $startDateString = $startDate->toDateString();
        $endDateString = $endDate->toDateString();

        $offerStats = Offer::query()
            ->where('status', '!=', OfferStatusEnum::Draft->value)
            ->whereDate('created_at', '>=', $startDateString)
            ->whereDate('created_at', '<=', $endDateString)
            ->selectRaw('SUM(cost) as total_income, COUNT(*) as offers_count')
            ->first();

        $income = $offerStats->total_income ?? 0;
        $offersCount = $offerStats->offers_count ?? 0;

        $debtsGiven = Debt::query()
            ->whereDate('created_at', '>=', $startDateString)
            ->whereDate('created_at', '<=', $endDateString)
            ->sum('amount');

        $paymentsReceived = Payment::query()
            ->whereDate('created_at', '>=', $startDateString)
            ->whereDate('created_at', '<=', $endDateString)
            ->sum('amount');

        return view('home', compact('income', 'debtsGiven', 'paymentsReceived', 'offersCount'));
    }

    
    public function adminHome(Request $request)
    {
        // Получаем данные за текущий день по умолчанию
        $startDate = Carbon::now()->startOfDay();
        $endDate = Carbon::now()->endOfDay();
        
        // Получаем данные за текущую неделю по умолчанию
        $startWeek = Carbon::now()->subDays(6)->startOfDay(); // Начало периода (7 дней назад)
        $endWeek = Carbon::now()->endOfDay(); // Конец периода (сегодня)

        $statistics = $this->getOffersStatistics($startDate, $endDate);
        $chartData = $this->getOffersChartData($startWeek, $endWeek);
        
        $debtsData = $this->getDebtsChartData($startWeek, $endWeek);
        $debtsStatistics = $this->getDebtsStatistics($startWeek, $endWeek);

        // Убедимся, что все ключи существуют
        $statistics = array_merge([
            'total_income' => 0,
            'total_count' => 0,
            'average_check' => 0,
            'growth' => 0
        ], $statistics);

        return view('admin.admin-home', [
            'statistics' => $statistics,
            'chartData' => $chartData,
            'debtsData' => $debtsData,
            'debtsStatistics' => $debtsStatistics
        ]);
    }

    
    public function getChartData(Request $request)
    {
        $validated = $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'period' => 'required|in:week,month,months,years,last_week'
        ]);

        $startDate = Carbon::parse($validated['start_date']);
        $endDate = Carbon::parse($validated['end_date']);
        
        return response()->json([
            'chartData' => $this->getOffersChartData(
                $startDate, 
                $endDate, 
                $validated['period']
            )
        ]);
    }

    public function getStatisticsData(Request $request)
    {
        $validated = $request->validate([
            'period' => 'required|in:today,yesterday,week,month,custom',
            'start_date' => 'required_if:period,custom|date',
            'end_date' => 'required_if:period,custom|date'
        ]);

        $startDate = null;
        $endDate = null;

        switch ($validated['period']) {
            case 'today':
                $startDate = Carbon::today();
                $endDate = Carbon::today();
                break;
            case 'yesterday':
                $startDate = Carbon::yesterday();
                $endDate = Carbon::yesterday();
                break;
            case 'week':
                $startDate = Carbon::now()->startOfWeek();
                $endDate = Carbon::now()->endOfWeek();
                break;
            case 'month':
                $startDate = Carbon::now()->startOfMonth();
                $endDate = Carbon::now()->endOfMonth();
                break;
            case 'custom':
                $startDate = Carbon::parse($validated['start_date']);
                $endDate = Carbon::parse($validated['end_date']);
                break;
        }

        return response()->json([
            'statistics' => $this->getOffersStatistics($startDate, $endDate)
        ]);
    }

    private function getOffersStatistics($startDate, $endDate, $categoryId = null)
    {
        $query = Offer::query()
            ->where('status', '!=', OfferStatusEnum::Draft->value)
            ->whereDate('created_at', '>=', $startDate)
            ->whereDate('created_at', '<=', $endDate);

        if ($categoryId) {
            $query->whereHas('offerItems.service', function($q) use ($categoryId) {
                $q->where('category_id', $categoryId);
            });
        }

        $currentPeriodStats = $query->selectRaw('
            COUNT(*) as total_count,
            SUM(cost) as total_income,
            AVG(cost) as average_check
        ')->first();

        return [
            'total_income' => $currentPeriodStats->total_income ?? 0,
            'total_count' => $currentPeriodStats->total_count ?? 0,
            'average_check' => $currentPeriodStats->average_check ?? 0
        ];
    }

    private function getOffersChartData($startDate, $endDate, $period = 'week')
    {
        $query = Offer::query()
            ->where('status', '!=', OfferStatusEnum::Draft->value)
            ->whereDate('created_at', '>=', $startDate)
            ->whereDate('created_at', '<=', $endDate);

        switch ($period) {
            case 'week':
            case 'last_week':
            case 'month':
                $data = $query->selectRaw('
                    DATE(created_at) as date,
                    SUM(cost) as daily_income,
                    COUNT(*) as orders_count
                ')
                ->groupBy('date')
                ->get();

                return [
                    'categories' => $data->pluck('date')->map(function($date) {
                        return Carbon::parse($date)->format('j F');
                    })->values()->all(),
                    'data' => $data->pluck('daily_income')->map(function($value) {
                        return round($value);
                    })->values()->all(),
                    'orders_count' => $data->pluck('orders_count')->values()->all()
                ];

            case 'months':
                $data = $query->selectRaw('
                    DATE_FORMAT(created_at, "%Y-%m") as month,
                    SUM(cost) as monthly_income,
                    COUNT(*) as orders_count
                ')
                ->groupBy('month')
                ->orderBy('month')
                ->get();

                return [
                    'categories' => $data->pluck('month')->map(function($month) {
                        return Carbon::parse($month)->format('F');
                    }),
                    'data' => $data->pluck('monthly_income')->map(function($value) {
                        return round($value);
                    }),
                    'orders_count' => $data->pluck('orders_count')
                ];

            case 'years':
                $data = $query->selectRaw('
                    YEAR(created_at) as year,
                    SUM(cost) as yearly_income,
                    COUNT(*) as orders_count
                ')
                ->groupBy('year')
                ->orderBy('year')
                ->get();

                return [
                    'categories' => $data->pluck('year')->map(function($year) {
                        return $year . ' г.';
                    }),
                    'data' => $data->pluck('yearly_income')->map(function($value) {
                        return round($value);
                    }),
                    'orders_count' => $data->pluck('orders_count')
                ];
        }
    }

    public function getDebtsData(Request $request)
    {
        $validated = $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'period' => 'required|in:week,month,months,years',
            'payment_type' => 'nullable|integer'
        ]);

        $startDate = Carbon::parse($validated['start_date']);
        $endDate = Carbon::parse($validated['end_date']);
        
        return response()->json([
            'chartData' => $this->getDebtsChartData(
                $startDate, 
                $endDate, 
                $validated['period'],
                $validated['payment_type'] ?? null
            ),
            'statistics' => $this->getDebtsStatistics(
                $startDate, 
                $endDate,
                $validated['payment_type'] ?? null
            )
        ]);
    }

    private function getDebtsStatistics($startDate, $endDate, $paymentType = null)
    {
        // Сумма выданных долгов (не зависит от типа оплаты)
        $debtsGiven = Debt::query()
            ->whereDate('created_at', '>=', $startDate)
            ->whereDate('created_at', '<=', $endDate)
            ->sum('amount');

        // Сумма полученных платежей (с учетом типа оплаты)
        $paymentsQuery = Payment::query()
            ->whereDate('created_at', '>=', $startDate)
            ->whereDate('created_at', '<=', $endDate);

        if ($paymentType) {
            $paymentsQuery->where('type', $paymentType);
        }

        $paymentsReceived = $paymentsQuery->sum('amount');

        // Количество должников
        $debtorsCount = Debt::query()
            ->whereDate('created_at', '>=', $startDate)
            ->whereDate('created_at', '<=', $endDate)
            ->distinct('client_id')
            ->count();

        return [
            'debts_given' => $debtsGiven,
            'payments_received' => $paymentsReceived,
            'left_to_collect' => max(0, $debtsGiven - $paymentsReceived),
            'debtors_count' => $debtorsCount
        ];
    }

    private function getDebtsChartData($startDate, $endDate, $period = 'week', $paymentType = null)
    {
        // Базовый запрос для долгов
        $debtsQuery = Debt::query()
            ->whereDate('created_at', '>=', $startDate)
            ->whereDate('created_at', '<=', $endDate);

        // Базовый запрос для платежей
        $paymentsQuery = Payment::query()
            ->whereDate('created_at', '>=', $startDate)
            ->whereDate('created_at', '<=', $endDate);

        if ($paymentType) {
            $paymentsQuery->where('type', $paymentType);
        }

        switch ($period) {
            case 'week':
            case 'month':
                $debtsData = $debtsQuery->selectRaw('
                    DATE(created_at) as date,
                    SUM(amount) as daily_debts
                ')
                ->groupBy('date')
                ->get();

                $paymentsData = $paymentsQuery->selectRaw('
                    DATE(created_at) as date,
                    SUM(amount) as daily_payments
                ')
                ->groupBy('date')
                ->get();

                $dates = $debtsData->pluck('date')
                    ->merge($paymentsData->pluck('date'))
                    ->unique()
                    ->sort()
                    ->values();

                return [
                    'categories' => $dates->map(function($date) {
                        return Carbon::parse($date)->format('j F');
                    })->all(),
                    'debts' => $dates->map(function($date) use ($debtsData) {
                        return $debtsData->firstWhere('date', $date)->daily_debts ?? 0;
                    })->all(),
                    'payments' => $dates->map(function($date) use ($paymentsData) {
                        return $paymentsData->firstWhere('date', $date)->daily_payments ?? 0;
                    })->all()
                ];

            case 'months':
                $debtsData = $debtsQuery->selectRaw('
                    DATE_FORMAT(created_at, "%Y-%m") as month,
                    SUM(amount) as monthly_debts
                ')
                ->groupBy('month')
                ->orderBy('month')
                ->get();

                $paymentsData = $paymentsQuery->selectRaw('
                    DATE_FORMAT(created_at, "%Y-%m") as month,
                    SUM(amount) as monthly_payments
                ')
                ->groupBy('month')
                ->orderBy('month')
                ->get();

                $months = $debtsData->pluck('month')
                    ->merge($paymentsData->pluck('month'))
                    ->unique()
                    ->sort()
                    ->values();

                return [
                    'categories' => $months->map(function($month) {
                        return Carbon::parse($month)->format('F Y');
                    })->all(),
                    'debts' => $months->map(function($month) use ($debtsData) {
                        return $debtsData->firstWhere('month', $month)->monthly_debts ?? 0;
                    })->all(),
                    'payments' => $months->map(function($month) use ($paymentsData) {
                        return $paymentsData->firstWhere('month', $month)->monthly_payments ?? 0;
                    })->all()
                ];

            case 'years':
                $debtsData = $debtsQuery->selectRaw('
                    YEAR(created_at) as year,
                    SUM(amount) as yearly_debts
                ')
                ->groupBy('year')
                ->orderBy('year')
                ->get();

                $paymentsData = $paymentsQuery->selectRaw('
                    YEAR(created_at) as year,
                    SUM(amount) as yearly_payments
                ')
                ->groupBy('year')
                ->orderBy('year')
                ->get();

                $years = $debtsData->pluck('year')
                    ->merge($paymentsData->pluck('year'))
                    ->unique()
                    ->sort()
                    ->values();

                return [
                    'categories' => $years->map(function($year) {
                        return $year . ' г.';
                    })->all(),
                    'debts' => $years->map(function($year) use ($debtsData) {
                        return $debtsData->firstWhere('year', $year)->yearly_debts ?? 0;
                    })->all(),
                    'payments' => $years->map(function($year) use ($paymentsData) {
                        return $paymentsData->firstWhere('year', $year)->yearly_payments ?? 0;
                    })->all()
                ];
        }
    }
}