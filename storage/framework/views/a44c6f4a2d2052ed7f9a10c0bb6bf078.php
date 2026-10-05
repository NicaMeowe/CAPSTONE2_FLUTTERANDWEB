<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title><?php echo e($pageTitle ?? 'Preventia'); ?> · Preventia</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Mono:wght@400;500&family=DM+Sans:wght@400;500;600;700&family=Space+Grotesk:wght@500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo e(asset('css/admin.css')); ?>">
</head>
<body>
<div class="app-shell">
    <aside class="sidebar" id="sidebar">
        <div class="brand">
            <div class="brand-mark"><span></span><span></span><span></span></div>
            <div>
                <strong>preventia</strong>
                <small>field intelligence</small>
            </div>
        </div>

        <div class="workspace-switcher">
            <span class="workspace-dot"></span>
            <div>
                <small>Workspace</small>
                <strong>Preventia Healthcare</strong>
            </div>
            <span class="switcher-chevron">⌄</span>
        </div>

        <nav class="primary-nav" aria-label="Primary navigation">
            <p class="nav-label">Monitor</p>
            <a class="nav-link <?php echo e($page === 'dashboard' ? 'active' : ''); ?>" href="<?php echo e(route('dashboard')); ?>">
                <span class="nav-icon">OV</span><span>Overview</span>
            </a>
            <a class="nav-link <?php echo e($page === 'visits' ? 'active' : ''); ?>" href="<?php echo e(route('visits')); ?>">
                <span class="nav-icon">VT</span><span>Visit history</span>
                <span class="nav-count"><?php echo e($payload['stats']['totalVisits'] ?? 0); ?></span>
            </a>
            <a class="nav-link <?php echo e($page === 'representatives' ? 'active' : ''); ?>" href="<?php echo e(route('representatives')); ?>">
                <span class="nav-icon">RP</span><span>Representatives</span>
            </a>
            <a class="nav-link <?php echo e($page === 'facilities' ? 'active' : ''); ?>" href="<?php echo e(route('facilities')); ?>">
                <span class="nav-icon">FC</span><span>Facilities</span>
            </a>

            <p class="nav-label nav-label-spaced">Understand</p>
            <a class="nav-link <?php echo e($page === 'analytics' ? 'active' : ''); ?>" href="<?php echo e(route('analytics')); ?>">
                <span class="nav-icon">AN</span><span>Predictive analytics</span>
                <span class="nav-new">New</span>
            </a>
            <a class="nav-link <?php echo e($page === 'alerts' ? 'active' : ''); ?>" href="<?php echo e(route('alerts')); ?>">
                <span class="nav-icon">AL</span><span>Alerts & requests</span>
                <?php if(($payload['stats']['activeAlerts'] ?? 0) > 0): ?>
                    <span class="nav-count"><?php echo e($payload['stats']['activeAlerts']); ?></span>
                <?php endif; ?>
            </a>

            <p class="nav-label nav-label-spaced">Workspace</p>
            <a class="nav-link <?php echo e($page === 'settings' ? 'active' : ''); ?>" href="<?php echo e(route('settings')); ?>">
                <span class="nav-icon">ST</span><span>Settings</span>
            </a>
        </nav>

        <div class="sidebar-bottom">
            <div class="privacy-note">
                <span class="privacy-dot"></span>
                <div>
                    <strong>Secure monitoring</strong>
                    <small>Activity is tracked during official hours only.</small>
                </div>
            </div>
            <div class="user-profile">
                <div class="avatar avatar-indigo"><?php echo e(strtoupper(substr(session('preventia_admin_name', 'nica'), 0, 1))); ?></div>
                <div class="user-copy">
                    <strong><?php echo e(session('preventia_admin_name', 'nica')); ?></strong>
                    <small>Administrator</small>
                </div>
                <form method="POST" action="<?php echo e(route('logout')); ?>">
                    <?php echo csrf_field(); ?>
                    <button type="submit" class="logout-button" aria-label="Sign out">↗</button>
                </form>
            </div>
        </div>
    </aside>

    <main class="main-content">
        <header class="topbar">
            <button class="mobile-menu" id="mobile-menu" aria-label="Open navigation">☰</button>
            <div class="breadcrumb">
                <span>Preventia Healthcare</span>
                <span class="breadcrumb-slash">/</span>
                <strong><?php echo e($pageTitle ?? 'Overview'); ?></strong>
            </div>
            <div class="topbar-actions">
                <span class="topbar-date">Wednesday, September 23, 2026</span>
                <button class="icon-button" aria-label="Notifications">○<span class="notification-dot"></span></button>
                <div class="top-avatar"><?php echo e(strtoupper(substr(session('preventia_admin_name', 'nica'), 0, 1))); ?></div>
            </div>
        </header>

        <div class="page-wrap">
            <?php if(($payload['connection']['mode'] ?? 'demo') === 'demo'): ?>
                <div class="connection-banner">
                    <span class="banner-icon">!</span>
                    <div>
                        <strong>Preview mode is active</strong>
                        <span>Firebase access is not authorized yet, so you are seeing representative data. Add <code>FIREBASE_DATABASE_AUTH_TOKEN</code> to load live records from the configured database.</span>
                    </div>
                    <a href="<?php echo e(route('settings')); ?>">View setup</a>
                </div>
            <?php endif; ?>

            <?php echo $__env->yieldContent('content'); ?>
        </div>
    </main>
</div>
<script>
    window.preventiaData = <?php echo json_encode($payload, 15, 512) ?>;
    window.preventiaRoutes = {
        alertStatus: <?php echo json_encode(route('admin.alerts.status', ['alertId' => '__ID__']), 512) ?>,
        visitReview: <?php echo json_encode(route('admin.visits.review', ['visitId' => '__ID__']), 512) ?>
    };
</script>
<script src="<?php echo e(asset('js/admin.js')); ?>"></script>
</body>
</html><?php /**PATH /home/runner/workspace/preventia-laravel/resources/views/layouts/app.blade.php ENDPATH**/ ?>