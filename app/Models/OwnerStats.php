<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Owner Statistics Model
 * 
 * Tracks property-related statistics for property owners
 */
class OwnerStats extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'properties_count',
        'total_property_value',
        'rental_income',
        'maintenance_tickets',
        'service_apartment_enrollments',
        'active_properties',
        'rented_properties',
        'monthly_revenue',
    ];

    /**
     * The attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'total_property_value' => 'decimal:2',
            'rental_income' => 'decimal:2',
            'monthly_revenue' => 'decimal:2',
        ];
    }

    /**
     * Get the user that owns the stats
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get formatted property value
     */
    public function getFormattedPropertyValue(): string
    {
        return '₹' . number_format($this->total_property_value, 2);
    }

    /**
     * Get formatted rental income
     */
    public function getFormattedRentalIncome(): string
    {
        return '₹' . number_format($this->rental_income, 2);
    }

    /**
     * Get formatted monthly revenue
     */
    public function getFormattedMonthlyRevenue(): string
    {
        return '₹' . number_format($this->monthly_revenue, 2);
    }

    /**
     * Get occupancy rate percentage
     */
    public function getOccupancyRate(): float
    {
        if ($this->properties_count == 0) {
            return 0;
        }
        
        return round(($this->rented_properties / $this->properties_count) * 100, 1);
    }

    /**
     * Initialize stats for a new owner
     */
    public static function initializeForUser(int $userId): self
    {
        return self::create([
            'user_id' => $userId,
        ]);
    }
}
