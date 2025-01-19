<?php

namespace App\Models;

use App\Models\User;
use App\Models\Farmer;
use App\Models\OrderItem;
use App\Models\Transaction;
use App\Models\OrderMovement;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory;


    protected $casts = [
        'order_date' => 'datetime', // Ensure order_date is cast as a datetime
        'shipped_date' => 'datetime',
        'delivery_date' => 'datetime',
    ];
    
   
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
        self::PENDING =>  self::PENDING,
        self::PROCESSING =>  self::PROCESSING,
        self::CONFIRMED =>  self::CONFIRMED,
        self::SHIPPED =>  self::SHIPPED,
        self::OUT_FOR_DELIVERY =>  self::OUT_FOR_DELIVERY,
        self::COMPLETED =>  self::COMPLETED,
        self::CANCELLED =>  self::CANCELLED,
        self::RETURNED =>  self::RETURNED,
    ];

    // use the admin
    public const ADMIN_ORDER_MANAGE_OPTIONS = [
        self::PENDING =>  self::PENDING,
        self::CONFIRMED =>  self::CONFIRMED,
        self::SHIPPED =>  self::SHIPPED,
        self::OUT_FOR_DELIVERY =>  self::OUT_FOR_DELIVERY,
        self::COMPLETED =>  self::COMPLETED,
        self::CANCELLED =>  self::CANCELLED,
        self::RETURNED =>  self::RETURNED,
    ];

    public const IF_PENDING = [
        self::CONFIRMED => self::CONFIRMED,
        self::CANCELLED => self::CANCELLED,
    ];

   
    public const IF_CONFIRMED = [
        self::SHIPPED => self::SHIPPED,
        self::CANCELLED => self::CANCELLED,
    ];

    public const IF_SHIPPED = [
        self::OUT_FOR_DELIVERY => self::OUT_FOR_DELIVERY,
        self::COMPLETED => self::COMPLETED,
        self::RETURNED => self::RETURNED,
        self::CANCELLED => self::CANCELLED,
        
    ];

    public const IF_OUT_FOR_DELIVERY = [
        self::COMPLETED => self::COMPLETED,
        self::CANCELLED => self::CANCELLED,
        self::RETURNED => self::RETURNED,
        // self::RETURNED => self::RETURNED,
    ];

    public const IF_COMPLETED = [];
    public const IF_CANCELLED = [
        self::CONFIRMED => self::CONFIRMED,
        self::SHIPPED => self::SHIPPED,
    ]; 

    public const IF_RETURNED = [
        self::SHIPPED => self::SHIPPED,
        self::COMPLETED => self::COMPLETED,
    ];
    // Payment Method Constants
    public const PAYMENT_COD = 'COD (Cash on Delivery)';
    public const PAYMENT_ONLINE = 'ONLINE (Online Payment)';

    public const PAYMENT_METHOD_OPTIONS = [
        self::PAYMENT_COD => self::PAYMENT_COD,
        self::PAYMENT_ONLINE => self::PAYMENT_ONLINE,
    ];

    public  function getAvailableStatusTransitions(): array
    {
        $statusTransitions = [
            self::PENDING => self::IF_PENDING,
       
            self::CONFIRMED => self::IF_CONFIRMED,
            self::SHIPPED => self::IF_SHIPPED,
            self::OUT_FOR_DELIVERY => self::IF_OUT_FOR_DELIVERY,
            self::COMPLETED => self::IF_COMPLETED,
            self::CANCELLED => self::IF_CANCELLED,
            self::RETURNED => self::IF_RETURNED,
        ];

        return $statusTransitions[$this->status] ?? [];
    }

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
    public function scopeMyBuyersOrder($query)
    {
        return $query->where('farmer_id', Auth::user()->farmer->id);
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopePendingOrProcessing($query)
    {
        return $query->whereIn('status', [self::PENDING, self::PROCESSING]);
    }
    public function scopeNotProcessing($query)
    {
        return $query->where('status', '!=', self::PROCESSING);
    }
    public function scopePending($query)
    {
        return $query->where('status', self::PENDING);
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
    public function calculateTotalOrders()
    {
        return $this->items->sum(fn($item) => $item->price_per_unit * $item->quantity);
    }


    public function getTotalAttribute()
    {
        return $this->calculateTotal();
    }

    public function getFormattedTotalAttribute()
    {
        return '₱' . number_format($this->calculateTotalOrders(), 2);
    }

    public function getSubtotalAttribute()
    {
        return $this->calculateSubtotal();
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
            get: fn($value) => \Carbon\Carbon::parse($value)->format('F j, Y g:i a'),
            // get: fn ($value) => \Carbon\Carbon::parse($value)->format('F j, Y g:i a'),
        );
    }

    // Accessor for shipped_date
    protected function shippedDate(): Attribute
    {
        return Attribute::make(
            get: fn($value) => $value ? \Carbon\Carbon::parse($value)->format('F j, Y g:i a') : '',
        );
    }

    // Accessor for delivery_date
    protected function deliveryDate(): Attribute
    {
        return Attribute::make(
            get: fn($value) => $value ? \Carbon\Carbon::parse($value)->format('F j, Y g:i a') : '',
        );
    }
    public function updateTotal()
    {
        $this->total = $this->items->sum('subtotal'); // Sum of all OrderItem subtotals
        $this->save();
    }


    public function isDeliveryInformationComplete(): bool
    {
        return !(
            empty($this->region) ||
            empty($this->province) ||
            empty($this->city_municipality) ||
            empty($this->barangay) ||
            empty($this->street) ||
            empty($this->zip_code) ||
            empty($this->payment_method)
        );
    }

    public function getMissingDeliveryFields(): array
    {
        $fields = [
            'region' => $this->region,
            'province' => $this->province,
            'city_municipality' => $this->city_municipality,
            'barangay' => $this->barangay,
            'street' => $this->street,
            'zip_code' => $this->zip_code,
            'payment_method' => $this->payment_method,
        ];

        // Return the list of keys with missing values
        return array_keys(array_filter($fields, fn($value) => empty($value)));
    }

    // order total items price * quantity 


    public function orderMovements()
    {
        return $this->hasMany(OrderMovement::class);
    }

    public function getLatestMovements()
{
    return $this->orderMovements()->latest()->get();
}

//stats

public function scopeTotalOrders($query)
{
    return $query->count();
}

public function scopeByFarmer($query, $farmerId)
{
    return $query->where('farmer_id', $farmerId);
}



public function scopeCompletedOrders($query)
{
    return $query->where('status', self::COMPLETED)->count();
}

public function scopeCancelledOrders($query)
{
    return $query->where('status', self::CANCELLED)->count();
}

public function scopePendingOrders($query)
{
    return $query->where('status', self::PENDING)->count();
}

public function scopeReturnedOrders($query)
{
    return $query->where('status', self::RETURNED)->count();
}

public function scopeOutForDelivery($query)
{
    return $query->where('status', self::OUT_FOR_DELIVERY)->count();
}
public function scopeShipped($query)
{
    return $query->where('status', self::SHIPPED)->count();
}


}
