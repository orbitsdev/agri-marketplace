<?php

namespace App\Models;

use App\Models\User;
use App\Models\Farmer;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;
    
    public const AVAILABLE = 'Available';
    public const SOLD = 'Sold';
    public const PENDING = 'Pending';


    public const STATUS_OPTIONS = [
        self::AVAILABLE => self::AVAILABLE,
        self::SOLD => self::SOLD,
        self::PENDING => self::PENDING,
    ];


    public function farmer()
    {
        return $this->belongsTo(Farmer::class,);
    }

    
}
