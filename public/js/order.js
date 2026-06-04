(function () {
    const routeUrl = window.routeUrl || function (p) { return (window.APP_BASE || '') + '/index.php?route=' + encodeURIComponent(p); };
    const form = document.getElementById('order-form');
    const preview = document.getElementById('cost-preview');
    const startEl = document.getElementById('start_date');
    const endEl = document.getElementById('end_date');
    const carId = window.ORDER_CAR_ID;

    const updateCost = () => {
        if (!preview || !startEl || !endEl || !carId) return;
        const start = startEl.value;
        const end = endEl.value;
        if (!start || !end) {
            preview.innerHTML = '<p>Select dates to see estimated total.</p>';
            return;
        }
        const qs = new URLSearchParams({ car_id: carId, start_date: start, end_date: end });
        fetch(routeUrl('/api/order/calculate') + '&' + qs)
            .then((r) => r.json())
            .then((data) => {
                if (!data.success) {
                    preview.innerHTML = `<p class="field-error">${data.message || 'Invalid dates.'}</p>`;
                    return;
                }
                preview.innerHTML = `
                    <p><strong>${data.days}</strong> day(s) × ৳${Number(data.price_per_day).toLocaleString()}</p>
                    <p class="price-tag">Estimated total: ৳${Number(data.total_cost).toLocaleString(undefined, { minimumFractionDigits: 2 })}</p>
                `;
            })
            .catch(() => {});
    };

    if (startEl) startEl.addEventListener('change', updateCost);
    if (endEl) endEl.addEventListener('change', updateCost);

    document.querySelectorAll('[data-ajax-cancel]').forEach((btn) => {
        btn.addEventListener('click', (e) => {
            e.preventDefault();
            const id = btn.getAttribute('data-ajax-cancel');
            if (!confirm('Cancel this order?')) return;
            fetch(routeUrl('/api/order/' + id + '/cancel'), { method: 'POST' })
                .then((r) => r.json())
                .then((data) => {
                    if (data.success) window.location.href = routeUrl('/');
                    else alert(data.message || 'Could not cancel.');
                });
        });
    });
})();
