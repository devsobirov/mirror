<?php

namespace App\Http\Controllers\Finance;

use App\Enums\Finance\DebtStatusEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\Finance\DebtCreateRequest;
use App\Http\Requests\Finance\PaymentCreateRequest;
use App\Models\Finance\Debt;
use App\Models\Finance\Payment;
use App\Services\Finance\DebtService;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;

class DebtsController extends Controller
{
    public function index(Request $request)
    {
        $query = Debt::query();

        // Применяем фильтры
        $query->byStatus($request->input('status') ?: DebtStatusEnum::Pending->value)
              ->byPaymentDateFrom($request->input('date_from'))
              ->byPaymentDateTo($request->input('date_to'))
              ->byClient($request->input('search_client'));

        $items = $query->orderBy('payment_date', $request->sort ?: 'desc')->paginate()->withQueryString();

        // Генерируем агрегаты только для админа
        $stats = auth()->user()->isAdmin() ? $this->generateStats($query) : null;

        return view('finance.debts', compact('items', 'stats'));
    }

    private function generateStats(Builder $query)
    {
        $sum = $query->sum('amount');
        $paid = $query->withSum('payments as paid_sum', 'amount')->get()->sum('paid_sum');
        return [
            'total_debts' => $sum,
            'total_paid' => $paid,
            'total_left' => $sum - $paid,
            'debtors_count' => $query->distinct('client_id')->count('client_id'),
        ];
    }

    public function create(DebtCreateRequest $request)
    {
        Debt::create($request->validated());
        return redirect()->back()->with('success', 'Muvaffaqiyatli saqlandi');
    }

    public function pay(PaymentCreateRequest $request, Debt $debt)
    {
        Payment::create($request->validated());
        $debt->load('payments');
        DebtService::updateDebtStatus($debt);
        DebtService::updateDebtRating($debt);
        return redirect()->back()->with('success', 'Muvaffaqiyatli saqlandi');
    }
}
