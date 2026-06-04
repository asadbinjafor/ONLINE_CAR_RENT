(function () {
    const routeUrl = window.routeUrl || function (p) { return (window.APP_BASE || '') + '/index.php?route=' + encodeURIComponent(p); };
    const form = document.getElementById('blog-form');
    if (form) {
        form.addEventListener('submit', (e) => {
            e.preventDefault();
            const title = document.getElementById('blog_title').value.trim();
            const content = document.getElementById('blog_content').value.trim();
            if (!title || !content) return;

            fetch(routeUrl('/api/blogs/create'), {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ title, content, csrf_token: window.CSRF_TOKEN }),
            })
                .then((r) => r.json())
                .then((data) => {
                    if (!data.success) {
                        alert(data.message || 'Could not post.');
                        return;
                    }
                    const list = document.getElementById('blog-list');
                    const empty = list.querySelector('.text-muted');
                    if (empty) empty.remove();

                    const post = data.post;
                    const article = document.createElement('article');
                    article.className = 'blog-card card';
                    article.id = 'blog-post-' + post.id;
                    article.innerHTML = `
                        <header class="blog-header">
                            <h3>${escapeHtml(post.title)}</h3>
                            <p class="blog-meta">By ${escapeHtml(post.author_name)} · Just now</p>
                        </header>
                        <p>${escapeHtml(post.content).replace(/\n/g, '<br>')}</p>
                        <button type="button" class="btn btn-sm danger" data-delete-blog="${post.id}">Delete</button>
                    `;
                    list.prepend(article);
                    bindDelete(article.querySelector('[data-delete-blog]'));
                    form.reset();
                });
        });
    }

    function bindDelete(btn) {
        if (!btn) return;
        btn.addEventListener('click', () => deletePost(btn.getAttribute('data-delete-blog')));
    }

    function deletePost(id) {
        if (!confirm('Delete this post?')) return;
        fetch(routeUrl('/api/blogs/' + id), { method: 'DELETE' })
            .then((r) => r.json())
            .then((data) => {
                if (data.success) {
                    document.getElementById('blog-post-' + id)?.remove();
                } else {
                    alert(data.message || 'Delete failed.');
                }
            });
    }

    document.querySelectorAll('[data-delete-blog]').forEach((btn) => bindDelete(btn));

    function escapeHtml(str) {
        const d = document.createElement('div');
        d.textContent = str;
        return d.innerHTML;
    }
})();
