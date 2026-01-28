@extends('layouts.app')

@section('title', 'My Profile - 360WinEstate')

@section('content')
<div class="container py-4">
    <div class="row">
        <!-- Profile Header -->
        <div class="col-12 mb-4">
            <div class="d-flex justify-content-between align-items-center">
                <h2 class="mb-0">
                    <i class="bi bi-person-circle me-2"></i>My Profile
                </h2>
                <div class="btn-group">
                    <a href="{{ route('user.profile.edit') }}" class="btn btn-primary">
                        <i class="bi bi-pencil-square me-1"></i>Edit Profile
                    </a>
                    <a href="{{ route('user.profile.change-password') }}" class="btn btn-outline-primary">
                        <i class="bi bi-shield-lock me-1"></i>Change Password
                    </a>
                </div>
            </div>
        </div>

        <!-- Success/Error Messages -->
        @if(session('success'))
            <div class="col-12 mb-3">
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="col-12 mb-3">
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            </div>
        @endif

        <!-- Basic Information Card -->
        <div class="col-lg-6 mb-4">
            <div class="card h-100">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-person-fill me-2"></i>Basic Information</h5>
                </div>
                <div class="card-body">
                    <div class="info-item mb-3">
                        <label class="text-muted small">Full Name</label>
                        <p class="mb-0 fw-semibold">{{ $user->name }}</p>
                    </div>
                    <div class="info-item mb-3">
                        <label class="text-muted small">Email Address</label>
                        <p class="mb-0 fw-semibold">
                            {{ $user->email }}
                            @if($user->email_verified_at)
                                <span class="badge bg-success ms-2">
                                    <i class="bi bi-check-circle-fill"></i> Verified
                                </span>
                            @endif
                        </p>
                    </div>
                    <div class="info-item mb-3">
                        <label class="text-muted small">Phone Number</label>
                        <p class="mb-0 fw-semibold">{{ $user->phone ?? 'Not provided' }}</p>
                    </div>
                    <div class="info-item mb-3">
                        <label class="text-muted small">Membership Type</label>
                        <p class="mb-0">
                            <span class="badge bg-primary fs-6">
                                <i class="bi bi-award-fill me-1"></i>{{ ucfirst($user->membership_type) }}
                            </span>
                        </p>
                    </div>
                    @if($user->date_of_birth)
                        <div class="info-item mb-3">
                            <label class="text-muted small">Date of Birth</label>
                            <p class="mb-0 fw-semibold">{{ \Carbon\Carbon::parse($user->date_of_birth)->format('F d, Y') }}</p>
                        </div>
                    @endif
                    @if($user->bio)
                        <div class="info-item">
                            <label class="text-muted small">Bio</label>
                            <p class="mb-0">{{ $user->bio }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Account Status Card -->
        <div class="col-lg-6 mb-4">
            <div class="card h-100">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-shield-check me-2"></i>Account Status</h5>
                </div>
                <div class="card-body">
                    <div class="info-item mb-3">
                        <label class="text-muted small">Account Status</label>
                        <p class="mb-0">
                            <span class="badge {{ $user->getStatusBadgeClass() }} fs-6">
                                {{ $user->getStatusLabel() }}
                            </span>
                        </p>
                    </div>
                    <div class="info-item mb-3">
                        <label class="text-muted small">Account Created</label>
                        <p class="mb-0 fw-semibold">{{ $user->created_at->format('F d, Y') }}</p>
                    </div>
                    @if($lastLogin)
                        <div class="info-item mb-3">
                            <label class="text-muted small">Last Login</label>
                            <p class="mb-0 fw-semibold">{{ $lastLogin->diffForHumans() }}</p>
                        </div>
                    @endif
                    @if($user->address)
                        <div class="info-item">
                            <label class="text-muted small">Address</label>
                            <p class="mb-0">
                                {{ $user->address }}<br>
                                @if($user->city){{ $user->city }}, @endif
                                @if($user->state){{ $user->state }} @endif
                                @if($user->postal_code){{ $user->postal_code }}@endif<br>
                                @if($user->country){{ $user->country }}@endif
                            </p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- KYC Status Card -->
        <div class="col-12 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-file-earmark-check me-2"></i>KYC Verification Status</h5>
                </div>
                <div class="card-body">
                    @if($kycSubmission)
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="me-3">
                                        @if($kycSubmission->status === 'approved')
                                            <i class="bi bi-check-circle-fill text-success" style="font-size: 3rem;"></i>
                                        @elseif($kycSubmission->status === 'rejected')
                                            <i class="bi bi-x-circle-fill text-danger" style="font-size: 3rem;"></i>
                                        @elseif(in_array($kycSubmission->status, ['submitted', 'under_review']))
                                            <i class="bi bi-clock-fill text-warning" style="font-size: 3rem;"></i>
                                        @else
                                            <i class="bi bi-info-circle-fill text-info" style="font-size: 3rem;"></i>
                                        @endif
                                    </div>
                                    <div>
                                        <h4 class="mb-1">
                                            @if($kycSubmission->status === 'approved')
                                                <span class="text-success">KYC Approved</span>
                                            @elseif($kycSubmission->status === 'rejected')
                                                <span class="text-danger">KYC Rejected</span>
                                            @elseif(in_array($kycSubmission->status, ['submitted', 'under_review']))
                                                <span class="text-warning">Under Review</span>
                                            @else
                                                <span class="text-info">{{ ucfirst(str_replace('_', ' ', $kycSubmission->status)) }}</span>
                                            @endif
                                        </h4>
                                        <p class="text-muted mb-0">
                                            Submitted on {{ $kycSubmission->submitted_at->format('F d, Y') }}
                                        </p>
                                        @if($kycSubmission->reviewed_at)
                                            <p class="text-muted mb-0">
                                                Reviewed on {{ $kycSubmission->reviewed_at->format('F d, Y') }}
                                            </p>
                                        @endif
                                    </div>
                                </div>
                                @if($kycSubmission->admin_notes)
                                    <div class="alert alert-info">
                                        <strong>Admin Notes:</strong> {{ $kycSubmission->admin_notes }}
                                    </div>
                                @endif
                                @if($kycSubmission->rejection_reason)
                                    <div class="alert alert-danger">
                                        <strong>Rejection Reason:</strong> {{ $kycSubmission->rejection_reason }}
                                    </div>
                                @endif
                            </div>
                            <div class="col-md-4 text-md-end">
                                <a href="{{ route('user.kyc.status') }}" class="btn btn-outline-primary mb-2 w-100">
                                    <i class="bi bi-eye me-1"></i>View Details
                                </a>
                                @if(in_array($kycSubmission->status, ['rejected', 'resubmission_required']))
                                    <a href="{{ route('user.kyc.resubmit') }}" class="btn btn-warning w-100">
                                        <i class="bi bi-arrow-repeat me-1"></i>Resubmit KYC
                                    </a>
                                @endif
                                @if($kycSubmission->status === 'approved')
                                    <a href="{{ route('user.kyc.download') }}" class="btn btn-success w-100">
                                        <i class="bi bi-download me-1"></i>Download Documents
                                    </a>
                                @endif
                            </div>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="bi bi-file-earmark-x text-muted" style="font-size: 4rem;"></i>
                            <h5 class="mt-3">No KYC Submission</h5>
                            <p class="text-muted">You haven't submitted your KYC documents yet.</p>
                            <a href="{{ route('kyc.submit') }}" class="btn btn-primary">
                                <i class="bi bi-file-earmark-plus me-1"></i>Submit KYC Now
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Recent Activity Card -->
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="bi bi-clock-history me-2"></i>Recent Activity</h5>
                    <a href="{{ route('user.activity') }}" class="btn btn-sm btn-outline-primary">
                        View All <i class="bi bi-arrow-right ms-1"></i>
                    </a>
                </div>
                <div class="card-body">
                    @if($recentActivities && $recentActivities->count() > 0)
                        <div class="activity-timeline">
                            @foreach($recentActivities as $activity)
                                <div class="activity-item d-flex mb-3 pb-3 border-bottom">
                                    <div class="activity-icon me-3">
                                        <i class="{{ $activity->getIconClass() }}" style="font-size: 1.5rem;"></i>
                                    </div>
                                    <div class="activity-content flex-grow-1">
                                        <h6 class="mb-1">{{ $activity->getTypeLabel() }}</h6>
                                        <p class="text-muted small mb-1">{{ $activity->description }}</p>
                                        <small class="text-muted">
                                            <i class="bi bi-clock me-1"></i>{{ $activity->created_at->diffForHumans() }}
                                        </small>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="bi bi-inbox text-muted" style="font-size: 3rem;"></i>
                            <p class="text-muted mt-2">No recent activity</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .info-item label {
        display: block;
        font-size: 0.875rem;
        margin-bottom: 0.25rem;
    }
    
    .activity-timeline .activity-item:last-child {
        border-bottom: none !important;
        margin-bottom: 0 !important;
        padding-bottom: 0 !important;
    }
    
    @media (max-width: 768px) {
        .btn-group {
            flex-direction: column;
            width: 100%;
        }
        
        .btn-group .btn {
            width: 100%;
            margin-bottom: 0.5rem;
        }
    }
</style>
@endsection
