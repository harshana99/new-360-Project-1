# 📚 Enhanced User Model Documentation - 360WinEstate

## 🎯 **OVERVIEW:**

Comprehensive User model with advanced features including:
- Diaspora tracking (country, city, address)
- Two-factor authentication
- Role-based access control
- Multi-address support
- Wallet integration
- Document management
- KYC workflow
- Language & timezone preferences

---

## 📁 **FILES CREATED:**

### **Models:**
1. **`app/Models/UserEnhanced.php`** - Enhanced User model (700+ lines)
2. **`app/Models/Role.php`** - Role management
3. **`app/Models/Address.php`** - Multi-address support
4. **`app/Models/Wallet.php`** - Wallet & transactions
5. **`app/Models/Document.php`** - Document management

### **Migration:**
1. **`database/migrations/2024_01_04_000000_add_enhanced_user_fields.php`**

---

## 🗃️ **DATABASE FIELDS:**

### **User Table Fields:**

```php
// Basic Information
- id (bigint, primary key)
- name (string, 255)
- email (string, unique)
- phone (string, nullable)
- password (string, hashed)

// Diaspora Tracking
- country (string, 100, nullable, indexed)
- city (string, 100, nullable, indexed)
- address (text, nullable)

// Status & Membership
- user_status (enum, indexed)
  * registered
  * membership_selected
  * kyc_submitted
  * under_review
  * approved
  * rejected
- membership_type (enum, nullable)
  * owner
  * investor
  * marketer

// Verification
- email_verified_at (timestamp, nullable)
- phone_verified_at (timestamp, nullable)

// Security & Preferences
- is_active (boolean, default: true, indexed)
- two_factor_enabled (boolean, default: false)
- two_factor_secret (text, nullable, hidden)
- two_factor_recovery_codes (text, nullable, hidden)
- preferred_language (string, 10, default: 'en', indexed)
- timezone (string, 50, default: 'UTC')

// Admin & Role
- is_admin (boolean, default: false)
- role (string, nullable)

// Workflow Timestamps
- membership_selected_at (timestamp, nullable)
- kyc_submitted_at (timestamp, nullable)
- approved_at (timestamp, nullable)
- rejected_at (timestamp, nullable)
- rejection_reason (text, nullable)

// System Timestamps
- created_at (timestamp)
- updated_at (timestamp)
- deleted_at (timestamp, nullable) // Soft deletes
```

---

## 🔗 **RELATIONSHIPS:**

### **User Model Relationships:**

```php
// Many-to-Many
roles()                  // BelongsToMany - User roles via pivot
                        // Pivot: user_roles (user_id, role_id, assigned_at, assigned_by)

// One-to-Many
addresses()             // HasMany - Multiple addresses
kycSubmissions()        // HasMany - All KYC submissions
documents()             // HasMany - All uploaded documents

// One-to-One
wallet()                // HasOne - User wallet
latestKycSubmission()   // HasOne - Latest KYC submission
ownerStats()            // HasOne - Owner statistics
investorStats()         // HasOne - Investor statistics
marketerStats()         // HasOne - Marketer statistics
```

---

## 🎯 **KEY METHODS:**

### **Status Check Methods:**

```php
isApproved()            // Check if user is approved
isRegistered()          // Check if user is in initial state
hasSubmittedKyc()       // Check if KYC submitted
isUnderReview()         // Check if under review
isRejected()            // Check if rejected
isActive()              // Check if account is active
hasVerifiedEmail()      // Check if email verified
hasVerifiedPhone()      // Check if phone verified
hasTwoFactorEnabled()   // Check if 2FA enabled
```

### **Role & Permission Methods:**

```php
isAdmin()               // Check if user is admin
isOwner()               // Check if user is property owner
isInvestor()            // Check if user is investor
isMarketer()            // Check if user is marketer
hasRole($role)          // Check if user has specific role(s)
canAccess($feature)     // Check if user can access feature
assignRole($role)       // Assign a role to user
removeRole($role)       // Remove a role from user
```

### **Utility Methods:**

