<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KycSubmission;
use App\Models\KycDocument;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

/**
 * Admin KYC Controller
 * 
 * Handles KYC review and approval for administrators
 */
class AdminKycController extends Controller
{
    /**
     * Show all KYC submissions
     */
    public function index(Request $request)
    {
        $status = $request->get('status', 'all');
        
        $query = KycSubmission::with(['user', 'reviewer'])
            ->latest();

        if ($status !== 'all') {
            $query->where('status', $status);
        }

        $submissions = $query->paginate(20);
        
        $stats = [
            'total' => KycSubmission::count(),
            'submitted' => KycSubmission::where('status', KycSubmission::STATUS_SUBMITTED)->count(),
            'under_review' => KycSubmission::where('status', KycSubmission::STATUS_UNDER_REVIEW)->count(),
            'approved' => KycSubmission::where('status', KycSubmission::STATUS_APPROVED)->count(),
            'rejected' => KycSubmission::where('status', KycSubmission::STATUS_REJECTED)->count(),
            'resubmission' => KycSubmission::where('status', KycSubmission::STATUS_RESUBMISSION_REQUIRED)->count(),
        ];

        return view('admin.kyc.index', compact('submissions', 'stats', 'status'));
    }

    /**
     * Show KYC submission details for review
     */
    public function show(KycSubmission $kycSubmission)
    {
        $kycSubmission->load(['user', 'documents', 'reviewer', 'previousSubmission']);
        
        return view('admin.kyc.show', compact('kycSubmission'));
    }

    /**
     * Mark KYC as under review
     */
    public function markUnderReview(KycSubmission $kycSubmission)
    {
        if ($kycSubmission->isSubmitted()) {
            $kycSubmission->markUnderReview(Auth::id());
            
            return redirect()->back()
                ->with('success', 'KYC marked as under review.');
        }

        return redirect()->back()
            ->with('error', 'KYC cannot be marked as under review.');
    }

    /**
     * Approve KYC submission
     */
    public function approve(Request $request, KycSubmission $kycSubmission)
    {
        $validated = $request->validate([
            'admin_notes' => ['nullable', 'string', 'max:1000'],
        ]);

        if ($kycSubmission->isSubmitted() || $kycSubmission->isUnderReview()) {
            $kycSubmission->approve(Auth::id(), $validated['admin_notes'] ?? null);
            
            // TODO: Send approval email notification
            
            return redirect()->route('admin.kyc.index')
                ->with('success', 'KYC approved successfully! User has been granted access.');
        }

        return redirect()->back()
            ->with('error', 'KYC cannot be approved at this time.');
    }

    /**
     * Reject KYC submission
     */
    public function reject(Request $request, KycSubmission $kycSubmission)
    {
        $validated = $request->validate([
            'rejection_reason' => ['required', 'string', 'max:1000'],
            'admin_notes' => ['nullable', 'string', 'max:1000'],
        ]);

        if ($kycSubmission->isSubmitted() || $kycSubmission->isUnderReview()) {
            $kycSubmission->reject(
                Auth::id(),
                $validated['rejection_reason'],
                $validated['admin_notes'] ?? null
            );
            
            // TODO: Send rejection email notification
            
            return redirect()->route('admin.kyc.index')
                ->with('success', 'KYC rejected. User has been notified.');
        }

        return redirect()->back()
            ->with('error', 'KYC cannot be rejected at this time.');
    }

    /**
     * Request resubmission
     */
    public function requestResubmission(Request $request, KycSubmission $kycSubmission)
    {
        $validated = $request->validate([
            'rejection_reason' => ['required', 'string', 'max:1000'],
            'admin_notes' => ['nullable', 'string', 'max:1000'],
        ]);

        if ($kycSubmission->isSubmitted() || $kycSubmission->isUnderReview()) {
            $kycSubmission->requestResubmission(
                Auth::id(),
                $validated['rejection_reason'],
                $validated['admin_notes'] ?? null
            );
            
            // TODO: Send resubmission request email
            
            return redirect()->route('admin.kyc.index')
                ->with('success', 'Resubmission requested. User has been notified.');
        }

        return redirect()->back()
            ->with('error', 'Cannot request resubmission at this time.');
    }

