@extends('layouts.admin')

@section('title', 'Compliance Dashboard')

@section('content')
<div class="row mb-4">
    <div class="col-md-4 mb-3">
        <div class="card stat-card bg-primary text-white h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-white-50 mb-1">Pending KYC</h6>
                        <h3 class="mb-0">{{ $pendingKYC }}</h3>
                    </div>
                    <i class="bi bi-file-earmark-person fs-1 opacity-50"></i>
                </div>
                <div class="mt-3">
                    <a href="{{ route('admin.kyc') }}" class="text-white text-decoration-none small">
                        Review Pending <i class="bi bi-arrow-right ms-1"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-3">
        <div class="card stat-card bg-success text-white h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-white-50 mb-1">Approved KYC</h6>
                        <h3 class="mb-0">{{ $approvedKYC }}</h3>
                    </div>
                    <i class="bi bi-check-circle fs-1 opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-3">
        <div class="card stat-card bg-danger text-white h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-white-50 mb-1">Rejected KYC</h6>
                        <h3 class="mb-0">{{ $rejectedKYC }}</h3>
                    </div>
                    <i class="bi bi-x-circle fs-1 opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
        <h5 class="mb-0 fw-bold">Recent KYC Submissions</h5>
        <a href="{{ route('admin.kyc') }}" class="btn btn-sm btn-outline-primary">View All</a>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4">User</th>
                        <th>ID Type</th>
                        <th>Date</th>
                        <th>Status</th>
                        <th class="text-end pe-4">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentKYC as $submission)
                    <tr>
                        <td class="ps-4">
                            <div class="d-flex align-items-center">
                                <div class="avatar-circle me-3 bg-navy text-white d-flex align-items-center justify-content-center rounded-circle" style="width: 35px; height: 35px;">
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
                                {{ $submission->submitted_at->format('M d, Y') }}
                            @else
                                <span class="text-muted">N/A</span>
                            @endif
                        </td>
                        <td>
                            <span class="badge {{ $submission->getStatusBadgeClass() }}">
                                {{ $submission->getStatusLabel() }}
                            </span>
                        </td>
                        <td class="text-end pe-4">
                            <a href="{{ route('admin.users.kyc', $submission->user_id) }}" class="btn btn-sm btn-outline-secondary">
                                <i class="bi bi-eye"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-4 text-muted">No recent submissions found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<style>
    .text-navy { color: #0F1A3C; }
    .bg-navy { background-color: #0F1A3C; }
    .opacity-50 { opacity: 0.5; }
</style>
@endsection
