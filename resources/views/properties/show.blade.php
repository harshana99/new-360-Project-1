@extends('dashboards.layouts.dashboard-layout')

@section('title', $property->title)

@push('styles')
<style>
    .prop-detail-card {
        background: var(--bg-card);
        border: 1px solid rgba(255,255,255,0.05);
        border-radius: 16px;
        overflow: hidden;
    }
    
    .prop-image-container {
        position: relative;
        height: 500px;
        background: #0f172a;
    }
    
    .prop-image-container img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .carousel-control-prev, .carousel-control-next {
        width: 50px;
        height: 50px;
        background: rgba(0,0,0,0.5);
        border-radius: 50%;
        top: 50%;
        transform: translateY(-50%);
        margin: 0 20px;
        opacity: 0.8;
    }
    .carousel-control-prev:hover, .carousel-control-next:hover {
        opacity: 1;
        background: var(--accent-gold);
    }
    
    .feature-box {
        background: rgba(255,255,255,0.03);
        border-radius: 12px;
        padding: 20px;
        text-align: center;
        border: 1px solid rgba(255,255,255,0.02);
    }
    .feature-box small { color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px; font-size: 11px; margin-bottom: 5px; display: block; }
    .feature-box span { font-size: 18px; font-weight: 600; color: white; }
    
    .sidebar-card {
        background: white; /* Contrast card as requested in some designs, or keep dark. Let's make it Dark based on "previous dark style" request */
        background: var(--bg-card);
        border-radius: 16px;
        padding: 30px;
        border: 1px solid rgba(255,255,255,0.05);
    }
    
    .btn-invest {
        background: white;
        color: #0F1A3C;
        font-weight: 700;
        padding: 15px;
        border-radius: 12px;
        margin-bottom: 10px;
        transition: 0.3s;
    }
    .btn-invest:hover {
        background: #f1f5f9;
        transform: translateY(-2px);
    }
    
    .btn-contact {
        background: transparent;
        border: 1px solid rgba(255,255,255,0.1);
        color: white;
        padding: 15px;
        border-radius: 12px;
        font-weight: 600;
    }
    .btn-contact:hover {
        border-color: white;
        background: rgba(255,255,255,0.05);
    }

    .doc-item {
        background: rgba(255,255,255,0.03);
        padding: 15px;
        border-radius: 10px;
        margin-bottom: 10px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        border: 1px solid rgba(255,255,255,0.02);
    }
</style>
@endpush

