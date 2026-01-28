# MODULE 11 - COMPLETE TESTING GUIDE

## 🧪 How to Test All Functions

---

## 📋 PRE-TESTING CHECKLIST

Before testing, ensure:

```bash
# 1. Database is running
# Check XAMPP - MySQL should be running

# 2. Clear all caches
php artisan optimize:clear

# 3. Check database connection
php artisan migrate:status

# 4. Start development server
php artisan serve
```

**Server should start at:** `http://localhost:8000` or `http://127.0.0.1:8000`

---

## 👤 PART 1: USER FEATURES TESTING

### **STEP 1: Create Test User**

**Option A: Via Registration (if you have registration page)**
1. Go to registration page
2. Fill in details
3. Register

**Option B: Via Database Seeder**
```bash
php artisan tinker
```

Then run:
```php
$user = \App\Models\User::create([
    'name' => 'Test User',
    'email' => 'testuser@example.com',
    'password' => bcrypt('Password@123'),
    'phone' => '1234567890',
    'membership_type' => 'investor',
    'status' => 'registered',
]);

echo "User created! ID: " . $user->id;
exit;
```

**Option C: Manual Database Insert**
- Open phpMyAdmin
- Go to `users` table
- Click "Insert"
- Fill in the data

---

### **STEP 2: Login as User**

1. Go to: `http://localhost:8000/login`
2. Email: `testuser@example.com`
3. Password: `Password@123`
4. Click "Login"

**Expected:** You should be logged in and redirected to dashboard

---

### **STEP 3: Test User Profile**

#### **3.1 View Profile**
1. Go to: `http://localhost:8000/user/profile`
2. **Check:**
   - ✅ Your name is displayed
   - ✅ Email is shown
   - ✅ Status badge is visible
   - ✅ "Edit Profile" button exists
   - ✅ "Change Password" button exists
   - ✅ Recent activity section shows

**Screenshot:** Take a screenshot of your profile

---

#### **3.2 Edit Profile**
1. Click "Edit Profile" button
2. **URL should be:** `http://localhost:8000/user/profile/edit`
3. **Test Changes:**
   - Change name to "Test User Updated"
   - Add phone: "9876543210"
   - Add address: "123 Test Street"
   - Add city: "Test City"
   - Add state: "Test State"
   - Add country: "Test Country"
   - Add postal code: "12345"
   - Add bio: "This is a test bio"
4. Click "Update Profile"

**Expected:**
- ✅ Success message appears
- ✅ Redirected to profile page
- ✅ Changes are visible
- ✅ Activity log shows "Profile Update"

---

#### **3.3 Change Password**
1. Go to: `http://localhost:8000/user/profile/change-password`
2. **Test the Password Strength Meter:**
   - Type "weak" → Should show RED (Weak)
   - Type "Medium123" → Should show YELLOW (Medium)
   - Type "Strong@Pass123" → Should show GREEN (Strong)
3. **Fill the form:**
   - Current Password: `Password@123`
   - New Password: `NewPassword@123`
   - Confirm Password: `NewPassword@123`
4. **Watch the validation:**
   - ✅ All 5 requirements should turn green
   - ✅ Strength meter should show "Strong"
   - ✅ Passwords match indicator should show
5. Click "Change Password"

**Expected:**
- ✅ Success message
- ✅ Redirected to profile
- ✅ Activity log shows "Password Changed"

**Important:** Your new password is now `NewPassword@123`

---

#### **3.4 Test KYC Status**
1. Go to: `http://localhost:8000/user/kyc-status`

**Expected (if no KYC submitted):**
- ✅ Redirected to KYC submission page
- ✅ Message: "You have not submitted KYC yet"

**If you have KYC submitted:**
- ✅ Status badge shows (Pending/Approved/Rejected)
- ✅ Submission details visible
- ✅ Documents can be viewed
- ✅ Download button works

---

#### **3.5 Test Activity Log**
1. Go to: `http://localhost:8000/user/account-activity`
2. **Check:**
   - ✅ Timeline shows all activities
   - ✅ Icons are displayed
   - ✅ Timestamps are correct
   - ✅ "View Details" expands metadata
3. **Test Filters:**
   - Select activity type: "Profile Update"
   - Click "Filter"
   - ✅ Only profile updates show
4. **Test Date Range:**
   - Set "From Date": Today's date
   - Click "Filter"
   - ✅ Only today's activities show
5. **Clear Filters:**
   - Click X button
   - ✅ All activities show again

---

## 👨‍💼 PART 2: ADMIN FEATURES TESTING

### **STEP 1: Create Test Admin**

**Via Tinker:**
```bash
php artisan tinker
```

