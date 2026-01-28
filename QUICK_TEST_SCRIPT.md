# 🎯 QUICK TEST SCRIPT - Run These Commands

## ✅ **STEP 1: VERIFY DATABASE (COMPLETED!)**

The `documents` table has been created successfully!

Run this to verify all tables exist:

```cmd
php artisan tinker
```

Then paste this:

```php
// Check all required tables
$tables = [
    'users',
    'roles',
    'role_user',
    'permissions',
    'permission_role',
    'kyc_submissions',
    'kyc_documents',
    'documents',
    'owner_stats',
    'investor_stats',
    'marketer_stats',
    'properties',
    'sessions'
];

foreach ($tables as $table) {
    $exists = Schema::hasTable($table);
    echo $table . ': ' . ($exists ? '✓ EXISTS' : '✗ MISSING') . "\n";
}

exit
```

---

## ✅ **STEP 2: SEED DATABASE (IF NOT DONE)**

```cmd
php artisan db:seed --class=RoleSeeder
php artisan db:seed --class=PermissionSeeder
php artisan db:seed --class=UserSeeder
```

---

## ✅ **STEP 3: VERIFY TEST ACCOUNTS**

```cmd
php artisan tinker
```

```php
// Check test users exist
App\Models\User::all()->pluck('email');

// Should see:
// - owner@360winestate.com
// - investor@360winestate.com
// - marketer@360winestate.com
// - etc.

exit
```

---

## ✅ **STEP 4: START SERVER**

```cmd
php artisan serve
```

Server will start at: **http://localhost:8000**

---

## ✅ **STEP 5: TEST MODULE 1 - AUTHENTICATION**

### **Test 1: Registration**

1. **Open browser:** http://localhost:8000/register
2. **Fill form:**
   - Name: Test User
   - Email: test@example.com
   - Phone: +2348012345678
   - Password: Test@123
   - Confirm: Test@123
3. **Click "Register"**
4. **Expected:** Redirect to email verification page

### **Test 2: Verify Email (Manual)**

```cmd
php artisan tinker
```

```php
$user = App\Models\User::where('email', 'test@example.com')->first();
$user->email_verified_at = now();
$user->save();
echo "Email verified!\n";
exit
```

### **Test 3: Login**

1. **Open:** http://localhost:8000/login
2. **Login:**
   - Email: test@example.com
   - Password: Test@123
3. **Expected:** Redirect to membership selection

### **Test 4: Select Membership**

1. **Should be at:** http://localhost:8000/membership/select
2. **Click "Select" on Owner card**
3. **Expected:** Redirect to locked dashboard

### **Test 5: Verify Locked Dashboard**

1. **Should be at:** http://localhost:8000/dashboard
2. **Expected to see:**
   - ✓ Progress tracker: Account ✓ | Membership ✓ | KYC ⏳ | Approval ⏳
   - ✓ "Complete Your KYC" button
   - ✓ User info displayed
   - ✓ NOT the full dashboard (should be locked)

---

## ✅ **STEP 6: TEST MODULE 2 - KYC SUBMISSION**

### **Test 6: KYC Form**

1. **Click "Complete Your KYC" button**
2. **Should be at:** http://localhost:8000/kyc/create
3. **Expected to see:**
   - ✓ KYC form with all fields
   - ✓ ID Type dropdown
   - ✓ File upload fields
   - ✓ Personal info fields

### **Test 7: Submit KYC**

1. **Fill form:**
   - ID Type: Passport
   - ID Number: A12345678
   - ID Expiry: 2030-12-31
   - Upload any images for ID Front, ID Back, Proof of Address, Selfie
   - Full Name: Test User
   - DOB: 1990-01-01
   - Nationality: Nigerian
   - Address: 123 Test St
   - City: Lagos
   - State: Lagos
   - Postal Code: 100001
   - Country: Nigeria

2. **Click "Submit KYC"**
3. **Expected:** Redirect to status page

### **Test 8: Verify KYC Submission**

```cmd
php artisan tinker
```

```php
$user = App\Models\User::where('email', 'test@example.com')->first();
$kyc = $user->kycSubmissions()->latest()->first();

echo "KYC Status: " . $kyc->status . "\n";
echo "Documents: " . $kyc->documents->count() . "\n";
echo "User Status: " . $user->user_status . "\n";

exit
```

---

## ✅ **STEP 7: TEST ADMIN KYC REVIEW**

### **Test 9: Create Admin User**

```cmd
php artisan tinker
```

```php
$admin = App\Models\User::create([
    'name' => 'Admin User',
    'email' => 'admin@test.com',
    'phone' => '+2348099999999',
    'password' => bcrypt('Admin@123'),
    'user_status' => 'approved',
    'email_verified_at' => now(),
]);

// Assign admin role
$adminRole = App\Models\Role::where('name', 'admin')->first();
if (!$adminRole) {
    $adminRole = App\Models\Role::create([
        'name' => 'admin',
        'slug' => 'admin',
        'description' => 'Administrator'
    ]);
}
$admin->roles()->attach($adminRole->id);

echo "Admin created: admin@test.com / Admin@123\n";
exit
```

### **Test 10: Login as Admin**

1. **Logout current user:** http://localhost:8000/logout
2. **Login as admin:**
   - Email: admin@test.com
   - Password: Admin@123

### **Test 11: Review KYC**

1. **Go to:** http://localhost:8000/admin/kyc
2. **Expected:** See list of pending KYC submissions
3. **Click "Review" on Test User's submission**
4. **Expected:** See review form with documents

