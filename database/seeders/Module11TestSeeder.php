<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Admin;
use App\Models\Activity;
use App\Models\KycSubmission;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

/**
 * Module 11 Test Data Seeder
 * 
 * Creates test users, admins, and sample data for testing Module 11 features
 * 
 * Usage: php artisan db:seed --class=Module11TestSeeder
 */
class Module11TestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('🌱 Seeding Module 11 test data...');

        // Create Test Users
        $this->createTestUsers();

        // Create Test Admin
        $this->createTestAdmin();

        // Create Sample Activities
        $this->createSampleActivities();

        // Create Sample KYC Submissions
        $this->createSampleKYC();

        $this->command->info('✅ Module 11 test data seeded successfully!');
        $this->command->newLine();
        $this->displayCredentials();
    }

    /**
     * Create test users
     */
    private function createTestUsers(): void
    {
        $this->command->info('Creating test users...');

        // Test User 1 - Registered
        User::updateOrCreate(
            ['email' => 'john@test.com'],
            [
                'name' => 'John Doe',
                'password' => Hash::make('Password@123'),
                'phone' => '1234567890',
                'membership_type' => 'investor',
                'status' => 'registered',
                'address' => '123 Main Street',
                'city' => 'New York',
                'state' => 'NY',
                'country' => 'USA',
                'postal_code' => '10001',
                'date_of_birth' => '1990-01-15',
                'bio' => 'Test investor user',
            ]
        );

        // Test User 2 - Approved
        User::updateOrCreate(
            ['email' => 'jane@test.com'],
            [
                'name' => 'Jane Smith',
                'password' => Hash::make('Password@123'),
                'phone' => '0987654321',
                'membership_type' => 'owner',
                'status' => 'approved',
                'address' => '456 Oak Avenue',
                'city' => 'Los Angeles',
                'state' => 'CA',
                'country' => 'USA',
                'postal_code' => '90001',
                'date_of_birth' => '1985-05-20',
                'bio' => 'Test owner user',
            ]
        );

        // Test User 3 - KYC Submitted
        User::updateOrCreate(
            ['email' => 'bob@test.com'],
            [
                'name' => 'Bob Johnson',
                'password' => Hash::make('Password@123'),
                'phone' => '5555555555',
                'membership_type' => 'marketer',
                'status' => 'kyc_submitted',
                'address' => '789 Pine Road',
                'city' => 'Chicago',
                'state' => 'IL',
                'country' => 'USA',
                'postal_code' => '60601',
                'date_of_birth' => '1992-08-10',
                'bio' => 'Test marketer user',
            ]
        );

        // Test User 4 - Suspended
        User::updateOrCreate(
            ['email' => 'alice@test.com'],
            [
                'name' => 'Alice Brown',
                'password' => Hash::make('Password@123'),
                'phone' => '6666666666',
                'membership_type' => 'investor',
                'status' => 'suspended',
                'address' => '321 Elm Street',
                'city' => 'Houston',
                'state' => 'TX',
                'country' => 'USA',
                'postal_code' => '77001',
                'date_of_birth' => '1988-12-25',
                'bio' => 'Test suspended user',
            ]
        );

        $this->command->info('✓ Created 4 test users');
    }

    /**
     * Create test admin
     */
    private function createTestAdmin(): void
    {
        $this->command->info('Creating test admin...');

        // Create admin user
        $adminUser = User::updateOrCreate(
            ['email' => 'admin@test.com'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('Admin@123'),
                'phone' => '9999999999',
                'membership_type' => 'investor',
                'status' => 'approved',
            ]
        );

        // Create admin record
        Admin::updateOrCreate(
            ['user_id' => $adminUser->id],
            [
                'admin_role' => 'super_admin',
                'status' => 'active',
            ]
        );

        $this->command->info('✓ Created super admin');
    }

    /**
     * Create sample activities
     */
    private function createSampleActivities(): void
    {
        $this->command->info('Creating sample activities...');

        $users = User::whereNotIn('email', ['admin@test.com'])->get();

        foreach ($users as $user) {
            // Registration activity
            Activity::create([
                'user_id' => $user->id,
                'activity_type' => 'registration',
                'description' => 'User registered on the platform',
                'ip_address' => '127.0.0.1',
                'user_agent' => 'Mozilla/5.0',
                'created_at' => now()->subDays(rand(1, 30)),
            ]);

            // Login activity
            Activity::create([
                'user_id' => $user->id,
                'activity_type' => 'login',
                'description' => 'User logged in',
                'ip_address' => '127.0.0.1',
                'user_agent' => 'Mozilla/5.0',
                'created_at' => now()->subHours(rand(1, 24)),
            ]);

            // Profile update activity
            Activity::create([
                'user_id' => $user->id,
                'activity_type' => 'profile_update',
                'description' => 'User updated their profile information',
                'ip_address' => '127.0.0.1',
                'user_agent' => 'Mozilla/5.0',
                'metadata' => ['updated_fields' => ['name', 'phone', 'address']],
                'created_at' => now()->subHours(rand(1, 12)),
            ]);
        }

        $this->command->info('✓ Created sample activities');
    }

    /**
     * Create sample KYC submissions
     */
    private function createSampleKYC(): void
    {
        $this->command->info('Creating sample KYC submissions...');

        // KYC for Bob (submitted)
        $bob = User::where('email', 'bob@test.com')->first();
        if ($bob) {
            KycSubmission::create([
                'user_id' => $bob->id,
                'id_type' => 'passport',
                'id_number' => 'P123456789',
                'full_name' => 'Bob Johnson',
                'date_of_birth' => '1992-08-10',
                'address' => '789 Pine Road',
                'city' => 'Chicago',
                'state' => 'IL',
                'country' => 'USA',
                'postal_code' => '60601',
                'id_image_path' => 'kyc/sample_id.jpg',
                'address_proof_path' => 'kyc/sample_address.jpg',
                'status' => 'submitted',
                'submitted_at' => now()->subDays(2),
            ]);
        }

        // KYC for Jane (approved)
        $jane = User::where('email', 'jane@test.com')->first();
        if ($jane) {
            KycSubmission::create([
                'user_id' => $jane->id,
                'id_type' => 'drivers_license',
                'id_number' => 'DL987654321',
                'full_name' => 'Jane Smith',
                'date_of_birth' => '1985-05-20',
                'address' => '456 Oak Avenue',
                'city' => 'Los Angeles',
                'state' => 'CA',
                'country' => 'USA',
                'postal_code' => '90001',
                'id_image_path' => 'kyc/sample_id.jpg',
                'address_proof_path' => 'kyc/sample_address.jpg',
                'status' => 'approved',
                'submitted_at' => now()->subDays(10),
                'reviewed_at' => now()->subDays(8),
                'admin_notes' => 'All documents verified successfully',
            ]);
        }

        $this->command->info('✓ Created sample KYC submissions');
    }

    /**
     * Display test credentials
     */
    private function displayCredentials(): void
    {
        $this->command->info('═══════════════════════════════════════════════════════');
        $this->command->info('📋 TEST CREDENTIALS');
        $this->command->info('═══════════════════════════════════════════════════════');
        $this->command->newLine();

        $this->command->info('👤 TEST USERS:');
        $this->command->info('─────────────────────────────────────────────────────');
        $this->command->info('1. John Doe (Registered)');
        $this->command->info('   Email: john@test.com');
        $this->command->info('   Password: Password@123');
        $this->command->newLine();

        $this->command->info('2. Jane Smith (Approved)');
        $this->command->info('   Email: jane@test.com');
        $this->command->info('   Password: Password@123');
        $this->command->newLine();

        $this->command->info('3. Bob Johnson (KYC Submitted)');
        $this->command->info('   Email: bob@test.com');
        $this->command->info('   Password: Password@123');
        $this->command->newLine();

        $this->command->info('4. Alice Brown (Suspended)');
        $this->command->info('   Email: alice@test.com');
        $this->command->info('   Password: Password@123');
        $this->command->newLine();

        $this->command->info('👨‍💼 TEST ADMIN:');
        $this->command->info('─────────────────────────────────────────────────────');
        $this->command->info('Super Admin');
        $this->command->info('Email: admin@test.com');
        $this->command->info('Password: Admin@123');
        $this->command->newLine();

        $this->command->info('═══════════════════════════════════════════════════════');
        $this->command->info('🚀 START TESTING:');
        $this->command->info('─────────────────────────────────────────────────────');
        $this->command->info('User Login: http://localhost:8000/login');
        $this->command->info('Admin Login: http://localhost:8000/admin/login');
        $this->command->info('═══════════════════════════════════════════════════════');
    }
}
