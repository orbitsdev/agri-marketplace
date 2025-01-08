<?php

namespace App\Models;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OrderItem extends Model
{
    use HasFactory;


    public function order()
    {
        return $this->belongsTo(Order::class);
    }
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function calculateSubtotal()
    {
        return $this->quantity * $this->price_per_unit;
    }

    public function getSubtotalAttribute()
    {
        return $this->calculateSubtotal();
    }

    public function getFormattedSubtotalAttribute()
    {
        return '₱' . number_format($this->calculateSubtotal(), 2);
    }

}
