@extends('layouts.app')

@section('title', 'Dashboard - 360WinEstate')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-12">
            <!-- Welcome Header -->
            <div class="card mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="mb-2">Welcome back, {{ $user->name }}! 🎉</h3>
                            <p class="text-muted mb-0">
                                <i class="bi bi-person-badge me-2"></i>{{ $user->getMembershipTypeLabel() }}
                            </p>
                        </div>
                        <div class="text-end">
                            <span class="badge {{ $user->getStatusBadgeClass() }} fs-6 px-3 py-2">
                                {{ $user->getStatusLabel() }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Success Message -->
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <!-- Dashboard Content -->
            <div class="row g-4">
                <!-- Quick Stats -->
                <div class="col-md-4">
                    <div class="card h-100">
                        <div class="card-body text-center">
                            <i class="bi bi-speedometer2 text-gold mb-3" style="font-size: 3rem;"></i>
                            <h5>Dashboard</h5>
                            <p class="text-muted">Your main control panel</p>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card h-100">
                        <div class="card-body text-center">
                            <i class="bi bi-graph-up text-gold mb-3" style="font-size: 3rem;"></i>
                            <h5>Analytics</h5>
                            <p class="text-muted">Track your performance</p>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card h-100">
                        <div class="card-body text-center">
                            <i class="bi bi-gear text-gold mb-3" style="font-size: 3rem;"></i>
                            <h5>Settings</h5>
                            <p class="text-muted">Manage your account</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Account Information -->
            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="mb-0">Account Information</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <small class="text-muted d-block">Full Name</small>
                            <strong>{{ $user->name }}</strong>
                        </div>
                        <div class="col-md-6 mb-3">
                            <small class="text-muted d-block">Email Address</small>
                            <strong>{{ $user->email }}</strong>
                            @if($user->hasVerifiedEmail())
                                <i class="bi bi-check-circle-fill text-success ms-2" title="Verified"></i>
                            @endif
                        </div>
                        @if($user->phone)
                            <div class="col-md-6 mb-3">
                                <small class="text-muted d-block">Phone Number</small>
                                <strong>{{ $user->phone }}</strong>
                            </div>
                        @endif
                        <div class="col-md-6 mb-3">
                            <small class="text-muted d-block">Membership Type</small>
                            <strong>{{ $user->getMembershipTypeLabel() }}</strong>
                        </div>
                        <div class="col-md-6 mb-3">
                            <small class="text-muted d-block">Member Since</small>
                            <strong>{{ $user->created_at->format('F d, Y') }}</strong>
                        </div>
                        @if($user->approved_at)
                            <div class="col-md-6 mb-3">
                                <small class="text-muted d-block">Approved On</small>
                                <strong>{{ $user->approved_at->format('F d, Y') }}</strong>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Next Steps (based on membership type) -->
            <div class="card mt-4">
                <div class="card-header bg-gold text-navy">
                    <h5 class="mb-0">Next Steps</h5>
                </div>
                <div class="card-body">
                    @if($user->membership_type === 'owner')
                        <h6 class="mb-3">As a Property Owner, you can:</h6>
                        <ul>
                            <li>List your properties on the platform</li>
                            <li>Manage property details and documentation</li>
                            <li>Connect with potential investors</li>
                            <li>Track property performance and analytics</li>
                        </ul>
                        <a href="#" class="btn btn-primary mt-3">
                            <i class="bi bi-plus-circle me-2"></i>Add Your First Property
                        </a>
                    @elseif($user->membership_type === 'investor')
                        <h6 class="mb-3">As an Investor, you can:</h6>
                        <ul>
                            <li>Browse verified property listings</li>
                            <li>Invest in properties that match your criteria</li>
                            <li>Track your investment portfolio</li>
                            <li>View ROI analytics and reports</li>
                        </ul>
                        <a href="#" class="btn btn-primary mt-3">
                            <i class="bi bi-search me-2"></i>Browse Properties
                        </a>
                    @elseif($user->membership_type === 'marketer')
                        <h6 class="mb-3">As a Marketer, you can:</h6>
                        <ul>
                            <li>Access property listings to promote</li>
                            <li>Generate referral links and marketing materials</li>
                            <li>Track your commissions and earnings</li>
                            <li>View performance analytics</li>
                        </ul>
                        <a href="#" class="btn btn-primary mt-3">
                            <i class="bi bi-megaphone me-2"></i>Start Marketing
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
