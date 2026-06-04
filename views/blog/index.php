<section class="section">
    <h1 class="section-title">Rental Experiences Blog</h1>

    <?php if ($authUser): ?>
    <div class="form-card card" id="blog-form-wrap">
        <h2>Share Your Experience</h2>
        <form id="blog-form" class="form-stack" data-validate="blog">
            <div class="field">
                <label for="blog_title">Title</label>
                <input type="text" id="blog_title" name="title" required>
                <span class="field-error" id="blog-title-error"></span>
            </div>
            <div class="field">
                <label for="blog_content">Content</label>
                <textarea id="blog_content" name="content" rows="5" required></textarea>
                <span class="field-error" id="blog-content-error"></span>
            </div>
            <button type="submit" class="btn">Post Experience</button>
        </form>
    </div>
    <?php else: ?>
        <p class="text-muted">Please <a href="<?= app_url('/login') ?>">login</a> to post your experience.</p>
    <?php endif; ?>

    <div class="blog-list" id="blog-list">
        <?php if (empty($posts)): ?>
            <p class="text-muted">No blog posts yet.</p>
        <?php else: ?>
            <?php foreach ($posts as $post): ?>
                <article class="blog-card card" id="blog-post-<?= (int) $post['id'] ?>">
                    <header class="blog-header">
                        <h3><?= Security::e($post['title']) ?></h3>
                        <p class="blog-meta">By <?= Security::e($post['author_name']) ?> · <?= Security::e(date('M j, Y', strtotime($post['created_at']))) ?></p>
                    </header>
                    <p><?= nl2br(Security::e($post['content'])) ?></p>
                    <?php
                    $canDelete = $authUser && (
                        $authUser['role'] === 'admin' || (int) $post['user_id'] === $authUser['id']
                    );
                    ?>
                    <?php if ($canDelete): ?>
                        <button type="button" class="btn btn-sm danger" data-delete-blog="<?= (int) $post['id'] ?>">Delete</button>
                    <?php endif; ?>
                </article>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</section>
<script src="<?= BASE_URL ?>/public/js/blog.js"></script>