```php
getOrCreateStats()      // Get or create stats based on membership
getStatusLabel()        // Get human-readable status
getStatusBadgeClass()   // Get Bootstrap badge class
getMembershipLabel()    // Get membership type label
getInitials()           // Get user initials for avatar
isDiaspora($country)    // Check if user is from diaspora
getLocalTime()          // Get user's local time
markPhoneAsVerified()   // Mark phone as verified
activate()              // Activate user account
deactivate()            // Deactivate user account
enableTwoFactor()       // Enable 2FA
disableTwoFactor()      // Disable 2FA
```

### **Accessors:**

```php
full_name               // Get full name (same as name)
full_address            // Get complete address string
```

### **Mutators:**

```php
setPasswordAttribute()  // Auto-hash password
setEmailAttribute()     // Convert email to lowercase
setPhoneAttribute()     // Clean phone number
```

---

## 🔐 **SECURITY FEATURES:**

### **Password Hashing:**
```php
// Automatic password hashing
$user->password = 'plain-text-password';
// Stored as: $2y$10$... (bcrypt hash)
```

### **Hidden Fields:**
```php
// Never exposed in JSON/arrays
- password
- remember_token
- two_factor_secret
- two_factor_recovery_codes
```

### **Two-Factor Authentication:**
```php
// Enable 2FA
$user->enableTwoFactor();

// Disable 2FA
$user->disableTwoFactor();

// Check status
if ($user->hasTwoFactorEnabled()) {
    // Require 2FA code
}
```

---

## 📊 **USAGE EXAMPLES:**

### **Create User:**
```php
$user = User::create([
    'name' => 'John Doe',
    'email' => 'john@example.com',
    'phone' => '+1234567890',
    'password' => 'SecurePassword123',
    'country' => 'India',
    'city' => 'Mumbai',
    'address' => '123 Main Street',
    'preferred_language' => 'en',
    'timezone' => 'Asia/Kolkata',
]);
```

### **Check Status:**
```php
if ($user->isApproved()) {
    // Allow access to dashboard
}

if ($user->hasSubmittedKyc()) {
    // Show KYC status
}
```

### **Role Management:**
```php
// Assign role
$user->assignRole('admin');

// Check role
if ($user->hasRole('admin')) {
    // Admin access
}

// Check feature access
if ($user->canAccess('properties')) {
    // Show properties
}
```

### **Diaspora Tracking:**
```php
// Check if user is from diaspora
if ($user->isDiaspora('India')) {
    // User is outside India
    $localTime = $user->getLocalTime();
}
```

### **Wallet Operations:**
```php
// Get wallet
$wallet = $user->wallet;

// Credit funds
$wallet->credit(1000, 'Deposit');

// Debit funds
$wallet->debit(500, 'Purchase');

// Transfer
$wallet->transfer($otherWallet, 200, 'Payment');
```

### **Address Management:**
```php
// Add address
$user->addresses()->create([
    'type' => Address::TYPE_BILLING,
    'address_line_1' => '123 Main St',
    'city' => 'Mumbai',
    'state' => 'Maharashtra',
    'country' => 'India',
    'postal_code' => '400001',
    'is_default' => true,
]);

// Get default address
$defaultAddress = $user->addresses()
    ->where('is_default', true)
    ->first();
```

### **Document Upload:**
```php
// Upload document
$document = $user->documents()->create([
    'type' => Document::TYPE_ID_PROOF,
    'title' => 'Passport',
    'file_name' => 'passport.pdf',
    'file_path' => 'documents/passport.pdf',
    'file_type' => 'application/pdf',
    'file_size' => 1024000,
]);

// Verify document
$document->verify($adminId);

// Reject document
$document->reject('Document not clear', $adminId);
```

---

## 🎨 **SUPPORTING MODELS:**

### **1. Role Model:**
```php
// Fields:
- id, name, slug, description
- permissions (JSON array)
- is_active

// Methods:
- hasPermission($permission)
- addPermission($permission)
- removePermission($permission)
```

### **2. Address Model:**
```php
// Fields:
- id, user_id, type
- address_line_1, address_line_2
- city, state, country, postal_code
- landmark, is_default

// Types:
- billing, shipping, property, office, other

// Methods:
- setAsDefault()
- getFullAddressAttribute()
```

