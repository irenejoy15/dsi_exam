<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TotalAmountResource extends JsonResource
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
            'total_amount' => $this->total_amount,
            'color' => sprintf('#%06X', mt_rand(0, 0xFFFFFF)),
        ];
    }
}
