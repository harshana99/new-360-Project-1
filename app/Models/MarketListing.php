<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Market Listing Model
 * 
 * Manages property listings in the marketplace for sale/auction
 * 
 * @property int $id
 * @property int $property_id
 * @property int $seller_id
 * @property string $listing_type
 * @property decimal $asking_price
 * @property decimal|null $minimum_bid
 * @property decimal|null $current_bid
 * @property int|null $highest_bidder_id
 * @property string $status
 * @property text|null $description
 * @property \DateTime|null $auction_start_date
 * @property \DateTime|null $auction_end_date
 * @property \DateTime|null $expires_at
 * @property int $views_count
 * @property int $inquiries_count
 * @property \DateTime $created_at
 * @property \DateTime $updated_at
 * @property \DateTime|null $deleted_at
 */
class MarketListing extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Listing type constants
     */
    const TYPE_SALE = 'sale';
    const TYPE_AUCTION = 'auction';
    const TYPE_FRACTIONAL = 'fractional';

    /**
     * Listing status constants
     */
    const STATUS_DRAFT = 'draft';
    const STATUS_ACTIVE = 'active';
    const STATUS_PENDING = 'pending';
    const STATUS_SOLD = 'sold';
    const STATUS_EXPIRED = 'expired';
    const STATUS_CANCELLED = 'cancelled';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'property_id',
        'seller_id',
        'listing_type',
        'asking_price',
        'minimum_bid',
        'current_bid',
        'highest_bidder_id',
        'status',
        'description',
        'auction_start_date',
        'auction_end_date',
        'expires_at',
        'views_count',
        'inquiries_count',
    ];

    /**
     * The attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'asking_price' => 'decimal:2',
            'minimum_bid' => 'decimal:2',
            'current_bid' => 'decimal:2',
            'views_count' => 'integer',
            'inquiries_count' => 'integer',
            'auction_start_date' => 'datetime',
            'auction_end_date' => 'datetime',
            'expires_at' => 'datetime',
        ];
    }

    /**
     * The attributes that should have default values.
     *
     * @var array
     */
    protected $attributes = [
        'status' => self::STATUS_DRAFT,
        'listing_type' => self::TYPE_SALE,
        'views_count' => 0,
        'inquiries_count' => 0,
    ];

    /**
     * Get the property being listed
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function property(): BelongsTo
    {
        return $this->belongsTo(Property::class);
    }

    /**
     * Get the seller
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function seller(): BelongsTo
    {
        return $this->belongsTo(User::class, 'seller_id');
    }

    /**
     * Get the highest bidder
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function highestBidder(): BelongsTo
    {
        return $this->belongsTo(User::class, 'highest_bidder_id');
    }

    /**
     * Check if listing is an auction
     *
     * @return bool
     */
    public function isAuction(): bool
    {
        return $this->listing_type === self::TYPE_AUCTION;
    }

    /**
     * Check if listing is active
     *
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->status === self::STATUS_ACTIVE 
            && ($this->expires_at === null || $this->expires_at->isFuture());
    }

    /**
     * Check if auction is ongoing
     *
     * @return bool
     */
    public function isAuctionOngoing(): bool
    {
        if (!$this->isAuction()) {
            return false;
        }

        return $this->auction_start_date <= now() 
            && $this->auction_end_date >= now()
            && $this->status === self::STATUS_ACTIVE;
    }

    /**
     * Place a bid
     *
     * @param int $bidderId
     * @param float $bidAmount
     * @return bool
     */
    public function placeBid(int $bidderId, float $bidAmount): bool
    {
        if (!$this->isAuctionOngoing()) {
            return false;
        }

        if ($bidAmount <= ($this->current_bid ?? $this->minimum_bid)) {
            return false;
        }

        return $this->update([
            'current_bid' => $bidAmount,
            'highest_bidder_id' => $bidderId,
        ]);
    }

    /**
     * Increment views count
     *
     * @return void
     */
    public function incrementViews(): void
    {
        $this->increment('views_count');
    }

    /**
     * Increment inquiries count
     *
     * @return void
     */
    public function incrementInquiries(): void
    {
        $this->increment('inquiries_count');
    }

    /**
     * Mark as sold
     *
     * @return bool
     */
    public function markAsSold(): bool
    {
        return $this->update(['status' => self::STATUS_SOLD]);
    }

    /**
     * Get formatted asking price
     *
     * @return string
     */
    public function getFormattedAskingPrice(): string
    {
        return '₹' . number_format($this->asking_price, 2);
    }

    /**
     * Get formatted current bid
     *
     * @return string|null
     */
    public function getFormattedCurrentBid(): ?string
    {
        return $this->current_bid 
            ? '₹' . number_format($this->current_bid, 2) 
            : null;
    }
}
