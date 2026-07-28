<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('[data-crm-auto-filter]').forEach(function (form) {
        var delay;
        var request;

        function updateResults() {
            clearTimeout(delay);
            var url = new URL(form.action, window.location.origin);
            var values = new FormData(form);

            values.forEach(function (value, key) {
                if (value !== '') {
                    url.searchParams.set(key, value);
                }
            });

            if (request) {
                request.abort();
            }

            request = new AbortController();
            form.setAttribute('aria-busy', 'true');

            fetch(url.toString(), {
                headers: { 'X-Requested-With': 'XMLHttpRequest' },
                signal: request.signal,
            })
                .then(function (response) {
                    if (!response.ok) {
                        throw new Error('Unable to update CRM results.');
                    }

                    return response.text();
                })
                .then(function (html) {
                    var page = new DOMParser().parseFromString(html, 'text/html');
                    var nextResults = page.querySelectorAll('[data-crm-live-results]');
                    var currentResults = document.querySelectorAll('[data-crm-live-results]');

                    if (!nextResults.length || nextResults.length !== currentResults.length) {
                        return;
                    }

                    currentResults.forEach(function (result, index) {
                        result.replaceWith(nextResults[index]);
                    });
                    window.history.replaceState({}, '', url);
                })
                .catch(function (error) {
                    if (error.name !== 'AbortError') {
                        form.submit();
                    }
                })
                .finally(function () {
                    form.removeAttribute('aria-busy');
                });
        }

        form.querySelectorAll('input[type="text"], input[type="search"]').forEach(function (input) {
            input.addEventListener('input', function () {
                clearTimeout(delay);
                delay = setTimeout(updateResults, 300);
            });
        });

        form.querySelectorAll('select').forEach(function (select) {
            select.addEventListener('change', updateResults);
        });

        form.addEventListener('submit', function (event) {
            event.preventDefault();
            updateResults();
        });
    });
});
</script>
