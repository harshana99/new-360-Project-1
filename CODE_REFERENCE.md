# 360WinEstate Module 1 - Complete Code Reference

## 📚 Quick Reference Guide

This document provides quick access to all important code snippets and configurations.

---

## 🗄️ Database Migration

**File:** `database/migrations/2024_01_01_000000_create_users_table.php`

```php
Schema::create('users', function (Blueprint $table) {
    $table->id();
    $table->string('name');
    $table->string('email')->unique();
    $table->string('phone', 20)->nullable();
    $table->timestamp('email_verified_at')->nullable();
    $table->string('password');
    $table->enum('membership_type', ['owner', 'investor', 'marketer'])->nullable();
    $table->enum('status', [
        'registered',
        'membership_selected',
        'kyc_submitted',
        'under_review',
        'approved',
        'rejected'
    ])->default('registered');
    $table->timestamp('membership_selected_at')->nullable();
    $table->timestamp('kyc_submitted_at')->nullable();
    $table->timestamp('approved_at')->nullable();
    $table->timestamp('rejected_at')->nullable();
    $table->text('rejection_reason')->nullable();
    $table->rememberToken();
    $table->timestamps();
    $table->softDeletes();
});
```

---

## 🎨 User Model - Key Methods

**File:** `app/Models/User.php`

### Status Checking
```php
// Check if user has selected membership
$user->hasMembership(); // bool

// Check if user is approved
$user->isApproved(); // bool

// Check if user is rejected
$user->isRejected(); // bool

// Check if user is under review
$user->isUnderReview(); // bool

// Check if user can access dashboard
$user->canAccessDashboard(); // bool
```

### Status Updates
```php
// Select membership
$user->selectMembership('investor');

// Submit KYC
$user->submitKyc();

// Approve user
$user->approve();

// Reject user
$user->reject('Reason for rejection');
```

### Display Helpers
```php
// Get membership label
$user->getMembershipTypeLabel(); // "Property Owner"

// Get status label
$user->getStatusLabel(); // "Under Review"

// Get status badge class
$user->getStatusBadgeClass(); // "bg-warning"
```

---

## 🛣️ Routes Configuration

**File:** `routes/web.php`

### Guest Routes
```php
Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});
```

### Authenticated Routes
```php
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    
    // Email verification
    Route::get('/email/verify', [AuthController::class, 'showVerificationNotice'])
        ->name('verification.notice');
    Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
        $request->fulfill();
        // Redirect logic...
    })->middleware(['signed'])->name('verification.verify');
});
```

### Verified Routes
```php
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/membership/select', [MembershipController::class, 'showSelectionForm'])
        ->name('membership.select');
    Route::post('/membership/select', [MembershipController::class, 'selectMembership']);
    Route::get('/dashboard/locked', [DashboardController::class, 'locked'])
        ->name('dashboard.locked');
});
```

### Approved Routes
```php
Route::middleware(['auth', 'verified', 'check.approved'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');
});
```

---

## 🔒 Middleware

**File:** `app/Http/Middleware/CheckApproved.php`

```php
public function handle(Request $request, Closure $next): Response
{
    $user = $request->user();

    if (!$user) {
        return redirect()->route('login');
    }

    if (!$user->hasVerifiedEmail()) {
        return redirect()->route('verification.notice')
            ->with('warning', 'Please verify your email address to continue.');
    }

    if (!$user->hasMembership()) {
        return redirect()->route('membership.select')
            ->with('info', 'Please select your membership type to continue.');
    }

    if (!$user->isApproved()) {
        return redirect()->route('dashboard.locked')
            ->with('warning', 'Your account is pending approval.');
    }

    return $next($request);
}
```

**Register Middleware:** `bootstrap/app.php`
```php
->withMiddleware(function (Middleware $middleware) {
    $middleware->alias([
        'check.approved' => \App\Http\Middleware\CheckApproved::class,
    ]);
})
```

---

## 📝 Form Validation

### Registration Validation
**File:** `app/Http/Requests/RegisterRequest.php`

```php
public function rules(): array
{
    return [
        'name' => [
            'required',
            'string',
            'max:255',
            'min:2',
            'regex:/^[a-zA-Z\s]+$/',
        ],
        'email' => [
            'required',
            'string',
            'email:rfc,dns',
            'max:255',
            'unique:users,email',
        ],
        'phone' => [
            'nullable',
            'string',
            'max:20',
            'regex:/^[0-9+\-\s()]*$/',
        ],
        'password' => [
            'required',
            'confirmed',
            Password::min(8)
                ->mixedCase()
                ->numbers()
                ->symbols()
                ->uncompromised(),
        ],
    ];
}
```

