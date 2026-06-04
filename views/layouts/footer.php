</main>
<footer class="site-footer">
    <div class="footer-inner">
        <p>&copy; <?= date('Y') ?> <?= Security::e(APP_NAME) ?> — Drive your journey with confidence.</p>
    </div>
</footer>
<script>
window.APP_BASE = <?= json_encode(rtrim(BASE_URL, '/')) ?>;
window.CSRF_TOKEN = <?= json_encode(Security::csrfToken()) ?>;
window.routeUrl = function (path) {
    const index = window.APP_BASE + '/index.php';
    if (!path || path === '/') return index;
    return index + '?route=' + encodeURIComponent(path);
};
window.routeLink = function (path, params) {
    let url = window.routeUrl(path);
    if (params && Object.keys(params).length) {
        const qs = new URLSearchParams(params).toString();
        url += (url.includes('?') ? '&' : '?') + qs;
    }
    return url;
};
</script>
<script src="<?= BASE_URL ?>/public/js/app.js"></script>
</body>
</html>
