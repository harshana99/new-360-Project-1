<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Admin;
use App\Models\Activity;

class AuthController extends Controller
{
    /**
     * Show admin login form
     */
    public function showLoginForm()
    {
        // If already logged in as admin, redirect to dashboard
        if (Auth::check() && Auth::user()->admin) {
            return redirect()->route('admin.dashboard');
        }

        return view('admin.auth.login');
    }

    /**
     * Handle admin login
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');
        $remember = $request->filled('remember');

        if (Auth::attempt($credentials, $remember)) {
            $user = Auth::user();
            
            // Check if user is an admin
            if ($user->admin && $user->admin->isActive()) {
                $request->session()->regenerate();
                
                // Log admin login activity
                Activity::log(
                    $user->id,
                    Activity::TYPE_LOGIN,
                    'Admin logged in',
                    $user->admin->id
                );

                return redirect()->intended(route('admin.dashboard'))
                    ->with('success', 'Welcome back, ' . $user->name . '!');
            } else {
                // Not an admin, logout
                Auth::logout();
                return back()->withErrors([
                    'email' => 'You do not have admin access.',
                ])->withInput($request->only('email'));
            }
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->withInput($request->only('email'));
    }

    /**
     * Handle admin logout
     */
    public function logout(Request $request)
    {
        $user = Auth::user();
        
        // Log admin logout activity
        if ($user && $user->admin) {
            Activity::log(
                $user->id,
                Activity::TYPE_LOGOUT,
                'Admin logged out',
                $user->admin->id
            );
        }

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login')
            ->with('success', 'You have been logged out successfully.');
    }
}
