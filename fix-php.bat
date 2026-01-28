@echo off
echo ========================================
echo Fixing XAMPP PHP Configuration
echo ========================================
echo.

echo Enabling ZIP extension in php.ini...
echo.

REM Backup php.ini
copy "C:\xampp1\php\php.ini" "C:\xampp1\php\php.ini.backup" >nul 2>&1

REM Enable zip extension
powershell -Command "(Get-Content 'C:\xampp1\php\php.ini') -replace ';extension=zip', 'extension=zip' | Set-Content 'C:\xampp1\php\php.ini'"

echo [OK] ZIP extension enabled
echo.

echo ========================================
echo Configuration Fixed!
echo ========================================
echo.
echo The ZIP extension has been enabled.
echo.
echo Next steps:
echo 1. Run install.bat again
echo 2. Installation will now work properly
echo.

pause
