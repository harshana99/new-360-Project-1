<?php

namespace App\Http\Controllers;

use App\Models\KycSubmission;
use App\Models\KycDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * KYC Controller
 * 
 * Handles KYC submission and document uploads for users
 */
class KycController extends Controller
{
    /**
     * Show KYC submission form
     */
    public function create()
    {
        $user = Auth::user();
        
        // Check if user already has a KYC submission
        $kycSubmission = $user->kycSubmissions()->latest()->first();
        
        // If approved, redirect to dashboard
        if ($kycSubmission && $kycSubmission->isApproved()) {
            return redirect()->route('dashboard')
                ->with('info', 'Your KYC is already approved.');
        }
        
        // If under review, show status
        if ($kycSubmission && ($kycSubmission->isSubmitted() || $kycSubmission->isUnderReview())) {
            return redirect()->route('kyc.status')
                ->with('info', 'Your KYC is currently under review.');
        }
        
        return view('kyc.create', compact('kycSubmission'));
    }

    /**
     * Store KYC submission
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'full_name' => ['required', 'string', 'max:255'],
            'date_of_birth' => ['required', 'date', 'before:today', 'after:1900-01-01'],
            'nationality' => ['required', 'string', 'max:100'],
            'address' => ['required', 'string', 'max:500'],
            'city' => ['required', 'string', 'max:100'],
            'state' => ['required', 'string', 'max:100'],
            'postal_code' => ['required', 'string', 'max:20'],
            'country' => ['required', 'string', 'max:100'],
            'id_type' => ['required', 'in:passport,drivers_license,national_id,voter_id'],
            'id_number' => ['required', 'string', 'max:100'],
            'id_expiry_date' => ['nullable', 'date', 'after:today'],
            
            // Document uploads
            'id_front' => ['required', 'image', 'mimes:jpeg,jpg,png,pdf', 'max:5120'], // 5MB
            'id_back' => ['required', 'image', 'mimes:jpeg,jpg,png,pdf', 'max:5120'],
            'proof_of_address' => ['required', 'mimes:jpeg,jpg,png,pdf', 'max:5120'],
            'selfie' => ['required', 'image', 'mimes:jpeg,jpg,png', 'max:5120'],
            'additional_documents.*' => ['nullable', 'mimes:jpeg,jpg,png,pdf', 'max:5120'],
        ], [
            'date_of_birth.before' => 'Date of birth must be in the past.',
            'id_expiry_date.after' => 'ID must not be expired.',
            '*.max' => 'File size must not exceed 5MB.',
            '*.mimes' => 'File must be an image (JPEG, PNG) or PDF.',
        ]);

        $user = Auth::user();

        // Create KYC submission
        $kycSubmission = KycSubmission::create([
            'user_id' => $user->id,
            'full_name' => $validated['full_name'],
            'date_of_birth' => $validated['date_of_birth'],
            'nationality' => $validated['nationality'],
            'address' => $validated['address'],
            'city' => $validated['city'],
            'state' => $validated['state'],
            'postal_code' => $validated['postal_code'],
            'country' => $validated['country'],
            'id_type' => $validated['id_type'],
            'id_number' => $validated['id_number'],
            'id_expiry_date' => $validated['id_expiry_date'] ?? null,
            'status' => KycSubmission::STATUS_DRAFT,
        ]);

        // Upload required documents
        $this->uploadDocument($request->file('id_front'), $kycSubmission, 'id_front');
        $this->uploadDocument($request->file('id_back'), $kycSubmission, 'id_back');
        $this->uploadDocument($request->file('proof_of_address'), $kycSubmission, 'proof_of_address');
        $this->uploadDocument($request->file('selfie'), $kycSubmission, 'selfie');

        // Upload additional documents if any
        if ($request->hasFile('additional_documents')) {
            foreach ($request->file('additional_documents') as $file) {
                $this->uploadDocument($file, $kycSubmission, 'additional');
            }
        }

        // Submit the KYC
        $kycSubmission->submit();

        return redirect()->route('kyc.status')
            ->with('success', 'KYC submitted successfully! Your submission is now under review.');
    }

    /**
     * Show KYC status
     */
    public function status()
    {
        $user = Auth::user();
        $kycSubmission = $user->kycSubmissions()->latest()->first();

        if (!$kycSubmission) {
            return redirect()->route('kyc.create')
                ->with('info', 'Please submit your KYC documents.');
        }

        return view('kyc.status', compact('kycSubmission'));
    }

