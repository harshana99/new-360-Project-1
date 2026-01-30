<?php

namespace App\Services;

use App\Models\MarketerMetrics;
use App\Models\User;

class MarketerDashboardService
{
    public function getMetrics(User $user)
    {
        $metrics = MarketerMetrics::firstOrCreate(['user_id' => $user->id]);
        
        if (!$metrics->calculated_at || $metrics->calculated_at->diffInHours(now()) > 4) {
            $metrics->calculateMetrics();
        }
        return $metrics;
    }

    public function getTeamHierarchy(User $user)
    {
        return (new MarketerMetrics())->getTeamHierarchy();
    }

    public function getCommissionBreakdown(User $user)
    {
        return (new MarketerMetrics())->getCommissionBreakdown();
    }

    public function getLeaderboardPosition(User $user)
    {
        return (new MarketerMetrics())->getLeaderboardPosition();
    }
}
