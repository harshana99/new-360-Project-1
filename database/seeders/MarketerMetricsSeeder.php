<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\MarketerMetrics;
use Illuminate\Support\Facades\Hash;

class MarketerMetricsSeeder extends Seeder
{
    public function run()
    {
        $user = User::firstOrCreate(
            ['email' => 'marketer@360winestate.com'],
            [
                'name' => 'Demo Marketer',
                'password' => Hash::make('password'),
                'phone' => '1122334455',
                'membership_type' => 'marketer',
                'status' => 'approved',
                'email_verified_at' => now(),
            ]
        );

        MarketerMetrics::updateOrCreate(
            ['user_id' => $user->id],
            [
                'total_referrals' => 45,
                'total_commission' => 450000,
                'active_team_members' => 30,
                'pending_commission' => 12000,
                'monthly_commission' => 50000,
                'commission_level' => 'gold',
                'team_tree_depth' => 3,
                'calculated_at' => now(),
            ]
        );
    }
}
