<?php

namespace App\Models;

use App\Models\Farmer;
use App\Models\Product;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Comment extends Model
{
    use HasFactory;

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

    public function scopeVisibleToUser($query, $farmerId, $productId, $buyerId)
    {
        return $query->where('farmer_id', $farmerId)
            ->where('product_id', $productId)
            ->where('buyer_id', $buyerId);
    }
}
