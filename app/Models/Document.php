<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

/**
 * Document Model
 * 
 * Manages user-uploaded documents (KYC, contracts, etc.)
 * 
 * @property int $id
 * @property int $user_id
 * @property string $type
 * @property string $title
 * @property string $file_name
 * @property string $file_path
 * @property string $file_type
 * @property int $file_size
 * @property string $status
 * @property string|null $verified_by
 * @property \DateTime|null $verified_at
 * @property string|null $rejection_reason
 * @property \DateTime $created_at
 * @property \DateTime $updated_at
 * @property \DateTime|null $deleted_at
 */
class Document extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Document type constants
     */
    const TYPE_ID_PROOF = 'id_proof';
    const TYPE_ADDRESS_PROOF = 'address_proof';
    const TYPE_BANK_STATEMENT = 'bank_statement';
    const TYPE_CONTRACT = 'contract';
    const TYPE_PROPERTY_DEED = 'property_deed';
    const TYPE_OTHER = 'other';

    /**
     * Document status constants
     */
    const STATUS_PENDING = 'pending';
    const STATUS_VERIFIED = 'verified';
    const STATUS_REJECTED = 'rejected';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'type',
        'title',
        'file_name',
        'file_path',
        'file_type',
        'file_size',
        'status',
        'verified_by',
        'verified_at',
        'rejection_reason',
    ];

    /**
     * The attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'verified_at' => 'datetime',
            'file_size' => 'integer',
        ];
    }

    /**
     * The attributes that should have default values.
     *
     * @var array
     */
    protected $attributes = [
        'status' => self::STATUS_PENDING,
    ];

    /**
     * Get the user that owns the document
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the admin who verified the document
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function verifier(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    /**
     * Get the full URL to the document
     *
     * @return string
     */
    public function getUrlAttribute(): string
    {
        return Storage::url($this->file_path);
    }

    /**
     * Get formatted file size
     *
     * @return string
     */
    public function getFormattedSizeAttribute(): string
    {
        $bytes = $this->file_size;
        
        if ($bytes >= 1073741824) {
            return number_format($bytes / 1073741824, 2) . ' GB';
        } elseif ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            return number_format($bytes / 1024, 2) . ' KB';
        }
        
        return $bytes . ' bytes';
    }

    /**
     * Check if document is verified
     *
     * @return bool
     */
    public function isVerified(): bool
    {
        return $this->status === self::STATUS_VERIFIED;
    }

    /**
     * Check if document is pending
     *
     * @return bool
     */
    public function isPending(): bool
    {
        return $this->status === self::STATUS_PENDING;
    }

    /**
     * Check if document is rejected
     *
     * @return bool
     */
    public function isRejected(): bool
    {
        return $this->status === self::STATUS_REJECTED;
    }

    /**
     * Verify the document
     *
     * @param int $verifiedBy User ID of verifier
     * @return bool
     */
    public function verify(int $verifiedBy): bool
    {
        return $this->update([
            'status' => self::STATUS_VERIFIED,
            'verified_by' => $verifiedBy,
            'verified_at' => now(),
            'rejection_reason' => null,
        ]);
    }

    /**
     * Reject the document
     *
     * @param string $reason
     * @param int $rejectedBy User ID of rejector
     * @return bool
     */
    public function reject(string $reason, int $rejectedBy): bool
    {
        return $this->update([
            'status' => self::STATUS_REJECTED,
            'verified_by' => $rejectedBy,
            'verified_at' => now(),
            'rejection_reason' => $reason,
        ]);
    }

    /**
     * Delete the document file from storage
     *
     * @return bool
     */
    public function deleteFile(): bool
    {
        if (Storage::exists($this->file_path)) {
            return Storage::delete($this->file_path);
        }
        
        return false;
    }

    /**
     * Boot method to handle model events
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        // Delete file when document is deleted
        static::deleting(function ($document) {
            $document->deleteFile();
        });
    }
}
