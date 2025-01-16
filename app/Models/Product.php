<?php

namespace App\Models;

use App\Models\User;
use App\Models\Farmer;
use App\Models\Category;
use Illuminate\Support\Str;
use Spatie\MediaLibrary\HasMedia;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\Factories\HasFactory;

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

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
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


    public function scopeByStatus($query, $status = null)
    {
        return $status ? $query->where('status', $status) : $query;
    }


    // scopre for available
    public function scopeAvailable($query)
    {
        return $query->where('status', self::AVAILABLE);
    }


    //scope with relation farmer
    public function scopeWithRelations($query){
        return $query->whereHas('farmer.user')->with(['category','farmer.user','media']);
    }

    public function nameWithPrice(){
        return $this->product_name. ' Php '.$this->price.' ';
    }




public function getSlugAttribute()
{
    $farmerName = $this->farmer ? Str::slug($this->farmer->farm_name ?? $this->farmer->user->name) : null;

    // Combine farmer name with product name
    if ($farmerName) {
        return $farmerName . '-' . Str::slug($this->product_name);
    }

    // Fallback to product name only
    return Str::slug($this->product_name);
}



public function scopeMyProduct($query, $farmerId)
{
    return $query->where('farmer_id', $farmerId);
}
public function scopeByCategory($query, $categoryId = null)
{
    return $categoryId ? $query->where('category_id', $categoryId) : $query;
}

//scope for is publis

    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }


}
