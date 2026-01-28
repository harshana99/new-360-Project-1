# 🚀 360WinEstate - Quick Start Guide

## ⚡ **GET STARTED IN 5 MINUTES!**

---

## 📋 **PREREQUISITES:**

- ✅ PHP 8.1+ installed
- ✅ Composer installed
- ✅ MySQL/MariaDB running
- ✅ XAMPP/WAMP/MAMP (or similar)
- ✅ Node.js & NPM (optional, for assets)

---

## 🔧 **SETUP STEPS:**

### **Step 1: Database Setup**

```sql
-- Create database
CREATE DATABASE 360winestate;

-- Or use phpMyAdmin
```

### **Step 2: Environment Configuration**

```bash
# Copy .env.example to .env (if not already done)
cp .env.example .env

# Update .env file with your database credentials:
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=360winestate
DB_USERNAME=root
DB_PASSWORD=your_password
```

### **Step 3: Install Dependencies**

```bash
# Install PHP dependencies
composer install

# Generate application key
php artisan key:generate
```

### **Step 4: Run Migrations**

```bash
# Run all migrations
php artisan migrate

# Or fresh migration (if needed)
php artisan migrate:fresh
```

### **Step 5: Seed Database**

```bash
# Seed roles, permissions, and test users
php artisan db:seed --class=RoleSeeder
php artisan db:seed --class=PermissionSeeder
php artisan db:seed --class=UserSeeder

# Or seed all at once
php artisan db:seed
```

### **Step 6: Create Storage Link**

```bash
# Create symbolic link for file storage
php artisan storage:link
```

### **Step 7: Start Development Server**

```bash
# Start Laravel development server
php artisan serve

# Server will start at: http://localhost:8000
```

---

## 🧪 **TEST THE PLATFORM:**

### **Test Accounts (from UserSeeder):**

#### **1. Owner Account**
```
URL: http://localhost:8000/login
Email: owner@360winestate.com
Password: Owner@123
Role: Owner
Status: Approved
```
**Expected:** Redirects to Owner Dashboard (red gradient)

#### **2. Investor Account**
```
URL: http://localhost:8000/login
Email: investor@360winestate.com
Password: Investor@123
Role: Investor
Status: Approved
```
**Expected:** Redirects to Investor Dashboard (teal gradient)

#### **3. Marketer Account**
```
URL: http://localhost:8000/login
Email: marketer@360winestate.com
Password: Marketer@123
Role: Marketer
Status: Approved
```
**Expected:** Redirects to Marketer Dashboard (purple gradient)

#### **4. Membership Selected (KYC Pending)**
```
URL: http://localhost:8000/login
Email: selected@360winestate.com
Password: Selected@123
Role: Owner
Status: membership_selected
```
**Expected:** Shows locked dashboard with "Complete KYC" button

#### **5. KYC Submitted (Under Review)**
```
URL: http://localhost:8000/login
Email: submitted@360winestate.com
Password: Submitted@123
Role: Investor
Status: kyc_submitted
```
**Expected:** Shows locked dashboard with "Under Review" message

#### **6. Rejected KYC**
```
URL: http://localhost:8000/login
Email: rejected@360winestate.com
Password: Rejected@123
Role: Marketer
Status: rejected
```
**Expected:** Shows locked dashboard with "Resubmit KYC" button

---

## 🎯 **COMPLETE USER FLOW TEST:**

### **Test 1: New User Registration**

1. **Visit:** `http://localhost:8000/register`
2. **Fill form:**
   - Name: Test User
   - Email: test@example.com
   - Phone: +234 123 456 7890
   - Password: Test@123
   - Confirm Password: Test@123
3. **Submit** → Should redirect to email verification page
4. **Check database:** `users` table should have new user with `email_verified_at = NULL`

### **Test 2: Email Verification (Manual)**

```bash
# In Tinker
php artisan tinker

# Verify email manually
$user = User::where('email', 'test@example.com')->first();
$user->email_verified_at = now();
$user->save();
exit
```

### **Test 3: Membership Selection**

1. **Login** with test@example.com
2. **Should redirect to:** `/membership/select`
3. **Select membership:** Owner / Investor / Marketer
4. **Submit** → Should redirect to locked dashboard
5. **Check database:** `users.membership_type` should be set

### **Test 4: KYC Submission**

1. **Click:** "Complete Your KYC" button
2. **Should redirect to:** `/kyc/create`
3. **Fill form:**
   - ID Type: Passport
   - ID Number: A12345678
   - Upload ID Front (any image)
   - Upload ID Back (any image)
   - Upload Proof of Address (any PDF/image)
   - Upload Selfie (any image)
   - Full Name: Test User
   - Date of Birth: 1990-01-01
   - Nationality: Nigerian
   - Address: 123 Test Street
   - City: Lagos
   - State: Lagos
   - Postal Code: 100001
   - Country: Nigeria
4. **Submit** → Should redirect to `/kyc/status`
5. **Check database:** `kyc_submissions` table should have new entry

### **Test 5: Admin KYC Review**

1. **Create admin user** (or use existing)
2. **Visit:** `/admin/kyc`
3. **See pending KYC submissions**
4. **Click "Review"** on a submission
5. **Approve/Reject** with notes
6. **Check database:** `kyc_submissions.status` should be updated
7. **Check user status:** `users.user_status` should be 'approved'

### **Test 6: Approved Dashboard Access**

1. **Logout**
2. **Login** with the approved user
3. **Should redirect to:** Role-specific dashboard
   - Owner → `/owner/dashboard`
   - Investor → `/investor/dashboard`
   - Marketer → `/marketer/dashboard`
