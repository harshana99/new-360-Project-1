@extends('layouts.admin')

@section('title', 'User Details - ' . $user->name)

@section('content')
<div class="container-fluid py-4">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary btn-sm mb-2">
                        <i class="bi bi-arrow-left me-1"></i>Back to Users
                    </a>
                    <h2 class="mb-0">{{ $user->name }}</h2>
                    <p class="text-muted">User ID: #{{ $user->id }}</p>
                </div>
                <div class="btn-group">
                    <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-primary">
                        <i class="bi bi-pencil me-1"></i>Edit User
                    </a>
                    @if($user->isSuspended())
                        <form action="{{ route('admin.users.activate', $user->id) }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-success" onclick="return confirm('Activate this user account?')">
                                <i class="bi bi-check-circle me-1"></i>Activate
                            </button>
                        </form>
                    @else
                        <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#suspendModal">
                            <i class="bi bi-slash-circle me-1"></i>Suspend
                        </button>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Success/Error Messages -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row">
        <!-- Left Column -->
        <div class="col-lg-4 mb-4">
            <!-- User Profile Card -->
            <div class="card mb-4">
                <div class="card-body text-center">
                    <div class="avatar-large mx-auto mb-3">
                        {{ strtoupper(substr($user->name, 0, 2)) }}
                    </div>
                    <h4>{{ $user->name }}</h4>
                    <p class="text-muted">{{ $user->email }}</p>
                    <span class="badge {{ $user->getStatusBadgeClass() }} fs-6">
                        {{ $user->getStatusLabel() }}
                    </span>
                </div>
            </div>

            <!-- Quick Stats -->
            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="mb-0">Quick Stats</h6>
                </div>
                <div class="card-body">
                    <div class="stat-item mb-3">
                        <small class="text-muted">Membership Type</small>
                        <div class="fw-semibold">
                            <i class="bi bi-award text-primary me-1"></i>{{ ucfirst($user->membership_type) }}
                        </div>
                    </div>
                    <div class="stat-item mb-3">
                        <small class="text-muted">Account Created</small>
                        <div class="fw-semibold">{{ $user->created_at->format('M d, Y') }}</div>
                    </div>
                    <div class="stat-item mb-3">
                        <small class="text-muted">Last Login</small>
                        <div class="fw-semibold">
                            @if($user->getLastLogin())
                                {{ $user->getLastLogin()->diffForHumans() }}
                            @else
                                Never
                            @endif
                        </div>
                    </div>
                    <div class="stat-item">
                        <small class="text-muted">KYC Status</small>
                        <div class="fw-semibold">
                            @if($kycSubmission)
                                @if($kycSubmission->status === 'approved')
                                    <i class="bi bi-check-circle-fill text-success me-1"></i>Approved
                                @elseif($kycSubmission->status === 'rejected')
                                    <i class="bi bi-x-circle-fill text-danger me-1"></i>Rejected
                                @else
                                    <i class="bi bi-clock-fill text-warning me-1"></i>{{ ucfirst($kycSubmission->status) }}
                                @endif
                            @else
                                <i class="bi bi-x-circle text-muted me-1"></i>Not Submitted
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">Actions</h6>
                </div>
                <div class="list-group list-group-flush">
                    @if($kycSubmission)
                        <a href="{{ route('admin.users.kyc', $user->id) }}" class="list-group-item list-group-item-action">
                            <i class="bi bi-file-earmark-check me-2"></i>View KYC Documents
                        </a>
                    @endif
                    <a href="#activitySection" class="list-group-item list-group-item-action">
                        <i class="bi bi-clock-history me-2"></i>View Activity Log
                    </a>
                    <a href="mailto:{{ $user->email }}" class="list-group-item list-group-item-action">
                        <i class="bi bi-envelope me-2"></i>Send Email
                    </a>
                </div>
            </div>
        </div>

        <!-- Right Column -->
        <div class="col-lg-8">
            <!-- Personal Information -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-person-fill me-2"></i>Personal Information</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small">Full Name</label>
                            <p class="mb-0 fw-semibold">{{ $user->name }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small">Email</label>
                            <p class="mb-0 fw-semibold">{{ $user->email }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small">Phone</label>
                            <p class="mb-0 fw-semibold">{{ $user->phone ?? 'Not provided' }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small">Date of Birth</label>
                            <p class="mb-0 fw-semibold">
                                {{ $user->date_of_birth ? \Carbon\Carbon::parse($user->date_of_birth)->format('M d, Y') : 'Not provided' }}
                            </p>
                        </div>
                        @if($user->address)
                            <div class="col-12">
                                <label class="text-muted small">Address</label>
                                <p class="mb-0">
                                    {{ $user->address }}<br>
                                    {{ $user->city }}, {{ $user->state }} {{ $user->postal_code }}<br>
                                    {{ $user->country }}
                                </p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- KYC Information -->
            @if($kycSubmission)
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0"><i class="bi bi-file-earmark-check me-2"></i>KYC Information</h5>
                        <a href="{{ route('admin.users.kyc', $user->id) }}" class="btn btn-sm btn-primary">
                            View Full Details
                        </a>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="text-muted small">Status</label>
                                <p class="mb-0">
                                    <span class="badge bg-{{ $kycSubmission->status === 'approved' ? 'success' : ($kycSubmission->status === 'rejected' ? 'danger' : 'warning') }}">
                                        {{ ucfirst(str_replace('_', ' ', $kycSubmission->status)) }}
                                    </span>
                                </p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="text-muted small">Submitted</label>
                                <p class="mb-0 fw-semibold">{{ $kycSubmission->submitted_at->format('M d, Y') }}</p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="text-muted small">ID Type</label>
                                <p class="mb-0 fw-semibold">{{ ucfirst(str_replace('_', ' ', $kycSubmission->id_type)) }}</p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="text-muted small">ID Number</label>
                                <p class="mb-0 fw-semibold">{{ substr($kycSubmission->id_number, 0, 3) . str_repeat('*', 6) . substr($kycSubmission->id_number, -3) }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Recent Activity -->
            <div class="card" id="activitySection">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-clock-history me-2"></i>Recent Activity</h5>
                </div>
                <div class="card-body">
                    @if($recentActivities && count($recentActivities) > 0)
                        <div class="activity-timeline">
                            @foreach($recentActivities as $activity)
                                <div class="activity-item d-flex mb-3 pb-3 border-bottom">
                                    <div class="activity-icon me-3">
                                        <i class="{{ $activity->getIconClass() }}" style="font-size: 1.5rem;"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h6 class="mb-1">{{ $activity->getTypeLabel() }}</h6>
                                        <p class="text-muted small mb-1">{{ $activity->description }}</p>
                                        <small class="text-muted">
                                            <i class="bi bi-clock me-1"></i>{{ $activity->created_at->diffForHumans() }}
                                            @if($activity->admin)
                                                <span class="mx-2">•</span>
                                                <i class="bi bi-person-badge me-1"></i>{{ $activity->admin->user->name }}
                                            @endif
                                        </small>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-muted text-center py-4">No recent activity</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Suspend Modal -->
<div class="modal fade" id="suspendModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.users.suspend', $user->id) }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Suspend User Account</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>You are about to suspend <strong>{{ $user->name }}</strong>'s account.</p>
                    <div class="mb-3">
                        <label for="reason" class="form-label">Reason for Suspension <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="reason" name="reason" rows="3" required 
                                  placeholder="Enter the reason for suspending this account..."></textarea>
                    </div>
                    <div class="alert alert-warning">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        The user will be notified via email and will not be able to access their account.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-warning">Suspend Account</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    .avatar-large {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        background: linear-gradient(135deg, #0F1A3C, #E4B400);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2.5rem;
        font-weight: bold;
    }
    
    .stat-item small {
        display: block;
        margin-bottom: 0.25rem;
    }
    
    .activity-timeline .activity-item:last-child {
        border-bottom: none !important;
        padding-bottom: 0 !important;
        margin-bottom: 0 !important;
    }
</style>
@endsection
