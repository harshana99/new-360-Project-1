@extends('layouts.app')

@section('title', 'KYC Status - 360WinEstate')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <!-- Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="mb-0">
                    <i class="bi bi-file-earmark-check me-2"></i>KYC Verification Status
                </h2>
                <a href="{{ route('user.profile') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-1"></i>Back to Profile
                </a>
            </div>

            <!-- Status Badge -->
            <div class="card mb-4">
                <div class="card-body text-center py-5">
                    @if($kycSubmission->status === 'approved')
                        <i class="bi bi-check-circle-fill text-success" style="font-size: 5rem;"></i>
                        <h3 class="text-success mt-3">KYC APPROVED</h3>
                        <p class="text-muted">Your identity has been verified successfully</p>
                    @elseif($kycSubmission->status === 'rejected')
                        <i class="bi bi-x-circle-fill text-danger" style="font-size: 5rem;"></i>
                        <h3 class="text-danger mt-3">KYC REJECTED</h3>
                        <p class="text-muted">Your KYC submission was not approved</p>
                    @elseif(in_array($kycSubmission->status, ['submitted', 'under_review']))
                        <i class="bi bi-clock-fill text-warning" style="font-size: 5rem;"></i>
                        <h3 class="text-warning mt-3">UNDER REVIEW</h3>
                        <p class="text-muted">Your documents are being reviewed by our team</p>
                    @else
                        <i class="bi bi-info-circle-fill text-info" style="font-size: 5rem;"></i>
                        <h3 class="text-info mt-3">{{ strtoupper(str_replace('_', ' ', $kycSubmission->status)) }}</h3>
                    @endif
                </div>
            </div>

            <!-- Submission Details -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-info-circle me-2"></i>Submission Details</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small">Submission ID</label>
                            <p class="mb-0 fw-semibold">KYC_{{ str_pad($kycSubmission->id, 6, '0', STR_PAD_LEFT) }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small">Submitted On</label>
                            <p class="mb-0 fw-semibold">{{ $kycSubmission->submitted_at->format('F d, Y \a\t h:i A') }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small">Status</label>
                            <p class="mb-0">
                                <span class="badge bg-{{ $kycSubmission->status === 'approved' ? 'success' : ($kycSubmission->status === 'rejected' ? 'danger' : 'warning') }}">
                                    {{ ucfirst(str_replace('_', ' ', $kycSubmission->status)) }}
                                </span>
                            </p>
                        </div>
                        @if($kycSubmission->reviewed_at)
                            <div class="col-md-6 mb-3">
                                <label class="text-muted small">Reviewed On</label>
                                <p class="mb-0 fw-semibold">{{ $kycSubmission->reviewed_at->format('F d, Y \a\t h:i A') }}</p>
                            </div>
                        @endif
                        @if($kycSubmission->reviewed_by)
                            <div class="col-md-6 mb-3">
                                <label class="text-muted small">Reviewed By</label>
                                <p class="mb-0 fw-semibold">{{ $kycSubmission->reviewer->user->name ?? 'Admin' }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Submitted Information -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-person-vcard me-2"></i>Submitted Information</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small">ID Type</label>
                            <p class="mb-0 fw-semibold">{{ ucfirst(str_replace('_', ' ', $kycSubmission->id_type)) }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small">ID Number</label>
                            <p class="mb-0 fw-semibold">{{ substr($kycSubmission->id_number, 0, 3) . str_repeat('*', strlen($kycSubmission->id_number) - 6) . substr($kycSubmission->id_number, -3) }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small">Full Name</label>
                            <p class="mb-0 fw-semibold">{{ $kycSubmission->full_name }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small">Date of Birth</label>
                            <p class="mb-0 fw-semibold">{{ \Carbon\Carbon::parse($kycSubmission->date_of_birth)->format('F d, Y') }}</p>
                        </div>
                        <div class="col-12 mb-3">
                            <label class="text-muted small">Address</label>
                            <p class="mb-0">
                                {{ $kycSubmission->address }}<br>
                                {{ $kycSubmission->city }}, {{ $kycSubmission->state }} {{ $kycSubmission->postal_code }}<br>
                                {{ $kycSubmission->country }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Documents -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-file-earmark-image me-2"></i>Uploaded Documents</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- ID Document -->
                        <div class="col-md-6 mb-3">
                            <h6>ID Document</h6>
                            <div class="document-preview border rounded p-2">
                                <img src="{{ asset('storage/' . $kycSubmission->id_image_path) }}" 
                                     alt="ID Document" 
                                     class="img-fluid rounded"
                                     style="max-height: 300px; width: 100%; object-fit: contain;">
                            </div>
                            <a href="{{ asset('storage/' . $kycSubmission->id_image_path) }}" 
                               download 
                               class="btn btn-sm btn-outline-primary mt-2 w-100">
                                <i class="bi bi-download me-1"></i>Download ID Document
                            </a>
                        </div>

                        <!-- Address Proof -->
                        <div class="col-md-6 mb-3">
                            <h6>Address Proof</h6>
                            <div class="document-preview border rounded p-2">
                                <img src="{{ asset('storage/' . $kycSubmission->address_proof_path) }}" 
                                     alt="Address Proof" 
                                     class="img-fluid rounded"
                                     style="max-height: 300px; width: 100%; object-fit: contain;">
                            </div>
                            <a href="{{ asset('storage/' . $kycSubmission->address_proof_path) }}" 
                               download 
                               class="btn btn-sm btn-outline-primary mt-2 w-100">
                                <i class="bi bi-download me-1"></i>Download Address Proof
                            </a>
                        </div>
                    </div>

                    <div class="text-center mt-3">
                        <a href="{{ route('user.kyc.download') }}" class="btn btn-success">
                            <i class="bi bi-download me-1"></i>Download All Documents (ZIP)
                        </a>
                    </div>
                </div>
            </div>

            <!-- Admin Notes / Rejection Reason -->
            @if($kycSubmission->admin_notes || $kycSubmission->rejection_reason)
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="bi bi-chat-left-text me-2"></i>Admin Feedback</h5>
                    </div>
                    <div class="card-body">
                        @if($kycSubmission->rejection_reason)
                            <div class="alert alert-danger">
                                <h6 class="alert-heading">
                                    <i class="bi bi-exclamation-triangle me-2"></i>Rejection Reason
                                </h6>
                                <p class="mb-0">{{ $kycSubmission->rejection_reason }}</p>
                            </div>
                        @endif

                        @if($kycSubmission->admin_notes)
                            <div class="alert alert-info">
                                <h6 class="alert-heading">
                                    <i class="bi bi-info-circle me-2"></i>Admin Notes
                                </h6>
                                <p class="mb-0">{{ $kycSubmission->admin_notes }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            @endif

            <!-- Actions -->
            @if(in_array($kycSubmission->status, ['rejected', 'resubmission_required']))
                <div class="card border-warning">
                    <div class="card-body text-center">
                        <h5>Resubmission Required</h5>
                        <p class="text-muted">Please review the feedback above and resubmit your KYC documents.</p>
                        <a href="{{ route('user.kyc.resubmit') }}" class="btn btn-warning btn-lg">
                            <i class="bi bi-arrow-repeat me-1"></i>Resubmit KYC
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<style>
    .document-preview {
        background: #f8f9fa;
        min-height: 200px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
</style>
@endsection
