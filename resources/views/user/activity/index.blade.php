@extends('layouts.app')

@section('title', 'Account Activity - 360WinEstate')

@section('content')
<div class="container py-4">
    <div class="row">
        <!-- Header -->
        <div class="col-12 mb-4">
            <div class="d-flex justify-content-between align-items-center">
                <h2 class="mb-0">
                    <i class="bi bi-clock-history me-2"></i>Account Activity Log
                </h2>
                <a href="{{ route('user.profile') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-1"></i>Back to Profile
                </a>
            </div>
        </div>

        <!-- Filters -->
        <div class="col-12 mb-4">
            <div class="card">
                <div class="card-body">
                    <form method="GET" action="{{ route('user.activity') }}" class="row g-3">
                        <!-- Activity Type Filter -->
                        <div class="col-md-4">
                            <label for="type" class="form-label">Activity Type</label>
                            <select class="form-select" id="type" name="type">
                                <option value="">All Activities</option>
                                @foreach($activityTypes as $type)
                                    <option value="{{ $type }}" {{ $activityType === $type ? 'selected' : '' }}>
                                        {{ ucfirst(str_replace('_', ' ', $type)) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Date From -->
                        <div class="col-md-3">
                            <label for="date_from" class="form-label">From Date</label>
                            <input 
                                type="date" 
                                class="form-control" 
                                id="date_from" 
                                name="date_from" 
                                value="{{ $dateFrom }}"
                            >
                        </div>

                        <!-- Date To -->
                        <div class="col-md-3">
                            <label for="date_to" class="form-label">To Date</label>
                            <input 
                                type="date" 
                                class="form-control" 
                                id="date_to" 
                                name="date_to" 
                                value="{{ $dateTo }}"
                            >
                        </div>

                        <!-- Filter Buttons -->
                        <div class="col-md-2 d-flex align-items-end">
                            <div class="btn-group w-100">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-funnel me-1"></i>Filter
                                </button>
                                <a href="{{ route('user.activity') }}" class="btn btn-outline-secondary">
                                    <i class="bi bi-x-circle"></i>
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Activity Timeline -->
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Activity Timeline</h5>
                    <span class="badge bg-secondary">{{ $activities->total() }} Total Activities</span>
                </div>
                <div class="card-body">
                    @if($activities->count() > 0)
                        <div class="activity-timeline">
                            @foreach($activities as $activity)
                                <div class="activity-item d-flex mb-4 pb-4 border-bottom">
                                    <!-- Activity Icon -->
                                    <div class="activity-icon me-3">
                                        <div class="icon-circle">
                                            <i class="{{ $activity->getIconClass() }}" style="font-size: 1.5rem;"></i>
                                        </div>
                                    </div>

                                    <!-- Activity Content -->
                                    <div class="activity-content flex-grow-1">
                                        <!-- Activity Header -->
                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                            <div>
                                                <h6 class="mb-1">{{ $activity->getTypeLabel() }}</h6>
                                                <p class="text-muted mb-0">{{ $activity->description }}</p>
                                            </div>
                                            <span class="badge bg-light text-dark">
                                                {{ $activity->created_at->format('M d, Y') }}
                                            </span>
                                        </div>

                                        <!-- Activity Details -->
                                        <div class="activity-details">
                                            <small class="text-muted d-block">
                                                <i class="bi bi-clock me-1"></i>
                                                {{ $activity->created_at->format('h:i A') }}
                                                <span class="mx-2">•</span>
                                                {{ $activity->created_at->diffForHumans() }}
                                            </small>

                                            @if($activity->admin)
                                                <small class="text-muted d-block mt-1">
                                                    <i class="bi bi-person-badge me-1"></i>
                                                    Performed by: <strong>{{ $activity->admin->user->name }}</strong> ({{ ucfirst($activity->admin->admin_role) }} Admin)
                                                </small>
                                            @endif

                                            @if($activity->ip_address)
                                                <small class="text-muted d-block mt-1">
                                                    <i class="bi bi-geo-alt me-1"></i>
                                                    IP: {{ $activity->ip_address }}
                                                </small>
                                            @endif

                                            <!-- Metadata (if exists) -->
                                            @if($activity->metadata && is_array($activity->metadata) && count($activity->metadata) > 0)
                                                <div class="mt-2">
                                                    <button class="btn btn-sm btn-outline-secondary" type="button" data-bs-toggle="collapse" data-bs-target="#metadata-{{ $activity->id }}">
                                                        <i class="bi bi-info-circle me-1"></i>View Details
                                                    </button>
                                                    <div class="collapse mt-2" id="metadata-{{ $activity->id }}">
                                                        <div class="alert alert-light mb-0">
                                                            <small>
                                                                @foreach($activity->metadata as $key => $value)
                                                                    <strong>{{ ucfirst(str_replace('_', ' ', $key)) }}:</strong> 
                                                                    {{ is_array($value) ? json_encode($value) : $value }}
                                                                    <br>
                                                                @endforeach
                                                            </small>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Pagination -->
                        <div class="d-flex justify-content-center mt-4">
                            {{ $activities->links() }}
                        </div>
                    @else
                        <!-- Empty State -->
                        <div class="text-center py-5">
                            <i class="bi bi-inbox text-muted" style="font-size: 4rem;"></i>
                            <h5 class="mt-3 text-muted">No Activities Found</h5>
                            <p class="text-muted">
                                @if($activityType || $dateFrom || $dateTo)
                                    No activities match your filter criteria. Try adjusting your filters.
                                @else
                                    Your account activity will appear here.
                                @endif
                            </p>
                            @if($activityType || $dateFrom || $dateTo)
                                <a href="{{ route('user.activity') }}" class="btn btn-primary mt-2">
                                    <i class="bi bi-arrow-clockwise me-1"></i>Clear Filters
                                </a>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Activity Summary -->
        @if($activities->total() > 0)
            <div class="col-12 mt-4">
                <div class="card">
                    <div class="card-body">
                        <h6 class="mb-3">
                            <i class="bi bi-bar-chart me-2"></i>Activity Summary
                        </h6>
                        <div class="row text-center">
                            <div class="col-md-3 mb-3">
                                <div class="p-3 bg-light rounded">
                                    <h4 class="mb-1 text-primary">{{ $activities->total() }}</h4>
                                    <small class="text-muted">Total Activities</small>
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <div class="p-3 bg-light rounded">
                                    <h4 class="mb-1 text-success">{{ $activities->where('activity_type', 'login')->count() }}</h4>
                                    <small class="text-muted">Logins</small>
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <div class="p-3 bg-light rounded">
                                    <h4 class="mb-1 text-info">{{ $activities->where('activity_type', 'profile_update')->count() }}</h4>
                                    <small class="text-muted">Profile Updates</small>
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <div class="p-3 bg-light rounded">
                                    <h4 class="mb-1 text-warning">{{ $activities->whereIn('activity_type', ['kyc_submitted', 'kyc_resubmitted'])->count() }}</h4>
                                    <small class="text-muted">KYC Submissions</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>

<style>
    .activity-timeline .activity-item:last-child {
        border-bottom: none !important;
        margin-bottom: 0 !important;
        padding-bottom: 0 !important;
    }

    .icon-circle {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        background: #f8f9fa;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 2px solid #dee2e6;
    }

    .activity-item {
        position: relative;
    }

    .activity-item:not(:last-child)::before {
        content: '';
        position: absolute;
        left: 24px;
        top: 60px;
        bottom: -20px;
        width: 2px;
        background: #dee2e6;
    }

    @media (max-width: 768px) {
        .btn-group {
            flex-direction: column;
        }

        .btn-group .btn {
            border-radius: 0.375rem !important;
            margin-bottom: 0.5rem;
        }

        .activity-icon {
            margin-right: 1rem !important;
        }

        .icon-circle {
            width: 40px;
            height: 40px;
        }

        .icon-circle i {
            font-size: 1.2rem !important;
        }
    }
</style>
@endsection
