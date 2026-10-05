<?php ($representatives = $payload['collections']['representatives']); ?>
<section class="page-heading compact-heading">
    <div><p class="eyebrow">MONITOR / FIELD TEAM</p><h1>People in the field.</h1><p class="page-subtitle">Live representative status and territory coverage across Makati and nearby areas.</p></div>
    <button class="primary-button" data-action="notify">+ <span>Send update</span></button>
</section>
<section class="team-summary-grid">
    <div class="mini-stat panel"><span class="mini-stat-icon teal">●</span><div><strong><?php echo e($payload['stats']['activeRepresentatives']); ?></strong><small>Active now</small></div></div>
    <div class="mini-stat panel"><span class="mini-stat-icon indigo">↗</span><div><strong>81%</strong><small>Average coverage</small></div></div>
    <div class="mini-stat panel"><span class="mini-stat-icon amber">◷</span><div><strong>3.8h</strong><small>Avg. field time</small></div></div>
</section>
<section class="panel table-panel">
    <div class="panel-header"><div><p class="eyebrow">REPRESENTATIVE DIRECTORY</p><h2>Field team status</h2></div><div class="search-field compact-search"><span>⌕</span><input type="search" placeholder="Find a representative" data-table-search="#reps-table"></div></div>
    <div class="table-scroll"><table id="reps-table"><thead><tr><th>Representative</th><th>Territory</th><th>Today’s visits</th><th>Coverage</th><th>Status</th><th>Last seen</th></tr></thead><tbody>
    <?php $__currentLoopData = $representatives; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $rep): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <tr><td><div class="table-person"><div class="avatar avatar-<?php echo e($loop->index + 1); ?>"><?php echo e(strtoupper(substr($rep['name'] ?? 'R', 0, 1))); ?></div><div><strong><?php echo e($rep['name'] ?? '—'); ?></strong><small>Medical representative</small></div></div></td><td><?php echo e($rep['territory'] ?? 'Unassigned'); ?></td><td><span class="visit-number"><?php echo e($rep['visits'] ?? 0); ?></span></td><td><div class="table-progress"><span style="width: <?php echo e($rep['coverage'] ?? 0); ?>%"></span></div><strong class="progress-value"><?php echo e($rep['coverage'] ?? 0); ?>%</strong></td><td><span class="presence-status <?php echo e(strtolower($rep['status'] ?? '') === 'active' ? 'presence-active' : 'presence-away'); ?>"><i></i><?php echo e($rep['status'] ?? 'Unknown'); ?></span></td><td class="muted"><?php echo e($rep['lastSeen'] ?? '—'); ?></td></tr>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </tbody></table></div>
</section><?php /**PATH /home/runner/workspace/preventia-laravel/resources/views/dashboard/sections/representatives.blade.php ENDPATH**/ ?>