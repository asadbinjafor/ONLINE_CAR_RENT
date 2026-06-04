(function () {
    const routeUrl = window.routeUrl || function (p) { return (window.APP_BASE || '') + '/index.php?route=' + encodeURIComponent(p); };
    const routeLink = window.routeLink || function (p, q) {
        let u = routeUrl(p);
        if (q) u += (u.includes('?') ? '&' : '?') + new URLSearchParams(q).toString();
        return u;
    };
    const form = document.getElementById('car-search-form');
    const results = document.getElementById('car-results');
    if (!form || !results) return;

    let timer;
    const runSearch = () => {
        const q = form.querySelector('#q')?.value || '';
        const type = form.querySelector('#type')?.value || '';
        const params = new URLSearchParams({ q, type });
        fetch(routeUrl('/api/cars/search') + '&' + params)
            .then((r) => r.json())
            .then((data) => {
                if (!data.success) return;
                if (!data.cars.length) {
                    results.innerHTML = '<p class="text-muted">No cars found.</p>';
                    return;
                }
                results.innerHTML = data.cars.map((car) => `
                    <article class="car-card card">
                        ${car.image_url
                            ? `<img class="card-img" src="${car.image_url}" alt="${car.name}">`
                            : '<div class="card-img card-img-placeholder">🚗</div>'}
                        <span class="badge badge-${car.availability_status}">${car.availability_status}</span>
                        <h3>${escapeHtml(car.name)}</h3>
                        <p class="car-meta">${escapeHtml(car.model)} · ${escapeHtml(car.type)}</p>
                        <p class="price-tag">৳${Number(car.price_per_day).toLocaleString()} <span>/ day</span></p>
                        <a class="link-arrow" href="${car.url}">View details →</a>
                    </article>
                `).join('');
            })
            .catch(() => {});
    };

    form.addEventListener('input', () => {
        clearTimeout(timer);
        timer = setTimeout(runSearch, 350);
    });

    function escapeHtml(str) {
        const d = document.createElement('div');
        d.textContent = str;
        return d.innerHTML;
    }
})();
