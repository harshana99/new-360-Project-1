<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvestorMetrics extends Model
{
    use HasFactory;

    protected $table = 'investor_metrics';

    protected $fillable = [
        'user_id',
        'total_invested',
        'total_returns',
        'total_roi_percentage',
        'active_investments',
        'completed_investments',
        'pending_dividends',
        'next_dividend_date',
        'calculated_at',
    ];

    protected $casts = [
        'calculated_at' => 'datetime',
        'next_dividend_date' => 'date',
        'total_invested' => 'decimal:2',
        'total_returns' => 'decimal:2',
        'total_roi_percentage' => 'decimal:2',
        'pending_dividends' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function calculateMetrics()
    {
        $this->calculated_at = now();
        $this->save();
        return $this;
    }

    public function getPortfolioBreakdown()
    {
        return [
            'labels' => ['Residential', 'Commercial', 'Land'],
            'data' => [45, 30, 25]
        ];
    }

    public function getDividendSchedule()
    {
        return collect([
            ['date' => '2024-04-01', 'property' => 'Lekki Heights', 'amount' => 50000, 'status' => 'confirmed'],
            ['date' => '2024-05-01', 'property' => 'Victoria Garden', 'amount' => 35000, 'status' => 'projected'],
        ]);
    }

    public function getPerformanceChart()
    {
        return [
            'labels' => ['Q1', 'Q2', 'Q3', 'Q4'],
            'roi' => [4.5, 5.2, 4.8, 6.1]
        ];
    }
}
