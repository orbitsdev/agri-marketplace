<?php

namespace App\Models;

use App\Models\Farmer;
use App\Models\Product;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = ['product_id', 'buyer_id', 'farmer_id', 'parent_id', 'content', 'creator', 'is_read'];

    protected $casts = [
        'creator' => 'string',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function buyer()
    {
        return $this->belongsTo(User::class, 'buyer_id');
    }

    public function farmer()
    {
        return $this->belongsTo(Farmer::class, 'farmer_id');
    }

    public function replies()
    {
        return $this->hasMany(Comment::class, 'parent_id')->with('replies'); // Recursive relation
    }

    public function parent()
    {
        return $this->belongsTo(Comment::class, 'parent_id');
    }

    public function scopeVisibleToUser($query, $buyerId, $farmerId, $productId)
{
    return $query->where('product_id', $productId)
        ->where(function ($query) use ($buyerId, $farmerId) {
            $query->where('buyer_id', $buyerId) // Show comments for the authenticated buyer
                  ->orWhere('farmer_id', $farmerId); // Show all comments to the farmer (product owner)
        });
}

public function scopePrivateToFarmerAndBuyer($query, $buyerId, $farmerId, $productId)
{
    return $query->where('product_id', $productId)->where('farmer_id', $farmerId)->where('buyer_id', $buyerId)
    ;

}




}
