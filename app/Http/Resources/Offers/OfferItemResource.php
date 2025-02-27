<?php

namespace App\Http\Resources\Offers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OfferItemResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $hasDiscount = $this->price < $this->original_price;
        $dm = $this->dimension_amount ?: 1;

        return [
            'id' => $this->id,
            'parent_item_id' => $this->parent_item_id,
            'service_id' => $this->service_id,
            'service_name' => $this->service->name,
            'service_image' => $this->service->image_url,
            'quantity' => $dm == 1 ? 1 : $this->quantity, //**
            //number_format($this->quantity, 2, '.', ' '),
            'price' => number_format($this->price, 0, '.', ' '),
            'original_price' => $hasDiscount ? number_format($this->original_price, 0, '.', ' ') : null,
            'item_total' => number_format($this->item_cost, 0, '.', ' '),
            'total' => number_format($this->cost, 0, '.', ' '),
            'unit' => $this->service->unit,
            'has_discount' => $hasDiscount,
            'x' => $this->x,
            'y' => $this->y,
            'dimention_amount' => $this->dimention_amount ?: 1,
            'total_amount' => ($dm != 1 ? round($dm * $this->quantity, 2) : $this->quantity) . " " . $this->service->unit,
            'calculator' => $this->service?->calculator_id,
            'memo' => $this->memo,
            'full_memo' => $this->full_memo,
            'sub_items' => $this->subItems->isNotEmpty() ? self::collection($this->subItems): []
        ];
    }
}
