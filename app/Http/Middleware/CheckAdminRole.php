<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Admin;

/**
 * Check Admin Role Middleware
 * 
 * Verifies that the authenticated user:
 * 1. Is logged in
 * 2. Has an admin account
 * 3. Has the required admin role
 * 4. Has an active admin status
 * 
 * Usage:
 * Route::middleware(['auth', 'check_admin_role:super_admin'])
 * Route::middleware(['auth', 'check_admin_role:compliance_admin,finance_admin'])
 */
class CheckAdminRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  ...$roles  Required admin role(s)
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        // Check if user is authenticated
        if (!auth()->check()) {
            return redirect()->route('login')
                ->with('error', 'Please login to access the admin panel.');
        }

        $user = auth()->user();

        // Check if user has an admin account
        $admin = Admin::where('user_id', $user->id)->first();
        
        if (!$admin) {
            abort(403, 'Unauthorized. You do not have admin access.');
        }

        // Check if admin account is active
        if (!$admin->isActive()) {
            auth()->logout();
            return redirect()->route('login')
                ->with('error', 'Your admin account has been deactivated. Please contact support.');
        }

        // Check if admin has one of the required roles
        if (!empty($roles) && !in_array($admin->admin_role, $roles)) {
            abort(403, 'Unauthorized. You do not have permission to access this resource.');
        }

        // Update last login timestamp
        $admin->updateLastLogin();

        // Store admin in request for easy access in controllers
        $request->merge(['admin' => $admin]);

        return $next($request);
    }
}
