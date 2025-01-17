<?php

namespace App\Models;

use App\Models\User;
use App\Models\Order;
use App\Models\Product;
use App\Models\Document;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Farmer extends Model
{
    use HasFactory;

    public const STATUS_PENDING = 'Pending';
    public const STATUS_APPROVED = 'Approved';
    public const STATUS_REJECTED = 'Rejected';
    public const STATUS_BLOCKED = 'Blocked';

    // Status Options
    public const STATUS_OPTIONS = [
        self::STATUS_PENDING,
        self::STATUS_APPROVED,
        self::STATUS_REJECTED,
        self::STATUS_BLOCKED,
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }


    public function documents(){
        return $this->hasMany(Document::class,
            'farmer_id');
    }

    public function orders() {
        return $this->hasMany(Order::class);
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeApproved($query)
    {
        return $query->where('status', self::STATUS_APPROVED);
    }

    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    public function scopeRejected($query)
    {
        return $query->where('status', self::STATUS_REJECTED);
    }

    public function scopeBlocked($query)
    {
        return $query->where('status', self::STATUS_BLOCKED);
    }

    // public function scopeByScoreRange($query, $minScore, $maxScore)
    // {
    //     return $query->whereBetween('score', [$minScore, $maxScore]);
    // }

    // public function scopeHighPerformers($query, $threshold = 80)
    // {
    //     return $query->where('score', '>=', $threshold);
    // }

    public function scopeSearchByFarmName($query, $farmName)
    {
        return $query->where('farm_name', 'LIKE', '%' . $farmName . '%');
    }

    public function scopeWithProducts($query)
    {
        return $query->whereHas('products');
    }

    // Helper Methods
    public function isApproved(): bool
    {
        return $this->status === self::STATUS_APPROVED;
    }

    // public function incrementScore(int $value = 1): void
    // {
    //     $this->score += $value;
    //     $this->save();
    // }

    // public function decrementScore(int $value = 1): void
    // {
    //     $this->score = max(0, $this->score - $value);
    //     $this->save();
    // }

    public function updateStatus($status)
    {
        if (in_array($status, self::STATUS_OPTIONS)) {
            $this->status = $status;
            $this->save();
        } else {
            throw new \Exception("Invalid status: {$status}");
        }
    }

}
