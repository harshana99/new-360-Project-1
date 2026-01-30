<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PropertyEarnings extends Model
{
    use HasFactory;

    protected $fillable = [
        'property_id',
        'user_id',
        'earnings_from_investments',
        'earnings_from_rentals',
        'total_earnings',
        'month',
    ];

    protected $casts = [
        'earnings_from_investments' => 'decimal:2',
        'earnings_from_rentals' => 'decimal:2',
        'total_earnings' => 'decimal:2',
        'month' => 'date',
    ];

    public function property()
    {
        return $this->belongsTo(Property::class);
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
