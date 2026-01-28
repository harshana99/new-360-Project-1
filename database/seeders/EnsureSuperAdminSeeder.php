<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Admin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class EnsureSuperAdminSeeder extends Seeder
{
    public function run(): void
    {
        $email = 'superadmin@360winestate.com';
        $password = 'SuperAdmin@123';

        $this->command->info("Checking credentials for: $email");

        // 1. Ensure User exists
        $user = User::firstWhere('email', $email);
        
        if (!$user) {
            $this->command->info('Creating new Super Admin user...');
            $user = User::create([
                'name' => 'Super Admin',
                'email' => $email,
                'password' => Hash::make($password),
                'phone' => '8888888888',
                'membership_type' => 'investor', 
                'status' => 'approved',
            ]);
        } else {
            $this->command->info('User exists. Resetting password...');
            $user->update([
                'password' => Hash::make($password),
                'status' => 'approved'
            ]);
        }

        // 2. Ensure Admin record exists
        $this->command->info('Ensuring Admin privileges...');
        
        $admin = Admin::updateOrCreate(
            ['user_id' => $user->id],
            [
                'admin_role' => 'super_admin',
                'status' => 'active'
            ]
        );

        $this->command->info('✅ DONE!');
        $this->command->info("URL: http://localhost:8000/admin/login");
        $this->command->info("Email: $email");
        $this->command->info("Password: $password");
    }
}
