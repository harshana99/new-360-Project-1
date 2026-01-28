<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Dashboard Controller
 * 
 * Handles role-based dashboard views for approved and locked states
 */
class DashboardController extends Controller
{
    /**
     * Show main dashboard (for approved users)
     * Redirects to role-specific dashboard
     *
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function index()
    {
        $user = Auth::user();

        // Redirect to role-specific dashboard
        if ($user->isOwner()) {
            return $this->ownerDashboard();
        } elseif ($user->isInvestor()) {
            return $this->investorDashboard();
        } elseif ($user->isMarketer()) {
            return $this->marketerDashboard();
        }

        // Fallback to generic dashboard
        return view('dashboard.index', compact('user'));
    }

    /**
     * Show Owner Dashboard
     */
    public function ownerDashboard()
    {
        $user = Auth::user();
        
        // Get or create owner stats
        $stats = $user->getOrCreateStats();

        return view('dashboard.owner', compact('user', 'stats'));
    }

    /**
     * Show Investor Dashboard
     */
    public function investorDashboard()
    {
        $user = Auth::user();
        
        // Get or create investor stats
        $stats = $user->getOrCreateStats();

        return view('dashboard.investor', compact('user', 'stats'));
    }

    /**
     * Show Marketer Dashboard
     */
    public function marketerDashboard()
    {
        $user = Auth::user();
        
        // Get or create marketer stats
        $stats = $user->getOrCreateStats();

        return view('dashboard.marketer', compact('user', 'stats'));
    }

    /**
     * Show locked dashboard (for non-approved users)
     *
     * @return \Illuminate\View\View
     */
    public function locked()
    {
        $user = Auth::user();

        return view('dashboard.locked', compact('user'));
    }
}
