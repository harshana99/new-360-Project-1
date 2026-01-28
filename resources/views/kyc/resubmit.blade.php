@extends('layouts.app')

@section('title', 'Resubmit KYC - 360WinEstate')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <!-- Header -->
            <div class="text-center mb-4">
                <h2 class="fw-bold text-navy mb-3">Resubmit Your KYC</h2>
                <p class="text-muted">Please correct the issues and resubmit your documents</p>
            </div>

            <!-- Previous Rejection Info -->
            @if($previousSubmission->rejection_reason || $previousSubmission->admin_notes)
            <div class="alert alert-warning border-0 shadow-sm mb-4">
                <div class="d-flex align-items-start">
                    <i class="bi bi-exclamation-triangle-fill fs-3 me-3"></i>
                    <div class="flex-grow-1">
                        <h5 class="fw-bold mb-3">Please Address the Following:</h5>
                        
                        @if($previousSubmission->rejection_reason)
                        <div class="mb-3">
                            <strong class="text-danger">Rejection Reason:</strong>
                            <div class="bg-white p-3 rounded mt-2">
                                {{ $previousSubmission->rejection_reason }}
                            </div>
                        </div>
                        @endif

                        @if($previousSubmission->admin_notes)
                        <div>
                            <strong class="text-primary">Admin Notes:</strong>
                            <div class="bg-white p-3 rounded mt-2">
                                {{ $previousSubmission->admin_notes }}
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            @endif

            <!-- Resubmission Form -->
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4 p-md-5">
                    <form action="{{ route('kyc.store-resubmission') }}" method="POST" enctype="multipart/form-data" id="kycResubmitForm">
                        @csrf

                        <!-- Section 1: ID Information -->
                        <div class="mb-5">
                            <h5 class="fw-bold text-navy mb-4">
                                <i class="bi bi-card-heading me-2"></i>
                                ID Information
                            </h5>

                            <!-- ID Type -->
                            <div class="mb-4">
                                <label for="id_type" class="form-label fw-semibold">ID Type <span class="text-danger">*</span></label>
                                <select class="form-select @error('id_type') is-invalid @enderror" id="id_type" name="id_type" required>
                                    <option value="">Select ID Type</option>
                                    <option value="passport" {{ old('id_type', $previousSubmission->id_type) === 'passport' ? 'selected' : '' }}>International Passport</option>
                                    <option value="drivers_license" {{ old('id_type', $previousSubmission->id_type) === 'drivers_license' ? 'selected' : '' }}>Driver's License</option>
                                    <option value="national_id" {{ old('id_type', $previousSubmission->id_type) === 'national_id' ? 'selected' : '' }}>National ID Card (NIN)</option>
                                    <option value="voter_id" {{ old('id_type', $previousSubmission->id_type) === 'voter_id' ? 'selected' : '' }}>Voter's Card</option>
                                </select>
                                @error('id_type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- ID Number -->
                            <div class="mb-4">
                                <label for="id_number" class="form-label fw-semibold">ID Number <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('id_number') is-invalid @enderror" id="id_number" name="id_number" value="{{ old('id_number', $previousSubmission->id_number) }}" required>
                                @error('id_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- ID Expiry Date -->
                            <div class="mb-4">
                                <label for="id_expiry_date" class="form-label fw-semibold">ID Expiry Date (if applicable)</label>
                                <input type="date" class="form-control @error('id_expiry_date') is-invalid @enderror" id="id_expiry_date" name="id_expiry_date" value="{{ old('id_expiry_date', $previousSubmission->id_expiry_date?->format('Y-m-d')) }}">
                                @error('id_expiry_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="alert alert-info border-0 mb-4">
                                <i class="bi bi-info-circle me-2"></i>
                                <strong>Note:</strong> Only upload new documents if you need to replace the previous ones. Leave blank to keep existing documents.
                            </div>

                            <!-- ID Front Upload (Optional for resubmission) -->
                            <div class="mb-4">
                                <label for="id_front" class="form-label fw-semibold">Upload New ID (Front) <span class="text-muted">(Optional)</span></label>
                                <input type="file" class="form-control @error('id_front') is-invalid @enderror" id="id_front" name="id_front" accept="image/*,application/pdf" onchange="previewImage(this, 'id_front_preview')">
                                @error('id_front')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted d-block">JPG, PNG, or PDF - Max 5MB</small>
                                <div id="id_front_preview" class="mt-2"></div>
                            </div>

                            <!-- ID Back Upload (Optional) -->
                            <div class="mb-4">
                                <label for="id_back" class="form-label fw-semibold">Upload New ID (Back) <span class="text-muted">(Optional)</span></label>
                                <input type="file" class="form-control @error('id_back') is-invalid @enderror" id="id_back" name="id_back" accept="image/*,application/pdf" onchange="previewImage(this, 'id_back_preview')">
                                @error('id_back')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted d-block">JPG, PNG, or PDF - Max 5MB</small>
                                <div id="id_back_preview" class="mt-2"></div>
                            </div>

                            <!-- Proof of Address Upload (Optional) -->
                            <div class="mb-4">
                                <label for="proof_of_address" class="form-label fw-semibold">Upload New Proof of Address <span class="text-muted">(Optional)</span></label>
                                <input type="file" class="form-control @error('proof_of_address') is-invalid @enderror" id="proof_of_address" name="proof_of_address" accept="image/*,application/pdf" onchange="previewImage(this, 'proof_preview')">
                                @error('proof_of_address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted d-block">Utility bill, bank statement, or rental agreement - Max 5MB</small>
                                <div id="proof_preview" class="mt-2"></div>
                            </div>

                            <!-- Selfie Upload (Optional) -->
                            <div class="mb-4">
                                <label for="selfie" class="form-label fw-semibold">Upload New Selfie with ID <span class="text-muted">(Optional)</span></label>
                                <input type="file" class="form-control @error('selfie') is-invalid @enderror" id="selfie" name="selfie" accept="image/*" onchange="previewImage(this, 'selfie_preview')">
                                @error('selfie')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted d-block">Clear photo of you holding your ID - Max 5MB</small>
                                <div id="selfie_preview" class="mt-2"></div>
                            </div>
                        </div>

                        <hr class="my-5">

                        <!-- Section 2: Personal Information -->
                        <div class="mb-5">
                            <h5 class="fw-bold text-navy mb-4">
                                <i class="bi bi-person-circle me-2"></i>
                                Personal Information
                            </h5>

                            <div class="row">
                                <!-- Full Name -->
                                <div class="col-md-12 mb-4">
                                    <label for="full_name" class="form-label fw-semibold">Full Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('full_name') is-invalid @enderror" id="full_name" name="full_name" value="{{ old('full_name', $previousSubmission->full_name) }}" required>
                                    @error('full_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Date of Birth -->
                                <div class="col-md-6 mb-4">
                                    <label for="date_of_birth" class="form-label fw-semibold">Date of Birth <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control @error('date_of_birth') is-invalid @enderror" id="date_of_birth" name="date_of_birth" value="{{ old('date_of_birth', $previousSubmission->date_of_birth?->format('Y-m-d')) }}" max="{{ now()->subYears(18)->format('Y-m-d') }}" required>
                                    @error('date_of_birth')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Nationality -->
                                <div class="col-md-6 mb-4">
                                    <label for="nationality" class="form-label fw-semibold">Nationality <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('nationality') is-invalid @enderror" id="nationality" name="nationality" value="{{ old('nationality', $previousSubmission->nationality) }}" required>
                                    @error('nationality')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Address -->
                                <div class="col-md-12 mb-4">
                                    <label for="address" class="form-label fw-semibold">Street Address <span class="text-danger">*</span></label>
                                    <textarea class="form-control @error('address') is-invalid @enderror" id="address" name="address" rows="2" required>{{ old('address', $previousSubmission->address) }}</textarea>
                                    @error('address')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- City -->
                                <div class="col-md-4 mb-4">
                                    <label for="city" class="form-label fw-semibold">City <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('city') is-invalid @enderror" id="city" name="city" value="{{ old('city', $previousSubmission->city) }}" required>
                                    @error('city')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- State -->
                                <div class="col-md-4 mb-4">
                                    <label for="state" class="form-label fw-semibold">State <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('state') is-invalid @enderror" id="state" name="state" value="{{ old('state', $previousSubmission->state) }}" required>
                                    @error('state')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Postal Code -->
                                <div class="col-md-4 mb-4">
                                    <label for="postal_code" class="form-label fw-semibold">Postal Code <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('postal_code') is-invalid @enderror" id="postal_code" name="postal_code" value="{{ old('postal_code', $previousSubmission->postal_code) }}" required>
                                    @error('postal_code')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Country -->
                                <div class="col-md-12 mb-4">
                                    <label for="country" class="form-label fw-semibold">Country <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('country') is-invalid @enderror" id="country" name="country" value="{{ old('country', $previousSubmission->country) }}" required>
                                    @error('country')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Trust Message -->
                        <div class="alert alert-info border-0 mb-4">
                            <div class="d-flex align-items-start">
                                <i class="bi bi-shield-check fs-3 me-3 text-primary"></i>
                                <div>
                                    <h6 class="fw-bold mb-2">Your Data is Secure</h6>
                                    <ul class="mb-0 ps-3">
                                        <li>✓ All data is encrypted and stored securely</li>
                                        <li>✓ Reviewed only by authorized personnel</li>
                                        <li>✓ Never shared with third parties</li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <!-- Submit Buttons -->
                        <div class="d-grid gap-2 d-md-flex justify-content-md-between">
                            <a href="{{ route('kyc.status') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-left me-2"></i>Cancel
                            </a>
                            <button type="submit" class="btn btn-primary btn-lg px-5" id="submitBtn">
                                <i class="bi bi-arrow-repeat me-2"></i>Resubmit KYC
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .text-navy {
        color: #0F1A3C;
    }
    
    .btn-primary {
        background-color: #E4B400;
        border-color: #E4B400;
        color: #0F1A3C;
        font-weight: 600;
    }
    
    .btn-primary:hover {
        background-color: #c99d00;
        border-color: #c99d00;
        color: #0F1A3C;
    }
    
    .preview-image {
        max-width: 200px;
        max-height: 200px;
        border-radius: 8px;
        border: 2px solid #dee2e6;
        padding: 4px;
    }
</style>

<script>
    // Image preview function
    function previewImage(input, previewId) {
        const preview = document.getElementById(previewId);
        preview.innerHTML = '';
        
        if (input.files && input.files[0]) {
            const file = input.files[0];
            
            // Check file size (5MB)
            if (file.size > 5 * 1024 * 1024) {
                alert('File size must be less than 5MB');
                input.value = '';
                return;
            }
            
            // Show file info
            const fileInfo = document.createElement('div');
            fileInfo.className = 'alert alert-success py-2 px-3 mt-2';
            fileInfo.innerHTML = `
                <i class="bi bi-check-circle me-2"></i>
                <strong>${file.name}</strong> (${(file.size / 1024 / 1024).toFixed(2)} MB)
            `;
            preview.appendChild(fileInfo);
            
            // Show image preview if it's an image
            if (file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.className = 'preview-image mt-2';
                    preview.appendChild(img);
                };
                reader.readAsDataURL(file);
            }
        }
    }
    
    // Form submission
    document.getElementById('kycResubmitForm').addEventListener('submit', function(e) {
        const submitBtn = document.getElementById('submitBtn');
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Resubmitting...';
    });
</script>
@endsection
