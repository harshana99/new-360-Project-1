# 360WinEstate - Testing Guide

## 🧪 Complete Testing Guide for Module 1

This guide will help you thoroughly test all features of the authentication system.

---

## 🚀 Quick Setup for Testing

### 1. Seed Test Data

```bash
php artisan migrate:fresh
php artisan db:seed --class=UserSeeder
```

This creates 10 test users with different statuses.

### 2. Test Accounts

| Email | Password | Type | Status | Purpose |
|-------|----------|------|--------|---------|
| owner@360winestate.com | Owner@123 | Owner | Approved | Test full dashboard (Owner) |
| investor@360winestate.com | Investor@123 | Investor | Approved | Test full dashboard (Investor) |
| marketer@360winestate.com | Marketer@123 | Marketer | Approved | Test full dashboard (Marketer) |
| pending@360winestate.com | Pending@123 | Investor | Under Review | Test locked dashboard |
| kyc@360winestate.com | Kyc@123 | Owner | KYC Submitted | Test KYC submitted state |
| selected@360winestate.com | Selected@123 | Marketer | Membership Selected | Test membership selected state |
| rejected@360winestate.com | Rejected@123 | Investor | Rejected | Test rejection message |
| new@360winestate.com | New@123 | None | Registered | Test email verification |
| admin@360winestate.com | Admin@123 | Owner | Approved | Admin testing |
| test@360winestate.com | Test@123 | Investor | Approved | General testing |

---

## 📋 Test Cases

### Test 1: User Registration

**Objective:** Verify registration process works correctly

**Steps:**
1. Navigate to `/register`
2. Fill in the form:
   - Name: `John Doe`
   - Email: `john.doe@example.com`
   - Phone: `+1234567890`
   - Password: `Test@123`
   - Confirm Password: `Test@123`
3. Click "Create Account"

**Expected Results:**
- ✅ User is created in database
- ✅ User is automatically logged in
- ✅ Redirected to email verification page
- ✅ Email verification sent
- ✅ Success message displayed

**Validation Tests:**
- Try weak password → Should show error
- Try duplicate email → Should show error
- Try invalid email → Should show error
- Try short name → Should show error

---

### Test 2: Email Verification

**Objective:** Verify email verification works

**Steps:**
1. Login as `new@360winestate.com` / `New@123`
2. You should see email verification notice
3. Click "Resend Verification Email"
4. Check email inbox (Mailtrap)
5. Click verification link

**Expected Results:**
- ✅ Redirected to verification notice if not verified
- ✅ Resend button works (rate limited)
- ✅ Verification link works
- ✅ After verification, redirected to membership selection
- ✅ Success message displayed

---

### Test 3: Login System

**Objective:** Verify login works correctly

**Steps:**
1. Logout if logged in
2. Navigate to `/login`
3. Enter credentials:
   - Email: `test@360winestate.com`
   - Password: `Test@123`
4. Check "Remember Me"
5. Click "Login"

**Expected Results:**
- ✅ User is logged in
- ✅ Session created
- ✅ Redirected to appropriate page based on status
- ✅ Welcome message displayed

**Validation Tests:**
- Wrong password → Should show error
- Non-existent email → Should show error
- Unverified email → Should redirect to verification

---

### Test 4: Membership Selection

**Objective:** Verify membership selection works

**Steps:**
1. Login as `selected@360winestate.com` / `Selected@123`
2. You should be on membership selection page
3. Try selecting each membership type:
   - Property Owner
   - Investor
   - Marketer

**Expected Results:**
- ✅ Three cards displayed with details
- ✅ Clicking any card submits selection
- ✅ Status updated to 'membership_selected'
- ✅ Redirected to locked dashboard
- ✅ Cannot select membership again

**Test Each Type:**
- Select Owner → Should update correctly
- Select Investor → Should update correctly
- Select Marketer → Should update correctly

---

### Test 5: Locked Dashboard (Pending Users)

**Objective:** Verify locked dashboard displays correctly

**Test 5a: Membership Selected State**
1. Login as `selected@360winestate.com` / `Selected@123`

**Expected Results:**
- ✅ Shows "Complete Your KYC" message
- ✅ Shows selected membership type
- ✅ Shows "Start KYC Verification" button
- ✅ Shows account information
- ✅ Shows next steps

**Test 5b: KYC Submitted State**
1. Login as `kyc@360winestate.com` / `Kyc@123`

**Expected Results:**
- ✅ Shows "KYC Submitted" message
- ✅ Shows pending review message
- ✅ Shows account information

**Test 5c: Under Review State**
1. Login as `pending@360winestate.com` / `Pending@123`

