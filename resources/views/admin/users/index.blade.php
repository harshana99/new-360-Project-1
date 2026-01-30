@extends('layouts.admin')

@section('title', 'User Management')

@section('content')
<!-- Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="text-white fw-bold mb-1">User Management</h2>
        <p class="text-muted">Manage system users, investors, and owners</p>
    </div>
    <a href="{{ route('admin.users.export', request()->query()) }}" class="btn btn-outline-success">
        <i class="bi bi-download me-1"></i>Export to CSV
    </a>
</div>

<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="stats-card">
            <h6 class="text-white text-opacity-75 small">Total Users</h6>
            <h3 class="mb-0 text-white">{{ number_format($stats['total']) }}</h3>
            <div class="position-absolute end-0 top-0 p-4 opacity-25">
                <i class="bi bi-people-fill fs-2"></i>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="stats-card">
            <h6 class="text-white text-opacity-75 small">Approved</h6>
            <h3 class="mb-0 text-success">{{ number_format($stats['approved']) }}</h3>
            <div class="position-absolute end-0 top-0 p-4 opacity-25">
                <i class="bi bi-check-circle-fill text-success fs-2"></i>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="stats-card">
            <h6 class="text-white text-opacity-75 small">Pending KYC</h6>
            <h3 class="mb-0 text-warning">{{ number_format($stats['pending']) }}</h3>
            <div class="position-absolute end-0 top-0 p-4 opacity-25">
                <i class="bi bi-clock-fill text-warning fs-2"></i>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="stats-card">
            <h6 class="text-white text-opacity-75 small">Suspended</h6>
            <h3 class="mb-0 text-danger">{{ number_format($stats['suspended']) }}</h3>
            <div class="position-absolute end-0 top-0 p-4 opacity-25">
                <i class="bi bi-slash-circle-fill text-danger fs-2"></i>
            </div>
        </div>
    </div>
</div>

<!-- Filters & Search -->
<div class="stats-card mb-4">
    <form method="GET" action="{{ route('admin.users.index') }}" class="row g-3">
        <!-- Search -->
        <div class="col-md-4">
            <label class="form-label text-muted small">Search</label>
            <div class="search-bar w-100">
                 <i class="bi bi-search"></i>
                 <input type="text" name="search" value="{{ $search }}" placeholder="Name, email, or phone...">
            </div>
        </div>

        <!-- Membership Type -->
        <div class="col-md-3">
            <label class="form-label text-muted small">Membership Type</label>
            <select class="form-select bg-dark text-white border-secondary" name="membership_type">
                <option value="">All Types</option>
                <option value="owner" {{ $membershipType === 'owner' ? 'selected' : '' }}>Owner</option>
                <option value="investor" {{ $membershipType === 'investor' ? 'selected' : '' }}>Investor</option>
                <option value="marketer" {{ $membershipType === 'marketer' ? 'selected' : '' }}>Marketer</option>
            </select>
        </div>

        <!-- Status -->
        <div class="col-md-3">
            <label class="form-label text-muted small">Status</label>
            <select class="form-select bg-dark text-white border-secondary" name="status">
                <option value="">All Statuses</option>
                <option value="registered" {{ $status === 'registered' ? 'selected' : '' }}>Registered</option>
                <option value="membership_selected" {{ $status === 'membership_selected' ? 'selected' : '' }}>Membership Selected</option>
                <option value="kyc_submitted" {{ $status === 'kyc_submitted' ? 'selected' : '' }}>KYC Submitted</option>
                <option value="under_review" {{ $status === 'under_review' ? 'selected' : '' }}>Under Review</option>
                <option value="approved" {{ $status === 'approved' ? 'selected' : '' }}>Approved</option>
                <option value="rejected" {{ $status === 'rejected' ? 'selected' : '' }}>Rejected</option>
                <option value="suspended" {{ $status === 'suspended' ? 'selected' : '' }}>Suspended</option>
            </select>
        </div>

        <!-- Buttons -->
        <div class="col-md-2 d-flex align-items-end">
            <div class="d-flex gap-2 w-100">
                <button type="submit" class="btn btn-warning flex-grow-1">
                    <i class="bi bi-funnel me-1"></i>Filter
                </button>
                <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-x-circle"></i>
                </a>
            </div>
        </div>
    </form>
</div>

<!-- Users Table -->
<div class="stats-card p-0 overflow-hidden">
    <div class="p-4 d-flex justify-content-between align-items-center">
        <h5 class="text-white mb-0 fw-bold">All Users ({{ $users->total() }})</h5>
        <div class="btn-group btn-group-sm">
            <a href="{{ route('admin.users.index', array_merge(request()->query(), ['sort_by' => 'created_at', 'sort_order' => 'desc'])) }}" 
               class="btn btn-outline-light {{ $sortBy === 'created_at' && $sortOrder === 'desc' ? 'active' : '' }}">
                Newest
            </a>
            <a href="{{ route('admin.users.index', array_merge(request()->query(), ['sort_by' => 'name', 'sort_order' => 'asc'])) }}" 
               class="btn btn-outline-light {{ $sortBy === 'name' && $sortOrder === 'asc' ? 'active' : '' }}">
                A-Z
            </a>
        </div>
    </div>
    
    <div class="table-responsive">
        <table class="table table-dark-custom mb-0">
            <thead>
                <tr>
                    <th class="ps-4">ID</th>
                    <th>User</th>
                    <th>Membership</th>
                    <th>Status</th>
                    <th>KYC</th>
                    <th>Joined</th>
                    <th class="text-end pe-4">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                    <tr>
                        <td class="ps-4 text-muted border-bottom border-light border-opacity-10"><strong>#{{ $user->id }}</strong></td>
                        <td class="border-bottom border-light border-opacity-10">
                            <div class="d-flex align-items-center">
                                <div class="rounded-circle bg-secondary bg-opacity-25 me-3 text-white d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                                <div>
                                    <div class="fw-bold text-white">{{ $user->name }}</div>
                                    <small class="text-muted">{{ $user->email }}</small>
                                </div>
                            </div>
                        </td>
                        <td class="border-bottom border-light border-opacity-10">
                            <span class="badge bg-primary bg-opacity-25 text-primary border border-primary border-opacity-25">
                                <i class="bi bi-award me-1"></i>{{ ucfirst($user->membership_type) }}
                            </span>
                        </td>
                        <td class="border-bottom border-light border-opacity-10">
                            <span class="badge {{ $user->getStatusBadgeClass() }}">
                                {{ $user->getStatusLabel() }}
                            </span>
                        </td>
                        <td class="border-bottom border-light border-opacity-10">
                            @if($user->hasKYC())
                                <i class="bi bi-check-circle-fill text-success fs-5"></i>
                            @else
                                <i class="bi bi-dash-circle text-muted fs-5 opacity-50"></i>
                            @endif
                        </td>
                        <td class="text-muted border-bottom border-light border-opacity-10">
                            <small>{{ $user->created_at->format('M d, Y') }}</small>
                        </td>
                        <td class="text-end pe-4 border-bottom border-light border-opacity-10">
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('admin.users.show', $user->id) }}" 
                                   class="btn btn-outline-primary border-0" title="View Details">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('admin.users.edit', $user->id) }}" 
                                   class="btn btn-outline-warning border-0" title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-5 border-none">
                            <i class="bi bi-inbox text-muted opacity-25" style="font-size: 3rem;"></i>
                            <p class="text-muted mt-2">No users found</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    @if($users->hasPages())
        <div class="p-3 border-top border-light border-opacity-10">
            {{ $users->links() }}
        </div>
    @endif
</div>
@endsection
