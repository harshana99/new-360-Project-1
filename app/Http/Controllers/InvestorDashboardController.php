<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\InvestorDashboardService;

class InvestorDashboardController extends Controller
{
    protected $investorService;

    public function __construct(InvestorDashboardService $investorService)
    {
        $this->investorService = $investorService;
    }

    public function index()
    {
        $user = auth()->user();
        $metrics = $this->investorService->getMetrics($user);
        
        $data = [
            'user' => $user,
            'metrics' => $metrics,
            'recentInvestments' => [], // Placeholder
            'upcomingDividends' => $this->investorService->getDividendSchedule($user),
            'portfolio' => $this->investorService->getPortfolioBreakdown($user),
            'performanceChart' => $this->investorService->getReturnsTrend($user),
        ];

        return view('dashboards.investor.dashboard', $data);
    }

    public function investments()
    {
        return view('dashboards.investor.investments');
    }

    public function portfolio()
    {
        return view('dashboards.investor.portfolio');
    }

    public function dividends()
    {
        return view('dashboards.investor.dividends');
    }

    public function performance()
    {
        return view('dashboards.investor.performance');
    }
}
