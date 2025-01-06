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



 public function scopeSelected($query)
 {
     return $query->where('is_selected', true);
 }


 public function scopeTotalQuantity($query)
 {
     return $query->where('is_selected', true)->sum('quantity');
 }

 // Get total value for selected cart items
 public function scopeTotalValueForSelected($query)
 {
     return $query->where('is_selected', true)
         ->sum(DB::raw('quantity * price_per_unit'));
 }





 public function scopeForBuyer($query, $buyerId)
 {
     return $query->where('buyer_id', $buyerId);
 }


 public function scopeCartItemsForBuyer($query, $buyerId)
 {
     return $query->where('buyer_id', $buyerId)->with('product');
 }


 public function scopeTotalPriceForBuyer($query, $buyerId)
 {
     return $query->where('buyer_id', $buyerId)
         ->sum(DB::raw('quantity * price_per_unit'));
 }


 public function scopeWithProductDetails($query)
 {
     return $query->with('product');
 }

 public function scopeTotalCartItems($query, $buyerId)
{
    return $query->where('buyer_id', $buyerId)->count();
}

public static function getTotalCartItems($buyerId)
{
    return self::where('buyer_id', $buyerId)->sum('quantity');
}

// total carts

public function totalBuyerCartItems($buyerId){
    return self::where('buyer_id', $buyerId)->count();
}
public function scopeCartItemsWithDetails($query, $buyerId)
{
    return $query->where('buyer_id', $buyerId)
        ->with('product.media');
}
//scope selected
public function scopeSelectedCartItems($query, $buyerId)
{
    return $query->where('buyer_id', $buyerId)->where('is_selected', true);
}

//scope total selected cart items
}
