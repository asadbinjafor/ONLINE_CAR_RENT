<div class="profile-layout card">
    <h1>My Profile</h1>
    <?php if (!empty($success)): ?><div class="alert alert-success"><?= Security::e($success) ?></div><?php endif; ?>
    <form method="post" action="<?= app_url('/profile') ?>" enctype="multipart/form-data" class="form-stack" data-validate="profile">
        <?= Security::csrfField() ?>
        <div class="profile-photo">
            <?php if (!empty($user['profile_picture'])): ?>
                <img src="<?= PROFILE_UPLOAD_WEB ?>/<?= Security::e($user['profile_picture']) ?>" alt="Profile">
            <?php else: ?>
                <div class="avatar-placeholder"><?= Security::e(mb_substr($user['name'], 0, 1)) ?></div>
            <?php endif; ?>
        </div>
        <div class="field">
            <label for="name">Name</label>
            <input type="text" id="name" name="name" required value="<?= Security::e($user['name']) ?>">
            <?php if (!empty($errors['name'])): ?><span class="field-error"><?= Security::e($errors['name']) ?></span><?php endif; ?>
        </div>
        <div class="field">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" required value="<?= Security::e($user['email']) ?>">
            <?php if (!empty($errors['email'])): ?><span class="field-error"><?= Security::e($errors['email']) ?></span><?php endif; ?>
        </div>
        <div class="field">
            <label for="address">Address</label>
            <input type="text" id="address" name="address" required value="<?= Security::e($user['address'] ?? '') ?>">
            <?php if (!empty($errors['address'])): ?><span class="field-error"><?= Security::e($errors['address']) ?></span><?php endif; ?>
        </div>
        <div class="field">
            <label for="phone">Phone</label>
            <input type="tel" id="phone" name="phone" required value="<?= Security::e($user['phone'] ?? '') ?>">
            <?php if (!empty($errors['phone'])): ?><span class="field-error"><?= Security::e($errors['phone']) ?></span><?php endif; ?>
        </div>
        <div class="field">
            <label for="profile_picture">Profile picture (JPEG/PNG, max 2MB)</label>
            <input type="file" id="profile_picture" name="profile_picture" accept="image/jpeg,image/png">
            <?php if (!empty($errors['profile_picture'])): ?><span class="field-error"><?= Security::e($errors['profile_picture']) ?></span><?php endif; ?>
        </div>
        <hr>
        <h2>Change password</h2>
        <div class="field">
            <label for="current_password">Current password</label>
            <input type="password" id="current_password" name="current_password">
            <?php if (!empty($errors['current_password'])): ?><span class="field-error"><?= Security::e($errors['current_password']) ?></span><?php endif; ?>
        </div>
        <div class="field">
            <label for="new_password">New password</label>
            <input type="password" id="new_password" name="new_password" minlength="8">
            <?php if (!empty($errors['new_password'])): ?><span class="field-error"><?= Security::e($errors['new_password']) ?></span><?php endif; ?>
        </div>
        <div class="field">
            <label for="new_password_confirm">Confirm new password</label>
            <input type="password" id="new_password_confirm" name="new_password_confirm" minlength="8">
            <?php if (!empty($errors['new_password_confirm'])): ?><span class="field-error"><?= Security::e($errors['new_password_confirm']) ?></span><?php endif; ?>
        </div>
        <button type="submit" class="btn">Save changes</button>
    </form>
</div>

<?php if (!empty($rentalHistory)): ?>
<section class="section">
    <h2 class="section-title">Rental History</h2>
    <div class="table-wrap card">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Car</th>
                    <th>Dates</th>
                    <th>Total</th>
                    <th>Status</th>
                    <th>Payment</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($rentalHistory as $row): ?>
                    <tr>
                        <td><?= Security::e($row['car_name']) ?> (<?= Security::e($row['car_model']) ?>)</td>
                        <td><?= Security::e($row['start_date']) ?> → <?= Security::e($row['end_date']) ?></td>
                        <td>৳<?= number_format((float) $row['total_cost'], 2) ?></td>
                        <td><span class="status-pill status-<?= Security::e($row['status']) ?>"><?= Security::e(ucfirst($row['status'])) ?></span></td>
                        <td><?= Security::e($row['payment_method'] ? (PAYMENT_METHODS[$row['payment_method']] ?? $row['payment_method']) : '—') ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</section>
<?php endif; ?>
