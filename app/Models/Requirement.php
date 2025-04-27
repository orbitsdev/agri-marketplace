<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Requirement extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'is_active',
        'is_mandatory',
        'order_index'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_mandatory' => 'boolean',
        'order_index' => 'integer'
    ];

    /**
     * Get the farmer requirements associated with this requirement.
     */
    public function farmerRequirements()
    {
        return $this->hasMany(FarmerRequirement::class);
    }

    /**
     * Get all farmers who have this requirement.
     */
    public function farmers()
    {
        return $this->belongsToMany(Farmer::class, 'farmer_requirements')
            ->withPivot(['status', 'is_checked', 'remarks', 'document_id'])
            ->withTimestamps();
    }

    /**
     * Scope a query to only include active requirements.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope a query to only include mandatory requirements.
     */
    public function scopeMandatory($query)
    {
        return $query->where('is_mandatory', true);
    }

    /**
     * Scope a query to order by the order_index.
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('order_index');
    }
}
