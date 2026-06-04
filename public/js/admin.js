(function () {
    const routeUrl = window.routeUrl || function (p) { return (window.APP_BASE || '') + '/index.php?route=' + encodeURIComponent(p); };
    document.querySelectorAll('[data-delete-member]').forEach((btn) => {
        btn.addEventListener('click', () => {
            const id = btn.getAttribute('data-delete-member');
            if (!confirm('Delete this member and all related data?')) return;

            const fd = new FormData();
            fd.append('member_id', id);
            fd.append('csrf_token', window.CSRF_TOKEN);

            fetch(routeUrl('/api/admin/member/delete'), { method: 'POST', body: fd })
                .then((r) => r.json())
                .then((data) => {
                    if (data.success) {
                        document.getElementById('member-row-' + id)?.remove();
                    } else {
                        alert(data.message || 'Delete failed.');
                    }
                });
        });
    });
})();
