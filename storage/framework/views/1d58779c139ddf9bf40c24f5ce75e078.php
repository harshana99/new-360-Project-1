

<?php $__env->startSection('title', 'Analytics'); ?>

<?php $__env->startPush('styles'); ?>
<style>
    /* Tabs Styling */
    .nav-tabs { border-bottom: 1px solid rgba(255,255,255,0.1); margin-bottom: 30px; }
    .nav-tabs .nav-link { color: var(--text-muted); background: transparent; border: none; padding-bottom: 15px; position: relative; }
    .nav-tabs .nav-link:hover { color: white; }
    .nav-tabs .nav-link.active { color: var(--accent-gold); background: transparent; font-weight: 600; }
    .nav-tabs .nav-link.active::after { content: ''; position: absolute; bottom: 0; left: 0; width: 100%; height: 2px; background: var(--accent-gold); }

    .chart-container {
        position: relative;
        height: 300px;
        width: 100%;
    }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<h2 class="fw-bold text-white mb-4">Advanced Analytics</h2>

<!-- Custom Navigation Tabs -->
<ul class="nav nav-tabs">
    <li class="nav-item"><a class="nav-link" href="<?php echo e(route('owner.dashboard')); ?>">Dashboard</a></li>
    <li class="nav-item"><a class="nav-link" href="<?php echo e(route('owner.properties')); ?>">My Properties</a></li>
    <li class="nav-item"><a class="nav-link" href="<?php echo e(route('owner.earnings')); ?>">Earnings</a></li>
    <li class="nav-item"><a class="nav-link" href="<?php echo e(route('owner.documents')); ?>">Documents</a></li>
    <li class="nav-item"><a class="nav-link active" href="#">Analytics</a></li>
</ul>

<div class="row g-4 mb-4">
    <!-- Chart 1: Revenue Over Time -->
    <div class="col-lg-8">
        <div class="stats-card p-4 h-100">
            <h5 class="fw-bold text-white mb-4">Revenue Growth</h5>
            <div class="chart-container">
                <canvas id="revenueChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Chart 2: Property Views -->
    <div class="col-lg-4">
        <div class="stats-card p-4 h-100">
            <h5 class="fw-bold text-white mb-4">Traffic Source</h5>
            <div class="chart-container">
                <canvas id="trafficChart"></canvas>
            </div>
        </div>
    </div>
</div>

<div class="stats-card p-4">
    <h5 class="fw-bold text-white mb-4">Performance Metrics</h5>
    <div class="row g-4">
        <div class="col-md-3">
            <div class="p-3 rounded" style="background: rgba(255,255,255,0.03);">
                <small class="text-white text-opacity-75 text-uppercase">Avg. View Duration</small>
                <h3 class="text-white mt-2">2m 45s</h3>
                <small class="text-success"><i class="bi bi-arrow-up"></i> 12% vs last month</small>
            </div>
        </div>
        <div class="col-md-3">
            <div class="p-3 rounded" style="background: rgba(255,255,255,0.03);">
                <small class="text-white text-opacity-75 text-uppercase">Conversion Rate</small>
                <h3 class="text-white mt-2">1.2%</h3>
                <small class="text-muted">Stable</small>
            </div>
        </div>
        <div class="col-md-3">
            <div class="p-3 rounded" style="background: rgba(255,255,255,0.03);">
                <small class="text-white text-opacity-75 text-uppercase">Total Impressions</small>
                <h3 class="text-white mt-2">15,400</h3>
                <small class="text-success"><i class="bi bi-arrow-up"></i> 5% vs last month</small>
            </div>
        </div>
        <div class="col-md-3">
            <div class="p-3 rounded" style="background: rgba(255,255,255,0.03);">
                <small class="text-white text-opacity-75 text-uppercase">Click-Through Rate</small>
                <h3 class="text-white mt-2">3.8%</h3>
                <small class="text-danger"><i class="bi bi-arrow-down"></i> 1% vs last month</small>
            </div>
        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Revenue Chart
    const ctxRevenue = document.getElementById('revenueChart').getContext('2d');
    new Chart(ctxRevenue, { // Fixed typo in variable name in previous thought if any
        type: 'line',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
            datasets: [{
                label: 'Revenue (₦)',
                data: [120000, 150000, 180000, 220000, 200000, 250000],
                borderColor: '#F59E0B',
                backgroundColor: 'rgba(245, 158, 11, 0.1)',
                fill: true,
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                y: { grid: { color: 'rgba(255,255,255,0.05)' }, ticks: { color: '#94a3b8' } },
                x: { grid: { display: false }, ticks: { color: '#94a3b8' } }
            }
        }
    });

    // Traffic Chart (Pie)
    const ctxTraffic = document.getElementById('trafficChart').getContext('2d');
    new Chart(ctxTraffic, {
        type: 'doughnut',
        data: {
            labels: ['Direct', 'Social', 'Organic', 'Referral'],
            datasets: [{
                data: [35, 25, 20, 20],
                backgroundColor: ['#F59E0B', '#10B981', '#3B82F6', '#6366F1'],
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { position: 'bottom', labels: { color: '#94a3b8' } } },
            cutout: '70%'
        }
    });
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('dashboards.layouts.dashboard-layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp1\htdocs\new 360 Project\resources\views/dashboards/owner/analytics.blade.php ENDPATH**/ ?>