@extends('layouts.app')

@section('title', 'Edit Profile - 360WinEstate')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <!-- Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="mb-0">
                    <i class="bi bi-pencil-square me-2"></i>Edit Profile
                </h2>
                <a href="{{ route('user.profile') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-1"></i>Back to Profile
                </a>
            </div>

            <!-- Edit Form Card -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Update Your Information</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('user.profile.update') }}" method="POST">
                        @csrf

                        <!-- Basic Information Section -->
                        <h6 class="text-gold mb-3">
                            <i class="bi bi-person-fill me-2"></i>Basic Information
                        </h6>

                        <!-- Full Name -->
                        <div class="mb-3">
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

                        <!-- Email (Read-only) -->
                        <div class="mb-3">
                            <label for="email" class="form-label">Email Address</label>
                            <input 
                                type="email" 
                                class="form-control" 
                                id="email" 
                                value="{{ $user->email }}" 
                                readonly
                                disabled
                            >
                            <small class="text-muted">Email cannot be changed</small>
                        </div>

                        <!-- Phone Number -->
                        <div class="mb-4">
                            <label for="phone" class="form-label">
                                Phone Number <span class="text-danger">*</span>
                            </label>
                            <input 
                                type="tel" 
                                class="form-control @error('phone') is-invalid @enderror" 
                                id="phone" 
                                name="phone" 
                                value="{{ old('phone', $user->phone) }}" 
                                placeholder="+234 800 000 0000"
                                required
                            >
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Personal Information Section -->
                        <h6 class="text-gold mb-3 mt-4">
                            <i class="bi bi-calendar-event me-2"></i>Personal Information
                        </h6>

                        <!-- Date of Birth -->
                        <div class="mb-3">
                            <label for="date_of_birth" class="form-label">Date of Birth</label>
                            <input 
                                type="date" 
                                class="form-control @error('date_of_birth') is-invalid @enderror" 
                                id="date_of_birth" 
                                name="date_of_birth" 
                                value="{{ old('date_of_birth', $user->date_of_birth ? \Carbon\Carbon::parse($user->date_of_birth)->format('Y-m-d') : '') }}"
                                max="{{ now()->subYears(18)->format('Y-m-d') }}"
                            >
                            <small class="text-muted">You must be at least 18 years old</small>
                            @error('date_of_birth')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Address Information Section -->
                        <h6 class="text-gold mb-3 mt-4">
                            <i class="bi bi-geo-alt-fill me-2"></i>Address Information
                        </h6>

                        <!-- Street Address -->
                        <div class="mb-3">
                            <label for="address" class="form-label">Street Address</label>
                            <textarea 
                                class="form-control @error('address') is-invalid @enderror" 
                                id="address" 
                                name="address" 
                                rows="2"
                                placeholder="Enter your street address"
                            >{{ old('address', $user->address) }}</textarea>
                            @error('address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- City and State -->
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="city" class="form-label">City</label>
                                <input 
                                    type="text" 
                                    class="form-control @error('city') is-invalid @enderror" 
                                    id="city" 
                                    name="city" 
                                    value="{{ old('city', $user->city) }}"
                                    placeholder="Lagos"
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
                                    placeholder="Lagos State"
                                >
                                @error('state')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Country and Postal Code -->
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="country" class="form-label">Country</label>
                                <input 
                                    type="text" 
                                    class="form-control @error('country') is-invalid @enderror" 
                                    id="country" 
                                    name="country" 
                                    value="{{ old('country', $user->country) }}"
                                    placeholder="Nigeria"
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
                                    placeholder="100001"
                                >
                                @error('postal_code')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Bio Section -->
                        <h6 class="text-gold mb-3 mt-4">
                            <i class="bi bi-chat-left-text me-2"></i>About You
                        </h6>

                        <!-- Bio -->
                        <div class="mb-4">
                            <label for="bio" class="form-label">Bio/Description</label>
                            <textarea 
                                class="form-control @error('bio') is-invalid @enderror" 
                                id="bio" 
                                name="bio" 
                                rows="4"
                                maxlength="500"
                                placeholder="Tell us a bit about yourself..."
                            >{{ old('bio', $user->bio) }}</textarea>
                            <small class="text-muted">Maximum 500 characters</small>
                            @error('bio')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Action Buttons -->
                        <div class="d-flex gap-2 justify-content-end">
                            <a href="{{ route('user.profile') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-x-circle me-1"></i>Cancel
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle me-1"></i>Save Changes
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Help Text -->
            <div class="alert alert-info mt-3">
                <i class="bi bi-info-circle me-2"></i>
                <strong>Note:</strong> Your email address cannot be changed. If you need to update it, please contact support.
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