**Expected Results:**
- ✅ Shows "Under Review" message
- ✅ Shows hourglass icon
- ✅ Shows 24-48 hours message
- ✅ Shows account information

**Test 5d: Rejected State**
1. Login as `rejected@360winestate.com` / `Rejected@123`

**Expected Results:**
- ✅ Shows "Account Rejected" message
- ✅ Shows rejection reason
- ✅ Shows red X icon
- ✅ Shows contact support message

---

### Test 6: Full Dashboard (Approved Users)

**Objective:** Verify full dashboard for approved users

**Test 6a: Property Owner Dashboard**
1. Login as `owner@360winestate.com` / `Owner@123`

**Expected Results:**
- ✅ Shows welcome message with name
- ✅ Shows "Property Owner" badge
- ✅ Shows "Approved" status badge (green)
- ✅ Shows quick stats cards
- ✅ Shows account information
- ✅ Shows owner-specific next steps
- ✅ Shows "Add Your First Property" button

**Test 6b: Investor Dashboard**
1. Login as `investor@360winestate.com` / `Investor@123`

**Expected Results:**
- ✅ Shows "Investor" badge
- ✅ Shows investor-specific next steps
- ✅ Shows "Browse Properties" button

**Test 6c: Marketer Dashboard**
1. Login as `marketer@360winestate.com` / `Marketer@123`

**Expected Results:**
- ✅ Shows "Marketer" badge
- ✅ Shows marketer-specific next steps
- ✅ Shows "Start Marketing" button

---

### Test 7: Middleware Protection

**Objective:** Verify middleware blocks unauthorized access

**Test 7a: Unauthenticated Access**
1. Logout
2. Try to access `/dashboard` directly

**Expected Results:**
- ✅ Redirected to login page

**Test 7b: Unverified Email Access**
1. Login as `new@360winestate.com` / `New@123`
2. Try to access `/dashboard` directly

**Expected Results:**
- ✅ Redirected to email verification page

**Test 7c: No Membership Access**
1. Create new user
2. Verify email
3. Try to access `/dashboard` directly

**Expected Results:**
- ✅ Redirected to membership selection

**Test 7d: Unapproved Access**
1. Login as `pending@360winestate.com` / `Pending@123`
2. Try to access `/dashboard` directly

**Expected Results:**
- ✅ Redirected to locked dashboard
- ✅ Warning message displayed

---

### Test 8: Logout Functionality

**Objective:** Verify logout works correctly

**Steps:**
1. Login as any user
2. Click "Logout" button in navbar

**Expected Results:**
- ✅ User is logged out
- ✅ Session invalidated
- ✅ Redirected to login page
- ✅ Success message displayed
- ✅ Cannot access protected pages

---

### Test 9: User Status Updates (Admin Operations)

**Objective:** Verify status update methods work

**Steps:**
```bash
php artisan tinker
```

**Test 9a: Approve User**
```php
$user = User::where('email', 'pending@360winestate.com')->first();
$user->approve();
$user->status; // Should be 'approved'
$user->approved_at; // Should have timestamp
```

**Test 9b: Reject User**
```php
$user = User::where('email', 'kyc@360winestate.com')->first();
$user->reject('Documents not clear');
$user->status; // Should be 'rejected'
$user->rejected_at; // Should have timestamp
$user->rejection_reason; // Should be 'Documents not clear'
```

**Test 9c: Select Membership**
```php
$user = User::create([
    'name' => 'Test User',
    'email' => 'test2@example.com',
    'password' => bcrypt('Test@123'),
    'email_verified_at' => now(),
]);
$user->selectMembership('investor');
$user->membership_type; // Should be 'investor'
$user->status; // Should be 'membership_selected'
```

---

### Test 10: Security Features

**Objective:** Verify security measures are working

**Test 10a: CSRF Protection**
1. Try to submit form without CSRF token
2. Should get 419 error

**Test 10b: Password Hashing**
```bash
php artisan tinker
```
```php
$user = User::first();
$user->password; // Should be hashed (starts with $2y$)
Hash::check('Test@123', $user->password); // Should return true
```

**Test 10c: Email Verification Rate Limiting**
1. Login as unverified user
2. Click "Resend Verification Email" 7 times quickly
3. Should get rate limit error after 6 attempts

**Test 10d: SQL Injection Protection**
1. Try login with: `' OR '1'='1` as email
2. Should fail safely (no SQL error)

**Test 10e: XSS Protection**
1. Register with name: `<script>alert('XSS')</script>`
2. View dashboard
3. Script should be escaped (not executed)

