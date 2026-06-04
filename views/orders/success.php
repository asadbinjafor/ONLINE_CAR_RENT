<div class="success-card card">
    <div class="success-icon">✓</div>
    <h1>Booking Confirmed!</h1>
    <p>Your rental has been confirmed. Here is your summary:</p>
    <ul class="summary-list">
        <li><strong>Order:</strong> #<?= (int) $order['id'] ?></li>
        <li><strong>Car:</strong> <?= Security::e($order['car_name']) ?> (<?= Security::e($order['car_model']) ?>)</li>
        <li><strong>Dates:</strong> <?= Security::e($order['start_date']) ?> → <?= Security::e($order['end_date']) ?></li>
        <li><strong>Total:</strong> ৳<?= number_format((float) $order['total_cost'], 2) ?></li>
        <?php if ($payment): ?>
            <li><strong>Payment:</strong> <?= Security::e(PAYMENT_METHODS[$payment['payment_method']] ?? $payment['payment_method']) ?></li>
            <li><strong>Transaction ID:</strong> <?= Security::e($payment['transaction_id']) ?></li>
        <?php endif; ?>
    </ul>
    <a class="btn" href="<?= app_url('/') ?>">Back to Home</a>
    <a class="btn btn-outline" href="<?= app_url('/profile') ?>">View Rental History</a>
</div>
