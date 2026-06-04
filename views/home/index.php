<section class="hero">
    <div class="hero-content">
        <span class="hero-badge">Premium Car Rental</span>
        <h1>Find the perfect ride for every journey</h1>
        <p>Browse sedans, SUVs, microbuses and more. Book online in minutes with flexible payment options.</p>
        <div class="hero-actions">
            <a class="btn" href="<?= app_url('/cars') ?>">Browse All Cars</a>
            <?php if (!$authUser): ?>
                <a class="btn btn-outline" href="<?= app_url('/register') ?>">Join Now</a>
            <?php endif; ?>
        </div>
    </div>
</section>

<section class="section">
    <h2 class="section-title">Browse by Category</h2>
    <div class="category-bar">
        <a class="category-chip <?= empty($activeCategory) ? 'active' : '' ?>" href="<?= app_url('/cars') ?>">All</a>
        <?php foreach ($categories as $cat): ?>
            <a class="category-chip" href="<?= app_link('/cars', ['type' => $cat]) ?>"><?= Security::e($cat) ?></a>
        <?php endforeach; ?>
    </div>
</section>

<section class="section">
    <h2 class="section-title">Featured Cars</h2>
    <?php if (empty($featuredCars)): ?>
        <p class="text-muted">No cars available yet. Admin can add cars from the dashboard.</p>
    <?php else: ?>
        <div class="card-grid">
            <?php foreach ($featuredCars as $car): ?>
                <?php include ROOT_DIR . '/views/partials/car_card.php'; ?>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</section>

<section class="section promo-strip">
    <div class="promo-card">
        <h3>Share your rental story</h3>
        <p>Tell others about your experience on our blog page.</p>
        <a class="btn btn-outline" href="<?= app_url('/blog') ?>">Visit Blog</a>
    </div>
</section>
