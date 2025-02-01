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

class DebtsController extends Controller
{
    public function index()
    {
        $items = Debt::query()
            ->byStatus(DebtStatusEnum::Pending->value)
            ->with('client', 'payments')
            ->orderBy('payment_date')
            ->paginate();

        return view('finance.debts', compact('items'));
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
        return redirect()->back()->with('success', 'Muvaffaqiyatli saqlandi');
    }
}
