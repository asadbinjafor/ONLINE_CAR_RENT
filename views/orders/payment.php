<div class="form-card card">
    <h1>Choose Payment Method</h1>
    <p>Order #<?= (int) $order['id'] ?> — Total: <strong>৳<?= number_format((float) $order['total_cost'], 2) ?></strong></p>
    <form method="post" action="<?= app_url('/order/payment') ?>" class="form-stack" data-validate="payment" id="payment-form">
        <?= Security::csrfField() ?>
        <input type="hidden" name="order_id" value="<?= (int) $order['id'] ?>">
        <div class="field">
            <label for="payment_method">Payment method</label>
            <select id="payment_method" name="payment_method" required>
                <option value="">Select method</option>
                <?php foreach (PAYMENT_METHODS as $key => $label): ?>
                    <option value="<?= Security::e($key) ?>"><?= Security::e($label) ?></option>
                <?php endforeach; ?>
            </select>
            <?php if (!empty($errors['payment_method'])): ?><span class="field-error"><?= Security::e($errors['payment_method']) ?></span><?php endif; ?>
        </div>
        <div class="field" id="transaction-field">
            <label for="transaction_id">Transaction / Reference ID</label>
            <input type="text" id="transaction_id" name="transaction_id" placeholder="Required for card, bKash, Nagad, bank">
            <?php if (!empty($errors['transaction_id'])): ?><span class="field-error"><?= Security::e($errors['transaction_id']) ?></span><?php endif; ?>
        </div>
        <button type="submit" class="btn">Confirm Payment</button>
    </form>
</div>
