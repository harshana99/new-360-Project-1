<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\OwnerDashboardService;
use App\Models\User;

class OwnerDashboardController extends Controller
{
    protected $ownerService;

    public function __construct(OwnerDashboardService $ownerService)
    {
        $this->ownerService = $ownerService;
    }

    /**
     * Owner Dashboard Index
     */
    public function index()
    {
        $user = auth()->user();
        $metrics = $this->ownerService->getMetrics($user);
        
        $data = [
            'user' => $user,
            'metrics' => $metrics,
            'recentProperties' => [], // TODO: Get from Property Service
            'recentTransactions' => $this->ownerService->getRecentTransactions($user),
            'earningsChart' => $this->ownerService->getEarningsTrend($user),
            'propertiesSummary' => $this->ownerService->getPropertiesSummary($user),
        ];

        return view('dashboards.owner.dashboard', $data);
    }

    public function properties()
    {
        // Placeholder for properties list
        $properties = []; 
        return view('dashboards.owner.properties', compact('properties'));
    }

    public function earnings()
    {
        $transactions = $this->ownerService->getRecentTransactions(auth()->user(), 15);
        return view('dashboards.owner.earnings', compact('transactions'));
    }

    public function documents()
    {
        // Placeholder
        $documents = [];
        return view('dashboards.owner.documents', compact('documents'));
    }

    public function analytics()
    {
        return view('dashboards.owner.analytics');
    }
}
