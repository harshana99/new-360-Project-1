# 360WinEstate - Quick Start Guide

## 🚀 Getting Started in 5 Minutes

### Step 1: Install Laravel (if not already installed)

If you don't have a Laravel installation yet:

```bash
composer create-project laravel/laravel . "11.*"
```

### Step 2: Configure Environment

```bash
# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate
```

### Step 3: Configure Database

Edit `.env` file and set your database credentials:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=360winestate
DB_USERNAME=root
DB_PASSWORD=your_password
```

Create the database:
```sql
CREATE DATABASE 360winestate;
```

### Step 4: Configure Email (for Email Verification)

**Option A: Using Mailtrap (Recommended for Development)**

1. Sign up at https://mailtrap.io (free)
2. Get your SMTP credentials
3. Update `.env`:

```env
MAIL_MAILER=smtp
MAIL_HOST=sandbox.smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your_mailtrap_username
MAIL_PASSWORD=your_mailtrap_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@360winestate.com
MAIL_FROM_NAME="360WinEstate"
```

**Option B: Using Gmail**

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your_email@gmail.com
MAIL_PASSWORD=your_app_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=your_email@gmail.com
MAIL_FROM_NAME="360WinEstate"
```

**Option C: Log Emails (Testing Only)**

```env
MAIL_MAILER=log
```

### Step 5: Run Migrations

```bash
php artisan migrate
```

### Step 6: Start the Server

```bash
php artisan serve
```

Visit: **http://localhost:8000**

---

## 📝 Testing the System

### 1. Register a New User

1. Go to http://localhost:8000/register
2. Fill in the form:
   - Name: John Doe
   - Email: john@example.com
   - Phone: +1234567890
   - Password: Test@123 (must be strong)
3. Click "Create Account"

### 2. Verify Email

- Check your email inbox (Mailtrap or Gmail)
- Click the verification link
- You'll be redirected to membership selection

### 3. Select Membership

Choose one of:
- **Property Owner** - List and manage properties
- **Investor** - Invest in properties
- **Marketer** - Promote properties

### 4. View Locked Dashboard

After selecting membership, you'll see the locked dashboard showing:
- Your account status
- Account information
- Next steps (KYC verification)

### 5. Approve User (Admin Action)

To test the full dashboard, manually approve a user:

```bash
php artisan tinker
```

Then run:
```php
$user = App\Models\User::where('email', 'john@example.com')->first();
$user->approve();
exit
```

### 6. Access Full Dashboard

- Logout and login again
- You'll now see the full dashboard with all features

---

## 🎯 User Flow Diagram

```
┌─────────────────┐
│   Registration  │
└────────┬────────┘
         │
         ▼
┌─────────────────┐
│ Email Verification│
└────────┬────────┘
         │
         ▼
┌─────────────────┐
│ Select Membership│
│ (Owner/Investor/ │
│   Marketer)     │
└────────┬────────┘
         │
         ▼
┌─────────────────┐
│ Locked Dashboard│
│ (Pending KYC)   │
└────────┬────────┘
         │
         ▼
┌─────────────────┐
│  Admin Review   │
└────────┬────────┘
         │
    ┌────┴────┐
    ▼         ▼
┌────────┐ ┌────────┐
│Approved│ │Rejected│
└───┬────┘ └────────┘
    │
    ▼
┌─────────────────┐
│ Full Dashboard  │
└─────────────────┘
```

---

## 🔧 Common Commands

### Clear Cache
```bash
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

### Reset Database
```bash
php artisan migrate:fresh
```

### Create Admin User (via Tinker)
```bash
php artisan tinker
```
```php
$user = App\Models\User::create([
    'name' => 'Admin User',
    'email' => 'admin@360winestate.com',
    'password' => bcrypt('Admin@123'),
    'email_verified_at' => now(),
    'membership_type' => 'owner',
    'status' => 'approved',
    'approved_at' => now(),
]);
```

---

## 🎨 Customization

### Change Colors

Edit `resources/views/layouts/app.blade.php`:

```css
:root {
    --navy: #0F1A3C;    /* Primary color */
    --gold: #E4B400;    /* Accent color */
    --white: #FFFFFF;   /* Background */
}
```

### Change Logo/Branding

Edit the navbar in `resources/views/layouts/app.blade.php`:

```html
<a class="navbar-brand" href="/">
    Your<span class="gold-text">Brand</span>Name
</a>
```

---

## 🐛 Troubleshooting

### Email Not Sending?

1. Check `.env` mail configuration
2. Test with `MAIL_MAILER=log` to see emails in `storage/logs/laravel.log`
3. Verify firewall isn't blocking SMTP port

### Migration Errors?

```bash
# Drop all tables and re-run
php artisan migrate:fresh

# Or rollback and re-run
php artisan migrate:rollback
php artisan migrate
```

### Session Issues?

```bash
# Create sessions table
php artisan session:table
php artisan migrate

# Clear sessions
php artisan cache:clear
```

### Permission Errors (Linux/Mac)?

```bash
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

---

## 📚 Next Steps

1. **Module 2:** KYC Document Upload System
2. **Module 3:** Admin Panel for User Management
3. **Module 4:** Property Management System
4. **Module 5:** Investment & Wallet System

---

## 💡 Tips

- Use **Mailtrap** for development email testing
- Enable **APP_DEBUG=true** during development
- Keep **APP_ENV=local** for development
- Use strong passwords in production
- Always backup database before migrations

---

## 📞 Support

Need help? Contact: support@360winestate.com

---

**Happy Coding! 🎉**
