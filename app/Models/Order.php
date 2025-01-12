<?php

namespace App\Models;

use App\Models\User;
use App\Models\Farmer;
use App\Models\OrderItem;
use App\Models\Transaction;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
class Order extends Model
{
    use HasFactory;

    // make a static for statue
    use HasFactory;

    // Order Status Constants
    public const PENDING = 'Pending';
    public const PROCESSING = 'Processing';
    public const CONFIRMED = 'Confirmed';
    public const SHIPPED = 'Shipped';
    public const OUT_FOR_DELIVERY = 'Out for Delivery';
    public const COMPLETED = 'Completed';
    public const CANCELLED = 'Cancelled';
    public const RETURNED = 'Returned';

    // Status Options
    public const STATUS_OPTIONS = [
        self::PENDING,
        self::PROCESSING,
        self::CONFIRMED,
        self::SHIPPED,
        self::OUT_FOR_DELIVERY,
        self::COMPLETED,
        self::CANCELLED,
        self::RETURNED,
    ];

    // Payment Method Constants
    public const PAYMENT_COD = 'Cash on Delivery';
    public const PAYMENT_ONLINE = 'Online Payment';
    public const PAYMENT_METHOD_OPTIONS = [
        self::PAYMENT_COD,
        self::PAYMENT_ONLINE,
    ];

    // Relationships
    public function buyer()
    {
        return $this->belongsTo(User::class, 'buyer_id');
    }

    public function farmer()
    {
        return $this->belongsTo(Farmer::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    // Scopes
    public function scopeForBuyer($query, $buyerId)
    {
        return $query->where('buyer_id', $buyerId);
    }

    public function scopeForFarmer($query, $farmerId)
    {
        return $query->where('farmer_id', $farmerId);
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopePendingOrProcessing($query)
    {
        return $query->whereIn('status', [self::PENDING, self::PROCESSING]);
    }

    // Helper Methods
    public function hasCompleteLocation()
    {
        return $this->region && $this->province && $this->city_municipality &&
            $this->barangay && $this->street && $this->zip_code;
    }

    public function isReadyToPlace()
    {
        return $this->hasCompleteLocation() && $this->calculateTotal() > 0;
    }

    public function getFormattedAddressAttribute()
    {
        return "{$this->street}, {$this->barangay}, {$this->city_municipality}, {$this->province}, {$this->region}, {$this->zip_code}";
    }

    public function updateStatus($status)
    {
        if (in_array($status, self::STATUS_OPTIONS)) {
            $this->status = $status;
            $this->save();
        } else {
            throw new \Exception("Invalid status: {$status}");
        }
    }

    // Total Calculation
    public function calculateTotal()
    {
        return $this->items->sum('subtotal');
    }

    public function getTotalAttribute()
    {
        return $this->calculateTotal();
    }

    public function getFormattedTotalAttribute()
    {
        return '₱' . number_format($this->calculateTotal(), 2);
    }


    public function scopeGroupedByFarmer($query, $buyerId)
{
    return $query->where('buyer_id', $buyerId)
        ->with('farmer.user')
        ->get()
        ->groupBy(fn($order) => $order->farmer->id);
}
protected function orderDate(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => \Carbon\Carbon::parse($value)->format('F j, Y g:i a'),
            // get: fn ($value) => \Carbon\Carbon::parse($value)->format('F j, Y g:i a'),
        );
    }

    // Accessor for shipped_date
    protected function shippedDate(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value ? \Carbon\Carbon::parse($value)->format('F j, Y g:i a') : '',
        );
    }

    // Accessor for delivery_date
    protected function deliveryDate(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value ? \Carbon\Carbon::parse($value)->format('F j, Y g:i a') : '',
        );
    }
    public function updateTotal()
{
    $this->total = $this->items->sum('subtotal'); // Sum of all OrderItem subtotals
    $this->save();
}

    
}
