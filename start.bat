@echo off
echo ========================================
echo 360WinEstate - Quick Start Script
echo ========================================
echo.

REM Check if composer is installed
where composer >nul 2>nul
if %ERRORLEVEL% NEQ 0 (
    echo [ERROR] Composer is not installed!
    echo.
    echo Please install Composer first:
    echo 1. Visit: https://getcomposer.org/download/
    echo 2. Download and run Composer-Setup.exe
    echo 3. Run this script again
    echo.
    pause
    exit /b 1
)

echo [OK] Composer is installed
echo.

REM Check if vendor directory exists
if not exist "vendor" (
    echo [INFO] Installing Laravel dependencies...
    echo This may take a few minutes...
    echo.
    composer install
    echo.
)

REM Check if .env exists
if not exist ".env" (
    echo [INFO] Creating .env file...
    copy .env.example .env
    echo.
    
    echo [INFO] Generating application key...
    php artisan key:generate
    echo.
)

echo ========================================
echo Setup Complete!
echo ========================================
echo.
echo Next Steps:
echo 1. Start XAMPP (Apache + MySQL)
echo 2. Create database '360winestate' in phpMyAdmin
echo 3. Run migrations: php artisan migrate
echo 4. Seed test data: php artisan db:seed --class=UserSeeder
echo 5. Start server: php artisan serve
echo.
echo Then open: http://localhost:8000
echo.
echo ========================================
echo.

choice /C YN /M "Do you want to start the Laravel server now"
if %ERRORLEVEL% EQU 1 (
    echo.
    echo Starting Laravel development server...
    echo Press Ctrl+C to stop the server
    echo.
    php artisan serve
) else (
    echo.
    echo To start the server later, run: php artisan serve
    echo.
)

pause
