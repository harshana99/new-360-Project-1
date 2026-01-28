<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * KYC Submission Model
 * 
 * Manages KYC submission data and workflow
 */
class KycSubmission extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Status constants
     */
    const STATUS_DRAFT = 'draft';
    const STATUS_SUBMITTED = 'submitted';
    const STATUS_UNDER_REVIEW = 'under_review';
    const STATUS_APPROVED = 'approved';
    const STATUS_REJECTED = 'rejected';
    const STATUS_RESUBMISSION_REQUIRED = 'resubmission_required';

    /**
     * ID Type constants
     */
    const ID_TYPE_PASSPORT = 'passport';
    const ID_TYPE_DRIVERS_LICENSE = 'drivers_license';
    const ID_TYPE_NATIONAL_ID = 'national_id';
    const ID_TYPE_VOTER_ID = 'voter_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'full_name',
        'date_of_birth',
        'nationality',
        'address',
        'city',
        'state',
        'postal_code',
        'country',
        'id_type',
        'id_number',
        'id_expiry_date',
        'status',
        'reviewed_by',
        'submitted_at',
        'reviewed_at',
        'admin_notes',
        'rejection_reason',
        'submission_count',
        'previous_submission_id',
    ];

    /**
     * The attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'date_of_birth' => 'date',
            'id_expiry_date' => 'date',
            'submitted_at' => 'datetime',
            'reviewed_at' => 'datetime',
        ];
    }

    /**
     * Get the user that owns the KYC submission
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the admin who reviewed this submission
     */
    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    /**
     * Get the previous submission (for resubmissions)
     */
    public function previousSubmission(): BelongsTo
    {
        return $this->belongsTo(KycSubmission::class, 'previous_submission_id');
    }

    /**
     * Get all documents for this submission
     */
    public function documents(): HasMany
    {
        return $this->hasMany(KycDocument::class);
    }

    /**
     * Check if submission is in draft status
     */
    public function isDraft(): bool
    {
        return $this->status === self::STATUS_DRAFT;
    }

    /**
     * Check if submission is submitted
     */
    public function isSubmitted(): bool
    {
        return $this->status === self::STATUS_SUBMITTED;
    }

    /**
     * Check if submission is under review
     */
    public function isUnderReview(): bool
    {
        return $this->status === self::STATUS_UNDER_REVIEW;
    }

    /**
     * Check if submission is approved
     */
    public function isApproved(): bool
    {
        return $this->status === self::STATUS_APPROVED;
    }

    /**
     * Check if submission is rejected
     */
    public function isRejected(): bool
    {
        return $this->status === self::STATUS_REJECTED;
    }

    /**
     * Check if resubmission is required
     */
    public function requiresResubmission(): bool
    {
        return $this->status === self::STATUS_RESUBMISSION_REQUIRED;
    }

    /**
     * Submit the KYC for review
     */
    public function submit(): void
    {
        $this->update([
            'status' => self::STATUS_SUBMITTED,
            'submitted_at' => now(),
        ]);

        // Update user status
        $this->user->update([
            'status' => User::STATUS_KYC_SUBMITTED,
            'kyc_submitted_at' => now(),
        ]);
    }

    /**
     * Mark as under review
     */
    public function markUnderReview(int $reviewerId): void
    {
        $this->update([
            'status' => self::STATUS_UNDER_REVIEW,
            'reviewed_by' => $reviewerId,
        ]);

        // Update user status
        $this->user->update([
            'status' => User::STATUS_UNDER_REVIEW,
        ]);
    }

    /**
     * Approve the KYC submission
     */
    public function approve(int $reviewerId, ?string $notes = null): void
    {
        $this->update([
            'status' => self::STATUS_APPROVED,
            'reviewed_by' => $reviewerId,
            'reviewed_at' => now(),
            'admin_notes' => $notes,
            'rejection_reason' => null,
        ]);

        // Update user status to approved
        $this->user->approve();
    }

    /**
     * Reject the KYC submission
     */
    public function reject(int $reviewerId, string $reason, ?string $notes = null): void
    {
        $this->update([
            'status' => self::STATUS_REJECTED,
            'reviewed_by' => $reviewerId,
            'reviewed_at' => now(),
            'rejection_reason' => $reason,
            'admin_notes' => $notes,
        ]);

        // Update user status to rejected
        $this->user->reject($reason);
    }

    /**
     * Request resubmission
     */
    public function requestResubmission(int $reviewerId, string $reason, ?string $notes = null): void
    {
        $this->update([
            'status' => self::STATUS_RESUBMISSION_REQUIRED,
            'reviewed_by' => $reviewerId,
            'reviewed_at' => now(),
            'rejection_reason' => $reason,
            'admin_notes' => $notes,
        ]);
    }

    /**
     * Get status label for display
     */
    public function getStatusLabel(): string
    {
        return match($this->status) {
            self::STATUS_DRAFT => 'Draft',
            self::STATUS_SUBMITTED => 'Submitted',
            self::STATUS_UNDER_REVIEW => 'Under Review',
            self::STATUS_APPROVED => 'Approved',
            self::STATUS_REJECTED => 'Rejected',
            self::STATUS_RESUBMISSION_REQUIRED => 'Resubmission Required',
            default => 'Unknown',
        };
    }

    /**
     * Get status badge class
     */
    public function getStatusBadgeClass(): string
    {
        return match($this->status) {
            self::STATUS_APPROVED => 'bg-success',
            self::STATUS_REJECTED => 'bg-danger',
            self::STATUS_UNDER_REVIEW => 'bg-warning',
            self::STATUS_SUBMITTED => 'bg-info',
            self::STATUS_RESUBMISSION_REQUIRED => 'bg-warning',
            self::STATUS_DRAFT => 'bg-secondary',
            default => 'bg-secondary',
        };
    }

    /**
     * Get ID type label
     */
    public function getIdTypeLabel(): string
    {
        return match($this->id_type) {
            self::ID_TYPE_PASSPORT => 'Passport',
            self::ID_TYPE_DRIVERS_LICENSE => "Driver's License",
            self::ID_TYPE_NATIONAL_ID => 'National ID Card',
            self::ID_TYPE_VOTER_ID => 'Voter ID Card',
            default => 'Unknown',
        };
    }

    /**
     * Check if all required documents are uploaded
     */
    public function hasAllRequiredDocuments(): bool
    {
        $requiredTypes = ['id_front', 'id_back', 'proof_of_address', 'selfie'];
        $uploadedTypes = $this->documents()->pluck('document_type')->toArray();
        
        foreach ($requiredTypes as $type) {
            if (!in_array($type, $uploadedTypes)) {
                return false;
            }
        }
        
        return true;
    }
}