```php
// Create admin user first
$adminUser = \App\Models\User::create([
    'name' => 'Test Admin',
    'email' => 'admin@example.com',
    'password' => bcrypt('Admin@123'),
    'phone' => '1111111111',
    'membership_type' => 'investor',
    'status' => 'approved',
]);

// Create admin record
$admin = \App\Models\Admin::create([
    'user_id' => $adminUser->id,
    'admin_role' => 'super_admin',
    'is_active' => true,
]);

echo "Admin created! User ID: " . $adminUser->id . ", Admin ID: " . $admin->id;
exit;
```

---

### **STEP 2: Login as Admin**

1. **Logout from user account** (if logged in)
2. Go to: `http://localhost:8000/admin/login`
3. Email: `admin@example.com`
4. Password: `Admin@123`
5. Click "Login"

**Expected:** Redirected to admin dashboard

---

### **STEP 3: Test User Management**

#### **3.1 View User List**
1. Go to: `http://localhost:8000/admin/user-management`
2. **Check:**
   - ✅ Statistics cards show (Total, Approved, Pending, Suspended)
   - ✅ User table displays
   - ✅ Your test user is visible
   - ✅ Avatar circles show
   - ✅ Status badges are colored correctly
   - ✅ Action buttons (View, Edit) exist

---

#### **3.2 Test Search & Filters**
1. **Test Search:**
   - Type "Test User" in search box
   - Click "Filter"
   - ✅ Only matching users show
2. **Test Membership Filter:**
   - Select "Investor"
   - Click "Filter"
   - ✅ Only investors show
3. **Test Status Filter:**
   - Select "Registered"
   - Click "Filter"
   - ✅ Only registered users show
4. **Clear Filters:**
   - Click X button
   - ✅ All users show

---

#### **3.3 View User Details**
1. Click the "eye" icon on your test user
2. **URL should be:** `http://localhost:8000/admin/user-management/{id}`
3. **Check:**
   - ✅ Large avatar shows
   - ✅ User name and email displayed
   - ✅ Status badge visible
   - ✅ Quick stats sidebar shows
   - ✅ Personal information card displays
   - ✅ Recent activity timeline shows
   - ✅ "Edit User" button exists
   - ✅ "Suspend" button exists (if not suspended)

---

#### **3.4 Edit User**
1. Click "Edit User" button
2. **URL should be:** `http://localhost:8000/admin/user-management/{id}/edit`
3. **Make changes:**
   - Change name to "Test User - Admin Edited"
   - Change membership type to "Owner"
   - Change status to "Approved"
4. Click "Update User"

**Expected:**
- ✅ Success message
- ✅ Redirected to user details
- ✅ Changes are visible
- ✅ Activity log shows "User Updated by Admin"

---

#### **3.5 Suspend User**
1. On user details page, click "Suspend" button
2. **Modal should open**
3. Enter reason: "Testing suspension feature"
4. Click "Suspend Account"

**Expected:**
- ✅ Success message
- ✅ Status changes to "Suspended"
- ✅ "Suspend" button changes to "Activate"
- ✅ Activity log shows "Account Suspended"

---

#### **3.6 Activate User**
1. Click "Activate" button
2. Confirm activation

**Expected:**
- ✅ Success message
- ✅ Status changes back to previous status
- ✅ "Activate" button changes to "Suspend"
- ✅ Activity log shows "Account Activated"

---

#### **3.7 Export Users**
1. Go back to user list: `http://localhost:8000/admin/user-management`
2. Apply some filters (optional)
3. Click "Export to CSV" button

**Expected:**
- ✅ CSV file downloads
- ✅ Filename includes timestamp
- ✅ File contains user data
- ✅ Open in Excel/Sheets to verify

---

### **STEP 4: Test KYC Review** (if you have KYC submissions)

#### **4.1 Create Test KYC Submission**

**Via Tinker:**
```bash
php artisan tinker
```

```php
$user = \App\Models\User::where('email', 'testuser@example.com')->first();

$kyc = \App\Models\KycSubmission::create([
    'user_id' => $user->id,
    'id_type' => 'passport',
    'id_number' => 'P123456789',
    'full_name' => 'Test User',
    'date_of_birth' => '1990-01-01',
    'address' => '123 Test Street',
    'city' => 'Test City',
    'state' => 'Test State',
    'country' => 'Test Country',
    'postal_code' => '12345',
    'id_image_path' => 'kyc/sample.jpg',
    'address_proof_path' => 'kyc/sample.jpg',
    'status' => 'submitted',
    'submitted_at' => now(),
]);

echo "KYC created! ID: " . $kyc->id;
exit;
```

---

#### **4.2 View KYC Details**
1. Go to user details page
2. Click "View KYC Documents"
3. **URL should be:** `http://localhost:8000/admin/user-management/{id}/kyc`
4. **Check:**
   - ✅ KYC status badge shows
   - ✅ Submission details visible
   - ✅ Personal information displays
   - ✅ Address information shows
   - ✅ Document previews visible (if images exist)
   - ✅ "Approve" and "Reject" buttons exist

