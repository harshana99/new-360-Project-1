@extends('layouts.admin')

@section('title', 'Edit User - ' . $user->name)

@section('content')
<div class="container-fluid py-4">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <a href="{{ route('admin.users.show', $user->id) }}" class="btn btn-outline-secondary btn-sm mb-2">
                <i class="bi bi-arrow-left me-1"></i>Back to User Details
            </a>
            <h2 class="mb-0">Edit User</h2>
            <p class="text-muted">{{ $user->name }} (ID: #{{ $user->id }})</p>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-pencil-square me-2"></i>User Information</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
                        @csrf

                        <!-- Basic Information -->
                        <h6 class="text-gold mb-3">
                            <i class="bi bi-person-fill me-2"></i>Basic Information
                        </h6>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="name" class="form-label">
                                    Full Name <span class="text-danger">*</span>
                                </label>
                                <input 
                                    type="text" 
                                    class="form-control @error('name') is-invalid @enderror" 
                                    id="name" 
                                    name="name" 
                                    value="{{ old('name', $user->name) }}" 
                                    required
                                >
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">
                                    Email Address <span class="text-danger">*</span>
                                </label>
                                <input 
                                    type="email" 
                                    class="form-control @error('email') is-invalid @enderror" 
                                    id="email" 
                                    name="email" 
                                    value="{{ old('email', $user->email) }}" 
                                    required
                                >
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="phone" class="form-label">
                                    Phone Number <span class="text-danger">*</span>
                                </label>
                                <input 
                                    type="tel" 
                                    class="form-control @error('phone') is-invalid @enderror" 
                                    id="phone" 
                                    name="phone" 
                                    value="{{ old('phone', $user->phone) }}" 
                                    required
                                >
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="date_of_birth" class="form-label">Date of Birth</label>
                                <input 
                                    type="date" 
                                    class="form-control @error('date_of_birth') is-invalid @enderror" 
                                    id="date_of_birth" 
                                    name="date_of_birth" 
                                    value="{{ old('date_of_birth', $user->date_of_birth ? \Carbon\Carbon::parse($user->date_of_birth)->format('Y-m-d') : '') }}" 
                                    max="{{ now()->subYears(18)->format('Y-m-d') }}"
                                >
                                <small class="text-muted">Must be at least 18 years old</small>
                                @error('date_of_birth')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <hr class="my-4">

                        <!-- Account Settings -->
                        <h6 class="text-gold mb-3">
                            <i class="bi bi-gear-fill me-2"></i>Account Settings
                        </h6>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="membership_type" class="form-label">
                                    Membership Type <span class="text-danger">*</span>
                                </label>
                                <select 
                                    class="form-select @error('membership_type') is-invalid @enderror" 
                                    id="membership_type" 
                                    name="membership_type" 
                                    required
                                >
                                    <option value="">Select Type</option>
                                    <option value="owner" {{ old('membership_type', $user->membership_type) === 'owner' ? 'selected' : '' }}>
                                        Owner
                                    </option>
                                    <option value="investor" {{ old('membership_type', $user->membership_type) === 'investor' ? 'selected' : '' }}>
                                        Investor
                                    </option>
                                    <option value="marketer" {{ old('membership_type', $user->membership_type) === 'marketer' ? 'selected' : '' }}>
                                        Marketer
                                    </option>
                                </select>
                                @error('membership_type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="status" class="form-label">
                                    Account Status <span class="text-danger">*</span>
                                </label>
                                <select 
                                    class="form-select @error('status') is-invalid @enderror" 
                                    id="status" 
                                    name="status" 
                                    required
                                >
                                    <option value="registered" {{ old('status', $user->status) === 'registered' ? 'selected' : '' }}>
                                        Registered
                                    </option>
                                    <option value="membership_selected" {{ old('status', $user->status) === 'membership_selected' ? 'selected' : '' }}>
                                        Membership Selected
                                    </option>
                                    <option value="kyc_submitted" {{ old('status', $user->status) === 'kyc_submitted' ? 'selected' : '' }}>
                                        KYC Submitted
                                    </option>
                                    <option value="under_review" {{ old('status', $user->status) === 'under_review' ? 'selected' : '' }}>
                                        Under Review
                                    </option>
                                    <option value="approved" {{ old('status', $user->status) === 'approved' ? 'selected' : '' }}>
                                        Approved
                                    </option>
                                    <option value="rejected" {{ old('status', $user->status) === 'rejected' ? 'selected' : '' }}>
                                        Rejected
                                    </option>
                                    <option value="suspended" {{ old('status', $user->status) === 'suspended' ? 'selected' : '' }}>
                                        Suspended
                                    </option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <hr class="my-4">

                        <!-- Address Information -->
                        <h6 class="text-gold mb-3">
                            <i class="bi bi-geo-alt-fill me-2"></i>Address Information
                        </h6>

                        <div class="mb-3">
                            <label for="address" class="form-label">Street Address</label>
                            <textarea 
                                class="form-control @error('address') is-invalid @enderror" 
                                id="address" 
                                name="address" 
                                rows="2"
                            >{{ old('address', $user->address) }}</textarea>
                            @error('address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="city" class="form-label">City</label>
                                <input 
                                    type="text" 
                                    class="form-control @error('city') is-invalid @enderror" 
                                    id="city" 
                                    name="city" 
                                    value="{{ old('city', $user->city) }}"
                                >
                                @error('city')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="state" class="form-label">State/Province</label>
                                <input 
                                    type="text" 
                                    class="form-control @error('state') is-invalid @enderror" 
                                    id="state" 
                                    name="state" 
                                    value="{{ old('state', $user->state) }}"
                                >
                                @error('state')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="country" class="form-label">Country</label>
                                <input 
                                    type="text" 
                                    class="form-control @error('country') is-invalid @enderror" 
                                    id="country" 
                                    name="country" 
                                    value="{{ old('country', $user->country) }}"
                                >
                                @error('country')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="postal_code" class="form-label">Postal Code</label>
                                <input 
                                    type="text" 
                                    class="form-control @error('postal_code') is-invalid @enderror" 
                                    id="postal_code" 
                                    name="postal_code" 
                                    value="{{ old('postal_code', $user->postal_code) }}"
                                >
                                @error('postal_code')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <hr class="my-4">

                        <!-- Bio -->
                        <h6 class="text-gold mb-3">
                            <i class="bi bi-card-text me-2"></i>Additional Information
                        </h6>

                        <div class="mb-4">
                            <label for="bio" class="form-label">Bio</label>
                            <textarea 
                                class="form-control @error('bio') is-invalid @enderror" 
                                id="bio" 
                                name="bio" 
                                rows="4" 
                                maxlength="500"
                                placeholder="Brief description about the user..."
                            >{{ old('bio', $user->bio) }}</textarea>
                            <small class="text-muted">Maximum 500 characters</small>
                            @error('bio')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Action Buttons -->
                        <div class="d-flex gap-2 justify-content-end">
                            <a href="{{ route('admin.users.show', $user->id) }}" class="btn btn-outline-secondary">
                                <i class="bi bi-x-circle me-1"></i>Cancel
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle me-1"></i>Update User
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Warning Notice -->
            <div class="alert alert-warning mt-3">
                <i class="bi bi-exclamation-triangle me-2"></i>
                <strong>Note:</strong> Changes to user information will be logged and the user will be notified via email.
            </div>
        </div>
    </div>
</div>

<style>
    .text-gold {
        color: #E4B400;
    }
    
    @media (max-width: 768px) {
        .d-flex.gap-2 {
            flex-direction: column;
        }
        
        .d-flex.gap-2 .btn {
            width: 100%;
        }
    }
</style>
@endsection
