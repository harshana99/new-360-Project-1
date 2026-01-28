# 360WinEstate - XAMPP Setup Guide for Windows

## 🚀 Quick Setup for XAMPP Users

### Step 1: Install Composer (if not installed)

1. **Download Composer:**
   - Visit: https://getcomposer.org/download/
   - Download: `Composer-Setup.exe`
   - Run the installer
   - Select your XAMPP PHP (usually `C:\xampp\php\php.exe`)
   - Complete installation

2. **Verify Composer Installation:**
   ```powershell
   composer --version
   ```
   You should see: `Composer version 2.x.x`

---

### Step 2: Install Laravel in Your Project

Open PowerShell or Command Prompt in your project folder:

```powershell
cd "c:\xampp1\htdocs\new 360 Project"
```

**Option A: Fresh Laravel Installation (Recommended)**
```powershell
# This will install Laravel 11 in the current directory
composer create-project laravel/laravel temp "11.*"

# Move Laravel files to current directory
Move-Item -Path temp\* -Destination . -Force

# Remove temp folder
Remove-Item temp -Recurse -Force
```

**Option B: If you want to keep existing files**
```powershell
# Initialize composer in current directory
composer init --no-interaction

# Add Laravel as dependency
composer require laravel/framework "^11.0"
```

---

### Step 3: Copy Our Custom Files

Our authentication files are already in place:
- ✅ Controllers
- ✅ Models
- ✅ Views
- ✅ Routes
- ✅ Migrations

Just need to ensure they're in the right Laravel structure.

---

### Step 4: Configure Environment

1. **Copy environment file:**
   ```powershell
   Copy-Item .env.example .env
   ```

2. **Generate application key:**
   ```powershell
   php artisan key:generate
   ```

3. **Edit `.env` file** with your database settings:
   ```env
   APP_NAME="360WinEstate"
   APP_URL=http://localhost/new%20360%20Project/public

   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=360winestate
   DB_USERNAME=root
   DB_PASSWORD=

   MAIL_MAILER=log
   ```

---

### Step 5: Create Database

1. **Start XAMPP:**
   - Open XAMPP Control Panel
   - Start **Apache**
   - Start **MySQL**

2. **Create Database:**
   - Open browser: http://localhost/phpmyadmin
   - Click "New" on left sidebar
   - Database name: `360winestate`
   - Collation: `utf8mb4_unicode_ci`
   - Click "Create"

---

### Step 6: Run Migrations

```powershell
php artisan migrate
```

If you get "command not found", use full path:
```powershell
C:\xampp\php\php.exe artisan migrate
```

---

### Step 7: Seed Test Data

```powershell
php artisan db:seed --class=UserSeeder
```

---

### Step 8: Start the Application

**Option A: Using PHP Built-in Server (Recommended)**
```powershell
php artisan serve
```
Then open: **http://localhost:8000**

**Option B: Using XAMPP Apache**
```powershell
# No command needed, just ensure Apache is running in XAMPP
```
Then open: **http://localhost/new%20360%20Project/public**

---

## 🌐 Accessing the Application

### If using `php artisan serve`:
- **URL:** http://localhost:8000
- **Register:** http://localhost:8000/register
- **Login:** http://localhost:8000/login

### If using XAMPP Apache:
- **URL:** http://localhost/new%20360%20Project/public
- **Register:** http://localhost/new%20360%20Project/public/register
- **Login:** http://localhost/new%20360%20Project/public/login

---

## 🧪 Test Accounts (After Seeding)

| Email | Password | Type | Status |
|-------|----------|------|--------|
| owner@360winestate.com | Owner@123 | Owner | Approved ✅ |
| investor@360winestate.com | Investor@123 | Investor | Approved ✅ |
| marketer@360winestate.com | Marketer@123 | Marketer | Approved ✅ |
| admin@360winestate.com | Admin@123 | Owner | Approved ✅ |

---

## 🐛 Troubleshooting

### Issue: "composer: command not found"
**Solution:** Install Composer from https://getcomposer.org/download/

### Issue: "php: command not found"
**Solution:** Use full path: `C:\xampp\php\php.exe artisan serve`

### Issue: "Class not found" errors
**Solution:** Run: `composer dump-autoload`

### Issue: Database connection error
**Solution:** 
1. Check XAMPP MySQL is running
2. Verify database `360winestate` exists
3. Check `.env` database credentials

### Issue: 404 Not Found
**Solution:** Make sure you're accessing `/public` folder or use `php artisan serve`

### Issue: Permission errors
**Solution:** Run as Administrator or check folder permissions

---

## ✅ Quick Verification Checklist

- [ ] Composer installed
- [ ] Laravel installed in project
- [ ] `.env` file configured
- [ ] Database created
- [ ] Migrations run
- [ ] Test data seeded
- [ ] Server running
- [ ] Browser opened to correct URL

---

## 🎯 Recommended: Use Laravel Artisan Serve

The easiest way to run the application:

```powershell
# Navigate to project
cd "c:\xampp1\htdocs\new 360 Project"

# Start server
php artisan serve

# Or specify port
php artisan serve --port=8080
```

Then open: **http://localhost:8000**

---

## 📞 Need Help?

If you encounter any issues:
1. Check XAMPP is running (Apache + MySQL)
2. Verify database exists
3. Check `.env` configuration
4. Run `composer install` to ensure dependencies
5. Clear cache: `php artisan cache:clear`

---

**Happy Coding! 🎉**
