<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MarketerMetrics extends Model
{
    use HasFactory;

    protected $table = 'marketer_metrics';

    protected $fillable = [
        'user_id',
        'total_referrals',
        'total_commission',
        'active_team_members',
        'pending_commission',
        'monthly_commission',
        'commission_level',
        'team_tree_depth',
        'calculated_at',
    ];

    protected $casts = [
        'calculated_at' => 'datetime',
        'total_commission' => 'decimal:2',
        'pending_commission' => 'decimal:2',
        'monthly_commission' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function calculateMetrics()
    {
        $this->calculated_at = now();
        $this->save();
        return $this;
    }

    public function getTeamHierarchy()
    {
        // Placeholder list of team members
        return collect([
            ['name' => 'Direct Referral 1', 'sales' => 5],
            ['name' => 'Direct Referral 2', 'sales' => 3],
            ['name' => 'Direct Referral 3', 'sales' => 10],
            ['name' => 'Direct Referral 4', 'sales' => 0],
        ]);
    }

    public function getCommissionBreakdown()
    {
        return [
            'direct' => 60,
            'team' => 30,
            'bonus' => 10
        ];
    }

    public function getLeaderboardPosition()
    {
        return [
            'rank' => 15,
            'top_percentile' => 5
        ];
    }
}
