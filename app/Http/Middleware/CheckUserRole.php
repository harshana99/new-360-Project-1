<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\User;

class CheckUserRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();

        // Check if user is approved
        if ($user->status !== User::STATUS_APPROVED) {
            // Allows access to KYC status page to avoid infinite loops if we protect that too
            if (!$request->routeIs('kyc.status')) {
                return redirect()->route('kyc.status'); 
            }
        }

        // Check Role
        // We assume $role matches the membership_type strings 'owner', 'investor', 'marketer'
        // OR user has multiple roles logic (if implemented). 
        // For now, based on User model having strict 'membership_type' string
        
        // If the user's membership type doesn't match the required role
        if ($user->membership_type !== $role) {
            // Handling multi-role: If we had a roles table, we'd check that.
            // Since we use a single column 'membership_type', strict check applies.
            
            // However, the prompt mentions "User may have multiple roles"
            // If the system allows multiple roles, they must be stored differently or check logic is different.
            // Given the User model I saw earlier has `membership_type` as a string, it implies single primary Role.
            // I will implement strict check for now. If user is 'investor' trying to access 'owner', deny.
            
            return redirect()->route('dashboard')->with('error', 'You do not have access to this area.');
        }

        return $next($request);
    }
}