---

## 🔍 PART 3: TESTING CHECKLIST

### **User Features:**
- [ ] Profile viewing works
- [ ] Profile editing works
- [ ] Password change works
- [ ] Password strength meter works
- [ ] Password visibility toggles work
- [ ] KYC status viewing works
- [ ] Activity log displays
- [ ] Activity filters work
- [ ] Activity date range works
- [ ] Success messages show
- [ ] Error messages show
- [ ] Validation works

### **Admin Features:**
- [ ] User list displays
- [ ] Statistics cards show correct numbers
- [ ] Search works
- [ ] Membership filter works
- [ ] Status filter works
- [ ] Sorting works
- [ ] Pagination works
- [ ] User details page works
- [ ] Edit user works
- [ ] Suspend user works
- [ ] Activate user works
- [ ] CSV export works
- [ ] KYC review page works

### **UI/UX:**
- [ ] Mobile responsive
- [ ] Buttons work
- [ ] Modals open/close
- [ ] Forms validate
- [ ] Icons display
- [ ] Colors are correct (Navy/Gold)
- [ ] Badges show correct colors
- [ ] Tables are readable
- [ ] Navigation works

### **Security:**
- [ ] Authentication required
- [ ] Unauthorized access blocked
- [ ] CSRF tokens present
- [ ] Password hashing works
- [ ] Activity logging works
- [ ] Admin attribution works

---

## 🐛 COMMON ISSUES & SOLUTIONS

### **Issue 1: Routes not found (404)**
**Solution:**
```bash
php artisan route:clear
php artisan optimize:clear
```

### **Issue 2: Views not found**
**Solution:**
```bash
php artisan view:clear
php artisan config:clear
```

### **Issue 3: Database errors**
**Solution:**
```bash
php artisan migrate:fresh
# Then recreate test users
```

### **Issue 4: Login not working**
**Solution:**
- Check `.env` file for correct database credentials
- Verify user exists in database
- Check password is hashed correctly

### **Issue 5: Images not showing**
**Solution:**
```bash
php artisan storage:link
```

### **Issue 6: Middleware errors**
**Solution:**
- Check if user is logged in
- Verify admin role is correct
- Clear cache: `php artisan optimize:clear`

---

## 📊 TESTING RESULTS TEMPLATE

Use this to track your testing:

```
MODULE 11 TESTING RESULTS
Date: _______________
Tester: _______________

USER FEATURES:
✅ Profile View: PASS / FAIL
✅ Profile Edit: PASS / FAIL
✅ Password Change: PASS / FAIL
✅ KYC Status: PASS / FAIL
✅ Activity Log: PASS / FAIL

ADMIN FEATURES:
✅ User List: PASS / FAIL
✅ User Details: PASS / FAIL
✅ User Edit: PASS / FAIL
✅ User Suspend: PASS / FAIL
✅ User Activate: PASS / FAIL
✅ CSV Export: PASS / FAIL
✅ KYC Review: PASS / FAIL

UI/UX:
✅ Mobile Responsive: PASS / FAIL
✅ Colors Correct: PASS / FAIL
✅ Icons Display: PASS / FAIL

BUGS FOUND:
1. _______________
2. _______________
3. _______________

NOTES:
_______________
_______________
```

---

## 🎯 QUICK TEST COMMANDS

**Create test user:**
```bash
php artisan tinker --execute="\\App\\Models\\User::create(['name'=>'Test','email'=>'test@test.com','password'=>bcrypt('Test@123'),'phone'=>'123','membership_type'=>'investor','status'=>'registered']);"
```

**Create test admin:**
```bash
php artisan tinker --execute="\$u=\\App\\Models\\User::create(['name'=>'Admin','email'=>'admin@test.com','password'=>bcrypt('Admin@123'),'phone'=>'111','membership_type'=>'investor','status'=>'approved']);\\App\\Models\\Admin::create(['user_id'=>\$u->id,'admin_role'=>'super_admin','is_active'=>true]);"
```

**Clear everything:**
```bash
php artisan optimize:clear && php artisan config:clear && php artisan route:clear && php artisan view:clear
```

---

## ✅ FINAL VERIFICATION

After testing everything, verify:

1. ✅ All user routes work
2. ✅ All admin routes work
3. ✅ All forms validate correctly
4. ✅ All success messages show
5. ✅ All error messages show
6. ✅ All activity logs correctly
7. ✅ All security checks work
8. ✅ Mobile responsive works
9. ✅ No console errors
10. ✅ No PHP errors

---

**Happy Testing! 🧪**

If you find any bugs, document them and we can fix them together!
