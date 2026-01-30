<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\OwnerMetrics;
use Illuminate\Support\Facades\Hash;

class OwnerMetricsSeeder extends Seeder
{
    public function run()
    {
        // Create or find a test Owner
        $user = User::firstOrCreate(
            ['email' => 'owner@360winestate.com'],
            [
                'name' => 'Demo Owner',
                'password' => Hash::make('password'),
                'phone' => '1234567890',
                'membership_type' => 'owner',
                'status' => 'approved',
                'email_verified_at' => now(),
            ]
        );

        // Update metrics
        OwnerMetrics::updateOrCreate(
            ['user_id' => $user->id],
            [
                'total_properties_count' => 12,
                'total_investments_received' => 25000000,
                'total_earnings' => 2500000,
                'active_investments_count' => 4,
                'pending_approvals' => 2,
                'monthly_earnings' => 180000,
                'calculated_at' => now(),
            ]
        );
    }
}
