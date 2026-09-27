<article class="car-card card">
    <?php if (!empty($car['image_path'])): ?>
        <img class="card-img" src="<?= Security::e(Storage::url('cars', $car['image_path'])) ?>" alt="<?= Security::e($car['name']) ?>">
    <?php else: ?>
        <div class="card-img card-img-placeholder">🚗</div>
    <?php endif; ?>
    <span class="badge badge-<?= Security::e($car['availability_status']) ?>"><?= Security::e(ucfirst($car['availability_status'])) ?></span>
    <h3><?= Security::e($car['name']) ?></h3>
    <p class="car-meta"><?= Security::e($car['model']) ?> · <?= Security::e($car['type']) ?></p>
    <p class="price-tag">৳<?= number_format((float) $car['price_per_day'], 0) ?> <span>/ day</span></p>
    <a class="link-arrow" href="<?= app_link('/car', ['id' => (int) $car['id']]) ?>">View details →</a>
</article>
