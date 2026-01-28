<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

/**
 * Authentication Controller
 * 
 * Handles user registration, login, logout, and email verification
 * for the 360WinEstate platform.
 */
class AuthController extends Controller
{
    /**
     * Show registration form
     *
     * @return \Illuminate\View\View
     */
    public function showRegisterForm()
    {
        return view('auth.register');
    }

    /**
     * Handle user registration
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function register(Request $request)
    {
        // Validate registration data
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'min:2'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'phone' => ['nullable', 'string', 'max:20', 'regex:/^[0-9+\-\s()]*$/'],
            'password' => ['required', 'confirmed', Password::min(8)
                ->mixedCase()
                ->numbers()
                ->symbols()
                ->uncompromised()],
        ], [
            'name.required' => 'Please enter your full name.',
            'name.min' => 'Name must be at least 2 characters.',
            'email.required' => 'Email address is required.',
            'email.email' => 'Please enter a valid email address.',
            'email.unique' => 'This email is already registered.',
            'phone.regex' => 'Please enter a valid phone number.',
            'password.required' => 'Password is required.',
            'password.confirmed' => 'Password confirmation does not match.',
        ]);

        // Create new user
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'password' => Hash::make($validated['password']),
            'status' => User::STATUS_REGISTERED,
        ]);

        // Fire the Registered event (triggers email verification)
        event(new Registered($user));

        // Log the user in
        Auth::login($user);

        // Redirect to email verification notice
        return redirect()->route('verification.notice')
            ->with('success', 'Registration successful! Please verify your email address.');
    }

    /**
     * Show login form
     *
     * @return \Illuminate\View\View
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Handle user login
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function login(Request $request)
    {
        // Validate login credentials
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ], [
            'email.required' => 'Email address is required.',
            'email.email' => 'Please enter a valid email address.',
            'password.required' => 'Password is required.',
        ]);

        // Remember me checkbox
        $remember = $request->boolean('remember');

        // Attempt to authenticate user
        if (Auth::attempt($credentials, $remember)) {
            // Regenerate session to prevent fixation attacks
            $request->session()->regenerate();

            $user = Auth::user();

            // Check if email is verified
            if (!$user->hasVerifiedEmail()) {
                return redirect()->route('verification.notice')
                    ->with('warning', 'Please verify your email address to continue.');
            }

            // Redirect based on user status
            return $this->redirectBasedOnStatus($user);
        }

        // Authentication failed
        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    /**
     * Handle user logout
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout(Request $request)
    {
        Auth::logout();

        // Invalidate the session
        $request->session()->invalidate();

        // Regenerate CSRF token
        $request->session()->regenerateToken();

        return redirect()->route('login')
            ->with('success', 'You have been logged out successfully.');
    }

    /**
     * Show email verification notice
     *
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function showVerificationNotice()
    {
        // If already verified, redirect based on status
        if (Auth::user()->hasVerifiedEmail()) {
            return $this->redirectBasedOnStatus(Auth::user());
        }

        return view('auth.verify-email');
    }

    /**
     * Resend email verification link
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function resendVerificationEmail(Request $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->route('dashboard');
        }

        $request->user()->sendEmailVerificationNotification();

        return back()->with('success', 'Verification link sent! Please check your email.');
    }

    /**
     * Redirect user based on their status
     *
     * @param User $user
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function redirectBasedOnStatus(User $user)
    {
        // If approved, go to dashboard
        if ($user->isApproved()) {
            return redirect()->route('dashboard')
                ->with('success', 'Welcome back, ' . $user->name . '!');
        }

        // If no membership selected, go to membership selection
        if (!$user->hasMembership()) {
            return redirect()->route('membership.select')
                ->with('info', 'Please select your membership type to continue.');
        }

        // Otherwise, show locked dashboard
        return redirect()->route('dashboard.locked')
            ->with('info', 'Your account is currently under review.');
    }
}
