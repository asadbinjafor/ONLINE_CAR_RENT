<div class="form-card card">
    <h1>Rent <?= Security::e($car['name']) ?></h1>
    <p class="car-meta"><?= Security::e($car['model']) ?> · ৳<?= number_format((float) $car['price_per_day'], 0) ?>/day</p>
    <form method="post" action="<?= app_url('/order/create') ?>" class="form-stack" data-validate="order" id="order-form">
        <?= Security::csrfField() ?>
        <input type="hidden" name="car_id" value="<?= (int) $car['id'] ?>">
        <div class="field">
            <label for="start_date">Start date</label>
            <input type="date" id="start_date" name="start_date" required min="<?= date('Y-m-d') ?>">
            <?php if (!empty($errors['start_date'])): ?><span class="field-error"><?= Security::e($errors['start_date']) ?></span><?php endif; ?>
        </div>
        <div class="field">
            <label for="end_date">End date</label>
            <input type="date" id="end_date" name="end_date" required min="<?= date('Y-m-d') ?>">
            <?php if (!empty($errors['end_date'])): ?><span class="field-error"><?= Security::e($errors['end_date']) ?></span><?php endif; ?>
        </div>
        <?php if (!empty($errors['dates'])): ?><span class="field-error"><?= Security::e($errors['dates']) ?></span><?php endif; ?>
        <div class="cost-preview card" id="cost-preview">
            <p>Select dates to see estimated total.</p>
        </div>
        <button type="submit" class="btn">Place Order</button>
    </form>
</div>
<script>
window.ORDER_CAR_ID = <?= (int) $car['id'] ?>;
</script>
<script src="<?= BASE_URL ?>/public/js/order.js"></script>
