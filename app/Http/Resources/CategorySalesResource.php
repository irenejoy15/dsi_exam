<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CategorySalesResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'name' => $this->name,
            'total_quantity' => $this->total_quantity,
            'color' => sprintf('#%06X', mt_rand(0, 0xFFFFFF)),
        ];
    }
}
