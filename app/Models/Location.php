<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Location extends Model
{
    use HasFactory;

    protected $casts = [
        'is_default' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }


    public function scopeNotDefault($query)
    {
        return $query->where('is_default', false);
    }


    public function scopeDefault($query)
    {
        return $query->where('is_default', true);
    }
    public function scopeHasDefault($query)
    {
        return $query->where('is_default', true);
    }


    public static function getDefaultLocation()
    {
        return static::where('is_default', true)->first();
    }


    public function formattedAddress(): string
    {
        $addressParts = collect([
            $this->street ?? '',
            $this->barangay ?? '',
            $this->city_municipality ?? '',
            $this->province ?? '',
            $this->region ?? '',
        ])->filter();

        // Return a default message if all parts are null
        return $addressParts->isNotEmpty()
            ? $addressParts->implode(', ')
            : 'No address available';
    }

}
