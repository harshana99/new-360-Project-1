<?php

namespace App\Services;

use App\Models\InvestorMetrics;
use App\Models\User;

class InvestorDashboardService
{
    public function getMetrics(User $user)
    {
        $metrics = InvestorMetrics::firstOrCreate(['user_id' => $user->id]);
        
        if (!$metrics->calculated_at || $metrics->calculated_at->diffInHours(now()) > 24) {
            $metrics->calculateMetrics();
        }

        return $metrics;
    }

    public function getPortfolioBreakdown(User $user)
    {
        return (new InvestorMetrics())->getPortfolioBreakdown();
    }

    public function getReturnsTrend(User $user, $months = 12)
    {
        return (new InvestorMetrics())->getPerformanceChart();
    }

    public function getDividendSchedule(User $user)
    {
        return (new InvestorMetrics())->getDividendSchedule();
    }
}
