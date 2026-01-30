@extends('dashboards.layouts.dashboard-layout')

@section('title', 'Edit Property')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-10">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold text-navy mb-0">Edit Property: {{ $property->title }}</h2>
            <a href="{{ route('owner.properties') }}" class="btn btn-outline-secondary">Back to List</a>
        </div>

        @if($property->status == 'rejected')
        <div class="alert alert-danger">
            <strong><i class="bi bi-exclamation-triangle-fill me-2"></i>Property Rejected:</strong>
            {{ $property->rejection_reason }}
            <div class="mt-2">Please fix the issues below and update to resubmit.</div>
        </div>
        @endif

        <div class="card shadow-sm border-0">
            <div class="card-body p-4">
                <form action="{{ route('owner.properties.update', $property->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('POST') 
                    {{-- Using POST for update because file uploads via PUT can be tricky in some server configs, 
                         but normally PUT is fine. Controller expects update logic. I'll use POST route in web.php or method spoofing. --}}
                    
                    <h5 class="fw-bold text-navy mb-3"><i class="bi bi-info-circle me-2"></i>Basic Information</h5>
                    <div class="row g-3 mb-4">
                        <div class="col-md-12">
                            <label class="form-label">Property Title</label>
                            <input type="text" name="title" class="form-control" value="{{ old('title', $property->title) }}" required>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label">Description</label>
                            <textarea name="description" class="form-control" rows="4" required>{{ old('description', $property->description) }}</textarea>
                        </div>
                         <div class="col-md-6">
                            <label class="form-label">Location (City/Area)</label>
                            <input type="text" name="location" class="form-control" value="{{ old('location', $property->location) }}" readonly disabled class="bg-light">
                             <small class="text-muted">Location can only be changed by admin request.</small>
                        </div>
                         <div class="col-md-6">
                            <label class="form-label">Price (₦)</label>
                            <input type="number" step="0.01" name="price" class="form-control" value="{{ old('price', $property->price) }}" required>
                        </div>
                    </div>

                    <div class="text-end">
                        <button type="submit" class="btn btn-gold btn-lg px-5 fw-bold">Update Property</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
