@extends('dashboards.layouts.dashboard-layout')

@section('title', 'Browse Properties')

@section('content')
<div class="row mb-4">
    <div class="col-md-6">
        <h2 class="fw-bold text-navy">Marketplace</h2>
        <p class="text-muted">Discover premium investment opportunities</p>
    </div>
    <div class="col-md-6 text-end">
        <!-- Search Form -->
        <form action="{{ route('properties.index') }}" method="GET" class="d-flex justify-content-end">
            <input type="text" name="search" class="form-control me-2 w-50" placeholder="Search location, title..." value="{{ request('search') }}">
            <button class="btn btn-navy" type="submit"><i class="bi bi-search"></i></button>
        </form>
    </div>
</div>

<div class="row g-4">
    @forelse($properties as $property)
    <div class="col-lg-4 col-md-6">
        <div class="card h-100 shadow-sm border-0 property-card">
            <div class="position-relative">
                <img src="{{ $property->image_url }}" class="card-img-top" alt="{{ $property->title }}" style="height: 200px; object-fit: cover;">
                <span class="badge bg-gold text-dark position-absolute top-0 end-0 m-3">{{ ucfirst($property->property_type) }}</span>
            </div>
            <div class="card-body">
                <h5 class="fw-bold text-navy mb-1">{{ $property->title }}</h5>
                <p class="text-muted small mb-3"><i class="bi bi-geo-alt-fill text-gold"></i> {{ $property->location }}</p>
                
                <div class="d-flex justify-content-between mb-3">
                    <div>
                        <small class="text-muted d-block">Price</small>
                        <span class="fw-bold">₦{{ number_format($property->price) }}</span>
                    </div>
                    <div class="text-end">
                        <small class="text-muted d-block">Exp. Return</small>
                        <span class="fw-bold text-success">{{ $property->expected_return_percentage }}%</span>
                    </div>
                </div>
                
                <div class="d-grid">
                    <a href="{{ route('properties.show', $property->id) }}" class="btn btn-outline-navy">View Details</a>
                </div>
            </div>
        </div>
    </div>
    @empty
    <div class="col-12 text-center py-5">
        <h3 class="text-muted">No properties found.</h3>
    </div>
    @endforelse
</div>

<div class="mt-4">
    {{ $properties->links() }}
</div>
@endsection
