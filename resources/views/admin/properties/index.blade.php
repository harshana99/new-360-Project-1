@extends('layouts.admin')

@section('title', 'Manage Properties')

@section('content')
<!-- Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="text-white fw-bold mb-1">Manage Properties</h2>
       <p class="text-muted">Review, approve, and manage system property listings</p>
    </div>
</div>

<!-- Filter Section -->
<div class="stats-card mb-4">
    <form action="{{ route('admin.properties.index') }}" method="GET" class="row g-3">
        <div class="col-md-3">
            <label class="form-label text-muted small">Status</label>
            <select name="status" class="form-select bg-dark text-white border-secondary">
                <option value="">All Statuses</option>
                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
            </select>
        </div>
        <div class="col-md-5">
            <label class="form-label text-muted small">Search</label>
            <div class="search-bar w-100">
                 <i class="bi bi-search"></i>
                 <input type="text" name="search" class="form-control" placeholder="Search title or owner..." value="{{ request('search') }}">
            </div>
        </div>
        <div class="col-md-2 d-flex align-items-end">
            <button type="submit" class="btn btn-warning w-100 fw-bold">Filter</button>
        </div>
    </form>
</div>

<!-- Properties Table -->
<div class="stats-card p-0 overflow-hidden">
    <div class="table-responsive">
        <table class="table table-dark-custom mb-0">
            <thead>
                <tr>
                    <th class="ps-4">Property</th>
                    <th>Owner</th>
                    <th>Type</th>
                    <th>Price</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th class="text-end pe-4">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($properties as $property)
                <tr>
                    <td class="ps-4 fw-bold text-white">{{ $property->title }}</td>
                    <td class="text-white-50">{{ $property->owner->name ?? 'Unknown' }}</td>
                    <td><span class="badge bg-secondary bg-opacity-25 text-white border border-secondary border-opacity-25">{{ ucfirst($property->property_type) }}</span></td>
                    <td class="text-white fw-bold">₦{{ number_format($property->price) }}</td>
                    <td>
                         @if($property->status == 'active')
                            <span class="badge bg-success bg-opacity-20 text-success border border-success border-opacity-25">Active</span>
                        @elseif($property->status == 'pending')
                            <span class="badge bg-warning bg-opacity-20 text-warning border border-warning border-opacity-25">Pending</span>
                        @elseif($property->status == 'rejected')
                            <span class="badge bg-danger bg-opacity-20 text-danger border border-danger border-opacity-25">Rejected</span>
                        @else
                            <span class="badge bg-secondary">{{ $property->status }}</span>
                        @endif
                    </td>
                    <td class="text-muted">{{ $property->created_at->format('M d, Y') }}</td>
                    <td class="text-end pe-4">
                        <a href="{{ route('admin.properties.show', $property->id) }}" class="btn btn-sm btn-outline-light">View & Manage</a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center py-5 text-muted opacity-50">No properties found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    @if($properties->hasPages())
    <div class="p-3 border-top border-light border-opacity-10">
        {{ $properties->links() }}
    </div>
    @endif
</div>
@endsection
