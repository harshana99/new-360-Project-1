

<?php $__env->startSection('title', 'Dashboard'); ?>

<?php $__env->startSection('content'); ?>
<div class="row g-4">
    <!-- LEFT COLUMN -->
    <div class="col-lg-8">
        
        <!-- ROW 1: Top Stats -->
        <div class="row g-4 mb-4">
            <div class="col-md-6">
                <!-- My Properties Card -->
                <div class="stats-card d-flex flex-column justify-content-between" style="background: white;">
                    <div>
                        <h3 style="color: #1E293B;">My Properties</h3>
                        <div class="value" style="color: #1E293B;"><?php echo e($metrics->total_properties_count); ?></div>
                        <div class="subtext" style="color: #000000;">Total Units Owned</div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <!-- Earnings Card -->
                <div class="stats-card d-flex flex-column justify-content-between" style="background: white;">
                    <div>
                        <h3 style="color: #1E293B;">Earnings Summary</h3>
                        <div class="value" style="color: #1E293B;">₦<?php echo e(number_format($metrics->total_earnings, 0)); ?></div>
                        <div class="subtext" style="color: #000000;">Total Earnings</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ROW 2: Secondary Stats -->
        <div class="row g-4 mb-4">
            <div class="col-md-6">
                <!-- Projects Funded -->
                <div class="stats-card d-flex flex-column justify-content-between" style="background: white;">
                    <div>
                        <h3 style="color: #1E293B;">Projects Funded</h3>
                        <div class="value" style="color: #1E293B;"><?php echo e($metrics->active_investments_count); ?></div>
                        <div class="subtext" style="color: #000000;">Crowdfunding Investments</div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <!-- Wallet Balance -->
                <div class="stats-card d-flex flex-column justify-content-between" style="background: white;">
                    <div>
                        <h3 style="color: #1E293B;">Wallet Balance</h3>
                        <div class="value" style="color: #1E293B;">₦850,000</div>
                        <div class="subtext" style="color: #000000;">Withdraw</div>
                    </div>
                    <button class="btn btn-sm btn-warning mt-2 w-auto align-self-start" style="background: #F59E0B; border:none; color:black; font-weight:600;">Withdraw</button>
                </div>
            </div>
        </div>

        <!-- ROW 4: Properties List -->
        <div class="mb-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="text-white mb-0">My Properties</h5>
                <a href="<?php echo e(route('owner.properties')); ?>" class="text-muted text-decoration-none" style="font-size: 14px;">See All <i class="bi bi-chevron-right"></i></a>
            </div>

            <?php
                // Fetch actual recent properties for the user
                $myProps = \App\Models\Property::where('user_id', auth()->id())->latest()->take(3)->get();
            ?>

            <?php $__empty_1 = true; $__currentLoopData = $myProps; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $prop): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <div class="property-list-item">
                <div class="prop-info">
                    <img src="<?php echo e($prop->image_url ?? 'https://via.placeholder.com/60'); ?>" class="prop-img" alt="Property">
                    <div class="prop-text">
                        <h4><?php echo e($prop->title); ?></h4>
                        <p><?php echo e($prop->location); ?></p>
                    </div>
                </div>
                <div class="prop-price">
                    <h4>₦<?php echo e(number_format($prop->price, 0)); ?></h4>
                    <span><?php echo e(ucfirst($prop->status)); ?></span>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="text-center py-4 text-muted">
                No properties found. <a href="<?php echo e(route('owner.properties')); ?>" class="text-white">Add one?</a>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- RIGHT COLUMN -->
    <div class="col-lg-4">
        <!-- Chart Card -->
        <div class="stats-card mb-4" style="min-height: 300px;">
            <h3>Property Value Growth</h3>
            <div style="height: 200px;">
                <canvas id="growthChart"></canvas>
            </div>
        </div>

        <!-- Notifications -->
        <div>
            <h5 class="text-white mb-3">Notifications</h5>
            
            <div class="d-flex gap-3 mb-3">
                <div style="min-width: 40px; height: 40px; border-radius: 50%; background: rgba(16, 185, 129, 0.1); color: #10B981; display:flex; align-items:center; justify-content:center;">
                    <i class="bi bi-check-lg"></i>
                </div>
                <div>
                    <h6 class="text-white mb-1" style="font-size: 14px;">Phase 1 Crowdfunding</h6>
                    <p class="text-muted mb-0" style="font-size: 12px;">Reached 85% Funding</p>
                    <small class="text-muted" style="font-size: 11px;">2 hours ago</small>
                </div>
            </div>

            <div class="d-flex gap-3 mb-3">
                <div style="min-width: 40px; height: 40px; border-radius: 50%; background: rgba(59, 130, 246, 0.1); color: #3B82F6; display:flex; align-items:center; justify-content:center;">
                    <i class="bi bi-wallet2"></i>
                </div>
                <div>
                    <h6 class="text-white mb-1" style="font-size: 14px;">Rental Payment Received</h6>
                    <p class="text-muted mb-0" style="font-size: 12px;">Unit 412 of Greenwood Residences</p>
                    <small class="text-muted" style="font-size: 11px;">1 day ago</small>
                </div>
            </div>

            <div class="d-flex gap-3 mb-3">
                <div style="min-width: 40px; height: 40px; border-radius: 50%; background: rgba(245, 158, 11, 0.1); color: #F59E0B; display:flex; align-items:center; justify-content:center;">
                    <i class="bi bi-tools"></i>
                </div>
                <div>
                    <h6 class="text-white mb-1" style="font-size: 14px;">Maintenance Request</h6>
                    <p class="text-muted mb-0" style="font-size: 12px;">Unit 805: In Progress</p>
                    <small class="text-muted" style="font-size: 11px;">2 days ago</small>
                </div>
            </div>

        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
    const ctx = document.getElementById('growthChart').getContext('2d');
    
    // Create gradient
    const gradient = ctx.createLinearGradient(0, 0, 0, 300);
    gradient.addColorStop(0, 'rgba(245, 158, 11, 0.2)');
    gradient.addColorStop(1, 'rgba(245, 158, 11, 0)');

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Sep'],
            datasets: [{
                label: 'Growth',
                data: [12, 19, 15, 25, 22, 30],
                borderColor: '#F59E0B',
                backgroundColor: gradient,
                borderWidth: 2,
                pointRadius: 0,
                fill: true,
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false }
            },
            scales: {
                x: { 
                    display: true, 
                    grid: { display: false },
                    ticks: { color: '#64748B' }
                },
                y: { 
                    display: false 
                }
            }
        }
    });
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('dashboards.layouts.dashboard-layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp1\htdocs\new 360 Project\resources\views/dashboards/owner/dashboard.blade.php ENDPATH**/ ?>