### **Test 12: Approve KYC**

1. **On review page:**
   - Select "Approve"
   - Enter notes: "All documents verified"
   - Click "Submit"
2. **Expected:** Success message, redirect to list

### **Test 13: Verify Approval**

```cmd
php artisan tinker
```

```php
$user = App\Models\User::where('email', 'test@example.com')->first();
echo "User Status: " . $user->user_status . "\n"; // Should be "approved"

$kyc = $user->kycSubmissions()->latest()->first();
echo "KYC Status: " . $kyc->status . "\n"; // Should be "approved"

exit
```

---

## ✅ **STEP 8: TEST MODULE 3 - FULL DASHBOARD**

### **Test 14: Login as Approved User**

1. **Logout admin:** http://localhost:8000/logout
2. **Login as test user:**
   - Email: test@example.com
   - Password: Test@123

### **Test 15: Access Full Dashboard**

1. **Should redirect to:** http://localhost:8000/owner/dashboard
2. **Expected to see:**
   - ✓ Red gradient background
   - ✓ Welcome message
   - ✓ Stats cards (Properties, Revenue, etc.)
   - ✓ Quick action buttons
   - ✓ NOT locked dashboard

---

## ✅ **STEP 9: TEST WITH EXISTING ACCOUNTS**

### **Test 16: Login as Owner**

```
URL: http://localhost:8000/login
Email: owner@360winestate.com
Password: Owner@123
```

**Expected:** Owner Dashboard (red gradient)

### **Test 17: Login as Investor**

```
URL: http://localhost:8000/login
Email: investor@360winestate.com
Password: Investor@123
```

**Expected:** Investor Dashboard (teal gradient)

### **Test 18: Login as Marketer**

```
URL: http://localhost:8000/login
Email: marketer@360winestate.com
Password: Marketer@123
```

**Expected:** Marketer Dashboard (purple gradient)

---

## 📊 **QUICK VERIFICATION CHECKLIST**

Run these commands to verify everything:

```cmd
php artisan tinker
```

```php
// 1. Check tables
echo "Tables Check:\n";
echo "users: " . (Schema::hasTable('users') ? '✓' : '✗') . "\n";
echo "roles: " . (Schema::hasTable('roles') ? '✓' : '✗') . "\n";
echo "kyc_submissions: " . (Schema::hasTable('kyc_submissions') ? '✓' : '✗') . "\n";
echo "kyc_documents: " . (Schema::hasTable('kyc_documents') ? '✓' : '✗') . "\n";
echo "documents: " . (Schema::hasTable('documents') ? '✓' : '✗') . "\n";
echo "owner_stats: " . (Schema::hasTable('owner_stats') ? '✓' : '✗') . "\n";
echo "investor_stats: " . (Schema::hasTable('investor_stats') ? '✓' : '✗') . "\n";
echo "marketer_stats: " . (Schema::hasTable('marketer_stats') ? '✓' : '✗') . "\n";

// 2. Check users
echo "\nUsers Count: " . App\Models\User::count() . "\n";

// 3. Check roles
echo "Roles Count: " . App\Models\Role::count() . "\n";
echo "Roles: " . App\Models\Role::pluck('name')->implode(', ') . "\n";

// 4. Check test accounts
echo "\nTest Accounts:\n";
$testUsers = ['owner@360winestate.com', 'investor@360winestate.com', 'marketer@360winestate.com'];
foreach ($testUsers as $email) {
    $user = App\Models\User::where('email', $email)->first();
    if ($user) {
        echo "✓ " . $email . " (Status: " . $user->user_status . ")\n";
    } else {
        echo "✗ " . $email . " (NOT FOUND)\n";
    }
}

exit
```

---

## 🎯 **EXPECTED RESULTS SUMMARY**

### **Database:**
- ✓ All 13+ tables created
- ✓ `documents` table exists (FIXED!)
- ✓ Relationships working

### **Module 1:**
- ✓ Registration works
- ✓ Email verification works
- ✓ Login works
- ✓ Membership selection works
- ✓ Locked dashboard displays

### **Module 2:**
- ✓ KYC form displays
- ✓ File uploads work
- ✓ KYC submission saves
- ✓ Admin can review
- ✓ Approval workflow works

### **Module 3:**
- ✓ Owner dashboard (red)
- ✓ Investor dashboard (teal)
- ✓ Marketer dashboard (purple)

---

## 🐛 **IF SOMETHING FAILS**

### **Error: "Route not found"**
```cmd
php artisan route:clear
php artisan route:cache
```

### **Error: "View not found"**
```cmd
php artisan view:clear
```

### **Error: "Class not found"**
```cmd
composer dump-autoload
```

### **Error: "Storage not writable"**
```cmd
php artisan storage:link
```

### **Error: Database connection**
```cmd
# Check .env file
# Make sure XAMPP MySQL is running
php artisan config:clear
```

---

## ✅ **SUCCESS CRITERIA**

You'll know everything is working when:

1. ✓ You can register a new user
2. ✓ You can select membership
3. ✓ You see locked dashboard
4. ✓ You can submit KYC
5. ✓ Admin can approve KYC
6. ✓ Approved user sees full dashboard
7. ✓ All 3 role dashboards work

---

**🎉 DATABASE FIX COMPLETE!**

The `documents` table has been created and migrated successfully. You can now proceed with testing!

**Next Steps:**
1. Run the verification commands above
2. Test the complete user flow
3. Check all 3 dashboards
4. Report any issues found

**Created:** January 28, 2026  
**Status:** ✅ READY TO TEST
