<?php

namespace App\Http\Requests\Offers;

use App\Enums\Offer\OfferStatusEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SaveOfferRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->isWorker();
    }

    public function rules(): array
    {
        return [
            'client_id' => 'nullable|integer|exists:clients,id',
            'client_name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:255',
            'phone2' => 'nullable|string|max:255',
            'comment' => 'nullable|string',
            'status' => ['required', 'integer', Rule::in(array_keys(OfferStatusEnum::list()))],
            'discount' => 'required|numeric|min:0',
            'offer_items' => 'required|array|min:1',
            'offer_items.*.service_id' => 'required|exists:services,id',
            'offer_items.*.quantity' => 'required|numeric|min:1',
            'offer_items.*.price' => 'required|numeric|min:0',
            'offer_items.*.original_price' => 'required|numeric|min:0',
            'offer_items.*.cost' => 'required|numeric|min:0',
            'offer_items.*.item_cost' => 'required|numeric|min:0',
            'offer_items.*.x' => 'nullable',
            'offer_items.*.y' => 'nullable',
            'offer_items.*.dimension_amount' => 'numeric|nullable',
            'offer_items.*.parent_item_id' => 'nullable|integer',
            'offer_items.*.sub_items' => 'nullable|array',
            'offer_items.*.sub_items.*.service_id' => 'required|exists:services,id',
            'offer_items.*.sub_items.*.quantity' => 'required|numeric|min:1',
            'offer_items.*.sub_items.*.price' => 'required|numeric|min:0',
            'offer_items.*.sub_items.*.original_price' => 'required|numeric|min:0',
            'offer_items.*.sub_items.*.cost' => 'required|numeric|min:0',
            'offer_items.*.sub_items.*.item_cost' => 'required|numeric|min:0',
            'offer_items.*.sub_items.*.x' => 'nullable',
            'offer_items.*.sub_items.*.y' => 'nullable',
            'offer_items.*.sub_items.*.dimension_amount' => 'numeric|nullable',
            'created_at' => 'nullable',
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'client_id' => $this->input('client.id') ?: null,
            'client_name' => $this->input('client.name'),
            'phone' => $this->clearPhone($this->input('client.phone_number')),
            'phone2' => $this->clearPhone($this->input('client.phone_number2')),
            'comment' => $this->input('client.note'),
            'status' => $this->input('status', OfferStatusEnum::Draft->value),
            'created_at' => $this->input('created_at') ? ($this->input('created_at').' '.now()->format('H:i:s')) : now()->format('d-m-Y H:i:s'),
        ]);
    }

    private function clearPhone($phone): ?string
    {
        if ($phone) {
            $phone = preg_replace('/\D/', '', $phone);

            return $phone ?: null;
        }

        return null;
    }

    public function messages(): array
    {
        return [
            'client_id.exists' => 'Tanlangan mijoz bazada topilmadi',
            'client_name.required' => 'Mijoz ismini kiriting',
            'client_name.max' => 'Mijoz ismi juda uzun',
            'phone.required' => 'Telefon raqamini kiriting',
            'phone.max' => 'Telefon raqami juda uzun',
            'discount.required' => 'Chegirma miqdorini kiriting',
            'discount.numeric' => 'Chegirma son bo\'lishi kerak',
            'discount.min' => 'Chegirma 0 dan kam bo\'lmasligi kerak',
            'offer_items.required' => 'Buyurtmaga kamida bitta xizmat qo\'shing',
            'offer_items.array' => 'Noto\'g\'ri ma\'lumot formati',
            'offer_items.*.quantity.required' => 'Xizmat miqdorini kiriting',
            'offer_items.*.quantity.numeric' => 'Xizmat miqdori son bo\'lishi kerak',
            'offer_items.*.quantity.min' => 'Xizmat miqdori 1 dan kam bo\'lmasligi kerak',
            'offer_items.*.price.required' => 'Xizmat narxini kiriting',
            'offer_items.*.price.numeric' => 'Xizmat narxi son bo\'lishi kerak',
            'offer_items.*.price.min' => 'Xizmat narxi 0 dan kam bo\'lmasligi kerak',
            'offer_items.*.original_price.required' => 'Xizmatning asl narxini kiriting',
            'offer_items.*.original_price.numeric' => 'Xizmatning asl narxi son bo\'lishi kerak',
            'offer_items.*.original_price.min' => 'Xizmatning asl narxi 0 dan kam bo\'lmasligi kerak',
            'offer_items.*.cost.required' => 'Xizmat umumiy narxini kiriting',
            'offer_items.*.cost.numeric' => 'Xizmat umumiy narxi son bo\'lishi kerak',
            'offer_items.*.cost.min' => 'Xizmat umumiy narxi 0 dan kam bo\'lmasligi kerak',
            'offer_items.*.service_id.required' => 'Xizmat turini tanlang',
            'offer_items.*.service_id.exists' => 'Tanlangan xizmat turi mavjud emas',
            'status.required' => 'Выберите статус заказа',
            'status.integer' => 'Некорректный формат статуса',
            'status.in' => 'Выбран несуществующий статус',
        ];
    }
}
