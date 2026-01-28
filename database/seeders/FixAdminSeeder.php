<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Admin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class FixAdminSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('🔧 Fixing Admin Record...');

        // 1. Ensure User exists
        $user = User::firstWhere('email', 'admin@test.com');
        
        if (!$user) {
            $this->command->info('Creating admin user...');
            $user = User::create([
                'name' => 'Super Admin',
                'email' => 'admin@test.com',
                'password' => Hash::make('Admin@123'),
                'phone' => '9999999999',
                'membership_type' => 'investor', 
                'status' => 'approved',
            ]);
        } else {
            $this->command->info('Admin user exists. Updating password...');
            $user->update([
                'password' => Hash::make('Admin@123'),
                'status' => 'approved' // Ensure user status is valid
            ]);
        }

        // 2. Ensure Admin record exists
        $this->command->info('Updating admin privileges...');
        
        $admin = Admin::updateOrCreate(
            ['user_id' => $user->id],
            [
                'admin_role' => 'super_admin',
                'status' => 'active' // Explicitly setting status to active
            ]
        );

        $this->command->info('✅ Admin record fixed!');
        $this->command->info('User ID: ' . $user->id);
        $this->command->info('Admin ID: ' . $admin->id);
        $this->command->info('Admin Status: ' . $admin->status);
    }
}
