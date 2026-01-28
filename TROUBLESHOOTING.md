# 🔧 TROUBLESHOOTING GUIDE

## ✅ Issues Fixed So Far:

1. ✅ **ZIP extension disabled** - Fixed by enabling in php.ini
2. ✅ **Vendor folder missing** - Installed Laravel 11 with Composer
3. ✅ **Sessions table missing** - Created and migrated
4. ✅ **Database config commented** - Uncommented in .env
5. ✅ **Controller.php missing** - Copied from Laravel temp
6. ✅ **Config files missing** - Copied all config files
7. ✅ **User model overwritten** - Restored custom User model

---

## 🌐 Application Should Now Be Working!

**URL:** http://localhost:8000

If you see any errors, try these solutions:

---

## 🐛 Common Issues & Solutions:

### Issue: "Class not found" or "File not found"
**Solution:**
```bash
composer dump-autoload
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
```

### Issue: "SQLSTATE connection refused"
**Solution:**
1. Check XAMPP MySQL is running
2. Verify database exists: `360winestate`
3. Check .env file has correct credentials

### Issue: "Session store not set"
**Solution:**
```bash
php artisan session:table
php artisan migrate
```

### Issue: "Route not found"
**Solution:**
```bash
php artisan route:clear
php artisan route:list
```

### Issue: "View not found"
**Solution:**
```bash
php artisan view:clear
```

### Issue: "Permission denied" on storage
**Solution:**
```bash
# Windows: Run as Administrator
icacls storage /grant Everyone:F /T
icacls bootstrap/cache /grant Everyone:F /T
```

---

## 🔄 Complete Reset (If Needed):

If nothing works, run these commands:

```bash
# Clear all caches
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

# Rebuild database
php artisan migrate:fresh --seed --force

# Restart server
# Press Ctrl+C to stop current server
php artisan serve
```

---

## ✅ Verification Checklist:

- [ ] XAMPP MySQL is running
- [ ] Database `360winestate` exists
- [ ] `.env` file has correct DB credentials
- [ ] Vendor folder exists (8000+ files)
- [ ] Config folder exists (10 files)
- [ ] Server is running on port 8000
- [ ] No errors in terminal

---

## 📊 Quick Health Check:

Run these commands to verify everything:

```bash
# Check Laravel version
php artisan --version

# Check database connection
php artisan migrate:status

# List all routes
php artisan route:list

# Check if server is running
# Should show: Server running on http://127.0.0.1:8000
```

---

## 🎯 Test URLs:

Once working, test these URLs:

- http://localhost:8000 - Home page
- http://localhost:8000/register - Registration
- http://localhost:8000/login - Login
- http://localhost:8000/dashboard - Dashboard (after login)

---

## 📞 Still Having Issues?

### Check Server Logs:
Look at the terminal where `php artisan serve` is running for error messages.

### Check Laravel Logs:
```bash
# View recent errors
type storage\logs\laravel.log
```

### Verify Files Exist:
```bash
# Check critical files
dir app\Http\Controllers\Controller.php
dir app\Models\User.php
dir config\app.php
dir vendor\autoload.php
```

---

## 🎊 When Everything Works:

You should see:
- ✅ Laravel welcome page or login page
- ✅ No errors in browser
- ✅ No errors in terminal
- ✅ Can register/login successfully

---

**Your application is ready! Enjoy testing!** 🚀
