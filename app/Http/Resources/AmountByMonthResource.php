<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AmountByMonthResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {   
        if($this->month == 1){
            $this->month = 'January';
        }elseif($this->month == 2){
            $this->month = 'February';
        }elseif($this->month == 3){
            $this->month = 'March';
        }elseif($this->month == 4){
            $this->month = 'April';
        }elseif($this->month == 5){
            $this->month = 'May';
        }elseif($this->month == 6){
            $this->month = 'June';
        }elseif($this->month == 7){
            $this->month = 'July';
        }elseif($this->month == 8){
            $this->month = 'August';
        }elseif($this->month == 9){
            $this->month = 'September';
        }elseif($this->month == 10){
            $this->month = 'October';
        }elseif($this->month == 11){
            $this->month = 'November';
        }elseif($this->month == 12){
            $this->month = 'December';
        }
        return [
            'month' => $this->month,
            'total_amount' => $this->total_amount,
            'color' => sprintf('#%06X', mt_rand(0, 0xFFFFFF)),
        ];
    }
}
