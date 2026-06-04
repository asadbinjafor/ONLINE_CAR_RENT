(function () {
    const toggle = document.querySelector('[data-nav-toggle]');
    const links = document.querySelector('[data-nav-links]');
    if (toggle && links) {
        toggle.addEventListener('click', () => links.classList.toggle('open'));
    }

    document.querySelectorAll('[data-validate]').forEach((form) => {
        form.addEventListener('submit', (e) => {
            const type = form.getAttribute('data-validate');
            let ok = true;

            const showError = (field, msg) => {
                let err = field.parentElement.querySelector('.field-error');
                if (!err) {
                    err = document.createElement('span');
                    err.className = 'field-error';
                    field.parentElement.appendChild(err);
                }
                err.textContent = msg;
                ok = false;
            };

            form.querySelectorAll('.field-error').forEach((el) => { el.textContent = ''; });

            if (type === 'register' || type === 'profile') {
                const pass = form.querySelector('[name="password"], [name="new_password"]');
                const confirm = form.querySelector('[name="password_confirm"], [name="new_password_confirm"]');
                if (pass && pass.value && pass.value.length < 8) {
                    showError(pass, 'Minimum 8 characters.');
                }
                if (pass && confirm && pass.value && confirm.value && pass.value !== confirm.value) {
                    showError(confirm, 'Passwords do not match.');
                }
            }

            if (type === 'order') {
                const start = form.querySelector('#start_date');
                const end = form.querySelector('#end_date');
                const today = new Date().toISOString().slice(0, 10);
                if (start && start.value < today) {
                    showError(start, 'Start date cannot be in the past.');
                }
                if (start && end && end.value < start.value) {
                    showError(end, 'End date must be after start date.');
                }
            }

            if (type === 'car') {
                const price = form.querySelector('#price_per_day');
                if (price && parseFloat(price.value) <= 0) {
                    showError(price, 'Price must be greater than zero.');
                }
            }

            if (type === 'blog') {
                const title = form.querySelector('#blog_title');
                const content = form.querySelector('#blog_content');
                if (title && !title.value.trim()) showError(title, 'Title is required.');
                if (content && !content.value.trim()) showError(content, 'Content is required.');
            }

            if (!ok) e.preventDefault();
        });
    });
})();
