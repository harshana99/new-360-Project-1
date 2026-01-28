@extends('layouts.admin')

@section('title', 'Edit Admin Role')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 fw-bold text-navy">Edit Admin Role</h5>
                        <a href="{{ route('admin.admins') }}" class="btn btn-outline-secondary btn-sm">
                            <i class="bi bi-arrow-left me-1"></i> Back
                        </a>
                    </div>
                </div>
                <div class="card-body p-4">
                    <div class="d-flex align-items-center mb-4 p-3 bg-light rounded">
                        <div class="avatar-circle bg-navy text-white d-flex align-items-center justify-content-center rounded-circle me-3" style="width: 50px; height: 50px; font-size: 20px;">
                            {{ substr($adminToEdit->user->name, 0, 1) }}
                        </div>
                        <div>
                            <h5 class="mb-0 fw-bold">{{ $adminToEdit->user->name }}</h5>
                            <div class="text-muted">{{ $adminToEdit->user->email }}</div>
                        </div>
                    </div>

                    <form action="{{ route('admin.update', $adminToEdit->id) }}" method="POST">
                        @csrf
                        
                        <!-- Role Selection -->
                        <div class="mb-4">
                            <label class="form-label fw-bold">Select Role</label>
                            <div class="row g-3">
                                @foreach($roles as $roleKey => $roleLabel)
                                <div class="col-md-12">
                                    <div class="form-check p-3 border rounded hover-bg-light {{ $adminToEdit->admin_role == $roleKey ? 'border-warning bg-light' : '' }}">
                                        <input class="form-check-input mt-1" type="radio" name="admin_role" id="role_{{ $roleKey }}" value="{{ $roleKey }}" {{ $adminToEdit->admin_role == $roleKey ? 'checked' : '' }} required>
                                        <label class="form-check-label ms-2 d-block cursor-pointer" for="role_{{ $roleKey }}">
                                            <span class="fw-bold d-block">{{ $roleLabel }}</span>
                                            <small class="text-muted d-block mt-1">
                                                @if($roleKey == 'compliance_admin')
                                                    Can review and approve KYC documents.
                                                @elseif($roleKey == 'finance_admin')
                                                    Can manage payments, commissions, and withdrawals.
                                                @elseif($roleKey == 'content_admin')
                                                    Can manage projects, listings, and updates.
                                                @endif
                                            </small>
                                        </label>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            @error('admin_role')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-gold btn-lg">
                                <i class="bi bi-save me-2"></i> Update Role
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            
            <div class="card border-0 shadow-sm mt-4">
                <div class="card-body">
                    <h6 class="text-danger fw-bold mb-3">Danger Zone</h6>
                    <p class="text-muted small">Revoking admin access will immediately remove this user's ability to access the admin panel.</p>
                    
                    @if($adminToEdit->status === 'active')
                    <form action="{{ route('admin.deactivate', $adminToEdit->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to deactivate this admin?');">
                        @csrf
                        <button type="submit" class="btn btn-outline-danger w-100">
                            <i class="bi bi-person-slash me-2"></i> Deactivate Admin Access
                        </button>
                    </form>
                    @else
                    <form action="{{ route('admin.activate', $adminToEdit->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-outline-success w-100">
                            <i class="bi bi-person-check me-2"></i> Reactivate Admin Access
                        </button>
                    </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .cursor-pointer { cursor: pointer; }
    .hover-bg-light:hover { background-color: #f8f9fa; border-color: #dee2e6 !important; }
    .border-warning { border-color: #E4B400 !important; }
</style>
@endsection