    /**
     * Show resubmission form
     */
    public function resubmit()
    {
        $user = Auth::user();
        $previousSubmission = $user->kycSubmissions()->latest()->first();

        if (!$previousSubmission || !$previousSubmission->requiresResubmission()) {
            return redirect()->route('kyc.status')
                ->with('error', 'Resubmission is not required.');
        }

        return view('kyc.resubmit', compact('previousSubmission'));
    }

    /**
     * Store resubmission
     */
    public function storeResubmission(Request $request)
    {
        $user = Auth::user();
        $previousSubmission = $user->kycSubmissions()->latest()->first();

        if (!$previousSubmission || !$previousSubmission->requiresResubmission()) {
            return redirect()->route('kyc.status')
                ->with('error', 'Resubmission is not required.');
        }

        $validated = $request->validate([
            'full_name' => ['required', 'string', 'max:255'],
            'date_of_birth' => ['required', 'date', 'before:today'],
            'nationality' => ['required', 'string', 'max:100'],
            'address' => ['required', 'string', 'max:500'],
            'city' => ['required', 'string', 'max:100'],
            'state' => ['required', 'string', 'max:100'],
            'postal_code' => ['required', 'string', 'max:20'],
            'country' => ['required', 'string', 'max:100'],
            'id_type' => ['required', 'in:passport,drivers_license,national_id,voter_id'],
            'id_number' => ['required', 'string', 'max:100'],
            'id_expiry_date' => ['nullable', 'date', 'after:today'],
            
            // Documents (optional for resubmission)
            'id_front' => ['nullable', 'image', 'mimes:jpeg,jpg,png,pdf', 'max:5120'],
            'id_back' => ['nullable', 'image', 'mimes:jpeg,jpg,png,pdf', 'max:5120'],
            'proof_of_address' => ['nullable', 'mimes:jpeg,jpg,png,pdf', 'max:5120'],
            'selfie' => ['nullable', 'image', 'mimes:jpeg,jpg,png', 'max:5120'],
        ]);

        // Create new submission
        $kycSubmission = KycSubmission::create([
            'user_id' => $user->id,
            'full_name' => $validated['full_name'],
            'date_of_birth' => $validated['date_of_birth'],
            'nationality' => $validated['nationality'],
            'address' => $validated['address'],
            'city' => $validated['city'],
            'state' => $validated['state'],
            'postal_code' => $validated['postal_code'],
            'country' => $validated['country'],
            'id_type' => $validated['id_type'],
            'id_number' => $validated['id_number'],
            'id_expiry_date' => $validated['id_expiry_date'] ?? null,
            'status' => KycSubmission::STATUS_DRAFT,
            'submission_count' => $previousSubmission->submission_count + 1,
            'previous_submission_id' => $previousSubmission->id,
        ]);

        // Upload new documents if provided
        if ($request->hasFile('id_front')) {
            $this->uploadDocument($request->file('id_front'), $kycSubmission, 'id_front');
        }
        if ($request->hasFile('id_back')) {
            $this->uploadDocument($request->file('id_back'), $kycSubmission, 'id_back');
        }
        if ($request->hasFile('proof_of_address')) {
            $this->uploadDocument($request->file('proof_of_address'), $kycSubmission, 'proof_of_address');
        }
        if ($request->hasFile('selfie')) {
            $this->uploadDocument($request->file('selfie'), $kycSubmission, 'selfie');
        }

        // Submit the KYC
        $kycSubmission->submit();

        return redirect()->route('kyc.status')
            ->with('success', 'KYC resubmitted successfully!');
    }

    /**
     * Upload a document
     */
    private function uploadDocument($file, KycSubmission $kycSubmission, string $type): KycDocument
    {
        $originalFilename = $file->getClientOriginalName();
        $extension = $file->getClientOriginalExtension();
        $storedFilename = Str::random(40) . '.' . $extension;
        
        // Store in private storage
        $path = $file->storeAs(
            'kyc-documents/' . $kycSubmission->user_id,
            $storedFilename,
            'private'
        );

        return KycDocument::create([
            'kyc_submission_id' => $kycSubmission->id,
            'user_id' => $kycSubmission->user_id,
            'document_type' => $type,
            'original_filename' => $originalFilename,
            'stored_filename' => $storedFilename,
            'file_path' => $path,
            'mime_type' => $file->getMimeType(),
            'file_size' => $file->getSize(),
        ]);
    }

    /**
     * Download a document
     */
    public function downloadDocument(KycDocument $document)
    {
        // Check if user owns this document
        if ($document->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access to document.');
        }

        return Storage::disk('private')->download(
            $document->file_path,
            $document->original_filename
        );
    }
}
