@extends('layouts.admin')

@section('title', 'Manage Admins')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="text-navy fw-bold">Manage Admins</h2>
            <p class="text-muted">Overview of all system administrators</p>
        </div>
        <a href="{{ route('admin.create') }}" class="btn btn-gold">
            <i class="bi bi-plus-circle me-2"></i>Create New Admin
        </a>
    </div>

    <!-- Stats Overview -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h6 class="text-muted text-uppercase small fw-bold">Total Admins</h6>
                    <h2 class="mb-0 text-navy">{{ $admins->count() }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h6 class="text-muted text-uppercase small fw-bold">Super Admins</h6>
                    <h2 class="mb-0 text-danger">{{ $admins->where('admin_role', 'super_admin')->count() }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h6 class="text-muted text-uppercase small fw-bold">Active</h6>
                    <h2 class="mb-0 text-success">{{ $admins->where('status', 'active')->count() }}</h2>
                </div>
            </div>
        </div>
    </div>

    <!-- Admins Table -->
    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
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
                                    <div class="avatar-circle me-3 bg-navy text-white d-flex align-items-center justify-content-center rounded-circle" style="width: 40px; height: 40px;">
                                        {{ substr($adminItem->user->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <div class="fw-bold text-navy">{{ $adminItem->user->name }}</div>
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
                            <td>
                                @if($adminItem->creator)
                                    {{ $adminItem->creator->name }}
                                @else
                                    <span class="text-muted fst-italic">System</span>
                                @endif
                                <div class="small text-muted">{{ $adminItem->created_at->format('M d, Y') }}</div>
                            </td>
                            <td>
                                @if($adminItem->last_login)
                                    {{ $adminItem->last_login->diffForHumans() }}
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td class="text-end pe-4">
                                @if(auth()->user()->admin->id !== $adminItem->id)
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-light" type="button" data-bs-toggle="dropdown">
                                            <i class="bi bi-three-dots-vertical"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end border-0 shadow">
                                            <li>
                                                <a class="dropdown-item" href="{{ route('admin.edit', $adminItem->id) }}">
                                                    <i class="bi bi-pencil me-2 text-primary"></i> Edit Role
                                                </a>
                                            </li>
                                            <li><hr class="dropdown-divider"></li>
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
                                    <i class="bi bi-people display-4 mb-3 d-block"></i>
                                    No admins found.
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<style>
    .text-navy { color: #0F1A3C; }
    .bg-navy { background-color: #0F1A3C; }
    .btn-gold { 
        background: linear-gradient(135deg, #E4B400 0%, #f5c842 100%);
        color: #0F1A3C;
        font-weight: 600;
        border: none;
    }
    .btn-gold:hover { 
        background: linear-gradient(135deg, #f5c842 0%, #E4B400 100%);
        color: #0F1A3C;
    }
</style>
@endsection
