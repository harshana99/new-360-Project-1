<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Check Approved Middleware
 * 
 * Ensures only approved users can access protected routes.
 * Non-approved users are redirected to the locked dashboard.
 */
class CheckApproved
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        // If user is not authenticated, let auth middleware handle it
        if (!$user) {
            return redirect()->route('login');
        }

        // Check if email is verified
        if (!$user->hasVerifiedEmail()) {
            return redirect()->route('verification.notice')
                ->with('warning', 'Please verify your email address to continue.');
        }

        // Check if user has selected membership
        if (!$user->hasMembership()) {
            return redirect()->route('membership.select')
                ->with('info', 'Please select your membership type to continue.');
        }

        // Check if user is approved
        if (!$user->isApproved()) {
            return redirect()->route('dashboard.locked')
                ->with('warning', 'Your account is pending approval. Please wait for admin verification.');
        }

        // User is approved, allow access
        return $next($request);
    }
}
