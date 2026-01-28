<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

/**
 * Permission Seeder
 * 
 * Seeds permissions and attaches them to appropriate roles
 * 
 * Run: php artisan db:seed --class=PermissionSeeder
 */
class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Define all permissions
        $permissions = [
            // Property Management (Owner)
            [
                'name' => 'view_properties',
                'module' => 'properties',
                'description' => 'View properties dashboard and listings',
            ],
            [
                'name' => 'add_properties',
                'module' => 'properties',
                'description' => 'Add new properties to the platform',
            ],
            [
                'name' => 'edit_properties',
                'module' => 'properties',
                'description' => 'Edit existing property details',
            ],
            [
                'name' => 'delete_properties',
                'module' => 'properties',
                'description' => 'Delete properties from the platform',
            ],
            [
                'name' => 'manage_maintenance',
                'module' => 'properties',
                'description' => 'Manage property maintenance tickets',
            ],
            
            // Investment (Investor)
            [
                'name' => 'view_investments',
                'module' => 'investments',
                'description' => 'View investment opportunities and portfolio',
            ],
            [
                'name' => 'make_investments',
                'module' => 'investments',
                'description' => 'Invest in property projects',
            ],
            [
                'name' => 'view_roi',
                'module' => 'investments',
                'description' => 'View return on investment reports',
            ],
            [
                'name' => 'manage_portfolio',
                'module' => 'investments',
                'description' => 'Manage investment portfolio',
            ],
            
            // Marketplace (All)
            [
                'name' => 'view_marketplace',
                'module' => 'marketplace',
                'description' => 'Browse marketplace listings',
            ],
            [
                'name' => 'create_listings',
                'module' => 'marketplace',
                'description' => 'Create marketplace listings',
            ],
            [
                'name' => 'bid_auctions',
                'module' => 'marketplace',
                'description' => 'Participate in property auctions',
            ],
            
            // Marketing/Referrals (Marketer)
            [
                'name' => 'view_referrals',
                'module' => 'marketing',
                'description' => 'View referral dashboard and statistics',
            ],
            [
                'name' => 'generate_referral_links',
                'module' => 'marketing',
                'description' => 'Generate unique referral links',
            ],
            [
                'name' => 'view_commissions',
                'module' => 'marketing',
                'description' => 'View commission earnings',
            ],
            [
                'name' => 'track_conversions',
                'module' => 'marketing',
                'description' => 'Track referral conversions',
            ],
            
            // Wallet & Earnings (All)
            [
                'name' => 'view_earnings',
                'module' => 'wallet',
                'description' => 'View earnings dashboard',
            ],
            [
                'name' => 'withdraw_funds',
                'module' => 'wallet',
                'description' => 'Withdraw funds from wallet',
            ],
            [
                'name' => 'view_transactions',
                'module' => 'wallet',
                'description' => 'View transaction history',
            ],
        ];

        // Create all permissions
        foreach ($permissions as $permissionData) {
            Permission::updateOrCreate(
                ['name' => $permissionData['name']],
                $permissionData
            );
        }

        $this->command->info('✓ Permissions created successfully!');

        // Attach permissions to roles
        $this->attachPermissionsToRoles();
    }

    /**
     * Attach permissions to appropriate roles
     */
    private function attachPermissionsToRoles(): void
    {
        // Get roles
        $ownerRole = Role::where('name', 'owner')->first();
        $investorRole = Role::where('name', 'investor')->first();
        $marketerRole = Role::where('name', 'marketer')->first();

        if (!$ownerRole || !$investorRole || !$marketerRole) {
            $this->command->error('✗ Roles not found! Please run RoleSeeder first.');
            return;
        }

        // Owner permissions
        $ownerPermissions = Permission::whereIn('name', [
            'view_properties',
            'add_properties',
            'edit_properties',
            'delete_properties',
            'manage_maintenance',
            'view_marketplace',
            'create_listings',
            'view_earnings',
            'withdraw_funds',
            'view_transactions',
        ])->pluck('id');

        $ownerRole->permissions()->sync($ownerPermissions);
        $this->command->info('✓ Owner permissions attached');

        // Investor permissions
        $investorPermissions = Permission::whereIn('name', [
            'view_investments',
            'make_investments',
            'view_roi',
            'manage_portfolio',
            'view_marketplace',
            'bid_auctions',
            'view_earnings',
            'withdraw_funds',
            'view_transactions',
        ])->pluck('id');

        $investorRole->permissions()->sync($investorPermissions);
        $this->command->info('✓ Investor permissions attached');

        // Marketer permissions
        $marketerPermissions = Permission::whereIn('name', [
            'view_referrals',
            'generate_referral_links',
            'view_commissions',
            'track_conversions',
            'view_marketplace',
            'view_earnings',
            'withdraw_funds',
            'view_transactions',
        ])->pluck('id');

        $marketerRole->permissions()->sync($marketerPermissions);
        $this->command->info('✓ Marketer permissions attached');

        $this->command->info('✓ All permissions attached to roles successfully!');
    }
}
