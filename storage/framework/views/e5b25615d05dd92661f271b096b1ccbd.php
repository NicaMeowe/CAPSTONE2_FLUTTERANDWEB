<?php
    $stats = $payload['stats'];
    $visits = $payload['collections']['visits'];
    $alerts = $payload['collections']['alerts'];
    $representatives = $payload['collections']['representatives'];
    $facilities = $payload['collections']['facilities'];
?>
<section class="page-heading">
    <div>
        <p class="eyebrow">WEDNESDAY · 23 SEPTEMBER 2026</p>
        <h1>Good morning, Nica <span class="heading-punctuation">.</span></h1>
        <p class="page-subtitle">Here’s what is happening across the Preventia field team today.</p>
    </div>
    <div class="heading-actions">
        <button class="secondary-button" data-action="refresh">↻ <span>Refresh data</span></button>
        <a class="primary-button" href="<?php echo e(route('visits')); ?>">View activity <span>→</span></a>
    </div>
</section>

<section class="metrics-grid" aria-label="Key metrics">
    <article class="metric-card metric-indigo">
        <div class="metric-top"><span class="metric-label">Visits today</span><span class="metric-icon">VT</span></div>
        <strong class="metric-value"><?php echo e($stats['totalVisits']); ?></strong>
        <div class="metric-meta"><span class="trend-up">↑ 12.4%</span><span>vs. last Wednesday</span></div>
        <div class="metric-spark"><i style="height: 28%"></i><i style="height: 38%"></i><i style="height: 34%"></i><i style="height: 57%"></i><i style="height: 51%"></i><i style="height: 67%"></i><i style="height: 82%"></i><i style="height: 76%"></i></div>
    </article>
    <article class="metric-card metric-teal">
        <div class="metric-top"><span class="metric-label">Attendance rate</span><span class="metric-icon">AT</span></div>
        <strong class="metric-value"><?php echo e($stats['attendanceRate']); ?><small>%</small></strong>
        <div class="metric-meta"><span class="trend-up">↑ 4.8%</span><span>within geofence</span></div>
        <div class="progress-line"><span style="width: <?php echo e($stats['attendanceRate']); ?>%"></span></div>
    </article>
    <article class="metric-card metric-amber">
        <div class="metric-top"><span class="metric-label">Active representatives</span><span class="metric-icon">RP</span></div>
        <strong class="metric-value"><?php echo e($stats['activeRepresentatives']); ?><small>/<?php echo e(count($representatives)); ?></small></strong>
        <div class="metric-meta"><span class="trend-neutral">● Live</span><span>tracked in territory</span></div>
        <div class="rep-dots"><?php $__currentLoopData = $representatives; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $rep): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><span class="<?php echo e(strtolower($rep['status'] ?? '') === 'active' ? 'online' : 'away'); ?>"></span><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></div>
    </article>
    <article class="metric-card metric-coral">
        <div class="metric-top"><span class="metric-label">Open alerts</span><span class="metric-icon">AL</span></div>
        <strong class="metric-value"><?php echo e($stats['activeAlerts']); ?></strong>
        <div class="metric-meta"><span class="trend-alert">Needs attention</span><span>low-stock & review</span></div>
        <div class="alert-severity"><span style="width: 58%"></span><span style="width: 27%"></span><span style="width: 15%"></span></div>
    </article>
</section>

<section class="dashboard-grid">
    <article class="panel activity-panel">
        <div class="panel-header">
            <div><p class="eyebrow">LIVE FEED</p><h2>Recent field activity</h2></div>
            <a href="<?php echo e(route('visits')); ?>" class="text-link">See all <span>→</span></a>
        </div>
        <div class="activity-list">
            <?php $__empty_1 = true; $__currentLoopData = array_slice($visits, 0, 4); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $visit): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <div class="activity-row">
                    <div class="activity-avatar avatar-<?php echo e($loop->index + 1); ?>"><?php echo e(strtoupper(substr($visit['representative'] ?? 'R', 0, 1))); ?></div>
                    <div class="activity-main">
                        <strong><?php echo e($visit['representative'] ?? 'Representative'); ?> <span><?php echo e(strtolower($visit['status'] ?? '') === 'completed' ? 'completed a visit' : 'updated a visit'); ?></span></strong>
                        <small><?php echo e($visit['facility'] ?? 'Facility not specified'); ?> · <?php echo e($visit['doctor'] ?? 'Doctor not specified'); ?></small>
                    </div>
                    <div class="activity-status">
                        <span class="status-dot status-<?php echo e(strtolower(str_replace(' ', '-', $visit['status'] ?? 'review'))); ?>"></span>
                        <small><?php echo e($visit['time'] ?? '—'); ?></small>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div class="empty-state">No visit activity has been recorded yet.</div>
            <?php endif; ?>
        </div>
    </article>

    <article class="panel coverage-panel">
        <div class="panel-header">
            <div><p class="eyebrow">TERRITORY PULSE</p><h2>Coverage by area</h2></div>
            <a href="<?php echo e(route('analytics')); ?>" class="icon-link" aria-label="Open analytics">↗</a>
        </div>
        <div class="coverage-visual">
            <div class="coverage-ring"><strong>78<span>%</span></strong><small>overall coverage</small></div>
            <div class="coverage-legend">
                <div><span class="legend-dot dot-indigo"></span><div><strong>Makati Central</strong><small>92% covered</small></div></div>
                <div><span class="legend-dot dot-teal"></span><div><strong>Quezon City South</strong><small>84% covered</small></div></div>
                <div><span class="legend-dot dot-amber"></span><div><strong>Pasig East</strong><small>78% covered</small></div></div>
                <div><span class="legend-dot dot-muted"></span><div><strong>Other territories</strong><small>54% covered</small></div></div>
            </div>
        </div>
    </article>
</section>

<section class="lower-grid">
    <article class="panel">
        <div class="panel-header">
            <div><p class="eyebrow">ATTENTION REQUIRED</p><h2>Open alerts</h2></div>
            <a href="<?php echo e(route('alerts')); ?>" class="text-link">Review all <span>→</span></a>
        </div>
        <div class="alert-list">
            <?php $__currentLoopData = array_slice($alerts, 0, 3); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $alert): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="alert-row">
                    <span class="severity-marker severity-<?php echo e($alert['severity'] ?? 'low'); ?>"></span>
                    <div><strong><?php echo e($alert['title'] ?? 'Field alert'); ?></strong><small><?php echo e($alert['detail'] ?? 'Review this activity'); ?></small></div>
                    <time><?php echo e($alert['time'] ?? '—'); ?></time>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </article>
    <article class="panel">
        <div class="panel-header">
            <div><p class="eyebrow">FIELD TEAM</p><h2>Representative pulse</h2></div>
            <a href="<?php echo e(route('representatives')); ?>" class="text-link">View team <span>→</span></a>
        </div>
        <div class="team-pulse">
            <?php $__currentLoopData = array_slice($representatives, 0, 4); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $rep): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="team-row"><div class="avatar avatar-<?php echo e($loop->index + 1); ?>"><?php echo e(strtoupper(substr($rep['name'] ?? 'R', 0, 1))); ?></div><div class="team-name"><strong><?php echo e($rep['name']); ?></strong><small><?php echo e($rep['territory'] ?? 'Unassigned'); ?></small></div><div class="team-score"><strong><?php echo e($rep['coverage'] ?? 0); ?>%</strong><small>coverage</small></div></div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </article>
</section><?php /**PATH C:\Capstone\Preventia-Field-Monitoring\preventia-laravel\resources\views/dashboard/sections/dashboard.blade.php ENDPATH**/ ?>