<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Ownership Model
 * 
 * Tracks property ownership including fractional ownership
 * and ownership history for the 360WinEstate platform.
 * 
 * @property int $id
 * @property int $property_id
 * @property int $user_id
 * @property float $ownership_percentage
 * @property decimal $investment_amount
 * @property string $status
 * @property \DateTime $acquired_date
 * @property \DateTime|null $sold_date
 * @property string|null $ownership_certificate_url
 * @property \DateTime $created_at
 * @property \DateTime $updated_at
 * @property \DateTime|null $deleted_at
 */
class Ownership extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Ownership status constants
     */
    const STATUS_ACTIVE = 'active';
    const STATUS_SOLD = 'sold';
    const STATUS_TRANSFERRED = 'transferred';
    const STATUS_PENDING = 'pending';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'property_id',
        'user_id',
        'ownership_percentage',
        'investment_amount',
        'status',
        'acquired_date',
        'sold_date',
        'ownership_certificate_url',
    ];

    /**
     * The attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'ownership_percentage' => 'float',
            'investment_amount' => 'decimal:2',
            'acquired_date' => 'datetime',
            'sold_date' => 'datetime',
        ];
    }

    /**
     * The attributes that should have default values.
     *
     * @var array
     */
    protected $attributes = [
        'status' => self::STATUS_PENDING,
        'ownership_percentage' => 100.0,
    ];

    /**
     * Get the property that is owned
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function property(): BelongsTo
    {
        return $this->belongsTo(Property::class);
    }

    /**
     * Get the user who owns the property
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Check if ownership is active
     *
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->status === self::STATUS_ACTIVE;
    }

    /**
     * Check if ownership is full (100%)
     *
     * @return bool
     */
    public function isFullOwnership(): bool
    {
        return $this->ownership_percentage >= 100.0;
    }

    /**
     * Check if ownership is fractional
     *
     * @return bool
     */
    public function isFractional(): bool
    {
        return $this->ownership_percentage < 100.0;
    }

    /**
     * Get formatted ownership percentage
     *
     * @return string
     */
    public function getFormattedPercentage(): string
    {
        return number_format($this->ownership_percentage, 2) . '%';
    }

    /**
     * Get formatted investment amount
     *
     * @return string
     */
    public function getFormattedInvestment(): string
    {
        return '₹' . number_format($this->investment_amount, 2);
    }
}