### Login Validation
**File:** `app/Http/Requests/LoginRequest.php`

```php
public function rules(): array
{
    return [
        'email' => ['required', 'email', 'exists:users,email'],
        'password' => ['required', 'string'],
        'remember' => ['nullable', 'boolean'],
    ];
}
```

---

## 🎨 Views - Blade Templates

### Layout Template
**File:** `resources/views/layouts/app.blade.php`

```blade
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', '360WinEstate')</title>
    
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    
    <!-- Poppins Font -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --navy: #0F1A3C;
            --gold: #E4B400;
            --white: #FFFFFF;
        }
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, var(--navy) 0%, #1a2847 100%);
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light">
        <div class="container">
            <a class="navbar-brand" href="/">
                360<span class="gold-text">Win</span>Estate
            </a>
            @auth
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-outline-primary">
                        Logout
                    </button>
                </form>
            @endauth
        </div>
    </nav>
    
    <main class="py-5">
        @yield('content')
    </main>
</body>
</html>
```

### Registration Form
**File:** `resources/views/auth/register.blade.php`

```blade
@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card">
                <div class="card-header text-center">
                    <h4>Create Your Account</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('register') }}" method="POST">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="name" class="form-label">Full Name</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                   id="name" name="name" value="{{ old('name') }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <!-- More fields... -->
                        
                        <button type="submit" class="btn btn-primary w-100">
                            Create Account
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
```

---

## 🎮 Controllers

### AuthController - Registration
```php
public function register(Request $request)
{
    $validated = $request->validate([
        'name' => ['required', 'string', 'max:255', 'min:2'],
        'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
        'phone' => ['nullable', 'string', 'max:20'],
        'password' => ['required', 'confirmed', Password::min(8)
            ->mixedCase()->numbers()->symbols()->uncompromised()],
    ]);

    $user = User::create([
        'name' => $validated['name'],
        'email' => $validated['email'],
        'phone' => $validated['phone'] ?? null,
        'password' => Hash::make($validated['password']),
        'status' => User::STATUS_REGISTERED,
    ]);

    event(new Registered($user));
    Auth::login($user);

    return redirect()->route('verification.notice')
        ->with('success', 'Registration successful!');
}
```

### AuthController - Login
```php
public function login(Request $request)
{
    $credentials = $request->validate([
        'email' => ['required', 'email'],
        'password' => ['required'],
    ]);

    $remember = $request->boolean('remember');

    if (Auth::attempt($credentials, $remember)) {
        $request->session()->regenerate();
        $user = Auth::user();

        if (!$user->hasVerifiedEmail()) {
            return redirect()->route('verification.notice');
        }

        return $this->redirectBasedOnStatus($user);
    }

    return back()->withErrors([
        'email' => 'The provided credentials do not match our records.',
    ]);
}
```

### MembershipController - Select Membership
```php
public function selectMembership(Request $request)
{
    $validated = $request->validate([
        'membership_type' => ['required', 'in:owner,investor,marketer'],
    ]);

    $user = Auth::user();

    if ($user->hasMembership()) {
        return redirect()->route('dashboard.locked')
            ->with('warning', 'You have already selected a membership type.');
    }

    $user->selectMembership($validated['membership_type']);

    return redirect()->route('dashboard.locked')
        ->with('success', 'Membership type selected successfully!');
}
```

---

## ⚙️ Environment Configuration

**File:** `.env`

```env
APP_NAME="360WinEstate"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost

# Database
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=360winestate
DB_USERNAME=root
DB_PASSWORD=

# Email (Mailtrap for development)
MAIL_MAILER=smtp
MAIL_HOST=sandbox.smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your_username
MAIL_PASSWORD=your_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@360winestate.com
MAIL_FROM_NAME="${APP_NAME}"

# Session
SESSION_DRIVER=database
SESSION_LIFETIME=120
```

---

## 🗃️ Database Seeder

**File:** `database/seeders/UserSeeder.php`

```php
// Create approved owner
User::create([
    'name' => 'John Owner',
    'email' => 'owner@360winestate.com',
    'password' => Hash::make('Owner@123'),
    'email_verified_at' => now(),
    'membership_type' => User::MEMBERSHIP_OWNER,
    'status' => User::STATUS_APPROVED,
    'approved_at' => now(),
]);

// Run seeder
php artisan db:seed --class=UserSeeder
```

---