@section('content')
<div class="row g-4">
    <!-- Main Content -->
    <div class="col-lg-8">
        <!-- Image & Details -->
        <div class="prop-detail-card mb-4">
            <!-- Image Carousel -->
            <div id="propertyCarousel" class="carousel slide prop-image-container" data-bs-ride="carousel">
                <div class="carousel-inner h-100">
                    @forelse($property->images as $key => $image)
                    <div class="carousel-item h-100 {{ $key == 0 ? 'active' : '' }}">
                        <img src="{{ $image->image_url }}" alt="Property Image" onerror="this.src='https://via.placeholder.com/800x500?text=No+Image'">
                    </div>
                    @empty
                    <div class="carousel-item active h-100">
                        <img src="https://via.placeholder.com/800x500?text=No+Image+Available" alt="Default Image">
                    </div>
                    @endforelse
                </div>
                @if($property->images->count() > 1)
                <button class="carousel-control-prev" type="button" data-bs-target="#propertyCarousel" data-bs-slide="prev">
                    <span class="bi bi-chevron-left fs-4" aria-hidden="true"></span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#propertyCarousel" data-bs-slide="next">
                    <span class="bi bi-chevron-right fs-4" aria-hidden="true"></span>
                </button>
                @endif
                
                <!-- Overlay Gradient -->
                <div style="position: absolute; bottom: 0; left: 0; width: 100%; height: 150px; background: linear-gradient(to top, #0B1221, transparent);"></div>
            </div>
            
            <div class="p-4 p-md-5">
                <div class="d-flex justify-content-between align-items-start mb-4">
                    <div>
                        <h1 class="fw-bold text-white mb-2">{{ $property->title }}</h1>
                        <p class="text-muted mb-0 d-flex align-items-center">
                            <i class="bi bi-geo-alt me-2" style="color: var(--accent-gold);"></i> 
                            {{ $property->address ?? $property->location }}
                        </p>
                    </div>
                    <div>
                         <span class="badge" style="background: rgba(255,255,255,0.1); color: white; padding: 10px 20px; border-radius: 8px;">
                            {{ ucfirst($property->property_type) }}
                         </span>
                    </div>
                </div>

                <!-- Key Metrics Grid -->
                <div class="row g-3 mb-5">
                    <div class="col-md-3 col-6">
                        <div class="feature-box">
                            <small>Price</small>
                            <span>₦{{ number_format($property->price) }}</span>
                        </div>
                    </div>
                    <div class="col-md-3 col-6">
                        <div class="feature-box">
                            <small>Min. Invest</small>
                            <span>₦{{ number_format($property->minimum_investment ?? 0) }}</span>
                        </div>
                    </div>
                    <div class="col-md-3 col-6">
                        <div class="feature-box">
                            <small>Returns</small>
                            <span class="text-success">{{ $property->expected_return_percentage }}%</span>
                        </div>
                    </div>
                    <div class="col-md-3 col-6">
                        <div class="feature-box">
                            <small>Duration</small>
                            <span>{{ $property->lease_duration_months ?? 'N/A' }} Months</span>
                        </div>
                    </div>
                </div>

                <h4 class="text-white fw-bold mb-3">Description</h4>
                <p class="text-muted mb-5" style="line-height: 1.8;">{{ $property->description }}</p>

                @if($property->documents && $property->documents->count() > 0)
                <h4 class="text-white fw-bold mb-3">Documents</h4>
                <div class="d-flex flex-column gap-2">
                    @foreach($property->documents as $doc)
                    <div class="doc-item">
                        <div class="d-flex align-items-center gap-3">
                            <i class="bi bi-file-earmark-pdf fs-4 text-danger"></i>
                            <span class="text-white">{{ $doc->file_name }}</span>
                        </div>
                        <a href="{{ $doc->file_url }}" target="_blank" class="btn btn-sm" style="color: var(--accent-gold); border: 1px solid var(--accent-gold);">Download</a>
                    </div>
                    @endforeach
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Right Sidebar -->
    <div class="col-lg-4">
        <!-- White/Light Card for Contrast/Call to Action -->
        <div class="sidebar-card mb-4" style="background: white; color: #0f172a;">
            <div class="d-flex justify-content-between align-items-center mb-4">
                 <h4 class="fw-bold mb-0">Start Investment</h4>
                 <i class="bi bi-graph-up-arrow fs-4 text-success"></i>
            </div>
            
            <div class="mb-4">
                <label class="small fw-bold text-muted mb-2">Available Units</label>
                <div class="progress" style="height: 10px;">
                  <div class="progress-bar bg-success" role="progressbar" style="width: 25%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
                <div class="d-flex justify-content-between mt-1">
                    <small>25% Funded</small>
                    <small>150 Units Left</small>
                </div>
            </div>

            <div class="d-grid"> <!-- Buttons -->
                <button class="btn btn-dark fw-bold py-3 mb-2" style="background: #0B1221; border:none;">Buy / Invest Now</button>
                <button class="btn btn-outline-secondary py-3">Contact Owner</button>
            </div>
        </div>

        <!-- Owner Profile -->
        <div class="sidebar-card">
            <h5 class="text-white fw-bold mb-4">Property Owner</h5>
            <div class="d-flex align-items-center gap-3">
                <div style="width: 50px; height: 50px; border-radius: 50%; background: #334155; display: flex; align-items: center; justify-content: center; color: white;">
                    {{ substr($property->owner->name ?? 'U', 0, 1) }}
                </div>
                <div>
                    <h6 class="text-white mb-0">{{ $property->owner->name ?? 'Unknown Owner' }}</h6>
                    <small class="text-muted"><i class="bi bi-check-circle-fill text-success me-1"></i> Verified Owner</small>
                </div>
            </div>
            <hr style="border-color: rgba(255,255,255,0.1); margin: 20px 0;">
            <div class="d-grid">
                <button class="btn btn-sm btn-outline-light">View Profile</button>
            </div>
        </div>
    </div>
</div>
@endsection
