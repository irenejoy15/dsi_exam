<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SecurityAnswer extends Model
{
    //
    protected $fillable = [
        'user_id',
        'first_question',
        'first_answer',
        'second_question',
        'second_answer',
        'third_question',
        'third_answer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
