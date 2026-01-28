<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Maintenance Ticket Model
 * 
 * Manages property maintenance requests and tracking
 * 
 * @property int $id
 * @property int $property_id
 * @property int $reported_by
 * @property int|null $assigned_to
 * @property string $title
 * @property text $description
 * @property string $category
 * @property string $priority
 * @property string $status
 * @property decimal|null $estimated_cost
 * @property decimal|null $actual_cost
 * @property \DateTime|null $scheduled_date
 * @property \DateTime|null $completed_date
 * @property text|null $resolution_notes
 * @property \DateTime $created_at
 * @property \DateTime $updated_at
 * @property \DateTime|null $deleted_at
 */
class MaintenanceTicket extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Category constants
     */
    const CATEGORY_PLUMBING = 'plumbing';
    const CATEGORY_ELECTRICAL = 'electrical';
    const CATEGORY_HVAC = 'hvac';
    const CATEGORY_STRUCTURAL = 'structural';
    const CATEGORY_APPLIANCE = 'appliance';
    const CATEGORY_CLEANING = 'cleaning';
    const CATEGORY_PEST_CONTROL = 'pest_control';
    const CATEGORY_OTHER = 'other';

    /**
     * Priority constants
     */
    const PRIORITY_LOW = 'low';
    const PRIORITY_MEDIUM = 'medium';
    const PRIORITY_HIGH = 'high';
    const PRIORITY_URGENT = 'urgent';

    /**
     * Status constants
     */
    const STATUS_OPEN = 'open';
    const STATUS_IN_PROGRESS = 'in_progress';
    const STATUS_ON_HOLD = 'on_hold';
    const STATUS_COMPLETED = 'completed';
    const STATUS_CANCELLED = 'cancelled';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'property_id',
        'reported_by',
        'assigned_to',
        'title',
        'description',
        'category',
        'priority',
        'status',
        'estimated_cost',
        'actual_cost',
        'scheduled_date',
        'completed_date',
        'resolution_notes',
    ];

    /**
     * The attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'estimated_cost' => 'decimal:2',
            'actual_cost' => 'decimal:2',
            'scheduled_date' => 'datetime',
            'completed_date' => 'datetime',
        ];
    }

    /**
     * The attributes that should have default values.
     *
     * @var array
     */
    protected $attributes = [
        'status' => self::STATUS_OPEN,
        'priority' => self::PRIORITY_MEDIUM,
    ];

    /**
     * Get the property this ticket belongs to
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function property(): BelongsTo
    {
        return $this->belongsTo(Property::class);
    }

    /**
     * Get the user who reported the ticket
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function reporter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reported_by');
    }

    /**
     * Get the user assigned to the ticket
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function assignee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    /**
     * Get priority color
     *
     * @return string
     */
    public function getPriorityColor(): string
    {
        return match($this->priority) {
            self::PRIORITY_URGENT => 'danger',
            self::PRIORITY_HIGH => 'warning',
            self::PRIORITY_MEDIUM => 'info',
            self::PRIORITY_LOW => 'secondary',
            default => 'secondary',
        };
    }

    /**
     * Get status color
     *
     * @return string
     */
    public function getStatusColor(): string
    {
        return match($this->status) {
            self::STATUS_COMPLETED => 'success',
            self::STATUS_IN_PROGRESS => 'primary',
            self::STATUS_ON_HOLD => 'warning',
            self::STATUS_CANCELLED => 'secondary',
            self::STATUS_OPEN => 'info',
            default => 'secondary',
        };
    }

    /**
     * Check if ticket is open
     *
     * @return bool
     */
    public function isOpen(): bool
    {
        return $this->status === self::STATUS_OPEN;
    }

    /**
     * Check if ticket is completed
     *
     * @return bool
     */
    public function isCompleted(): bool
    {
        return $this->status === self::STATUS_COMPLETED;
    }

    /**
     * Mark ticket as completed
     *
     * @param string|null $notes
     * @param float|null $actualCost
     * @return bool
     */
    public function markAsCompleted(?string $notes = null, ?float $actualCost = null): bool
    {
        return $this->update([
            'status' => self::STATUS_COMPLETED,
            'completed_date' => now(),
            'resolution_notes' => $notes,
            'actual_cost' => $actualCost,
        ]);
    }
}
