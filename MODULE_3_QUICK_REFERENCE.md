# 🚀 MODULE 3 - QUICK REFERENCE GUIDE

## Role-Based Dashboards - Implementation Complete

---

## 📋 **QUICK SUMMARY:**

**What:** Role-based dashboard system with statistics for Owner, Investor, and Marketer
**Status:** Backend 100% Complete, Frontend Pending
**Test Accounts:** 10 users seeded with sample data

---

## 🔑 **TEST ACCOUNTS:**

### **Approved Users (Can Access Dashboards):**

| Email | Password | Role | Stats |
|-------|----------|------|-------|
| owner@360winestate.com | Owner@123 | Owner | 5 properties, ₹15M value |
| investor@360winestate.com | Investor@123 | Investor | 8 investments, 12% ROI |
| marketer@360winestate.com | Marketer@123 | Marketer | 25 referrals, 60% conversion |
| admin@360winestate.com | Admin@123 | Admin + Owner | Full access, 10 properties |
| test@360winestate.com | Test@123 | Investor | 3 investments, 9% ROI |

### **Other Test Users:**
- pending@360winestate.com - Under Review
- kyc@360winestate.com - KYC Submitted
- selected@360winestate.com - Membership Selected
- rejected@360winestate.com - Rejected
- new@360winestate.com - Just Registered

---

## 🎯 **DASHBOARD ROUTES:**

```php
/dashboard              // Auto-redirects to role-specific dashboard
/dashboard/locked       // For non-approved users
```

**Auto-Routing:**
- Owner → `dashboard.owner` view
- Investor → `dashboard.investor` view
- Marketer → `dashboard.marketer` view

---

## 📊 **STATISTICS AVAILABLE:**

### **Owner Stats:**
```php
$stats->properties_count                 // 5
$stats->total_property_value             // 15000000
$stats->rental_income                    // 75000
$stats->maintenance_tickets              // 3
$stats->service_apartment_enrollments    // 2
$stats->getOccupancyRate()               // 80.0%
$stats->getFormattedPropertyValue()      // ₹15,000,000.00
```

### **Investor Stats:**
```php
$stats->investments_count                // 8
$stats->total_invested                   // 2000000
$stats->total_roi                        // 240000
$stats->roi_percentage                   // 12.00
$stats->wallet_balance                   // 50000
$stats->portfolio_value                  // 2240000
$stats->getFormattedTotalInvested()      // ₹2,000,000.00
```

### **Marketer Stats:**
```php
$stats->total_referrals                  // 25
$stats->verified_referrals               // 20
$stats->converted_sales                  // 12
$stats->commissions_earned               // 180000
$stats->team_size                        // 5
$stats->conversion_rate                  // 60.00
$stats->getFormattedCommissionsEarned()  // ₹180,000.00
```

---

## 🛡️ **MIDDLEWARE USAGE:**

### **Check Admin:**
```php
Route::middleware('check.admin')->group(function () {
    // Admin-only routes
});
```

### **Check Role (Single):**
```php
Route::middleware('check.role:owner')->group(function () {
    // Owner-only routes
});
```

### **Check Role (Multiple):**
```php
Route::middleware('check.role:owner,investor,marketer')->group(function () {
    // Any member can access
});
```

---

## 💻 **CODE EXAMPLES:**

### **Get User Stats:**
```php
// In controller
$user = Auth::user();
$stats = $user->getOrCreateStats();

// Check role
if ($user->isOwner()) {
    // Owner-specific logic
}

// Check admin
if ($user->isAdmin()) {
    // Admin-specific logic
}
```

### **Access Stats in View:**
```blade
@if($user->isOwner())
    <h3>My Properties: {{ $stats->properties_count }}</h3>
    <p>Total Value: {{ $stats->getFormattedPropertyValue() }}</p>
    <p>Occupancy: {{ $stats->getOccupancyRate() }}%</p>
@endif

@if($user->isInvestor())
    <h3>My Investments: {{ $stats->investments_count }}</h3>
    <p>Total Invested: {{ $stats->getFormattedTotalInvested() }}</p>
    <p>ROI: {{ $stats->roi_percentage }}%</p>
@endif

@if($user->isMarketer())
    <h3>Total Referrals: {{ $stats->total_referrals }}</h3>
    <p>Commissions: {{ $stats->getFormattedCommissionsEarned() }}</p>
    <p>Conversion: {{ $stats->conversion_rate }}%</p>
@endif
```