4. **See stats cards** with data
5. **See quick actions** buttons
6. **Test responsive design** (resize browser)

---

## 🐛 **TROUBLESHOOTING:**

### **Issue: "Class not found" error**

```bash
# Clear and regenerate autoload
composer dump-autoload
```

### **Issue: "No application encryption key"**

```bash
# Generate new key
php artisan key:generate
```

### **Issue: "SQLSTATE[HY000] [1045] Access denied"**

```bash
# Check .env database credentials
# Make sure MySQL is running
# Test connection:
php artisan tinker
DB::connection()->getPdo();
```

### **Issue: "Storage not writable"**

```bash
# Fix permissions (Linux/Mac)
chmod -R 775 storage bootstrap/cache

# Windows: Right-click → Properties → Security → Give full control
```

### **Issue: "File upload fails"**

```bash
# Create storage link
php artisan storage:link

# Check php.ini upload limits
upload_max_filesize = 10M
post_max_size = 10M
```

### **Issue: "Route not found"**

```bash
# Clear route cache
php artisan route:clear
php artisan route:cache

# List all routes
php artisan route:list
```

### **Issue: "View not found"**

```bash
# Clear view cache
php artisan view:clear

# Check file exists in resources/views/
```

---

## 📊 **VERIFY INSTALLATION:**

### **Check Database Tables:**

```sql
-- Should see these tables:
SHOW TABLES;

-- Expected tables:
users
roles
permissions
role_user
permission_role
kyc_submissions
kyc_documents
owner_stats
investor_stats
marketer_stats
properties
wallets
documents
```

### **Check Seeders:**

```bash
# Check roles
php artisan tinker
Role::all();
# Should see: owner, investor, marketer

# Check permissions
Permission::count();
# Should see multiple permissions

# Check test users
User::count();
# Should see 6+ users
```

### **Check Routes:**

```bash
# List all routes
php artisan route:list

# Should see:
# GET /register
# POST /register
# GET /login
# POST /login
# GET /dashboard
# GET /kyc/create
# POST /kyc/store
# GET /owner/dashboard
# GET /investor/dashboard
# GET /marketer/dashboard
# etc.
```

---

## 🎨 **CUSTOMIZATION:**

### **Change Colors:**

**Module 1 & 2 (Auth & KYC):**
Edit inline styles in Blade files:
- Navy: `#0F1A3C`
- Gold: `#E4B400`

**Module 3 (Dashboards):**
Edit `<style>` sections in:
- `resources/views/dashboard/owner.blade.php`
- `resources/views/dashboard/investor.blade.php`
- `resources/views/dashboard/marketer.blade.php`

### **Change Logo:**

Add logo image to `public/images/logo.png`

Update navbar in:
- `resources/views/auth/*.blade.php`
- `resources/views/dashboard/*.blade.php`

### **Change Email Templates:**

Create in `resources/views/emails/`:
- `kyc_submitted.blade.php`
- `kyc_approved.blade.php`
- `kyc_rejected.blade.php`

---

## 📈 **NEXT STEPS:**

### **1. Add Sample Data:**

```bash
# Create sample properties, investments, referrals
php artisan tinker

# Create sample property
$user = User::find(1);
$property = Property::create([
    'user_id' => $user->id,
    'title' => 'Lekki Heights',
    'location' => 'Lagos',
    'value' => 50000000,
    'status' => 'active'
]);

# Update owner stats
$stats = $user->ownerStats;
$stats->properties_count = 1;
$stats->total_property_value = 50000000;
$stats->save();
```

### **2. Configure Email:**

```bash
# Update .env
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your_username
MAIL_PASSWORD=your_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@360winestate.com
MAIL_FROM_NAME="360WinEstate"
```

### **3. Add Charts:**

```bash
# Install Chart.js via CDN in dashboard views
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
```

### **4. Deploy to Production:**

```bash
# Optimize for production
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Set environment
APP_ENV=production
APP_DEBUG=false
```

---

## ✅ **CHECKLIST:**

- [ ] Database created
- [ ] .env configured
- [ ] Dependencies installed
- [ ] Migrations run
- [ ] Seeders run
- [ ] Storage link created
- [ ] Server started
- [ ] Test accounts work
- [ ] Registration works
- [ ] Login works
- [ ] Membership selection works
- [ ] KYC submission works
- [ ] Dashboards load
- [ ] Stats display correctly
- [ ] File uploads work
- [ ] Email configured (optional)

---

## 🎊 **SUCCESS!**

If all tests pass, you now have a fully functional 360WinEstate platform!

**Access your platform at:** `http://localhost:8000`

**Login with:**
- Owner: owner@360winestate.com / Owner@123
- Investor: investor@360winestate.com / Investor@123
- Marketer: marketer@360winestate.com / Marketer@123

---

## 📞 **NEED HELP?**

**Documentation:**
- `MODULE_1_COMPLETE.md` - Authentication
- `MODULE_2_STATUS.md` - KYC System
- `MODULE_3_COMPLETE.md` - Dashboards
- `PLATFORM_ARCHITECTURE.md` - Complete overview

**Logs:**
- `storage/logs/laravel.log` - Application logs
- Browser Console - Frontend errors

**Database:**
- phpMyAdmin: `http://localhost/phpmyadmin`
- Tinker: `php artisan tinker`

---

**Created:** January 28, 2026  
**Version:** 1.0.0  
**Status:** ✅ READY TO USE

**🚀 Happy Building! 🎉**
