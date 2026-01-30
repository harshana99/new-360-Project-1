<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\InvestorMetrics;
use Illuminate\Support\Facades\Hash;

class InvestorMetricsSeeder extends Seeder
{
    public function run()
    {
        $user = User::firstOrCreate(
            ['email' => 'investor@360winestate.com'],
            [
                'name' => 'Demo Investor',
                'password' => Hash::make('password'),
                'phone' => '0987654321',
                'membership_type' => 'investor',
                'status' => 'approved',
                'email_verified_at' => now(),
            ]
        );

        InvestorMetrics::updateOrCreate(
            ['user_id' => $user->id],
            [
                'total_invested' => 5200000,
                'total_returns' => 850000,
                'total_roi_percentage' => 18.5,
                'active_investments' => 8,
                'completed_investments' => 2,
                'pending_dividends' => 120000,
                'next_dividend_date' => now()->addDays(15),
                'calculated_at' => now(),
            ]
        );
    }
}
