<style>
    .support-module .support-primary {
        background: var(--primary) !important;
        color: #fff !important;
        border-color: rgba(255, 255, 255, .25) !important;
    }

    .support-module .support-primary:hover,
    .support-module .support-primary:focus {
        background: #4338CA !important;
        color: #fff !important;
    }

    .support-module .support-filter-card {
        background: rgba(255, 255, 255, .7);
        border: 1px solid rgba(0, 0, 0, .06);
        box-shadow: none;
    }

    .support-module .table-responsive {
        overflow-x: auto;
    }

    .support-module .table th {
        white-space: nowrap;
    }

    .support-module .table td {
        vertical-align: middle;
    }

    .support-module .support-table-actions {
        white-space: nowrap;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        document.querySelectorAll('form[method="GET"]').forEach((form) => {
            form.classList.add('support-filter-form');
            const submit = form.querySelector('button[type="submit"]');
            if (submit) {
                submit.classList.add('support-primary');
            }

            if (submit && !form.querySelector('[data-support-reset]')) {
                const reset = document.createElement('a');
                const resetUrl = new URL(form.action || window.location.href, window.location.origin);
                resetUrl.search = '';
                reset.hash = '';
                reset.href = resetUrl.toString();
                reset.className = 'btn btn-sm btn-outline-secondary ms-2';
                reset.dataset.supportReset = '1';
                reset.textContent = 'Reset';
                submit.insertAdjacentElement('afterend', reset);
            }
        });

        document.querySelectorAll('.modal-footer .btn:not(.btn-outline-secondary)').forEach((button) => {
            button.classList.add('support-primary');
        });

        document.querySelectorAll('button[style*="background:#5347CE"]').forEach((button) => {
            button.classList.add('support-primary');
            button.style.removeProperty('background');
            button.style.removeProperty('color');
            button.style.removeProperty('border');
        });
    });
</script>
