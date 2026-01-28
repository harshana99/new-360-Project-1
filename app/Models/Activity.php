<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Activity Model
 * 
 * Tracks all user and admin activities for audit trail and compliance
 * 
 * @property int $id
 * @property int $user_id
 * @property int|null $admin_id
 * @property string $activity_type
 * @property string $description
 * @property string|null $ip_address
 * @property string|null $user_agent
 * @property array|null $metadata
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 */
class Activity extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'admin_id',
        'activity_type',
        'description',
        'ip_address',
        'user_agent',
        'metadata',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'metadata' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Activity type constants
     */
    const TYPE_REGISTRATION = 'registration';
    const TYPE_LOGIN = 'login';
    const TYPE_LOGOUT = 'logout';
    const TYPE_PROFILE_UPDATE = 'profile_update';
    const TYPE_PASSWORD_CHANGED = 'password_changed';
    const TYPE_KYC_SUBMITTED = 'kyc_submitted';
    const TYPE_KYC_APPROVED = 'kyc_approved';
    const TYPE_KYC_REJECTED = 'kyc_rejected';
    const TYPE_KYC_RESUBMITTED = 'kyc_resubmitted';
    const TYPE_MEMBERSHIP_SELECTED = 'membership_selected';
    const TYPE_ACCOUNT_SUSPENDED = 'account_suspended';
    const TYPE_ACCOUNT_ACTIVATED = 'account_activated';
    const TYPE_ADMIN_CREATED = 'admin_created';
    const TYPE_ADMIN_UPDATED = 'admin_updated';
    const TYPE_ADMIN_DEACTIVATED = 'admin_deactivated';
    const TYPE_ADMIN_ACTIVATED = 'admin_activated';
    const TYPE_USER_UPDATED_BY_ADMIN = 'user_updated_by_admin';
    const TYPE_OTHER = 'other';

    /**
     * Get the user who performed this activity
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the admin who performed this activity (if admin action)
     */
    public function admin(): BelongsTo
    {
        return $this->belongsTo(Admin::class);
    }

    /**
     * Create a new activity log entry
     *
     * @param int $userId
     * @param string $activityType
     * @param string $description
     * @param int|null $adminId
     * @param array|null $metadata
     * @return Activity
     */
    public static function log(
        int $userId,
        string $activityType,
        string $description,
        ?int $adminId = null,
        ?array $metadata = null
    ): Activity {
        return self::create([
            'user_id' => $userId,
            'admin_id' => $adminId,
            'activity_type' => $activityType,
            'description' => $description,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'metadata' => $metadata,
        ]);
    }

    /**
     * Get formatted activity type label
     *
     * @return string
     */
    public function getTypeLabel(): string
    {
        return match($this->activity_type) {
            self::TYPE_REGISTRATION => 'Account Created',
            self::TYPE_LOGIN => 'Login',
            self::TYPE_LOGOUT => 'Logout',
            self::TYPE_PROFILE_UPDATE => 'Profile Updated',
            self::TYPE_PASSWORD_CHANGED => 'Password Changed',
            self::TYPE_KYC_SUBMITTED => 'KYC Submitted',
            self::TYPE_KYC_APPROVED => 'KYC Approved',
            self::TYPE_KYC_REJECTED => 'KYC Rejected',
            self::TYPE_KYC_RESUBMITTED => 'KYC Resubmitted',
            self::TYPE_MEMBERSHIP_SELECTED => 'Membership Selected',
            self::TYPE_ACCOUNT_SUSPENDED => 'Account Suspended',
            self::TYPE_ACCOUNT_ACTIVATED => 'Account Activated',
            self::TYPE_ADMIN_CREATED => 'Admin Created',
            self::TYPE_ADMIN_UPDATED => 'Admin Updated',
            self::TYPE_ADMIN_DEACTIVATED => 'Admin Deactivated',
            self::TYPE_ADMIN_ACTIVATED => 'Admin Activated',
            self::TYPE_USER_UPDATED_BY_ADMIN => 'Updated by Admin',
            default => 'Other Activity',
        };
    }

    /**
     * Get icon class for activity type
     *
     * @return string
     */
    public function getIconClass(): string
    {
        return match($this->activity_type) {
            self::TYPE_REGISTRATION => 'bi-person-plus-fill text-success',
            self::TYPE_LOGIN => 'bi-box-arrow-in-right text-primary',
            self::TYPE_LOGOUT => 'bi-box-arrow-right text-secondary',
            self::TYPE_PROFILE_UPDATE => 'bi-pencil-square text-info',
            self::TYPE_PASSWORD_CHANGED => 'bi-shield-lock-fill text-warning',
            self::TYPE_KYC_SUBMITTED => 'bi-file-earmark-check text-primary',
            self::TYPE_KYC_APPROVED => 'bi-check-circle-fill text-success',
            self::TYPE_KYC_REJECTED => 'bi-x-circle-fill text-danger',
            self::TYPE_KYC_RESUBMITTED => 'bi-arrow-repeat text-warning',
            self::TYPE_MEMBERSHIP_SELECTED => 'bi-award-fill text-success',
            self::TYPE_ACCOUNT_SUSPENDED => 'bi-slash-circle text-danger',
            self::TYPE_ACCOUNT_ACTIVATED => 'bi-check-circle text-success',
            self::TYPE_ADMIN_CREATED => 'bi-shield-plus text-success',
            self::TYPE_ADMIN_UPDATED => 'bi-shield-check text-info',
            self::TYPE_ADMIN_DEACTIVATED => 'bi-shield-slash text-danger',
            self::TYPE_ADMIN_ACTIVATED => 'bi-shield-check text-success',
            self::TYPE_USER_UPDATED_BY_ADMIN => 'bi-person-gear text-warning',
            default => 'bi-info-circle text-secondary',
        };
    }

    /**
     * Scope to filter by activity type
     */
    public function scopeOfType($query, string $type)
    {
        return $query->where('activity_type', $type);
    }

    /**
     * Scope to filter by user
     */
    public function scopeForUser($query, int $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope to filter by admin
     */
    public function scopeByAdmin($query, int $adminId)
    {
        return $query->where('admin_id', $adminId);
    }

    /**
     * Scope to get recent activities
     */
    public function scopeRecent($query, int $limit = 10)
    {
        return $query->latest()->limit($limit);
    }
}