    /**
     * Verify a specific document
     */
    public function verifyDocument(Request $request, KycDocument $document)
    {
        $validated = $request->validate([
            'verification_notes' => ['nullable', 'string', 'max:500'],
        ]);

        $document->verify(Auth::id(), $validated['verification_notes'] ?? null);

        return redirect()->back()
            ->with('success', 'Document verified successfully.');
    }

    /**
     * View/download document
     */
    public function viewDocument(KycDocument $document)
    {
        if (!Storage::disk('private')->exists($document->file_path)) {
            abort(404, 'Document not found.');
        }

        return Storage::disk('private')->response(
            $document->file_path,
            $document->original_filename
        );
    }

    /**
     * Download document
     */
    public function downloadDocument(KycDocument $document)
    {
        if (!Storage::disk('private')->exists($document->file_path)) {
            abort(404, 'Document not found.');
        }

        return Storage::disk('private')->download(
            $document->file_path,
            $document->original_filename
        );
    }

    /**
     * Show KYC statistics dashboard
     */
    public function dashboard()
    {
        $stats = [
            'total_submissions' => KycSubmission::count(),
            'pending_review' => KycSubmission::whereIn('status', [
                KycSubmission::STATUS_SUBMITTED,
                KycSubmission::STATUS_UNDER_REVIEW
            ])->count(),
            'approved_today' => KycSubmission::where('status', KycSubmission::STATUS_APPROVED)
                ->whereDate('reviewed_at', today())
                ->count(),
            'rejected_today' => KycSubmission::where('status', KycSubmission::STATUS_REJECTED)
                ->whereDate('reviewed_at', today())
                ->count(),
            'average_review_time' => $this->getAverageReviewTime(),
        ];

        $recentSubmissions = KycSubmission::with('user')
            ->latest()
            ->take(10)
            ->get();

        $pendingSubmissions = KycSubmission::with('user')
            ->whereIn('status', [KycSubmission::STATUS_SUBMITTED, KycSubmission::STATUS_UNDER_REVIEW])
            ->latest()
            ->take(5)
            ->get();

        return view('admin.kyc.dashboard', compact('stats', 'recentSubmissions', 'pendingSubmissions'));
    }

    /**
     * Calculate average review time in hours
     */
    private function getAverageReviewTime(): float
    {
        $submissions = KycSubmission::whereNotNull('submitted_at')
            ->whereNotNull('reviewed_at')
            ->get();

        if ($submissions->isEmpty()) {
            return 0;
        }

        $totalHours = 0;
        foreach ($submissions as $submission) {
            $totalHours += $submission->submitted_at->diffInHours($submission->reviewed_at);
        }

        return round($totalHours / $submissions->count(), 1);
    }

    /**
     * Bulk approve KYC submissions
     */
    public function bulkApprove(Request $request)
    {
        $validated = $request->validate([
            'submission_ids' => ['required', 'array'],
            'submission_ids.*' => ['exists:kyc_submissions,id'],
            'admin_notes' => ['nullable', 'string', 'max:1000'],
        ]);

        $count = 0;
        foreach ($validated['submission_ids'] as $id) {
            $submission = KycSubmission::find($id);
            if ($submission && ($submission->isSubmitted() || $submission->isUnderReview())) {
                $submission->approve(Auth::id(), $validated['admin_notes'] ?? null);
                $count++;
            }
        }

        return redirect()->back()
            ->with('success', "Successfully approved {$count} KYC submission(s).");
    }

    /**
     * Bulk reject KYC submissions
     */
    public function bulkReject(Request $request)
    {
        $validated = $request->validate([
            'submission_ids' => ['required', 'array'],
            'submission_ids.*' => ['exists:kyc_submissions,id'],
            'rejection_reason' => ['required', 'string', 'max:1000'],
            'admin_notes' => ['nullable', 'string', 'max:1000'],
        ]);

        $count = 0;
        foreach ($validated['submission_ids'] as $id) {
            $submission = KycSubmission::find($id);
            if ($submission && ($submission->isSubmitted() || $submission->isUnderReview())) {
                $submission->reject(
                    Auth::id(),
                    $validated['rejection_reason'],
                    $validated['admin_notes'] ?? null
                );
                $count++;
            }
        }

        return redirect()->back()
            ->with('success', "Successfully rejected {$count} KYC submission(s).");
    }
}
