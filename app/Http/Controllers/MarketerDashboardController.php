<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\MarketerDashboardService;

class MarketerDashboardController extends Controller
{
    protected $marketerService;

    public function __construct(MarketerDashboardService $marketerService)
    {
        $this->marketerService = $marketerService;
    }

    public function index()
    {
        $user = auth()->user();
        $metrics = $this->marketerService->getMetrics($user);
        
        $data = [
            'user' => $user,
            'metrics' => $metrics,
            'recentReferrals' => [], // Placeholder
            'teamHierarchy' => $this->marketerService->getTeamHierarchy($user),
            'commissionBreakdown' => $this->marketerService->getCommissionBreakdown($user),
        ];

        return view('dashboards.marketer.dashboard', $data);
    }

    public function team()
    {
        return view('dashboards.marketer.team');
    }

    public function commissions()
    {
        return view('dashboards.marketer.commissions');
    }

    public function leaderboard()
    {
        return view('dashboards.marketer.leaderboard');
    }

    public function targets()
    {
        return view('dashboards.marketer.targets');
    }
}
