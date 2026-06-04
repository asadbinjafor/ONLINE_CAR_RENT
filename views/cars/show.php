<div class="detail-layout">
    <div class="detail-media card">
        <?php if (!empty($car['image_path'])): ?>
            <img class="detail-img" src="<?= CAR_UPLOAD_WEB ?>/<?= Security::e($car['image_path']) ?>" alt="<?= Security::e($car['name']) ?>">
        <?php else: ?>
            <div class="detail-img card-img-placeholder">🚗</div>
        <?php endif; ?>
    </div>
    <div class="detail-info card">
        <span class="badge badge-<?= Security::e($car['availability_status']) ?>"><?= Security::e(ucfirst($car['availability_status'])) ?></span>
        <h1><?= Security::e($car['name']) ?></h1>
        <p class="car-meta"><?= Security::e($car['model']) ?> · <?= Security::e($car['type']) ?></p>
        <p class="price-tag large">৳<?= number_format((float) $car['price_per_day'], 0) ?> <span>/ day</span></p>
        <p><?= Security::e($car['description']) ?></p>
        <?php if ($car['availability_status'] === 'available'): ?>
            <?php if ($authUser && $authUser['role'] === 'member'): ?>
                <a class="btn" href="<?= app_link('/order/create', ['car_id' => (int) $car['id']]) ?>">Rent This Car</a>
            <?php elseif (!$authUser): ?>
                <p class="text-muted">Please <a href="<?= app_url('/login') ?>">login</a> as a member to rent.</p>
            <?php else: ?>
                <p class="text-muted">Only members can place rental orders.</p>
            <?php endif; ?>
        <?php else: ?>
            <p class="alert alert-error">This car is currently not available.</p>
        <?php endif; ?>
        <a class="btn btn-outline" href="<?= app_url('/cars') ?>">← Back to listings</a>
    </div>
</div>