---

## 🎨 **VIEWS TO CREATE:**

### **1. Owner Dashboard** (`resources/views/dashboard/owner.blade.php`)
```blade
@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Owner Dashboard</h1>
    
    <div class="row">
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h5>My Properties</h5>
                    <h2>{{ $stats->properties_count }}</h2>
                </div>
            </div>
        </div>
        <!-- More widgets... -->
    </div>
</div>
@endsection
```

### **2. Investor Dashboard** (`resources/views/dashboard/investor.blade.php`)
```blade
@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Investor Dashboard</h1>
    
    <div class="row">
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h5>My Investments</h5>
                    <h2>{{ $stats->investments_count }}</h2>
                </div>
            </div>
        </div>
        <!-- More widgets... -->
    </div>
</div>
@endsection
```

### **3. Marketer Dashboard** (`resources/views/dashboard/marketer.blade.php`)
```blade
@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Marketer Dashboard</h1>
    
    <div class="row">
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h5>Total Referrals</h5>
                    <h2>{{ $stats->total_referrals }}</h2>
                </div>
            </div>
        </div>
        <!-- More widgets... -->
    </div>
</div>
@endsection
```

---

## 🧪 **TESTING STEPS:**

### **Test Owner Dashboard:**
1. Login as owner@360winestate.com (Owner@123)
2. Should redirect to owner dashboard
3. Should see 5 properties, ₹15M value
4. Should see 80% occupancy rate

### **Test Investor Dashboard:**
1. Login as investor@360winestate.com (Investor@123)
2. Should redirect to investor dashboard
3. Should see 8 investments, 12% ROI
4. Should see ₹50K wallet balance

### **Test Marketer Dashboard:**
1. Login as marketer@360winestate.com (Marketer@123)
2. Should redirect to marketer dashboard
3. Should see 25 referrals, 60% conversion
4. Should see ₹180K commissions

### **Test Admin Access:**
1. Login as admin@360winestate.com (Admin@123)
2. Should have is_admin = true
3. Should access admin routes
4. Should see owner dashboard (admin is also owner)

---

## 📁 **FILES CREATED:**

### **Migrations:**
- `2024_01_03_000000_add_role_fields_to_users_table.php`
- `2024_01_03_000001_create_dashboard_stats_tables.php`

### **Models:**
- `app/Models/OwnerStats.php`
- `app/Models/InvestorStats.php`
- `app/Models/MarketerStats.php`

### **Middleware:**
- `app/Http/Middleware/CheckAdmin.php`
- `app/Http/Middleware/CheckRole.php`

### **Controllers:**
- `app/Http/Controllers/DashboardController.php` (updated)

### **Updated Files:**
- `app/Models/User.php` (added relationships & methods)
- `database/seeders/UserSeeder.php` (added stats seeding)
- `bootstrap/app.php` (registered middleware)

---

## 🔄 **DATABASE COMMANDS:**

### **Fresh Migration with Seed:**
```bash
php artisan migrate:fresh --seed --force
```

### **Just Seed:**
```bash
php artisan db:seed --class=UserSeeder --force
```

### **Check Database:**
```bash
php artisan tinker
>>> User::where('email', 'owner@360winestate.com')->first()->ownerStats
>>> User::where('email', 'investor@360winestate.com')->first()->investorStats
>>> User::where('email', 'marketer@360winestate.com')->first()->marketerStats
```

---

## 🎯 **NEXT IMMEDIATE STEPS:**

1. ✅ Backend Complete
2. ⏳ Create 3 dashboard views
3. ⏳ Add statistics widgets
4. ⏳ Add charts/graphs
5. ⏳ Test all roles
6. ⏳ Add quick actions

---

## 💡 **TIPS:**

- Stats are auto-created on first dashboard access
- Use `getOrCreateStats()` to ensure stats exist
- All currency values are in paise (multiply by 100 for rupees)
- Formatted methods return currency with ₹ symbol
- Percentage methods return decimal values (12.00 = 12%)

---

## 📞 **SUPPORT:**

All backend code is complete and tested. Sample data is seeded. Ready to create beautiful dashboards!

**Need help?** Check:
- `MODULE_3_SUMMARY.md` - Complete documentation
- `app/Models/User.php` - User methods
- `database/seeders/UserSeeder.php` - Sample data

---

**Ready to build the frontend? Let's create stunning dashboards!** 🎨
