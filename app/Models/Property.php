<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Property extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'title',
        'description',
        'location',
        'address',
        'latitude',
        'longitude',
        'property_type',
        'status',
        'price',
        'minimum_investment',
        'expected_return_percentage',
        'lease_duration_months',
        'completion_date',
        'currency',
        'admin_approved_by',
        'admin_approved_at',
        'rejection_reason',
        'view_count',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'minimum_investment' => 'decimal:2',
        'expected_return_percentage' => 'decimal:2',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'completion_date' => 'date',
        'admin_approved_at' => 'datetime',
    ];

    // Relationships

    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function approvedBy()
    {
        return $this->belongsTo(Admin::class, 'admin_approved_by');
    }

    public function images()
    {
        return $this->hasMany(PropertyImage::class)->orderBy('sort_order')->orderBy('is_featured', 'desc');
    }

    public function documents()
    {
        return $this->hasMany(PropertyDocument::class);
    }

    public function earnings()
    {
        return $this->hasMany(PropertyEarnings::class);
    }

    public function activities()
    {
         // Assuming Activity model uses subject_type/subject_id polymorphism
         // User: "Log all actions in activities table" - likely polymorphic
        return $this->morphMany(Activity::class, 'subject');
    }

    // Accessors & Methods

    public function getFullAddressAttribute()
    {
        return "{$this->address}, {$this->location}";
    }

    public function getImageUrlAttribute()
    {
        $featured = $this->images->where('is_featured', true)->first();
        if ($featured) return $featured->image_url;
        
        $first = $this->images->first();
        return $first ? $first->image_url : asset('images/property-placeholder.jpg'); // Default
    }

    public function canBeEditedBy($userId)
    {
        return $this->user_id == $userId && $this->status != 'sold';
    }

    public function canBeDeletedBy($userId)
    {
        return $this->user_id == $userId && in_array($this->status, ['pending', 'rejected']);
    }

    public function canBeInvestedIn()
    {
        return $this->status == 'active';
    }

    // Scopes

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeOwnedBy($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeByLocation($query, $location)
    {
        return $query->where('location', 'LIKE', "%{$location}%");
    }

    public function scopeByType($query, $type)
    {
        return $query->where('property_type', $type);
    }

    public function scopeByPriceRange($query, $min = null, $max = null)
    {
        if ($min) $query->where('price', '>=', $min);
        if ($max) $query->where('price', '<=', $max);
        return $query;
    }

    public function scopeSearchable($query, $search)
    {
        return $query->where(function($q) use ($search) {
            $q->where('title', 'LIKE', "%{$search}%")
              ->orWhere('description', 'LIKE', "%{$search}%")
              ->orWhere('location', 'LIKE', "%{$search}%");
        });
    }

    // Actions

    public function approveByAdmin($adminId)
    {
        $this->update([
            'status' => 'active',
            'admin_approved_by' => $adminId,
            'admin_approved_at' => now(),
            'rejection_reason' => null
        ]);
    }

    public function rejectByAdmin($adminId, $reason)
    {
        $this->update([
            'status' => 'rejected',
            'admin_approved_by' => $adminId,
            'rejection_reason' => $reason
        ]);
    }
}
