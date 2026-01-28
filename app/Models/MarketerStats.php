<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Marketer Statistics Model
 * 
 * Tracks referral and commission statistics for marketers
 */
class MarketerStats extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'total_referrals',
        'verified_referrals',
        'converted_sales',
        'commissions_earned',
        'team_size',
        'pending_referrals',
        'pending_commissions',
        'this_month_commissions',
        'this_month_referrals',
        'conversion_rate',
    ];

    /**
     * The attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'commissions_earned' => 'decimal:2',
            'pending_commissions' => 'decimal:2',
            'this_month_commissions' => 'decimal:2',
            'conversion_rate' => 'decimal:2',
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
     * Get formatted commissions earned
     */
    public function getFormattedCommissionsEarned(): string
    {
        return '₹' . number_format($this->commissions_earned, 2);
    }

    /**
     * Get formatted pending commissions
     */
    public function getFormattedPendingCommissions(): string
    {
        return '₹' . number_format($this->pending_commissions, 2);
    }

    /**
     * Get formatted this month commissions
     */
    public function getFormattedThisMonthCommissions(): string
    {
        return '₹' . number_format($this->this_month_commissions, 2);
    }

    /**
     * Calculate conversion rate
     */
    public function calculateConversionRate(): float
    {
        if ($this->verified_referrals == 0) {
            return 0;
        }
        
        return round(($this->converted_sales / $this->verified_referrals) * 100, 2);
    }

    /**
     * Update conversion rate
     */
    public function updateConversionRate(): void
    {
        $this->update([
            'conversion_rate' => $this->calculateConversionRate(),
        ]);
    }

    /**
     * Get verification rate
     */
    public function getVerificationRate(): float
    {
        if ($this->total_referrals == 0) {
            return 0;
        }
        
        return round(($this->verified_referrals / $this->total_referrals) * 100, 1);
    }

    /**
     * Get average commission per sale
     */
    public function getAverageCommissionPerSale(): float
    {
        if ($this->converted_sales == 0) {
            return 0;
        }
        
        return round($this->commissions_earned / $this->converted_sales, 2);
    }

    /**
     * Initialize stats for a new marketer
     */
    public static function initializeForUser(int $userId): self
    {
        return self::create([
            'user_id' => $userId,
        ]);
    }
}
