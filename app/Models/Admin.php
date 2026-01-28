<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Admin Model
 * 
 * Represents an admin user with role-based permissions
 * 
 * Admin Roles:
 * - super_admin: Full platform control + can create other admins
 * - compliance_admin: KYC review and approval only
 * - finance_admin: Payments, commissions, financial reports
 * - content_admin: Projects, property listings, content management
 */
class Admin extends Model
{
    use HasFactory;

    /**
     * Admin role constants
     */
    const ROLE_SUPER_ADMIN = 'super_admin';
    const ROLE_COMPLIANCE_ADMIN = 'compliance_admin';
    const ROLE_FINANCE_ADMIN = 'finance_admin';
    const ROLE_CONTENT_ADMIN = 'content_admin';

    /**
     * Admin status constants
     */
    const STATUS_ACTIVE = 'active';
    const STATUS_INACTIVE = 'inactive';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'admin_role',
        'status',
        'created_by',
        'last_login',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'last_login' => 'datetime',
    ];

    /**
     * Get the user that owns this admin account
     *
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the super admin who created this admin
     *
     * @return BelongsTo
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get all admins created by this super admin
     *
     * @return HasMany
     */
    public function createdAdmins(): HasMany
    {
        return $this->hasMany(Admin::class, 'created_by', 'user_id');
    }

    /**
     * Check if this admin is a Super Admin
     *
     * @return bool
     */
    public function isSuperAdmin(): bool
    {
        return $this->admin_role === self::ROLE_SUPER_ADMIN;
    }

    /**
     * Check if this admin is a Compliance Admin
     *
     * @return bool
     */
    public function isComplianceAdmin(): bool
    {
        return $this->admin_role === self::ROLE_COMPLIANCE_ADMIN;
    }

    /**
     * Check if this admin is a Finance Admin
     *
     * @return bool
     */
    public function isFinanceAdmin(): bool
    {
        return $this->admin_role === self::ROLE_FINANCE_ADMIN;
    }

    /**
     * Check if this admin is a Content Admin
     *
     * @return bool
     */
    public function isContentAdmin(): bool
    {
        return $this->admin_role === self::ROLE_CONTENT_ADMIN;
    }

    /**
     * Check if this admin can create other admins
     * Only Super Admin can create other admins
     *
     * @return bool
     */
    public function canCreateAdmins(): bool
    {
        return $this->isSuperAdmin();
    }

    /**
     * Check if this admin can review KYC submissions
     * Super Admin and Compliance Admin can review KYC
     *
     * @return bool
     */
    public function canReviewKYC(): bool
    {
        return $this->isSuperAdmin() || $this->isComplianceAdmin();
    }

    /**
     * Check if this admin can manage payments
     * Only Finance Admin can manage payments
     *
     * @return bool
     */
    public function canManagePayments(): bool
    {
        return $this->isSuperAdmin() || $this->isFinanceAdmin();
    }

    /**
     * Check if this admin can manage content
     * Only Content Admin can manage content
     *
     * @return bool
     */
    public function canManageContent(): bool
    {
        return $this->isSuperAdmin() || $this->isContentAdmin();
    }

    /**
     * Check if this admin account is active
     *
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->status === self::STATUS_ACTIVE;
    }

    /**
     * Get the display name for the admin role
     *
     * @return string
     */
    public function getRoleLabel(): string
    {
        return match($this->admin_role) {
            self::ROLE_SUPER_ADMIN => 'Super Admin',
            self::ROLE_COMPLIANCE_ADMIN => 'Compliance Admin',
            self::ROLE_FINANCE_ADMIN => 'Finance Admin',
            self::ROLE_CONTENT_ADMIN => 'Content Admin',
            default => 'Unknown',
        };
    }

    /**
     * Get the badge class for the admin role
     *
     * @return string
     */
    public function getRoleBadgeClass(): string
    {
        return match($this->admin_role) {
            self::ROLE_SUPER_ADMIN => 'bg-danger',
            self::ROLE_COMPLIANCE_ADMIN => 'bg-primary',
            self::ROLE_FINANCE_ADMIN => 'bg-success',
            self::ROLE_CONTENT_ADMIN => 'bg-warning',
            default => 'bg-secondary',
        };
    }

    /**
     * Get the status badge class
     *
     * @return string
     */
    public function getStatusBadgeClass(): string
    {
        return $this->status === self::STATUS_ACTIVE ? 'bg-success' : 'bg-secondary';
    }

    /**
     * Update last login timestamp
     *
     * @return void
     */
    public function updateLastLogin(): void
    {
        $this->update(['last_login' => now()]);
    }

    /**
     * Get all available admin roles (excluding super_admin)
     * Super admin can only be created via seeder
     *
     * @return array
     */
    public static function getCreatableRoles(): array
    {
        return [
            self::ROLE_COMPLIANCE_ADMIN => 'Compliance Admin (KYC Review)',
            self::ROLE_FINANCE_ADMIN => 'Finance Admin (Payments & Commissions)',
            self::ROLE_CONTENT_ADMIN => 'Content Admin (Projects & Updates)',
        ];
    }

    /**
     * Scope to get only active admins
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->where('status', self::STATUS_ACTIVE);
    }

    /**
     * Scope to get admins by role
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $role
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByRole($query, string $role)
    {
        return $query->where('admin_role', $role);
    }
}
