@extends('dashboards.layouts.dashboard-layout')

@section('title', 'Add New Property')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-10">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold text-navy mb-0">Add New Property</h2>
            <a href="{{ route('owner.properties') }}" class="btn btn-outline-secondary">Back to List</a>
        </div>

        <div class="card shadow-sm border-0">
            <div class="card-body p-4">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('owner.properties.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <h5 class="fw-bold text-navy mb-3"><i class="bi bi-info-circle me-2"></i>Basic Information</h5>
                    <div class="row g-3 mb-4">
                        <div class="col-md-12">
                            <label class="form-label">Property Title <span class="text-danger">*</span></label>
                            <input type="text" name="title" class="form-control" value="{{ old('title') }}" required placeholder="e.g. Luxury Apartment in Lekki">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Location (City/Area) <span class="text-danger">*</span></label>
                            <input type="text" name="location" class="form-control" value="{{ old('location') }}" required placeholder="e.g. Lekki Phase 1">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Property Type <span class="text-danger">*</span></label>
                            <select name="property_type" class="form-select" required>
                                <option value="residential">Residential</option>
                                <option value="commercial">Commercial</option>
                                <option value="mixed_use">Mixed Use</option>
                                <option value="land">Land</option>
                            </select>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label">Full Address <span class="text-danger">*</span></label>
                            <input type="text" name="address" class="form-control" value="{{ old('address') }}" required placeholder="Street address">
                        </div>
                        <div class="col-md-12">
                            <label class="form-label">Description <span class="text-danger">*</span></label>
                            <textarea name="description" class="form-control" rows="4" required>{{ old('description') }}</textarea>
                        </div>
                    </div>

                    <h5 class="fw-bold text-navy mb-3"><i class="bi bi-cash-coin me-2"></i>Financial Details</h5>
                    <div class="row g-3 mb-4">
                        <div class="col-md-4">
                            <label class="form-label">Total Price (₦) <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" name="price" class="form-control" value="{{ old('price') }}" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Min Investment (₦) <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" name="minimum_investment" class="form-control" value="{{ old('minimum_investment') }}" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Expected Return (%) <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" name="expected_return_percentage" class="form-control" value="{{ old('expected_return_percentage') }}" required>
                        </div>
                    </div>

                    <h5 class="fw-bold text-navy mb-3"><i class="bi bi-images me-2"></i>Media & Documents</h5>
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label">Property Images <span class="text-danger">*</span></label>
                            <input type="file" name="images[]" class="form-control" multiple accept="image/*">
                            <small class="text-muted">Upload multiple images. First one will be featured.</small>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Documents (Optional)</label>
                            <input type="file" name="documents[]" class="form-control" multiple accept=".pdf,.doc,.docx">
                        </div>
                    </div>

                    <div class="text-end">
                        <button type="submit" class="btn btn-gold btn-lg px-5 fw-bold">Submit Property</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
