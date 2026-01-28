<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use App\Models\Admin;

// Find the super admin user
$user = User::where('email', 'superadmin@360winestate.com')->first();

if (!$user) {
    echo "❌ Super admin user not found!\n";
    echo "Creating user...\n";
    
    $user = User::create([
        'name' => 'Super Admin',
        'email' => 'superadmin@360winestate.com',
        'phone' => '+2348000000000',
        'password' => bcrypt('SuperAdmin@123'),
        'status' => 'approved',
        'email_verified_at' => now(),
    ]);
    
    echo "✅ User created!\n";
}

// Check if admin record exists
if ($user->admin) {
    echo "✅ Super Admin already exists!\n";
    echo "Email: superadmin@360winestate.com\n";
    echo "Password: SuperAdmin@123\n";
    echo "Dashboard: http://localhost:8000/admin/dashboard\n";
} else {
    // Create admin record
    Admin::create([
        'user_id' => $user->id,
        'admin_role' => 'super_admin',
        'status' => 'active',
        'created_by' => null,
    ]);
    
    echo "✅ Super Admin created successfully!\n";
    echo "\n";
    echo "Login Credentials:\n";
    echo "Email: superadmin@360winestate.com\n";
    echo "Password: SuperAdmin@123\n";
    echo "\n";
    echo "Access admin panel at: http://localhost:8000/admin/dashboard\n";
}
