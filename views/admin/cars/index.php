<div class="admin-header">
    <h1>Manage Cars</h1>
    <a class="btn" href="<?= app_url('/admin/cars/create') ?>">+ Add Car</a>
</div>
<div class="table-wrap card">
    <table class="data-table">
        <thead>
            <tr>
                <th>Car</th>
                <th>Type</th>
                <th>Price/Day</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($cars as $car): ?>
                <tr>
                    <td>
                        <strong><?= Security::e($car['name']) ?></strong><br>
                        <small><?= Security::e($car['model']) ?></small>
                    </td>
                    <td><?= Security::e($car['type']) ?></td>
                    <td>৳<?= number_format((float) $car['price_per_day'], 0) ?></td>
                    <td><span class="status-pill status-<?= Security::e($car['availability_status']) ?>"><?= Security::e(ucfirst($car['availability_status'])) ?></span></td>
                    <td class="actions-cell">
                        <a class="btn btn-sm btn-outline" href="<?= app_link('/admin/cars/edit', ['id' => (int) $car['id']]) ?>">Edit</a>
                        <form method="post" action="<?= app_url('/admin/cars/delete') ?>" class="inline-form" onsubmit="return confirm('Delete this car?');">
                            <?= Security::csrfField() ?>
                            <input type="hidden" name="id" value="<?= (int) $car['id'] ?>">
                            <button type="submit" class="btn btn-sm danger">Delete</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
