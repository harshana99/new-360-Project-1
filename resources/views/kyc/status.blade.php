@extends('layouts.app')

@section('title', 'KYC Status - 360WinEstate')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <!-- Header -->
            <div class="text-center mb-5">
                <h2 class="fw-bold text-navy mb-3">Verification Status</h2>
                <p class="text-muted">Track your KYC verification progress</p>
            </div>

            <!-- Status Card -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body p-4 p-md-5">
                    <!-- Status Badge -->
                    <div class="text-center mb-4">
                        @if($kycSubmission->isApproved())
                            <div class="status-icon text-success mb-3">
                                <i class="bi bi-check-circle-fill" style="font-size: 5rem;"></i>
                            </div>
                            <h3 class="text-success fw-bold mb-2">✓ APPROVED</h3>
                        @elseif($kycSubmission->isRejected())
                            <div class="status-icon text-danger mb-3">
                                <i class="bi bi-x-circle-fill" style="font-size: 5rem;"></i>
                            </div>
                            <h3 class="text-danger fw-bold mb-2">✗ REJECTED</h3>
                        @elseif($kycSubmission->requiresResubmission())
                            <div class="status-icon text-warning mb-3">
                                <i class="bi bi-exclamation-triangle-fill" style="font-size: 5rem;"></i>
                            </div>
                            <h3 class="text-warning fw-bold mb-2">⚠ RESUBMISSION REQUIRED</h3>
                        @elseif($kycSubmission->isUnderReview())
                            <div class="status-icon text-info mb-3">
                                <i class="bi bi-hourglass-split" style="font-size: 5rem;"></i>
                            </div>
                            <h3 class="text-info fw-bold mb-2">⏳ UNDER REVIEW</h3>
                        @else
                            <div class="status-icon text-primary mb-3">
                                <i class="bi bi-clock-history" style="font-size: 5rem;"></i>
                            </div>
                            <h3 class="text-primary fw-bold mb-2">📝 SUBMITTED</h3>
                        @endif
                        
                        <span class="badge {{ $kycSubmission->getStatusBadgeClass() }} fs-6 px-4 py-2">
                            {{ $kycSubmission->getStatusLabel() }}
                        </span>
                    </div>

                    <hr class="my-4">

                    <!-- Submission Details -->
                    <div class="row text-center mb-4">
                        <div class="col-md-6 mb-3">
                            <small class="text-muted d-block">Submission Number</small>
                            <strong class="text-navy">{{ $kycSubmission->id ?? 'N/A' }}</strong>
                        </div>
                        <div class="col-md-6 mb-3">
                            <small class="text-muted d-block">Submitted</small>
                            <strong class="text-navy">{{ $kycSubmission->submitted_at?->diffForHumans() ?? 'N/A' }}</strong>
                        </div>
                        @if($kycSubmission->reviewed_at)
                        <div class="col-md-6 mb-3">
                            <small class="text-muted d-block">Reviewed</small>
                            <strong class="text-navy">{{ $kycSubmission->reviewed_at->diffForHumans() }}</strong>
                        </div>
                        @endif
                        @if($kycSubmission->reviewer)
                        <div class="col-md-6 mb-3">
                            <small class="text-muted d-block">Reviewed By</small>
                            <strong class="text-navy">{{ $kycSubmission->reviewer->name }}</strong>
                        </div>
                        @endif
                    </div>

                    <hr class="my-4">

                    <!-- Status-specific Messages -->
                    @if($kycSubmission->isApproved())
                        <!-- APPROVED -->
                        <div class="alert alert-success border-0 mb-4">
                            <div class="d-flex align-items-start">
                                <i class="bi bi-check-circle-fill fs-3 me-3"></i>
                                <div>
                                    <h5 class="fw-bold mb-2">Congratulations!</h5>
                                    <p class="mb-0">
                                        Your verification is complete! Your account is now fully activated. 
                                        You have access to all features based on your membership type.
                                    </p>
                                </div>
                            </div>
                        </div>

                        @if($kycSubmission->admin_notes)
                        <div class="alert alert-info border-0 mb-4">
                            <strong>Admin Notes:</strong><br>
                            {{ $kycSubmission->admin_notes }}
                        </div>
                        @endif

                        <div class="d-grid">
                            <a href="{{ route('dashboard') }}" class="btn btn-success btn-lg">
                                <i class="bi bi-speedometer2 me-2"></i>Go to Dashboard
                            </a>
                        </div>

                    @elseif($kycSubmission->isRejected())
                        <!-- REJECTED -->
                        <div class="alert alert-danger border-0 mb-4">
                            <div class="d-flex align-items-start">
                                <i class="bi bi-x-circle-fill fs-3 me-3"></i>
                                <div>
                                    <h5 class="fw-bold mb-2">Verification Not Approved</h5>
                                    <p class="mb-2">
                                        Unfortunately, your KYC verification could not be approved at this time.
                                    </p>
                                    @if($kycSubmission->rejection_reason)
                                    <div class="mt-3">
                                        <strong>Reason:</strong><br>
                                        <div class="bg-white p-3 rounded mt-2">
                                            {{ $kycSubmission->rejection_reason }}
                                        </div>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        @if($kycSubmission->admin_notes)
                        <div class="alert alert-warning border-0 mb-4">
                            <strong>Additional Notes:</strong><br>
                            {{ $kycSubmission->admin_notes }}
                        </div>
                        @endif>

                        <div class="d-grid gap-2">
                            <a href="{{ route('kyc.resubmit') }}" class="btn btn-primary btn-lg">
                                <i class="bi bi-arrow-repeat me-2"></i>Resubmit KYC
                            </a>
                            <a href="mailto:support@360winestate.com" class="btn btn-outline-secondary">
                                <i class="bi bi-envelope me-2"></i>Contact Support
                            </a>
                        </div>

                    @elseif($kycSubmission->requiresResubmission())
                        <!-- RESUBMISSION REQUIRED -->
                        <div class="alert alert-warning border-0 mb-4">
                            <div class="d-flex align-items-start">
                                <i class="bi bi-exclamation-triangle-fill fs-3 me-3"></i>
                                <div>
                                    <h5 class="fw-bold mb-2">Please Resubmit Your Documents</h5>
                                    <p class="mb-2">
                                        Our team has reviewed your submission and requires some corrections.
                                    </p>
                                    @if($kycSubmission->admin_notes)
                                    <div class="mt-3">
                                        <strong>What needs to be fixed:</strong><br>
                                        <div class="bg-white p-3 rounded mt-2">
                                            {{ $kycSubmission->admin_notes }}
                                        </div>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="d-grid">
                            <a href="{{ route('kyc.resubmit') }}" class="btn btn-primary btn-lg">
                                <i class="bi bi-arrow-repeat me-2"></i>Resubmit KYC Now
                            </a>
                        </div>

                    @elseif($kycSubmission->isUnderReview())
                        <!-- UNDER REVIEW -->
                        <div class="alert alert-info border-0 mb-4">
                            <div class="d-flex align-items-start">
                                <i class="bi bi-hourglass-split fs-3 me-3"></i>
                                <div>
                                    <h5 class="fw-bold mb-2">Your Documents Are Being Reviewed</h5>
                                    <p class="mb-0">
                                        Our verification team is currently reviewing your documents. 
                                        This process typically takes 24-72 hours. You will receive an 
                                        email notification once the review is complete.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="text-center">
                            <button onclick="location.reload()" class="btn btn-outline-primary">
                                <i class="bi bi-arrow-clockwise me-2"></i>Refresh Status
                            </button>
                        </div>

                    @else
                        <!-- SUBMITTED -->
                        <div class="alert alert-primary border-0 mb-4">
                            <div class="d-flex align-items-start">
                                <i class="bi bi-clock-history fs-3 me-3"></i>
                                <div>
                                    <h5 class="fw-bold mb-2">Submission Received</h5>
                                    <p class="mb-0">
                                        Thank you for submitting your KYC documents. Your submission 
                                        is in the queue and will be reviewed shortly. Typical review 
                                        time is 24-72 hours.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="text-center">
                            <button onclick="location.reload()" class="btn btn-outline-primary">
                                <i class="bi bi-arrow-clockwise me-2"></i>Refresh Status
                            </button>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Timeline -->
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <h6 class="fw-bold text-navy mb-4">Verification Timeline</h6>
                    
                    <div class="timeline">
                        <!-- Submitted -->
                        <div class="timeline-item {{ $kycSubmission->submitted_at ? 'completed' : '' }}">
                            <div class="timeline-marker">
                                <i class="bi bi-check-circle-fill"></i>
                            </div>
                            <div class="timeline-content">
                                <strong>KYC Submitted</strong>
                                @if($kycSubmission->submitted_at)
                                    <br><small class="text-muted">{{ $kycSubmission->submitted_at->format('M d, Y H:i') }}</small>
                                @endif
                            </div>
                        </div>

                        <!-- Under Review -->
                        <div class="timeline-item {{ $kycSubmission->isUnderReview() || $kycSubmission->reviewed_at ? 'completed' : '' }}">
                            <div class="timeline-marker">
                                <i class="bi bi-{{ $kycSubmission->isUnderReview() || $kycSubmission->reviewed_at ? 'check-circle-fill' : 'circle' }}"></i>
                            </div>
                            <div class="timeline-content">
                                <strong>Under Review</strong>
                                @if($kycSubmission->isUnderReview())
                                    <br><small class="text-muted">In progress...</small>
                                @endif
                            </div>
                        </div>

                        <!-- Completed -->
                        <div class="timeline-item {{ $kycSubmission->reviewed_at ? 'completed' : '' }}">
                            <div class="timeline-marker">
                                <i class="bi bi-{{ $kycSubmission->reviewed_at ? 'check-circle-fill' : 'circle' }}"></i>
                            </div>
                            <div class="timeline-content">
                                <strong>Review Complete</strong>
                                @if($kycSubmission->reviewed_at)
                                    <br><small class="text-muted">{{ $kycSubmission->reviewed_at->format('M d, Y H:i') }}</small>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Help Section -->
            <div class="text-center mt-4">
                <small class="text-muted">
                    <i class="bi bi-question-circle me-1"></i>
                    Need help? <a href="mailto:support@360winestate.com" class="text-gold text-decoration-none">Contact Support</a>
                </small>
            </div>
        </div>
    </div>
