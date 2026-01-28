<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

/**
 * KYC Document Model
 * 
 * Manages uploaded KYC documents
 */
class KycDocument extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Document type constants
     */
    const TYPE_ID_FRONT = 'id_front';
    const TYPE_ID_BACK = 'id_back';
    const TYPE_PROOF_OF_ADDRESS = 'proof_of_address';
    const TYPE_SELFIE = 'selfie';
    const TYPE_ADDITIONAL = 'additional';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'kyc_submission_id',
        'user_id',
        'document_type',
        'original_filename',
        'stored_filename',
        'file_path',
        'mime_type',
        'file_size',
        'is_verified',
        'verified_at',
        'verified_by',
        'verification_notes',
    ];

    /**
     * The attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_verified' => 'boolean',
            'verified_at' => 'datetime',
        ];
    }

    /**
     * Get the KYC submission that owns the document
     */
    public function kycSubmission(): BelongsTo
    {
        return $this->belongsTo(KycSubmission::class);
    }

    /**
     * Get the user that owns the document
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the admin who verified this document
     */
    public function verifier(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    /**
     * Get the full file path
     */
    public function getFullPath(): string
    {
        return storage_path('app/' . $this->file_path);
    }

    /**
     * Get the file URL
     */
    public function getFileUrl(): string
    {
        return Storage::url($this->file_path);
    }

    /**
     * Check if document is an image
     */
    public function isImage(): bool
    {
        return str_starts_with($this->mime_type, 'image/');
    }

    /**
     * Check if document is a PDF
     */
    public function isPdf(): bool
    {
        return $this->mime_type === 'application/pdf';
    }

    /**
     * Get human-readable file size
     */
    public function getFormattedFileSize(): string
    {
        $bytes = $this->file_size;
        
        if ($bytes >= 1073741824) {
            return number_format($bytes / 1073741824, 2) . ' GB';
        } elseif ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            return number_format($bytes / 1024, 2) . ' KB';
        } else {
            return $bytes . ' bytes';
        }
    }

    /**
     * Get document type label
     */
    public function getDocumentTypeLabel(): string
    {
        return match($this->document_type) {
            self::TYPE_ID_FRONT => 'ID Front',
            self::TYPE_ID_BACK => 'ID Back',
            self::TYPE_PROOF_OF_ADDRESS => 'Proof of Address',
            self::TYPE_SELFIE => 'Selfie',
            self::TYPE_ADDITIONAL => 'Additional Document',
            default => 'Unknown',
        };
    }

    /**
     * Verify the document
     */
    public function verify(int $verifierId, ?string $notes = null): void
    {
        $this->update([
            'is_verified' => true,
            'verified_at' => now(),
            'verified_by' => $verifierId,
            'verification_notes' => $notes,
        ]);
    }

    /**
     * Delete the physical file
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
     */
    protected static function boot()
    {
        parent::boot();

        // Delete physical file when model is deleted
        static::deleting(function ($document) {
            $document->deleteFile();
        });
    }
}
