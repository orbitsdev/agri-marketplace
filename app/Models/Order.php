<?php

namespace App\Models;

use App\Models\User;
use App\Models\OrderItem;
use App\Models\Transaction;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory;

    // make a static for statue
    public const PENDING = 'Pending';
    public const CONFIRM = 'Confirm';
    public const CANCEL = 'Cancel';
    public const COMPLETE = 'Complete';

    // make a static for status options
    public const STATUS_OPTIONS = [
        self::PENDING => self::PENDING,
        self::CONFIRM => self::CONFIRM,
        self::CANCEL => self::CANCEL,
        self::COMPLETE => self::COMPLETE,
    ];


    public function buyer()
    {
        return $this->belongsTo(User::class, 'buyer_id');
    }

    // order items
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    // transaction
    public function transaction()
    {
        return $this->hasOne(Transaction::class);
    }

    // scope for pending
    public function scopePending($query)
    {
        return $query->where('status', 'Pending');
    }

    //scope for confirm
    public function scopeConfirm($query)
    {
        return $query->where('status', 'Confirm');
    }
    
    // scope for cancel
    public function scopeCancel($query)
    {
        return $query->where('status', 'Cancel');
    }

    // scope for complete
    public function scopeComplete($query)
    {
        return $query->where('status', 'Complete');
    }

    // sum of order items
    public function getFormattedSubtotalAttribute()
    {
        return '₱' . number_format($this->items->sum('subtotal'), 2);
    }

    //get formatted subtotal
    public function getFormattedTotalAttribute()
    {
        return '₱' . number_format($this->total, 2);
    }

    // price times quantity 
    public function getTotalAttribute()
    {
        return $this->items->sum('subtotal');
    }

    
}
