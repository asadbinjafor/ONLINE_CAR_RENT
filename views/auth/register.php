<div class="auth-wrap card">
    <h1>Register</h1>
    <form method="post" action="<?= app_url('/register') ?>" class="form-stack" data-validate="register">
        <?= Security::csrfField() ?>
        <div class="field">
            <label for="name">Full name</label>
            <input type="text" id="name" name="name" required value="<?= Security::e($old['name'] ?? '') ?>">
            <?php if (!empty($errors['name'])): ?><span class="field-error"><?= Security::e($errors['name']) ?></span><?php endif; ?>
        </div>
        <div class="field">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" required value="<?= Security::e($old['email'] ?? '') ?>">
            <?php if (!empty($errors['email'])): ?><span class="field-error"><?= Security::e($errors['email']) ?></span><?php endif; ?>
        </div>
        <div class="field">
            <label for="address">Address</label>
            <input type="text" id="address" name="address" required value="<?= Security::e($old['address'] ?? '') ?>">
            <?php if (!empty($errors['address'])): ?><span class="field-error"><?= Security::e($errors['address']) ?></span><?php endif; ?>
        </div>
        <div class="field">
            <label for="phone">Phone</label>
            <input type="tel" id="phone" name="phone" required value="<?= Security::e($old['phone'] ?? '') ?>">
            <?php if (!empty($errors['phone'])): ?><span class="field-error"><?= Security::e($errors['phone']) ?></span><?php endif; ?>
        </div>
        <div class="field">
            <label for="password">Password (min 8 chars)</label>
            <input type="password" id="password" name="password" required minlength="8">
            <?php if (!empty($errors['password'])): ?><span class="field-error"><?= Security::e($errors['password']) ?></span><?php endif; ?>
        </div>
        <div class="field">
            <label for="password_confirm">Confirm password</label>
            <input type="password" id="password_confirm" name="password_confirm" required minlength="8">
            <?php if (!empty($errors['password_confirm'])): ?><span class="field-error"><?= Security::e($errors['password_confirm']) ?></span><?php endif; ?>
        </div>
        <button type="submit" class="btn">Create account</button>
    </form>
    <p class="form-footer">Already registered? <a href="<?= app_url('/login') ?>">Login</a></p>
</div>
