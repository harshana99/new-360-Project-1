<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;

/**
 * User Model - 360WinEstate Platform
 * 
 * Comprehensive user management with authentication, membership types,
 * KYC tracking, diaspora support, and role-based access control.
 * 
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string $phone
 * @property string $password
 * @property string|null $country
 * @property string|null $city
 * @property string|null $address
 * @property string $user_status
 * @property string|null $membership_type
 * @property \DateTime|null $email_verified_at
 * @property \DateTime|null $phone_verified_at
 * @property bool $is_active
 * @property bool $two_factor_enabled
 * @property string|null $preferred_language
 * @property string|null $timezone
 * @property bool $is_admin
 * @property string|null $role
 * @property \DateTime|null $membership_selected_at
 * @property \DateTime|null $kyc_submitted_at
 * @property \DateTime|null $approved_at
 * @property \DateTime|null $rejected_at
 * @property string|null $rejection_reason
 * @property \DateTime $created_at
 * @property \DateTime $updated_at
 * @property \DateTime|null $deleted_at
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
     * User status constants
     */
    const STATUS_REGISTERED = 'registered';
    const STATUS_MEMBERSHIP_SELECTED = 'membership_selected';
    const STATUS_KYC_SUBMITTED = 'kyc_submitted';
    const STATUS_UNDER_REVIEW = 'under_review';
    const STATUS_APPROVED = 'approved';
    const STATUS_REJECTED = 'rejected';

    /**
     * Default language
     */
    const DEFAULT_LANGUAGE = 'en';

    /**
     * Default timezone
     */
    const DEFAULT_TIMEZONE = 'UTC';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        // Basic Information
        'name',
        'email',
        'phone',
        'password',
        
        // Diaspora Tracking
        'country',
        'city',
        'address',
        
        // Status & Membership
        'user_status',
        'membership_type',
        
        // Verification
        'email_verified_at',
        'phone_verified_at',
        
        // Security & Preferences
        'is_active',
        'two_factor_enabled',
        'preferred_language',
        'timezone',
        
        // Admin & Role
        'is_admin',
        'role',
        
        // Workflow Timestamps
        'membership_selected_at',
        'kyc_submitted_at',
        'approved_at',
        'rejected_at',
        'rejection_reason',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_secret',
        'two_factor_recovery_codes',
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
            'phone_verified_at' => 'datetime',
            'password' => 'hashed',
            'membership_selected_at' => 'datetime',
            'kyc_submitted_at' => 'datetime',
            'approved_at' => 'datetime',
            'rejected_at' => 'datetime',
            'is_admin' => 'boolean',
            'is_active' => 'boolean',
            'two_factor_enabled' => 'boolean',
        ];
    }

    /**
     * The attributes that should have default values.
     *
     * @var array
     */
    protected $attributes = [
        'user_status' => self::STATUS_REGISTERED,
        'is_active' => true,
        'two_factor_enabled' => false,
        'is_admin' => false,
        'preferred_language' => self::DEFAULT_LANGUAGE,
        'timezone' => self::DEFAULT_TIMEZONE,
    ];

    /*
    |--------------------------------------------------------------------------
    | RELATIONSHIPS
    |--------------------------------------------------------------------------
    */

    /**
     * Get the roles associated with the user through pivot table
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'user_roles')
            ->withTimestamps()
            ->withPivot(['assigned_at', 'assigned_by']);
    }

    /**
     * Get all addresses for the user
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function addresses(): HasMany
    {
        return $this->hasMany(Address::class);
    }

    /**
     * Get the user's wallet
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function wallet(): HasOne
    {
        return $this->hasOne(Wallet::class);
    }

    /**
     * Get all KYC submissions for this user
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function kycSubmissions(): HasMany
    {
        return $this->hasMany(KycSubmission::class);
    }

    /**
     * Get the latest KYC submission
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function latestKycSubmission(): HasOne
    {
        return $this->hasOne(KycSubmission::class)->latestOfMany();
    }

    /**
     * Get all documents uploaded by the user
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function documents(): HasMany
    {
        return $this->hasMany(Document::class);
    }

    /**
     * Get owner statistics
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function ownerStats(): HasOne
    {
        return $this->hasOne(OwnerStats::class);
    }

    /**
     * Get investor statistics
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function investorStats(): HasOne
    {
        return $this->hasOne(InvestorStats::class);
    }

    /**
     * Get marketer statistics
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function marketerStats(): HasOne
    {
        return $this->hasOne(MarketerStats::class);
    }

    /*
    |--------------------------------------------------------------------------
    | ACCESSORS & MUTATORS
    |--------------------------------------------------------------------------
    */

    /**
     * Get the user's full name (accessor)
     *
     * @return string
     */
    public function getFullNameAttribute(): string
    {
        return $this->name;
    }

    /**
     * Get the user's full address (accessor)
     *
     * @return string|null
     */
    public function getFullAddressAttribute(): ?string
    {
        $parts = array_filter([
            $this->address,
            $this->city,
            $this->country,
        ]);

        return !empty($parts) ? implode(', ', $parts) : null;
    }

    /**
     * Set the user's password (mutator)
     * Automatically hashes the password
     *
     * @param string $value
     * @return void
     */
    public function setPasswordAttribute($value): void
    {
        if (!empty($value)) {
            $this->attributes['password'] = Hash::needsRehash($value) 
                ? Hash::make($value) 
                : $value;
        }
    }

    /**
     * Set the user's email (mutator)
     * Automatically converts to lowercase
     *
     * @param string $value
     * @return void
     */
    public function setEmailAttribute($value): void
    {
        $this->attributes['email'] = strtolower(trim($value));
    }

    /**
     * Set the user's phone (mutator)
     * Removes spaces and special characters
     *
     * @param string|null $value
     * @return void
     */
    public function setPhoneAttribute($value): void
    {
        if (!empty($value)) {
            $this->attributes['phone'] = preg_replace('/[^0-9+]/', '', $value);
        }
    }

    /*
    |--------------------------------------------------------------------------
    | STATUS CHECK METHODS
    |--------------------------------------------------------------------------
    */

    /**
     * Check if user is approved
     *
     * @return bool
     */
    public function isApproved(): bool
    {
        return $this->user_status === self::STATUS_APPROVED;
    }

    /**
     * Check if user is registered (initial state)
     *
     * @return bool
     */
    public function isRegistered(): bool
    {
        return $this->user_status === self::STATUS_REGISTERED;
    }

    /**
     * Check if user has submitted KYC
     *
     * @return bool
     */
    public function hasSubmittedKyc(): bool
    {
        return in_array($this->user_status, [
            self::STATUS_KYC_SUBMITTED,
            self::STATUS_UNDER_REVIEW,
            self::STATUS_APPROVED,
            self::STATUS_REJECTED,
        ]);
    }

    /**
     * Check if user is under review
     *
     * @return bool
     */
    public function isUnderReview(): bool
    {
        return $this->user_status === self::STATUS_UNDER_REVIEW;
    }

    /**
     * Check if user is rejected
     *
     * @return bool
     */
    public function isRejected(): bool
    {
        return $this->user_status === self::STATUS_REJECTED;
    }

    /**
     * Check if user is active
     *
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->is_active === true;
    }

    /**
     * Check if user has verified email
     *
     * @return bool
     */
    public function hasVerifiedEmail(): bool
    {
        return $this->email_verified_at !== null;
    }

    /**
     * Check if user has verified phone
     *
     * @return bool
     */
    public function hasVerifiedPhone(): bool
    {
        return $this->phone_verified_at !== null;
    }

    /**
     * Check if user has two-factor authentication enabled
     *
     * @return bool
     */
    public function hasTwoFactorEnabled(): bool
    {
        return $this->two_factor_enabled === true;
    }

    /*
    |--------------------------------------------------------------------------
    | ROLE & PERMISSION METHODS
    |--------------------------------------------------------------------------
    */

    /**
     * Check if user is an admin
     *
     * @return bool
     */
    public function isAdmin(): bool
    {
        return $this->is_admin === true;
    }

    /**
     * Check if user is an owner
     *
     * @return bool
     */
    public function isOwner(): bool
    {
        return $this->membership_type === self::MEMBERSHIP_OWNER;
    }

    /**
     * Check if user is an investor
     *
     * @return bool
     */
    public function isInvestor(): bool
    {
        return $this->membership_type === self::MEMBERSHIP_INVESTOR;
    }

    /**
     * Check if user is a marketer
     *
     * @return bool
     */
    public function isMarketer(): bool
    {
        return $this->membership_type === self::MEMBERSHIP_MARKETER;
    }

    /**
     * Check if user has a specific role
     *
     * @param string|array $role Role name(s) to check
     * @return bool
     */
    public function hasRole($role): bool
    {
        if (is_array($role)) {
            return $this->roles()->whereIn('name', $role)->exists();
        }

        return $this->roles()->where('name', $role)->exists();
    }

    /**
     * Check if user can access a specific feature
     *
     * @param string $feature Feature name to check
     * @return bool
     */
    public function canAccess(string $feature): bool
    {
        // Admin has access to everything
        if ($this->isAdmin()) {
            return true;
        }

        // User must be approved to access features
        if (!$this->isApproved()) {
            return false;
        }

        // User must be active
        if (!$this->isActive()) {
            return false;
        }

        // Feature-based access control
        $featureAccess = [
            'dashboard' => true, // All approved users
            'properties' => $this->isOwner(),
            'investments' => $this->isInvestor(),
            'referrals' => $this->isMarketer(),
            'marketplace' => true, // All approved users
            'wallet' => true, // All approved users
            'kyc' => true, // All users can access KYC
            'admin_panel' => $this->isAdmin(),
        ];

        return $featureAccess[$feature] ?? false;
    }

    /**
     * Assign a role to the user
     *
     * @param string|Role $role
     * @param int|null $assignedBy
     * @return void
     */
    public function assignRole($role, ?int $assignedBy = null): void
    {
        $roleId = $role instanceof Role ? $role->id : Role::where('name', $role)->first()?->id;

        if ($roleId) {
            $this->roles()->syncWithoutDetaching([
                $roleId => [
                    'assigned_at' => now(),
                    'assigned_by' => $assignedBy,
                ]
            ]);
        }
    }

    /**
     * Remove a role from the user
     *
     * @param string|Role $role
     * @return void
     */
    public function removeRole($role): void
    {
        $roleId = $role instanceof Role ? $role->id : Role::where('name', $role)->first()?->id;

        if ($roleId) {
            $this->roles()->detach($roleId);
        }
    }

    /*
    |--------------------------------------------------------------------------
    | UTILITY METHODS
    |--------------------------------------------------------------------------
    */

    /**
     * Get or create stats for user based on membership type
     *
     * @return OwnerStats|InvestorStats|MarketerStats|null
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
     * Get status label for display
     *
     * @return string
     */
    public function getStatusLabel(): string
    {
        return match($this->user_status) {
            self::STATUS_REGISTERED => 'Registered',
            self::STATUS_MEMBERSHIP_SELECTED => 'Membership Selected',
            self::STATUS_KYC_SUBMITTED => 'KYC Submitted',
            self::STATUS_UNDER_REVIEW => 'Under Review',
            self::STATUS_APPROVED => 'Approved',
            self::STATUS_REJECTED => 'Rejected',
            default => 'Unknown',
        };
    }

    /**
     * Get status badge class for UI
     *
     * @return string
     */
    public function getStatusBadgeClass(): string
    {
        return match($this->user_status) {
            self::STATUS_APPROVED => 'bg-success',
            self::STATUS_REJECTED => 'bg-danger',
            self::STATUS_UNDER_REVIEW => 'bg-warning',
            self::STATUS_KYC_SUBMITTED => 'bg-info',
            self::STATUS_MEMBERSHIP_SELECTED => 'bg-primary',
            self::STATUS_REGISTERED => 'bg-secondary',
            default => 'bg-secondary',
        };
    }

    /**
     * Get membership type label
     *
     * @return string|null
     */
    public function getMembershipLabel(): ?string
    {
        return match($this->membership_type) {
            self::MEMBERSHIP_OWNER => 'Property Owner',
            self::MEMBERSHIP_INVESTOR => 'Investor',
            self::MEMBERSHIP_MARKETER => 'Marketer',
            default => null,
        };
    }

    /**
     * Get user's initials for avatar
     *
     * @return string
     */
    public function getInitials(): string
    {
        $words = explode(' ', $this->name);
        
        if (count($words) >= 2) {
            return strtoupper(substr($words[0], 0, 1) . substr($words[1], 0, 1));
        }
        
        return strtoupper(substr($this->name, 0, 2));
    }

    /**
     * Check if user is from diaspora (outside home country)
     *
     * @param string $homeCountry Default home country
     * @return bool
     */
    public function isDiaspora(string $homeCountry = 'India'): bool
    {
        return $this->country && $this->country !== $homeCountry;
    }

    /**
     * Get user's local time
     *
     * @return \DateTime
     */
    public function getLocalTime(): \DateTime
    {
        $timezone = $this->timezone ?? self::DEFAULT_TIMEZONE;
        return now()->setTimezone($timezone);
    }

    /**
     * Send email verification notification
     *
     * @return void
     */
    public function sendEmailVerificationNotification(): void
    {
        // Override to customize email verification notification
        parent::sendEmailVerificationNotification();
    }

    /**
     * Mark phone as verified
     *
     * @return bool
     */
    public function markPhoneAsVerified(): bool
    {
        return $this->forceFill([
            'phone_verified_at' => now(),
        ])->save();
    }

    /**
     * Activate user account
     *
     * @return bool
     */
    public function activate(): bool
    {
        return $this->update(['is_active' => true]);
    }

    /**
     * Deactivate user account
     *
     * @return bool
     */
    public function deactivate(): bool
    {
        return $this->update(['is_active' => false]);
    }

    /**
     * Enable two-factor authentication
     *
     * @return bool
     */
    public function enableTwoFactor(): bool
    {
        return $this->update(['two_factor_enabled' => true]);
    }

    /**
     * Disable two-factor authentication
     *
     * @return bool
     */
    public function disableTwoFactor(): bool
    {
        return $this->update(['two_factor_enabled' => false]);
    }
}
