<?php

namespace App\Http\Requests\Finance;

use App\Models\Finance\Debt;
use App\Models\Finance\Payment;
use Illuminate\Foundation\Http\FormRequest;

class PaymentCreateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->isWorker();
    }

    public function rules(): array
    {
        return [
            'client_id' => 'required|integer|exists:clients,id',
            'debt_id' => 'required|integer|exists:debts,id',
            'amount' => 'required|numeric|min:1000|max:' . $this->route('debt')->left_amount,
            'type' => 'numeric',
            'left_debt_amount' => 'numeric',
            'rating' => 'nullable|numeric|min:1|max:5',
        ];
    }

    protected function prepareForValidation(): void
    {
        /** @var Debt $debt */
        $debt = $this->route('debt');
        $this->merge([
            'debt_id' => $debt->id,
            'left_debt_amount' => Payment::calcLeftPaymentAmount($debt, $this->post('amount')),
        ]);
    }
}
