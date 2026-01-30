<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Property;

class OwnerMetrics extends Model
{
    use HasFactory;

    protected $table = 'owner_metrics';

    protected $fillable = [
        'user_id',
        'total_properties_count',
        'total_investments_received',
        'total_earnings',
        'active_investments_count',
        'pending_approvals',
        'monthly_earnings',
        'calculated_at',
    ];

    protected $casts = [
        'calculated_at' => 'datetime',
        'total_investments_received' => 'decimal:2',
        'total_earnings' => 'decimal:2',
        'monthly_earnings' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Recalculate metrics from source data (Properties, Transactions)
     */
    /**
     * Recalculate metrics from source data (Properties, Transactions)
     */
    public function calculateMetrics()
    {
        $userId = $this->user_id;

        // Count properties by status
        $totalProps = Property::where('user_id', $userId)->count();
        $pending = Property::where('user_id', $userId)->whereIn('status', ['pending', 'under_review'])->count();
        $active = Property::where('user_id', $userId)->where('status', 'active')->count();
        $sold = Property::where('user_id', $userId)->where('status', 'sold')->count();

        // Update counts
        $this->total_properties_count = $totalProps;
        $this->pending_approvals = $pending;
        
        // Placeholder for investment counts (until Investment model is ready)
        // $this->active_investments_count = Investment::where('owner_id', $userId)->count(); 
        
        $this->calculated_at = now();
        $this->save();
        
        return $this;
    }

    /**
     * Get properties summary data
     */
    public function getPropertiesSummary()
    {
        $userId = $this->user_id;
        
        return [
            'active' => Property::where('user_id', $userId)->where('status', 'active')->count(),
            'pending' => Property::where('user_id', $userId)->whereIn('status', ['pending', 'under_review'])->count(),
            'sold' => Property::where('user_id', $userId)->where('status', 'sold')->count(),
        ];
    }

    /**
     * Get chart data for earnings
     */
    public function getEarningsChart()
    {
        // Placeholder data for chart
        return [
            'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
            'data' => [150000, 180000, 160000, 200000, 220000, 190000, 250000, 280000, 260000, 300000, 320000, 350000]
        ];
    }

    /**
     * Get recent transactions
     */
    public function getRecentTransactions($limit = 5)
    {
        // Placeholder for recent earnings transactions
        // Placeholder for recent earnings transactions
        return collect([
            (object)[
                'id' => 1,
                'property' => (object)['title' => 'Lekki Heights'],
                'amount' => 150000,
                'created_at' => now()->subDays(2),
                'type' => 'credit'
            ],
            (object)[
                'id' => 2,
                'property' => (object)['title' => 'Victoria Island Apt'],
                'amount' => 80000,
                'created_at' => now()->subDays(5),
                'type' => 'credit'
            ],
            (object)[
                'id' => 3,
                'property' => (object)['title' => 'Ikoyi Flat'],
                'amount' => 120000,
                'created_at' => now()->subDays(10),
                'type' => 'credit'
            ],
        ])->take($limit);
    }
}
