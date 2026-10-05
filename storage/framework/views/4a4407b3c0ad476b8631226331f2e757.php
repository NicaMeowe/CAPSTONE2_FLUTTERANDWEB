<?php ($facilities = $payload['collections']['facilities']); ?>
<section class="page-heading compact-heading">
    <div><p class="eyebrow">MONITOR / CARE NETWORK</p><h1>Know every territory.</h1><p class="page-subtitle">Facility coverage, doctor relationships, and stock signals in one place.</p></div>
    <button class="secondary-button" data-action="download">↓ <span>Export facilities</span></button>
</section>
<section class="facility-grid">
<?php $__currentLoopData = $facilities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $facility): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <article class="facility-card panel">
        <div class="facility-card-top"><span class="facility-type"><?php echo e($facility['type'] ?? 'Facility'); ?></span><span class="facility-more">···</span></div>
        <h2><?php echo e($facility['name'] ?? 'Unnamed facility'); ?></h2>
        <p><?php echo e($facility['territory'] ?? 'Unassigned territory'); ?></p>
        <div class="facility-metrics"><div><strong><?php echo e($facility['doctors'] ?? 0); ?></strong><small>doctors</small></div><div><strong><?php echo e($facility['coverage'] ?? 0); ?>%</strong><small>coverage</small></div><div><strong class="stock-<?php echo e(strtolower(str_replace(' ', '-', $facility['stock'] ?? 'healthy'))); ?>"><?php echo e($facility['stock'] ?? 'Healthy'); ?></strong><small>stock</small></div></div>
        <div class="facility-progress"><span style="width: <?php echo e($facility['coverage'] ?? 0); ?>%"></span></div>
    </article>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</section>
<section class="panel map-placeholder"><div class="map-grid"></div><div class="map-copy"><span class="map-pin">+</span><p class="eyebrow">TERRITORY MAP</p><h2>GPS coverage map</h2><p>Live geofence activity will appear here when representative locations sync with Firebase.</p><a href="<?php echo e(route('analytics')); ?>" class="text-link">Explore territory insights <span>→</span></a></div><div class="map-label label-one">Makati Central</div><div class="map-label label-two">QC South</div><div class="map-label label-three">Pasig East</div></section><?php /**PATH /home/runner/workspace/preventia-laravel/resources/views/dashboard/sections/facilities.blade.php ENDPATH**/ ?>