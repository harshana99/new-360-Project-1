<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

/**
 * Role Seeder
 * 
 * Seeds the roles table with the three membership types:
 * - Owner (Property owners)
 * - Investor (Investment participants)
 * - Marketer (Referral marketers)
 * 
 * Run: php artisan db:seed --class=RoleSeeder
 */
class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            [
                'name' => 'owner',
                'description' => 'Property owners who list and manage properties on the platform. Can add properties, manage listings, track rental income, and handle property maintenance.',
            ],
            [
                'name' => 'investor',
                'description' => 'Investors who participate in property investment opportunities. Can browse properties, invest in projects, track ROI, manage portfolio, and withdraw earnings.',
            ],
            [
                'name' => 'marketer',
                'description' => 'Referral marketers who promote the platform and earn commissions. Can refer new users, track referrals, view conversion rates, and earn commissions on successful sign-ups.',
            ],
        ];

        foreach ($roles as $roleData) {
            Role::updateOrCreate(
                ['name' => $roleData['name']], // Check if exists
                $roleData // Create or update
            );
        }

        $this->command->info('✓ Roles seeded successfully!');
        $this->command->info('  - Owner');
        $this->command->info('  - Investor');
        $this->command->info('  - Marketer');
    }
}
