# 🔴 DATABASE CONNECTION ERROR - FIX GUIDE

## ❌ **ERROR:**

```
SQLSTATE[HY000] [2002] No connection could be made because the target machine actively refused it
```

**What this means:** Laravel cannot connect to your MySQL database.

---

## 🔧 **QUICK FIX:**

### **Step 1: Start XAMPP MySQL**

1. Open **XAMPP Control Panel**
2. Click **Start** next to **MySQL**
3. Wait for it to show "Running" (green)

**If MySQL won't start:**
- Check if port 3306 is already in use
- Stop any other MySQL services
- Check XAMPP error logs

---

### **Step 2: Verify Database Exists**

1. Open **phpMyAdmin**: `http://localhost/phpmyadmin`
2. Check if database `360winestate` exists
3. If not, create it:
   - Click "New" in left sidebar
   - Database name: `360winestate`
   - Collation: `utf8mb4_unicode_ci`
   - Click "Create"

---

### **Step 3: Check .env File**

Open `.env` file and verify these settings:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=360winestate
DB_USERNAME=root
DB_PASSWORD=
```

**Common Issues:**
- ❌ `DB_HOST=localhost` → ✅ Change to `127.0.0.1`
- ❌ Wrong database name
- ❌ Wrong username/password
- ❌ Port 3306 blocked

---

### **Step 4: Clear Config Cache**

```cmd
cd C:\xampp1\htdocs\new 360 Project
php artisan config:clear
php artisan cache:clear
```

---

### **Step 5: Test Database Connection**

```cmd
php artisan tinker --execute="DB::connection()->getPdo(); echo 'Connected!'; exit"
```

**Expected:** `Connected!`

**If error:** Check XAMPP MySQL is running

---

## 🧪 **VERIFICATION STEPS:**

### **1. Check XAMPP Status:**
- [ ] Apache: Running (green)
- [ ] MySQL: Running (green)

### **2. Check Database:**
- [ ] Open phpMyAdmin: `http://localhost/phpmyadmin`
- [ ] Database `360winestate` exists
- [ ] Tables are present (users, admins, etc.)

### **3. Check .env:**
- [ ] `DB_HOST=127.0.0.1`
- [ ] `DB_DATABASE=360winestate`
- [ ] `DB_USERNAME=root`
- [ ] `DB_PASSWORD=` (empty for default XAMPP)

### **4. Test Connection:**
```cmd
php artisan migrate:status
```

**Expected:** List of migrations with "Ran" status

---

## 🔍 **TROUBLESHOOTING:**

### **MySQL Won't Start in XAMPP:**

**Option 1: Check Port Conflict**
```cmd
netstat -ano | findstr :3306
```

If port 3306 is in use, either:
- Stop the other service using it
- Change MySQL port in XAMPP config

**Option 2: Check Error Logs**
- Open XAMPP Control Panel
- Click "Logs" next to MySQL
- Check for errors

**Option 3: Reinstall MySQL Module**
- Stop MySQL in XAMPP
- Click "Config" → "my.ini"
- Check configuration
- Restart MySQL

---

### **Database Doesn't Exist:**

**Create via phpMyAdmin:**
1. Go to `http://localhost/phpmyadmin`
2. Click "New" in sidebar
3. Database name: `360winestate`
4. Click "Create"

**Create via Command:**
```cmd
php artisan tinker --execute="DB::statement('CREATE DATABASE IF NOT EXISTS 360winestate'); echo 'Created!'; exit"
```

---

### **.env Not Loading:**

**Clear all caches:**
```cmd
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
```

**Verify .env exists:**
```cmd
dir .env
```

If missing, copy from `.env.example`:
```cmd
copy .env.example .env
```

Then update database settings.

---

## 📋 **COMPLETE FIX CHECKLIST:**

1. [ ] Start XAMPP MySQL
2. [ ] Verify MySQL is running (green in XAMPP)
3. [ ] Open phpMyAdmin (`http://localhost/phpmyadmin`)
4. [ ] Verify database `360winestate` exists
5. [ ] Check `.env` file has correct settings
6. [ ] Run `php artisan config:clear`
7. [ ] Test connection: `php artisan migrate:status`
8. [ ] Refresh browser and try login again

---

## 🚀 **QUICK START COMMANDS:**

```cmd
# 1. Navigate to project
cd C:\xampp1\htdocs\new 360 Project

# 2. Clear caches
php artisan config:clear
php artisan cache:clear

# 3. Test database
php artisan migrate:status

# 4. Start server
php artisan serve
```

---

## ✅ **EXPECTED RESULT:**

After fixing:
- ✅ MySQL running in XAMPP
- ✅ Database `360winestate` exists
- ✅ `.env` configured correctly
- ✅ `php artisan migrate:status` shows migrations
- ✅ Login page loads without error
- ✅ Can login as super admin

---

## 🆘 **STILL NOT WORKING?**

**Check these:**

1. **XAMPP MySQL Status:**
   - Is it actually running?
   - Check XAMPP logs for errors

2. **Firewall:**
   - Is port 3306 blocked?
   - Temporarily disable firewall to test

3. **Another MySQL Running:**
   - Check Task Manager for other MySQL processes
   - Stop them or change XAMPP port

4. **Corrupted Installation:**
   - Reinstall XAMPP MySQL module
   - Or use different port

---

**Most Common Fix:** Just start MySQL in XAMPP Control Panel! 🎯

**Created:** January 28, 2026  
**Error:** Database connection refused  
**Solution:** Start XAMPP MySQL + verify .env settings
