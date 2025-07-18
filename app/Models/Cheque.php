<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cheque extends Model
{
    use HasFactory;

     protected $fillable = [
        'cheque_number',
        'bank_name',
        'cheque_date',
        'amount',
        'status',
        'notes',
    ];


    public function sale()
    {
        return $this->hasOne(Sale::class);
    }
}
