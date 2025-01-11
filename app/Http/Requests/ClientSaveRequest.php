<?php

namespace App\Http\Requests;

use App\Helpers\PhoneNormalizer;
use Illuminate\Foundation\Http\FormRequest;

class ClientSaveRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->isWorker();
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'phone' => 'required|'. $this->getUniquePhoneRules(),
            'phone2' => 'nullable|'. $this->getUniquePhoneRules(),
            'address' => 'nullable|string|max:255',
        ];
    }

    public function prepareForValidation(): void
    {
        $this->merge([
            'phone' => PhoneNormalizer::clearPhoneNumber($this->input('phone')),
            'phone2' => PhoneNormalizer::clearPhoneNumber($this->input('phone2')),
        ]);
    }

    private function getUniquePhoneRules(): string
    {
        $first = "unique:clients,phone,{$this->route('client')?->id}";
        $second = "unique:clients,phone2,{$this->route('client')?->id}";

        return "$first|$second";
    }
}