### **3. Wallet Model:**
```php
// Fields:
- id, user_id
- balance, locked_balance
- currency, is_active

// Methods:
- credit($amount, $description)
- debit($amount, $description)
- lock($amount)
- unlock($amount)
- transfer($toWallet, $amount)
- getAvailableBalanceAttribute()
```

### **4. Document Model:**
```php
// Fields:
- id, user_id, type, title
- file_name, file_path, file_type, file_size
- status, verified_by, verified_at
- rejection_reason

// Types:
- id_proof, address_proof, bank_statement
- contract, property_deed, other

// Methods:
- verify($verifiedBy)
- reject($reason, $rejectedBy)
- deleteFile()
- isVerified(), isPending(), isRejected()
```

---

## 🔄 **WORKFLOW EXAMPLE:**

```php
// 1. User Registration
$user = User::create([...]);
// Status: registered

// 2. Email Verification
$user->markEmailAsVerified();

// 3. Membership Selection
$user->update([
    'membership_type' => User::MEMBERSHIP_OWNER,
    'user_status' => User::STATUS_MEMBERSHIP_SELECTED,
    'membership_selected_at' => now(),
]);

// 4. KYC Submission
$kyc = $user->kycSubmissions()->create([...]);
$user->update([
    'user_status' => User::STATUS_KYC_SUBMITTED,
    'kyc_submitted_at' => now(),
]);

// 5. Admin Review
$user->update(['user_status' => User::STATUS_UNDER_REVIEW]);

// 6. Approval
$user->update([
    'user_status' => User::STATUS_APPROVED,
    'approved_at' => now(),
]);

// 7. Access Dashboard
if ($user->canAccess('dashboard')) {
    $stats = $user->getOrCreateStats();
    // Show role-specific dashboard
}
```

---

## 📊 **CONSTANTS:**

### **Membership Types:**
```php
User::MEMBERSHIP_OWNER      // 'owner'
User::MEMBERSHIP_INVESTOR   // 'investor'
User::MEMBERSHIP_MARKETER   // 'marketer'
```

### **User Status:**
```php
User::STATUS_REGISTERED             // 'registered'
User::STATUS_MEMBERSHIP_SELECTED    // 'membership_selected'
User::STATUS_KYC_SUBMITTED          // 'kyc_submitted'
User::STATUS_UNDER_REVIEW           // 'under_review'
User::STATUS_APPROVED               // 'approved'
User::STATUS_REJECTED               // 'rejected'
```

### **Defaults:**
```php
User::DEFAULT_LANGUAGE  // 'en'
User::DEFAULT_TIMEZONE  // 'UTC'
```

---

## 🚀 **MIGRATION GUIDE:**

### **Run Migration:**
```bash
php artisan migrate
```

### **If You Want to Use Enhanced Model:**
1. Backup current `app/Models/User.php`
2. Copy `UserEnhanced.php` to `User.php`
3. Run migration
4. Update seeders if needed

---

## ✅ **FEATURES CHECKLIST:**

- [x] Basic user fields (name, email, phone, password)
- [x] Diaspora tracking (country, city, address)
- [x] User status enum (6 states)
- [x] Email & phone verification
- [x] Two-factor authentication
- [x] Language & timezone preferences
- [x] Role-based access control
- [x] Multi-address support
- [x] Wallet integration
- [x] Document management
- [x] KYC relationships
- [x] Password auto-hashing
- [x] Soft deletes
- [x] Comprehensive methods
- [x] Full documentation

---

## 📞 **SUPPORT:**

**Files:**
- `app/Models/UserEnhanced.php` - Main user model
- `app/Models/Role.php` - Role management
- `app/Models/Address.php` - Address management
- `app/Models/Wallet.php` - Wallet management
- `app/Models/Document.php` - Document management

**Total Lines:** ~2,000 lines of code
**Total Models:** 5 models
**Total Methods:** 50+ methods
**Total Relationships:** 10 relationships

---

## 🎊 **READY TO USE!**

All models are created and ready to integrate into your 360WinEstate platform!
