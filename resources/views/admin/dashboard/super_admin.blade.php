@extends('layouts.admin')

@section('title', 'Super Admin Dashboard')

@section('content')
<div class="row g-4 mb-4">
    <!-- Top Stats -->
    <div class="col-md-4">
        <div class="stats-card d-flex align-items-center justify-content-between">
            <div>
                <h3>Total Users</h3>
                <div class="value">{{ $totalUsers }}</div>
                <div class="subtext">Registered Accounts</div>
            </div>
            <div class="icon-circle bg-primary bg-opacity-10 text-primary p-3 rounded-circle" style="width: 60px; height: 60px; display: flex; align-items: center; justify-content: center;">
                <i class="bi bi-people fs-3"></i>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stats-card d-flex align-items-center justify-content-between">
            <div>
                <h3>Approved Users</h3>
                <div class="value">{{ $approvedUsers }}</div>
                <div class="subtext">Active & Verified</div>
            </div>
            <div class="icon-circle bg-success bg-opacity-10 text-success p-3 rounded-circle" style="width: 60px; height: 60px; display: flex; align-items: center; justify-content: center;">
                <i class="bi bi-check-circle fs-3"></i>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stats-card d-flex align-items-center justify-content-between">
            <div>
                <h3>Active Admins</h3>
                <div class="value">{{ $activeAdmins }}</div>
                <div class="subtext">System Administrators</div>
            </div>
            <div class="icon-circle bg-warning bg-opacity-10 text-warning p-3 rounded-circle" style="width: 60px; height: 60px; display: flex; align-items: center; justify-content: center;">
                <i class="bi bi-shield-check fs-3"></i>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <!-- Quick Actions -->
    <div class="col-lg-6">
        <div class="stats-card">
            <h5 class="text-white mb-4 fw-bold">Quick Actions</h5>
            <div class="d-flex flex-wrap gap-2">
                <a href="{{ route('admin.create') }}" class="btn btn-warning fw-bold px-4 py-2">
                    <i class="bi bi-plus-circle me-2"></i>Create Admin
                </a>
                <a href="{{ route('admin.admins') }}" class="btn btn-outline-light px-4 py-2">
                    Manage Admins
                </a>
                <a href="{{ route('admin.users') }}" class="btn btn-outline-light px-4 py-2">
                    View Users
                </a>
            </div>
        </div>
    </div>

    <!-- Pending Items -->
    <div class="col-lg-6">
        <div class="stats-card">
            <h5 class="text-white mb-4 fw-bold">Pending Actions</h5>
            <div class="row">
                <div class="col-6">
                    <div class="p-3 rounded border border-secondary border-opacity-25 bg-dark bg-opacity-25 d-flex align-items-center">
                        <i class="bi bi-file-earmark-text text-warning fs-1 me-3"></i>
                        <div>
                            <h3 class="text-white mb-0">{{ $pendingKYC }}</h3>
                            <small class="text-muted">Pending KYC</small>
                        </div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="p-3 rounded border border-secondary border-opacity-25 bg-dark bg-opacity-25 d-flex align-items-center">
                        <i class="bi bi-building-exclamation text-warning fs-1 me-3"></i>
                        <div>
                            <h3 class="text-white mb-0">{{ $pendingProperties }}</h3>
                            <small class="text-muted">Pending Props</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recent Admins Table -->
@if($recentAdmins->count() > 0)
<div class="stats-card">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h5 class="text-white mb-0 fw-bold">Recent Admins</h5>
        <a href="{{ route('admin.admins') }}" class="text-warning text-decoration-none small">View All</a>
    </div>
    <div class="table-responsive">
        <table class="table table-dark-custom w-100">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Role</th>
                    <th>Status</th>
                    <th>Created</th>
                    <th class="text-end">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($recentAdmins as $recentAdmin)
                <tr>
                    <td class="text-white fw-medium">
                        <div class="d-flex align-items-center">
                            <div class="rounded-circle bg-secondary bg-opacity-25 me-3 d-flex align-items-center justify-content-center" style="width: 35px; height: 35px; font-size: 14px;">
                                {{ substr($recentAdmin->user->name, 0, 1) }}
                            </div>
                            {{ $recentAdmin->user->name }}
                        </div>
                    </td>
                    <td><span class="badge {{ $recentAdmin->getRoleBadgeClass() }}">{{ $recentAdmin->getRoleLabel() }}</span></td>
                    <td><span class="badge {{ $recentAdmin->getStatusBadgeClass() }}">{{ ucfirst($recentAdmin->status) }}</span></td>
                    <td class="text-muted">{{ $recentAdmin->created_at->diffForHumans() }}</td>
                    <td class="text-end">
                        <a href="#" class="btn btn-sm btn-outline-light border-0"><i class="bi bi-three-dots-vertical"></i></a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif

@endsection