---

### Test 11: Responsive Design

**Objective:** Verify UI works on different screen sizes

**Steps:**
1. Open browser developer tools
2. Test on different viewports:
   - Mobile (375px)
   - Tablet (768px)
   - Desktop (1920px)

**Expected Results:**
- ✅ All pages are responsive
- ✅ Forms are usable on mobile
- ✅ Cards stack properly on mobile
- ✅ Navigation works on all sizes
- ✅ No horizontal scroll

---

### Test 12: Form Validation

**Objective:** Verify all validation rules work

**Test 12a: Registration Validation**
- Empty name → Error
- Name with numbers → Error
- Name too short (1 char) → Error
- Invalid email format → Error
- Duplicate email → Error
- Weak password (no symbols) → Error
- Weak password (no numbers) → Error
- Weak password (no uppercase) → Error
- Password too short → Error
- Password mismatch → Error
- Invalid phone format → Error

**Test 12b: Login Validation**
- Empty email → Error
- Invalid email format → Error
- Empty password → Error
- Non-existent email → Error

**Test 12c: Membership Validation**
- No selection → Error
- Invalid type → Error

---

## 🎯 Automated Testing Commands

### Run All Tests
```bash
php artisan test
```

### Test Specific Feature
```bash
php artisan test --filter=AuthenticationTest
```

### Test with Coverage
```bash
php artisan test --coverage
```

---

## 📊 Testing Checklist

### Registration & Authentication
- [ ] User can register with valid data
- [ ] Registration validates all fields
- [ ] Email verification is sent
- [ ] User can verify email
- [ ] User can resend verification
- [ ] User can login with valid credentials
- [ ] Login rejects invalid credentials
- [ ] Remember me works
- [ ] User can logout

### Membership & Status
- [ ] User can select membership
- [ ] Cannot select membership twice
- [ ] Status updates correctly
- [ ] All three membership types work

### Dashboard Access
- [ ] Approved users see full dashboard
- [ ] Pending users see locked dashboard
- [ ] Rejected users see rejection message
- [ ] Dashboard content varies by membership type

### Middleware & Security
- [ ] Unauthenticated users redirected to login
- [ ] Unverified users redirected to verification
- [ ] Users without membership redirected to selection
- [ ] Unapproved users redirected to locked dashboard
- [ ] CSRF protection works
- [ ] Passwords are hashed
- [ ] Rate limiting works
- [ ] XSS protection works

### UI & UX
- [ ] All pages use correct branding (navy/gold)
- [ ] Poppins font loads correctly
- [ ] Forms are user-friendly
- [ ] Error messages are clear
- [ ] Success messages display
- [ ] Responsive on all devices
- [ ] Icons display correctly
- [ ] Hover effects work

---

## 🐛 Common Issues & Solutions

### Issue: Email not sending
**Solution:** Check `.env` mail configuration or use `MAIL_MAILER=log`

### Issue: Migration fails
**Solution:** `php artisan migrate:fresh`

### Issue: 419 CSRF error
**Solution:** Clear cache: `php artisan cache:clear`

### Issue: Session not persisting
**Solution:** Run `php artisan session:table` then `php artisan migrate`

### Issue: Middleware not working
**Solution:** Clear route cache: `php artisan route:clear`

---

## ✅ Final Verification

After completing all tests, verify:

1. **Database:**
   - Users table exists
   - All columns present
   - Indexes created

2. **Files:**
   - All controllers present
   - All views present
   - All routes defined
   - Middleware registered

3. **Functionality:**
   - Complete user journey works
   - All status states work
   - All membership types work
   - Security features active

4. **UI:**
   - Branding consistent
   - Responsive design works
   - Forms are user-friendly
   - Error handling works

---

## 📈 Performance Testing

### Load Testing
```bash
# Install Apache Bench
# Test login page
ab -n 100 -c 10 http://localhost:8000/login

# Test dashboard
ab -n 100 -c 10 -C "session_cookie" http://localhost:8000/dashboard
```

### Database Queries
```bash
# Enable query log in .env
DB_LOG_QUERIES=true

# Check logs
tail -f storage/logs/laravel.log
```

---

## 🎓 Testing Best Practices

1. **Test in Order:** Follow the user journey
2. **Test Edge Cases:** Try invalid inputs
3. **Test Security:** Attempt to bypass protections
4. **Test Responsiveness:** Check all screen sizes
5. **Test Performance:** Monitor load times
6. **Document Issues:** Keep track of bugs found
7. **Retest After Fixes:** Verify fixes work

---

**Happy Testing! 🧪**
