@extends('layouts.admin')

@section('title', 'Manage Admins')

@section('content')
<div class="row mb-4">
    <div class="col-12 d-flex justify-content-between align-items-center">
        <div>
            <h2 class="text-white fw-bold mb-1">Manage Admins</h2>
            <p class="text-muted">Overview of all system administrators</p>
        </div>
        <a href="{{ route('admin.create') }}" class="btn btn-warning fw-bold px-4 py-2">
            <i class="bi bi-plus-circle me-2"></i>Create New Admin
        </a>
    </div>
</div>

<!-- Stats Overview -->
<div class="row g-4 mb-4">
    <div class="col-md-4">
        <div class="stats-card">
            <h6 class="text-white text-opacity-75 text-uppercase small fw-bold">Total Admins</h6>
            <h2 class="mb-0 text-white">{{ $admins->count() }}</h2>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stats-card">
            <h6 class="text-white text-opacity-75 text-uppercase small fw-bold">Super Admins</h6>
            <h2 class="mb-0 text-danger">{{ $admins->where('admin_role', 'super_admin')->count() }}</h2>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stats-card">
            <h6 class="text-white text-opacity-75 text-uppercase small fw-bold">Active</h6>
            <h2 class="mb-0 text-success">{{ $admins->where('status', 'active')->count() }}</h2>
        </div>
    </div>
</div>

<!-- Admins Table -->
<div class="stats-card p-0 overflow-hidden">
    <div class="table-responsive">
        <table class="table table-dark-custom mb-0">
            <thead>
                <tr>
                    <th class="ps-4">Admin User</th>
                    <th>Role</th>
                    <th>Status</th>
                    <th>Created By</th>
                    <th>Last Login</th>
                    <th class="text-end pe-4">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($admins as $adminItem)
                <tr>
                    <td class="ps-4">
                        <div class="d-flex align-items-center">
                            <div class="avatar-circle me-3 bg-primary bg-opacity-25 text-white d-flex align-items-center justify-content-center rounded-circle" style="width: 40px; height: 40px;">
                                {{ substr($adminItem->user->name, 0, 1) }}
                            </div>
                            <div>
                                <div class="fw-bold text-white">{{ $adminItem->user->name }}</div>
                                <div class="small text-muted">{{ $adminItem->user->email }}</div>
                            </div>
                        </div>
                    </td>
                    <td>
                        <span class="badge rounded-pill {{ $adminItem->getRoleBadgeClass() }}">
                            {{ $adminItem->getRoleLabel() }}
                        </span>
                    </td>
                    <td>
                        <span class="badge {{ $adminItem->getStatusBadgeClass() }}">
                            {{ ucfirst($adminItem->status) }}
                        </span>
                    </td>
                    <td class="text-white">
                        @if($adminItem->creator)
                            {{ $adminItem->creator->name }}
                        @else
                            <span class="text-muted fst-italic">System</span>
                        @endif
                        <div class="small text-muted">{{ $adminItem->created_at->format('M d, Y') }}</div>
                    </td>
                    <td class="text-white">
                        @if($adminItem->last_login)
                            {{ $adminItem->last_login->diffForHumans() }}
                        @else
                            <span class="text-muted">-</span>
                        @endif
                    </td>
                    <td class="text-end pe-4">
                        @if(auth()->user()->admin->id !== $adminItem->id)
                            <div class="dropdown">
                                <button class="btn btn-sm btn-outline-light border-0" type="button" data-bs-toggle="dropdown">
                                    <i class="bi bi-three-dots-vertical"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end shadow" style="background: #1E293B; border: 1px solid rgba(255,255,255,0.1);">
                                    <li>
                                        <a class="dropdown-item text-white" href="{{ route('admin.edit', $adminItem->id) }}">
                                            <i class="bi bi-pencil me-2 text-primary"></i> Edit Role
                                        </a>
                                    </li>
                                    <li><hr class="dropdown-divider bg-secondary"></li>
                                    @if($adminItem->status === 'active')
                                    <li>
                                        <form action="{{ route('admin.deactivate', $adminItem->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="dropdown-item text-danger">
                                                <i class="bi bi-person-slash me-2"></i> Deactivate
                                            </button>
                                        </form>
                                    </li>
                                    @else
                                    <li>
                                        <form action="{{ route('admin.activate', $adminItem->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="dropdown-item text-success">
                                                <i class="bi bi-person-check me-2"></i> Activate
                                            </button>
                                        </form>
                                    </li>
                                    @endif
                                </ul>
                            </div>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center py-5">
                        <div class="text-muted">
                            <i class="bi bi-people display-4 mb-3 d-block opacity-25"></i>
                            No admins found.
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
