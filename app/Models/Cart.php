<?php

namespace App\Models;

use App\Models\User;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Cart extends Model
{
    use HasFactory;

   

    public function buyer()
{
    return $this->belongsTo(User::class, 'buyer_id');
}

public function product()
{
    return $this->belongsTo(Product::class, 'product_id');
}



// total quantity
public function getFormattedTotalQuantityAttribute()
{
    return number_format($this->total_quantity);
}


public function scopeTotalValue($query, $buyerId)
{
    return $query->where('buyer_id', $buyerId)
        ->sum(DB::raw('quantity * price_per_unit'));
}

}
