@extends('layouts.admin')

@section('title', 'Property Details')

@section('content')
<div class="mb-4">
    <a href="{{ route('admin.properties.index') }}" class="text-decoration-none text-muted transition-hover">
        <i class="bi bi-arrow-left me-1"></i> Back to Properties
    </a>
</div>

<div class="row g-4">
    <!-- Property Details (Left) -->
    <div class="col-lg-8">
        <div class="stats-card p-0 overflow-hidden">
            <div class="position-relative" style="height: 350px;">
                <img src="{{ $property->image_url }}" class="w-100 h-100 object-fit-cover" alt="{{ $property->title }}">
                <div class="position-absolute bottom-0 start-0 w-100 p-4" style="background: linear-gradient(to top, rgba(0,0,0,0.9), transparent);">
                    <div class="d-flex justify-content-between align-items-end">
                        <div>
                            <h2 class="fw-bold text-white mb-1">{{ $property->title }}</h2>
                            <div class="text-white text-opacity-75">
                                <i class="bi bi-geo-alt-fill text-warning me-1"></i> {{ $property->getFullAddressAttribute() }}
                            </div>
                        </div>
                        
                        @if($property->status == 'active')
                            <span class="badge bg-success bg-opacity-75 fs-6 px-3 py-2 border border-success border-opacity-50">Active</span>
                        @elseif($property->status == 'pending')
                            <span class="badge bg-warning bg-opacity-75 text-dark fs-6 px-3 py-2 border border-warning border-opacity-50">Pending Review</span>
                        @elseif($property->status == 'rejected')
                            <span class="badge bg-danger bg-opacity-75 fs-6 px-3 py-2 border border-danger border-opacity-50">Rejected</span>
                        @else
                            <span class="badge bg-secondary bg-opacity-75 fs-6 px-3 py-2">{{ ucfirst($property->status) }}</span>
                        @endif
                    </div>
                </div>
            </div>

            <div class="p-4">
                <div class="row g-4 mb-4">
                    <div class="col-md-4">
                        <div class="p-3 rounded" style="background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1);">
                            <small class="text-muted d-block text-uppercase" style="font-size: 11px; letter-spacing: 0.5px;">Listing Price</small>
                            <span class="fw-bold fs-4 text-white">₦{{ number_format($property->price) }}</span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="p-3 rounded" style="background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1);">
                            <small class="text-muted d-block text-uppercase" style="font-size: 11px; letter-spacing: 0.5px;">Min Investment</small>
                            <span class="fw-bold fs-4 text-warning">₦{{ number_format($property->minimum_investment) }}</span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="p-3 rounded" style="background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1);">
                            <small class="text-muted d-block text-uppercase" style="font-size: 11px; letter-spacing: 0.5px;">Owner</small>
                            <div class="d-flex align-items-center mt-1">
                                <div class="rounded-circle bg-secondary bg-opacity-50 me-2 d-flex align-items-center justify-content-center sm-avatar" style="width: 24px; height: 24px; font-size: 12px; color: white;">
                                    {{ substr($property->owner->name, 0, 1) }}
                                </div>
                                <span class="fw-bold text-white fs-5">{{ $property->owner->name ?? 'Unknown' }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <h5 class="fw-bold text-white mb-3">Description</h5>
                <p class="text-white text-opacity-75 lh-lg mb-5" style="white-space: pre-line;">
                    {{ $property->description }}
                </p>

                <h5 class="fw-bold text-white mb-3">Documents</h5>
                <div class="list-group">
                    @forelse($property->documents as $doc)
                        <a href="{{ $doc->file_url }}" target="_blank" class="list-group-item list-group-item-action p-3 d-flex align-items-center justify-content-between text-white" style="background: rgba(255,255,255,0.03); border-color: rgba(255,255,255,0.1);">
                            <div class="d-flex align-items-center">
                                <i class="bi bi-file-text-fill text-primary me-3 fs-4"></i>
                                <span>{{ $doc->file_name }}</span>
                            </div>
                            <i class="bi bi-download text-muted"></i>
                        </a>
                    @empty
                        <div class="p-3 rounded text-center text-muted" style="background: rgba(255,255,255,0.02); border: 1px dashed rgba(255,255,255,0.1);">
                            <i class="bi bi-folder-x display-6 mb-2 d-block opacity-25"></i>
                            No documents uploaded.
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Admin Actions (Right) -->
    <div class="col-lg-4">
        <div class="stats-card">
            <div class="border-bottom border-light border-opacity-10 pb-3 mb-4">
                <h5 class="mb-0 fw-bold text-white">Admin Actions</h5>
                <small class="text-muted">Review this status update</small>
            </div>
            
            <div class="mt-3">
                @if($property->status == 'pending' || $property->status == 'under_review')
                    <div class="d-grid gap-3">
                        <form action="{{ route('admin.properties.approve', $property->id) }}" method="POST">
                            @csrf
                            <button class="btn btn-success w-100 fw-bold py-3">
                                <i class="bi bi-check-circle-fill me-2"></i>Approve Property
                            </button>
                        </form>
                        
                        <button class="btn btn-outline-danger fw-bold py-3" data-bs-toggle="modal" data-bs-target="#rejectModal">
                            <i class="bi bi-x-circle-fill me-2"></i>Reject Property
                        </button>
                    </div>
                @elseif($property->status == 'active')
                    <div class="alert alert-success d-flex align-items-center border-0 mb-0" style="background: rgba(16, 185, 129, 0.1); color: #10B981;">
                        <i class="bi bi-check-circle-fill fs-3 me-3"></i>
                        <div>
                            <strong class="d-block">Approved & Active</strong>
                            <small class="opacity-75">This property is live on the marketplace.</small>
                        </div>
                    </div>
                @elseif($property->status == 'rejected')
                    <div class="alert alert-danger border-0 mb-0" style="background: rgba(239, 68, 68, 0.1); color: #EF4444;">
                        <div class="d-flex align-items-center mb-2">
                            <i class="bi bi-x-circle-fill fs-3 me-3"></i>
                            <strong>Rejected</strong>
                        </div>
                        <div class="p-3 rounded bg-dark bg-opacity-25 mt-2 text-white text-opacity-75 small">
                            "{{ $property->rejection_reason }}"
                        </div>
                    </div>
                @endif
            </div>

            <!-- Meta Info -->
            <div class="mt-4 pt-4 border-top border-light border-opacity-10">
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted small">Submitted By</span>
                    <span class="text-white small">{{ $property->owner->name }}</span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted small">Date</span>
                    <span class="text-white small">{{ $property->created_at->format('M d, Y h:i A') }}</span>
                </div>
                 <div class="d-flex justify-content-between">
                    <span class="text-muted small">Reference ID</span>
                    <span class="text-white small font-monospace">#PROP-{{ $property->id }}</span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Reject Modal (Dark Theme) -->
<div class="modal fade" id="rejectModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <form action="{{ route('admin.properties.reject', $property->id) }}" method="POST" class="modal-content" style="background: #1E293B; border: 1px solid rgba(255,255,255,0.1); color: white;">
            @csrf
            <div class="modal-header border-bottom border-light border-opacity-10">
                <h5 class="modal-title fw-bold text-white">Reject Property</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <label class="form-label text-muted">Reason for Rejection</label>
                <textarea name="reason" class="form-control bg-dark text-white border-secondary" rows="4" required placeholder="Please provide a clear reason for rejection (e.g. Incomplete documentation, Low image quality)..."></textarea>
            </div>
            <div class="modal-footer border-top border-light border-opacity-10">
                <button type="button" class="btn btn-outline-light" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-danger px-4">Confirm Rejection</button>
            </div>
        </form>
    </div>
</div>
@endsection
