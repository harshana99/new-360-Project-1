# 360WinEstate - Module 1: Authentication System

## Overview
Complete authentication and membership management system for the 360WinEstate platform built with Laravel 11.

## Features
✅ User Registration with Email Verification
✅ Secure Login/Logout System
✅ Membership Selection (Owner/Investor/Marketer)
✅ Status Tracking System
✅ Locked Dashboard for Pending Approvals
✅ Full Dashboard for Approved Users
✅ Bootstrap 5 UI with Custom Branding

## Installation

### Prerequisites
- PHP 8.2 or higher
- Composer
- MySQL or PostgreSQL
- Node.js & NPM (for assets)

### Setup Steps

1. **Install Laravel Dependencies**
```bash
composer install
```

2. **Environment Configuration**
```bash
cp .env.example .env
php artisan key:generate
```

3. **Configure Database**
Edit `.env` file:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=360winestate
DB_USERNAME=root
DB_PASSWORD=your_password
```

4. **Configure Email**
Edit `.env` file for email verification:
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your_username
MAIL_PASSWORD=your_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@360winestate.com
MAIL_FROM_NAME="360WinEstate"
```

5. **Run Migrations**
```bash
php artisan migrate
```

6. **Start Development Server**
```bash
php artisan serve
```

Visit: http://localhost:8000

## User Status Flow

```
registered 
    ↓ (email verified)
membership_selected 
    ↓ (KYC submitted)
kyc_submitted 
    ↓ (admin review)
under_review 
    ↓ (admin decision)
approved OR rejected
```

## Routes

### Guest Routes
- `GET /register` - Registration form
- `POST /register` - Handle registration
- `GET /login` - Login form
- `POST /login` - Handle login

### Authenticated Routes
- `POST /logout` - Logout user
- `GET /email/verify` - Email verification notice
- `GET /email/verify/{id}/{hash}` - Verify email
- `POST /email/verification-notification` - Resend verification

### Verified Email Routes
- `GET /membership/select` - Membership selection form
- `POST /membership/select` - Handle membership selection
- `GET /dashboard/locked` - Locked dashboard (pending approval)

### Approved User Routes
- `GET /dashboard` - Main dashboard (requires approval)

## Middleware

### `CheckApproved`
Ensures only approved users can access protected routes.

**Checks:**
1. User is authenticated
2. Email is verified
3. Membership is selected
4. Status is 'approved'

**Usage:**
```php
Route::middleware(['auth', 'verified', 'check.approved'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index']);
});
```

## Models

### User Model

**Key Methods:**
- `hasMembership()` - Check if user has selected membership
- `isApproved()` - Check if user is approved
- `isRejected()` - Check if user is rejected
- `isUnderReview()` - Check if user is under review
- `canAccessDashboard()` - Check if user can access dashboard
- `selectMembership($type)` - Update membership selection
- `submitKyc()` - Mark KYC as submitted
- `approve()` - Approve user
- `reject($reason)` - Reject user with reason

**Status Constants:**
- `STATUS_REGISTERED`
- `STATUS_MEMBERSHIP_SELECTED`
- `STATUS_KYC_SUBMITTED`
- `STATUS_UNDER_REVIEW`
- `STATUS_APPROVED`
- `STATUS_REJECTED`

**Membership Constants:**
- `MEMBERSHIP_OWNER`
- `MEMBERSHIP_INVESTOR`
- `MEMBERSHIP_MARKETER`

## Controllers

### AuthController
Handles authentication operations:
- `showRegisterForm()` - Display registration form
- `register()` - Process registration
- `showLoginForm()` - Display login form
- `login()` - Process login
- `logout()` - Process logout
- `showVerificationNotice()` - Display email verification notice
- `resendVerificationEmail()` - Resend verification email

### MembershipController
Handles membership selection:
- `showSelectionForm()` - Display membership options
- `selectMembership()` - Process membership selection

### DashboardController
Handles dashboard views:
- `index()` - Main dashboard (approved users)
- `locked()` - Locked dashboard (pending users)

## Validation Rules

### Registration
- **Name:** Required, 2-255 characters, letters only
- **Email:** Required, valid email, unique, DNS validation
- **Phone:** Optional, valid phone format
- **Password:** Required, min 8 chars, mixed case, numbers, symbols, not compromised

### Login
- **Email:** Required, valid email, must exist
- **Password:** Required

### Membership Selection
- **Membership Type:** Required, must be 'owner', 'investor', or 'marketer'

## Security Features

✅ **CSRF Protection** - All forms include CSRF tokens
✅ **Password Hashing** - Bcrypt hashing with automatic casting
✅ **Session Management** - Secure session handling
✅ **Email Verification** - Required before accessing features
✅ **Password Validation** - Strong password requirements
✅ **SQL Injection Protection** - Eloquent ORM with parameter binding
✅ **XSS Protection** - Blade template escaping
✅ **Rate Limiting** - Email verification throttled (6 per minute)

## Database Schema

### Users Table
```sql
- id (bigint, primary key)
- name (varchar 255)
- email (varchar 255, unique)
- phone (varchar 20, nullable)
- email_verified_at (timestamp, nullable)
- password (varchar 255)
- membership_type (enum: owner, investor, marketer, nullable)
- status (enum: registered, membership_selected, kyc_submitted, under_review, approved, rejected)
- membership_selected_at (timestamp, nullable)
- kyc_submitted_at (timestamp, nullable)
- approved_at (timestamp, nullable)
- rejected_at (timestamp, nullable)
- rejection_reason (text, nullable)
- remember_token (varchar 100)
- created_at (timestamp)
- updated_at (timestamp)
- deleted_at (timestamp, nullable)
```

## Design System

### Colors
- **Navy:** `#0F1A3C` - Primary brand color
- **Gold:** `#E4B400` - Accent color
- **White:** `#FFFFFF` - Background

### Typography
- **Font Family:** Poppins (Google Fonts)
- **Weights:** 300, 400, 500, 600, 700

### Components
- Bootstrap 5.3.0
- Bootstrap Icons 1.11.0
- Custom card designs
- Responsive layouts
- Smooth animations

## Testing

### Manual Testing Checklist

1. **Registration**
   - [ ] Valid registration creates user
   - [ ] Email verification sent
   - [ ] Duplicate email rejected
   - [ ] Weak password rejected
   - [ ] Invalid email rejected

2. **Login**
   - [ ] Valid credentials work
   - [ ] Invalid credentials rejected
   - [ ] Remember me works
   - [ ] Unverified email redirected

3. **Email Verification**
   - [ ] Verification link works
   - [ ] Resend verification works
   - [ ] Already verified handled

4. **Membership Selection**
   - [ ] All three types selectable
   - [ ] Status updated correctly
   - [ ] Cannot select twice

5. **Dashboard Access**
   - [ ] Approved users see full dashboard
   - [ ] Pending users see locked dashboard
   - [ ] Rejected users see rejection message

## Admin Operations

To manually approve a user (via Tinker):
```php
php artisan tinker

$user = User::where('email', 'user@example.com')->first();
$user->approve();
```

To reject a user:
```php
$user = User::where('email', 'user@example.com')->first();
$user->reject('KYC documents are invalid');
```

## Next Steps (Module 2)

- KYC Document Upload System
- Admin Panel for User Management
- Email Notifications
- User Profile Management
- Password Reset Functionality

## Support

For issues or questions, contact: support@360winestate.com

## License

Proprietary - 360WinEstate Platform

---

**Built with ❤️ using Laravel 11 & Bootstrap 5**
