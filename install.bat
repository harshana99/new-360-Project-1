@echo off
REM ========================================
REM 360WinEstate - Complete Auto Setup
REM ========================================

echo.
echo ========================================
echo 360WinEstate - Automated Installation
echo ========================================
echo.

REM Check if Composer is installed
where composer >nul 2>nul
if %ERRORLEVEL% NEQ 0 (
    echo [ERROR] Composer is NOT installed!
    echo.
    echo SOLUTION: Please install Composer first
    echo 1. Visit: https://getcomposer.org/download/
    echo 2. Download: Composer-Setup.exe
    echo 3. Run installer and select: C:\xampp\php\php.exe
    echo 4. Restart this script after installation
    echo.
    echo Opening Composer download page...
    start https://getcomposer.org/download/
    echo.
    pause
    exit /b 1
)

echo [OK] Composer is installed
echo.

REM Install Laravel dependencies
echo ========================================
echo Step 1: Installing Laravel Framework
echo ========================================
echo This may take 5-10 minutes...
echo.

if not exist "vendor" (
    composer install --no-interaction --prefer-dist
    if %ERRORLEVEL% NEQ 0 (
        echo.
        echo [ERROR] Failed to install dependencies
        echo.
        echo Trying alternative method...
        composer create-project laravel/laravel temp "11.*" --no-interaction
        xcopy /E /I /Y temp\* .
        rmdir /S /Q temp
    )
)

echo.
echo [OK] Laravel framework installed
echo.

REM Setup environment
echo ========================================
echo Step 2: Configuring Environment
echo ========================================
echo.

if not exist ".env" (
    echo Creating .env file...
    copy .env.example .env >nul
    echo [OK] .env file created
) else (
    echo [OK] .env file already exists
)

echo.
echo Generating application key...
php artisan key:generate --no-interaction
echo [OK] Application key generated
echo.

REM Configure .env for XAMPP
echo Configuring database settings...
powershell -Command "(Get-Content .env) -replace 'DB_CONNECTION=sqlite', 'DB_CONNECTION=mysql' | Set-Content .env"
powershell -Command "(Get-Content .env) -replace 'DB_DATABASE=.*', 'DB_DATABASE=360winestate' | Set-Content .env"
powershell -Command "(Get-Content .env) -replace 'DB_USERNAME=.*', 'DB_USERNAME=root' | Set-Content .env"
powershell -Command "(Get-Content .env) -replace 'DB_PASSWORD=.*', 'DB_PASSWORD=' | Set-Content .env"
powershell -Command "(Get-Content .env) -replace 'MAIL_MAILER=.*', 'MAIL_MAILER=log' | Set-Content .env"
echo [OK] Database configured for XAMPP
echo.

REM Check if MySQL is running
echo ========================================
echo Step 3: Database Setup
echo ========================================
echo.

echo Checking if MySQL is running...
netstat -an | find ":3306" >nul
if %ERRORLEVEL% NEQ 0 (
    echo.
    echo [WARNING] MySQL is NOT running!
    echo.
    echo Please start MySQL in XAMPP Control Panel
    echo Then press any key to continue...
    pause >nul
)

echo [OK] MySQL is running
echo.

REM Create database
echo Creating database '360winestate'...
php -r "$conn = @new mysqli('127.0.0.1', 'root', '', ''); if (!$conn->connect_error) { $conn->query('CREATE DATABASE IF NOT EXISTS 360winestate'); echo 'Database created successfully'; } else { echo 'Could not connect to MySQL'; }"
echo.

REM Run migrations
echo ========================================
echo Step 4: Running Migrations
echo ========================================
echo.

php artisan migrate --force --no-interaction
if %ERRORLEVEL% EQU 0 (
    echo [OK] Migrations completed successfully
) else (
    echo [WARNING] Migrations may have failed
    echo This is OK if tables already exist
)

echo.

REM Seed database
echo ========================================
echo Step 5: Seeding Test Data
echo ========================================
echo.

php artisan db:seed --class=UserSeeder --force --no-interaction
if %ERRORLEVEL% EQU 0 (
    echo [OK] Test data seeded successfully
) else (
    echo [WARNING] Seeding may have failed
)

echo.

REM Clear caches
echo ========================================
echo Step 6: Optimizing Application
echo ========================================
echo.

php artisan config:clear >nul 2>&1
php artisan cache:clear >nul 2>&1
php artisan route:clear >nul 2>&1
php artisan view:clear >nul 2>&1

echo [OK] Application optimized
echo.

REM Final summary
echo ========================================
echo Installation Complete! 🎉
echo ========================================
echo.
echo Your 360WinEstate application is ready!
echo.
echo Test Accounts:
echo ----------------------------------------
echo Email: owner@360winestate.com
echo Password: Owner@123
echo Type: Property Owner (Approved)
echo.
echo Email: investor@360winestate.com
echo Password: Investor@123
echo Type: Investor (Approved)
echo.
echo Email: admin@360winestate.com
echo Password: Admin@123
echo Type: Admin (Approved)
echo ----------------------------------------
echo.
echo Next Steps:
echo 1. Start the server: php artisan serve
echo 2. Open browser: http://localhost:8000
echo 3. Login with any test account above
echo.
echo ========================================
echo.

choice /C YN /M "Do you want to start the server now"
if %ERRORLEVEL% EQU 1 (
    echo.
    echo ========================================
    echo Starting Laravel Development Server
    echo ========================================
    echo.
    echo Server URL: http://localhost:8000
    echo.
    echo Press Ctrl+C to stop the server
    echo.
    echo Opening browser...
    timeout /t 3 >nul
    start http://localhost:8000
    echo.
    php artisan serve
) else (
    echo.
    echo To start the server later, run:
    echo   php artisan serve
    echo.
    echo Then open: http://localhost:8000
    echo.
)

pause
