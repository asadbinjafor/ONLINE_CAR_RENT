<div class="auth-wrap card">
    <h1>Login</h1>
    <?php if (!empty($errors['general'])): ?><div class="alert alert-error"><?= Security::e($errors['general']) ?></div><?php endif; ?>
    <form method="post" action="<?= app_url('/login') ?>" class="form-stack" data-validate="login">
        <?= Security::csrfField() ?>
        <div class="field">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" required value="<?= Security::e($old['email'] ?? '') ?>">
            <?php if (!empty($errors['email'])): ?><span class="field-error"><?= Security::e($errors['email']) ?></span><?php endif; ?>
        </div>
        <div class="field">
            <label for="password">Password</label>
            <input type="password" id="password" name="password" required>
            <?php if (!empty($errors['password'])): ?><span class="field-error"><?= Security::e($errors['password']) ?></span><?php endif; ?>
        </div>
        <div class="field field-check">
            <label><input type="checkbox" name="remember_me" value="1"> Remember me</label>
        </div>
        <button type="submit" class="btn">Login</button>
    </form>
    <p class="form-footer">No account? <a href="<?= app_url('/register') ?>">Register</a></p>
</div>
