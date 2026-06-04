<div class="invoice card">
    <h1>Rental Invoice</h1>
    <p class="invoice-id">Order #<?= (int) $order['id'] ?></p>
    <div class="invoice-grid">
        <div>
            <h3>Car</h3>
            <p><strong><?= Security::e($order['car_name']) ?></strong></p>
            <p><?= Security::e($order['car_model']) ?> · <?= Security::e($order['car_type']) ?></p>
        </div>
        <div>
            <h3>Rental Period</h3>
            <p><?= Security::e($order['start_date']) ?> to <?= Security::e($order['end_date']) ?></p>
        </div>
        <div>
            <h3>Total Cost</h3>
            <p class="price-tag large">৳<?= number_format((float) $order['total_cost'], 2) ?></p>
        </div>
    </div>
    <div class="invoice-actions">
        <form method="post" action="<?= app_url('/order/cancel') ?>">
            <?= Security::csrfField() ?>
            <input type="hidden" name="order_id" value="<?= (int) $order['id'] ?>">
            <button type="submit" class="btn btn-outline danger" data-ajax-cancel="<?= (int) $order['id'] ?>">Cancel Order</button>
        </form>
        <form method="post" action="<?= app_url('/order/finalize') ?>">
            <?= Security::csrfField() ?>
            <input type="hidden" name="order_id" value="<?= (int) $order['id'] ?>">
            <button type="submit" class="btn">Finalize & Pay</button>
        </form>
    </div>
</div>
<script src="<?= BASE_URL ?>/public/js/order.js"></script>