</div>

<style>
    .text-navy {
        color: #0F1A3C;
    }
    
    .text-gold {
        color: #E4B400;
    }
    
    .btn-primary {
        background-color: #E4B400;
        border-color: #E4B400;
        color: #0F1A3C;
        font-weight: 600;
    }
    
    .btn-primary:hover {
        background-color: #c99d00;
        border-color: #c99d00;
        color: #0F1A3C;
    }
    
    /* Timeline Styles */
    .timeline {
        position: relative;
        padding-left: 40px;
    }
    
    .timeline::before {
        content: '';
        position: absolute;
        left: 15px;
        top: 0;
        bottom: 0;
        width: 2px;
        background: #dee2e6;
    }
    
    .timeline-item {
        position: relative;
        margin-bottom: 30px;
    }
    
    .timeline-item:last-child {
        margin-bottom: 0;
    }
    
    .timeline-marker {
        position: absolute;
        left: -32px;
        top: 0;
        width: 32px;
        height: 32px;
        border-radius: 50%;
        background: white;
        border: 2px solid #dee2e6;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #6c757d;
    }
    
    .timeline-item.completed .timeline-marker {
        background: #198754;
        border-color: #198754;
        color: white;
    }
    
    .timeline-content {
        padding-top: 4px;
    }
</style>
@endsection
