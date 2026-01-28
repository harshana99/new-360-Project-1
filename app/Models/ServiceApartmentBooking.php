<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Service Apartment Booking Model
 * 
 * Manages short-term rental bookings for service apartments
 * 
 * @property int $id
 * @property int $property_id
 * @property int $guest_id
 * @property string $guest_name
 * @property string $guest_email
 * @property string $guest_phone
 * @property \DateTime $check_in_date
 * @property \DateTime $check_out_date
 * @property int $number_of_guests
 * @property decimal $price_per_night
 * @property decimal $total_price
 * @property decimal|null $cleaning_fee
 * @property decimal|null $service_fee
 * @property string $status
 * @property string|null $special_requests
 * @property string $payment_status
 * @property \DateTime|null $checked_in_at
 * @property \DateTime|null $checked_out_at
 * @property \DateTime $created_at
 * @property \DateTime $updated_at
 * @property \DateTime|null $deleted_at
 */
class ServiceApartmentBooking extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Booking status constants
     */
    const STATUS_PENDING = 'pending';
    const STATUS_CONFIRMED = 'confirmed';
    const STATUS_CHECKED_IN = 'checked_in';
    const STATUS_CHECKED_OUT = 'checked_out';
    const STATUS_CANCELLED = 'cancelled';

    /**
     * Payment status constants
     */
    const PAYMENT_PENDING = 'pending';
    const PAYMENT_PAID = 'paid';
    const PAYMENT_REFUNDED = 'refunded';
    const PAYMENT_FAILED = 'failed';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'property_id',
        'guest_id',
        'guest_name',
        'guest_email',
        'guest_phone',
        'check_in_date',
        'check_out_date',
        'number_of_guests',
        'price_per_night',
        'total_price',
        'cleaning_fee',
        'service_fee',
        'status',
        'special_requests',
        'payment_status',
        'checked_in_at',
        'checked_out_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'check_in_date' => 'datetime',
            'check_out_date' => 'datetime',
            'number_of_guests' => 'integer',
            'price_per_night' => 'decimal:2',
            'total_price' => 'decimal:2',
            'cleaning_fee' => 'decimal:2',
            'service_fee' => 'decimal:2',
            'checked_in_at' => 'datetime',
            'checked_out_at' => 'datetime',
        ];
    }

    /**
     * The attributes that should have default values.
     *
     * @var array
     */
    protected $attributes = [
        'status' => self::STATUS_PENDING,
        'payment_status' => self::PAYMENT_PENDING,
    ];

    /**
     * Get the property being booked
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function property(): BelongsTo
    {
        return $this->belongsTo(Property::class);
    }

    /**
     * Get the guest who made the booking
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function guest(): BelongsTo
    {
        return $this->belongsTo(User::class, 'guest_id');
    }

    /**
     * Get number of nights
     *
     * @return int
     */
    public function getNumberOfNights(): int
    {
        return $this->check_in_date->diffInDays($this->check_out_date);
    }

    /**
     * Calculate total price
     *
     * @return float
     */
    public function calculateTotalPrice(): float
    {
        $nights = $this->getNumberOfNights();
        $subtotal = $nights * $this->price_per_night;
        $cleaning = $this->cleaning_fee ?? 0;
        $service = $this->service_fee ?? 0;
        
        return $subtotal + $cleaning + $service;
    }

    /**
     * Get formatted total price
     *
     * @return string
     */
    public function getFormattedTotalPrice(): string
    {
        return '₹' . number_format($this->total_price, 2);
    }

    /**
     * Check if booking is confirmed
     *
     * @return bool
     */
    public function isConfirmed(): bool
    {
        return $this->status === self::STATUS_CONFIRMED;
    }

    /**
     * Check if booking is active (checked in)
     *
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->status === self::STATUS_CHECKED_IN;
    }

    /**
     * Check if payment is completed
     *
     * @return bool
     */
    public function isPaid(): bool
    {
        return $this->payment_status === self::PAYMENT_PAID;
    }

    /**
     * Check in guest
     *
     * @return bool
     */
    public function checkIn(): bool
    {
        return $this->update([
            'status' => self::STATUS_CHECKED_IN,
            'checked_in_at' => now(),
        ]);
    }

    /**
     * Check out guest
     *
     * @return bool
     */
    public function checkOut(): bool
    {
        return $this->update([
            'status' => self::STATUS_CHECKED_OUT,
            'checked_out_at' => now(),
        ]);
    }

    /**
     * Cancel booking
     *
     * @return bool
     */
    public function cancel(): bool
    {
        return $this->update(['status' => self::STATUS_CANCELLED]);
    }
}
