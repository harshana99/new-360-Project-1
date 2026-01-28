@extends('layouts.admin')

@section('title', 'User Management - Admin Dashboard')

@section('content')
<div class="container-fluid py-4">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h2 class="mb-0">
                    <i class="bi bi-people-fill me-2"></i>User Management
                </h2>
                <a href="{{ route('admin.users.export', request()->query()) }}" class="btn btn-success">
                    <i class="bi bi-download me-1"></i>Export to CSV
                </a>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card stat-card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-white-50 mb-1">Total Users</h6>
                            <h3 class="mb-0">{{ number_format($stats['total']) }}</h3>
                        </div>
                        <i class="bi bi-people-fill" style="font-size: 2.5rem; opacity: 0.3;"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card stat-card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-white-50 mb-1">Approved</h6>
                            <h3 class="mb-0">{{ number_format($stats['approved']) }}</h3>
                        </div>
                        <i class="bi bi-check-circle-fill" style="font-size: 2.5rem; opacity: 0.3;"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card stat-card bg-warning text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-white-50 mb-1">Pending KYC</h6>
                            <h3 class="mb-0">{{ number_format($stats['pending']) }}</h3>
                        </div>
                        <i class="bi bi-clock-fill" style="font-size: 2.5rem; opacity: 0.3;"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card stat-card bg-danger text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-white-50 mb-1">Suspended</h6>
                            <h3 class="mb-0">{{ number_format($stats['suspended']) }}</h3>
                        </div>
                        <i class="bi bi-slash-circle-fill" style="font-size: 2.5rem; opacity: 0.3;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters & Search -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.users.index') }}" class="row g-3">
                <!-- Search -->
                <div class="col-md-4">
                    <label class="form-label">Search</label>
                    <input type="text" class="form-control" name="search" value="{{ $search }}" 
                           placeholder="Name, email, or phone...">
                </div>

                <!-- Membership Type -->
                <div class="col-md-3">
                    <label class="form-label">Membership Type</label>
                    <select class="form-select" name="membership_type">
                        <option value="">All Types</option>
                        <option value="owner" {{ $membershipType === 'owner' ? 'selected' : '' }}>Owner</option>
                        <option value="investor" {{ $membershipType === 'investor' ? 'selected' : '' }}>Investor</option>
                        <option value="marketer" {{ $membershipType === 'marketer' ? 'selected' : '' }}>Marketer</option>
                    </select>
                </div>

                <!-- Status -->
                <div class="col-md-3">
                    <label class="form-label">Status</label>
                    <select class="form-select" name="status">
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
                    <div class="btn-group w-100">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-funnel me-1"></i>Filter
                        </button>
                        <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-x-circle"></i>
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Users Table -->
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">All Users ({{ $users->total() }})</h5>
            <div class="btn-group btn-group-sm">
                <a href="{{ route('admin.users.index', array_merge(request()->query(), ['sort_by' => 'created_at', 'sort_order' => 'desc'])) }}" 
                   class="btn btn-outline-secondary {{ $sortBy === 'created_at' && $sortOrder === 'desc' ? 'active' : '' }}">
                    Newest
                </a>
                <a href="{{ route('admin.users.index', array_merge(request()->query(), ['sort_by' => 'name', 'sort_order' => 'asc'])) }}" 
                   class="btn btn-outline-secondary {{ $sortBy === 'name' && $sortOrder === 'asc' ? 'active' : '' }}">
                    A-Z
                </a>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>User</th>
                            <th>Membership</th>
                            <th>Status</th>
                            <th>KYC</th>
                            <th>Joined</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $user)
                            <tr>
                                <td><strong>#{{ $user->id }}</strong></td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-circle me-2">
                                            {{ strtoupper(substr($user->name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <div class="fw-semibold">{{ $user->name }}</div>
                                            <small class="text-muted">{{ $user->email }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-primary">
                                        <i class="bi bi-award me-1"></i>{{ ucfirst($user->membership_type) }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge {{ $user->getStatusBadgeClass() }}">
                                        {{ $user->getStatusLabel() }}
                                    </span>
                                </td>
                                <td>
                                    @if($user->hasKYC())
                                        <i class="bi bi-check-circle-fill text-success"></i>
                                    @else
                                        <i class="bi bi-x-circle text-muted"></i>
                                    @endif
                                </td>
                                <td>
                                    <small>{{ $user->created_at->format('M d, Y') }}</small>
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('admin.users.show', $user->id) }}" 
                                           class="btn btn-outline-primary" title="View Details">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.users.edit', $user->id) }}" 
                                           class="btn btn-outline-secondary" title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-5">
                                    <i class="bi bi-inbox text-muted" style="font-size: 3rem;"></i>
                                    <p class="text-muted mt-2">No users found</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($users->hasPages())
            <div class="card-footer">
                {{ $users->links() }}
            </div>
        @endif
    </div>
</div>

<style>
    .stat-card {
        border: none;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }
    
    .avatar-circle {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: #0F1A3C;
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
    }
    
    .table th {
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 0.5px;
    }
</style>
@endsection
