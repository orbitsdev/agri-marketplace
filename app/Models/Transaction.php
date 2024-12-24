<?php

namespace App\Models;

use App\Models\Order;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Transaction extends Model
{
    use HasFactory;

    public const PENDING = 'Pending';
    public const COMPLETED = 'Completed';
    public const FAILED = 'Failed';

    public const STATUS_OPTIONS = [
        self::PENDING => self::PENDING,
        self::COMPLETED => self::COMPLETED,
        self::FAILED => self::FAILED,
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
