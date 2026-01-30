@extends('layouts.admin')

@section('title', 'KYC Management')

@section('content')
<!-- Header -->
<div class="row mb-4">
    <div class="col-12">
        <h2 class="mb-1 text-white fw-bold">KYC Management</h2>
        <p class="text-muted">Review and approve pending identity verification submissions</p>
    </div>
</div>

<!-- Alert for Pending Count -->
@if($pendingKYC->count() > 0)
<div class="alert alert-warning d-flex align-items-center mb-4" role="alert" style="background: rgba(245, 158, 11, 0.1); border: 1px solid rgba(245, 158, 11, 0.2); color: #F59E0B;">
    <i class="bi bi-exclamation-triangle-fill fs-4 me-3"></i>
    <div>
        <strong>Action Required:</strong> You have {{ $pendingKYC->total() }} pending KYC submission(s) waiting for review.
    </div>
</div>
@endif

<!-- Pending Applications Table -->
<div class="stats-card p-0 overflow-hidden">
    <div class="p-4 d-flex justify-content-between align-items-center">
        <h5 class="mb-0 fw-bold text-white">Pending Applications</h5>
        <span class="badge bg-warning text-dark">{{ $pendingKYC->total() }} Pending</span>
    </div>
    
    <div class="table-responsive">
        <table class="table table-dark-custom mb-0">
            <thead>
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
                            <div class="avatar-circle me-3 bg-secondary bg-opacity-25 text-white d-flex align-items-center justify-content-center rounded-circle" style="width: 40px; height: 40px;">
                                {{ substr($submission->user->name, 0, 1) }}
                            </div>
                            <div>
                                <div class="fw-bold text-white">{{ $submission->user->name }}</div>
                                <div class="small text-muted">{{ $submission->user->email }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="text-white-50">{{ $submission->getIdTypeLabel() }}</td>
                    <td>
                        @if($submission->submitted_at)
                            <div class="fw-bold text-white">{{ $submission->submitted_at->format('M d, Y') }}</div>
                            <small class="text-muted">{{ $submission->submitted_at->diffForHumans() }}</small>
                        @else
                            <span class="text-muted">N/A</span>
                        @endif
                    </td>
                    <td>
                        <span class="badge bg-secondary bg-opacity-20 text-white border border-secondary border-opacity-25">{{ ucfirst($submission->user->membership_type) }}</span>
                    </td>
                    <td>
                        <span class="badge {{ $submission->getStatusBadgeClass() }}">
                            {{ $submission->getStatusLabel() }}
                        </span>
                    </td>
                    <td class="text-end pe-4">
                        <a href="{{ route('admin.users.kyc', $submission->user_id) }}" class="btn btn-warning btn-sm text-dark fw-bold">
                            <i class="bi bi-search me-1"></i> Review
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center py-5">
                        <div class="text-muted opacity-50">
                            <i class="bi bi-check-circle-fill display-4 mb-3 d-block text-success opacity-50"></i>
                            <h5 class="text-white opacity-75">All Caught Up!</h5>
                            <p class="mb-0">No pending KYC submissions to review.</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    @if($pendingKYC->hasPages())
    <div class="p-3 border-top border-light border-opacity-10">
        {{ $pendingKYC->links() }}
    </div>
    @endif
</div>
@endsection
