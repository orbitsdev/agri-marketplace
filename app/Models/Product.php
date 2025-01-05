<?php

namespace App\Models;

use App\Models\User;
use App\Models\Farmer;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\HasMedia;
class Product extends Model implements HasMedia
{
    use HasFactory;
    use InteractsWithMedia;
    public const AVAILABLE = 'Available';
    public const SOLD = 'Sold';
    public const PENDING = 'Pending';

    protected $casts = [
        'is_published' => 'boolean',
    ];
    

    public const STATUS_OPTIONS = [
        self::AVAILABLE => self::AVAILABLE,
        self::SOLD => self::SOLD,
        self::PENDING => self::PENDING,
    ];


    public function farmer()
    {
        return $this->belongsTo(Farmer::class,);
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('image')->singleFile();
        
    }

  
    public function getImage()
    {
        if ($this->hasMedia()) {
            return $this->getFirstMediaUrl();
        }

        return asset('images/placeholder-image.jpg');
    }

    public function scopeHasQuantity($query)
    {
        return $query->where('quantity', '>', 0);
    }

    
}
