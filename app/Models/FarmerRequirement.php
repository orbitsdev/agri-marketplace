<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class FarmerRequirement extends Model implements HasMedia
{
    use HasFactory;
    use InteractsWithMedia;

    protected $fillable = [
        'farmer_id',
        'requirement_id',
        'status',
        'is_checked',
        'remarks'
    ];

    /**
     * Register media collections for this model
     */
    public function registerMediaCollections(): void
    {
        
        $this->addMediaCollection('file')
            ->singleFile();

    }

    protected $casts = [
        'is_checked' => 'boolean',
    ];

    /**
     * Status constants
     */
    public const STATUS_PENDING = 'Pending';
    public const STATUS_SUBMITTED = 'Submitted';
    public const STATUS_APPROVED = 'Approved';
    public const STATUS_REJECTED = 'Rejected';

    /**
     * Get the farmer that owns this requirement.
     */
    public function farmer()
    {
        return $this->belongsTo(Farmer::class);
    }

    /**
     * Get the requirement that this farmer requirement belongs to.
     */
    public function requirement()
    {
        return $this->belongsTo(Requirement::class);
    }

    /**
     * Get the document file URL if one exists
     */
    public function getDocumentUrl()
    {
        return $this->hasMedia('file') ? $this->getFirstMediaUrl('file') : null;
    }

    /**
     * Check if this requirement has a document uploaded
     */
    public function hasDocument(): bool
    {
        return $this->hasMedia('file');
    }

    /**
     * Scope a query to only include pending requirements.
     */
    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    /**
     * Scope a query to only include submitted requirements.
     */
    public function scopeSubmitted($query)
    {
        return $query->where('status', self::STATUS_SUBMITTED);
    }

    /**
     * Scope a query to only include approved requirements.
     */
    public function scopeApproved($query)
    {
        return $query->where('status', self::STATUS_APPROVED);
    }

    /**
     * Scope a query to only include rejected requirements.
     */
    public function scopeRejected($query)
    {
        return $query->where('status', self::STATUS_REJECTED);
    }

    /**
     * Scope a query to only include checked requirements.
     */
    public function scopeChecked($query)
    {
        return $query->where('is_checked', true);
    }

    /**
     * Scope a query to only include unchecked requirements.
     */
    public function scopeUnchecked($query)
    {
        return $query->where('is_checked', false);
    }
}
