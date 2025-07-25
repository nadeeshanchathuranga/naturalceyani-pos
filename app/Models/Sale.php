<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'employee_id',
        'user_id',
        'order_id',
        'total_amount',
        'discount',
        'payment_method',
        'sale_date',
        'total_cost',
        'cash',
        'custom_discount',
        'is_return_bill',
        'return_date',
        'is_whole',
        'guide_name',
        'guide_comi',
        'guide_cash',
        'guide_pending',
        'total_to_include_guide',
        'credit_bill',
        'cheque_id'
    ];

public function cheque()
{
    return $this->belongsTo(Cheque::class);
}


    public function sale()
    {
        return $this->belongsTo(Sale::class, 'sale_id','id');
    }

    public function saleItem()
    {
        return $this->belongsTo(SaleItem::class, 'order_id','id');
    }
    public function saleItems()
    {
        return $this->hasMany(SaleItem::class);
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id','id');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id','id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id','id');
    }

     // Accessor for final total
    public function getFinalTotalAttribute()
    {
        $discountRate = $this->custom_discount ?? 0;
        $discounted = ($this->total_amount ?? 0) * (1 - $discountRate / 100);
        return $discounted + ($this->guide_cash ?? 0);
    }
}
