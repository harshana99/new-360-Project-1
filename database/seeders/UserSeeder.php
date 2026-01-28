<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\OwnerStats;
use App\Models\InvestorStats;
use App\Models\MarketerStats;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

/**
 * User Seeder
 * 
 * Seeds the database with test users for each membership type and status
 * 
 * Usage: php artisan db:seed --class=UserSeeder
 */
class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Approved Property Owner
        User::create([
            'name' => 'John Owner',
            'email' => 'owner@360winestate.com',
            'phone' => '+1234567890',
            'password' => Hash::make('Owner@123'),
            'email_verified_at' => now(),
            'membership_type' => User::MEMBERSHIP_OWNER,
            'status' => User::STATUS_APPROVED,
            'membership_selected_at' => now()->subDays(5),
            'kyc_submitted_at' => now()->subDays(4),
            'approved_at' => now()->subDays(2),
        ]);

        // 2. Approved Investor
        User::create([
            'name' => 'Sarah Investor',
            'email' => 'investor@360winestate.com',
            'phone' => '+1234567891',
            'password' => Hash::make('Investor@123'),
            'email_verified_at' => now(),
            'membership_type' => User::MEMBERSHIP_INVESTOR,
            'status' => User::STATUS_APPROVED,
            'membership_selected_at' => now()->subDays(6),
            'kyc_submitted_at' => now()->subDays(5),
            'approved_at' => now()->subDays(3),
        ]);

        // 3. Approved Marketer
        User::create([
            'name' => 'Mike Marketer',
            'email' => 'marketer@360winestate.com',
            'phone' => '+1234567892',
            'password' => Hash::make('Marketer@123'),
            'email_verified_at' => now(),
            'membership_type' => User::MEMBERSHIP_MARKETER,
            'status' => User::STATUS_APPROVED,
            'membership_selected_at' => now()->subDays(4),
            'kyc_submitted_at' => now()->subDays(3),
            'approved_at' => now()->subDays(1),
        ]);

        // 4. User Under Review
        User::create([
            'name' => 'Tom Pending',
            'email' => 'pending@360winestate.com',
            'phone' => '+1234567893',
            'password' => Hash::make('Pending@123'),
            'email_verified_at' => now(),
            'membership_type' => User::MEMBERSHIP_INVESTOR,
            'status' => User::STATUS_UNDER_REVIEW,
            'membership_selected_at' => now()->subDays(2),
            'kyc_submitted_at' => now()->subDay(),
        ]);

        // 5. User with KYC Submitted
        User::create([
            'name' => 'Lisa Submitted',
            'email' => 'kyc@360winestate.com',
            'phone' => '+1234567894',
            'password' => Hash::make('Kyc@123'),
            'email_verified_at' => now(),
            'membership_type' => User::MEMBERSHIP_OWNER,
            'status' => User::STATUS_KYC_SUBMITTED,
            'membership_selected_at' => now()->subDays(3),
            'kyc_submitted_at' => now()->subDays(2),
        ]);

        // 6. User with Membership Selected (No KYC)
        User::create([
            'name' => 'David Selected',
            'email' => 'selected@360winestate.com',
            'phone' => '+1234567895',
            'password' => Hash::make('Selected@123'),
            'email_verified_at' => now(),
            'membership_type' => User::MEMBERSHIP_MARKETER,
            'status' => User::STATUS_MEMBERSHIP_SELECTED,
            'membership_selected_at' => now()->subDay(),
        ]);

        // 7. Rejected User
        User::create([
            'name' => 'Emma Rejected',
            'email' => 'rejected@360winestate.com',
            'phone' => '+1234567896',
            'password' => Hash::make('Rejected@123'),
            'email_verified_at' => now(),
            'membership_type' => User::MEMBERSHIP_INVESTOR,
            'status' => User::STATUS_REJECTED,
            'membership_selected_at' => now()->subDays(5),
            'kyc_submitted_at' => now()->subDays(4),
            'rejected_at' => now()->subDays(2),
            'rejection_reason' => 'The provided KYC documents were not clear enough. Please resubmit with high-quality scans of your identification documents.',
        ]);

        // 8. Newly Registered User (No Email Verification)
        User::create([
            'name' => 'Alex New',
            'email' => 'new@360winestate.com',
            'phone' => '+1234567897',
            'password' => Hash::make('New@123'),
            'status' => User::STATUS_REGISTERED,
        ]);

        // 9. Admin User (Super User)
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@360winestate.com',
            'phone' => '+1234567898',
            'password' => Hash::make('Admin@123'),
            'email_verified_at' => now(),
            'membership_type' => User::MEMBERSHIP_OWNER,
            'status' => User::STATUS_APPROVED,
            'membership_selected_at' => now()->subDays(30),
            'kyc_submitted_at' => now()->subDays(29),
            'approved_at' => now()->subDays(28),
            'is_admin' => true,
        ]);

        // 10. Test User (For General Testing)
        User::create([
            'name' => 'Test User',
            'email' => 'test@360winestate.com',
            'phone' => '+1234567899',
            'password' => Hash::make('Test@123'),
            'email_verified_at' => now(),
            'membership_type' => User::MEMBERSHIP_INVESTOR,
            'status' => User::STATUS_APPROVED,
            'membership_selected_at' => now()->subDays(10),
            'kyc_submitted_at' => now()->subDays(9),
            'approved_at' => now()->subDays(7),
        ]);

        // Create dashboard statistics for approved users
        $this->createDashboardStats();

        $this->command->info('✅ Successfully seeded 10 test users!');
        $this->command->newLine();
        $this->command->info('Test Accounts:');
        $this->command->table(
            ['Email', 'Password', 'Type', 'Status'],
            [
                ['owner@360winestate.com', 'Owner@123', 'Owner', 'Approved'],
                ['investor@360winestate.com', 'Investor@123', 'Investor', 'Approved'],
                ['marketer@360winestate.com', 'Marketer@123', 'Marketer', 'Approved'],
                ['pending@360winestate.com', 'Pending@123', 'Investor', 'Under Review'],
                ['kyc@360winestate.com', 'Kyc@123', 'Owner', 'KYC Submitted'],
                ['selected@360winestate.com', 'Selected@123', 'Marketer', 'Membership Selected'],
                ['rejected@360winestate.com', 'Rejected@123', 'Investor', 'Rejected'],
                ['new@360winestate.com', 'New@123', 'None', 'Registered'],
                ['admin@360winestate.com', 'Admin@123', 'Owner', 'Approved (Admin)'],
                ['test@360winestate.com', 'Test@123', 'Investor', 'Approved'],
            ]
        );
    }

    /**
     * Create dashboard statistics for approved users
     */
    private function createDashboardStats(): void
    {
        // Owner stats for approved owners
        $owner = User::where('email', 'owner@360winestate.com')->first();
        if ($owner) {
            OwnerStats::create([
                'user_id' => $owner->id,
                'properties_count' => 5,
                'total_property_value' => 15000000,
                'rental_income' => 75000,
                'maintenance_tickets' => 3,
                'service_apartment_enrollments' => 2,
                'active_properties' => 5,
                'rented_properties' => 4,
                'monthly_revenue' => 75000,
            ]);
        }

        $admin = User::where('email', 'admin@360winestate.com')->first();
        if ($admin) {
            OwnerStats::create([
                'user_id' => $admin->id,
                'properties_count' => 10,
                'total_property_value' => 50000000,
                'rental_income' => 250000,
                'maintenance_tickets' => 5,
                'service_apartment_enrollments' => 8,
                'active_properties' => 10,
                'rented_properties' => 9,
                'monthly_revenue' => 250000,
            ]);
        }

        // Investor stats for approved investors
        $investor = User::where('email', 'investor@360winestate.com')->first();
        if ($investor) {
            InvestorStats::create([
                'user_id' => $investor->id,
                'investments_count' => 8,
                'total_invested' => 2000000,
                'total_roi' => 240000,
                'roi_percentage' => 12.00,
                'projects_funded' => 6,
                'wallet_balance' => 50000,
                'portfolio_value' => 2240000,
                'active_investments' => 6,
                'monthly_returns' => 20000,
                'completed_projects' => 2,
            ]);
        }

        $testUser = User::where('email', 'test@360winestate.com')->first();
        if ($testUser) {
            InvestorStats::create([
                'user_id' => $testUser->id,
                'investments_count' => 3,
                'total_invested' => 500000,
                'total_roi' => 45000,
                'roi_percentage' => 9.00,
                'projects_funded' => 3,
                'wallet_balance' => 25000,
                'portfolio_value' => 545000,
                'active_investments' => 3,
                'monthly_returns' => 4500,
                'completed_projects' => 0,
            ]);
        }

        // Marketer stats for approved marketers
        $marketer = User::where('email', 'marketer@360winestate.com')->first();
        if ($marketer) {
            MarketerStats::create([
                'user_id' => $marketer->id,
                'total_referrals' => 25,
                'verified_referrals' => 20,
                'converted_sales' => 12,
                'commissions_earned' => 180000,
                'team_size' => 5,
                'pending_referrals' => 5,
                'pending_commissions' => 25000,
                'this_month_commissions' => 35000,
                'this_month_referrals' => 8,
                'conversion_rate' => 60.00,
            ]);
        }

        $this->command->info('✅ Dashboard statistics created for approved users!');
    }
}
