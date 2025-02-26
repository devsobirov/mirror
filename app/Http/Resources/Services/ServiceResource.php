<?php

namespace App\Http\Resources\Services;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ServiceResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'image' => $this->image_url,
            'category_id' => $this->category_id,
            'active' => $this->active,
            'unit' => $this->unit,
            'price' => $this->price,
            'full_price' => $this->full_price,
            'margin' => $this->margin,
            'calculator' => $this->calculator_id ?: null,
            'sub_services' => $this->subServices->isNotEmpty() ? ServiceResource::collection($this->subServices): []
        ];
    }

}