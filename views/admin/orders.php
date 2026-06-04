<h1 class="section-title">Rent Order History</h1>
<div class="search-panel card">
    <form method="get" action="<?= app_url('/admin/orders') ?>" class="search-grid">
        <div class="field">
            <label for="status">Status</label>
            <select id="status" name="status">
                <option value="">All</option>
                <?php foreach (ORDER_STATUSES as $s): ?>
                    <option value="<?= Security::e($s) ?>" <?= ($filters['status'] ?? '') === $s ? 'selected' : '' ?>><?= Security::e(ucfirst($s)) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="field">
            <label for="from">From</label>
            <input type="date" id="from" name="from" value="<?= Security::e($filters['from'] ?? '') ?>">
        </div>
        <div class="field">
            <label for="to">To</label>
            <input type="date" id="to" name="to" value="<?= Security::e($filters['to'] ?? '') ?>">
        </div>
        <button type="submit" class="btn">Filter</button>
    </form>
</div>
<div class="table-wrap card">
    <table class="data-table">
        <thead>
            <tr>
                <th>Order</th>
                <th>Member</th>
                <th>Car</th>
                <th>Dates</th>
                <th>Total</th>
                <th>Status</th>
                <th>Payment</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($orders)): ?>
                <tr><td colspan="7">No orders found.</td></tr>
            <?php else: ?>
                <?php foreach ($orders as $o): ?>
                    <tr>
                        <td>#<?= (int) $o['id'] ?></td>
                        <td><?= Security::e($o['user_name']) ?><br><small><?= Security::e($o['user_email']) ?></small></td>
                        <td><?= Security::e($o['car_name']) ?> (<?= Security::e($o['car_model']) ?>)</td>
                        <td><?= Security::e($o['start_date']) ?> → <?= Security::e($o['end_date']) ?></td>
                        <td>৳<?= number_format((float) $o['total_cost'], 2) ?></td>
                        <td><span class="status-pill status-<?= Security::e($o['status']) ?>"><?= Security::e(ucfirst($o['status'])) ?></span></td>
                        <td><?= Security::e($o['payment_method'] ? (PAYMENT_METHODS[$o['payment_method']] ?? $o['payment_method']) : '—') ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>
