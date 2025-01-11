<?php

namespace App\Http\Requests\Finance;

use App\Enums\Finance\DebtStatusEnum;
use App\Models\Finance\Debt;
use Illuminate\Foundation\Http\FormRequest;

class DebtCreateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->isWorker();
    }

    public function rules(): array
    {
        return [
            'created_at' => 'nullable|date',
            'payment_date' => 'required|date',
            'amount' => 'required|numeric',
            'client_id' => 'required|exists:clients,id',
            'caption' => 'nullable|string',
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'status' => DebtStatusEnum::Pending->value,
            //'crated_at' => $this->input('created_at') ?: now()->format('Y-m-d'),
        ]);
    }
}
