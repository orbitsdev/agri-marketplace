<?php

namespace App\Models;

use App\Models\Order;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OrderItem extends Model
{
    use HasFactory;

    public function order()
    {
        return $this->belongsTo(Order::class);
    }


    public function getFormattedSubtotalAttribute()
    {
        return '₱' . number_format($this->subtotal, 2);
    }



    //price time quantity

    public function getFormattedPriceAttribute()
    {
        return '₱' . number_format($this->price, 2);
    }
}
