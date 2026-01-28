# 🎯 MODULE 10: ROLE-BASED ADMIN SYSTEM

## ✅ COMPLETED FILES:

### **1. Database Migration**
- ✅ `database/migrations/2026_01_28_083745_create_admins_table.php`

### **2. Model**
- ✅ `app/Models/Admin.php`

### **3. Middleware**
- ✅ `app/Http/Middleware/CheckAdminRole.php`

### **4. Controller**
- ✅ `app/Http/Controllers/Admin/DashboardController.php`

---

## 📋 REMAINING FILES TO CREATE:

I've created the core backend files. Due to the large size of this module, here's what still needs to be created:

### **Views (7 files):**
1. `resources/views/admin/dashboard/super_admin.blade.php`
2. `resources/views/admin/dashboard/compliance_admin.blade.php`
3. `resources/views/admin/dashboard/finance_admin.blade.php`
4. `resources/views/admin/dashboard/content_admin.blade.php`
5. `resources/views/admin/admins/index.blade.php`
6. `resources/views/admin/admins/create.blade.php`
7. `resources/views/admin/layouts/admin.blade.php`

### **Routes:**
8. `routes/admin.php` (new file)

### **Seeder:**
9. `database/seeders/CreateSuperAdminSeeder.php`

---

## 🚀 QUICK SETUP INSTRUCTIONS:

### **STEP 1: Run Migration**

```cmd
php artisan migrate
```

This creates the `admins` table.

### **STEP 2: Register Middleware**

Add to `bootstrap/app.php`:

```php
->withMiddleware(function (Middleware $middleware) {
    $middleware->alias([
        'check_admin_role' => \App\Http\Middleware\CheckAdminRole::class,
    ]);
})
```

### **STEP 3: Update User Model**

Add to `app/Models/User.php`:

```php
/**
 * Get the admin record for this user
 */
public function admin()
{
    return $this->hasOne(Admin::class);
}

/**
 * Check if user is an admin
 */
public function isAdmin(): bool
{
    return $this->admin()->exists();
}

/**
 * Check if user is a super admin
 */
public function isSuperAdmin(): bool
{
    return $this->admin && $this->admin->isSuperAdmin();
}
```

### **STEP 4: Create Super Admin Manually**

```cmd
php artisan tinker
```

```php
// Create super admin user
$user = App\Models\User::create([
    'name' => 'Super Admin',
    'email' => 'superadmin@360winestate.com',
    'phone' => '+2348000000000',
    'password' => bcrypt('SuperAdmin@123'),
    'status' => 'approved',
    'email_verified_at' => now(),
]);

// Create admin record
$admin = App\Models\Admin::create([
    'user_id' => $user->id,
    'admin_role' => 'super_admin',
    'status' => 'active',
    'created_by' => null, // First admin, no creator
]);

echo "Super Admin created!\n";
echo "Email: superadmin@360winestate.com\n";
echo "Password: SuperAdmin@123\n";

exit
```

### **STEP 5: Create Admin Routes**

Create `routes/admin.php`:

```php
<?php

use App\Http\Controllers\Admin\DashboardController;
use Illuminate\Support\Facades\Route;

// Super Admin Only Routes
Route::middleware(['auth', 'check_admin_role:super_admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
    Route::get('/admins', [DashboardController::class, 'admins'])->name('admin.admins');
    Route::get('/admins/create', [DashboardController::class, 'createAdminForm'])->name('admin.create');
    Route::post('/admins', [DashboardController::class, 'storeAdmin'])->name('admin.store');
    Route::get('/admins/{id}/edit', [DashboardController::class, 'editAdminForm'])->name('admin.edit');
    Route::post('/admins/{id}', [DashboardController::class, 'updateAdmin'])->name('admin.update');
    Route::post('/admins/{id}/deactivate', [DashboardController::class, 'deactivateAdmin'])->name('admin.deactivate');
    Route::post('/admins/{id}/activate', [DashboardController::class, 'activateAdmin'])->name('admin.activate');
});

// Compliance Admin Routes
Route::middleware(['auth', 'check_admin_role:compliance_admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
    Route::get('/kyc', [DashboardController::class, 'kyc'])->name('admin.kyc');
});

// Finance Admin Routes
Route::middleware(['auth', 'check_admin_role:finance_admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
    Route::get('/payments', [DashboardController::class, 'payments'])->name('admin.payments');
    Route::get('/commissions', [DashboardController::class, 'commissions'])->name('admin.commissions');
});

// Content Admin Routes
Route::middleware(['auth', 'check_admin_role:content_admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
    Route::get('/projects', [DashboardController::class, 'projects'])->name('admin.projects');
});

// All Admin Types Can Access
Route::middleware(['auth', 'check_admin_role:super_admin,compliance_admin,finance_admin,content_admin'])->prefix('admin')->group(function () {
    Route::get('/users', [DashboardController::class, 'users'])->name('admin.users');
    Route::get('/analytics', [DashboardController::class, 'analytics'])->name('admin.analytics');
    Route::post('/logout', [DashboardController::class, 'logout'])->name('admin.logout');
});
```

