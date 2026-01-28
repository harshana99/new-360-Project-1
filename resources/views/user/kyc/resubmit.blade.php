@extends('layouts.app')

@section('title', 'Resubmit KYC - 360WinEstate')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <!-- Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="mb-0">
                    <i class="bi bi-arrow-repeat me-2"></i>Resubmit KYC
                </h2>
                <a href="{{ route('user.kyc.status') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-1"></i>Back to KYC Status
                </a>
            </div>

            <!-- Previous Rejection Notice -->
            @if($previousKyc->rejection_reason)
                <div class="alert alert-danger mb-4">
                    <h5 class="alert-heading">
                        <i class="bi bi-exclamation-triangle me-2"></i>Previous Rejection Reason
                    </h5>
                    <p class="mb-2">{{ $previousKyc->rejection_reason }}</p>
                    @if($previousKyc->admin_notes)
                        <hr>
                        <p class="mb-0"><strong>Admin Notes:</strong> {{ $previousKyc->admin_notes }}</p>
                    @endif
                </div>
            @endif

            <!-- Instructions -->
            <div class="alert alert-info mb-4">
                <h6 class="alert-heading">
                    <i class="bi bi-info-circle me-2"></i>Resubmission Instructions
                </h6>
                <ul class="mb-0">
                    <li>Review the rejection reason above carefully</li>
                    <li>Update the information that was incorrect or unclear</li>
                    <li>Upload new, clear images of your documents</li>
                    <li>Ensure all information matches your official documents</li>
                </ul>
            </div>

            <!-- Resubmission Form -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">KYC Resubmission Form</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('user.kyc.resubmit.submit') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <!-- ID Type -->
                        <div class="mb-3">
                            <label for="id_type" class="form-label">
                                ID Type <span class="text-danger">*</span>
                            </label>
                            <select 
                                class="form-select @error('id_type') is-invalid @enderror" 
                                id="id_type" 
                                name="id_type" 
                                required
                            >
                                <option value="">Select ID Type</option>
                                <option value="passport" {{ old('id_type', $previousKyc->id_type) === 'passport' ? 'selected' : '' }}>
                                    Passport
                                </option>
                                <option value="drivers_license" {{ old('id_type', $previousKyc->id_type) === 'drivers_license' ? 'selected' : '' }}>
                                    Driver's License
                                </option>
                                <option value="national_id" {{ old('id_type', $previousKyc->id_type) === 'national_id' ? 'selected' : '' }}>
                                    National ID Card
                                </option>
                            </select>
                            @error('id_type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- ID Number -->
                        <div class="mb-3">
                            <label for="id_number" class="form-label">
                                ID Number <span class="text-danger">*</span>
                            </label>
                            <input 
                                type="text" 
                                class="form-control @error('id_number') is-invalid @enderror" 
                                id="id_number" 
                                name="id_number" 
                                value="{{ old('id_number', $previousKyc->id_number) }}" 
                                required
                            >
                            @error('id_number')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Full Name -->
                        <div class="mb-3">
                            <label for="full_name" class="form-label">
                                Full Name (as on ID) <span class="text-danger">*</span>
                            </label>
                            <input 
                                type="text" 
                                class="form-control @error('full_name') is-invalid @enderror" 
                                id="full_name" 
                                name="full_name" 
                                value="{{ old('full_name', $previousKyc->full_name) }}" 
                                required
                            >
                            @error('full_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Date of Birth -->
                        <div class="mb-3">
                            <label for="date_of_birth" class="form-label">
                                Date of Birth <span class="text-danger">*</span>
                            </label>
                            <input 
                                type="date" 
                                class="form-control @error('date_of_birth') is-invalid @enderror" 
                                id="date_of_birth" 
                                name="date_of_birth" 
                                value="{{ old('date_of_birth', $previousKyc->date_of_birth ? \Carbon\Carbon::parse($previousKyc->date_of_birth)->format('Y-m-d') : '') }}" 
                                max="{{ now()->subYears(18)->format('Y-m-d') }}"
                                required
                            >
                            <small class="text-muted">You must be at least 18 years old</small>
                            @error('date_of_birth')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Address -->
                        <div class="mb-3">
                            <label for="address" class="form-label">
                                Street Address <span class="text-danger">*</span>
                            </label>
                            <textarea 
                                class="form-control @error('address') is-invalid @enderror" 
                                id="address" 
                                name="address" 
                                rows="2"
                                required
                            >{{ old('address', $previousKyc->address) }}</textarea>
                            @error('address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- City and State -->
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="city" class="form-label">
                                    City <span class="text-danger">*</span>
                                </label>
                                <input 
                                    type="text" 
                                    class="form-control @error('city') is-invalid @enderror" 
                                    id="city" 
                                    name="city" 
                                    value="{{ old('city', $previousKyc->city) }}" 
                                    required
                                >
                                @error('city')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="state" class="form-label">
                                    State/Province <span class="text-danger">*</span>
                                </label>
                                <input 
                                    type="text" 
                                    class="form-control @error('state') is-invalid @enderror" 
                                    id="state" 
                                    name="state" 
                                    value="{{ old('state', $previousKyc->state) }}" 
                                    required
                                >
                                @error('state')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Country and Postal Code -->
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="country" class="form-label">
                                    Country <span class="text-danger">*</span>
                                </label>
                                <input 
                                    type="text" 
                                    class="form-control @error('country') is-invalid @enderror" 
                                    id="country" 
                                    name="country" 
                                    value="{{ old('country', $previousKyc->country) }}" 
                                    required
                                >
                                @error('country')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="postal_code" class="form-label">
                                    Postal Code <span class="text-danger">*</span>
                                </label>
                                <input 
                                    type="text" 
                                    class="form-control @error('postal_code') is-invalid @enderror" 
                                    id="postal_code" 
                                    name="postal_code" 
                                    value="{{ old('postal_code', $previousKyc->postal_code) }}" 
                                    required
                                >
                                @error('postal_code')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <hr class="my-4">

                        <!-- Document Uploads -->
                        <h6 class="text-gold mb-3">
                            <i class="bi bi-cloud-upload me-2"></i>Upload New Documents
                        </h6>

                        <!-- Previous Documents Notice -->
                        <div class="alert alert-warning mb-3">
                            <i class="bi bi-exclamation-triangle me-2"></i>
                            <strong>Important:</strong> You must upload new, clear images of your documents. Previous documents will not be used.
                        </div>

                        <!-- ID Image -->
                        <div class="mb-3">
                            <label for="id_image" class="form-label">
                                ID Document Image <span class="text-danger">*</span>
                            </label>
                            <input 
                                type="file" 
                                class="form-control @error('id_image') is-invalid @enderror" 
                                id="id_image" 
                                name="id_image" 
                                accept="image/jpeg,image/png,image/jpg"
                                required
                            >
                            <small class="text-muted">
                                Accepted formats: JPG, PNG. Max size: 5MB. Ensure the image is clear and all details are visible.
                            </small>
                            @error('id_image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div id="id_image_preview" class="mt-2" style="display: none;">
                                <img src="" alt="ID Preview" class="img-thumbnail" style="max-height: 200px;">
                            </div>
                        </div>

                        <!-- Address Proof -->
                        <div class="mb-4">
                            <label for="address_proof" class="form-label">
                                Address Proof <span class="text-danger">*</span>
                            </label>
                            <input 
                                type="file" 
                                class="form-control @error('address_proof') is-invalid @enderror" 
                                id="address_proof" 
                                name="address_proof" 
                                accept="image/jpeg,image/png,image/jpg,application/pdf"
                                required
                            >
                            <small class="text-muted">
                                Utility bill, bank statement, or government letter. Accepted formats: JPG, PNG, PDF. Max size: 5MB.
                            </small>
                            @error('address_proof')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div id="address_proof_preview" class="mt-2" style="display: none;">
                                <img src="" alt="Address Proof Preview" class="img-thumbnail" style="max-height: 200px;">
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="d-flex gap-2 justify-content-end">
                            <a href="{{ route('user.kyc.status') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-x-circle me-1"></i>Cancel
                            </a>
                            <button type="submit" class="btn btn-warning">
                                <i class="bi bi-arrow-repeat me-1"></i>Resubmit KYC
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Image preview for ID
    const idImageInput = document.getElementById('id_image');
    const idImagePreview = document.getElementById('id_image_preview');
    
    idImageInput?.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file && file.type.startsWith('image/')) {
            const reader = new FileReader();
            reader.onload = function(e) {
                idImagePreview.querySelector('img').src = e.target.result;
                idImagePreview.style.display = 'block';
            };
            reader.readAsDataURL(file);
        }
    });
    
    // Image preview for Address Proof
    const addressProofInput = document.getElementById('address_proof');
    const addressProofPreview = document.getElementById('address_proof_preview');
    
    addressProofInput?.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file && file.type.startsWith('image/')) {
            const reader = new FileReader();
            reader.onload = function(e) {
                addressProofPreview.querySelector('img').src = e.target.result;
                addressProofPreview.style.display = 'block';
            };
            reader.readAsDataURL(file);
        } else if (file && file.type === 'application/pdf') {
            addressProofPreview.style.display = 'none';
        }
    });
});
</script>

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
