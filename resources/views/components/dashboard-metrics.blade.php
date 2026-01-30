@props(['title', 'value', 'icon', 'color' => 'success', 'trend' => null])

<div class="card h-100 stat-card">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-start mb-2">
            <div>
                <h6 class="text-muted text-uppercase small fw-bold mb-1">{{ $title }}</h6>
                <h3 class="mb-0 text-navy fw-bold">{{ $value }}</h3>
            </div>
            <div class="rounded-circle bg-light p-2 text-{{ $color }}">
                <i class="bi {{ $icon }} fs-4"></i>
            </div>
        </div>
        @if($trend)
            <div class="small">
                <span class="text-{{ $trend > 0 ? 'success' : 'danger' }} fw-bold">
                    <i class="bi bi-arrow-{{ $trend > 0 ? 'up' : 'down' }}"></i> {{ abs($trend) }}%
                </span>
                <span class="text-muted ms-1">vs last month</span>
            </div>
        @endif
    </div>
</div>