Then include in `bootstrap/app.php`:

```php
->withRouting(
    web: __DIR__.'/../routes/web.php',
    commands: __DIR__.'/../routes/console.php',
    health: '/up',
    then: function () {
        Route::middleware('web')
            ->group(base_path('routes/admin.php'));
    },
)
```

---

## 🎨 VIEW TEMPLATES (SIMPLIFIED):

### **Super Admin Dashboard** (`resources/views/admin/dashboard/super_admin.blade.php`)

```blade
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Super Admin Dashboard - 360WinEstate</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Poppins', sans-serif; background: #f8f9fa; }
        .sidebar { background: #0F1A3C; min-height: 100vh; color: white; }
        .sidebar a { color: white; text-decoration: none; padding: 10px 20px; display: block; }
        .sidebar a:hover { background: #E4B400; color: #0F1A3C; }
        .stat-card { background: white; border-radius: 10px; padding: 20px; margin-bottom: 20px; }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-2 sidebar">
                <h3 class="text-center mt-3">360<span style="color:#E4B400">Win</span>Estate</h3>
                <hr>
                <a href="{{ route('admin.dashboard') }}"><i class="bi bi-speedometer2"></i> Dashboard</a>
                <a href="{{ route('admin.admins') }}"><i class="bi bi-people"></i> Manage Admins</a>
                <a href="{{ route('admin.users') }}"><i class="bi bi-person"></i> Users</a>
                <a href="{{ route('admin.kyc') }}"><i class="bi bi-file-check"></i> KYC</a>
                <a href="{{ route('admin.analytics') }}"><i class="bi bi-graph-up"></i> Analytics</a>
                <form action="{{ route('admin.logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-link text-white"><i class="bi bi-box-arrow-right"></i> Logout</button>
                </form>
            </div>

            <!-- Main Content -->
            <div class="col-md-10 p-4">
                <h2>Super Admin Dashboard</h2>
                <p>Welcome, {{ $user->name }}! <span class="badge bg-danger">Super Admin</span></p>

                <div class="row">
                    <div class="col-md-4">
                        <div class="stat-card">
                            <h3>{{ $totalUsers }}</h3>
                            <p>Total Users</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="stat-card">
                            <h3>{{ $approvedUsers }}</h3>
                            <p>Approved Users</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="stat-card">
                            <h3>{{ $activeAdmins }}</h3>
                            <p>Active Admins</p>
                        </div>
                    </div>
                </div>

                <div class="mt-4">
                    <a href="{{ route('admin.create') }}" class="btn btn-primary">Create New Admin</a>
                    <a href="{{ route('admin.admins') }}" class="btn btn-secondary">Manage Admins</a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
```

---

## 🧪 TESTING:

### **1. Login as Super Admin:**
```
URL: http://localhost:8000/login
Email: superadmin@360winestate.com
Password: SuperAdmin@123
```

### **2. Access Admin Dashboard:**
```
URL: http://localhost:8000/admin/dashboard
```

### **3. Create Other Admins:**
```
URL: http://localhost:8000/admin/admins/create
```

---

## 📊 ADMIN ROLES SUMMARY:

| Role | Access |
|------|--------|
| **Super Admin** | Full access + create admins |
| **Compliance Admin** | KYC review only |
| **Finance Admin** | Payments & commissions only |
| **Content Admin** | Projects & content only |

---

## 🔒 SECURITY FEATURES:

✅ Role-based middleware on all routes  
✅ Cannot create super admin via form  
✅ Cannot deactivate yourself  
✅ Cannot edit super admin  
✅ Activity logging  
✅ Last login tracking  
✅ Active status check  

---

## 📝 NEXT STEPS:

1. ✅ Run migration
2. ✅ Register middleware
3. ✅ Update User model
4. ✅ Create super admin
5. ✅ Create admin routes
6. ⏳ Create view templates (simplified version provided above)
7. ⏳ Test admin login
8. ⏳ Create other admin types

---

**Would you like me to:**
1. Create all the view templates?
2. Create the seeder file?
3. Create a complete testing guide?
4. Something else?

Let me know what you need next!
