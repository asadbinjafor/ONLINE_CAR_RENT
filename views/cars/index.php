<section class="section">
    <h1 class="section-title"><?= Security::e($title) ?></h1>

    <div class="search-panel card">
        <form class="search-grid" id="car-search-form" method="get" action="<?= app_url('/cars') ?>">
            <div class="field">
                <label for="q">Search</label>
                <input type="search" id="q" name="q" value="<?= Security::e($search ?? '') ?>" placeholder="Car name, model...">
            </div>
            <div class="field">
                <label for="type">Category</label>
                <select id="type" name="type">
                    <option value="">All types</option>
                    <?php foreach ($categories as $cat): ?>
                        <option value="<?= Security::e($cat) ?>" <?= ($activeCategory ?? '') === $cat ? 'selected' : '' ?>><?= Security::e($cat) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <button type="submit" class="btn">Search</button>
        </form>
    </div>

    <div class="category-bar">
        <a class="category-chip <?= empty($activeCategory) ? 'active' : '' ?>" href="<?= app_url('/cars') ?>">All</a>
        <?php foreach ($categories as $cat): ?>
            <a class="category-chip <?= ($activeCategory ?? '') === $cat ? 'active' : '' ?>" href="<?= app_link('/cars', ['type' => $cat]) ?>"><?= Security::e($cat) ?></a>
        <?php endforeach; ?>
    </div>

    <div class="card-grid" id="car-results">
        <?php if (empty($cars)): ?>
            <p class="text-muted">No cars found.</p>
        <?php else: ?>
            <?php foreach ($cars as $car): ?>
                <?php include ROOT_DIR . '/views/partials/car_card.php'; ?>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</section>
<script src="<?= BASE_URL ?>/public/js/search.js"></script>
