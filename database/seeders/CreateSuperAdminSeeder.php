<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;

/**
 * Create Super Admin Seeder
 * 
 * Creates the first super admin account
 * This is the ONLY way to create a super admin
 * (cannot be created through the admin panel)
 */
class CreateSuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Check if super admin already exists
        $existingSuperAdmin = Admin::where('admin_role', Admin::ROLE_SUPER_ADMIN)->first();
        
        if ($existingSuperAdmin) {
            $this->command->info('Super Admin already exists!');
            $this->command->info('Email: ' . $existingSuperAdmin->user->email);
            return;
        }

        // Create super admin user
        $user = User::create([
            'name' => 'Super Admin',
            'email' => 'superadmin@360winestate.com',
            'phone' => '+2348000000000',
            'password' => Hash::make('SuperAdmin@123'),
            'status' => User::STATUS_APPROVED,
            'email_verified_at' => now(),
        ]);

        // Create admin record
        $admin = Admin::create([
            'user_id' => $user->id,
            'admin_role' => Admin::ROLE_SUPER_ADMIN,
            'status' => Admin::STATUS_ACTIVE,
            'created_by' => null, // First admin, no creator
        ]);

        $this->command->info('✅ Super Admin created successfully!');
        $this->command->info('');
        $this->command->info('Login Credentials:');
        $this->command->info('Email: superadmin@360winestate.com');
        $this->command->info('Password: SuperAdmin@123');
        $this->command->info('');
        $this->command->info('Access admin panel at: http://localhost:8000/admin/dashboard');
    }
}
