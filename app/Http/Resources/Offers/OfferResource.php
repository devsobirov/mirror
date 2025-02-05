<?php

namespace App\Http\Resources\Offers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OfferResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'client_name' => $this->client_name,
            'phone_number' => $this->phone_number,
            'phone_number2' => $this->phone_number2,
            'comment' => $this->comment,
            'status' => $this->status,
            'status_name' => $this->status_name,
            'status_color' => $this->status_color,
            'created_at' => $this->created_at?->format('d.m.Y H:i'),
            'cost' => number_format($this->cost + $this->discount, 0, '.', ' '),
            'discount' => $this->discount,
            'discount_amount' => number_format($this->discount ?? 0, 0, '.', ' '),
            'final_cost' => number_format($this->cost, 0, '.', ' '),


            'manager' => $this->whenLoaded('manager', fn() => [
                'id' => $this->manager->id,
                'name' => $this->manager->name
            ]),
            'offer_items' => $this->whenLoaded('offerItems', 
                fn() => OfferItemResource::collection($this->offerItems)
            ),
        ];
    }

    protected function getStatusName(): string
    {
        return match($this->status) {
            'new' => 'Новый',
            'in_progress' => 'В работе',
            'completed' => 'Завершен',
            'cancelled' => 'Отменен',
            default => 'Неизвестно'
        };
    }

    protected function calculateTotalCost(): float
    {
        if (!$this->offerItems) {
            return 0;
        }

        return $this->offerItems->sum(function ($item) {
            return $item->quantity * $item->price;
        });
    }
}

