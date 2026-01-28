<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Membership Controller
 * 
 * Handles membership selection for users
 */
class MembershipController extends Controller
{
    /**
     * Show membership selection form
     *
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function showSelectionForm()
    {
        $user = Auth::user();

        // If user already has membership, redirect to locked dashboard
        if ($user->hasMembership()) {
            return redirect()->route('dashboard.locked')
                ->with('info', 'You have already selected a membership type.');
        }

        return view('auth.select-membership');
    }

    /**
     * Handle membership selection
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function selectMembership(Request $request)
    {
        $user = Auth::user();

        // Validate membership type
        $validated = $request->validate([
            'membership_type' => ['required', 'in:owner,investor,marketer'],
        ], [
            'membership_type.required' => 'Please select a membership type.',
            'membership_type.in' => 'Invalid membership type selected.',
        ]);

        // Check if user already has membership
        if ($user->hasMembership()) {
            return redirect()->route('dashboard.locked')
                ->with('warning', 'You have already selected a membership type.');
        }

        // Update user membership
        $user->selectMembership($validated['membership_type']);

        // Redirect to locked dashboard with success message
        return redirect()->route('dashboard.locked')
            ->with('success', 'Membership type selected successfully! Please complete your KYC verification.');
    }
}
