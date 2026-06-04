<h1 class="section-title">Admin Dashboard</h1>
<div class="stats-grid">
    <div class="stat-card card">
        <span class="stat-icon">🚗</span>
        <strong><?= (int) $stats['cars'] ?></strong>
        <span>Total Cars</span>
    </div>
    <div class="stat-card card">
        <span class="stat-icon">👥</span>
        <strong><?= (int) $stats['members'] ?></strong>
        <span>Members</span>
    </div>
    <div class="stat-card card">
        <span class="stat-icon">📋</span>
        <strong><?= (int) $stats['orders'] ?></strong>
        <span>Orders</span>
    </div>
    <div class="stat-card card">
        <span class="stat-icon">📝</span>
        <strong><?= (int) $stats['blogs'] ?></strong>
        <span>Blog Posts</span>
    </div>
</div>
<div class="admin-links">
    <a class="btn" href="<?= app_url('/admin/cars') ?>">Manage Cars</a>
    <a class="btn btn-outline" href="<?= app_url('/admin/members') ?>">Manage Members</a>
    <a class="btn btn-outline" href="<?= app_url('/admin/orders') ?>">Order History</a>
</div>
