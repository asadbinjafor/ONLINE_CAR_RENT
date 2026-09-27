<?php $isEdit = !empty($car['id']); ?>
<div class="form-card card">
    <h1><?= $isEdit ? 'Edit Car' : 'Add Car' ?></h1>
    <form method="post" action="<?= app_url($isEdit ? '/admin/cars/edit' : '/admin/cars/create') ?>" enctype="multipart/form-data" class="form-stack" data-validate="car">
        <?= Security::csrfField() ?>
        <?php if ($isEdit): ?><input type="hidden" name="id" value="<?= (int) $car['id'] ?>"><?php endif; ?>
        <div class="field">
            <label for="name">Name</label>
            <input type="text" id="name" name="name" required value="<?= Security::e($car['name'] ?? '') ?>">
            <?php if (!empty($errors['name'])): ?><span class="field-error"><?= Security::e($errors['name']) ?></span><?php endif; ?>
        </div>
        <div class="field">
            <label for="model">Model / Year</label>
            <input type="text" id="model" name="model" required value="<?= Security::e($car['model'] ?? '') ?>">
            <?php if (!empty($errors['model'])): ?><span class="field-error"><?= Security::e($errors['model']) ?></span><?php endif; ?>
        </div>
        <div class="field">
            <label for="type">Type</label>
            <select id="type" name="type" required>
                <?php foreach (CAR_TYPES as $t): ?>
                    <option value="<?= Security::e($t) ?>" <?= ($car['type'] ?? '') === $t ? 'selected' : '' ?>><?= Security::e($t) ?></option>
                <?php endforeach; ?>
            </select>
            <?php if (!empty($errors['type'])): ?><span class="field-error"><?= Security::e($errors['type']) ?></span><?php endif; ?>
        </div>
        <div class="field">
            <label for="price_per_day">Price per day (৳)</label>
            <input type="number" id="price_per_day" name="price_per_day" required min="0.01" step="0.01" value="<?= Security::e($car['price_per_day'] ?? '') ?>">
            <?php if (!empty($errors['price_per_day'])): ?><span class="field-error"><?= Security::e($errors['price_per_day']) ?></span><?php endif; ?>
        </div>
        <div class="field">
            <label for="availability_status">Availability</label>
            <select id="availability_status" name="availability_status">
                <?php foreach (CAR_STATUSES as $s): ?>
                    <option value="<?= Security::e($s) ?>" <?= ($car['availability_status'] ?? 'available') === $s ? 'selected' : '' ?>><?= Security::e(ucfirst($s)) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="field">
            <label for="description">Description</label>
            <textarea id="description" name="description" rows="4" required><?= Security::e($car['description'] ?? '') ?></textarea>
            <?php if (!empty($errors['description'])): ?><span class="field-error"><?= Security::e($errors['description']) ?></span><?php endif; ?>
        </div>
        <div class="field">
            <label for="image">Car image (JPEG/PNG, max 2MB)<?= $isEdit ? ' — leave empty to keep current' : '' ?></label>
            <?php if ($isEdit && !empty($car['image_path'])): ?>
                <img class="thumb" src="<?= Security::e(Storage::url('cars', $car['image_path'])) ?>" alt="">
            <?php endif; ?>
            <input type="file" id="image" name="image" accept="image/jpeg,image/png" <?= $isEdit ? '' : 'required' ?>>
            <?php if (!empty($errors['image'])): ?><span class="field-error"><?= Security::e($errors['image']) ?></span><?php endif; ?>
        </div>
        <button type="submit" class="btn"><?= $isEdit ? 'Update Car' : 'Add Car' ?></button>
        <a class="btn btn-outline" href="<?= app_url('/admin/cars') ?>">Cancel</a>
    </form>
</div>
