<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Investor Statistics Model
 * 
 * Tracks investment-related statistics for investors
 */
class InvestorStats extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'investments_count',
        'total_invested',
        'total_roi',
        'roi_percentage',
        'projects_funded',
        'wallet_balance',
        'portfolio_value',
        'active_investments',
        'monthly_returns',
        'completed_projects',
    ];

    /**
     * The attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'total_invested' => 'decimal:2',
            'total_roi' => 'decimal:2',
            'roi_percentage' => 'decimal:2',
            'wallet_balance' => 'decimal:2',
            'portfolio_value' => 'decimal:2',
            'monthly_returns' => 'decimal:2',
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
     * Get formatted total invested
     */
    public function getFormattedTotalInvested(): string
    {
        return '₹' . number_format($this->total_invested, 2);
    }

    /**
     * Get formatted total ROI
     */
    public function getFormattedTotalRoi(): string
    {
        return '₹' . number_format($this->total_roi, 2);
    }

    /**
     * Get formatted wallet balance
     */
    public function getFormattedWalletBalance(): string
    {
        return '₹' . number_format($this->wallet_balance, 2);
    }

    /**
     * Get formatted portfolio value
     */
    public function getFormattedPortfolioValue(): string
    {
        return '₹' . number_format($this->portfolio_value, 2);
    }

    /**
     * Get formatted monthly returns
     */
    public function getFormattedMonthlyReturns(): string
    {
        return '₹' . number_format($this->monthly_returns, 2);
    }

    /**
     * Calculate ROI percentage
     */
    public function calculateRoiPercentage(): float
    {
        if ($this->total_invested == 0) {
            return 0;
        }
        
        return round(($this->total_roi / $this->total_invested) * 100, 2);
    }

    /**
     * Update ROI percentage
     */
    public function updateRoiPercentage(): void
    {
        $this->update([
            'roi_percentage' => $this->calculateRoiPercentage(),
        ]);
    }

    /**
     * Initialize stats for a new investor
     */
    public static function initializeForUser(int $userId): self
    {
        return self::create([
            'user_id' => $userId,
            'wallet_balance' => 0,
        ]);
    }
}
