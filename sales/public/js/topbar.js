(function () {
    function init() {
        const form = document.getElementById('global-search-form');
        const input = document.getElementById('global-search-input');
        const results = document.getElementById('global-search-results');
        const empty = document.getElementById('global-search-empty');

        if (!form || !input || !results || form.dataset.initialized) return;
        form.dataset.initialized = 'true';

        const links = Array.from(results.querySelectorAll('[data-search-text]'));

        function filterResults() {
            const query = input.value.trim().toLowerCase();
            let visibleCount = 0;

            links.forEach(function (link) {
                const matches = !query || link.dataset.searchText.includes(query);
                link.hidden = !matches;
                if (matches) visibleCount += 1;
            });

            if (empty) empty.hidden = visibleCount > 0;
            results.hidden = false;
            input.setAttribute('aria-expanded', 'true');
        }

        function closeResults() {
            results.hidden = true;
            input.setAttribute('aria-expanded', 'false');
        }

        input.addEventListener('focus', filterResults);
        input.addEventListener('input', filterResults);

        form.addEventListener('submit', function (event) {
            event.preventDefault();
            const firstVisibleLink = links.find((link) => !link.hidden);
            if (firstVisibleLink) window.location.assign(firstVisibleLink.href);
        });

        document.addEventListener('click', function (event) {
            if (!form.contains(event.target) && !results.contains(event.target)) closeResults();
        });

        document.addEventListener('keydown', function (event) {
            if ((event.ctrlKey || event.metaKey) && event.key.toLowerCase() === 'k') {
                event.preventDefault();
                input.focus();
            }

            if (event.key === 'Escape') {
                closeResults();
                input.blur();
            }
        });

        document.getElementById('topbar-search-help')?.addEventListener('click', function () {
            if (typeof bootstrap !== 'undefined') {
                const toggle = this.closest('.dropdown')?.querySelector('[data-bs-toggle="dropdown"]');
                if (toggle) bootstrap.Dropdown.getOrCreateInstance(toggle).hide();
            }
            input.focus();
        });
    }

    if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', init);
    else init();
})();