## 🔧 Artisan Commands

### Setup
```bash
# Install dependencies
composer install

# Copy environment file
cp .env.example .env

# Generate app key
php artisan key:generate

# Run migrations
php artisan migrate

# Seed database
php artisan db:seed --class=UserSeeder

# Start server
php artisan serve
```

### Maintenance
```bash
# Clear all caches
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Fresh migration
php artisan migrate:fresh

# Fresh migration with seed
php artisan migrate:fresh --seed
```

### Tinker Commands
```bash
php artisan tinker

# Create user
$user = User::create([
    'name' => 'Test User',
    'email' => 'test@example.com',
    'password' => bcrypt('Test@123'),
    'email_verified_at' => now(),
]);

# Approve user
$user = User::where('email', 'test@example.com')->first();
$user->approve();

# Reject user
$user->reject('Invalid documents');

# Check user status
$user->status;
$user->isApproved();
```

---

## 🎯 Common Code Patterns

### Display Success Message
```blade
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">
        <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif
```

### Display Validation Errors
```blade
<input type="text" class="form-control @error('name') is-invalid @enderror" 
       name="name" value="{{ old('name') }}">
@error('name')
    <div class="invalid-feedback">{{ $message }}</div>
@enderror
```

### Check User Status in Blade
```blade
@if($user->isApproved())
    <span class="badge bg-success">Approved</span>
@elseif($user->isRejected())
    <span class="badge bg-danger">Rejected</span>
@else
    <span class="badge bg-warning">Pending</span>
@endif
```

### Protect Route with Middleware
```php
Route::middleware(['auth', 'verified', 'check.approved'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index']);
});
```

---

## 📊 Status Flow Diagram

```
┌─────────────────────────────────────────────────────────────┐
│                     USER REGISTRATION                        │
│                    (status: registered)                      │
└──────────────────────────┬──────────────────────────────────┘
                           │
                           ▼
┌─────────────────────────────────────────────────────────────┐
│                   EMAIL VERIFICATION                         │
│              (email_verified_at timestamp)                   │
└──────────────────────────┬──────────────────────────────────┘
                           │
                           ▼
┌─────────────────────────────────────────────────────────────┐
│                  MEMBERSHIP SELECTION                        │
│           (status: membership_selected)                      │
│     Owner / Investor / Marketer                             │
└──────────────────────────┬──────────────────────────────────┘
                           │
                           ▼
┌─────────────────────────────────────────────────────────────┐
│                    KYC SUBMISSION                            │
│              (status: kyc_submitted)                         │
└──────────────────────────┬──────────────────────────────────┘
                           │
                           ▼
┌─────────────────────────────────────────────────────────────┐
│                    ADMIN REVIEW                              │
│              (status: under_review)                          │
└──────────────────────────┬──────────────────────────────────┘
                           │
                ┌──────────┴──────────┐
                ▼                     ▼
┌───────────────────────┐   ┌───────────────────────┐
│      APPROVED         │   │      REJECTED         │
│ (status: approved)    │   │ (status: rejected)    │
│ Access Full Dashboard │   │ Show Rejection Reason │
└───────────────────────┘   └───────────────────────┘
```

---

## 🎨 Color Scheme

```css
:root {
    --navy: #0F1A3C;      /* Primary brand color */
    --gold: #E4B400;      /* Accent/CTA color */
    --white: #FFFFFF;     /* Background */
}

/* Usage */
.btn-primary {
    background: var(--gold);
    color: var(--navy);
}

.navbar {
    background: var(--white);
}

body {
    background: linear-gradient(135deg, var(--navy) 0%, #1a2847 100%);
}
```

---

## 📱 Responsive Breakpoints

```css
/* Mobile First */
@media (max-width: 768px) {
    .card-body {
        padding: 1.5rem;
    }
}

/* Tablet */
@media (min-width: 768px) and (max-width: 1024px) {
    /* Tablet styles */
}

/* Desktop */
@media (min-width: 1024px) {
    /* Desktop styles */
}
```

---

## 🔐 Security Checklist

- ✅ CSRF tokens on all forms
- ✅ Password hashing (bcrypt)
- ✅ Email verification required
- ✅ Strong password validation
- ✅ SQL injection protection (Eloquent ORM)
- ✅ XSS protection (Blade escaping)
- ✅ Rate limiting on email verification
- ✅ Session regeneration on login
- ✅ Secure logout (session invalidation)
- ✅ Middleware protection on routes
- ✅ Soft deletes for data recovery

---

**Quick Reference Complete! 📚**
