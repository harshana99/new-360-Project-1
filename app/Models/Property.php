<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Property Model - 360WinEstate Platform
 * 
 * Manages real estate properties with ownership tracking,
 * maintenance, service apartment bookings, and marketplace listings.
 * 
 * @property int $id
 * @property string $unit_number
 * @property string $location
 * @property string $state
 * @property string $country
 * @property string $property_type
 * @property int $bedrooms
 * @property int $bathrooms
 * @property float $square_feet
 * @property string $status
 * @property decimal $purchase_price
 * @property decimal $current_valuation
 * @property string|null $cof_number
 * @property string|null $cof_document_url
 * @property string|null $deed_document_url
 * @property string|null $allocation_letter_url
 * @property text|null $description
 * @property string|null $featured_image_url
 * @property array|null $coordinates
 * @property array|null $amenities
 * @property int|null $owner_id
 * @property bool $is_managed
 * @property bool $is_featured
 * @property \DateTime $created_at
 * @property \DateTime $updated_at
 * @property \DateTime|null $deleted_at
 */
class Property extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Property type constants
     */
    const TYPE_FLAT = 'flat';
    const TYPE_VILLA = 'villa';
    const TYPE_SERVICE_APARTMENT = 'service_apartment';
    const TYPE_COMMERCIAL = 'commercial';

    /**
     * Property status constants
     */
    const STATUS_AVAILABLE = 'available';
    const STATUS_OCCUPIED = 'occupied';
    const STATUS_UNDER_MAINTENANCE = 'under_maintenance';
    const STATUS_LISTED = 'listed';
    const STATUS_SOLD = 'sold';

    /**
     * Minimum valuation increase percentage for auction
     */
    const MIN_AUCTION_VALUATION_INCREASE = 10;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        // Basic Information
        'unit_number',
        'location',
        'state',
        'country',
        
        // Property Details
        'property_type',
        'bedrooms',
        'bathrooms',
        'square_feet',
        
        // Status & Pricing
        'status',
        'purchase_price',
        'current_valuation',
        
        // Documents
        'cof_number',
        'cof_document_url',
        'deed_document_url',
        'allocation_letter_url',
        
        // Description & Media
        'description',
        'featured_image_url',
        
        // Structured Data
        'coordinates',
        'amenities',
        
        // Ownership & Management
        'owner_id',
        'is_managed',
        'is_featured',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        // Sensitive documents can be hidden if needed
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'purchase_price' => 'decimal:2',
            'current_valuation' => 'decimal:2',
            'square_feet' => 'float',
            'bedrooms' => 'integer',
            'bathrooms' => 'integer',
            'coordinates' => 'array',
            'amenities' => 'array',
            'is_managed' => 'boolean',
            'is_featured' => 'boolean',
        ];
    }

    /**
     * The attributes that should have default values.
     *
     * @var array
     */
    protected $attributes = [
        'status' => self::STATUS_AVAILABLE,
        'is_managed' => false,
        'is_featured' => false,
        'country' => 'India',
    ];

    /*
    |--------------------------------------------------------------------------
    | RELATIONSHIPS
    |--------------------------------------------------------------------------
    */

    /**
     * Get the owner of the property
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    /**
     * Get all ownership records for this property
     * Tracks ownership history and fractional ownership
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function ownerships(): HasMany
    {
        return $this->hasMany(Ownership::class);
    }

    /**
     * Get current active ownerships
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function activeOwnerships(): HasMany
    {
        return $this->hasMany(Ownership::class)
            ->where('status', 'active')
            ->orderBy('ownership_percentage', 'desc');
    }

    /**
     * Get all maintenance tickets for this property
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function maintenanceTickets(): HasMany
    {
        return $this->hasMany(MaintenanceTicket::class);
    }

    /**
     * Get open maintenance tickets
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function openMaintenanceTickets(): HasMany
    {
        return $this->hasMany(MaintenanceTicket::class)
            ->whereIn('status', ['open', 'in_progress']);
    }

    /**
     * Get all service apartment bookings for this property
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function serviceApartmentBookings(): HasMany
    {
        return $this->hasMany(ServiceApartmentBooking::class);
    }

    /**
     * Get active service apartment bookings
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function activeBookings(): HasMany
    {
        return $this->hasMany(ServiceApartmentBooking::class)
            ->where('status', 'confirmed')
            ->where('check_out_date', '>=', now());
    }

    /**
     * Get all marketplace listings for this property
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function marketListings(): HasMany
    {
        return $this->hasMany(MarketListing::class);
    }

    /**
     * Get active marketplace listings
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function activeListings(): HasMany
    {
        return $this->hasMany(MarketListing::class)
            ->where('status', 'active')
            ->where('expires_at', '>', now());
    }

    /**
     * Get property images
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function images(): HasMany
    {
        return $this->hasMany(PropertyImage::class);
    }

    /**
     * Get property reviews
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function reviews(): HasMany
    {
        return $this->hasMany(PropertyReview::class);
    }

    /*
    |--------------------------------------------------------------------------
    | ACCESSORS & MUTATORS
    |--------------------------------------------------------------------------
    */

    /**
     * Get the full address
     *
     * @return string
     */
    public function getFullAddressAttribute(): string
    {
        $parts = array_filter([
            $this->unit_number,
            $this->location,
            $this->state,
            $this->country,
        ]);

        return implode(', ', $parts);
    }

    /**
     * Get formatted purchase price
     *
     * @return string
     */
    public function getFormattedPurchasePriceAttribute(): string
    {
        return '₹' . number_format($this->purchase_price, 2);
    }

    /**
     * Get formatted current valuation
     *
     * @return string
     */
    public function getFormattedValuationAttribute(): string
    {
        return '₹' . number_format($this->current_valuation, 2);
    }

    /**
     * Get price per square foot
     *
     * @return float
     */
    public function getPricePerSqftAttribute(): float
    {
        if ($this->square_feet > 0) {
            return round($this->current_valuation / $this->square_feet, 2);
        }
        
        return 0;
    }

    /**
     * Get formatted price per square foot
     *
     * @return string
     */
    public function getFormattedPricePerSqftAttribute(): string
    {
        return '₹' . number_format($this->price_per_sqft, 2) . '/sqft';
    }

    /**
     * Set coordinates (ensure proper format)
     *
     * @param mixed $value
     * @return void
     */
    public function setCoordinatesAttribute($value): void
    {
        if (is_string($value)) {
            $this->attributes['coordinates'] = $value;
        } elseif (is_array($value)) {
            $this->attributes['coordinates'] = json_encode($value);
        }
    }

    /**
     * Set amenities (ensure proper format)
     *
     * @param mixed $value
     * @return void
     */
    public function setAmenitiesAttribute($value): void
    {
        if (is_string($value)) {
            $this->attributes['amenities'] = $value;
        } elseif (is_array($value)) {
            $this->attributes['amenities'] = json_encode($value);
        }
    }

    /*
    |--------------------------------------------------------------------------
    | VALUATION & PRICING METHODS
    |--------------------------------------------------------------------------
    */

    /**
     * Get valuation percentage change from purchase price
     *
     * @return float
     */
    public function getValuationPercentage(): float
    {
        if ($this->purchase_price > 0) {
            $change = $this->current_valuation - $this->purchase_price;
            return round(($change / $this->purchase_price) * 100, 2);
        }
        
        return 0;
    }

    /**
     * Get valuation change amount
     *
     * @return float
     */
    public function getValuationChange(): float
    {
        return round($this->current_valuation - $this->purchase_price, 2);
    }

    /**
     * Get formatted valuation change
     *
     * @return string
     */
    public function getFormattedValuationChange(): string
    {
        $change = $this->getValuationChange();
        $prefix = $change >= 0 ? '+' : '';
        
        return $prefix . '₹' . number_format(abs($change), 2);
    }

    /**
     * Check if valuation has increased
     *
     * @return bool
     */
    public function hasValuationIncreased(): bool
    {
        return $this->current_valuation > $this->purchase_price;
    }

    /**
     * Update current valuation
     *
     * @param float $newValuation
     * @return bool
     */
    public function updateValuation(float $newValuation): bool
    {
        return $this->update(['current_valuation' => $newValuation]);
    }

    /*
    |--------------------------------------------------------------------------
    | AMENITIES METHODS
    |--------------------------------------------------------------------------
    */

    /**
     * Get amenity list as array
     *
     * @return array
     */
    public function getAmenityList(): array
    {
        return $this->amenities ?? [];
    }

    /**
     * Check if property has a specific amenity
     *
     * @param string $amenity
     * @return bool
     */
    public function hasAmenity(string $amenity): bool
    {
        return in_array($amenity, $this->getAmenityList());
    }

    /**
     * Add an amenity
     *
     * @param string $amenity
     * @return void
     */
    public function addAmenity(string $amenity): void
    {
        $amenities = $this->getAmenityList();
        
        if (!in_array($amenity, $amenities)) {
            $amenities[] = $amenity;
            $this->update(['amenities' => $amenities]);
        }
    }

    /**
     * Remove an amenity
     *
     * @param string $amenity
     * @return void
     */
    public function removeAmenity(string $amenity): void
    {
        $amenities = $this->getAmenityList();
        
        if (($key = array_search($amenity, $amenities)) !== false) {
            unset($amenities[$key]);
            $this->update(['amenities' => array_values($amenities)]);
        }
    }

    /**
     * Get amenities count
     *
     * @return int
     */
    public function getAmenitiesCount(): int
    {
        return count($this->getAmenityList());
    }

    /*
    |--------------------------------------------------------------------------
    | STATUS METHODS
    |--------------------------------------------------------------------------
    */

    /**
     * Get status color for UI
     *
     * @return string
     */
    public function getStatusColor(): string
    {
        return match($this->status) {
            self::STATUS_AVAILABLE => 'success',
            self::STATUS_OCCUPIED => 'primary',
            self::STATUS_UNDER_MAINTENANCE => 'warning',
            self::STATUS_LISTED => 'info',
            self::STATUS_SOLD => 'secondary',
            default => 'secondary',
        };
    }

    /**
     * Get status label
     *
     * @return string
     */
    public function getStatusLabel(): string
    {
        return match($this->status) {
            self::STATUS_AVAILABLE => 'Available',
            self::STATUS_OCCUPIED => 'Occupied',
            self::STATUS_UNDER_MAINTENANCE => 'Under Maintenance',
            self::STATUS_LISTED => 'Listed for Sale',
            self::STATUS_SOLD => 'Sold',
            default => 'Unknown',
        };
    }

    /**
     * Get status badge class
     *
     * @return string
     */
    public function getStatusBadgeClass(): string
    {
        return 'bg-' . $this->getStatusColor();
    }

    /**
     * Check if property is available
     *
     * @return bool
     */
    public function isAvailable(): bool
    {
        return $this->status === self::STATUS_AVAILABLE;
    }

    /**
     * Check if property is occupied
     *
     * @return bool
     */
    public function isOccupied(): bool
    {
        return $this->status === self::STATUS_OCCUPIED;
    }

    /**
     * Check if property is under maintenance
     *
     * @return bool
     */
    public function isUnderMaintenance(): bool
    {
        return $this->status === self::STATUS_UNDER_MAINTENANCE;
    }

    /**
     * Check if property is listed for sale
     *
     * @return bool
     */
    public function isListed(): bool
    {
        return $this->status === self::STATUS_LISTED;
    }

    /**
     * Check if property is sold
     *
     * @return bool
     */
    public function isSold(): bool
    {
        return $this->status === self::STATUS_SOLD;
    }

    /*
    |--------------------------------------------------------------------------
    | MANAGEMENT & AUCTION METHODS
    |--------------------------------------------------------------------------
    */

    /**
     * Check if property is managed by platform
     *
     * @return bool
     */
    public function isManaged(): bool
    {
        return $this->is_managed === true;
    }

    /**
     * Check if property can be auctioned
     * Property can be auctioned if:
     * - Not sold
     * - Valuation increased by minimum percentage
     * - Not under maintenance
     *
     * @return bool
     */
    public function canBeAuctioned(): bool
    {
        // Cannot auction sold properties
        if ($this->isSold()) {
            return false;
        }

        // Cannot auction properties under maintenance
        if ($this->isUnderMaintenance()) {
            return false;
        }

        // Check if valuation has increased by minimum percentage
        $valuationIncrease = $this->getValuationPercentage();
        
        return $valuationIncrease >= self::MIN_AUCTION_VALUATION_INCREASE;
    }

    /**
     * Check if property is featured
     *
     * @return bool
     */
    public function isFeatured(): bool
    {
        return $this->is_featured === true;
    }

    /**
     * Mark property as featured
     *
     * @return bool
     */
    public function markAsFeatured(): bool
    {
        return $this->update(['is_featured' => true]);
    }

    /**
     * Remove featured status
     *
     * @return bool
     */
    public function removeFeatured(): bool
    {
        return $this->update(['is_featured' => false]);
    }

    /**
     * Mark property as managed
     *
     * @return bool
     */
    public function markAsManaged(): bool
    {
        return $this->update(['is_managed' => true]);
    }

    /**
     * Remove managed status
     *
     * @return bool
     */
    public function removeManaged(): bool
    {
        return $this->update(['is_managed' => false]);
    }

    /*
    |--------------------------------------------------------------------------
    | PROPERTY TYPE METHODS
    |--------------------------------------------------------------------------
    */

    /**
     * Get property type label
     *
     * @return string
     */
    public function getPropertyTypeLabel(): string
    {
        return match($this->property_type) {
            self::TYPE_FLAT => 'Flat/Apartment',
            self::TYPE_VILLA => 'Villa',
            self::TYPE_SERVICE_APARTMENT => 'Service Apartment',
            self::TYPE_COMMERCIAL => 'Commercial Property',
            default => 'Unknown',
        };
    }

    /**
     * Check if property is a flat
     *
     * @return bool
     */
    public function isFlat(): bool
    {
        return $this->property_type === self::TYPE_FLAT;
    }

    /**
     * Check if property is a villa
     *
     * @return bool
     */
    public function isVilla(): bool
    {
        return $this->property_type === self::TYPE_VILLA;
    }

    /**
     * Check if property is a service apartment
     *
     * @return bool
     */
    public function isServiceApartment(): bool
    {
        return $this->property_type === self::TYPE_SERVICE_APARTMENT;
    }

    /**
     * Check if property is commercial
     *
     * @return bool
     */
    public function isCommercial(): bool
    {
        return $this->property_type === self::TYPE_COMMERCIAL;
    }

    /*
    |--------------------------------------------------------------------------
    | DOCUMENT METHODS
    |--------------------------------------------------------------------------
    */

    /**
     * Check if property has COF (Certificate of Formation) document
     *
     * @return bool
     */
    public function hasCofDocument(): bool
    {
        return !empty($this->cof_document_url);
    }

    /**
     * Check if property has deed document
     *
     * @return bool
     */
    public function hasDeedDocument(): bool
    {
        return !empty($this->deed_document_url);
    }

    /**
     * Check if property has allocation letter
     *
     * @return bool
     */
    public function hasAllocationLetter(): bool
    {
        return !empty($this->allocation_letter_url);
    }

    /**
     * Check if all required documents are present
     *
     * @return bool
     */
    public function hasAllDocuments(): bool
    {
        return $this->hasCofDocument() 
            && $this->hasDeedDocument() 
            && $this->hasAllocationLetter();
    }

    /*
    |--------------------------------------------------------------------------
    | COORDINATES & LOCATION METHODS
    |--------------------------------------------------------------------------
    */

    /**
     * Get latitude from coordinates
     *
     * @return float|null
     */
    public function getLatitude(): ?float
    {
        return $this->coordinates['lat'] ?? null;
    }

    /**
     * Get longitude from coordinates
     *
     * @return float|null
     */
    public function getLongitude(): ?float
    {
        return $this->coordinates['lng'] ?? null;
    }

    /**
     * Check if property has coordinates
     *
     * @return bool
     */
    public function hasCoordinates(): bool
    {
        return $this->getLatitude() !== null && $this->getLongitude() !== null;
    }

    /**
     * Get Google Maps URL
     *
     * @return string|null
     */
    public function getGoogleMapsUrl(): ?string
    {
        if ($this->hasCoordinates()) {
            $lat = $this->getLatitude();
            $lng = $this->getLongitude();
            return "https://www.google.com/maps?q={$lat},{$lng}";
        }
        
        return null;
    }

    /*
    |--------------------------------------------------------------------------
    | STATISTICS & ANALYTICS METHODS
    |--------------------------------------------------------------------------
    */

    /**
     * Get total maintenance tickets count
     *
     * @return int
     */
    public function getTotalMaintenanceTickets(): int
    {
        return $this->maintenanceTickets()->count();
    }

    /**
     * Get open maintenance tickets count
     *
     * @return int
     */
    public function getOpenMaintenanceTickets(): int
    {
        return $this->openMaintenanceTickets()->count();
    }

    /**
     * Get total bookings count
     *
     * @return int
     */
    public function getTotalBookings(): int
    {
        return $this->serviceApartmentBookings()->count();
    }

    /**
     * Get active bookings count
     *
     * @return int
     */
    public function getActiveBookingsCount(): int
    {
        return $this->activeBookings()->count();
    }

    /**
     * Get average rating from reviews
     *
     * @return float
     */
    public function getAverageRating(): float
    {
        return round($this->reviews()->avg('rating') ?? 0, 1);
    }

    /**
     * Get total reviews count
     *
     * @return int
     */
    public function getTotalReviews(): int
    {
        return $this->reviews()->count();
    }

    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */

    /**
     * Scope to get available properties
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeAvailable($query)
    {
        return $query->where('status', self::STATUS_AVAILABLE);
    }

    /**
     * Scope to get featured properties
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    /**
     * Scope to get managed properties
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeManaged($query)
    {
        return $query->where('is_managed', true);
    }

    /**
     * Scope to filter by property type
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $type
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOfType($query, string $type)
    {
        return $query->where('property_type', $type);
    }

    /**
     * Scope to filter by location
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $location
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeInLocation($query, string $location)
    {
        return $query->where('location', 'like', "%{$location}%");
    }

    /**
     * Scope to filter by state
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $state
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeInState($query, string $state)
    {
        return $query->where('state', $state);
    }

    /**
     * Scope to filter by price range
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param float $min
     * @param float $max
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePriceRange($query, float $min, float $max)
    {
        return $query->whereBetween('current_valuation', [$min, $max]);
    }
}
