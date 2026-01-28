<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * User Model
 * 
 * Handles user authentication, membership types, and status tracking
 * for the 360WinEstate platform.
 */
class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable, SoftDeletes;

    /**
     * Membership type constants
     */
    const MEMBERSHIP_OWNER = 'owner';
    const MEMBERSHIP_INVESTOR = 'investor';
    const MEMBERSHIP_MARKETER = 'marketer';

    /**
     * Status constants
     */
    const STATUS_REGISTERED = 'registered';
    const STATUS_MEMBERSHIP_SELECTED = 'membership_selected';
    const STATUS_KYC_SUBMITTED = 'kyc_submitted';
    const STATUS_UNDER_REVIEW = 'under_review';
    const STATUS_APPROVED = 'approved';
    const STATUS_REJECTED = 'rejected';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
        'membership_type',
        'status',
        'membership_selected_at',
        'kyc_submitted_at',
        'approved_at',
        'rejected_at',
        'rejection_reason',
        'is_admin',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'membership_selected_at' => 'datetime',
            'kyc_submitted_at' => 'datetime',
            'approved_at' => 'datetime',
            'rejected_at' => 'datetime',
            'is_admin' => 'boolean',
        ];
    }

    /**
     * Check if user has selected a membership type
     */
    public function hasMembership(): bool
    {
        return !is_null($this->membership_type);
    }

    /**
     * Check if user is approved
     */
    public function isApproved(): bool
    {
        return $this->status === self::STATUS_APPROVED;
    }

    /**
     * Check if user is rejected
     */
    public function isRejected(): bool
    {
        return $this->status === self::STATUS_REJECTED;
    }

    /**
     * Check if user is under review
     */
    public function isUnderReview(): bool
    {
        return $this->status === self::STATUS_UNDER_REVIEW;
    }

    /**
     * Check if user can access the main dashboard
     */
    public function canAccessDashboard(): bool
    {
        return $this->hasVerifiedEmail() 
            && $this->hasMembership() 
            && $this->isApproved();
    }

    /**
     * Select membership type
     */
    public function selectMembership(string $type): void
    {
        $this->update([
            'membership_type' => $type,
            'status' => self::STATUS_MEMBERSHIP_SELECTED,
            'membership_selected_at' => now(),
        ]);
    }

    /**
     * Get the admin record for this user
     * 
     * @return HasOne
     */
    public function admin(): HasOne
    {
        return $this->hasOne(Admin::class);
    }

    /**
     * Check if user is a super admin
     * 
     * @return bool
     */
    public function isSuperAdmin(): bool
    {
        return $this->admin && $this->admin->isSuperAdmin();
    }

    /**
     * Check if user is a compliance admin
     * 
     * @return bool
     */
    public function isComplianceAdmin(): bool
    {
        return $this->admin && $this->admin->isComplianceAdmin();
    }

    /**
     * Check if user is a finance admin
     * 
     * @return bool
     */
    public function isFinanceAdmin(): bool
    {
        return $this->admin && $this->admin->isFinanceAdmin();
    }

    /**
     * Check if user is a content admin
     * 
     * @return bool
     */
    public function isContentAdmin(): bool
    {
        return $this->admin && $this->admin->isContentAdmin();
    }



    /**
     * Submit KYC
     */
    public function submitKyc(): void
    {
        $this->update([
            'status' => self::STATUS_KYC_SUBMITTED,
            'kyc_submitted_at' => now(),
        ]);
    }

    /**
     * Approve user
     */
    public function approve(): void
    {
        $this->update([
            'status' => self::STATUS_APPROVED,
            'approved_at' => now(),
            'rejected_at' => null,
            'rejection_reason' => null,
        ]);
    }

    /**
     * Reject user
     */
    public function reject(string $reason): void
    {
        $this->update([
            'status' => self::STATUS_REJECTED,
            'rejected_at' => now(),
            'rejection_reason' => $reason,
            'approved_at' => null,
        ]);
    }

    /**
     * Get membership type label
     */
    public function getMembershipTypeLabel(): string
    {
        return match($this->membership_type) {
            self::MEMBERSHIP_OWNER => 'Property Owner',
            self::MEMBERSHIP_INVESTOR => 'Investor',
            self::MEMBERSHIP_MARKETER => 'Marketer',
            default => 'Not Selected',
        };
    }

    /**
     * Get status label
     */
    public function getStatusLabel(): string
    {
        return match($this->status) {
            self::STATUS_REGISTERED => 'Registered',
            self::STATUS_MEMBERSHIP_SELECTED => 'Membership Selected',
            self::STATUS_KYC_SUBMITTED => 'KYC Submitted',
            self::STATUS_UNDER_REVIEW => 'Under Review',
            self::STATUS_APPROVED => 'Approved',
            self::STATUS_REJECTED => 'Rejected',
            'suspended' => 'Suspended',
            default => 'Unknown',
        };
    }

    /**
     * Get status badge class for UI
     */
    public function getStatusBadgeClass(): string
    {
        return match($this->status) {
            self::STATUS_APPROVED => 'bg-success',
            self::STATUS_REJECTED, 'suspended' => 'bg-danger',
            self::STATUS_UNDER_REVIEW, self::STATUS_KYC_SUBMITTED => 'bg-warning',
            self::STATUS_MEMBERSHIP_SELECTED => 'bg-primary',
            self::STATUS_REGISTERED => 'bg-info',
            default => 'bg-secondary',
        };
    }

    /**
     * Get all KYC submissions for this user
     */
    public function kycSubmissions(): HasMany
    {
        return $this->hasMany(KycSubmission::class);
    }

    /**
     * Get the latest KYC submission
     */
    public function latestKycSubmission(): HasOne
    {
        return $this->hasOne(KycSubmission::class)->latestOfMany();
    }

    /**
     * Get owner statistics
     */
    public function ownerStats(): HasOne
    {
        return $this->hasOne(OwnerStats::class);
    }

    /**
     * Get investor statistics
     */
    public function investorStats(): HasOne
    {
        return $this->hasOne(InvestorStats::class);
    }

    /**
     * Get marketer statistics
     */
    public function marketerStats(): HasOne
    {
        return $this->hasOne(MarketerStats::class);
    }

    /**
     * Check if user is an admin
     */
    public function isAdmin(): bool
    {
        return $this->admin()->exists();
    }

    /**
     * Check if user is an owner
     */
    public function isOwner(): bool
    {
        return $this->membership_type === self::MEMBERSHIP_OWNER;
    }

    /**
     * Check if user is an investor
     */
    public function isInvestor(): bool
    {
        return $this->membership_type === self::MEMBERSHIP_INVESTOR;
    }

    /**
     * Check if user is a marketer
     */
    public function isMarketer(): bool
    {
        return $this->membership_type === self::MEMBERSHIP_MARKETER;
    }

    /**
     * Get or create stats for user based on membership type
     */
    public function getOrCreateStats()
    {
        if ($this->isOwner()) {
            return $this->ownerStats()->firstOrCreate(['user_id' => $this->id]);
        } elseif ($this->isInvestor()) {
            return $this->investorStats()->firstOrCreate(['user_id' => $this->id]);
        } elseif ($this->isMarketer()) {
            return $this->marketerStats()->firstOrCreate(['user_id' => $this->id]);
        }
        
        return null;
    }

    /**
     * MODULE 11: USER MANAGEMENT METHODS
     */

    /**
     * Get user's activities
     */
    public function activities(): HasMany
    {
        return $this->hasMany(Activity::class);
    }

    /**
     * Get user's latest KYC submission
     */
    public function getKYCSubmission()
    {
        return $this->kycSubmissions()->latest()->first();
    }

    /**
     * Check if user's KYC is approved
     */
    public function isKYCApproved(): bool
    {
        return $this->status === self::STATUS_APPROVED;
    }

    /**
     * Check if user has submitted KYC
     */
    public function hasKYC(): bool
    {
        return $this->kycSubmissions()->exists();
    }

    /**
     * Get all account activities for this user
     */
    public function getAccountActivities(int $limit = null)
    {
        $query = $this->activities()->with('admin')->latest();
        
        if ($limit) {
            return $query->limit($limit)->get();
        }
        
        return $query->paginate(20);
    }

    /**
     * Suspend user account
     */
    public function suspend(string $reason = null, ?int $adminId = null): void
    {
        $this->update(['status' => 'suspended']);
        
        // Log activity
        Activity::log(
            $this->id,
            Activity::TYPE_ACCOUNT_SUSPENDED,
            $reason ?? 'Account suspended by admin',
            $adminId,
            ['reason' => $reason]
        );
    }

    /**
     * Activate user account
     */
    public function activate(?int $adminId = null): void
    {
        // Restore to appropriate status based on KYC
        $status = $this->isKYCApproved() ? self::STATUS_APPROVED : self::STATUS_MEMBERSHIP_SELECTED;
        
        $this->update(['status' => $status]);
        
        // Log activity
        Activity::log(
            $this->id,
            Activity::TYPE_ACCOUNT_ACTIVATED,
            'Account activated by admin',
            $adminId
        );
    }

    /**
     * Check if user can edit their profile
     */
    public function canEditProfile(): bool
    {
        return $this->status !== 'suspended';
    }

    /**
     * Get last login timestamp
     */
    public function getLastLogin()
    {
        $lastLogin = $this->activities()
            ->where('activity_type', Activity::TYPE_LOGIN)
            ->latest()
            ->first();
        
        return $lastLogin ? $lastLogin->created_at : null;
    }

    /**
     * Check if account is suspended
     */
    public function isSuspended(): bool
    {
        return $this->status === 'suspended';
    }

    /**
     * Log user activity
     */
    public function logActivity(string $type, string $description, ?array $metadata = null): Activity
    {
        return Activity::log($this->id, $type, $description, null, $metadata);
    }
}
