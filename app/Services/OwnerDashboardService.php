<?php

namespace App\Services;

use App\Models\OwnerMetrics;
use App\Models\User;

class OwnerDashboardService
{
    public function getMetrics(User $user)
    {
        // Find or create metrics record
        $metrics = OwnerMetrics::firstOrCreate(
            ['user_id' => $user->id],
            [
                'total_properties_count' => 0,
                'total_earnings' => 0
            ]
        );
        
        // Always recalculate for real-time accuracy
        $metrics->calculateMetrics();

        return $metrics;
    }

    public function getEarningsTrend(User $user, $months = 12)
    {
        // In reality, query Transaction model
        return (new OwnerMetrics())->getEarningsChart();
    }

    public function getPropertiesSummary(User $user)
    {
        return (new OwnerMetrics())->getPropertiesSummary();
    }

    public function getRecentTransactions(User $user, $limit = 5)
    {
        return (new OwnerMetrics())->getRecentTransactions($limit);
    }
}
