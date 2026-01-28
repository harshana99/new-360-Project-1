@extends('layouts.admin')

@section('title', 'KYC Management')

@section('content')
<div class="container-fluid py-4">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <h2 class="mb-0 text-navy fw-bold">
                <i class="bi bi-file-earmark-person-fill me-2"></i>KYC Management
            </h2>
            <p class="text-muted">Review and approve pending identity verification submissions</p>
        </div>
    </div>

    <!-- Alert for Pending Count -->
    @if($pendingKYC->count() > 0)
    <div class="alert alert-warning border-0 shadow-sm mb-4">
        <div class="d-flex align-items-center">
            <i class="bi bi-exclamation-triangle-fill fs-4 me-3 text-warning"></i>
            <div>
                <strong>Action Required:</strong> You have {{ $pendingKYC->total() }} pending KYC submission(s) waiting for review.
            </div>
        </div>
    </div>
    @endif

    <!-- Pending Applications Table -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold">Pending Applications</h5>
                <span class="badge bg-warning text-dark">{{ $pendingKYC->total() }} Pending</span>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4">User</th>
                            <th>ID Type</th>
                            <th>Submitted Date</th>
                            <th>Membership</th>
                            <th>Status</th>
                            <th class="text-end pe-4">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pendingKYC as $submission)
                        <tr>
                            <td class="ps-4">
                                <div class="d-flex align-items-center">
                                    <div class="avatar-circle me-3 bg-navy text-white d-flex align-items-center justify-content-center rounded-circle" style="width: 40px; height: 40px;">
                                        {{ substr($submission->user->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <div class="fw-bold text-navy">{{ $submission->user->name }}</div>
                                        <div class="small text-muted">{{ $submission->user->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>{{ $submission->getIdTypeLabel() }}</td>
                            <td>
                                @if($submission->submitted_at)
                                    <div class="fw-bold">{{ $submission->submitted_at->format('M d, Y') }}</div>
                                    <small class="text-muted">{{ $submission->submitted_at->diffForHumans() }}</small>
                                @else
                                    <span class="text-muted">N/A</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-secondary">{{ ucfirst($submission->user->membership_type) }}</span>
                            </td>
                            <td>
                                <span class="badge {{ $submission->getStatusBadgeClass() }}">
                                    {{ $submission->getStatusLabel() }}
                                </span>
                            </td>
                            <td class="text-end pe-4">
                                <a href="{{ route('admin.users.kyc', $submission->user_id) }}" class="btn btn-primary btn-sm">
                                    <i class="bi bi-search me-1"></i> Review
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-5">
                                <div class="text-muted">
                                    <i class="bi bi-file-earmark-check display-4 mb-3 d-block text-success" style="opacity: 0.5;"></i>
                                    <h5>All Caught Up!</h5>
                                    <p class="mb-0">No pending KYC submissions to review.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($pendingKYC->hasPages())
        <div class="card-footer bg-white border-top-0 py-3">
            {{ $pendingKYC->links() }}
        </div>
        @endif
    </div>
</div>

<style>
    .text-navy { color: #0F1A3C; }
    .bg-navy { background-color: #0F1A3C; }
</style>
@endsection
