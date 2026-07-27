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

    .support-module .after-sales-table-responsive {
        max-width: 100%;
        overflow-x: auto !important;
        overflow-y: hidden !important;
        overscroll-behavior-x: contain;
        touch-action: pan-x pan-y;
        width: 100%;
        -webkit-overflow-scrolling: touch;
    }

    .support-module .after-sales-table-responsive > table {
        margin-bottom: 0;
        width: 100%;
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

    .support-module .support-action-cell {
        min-width: 156px;
        text-align: center;
        vertical-align: middle;
        white-space: nowrap;
    }

    .support-module .support-action-group {
        align-items: center;
        display: inline-flex !important;
        flex-flow: row nowrap !important;
        gap: .4rem !important;
        justify-content: center;
        min-height: 36px !important;
        white-space: nowrap;
    }

    .support-module .support-action-group form {
        display: inline-flex !important;
        flex: 0 0 auto;
        margin: 0 !important;
        width: auto !important;
    }

    .support-module .support-action-button {
        align-items: center;
        border-radius: 8px !important;
        display: inline-flex !important;
        flex: 0 0 36px !important;
        font-size: 1rem !important;
        height: 36px !important;
        justify-content: center;
        line-height: 1;
        min-height: 36px !important;
        min-width: 36px !important;
        padding: 0 !important;
        transition: background-color .16s ease, border-color .16s ease, box-shadow .16s ease, color .16s ease, transform .16s ease;
        width: 36px !important;
    }

    .support-module .support-action-button i {
        display: block;
        line-height: 1;
        margin: 0 !important;
        pointer-events: none;
    }

    .support-module .support-action-button:hover {
        box-shadow: 0 4px 10px rgba(31, 41, 55, .1);
        transform: translateY(-1px);
    }

    .support-module .support-action-button:focus-visible {
        box-shadow: 0 0 0 .2rem rgba(83, 71, 206, .2);
        outline: 0;
    }

    .support-module .support-action-schedule {
        background: transparent !important;
        border-color: #6f5bd3 !important;
        color: #5b46c5 !important;
    }

    .support-module .support-action-schedule:hover,
    .support-module .support-action-schedule:focus {
        background: #6f5bd3 !important;
        border-color: #6f5bd3 !important;
        color: #fff !important;
    }

    .support-module .support-action-destructive {
        margin-left: .2rem;
    }

    @media (max-width: 575.98px) {
        .support-module .support-action-cell {
            min-width: 148px;
        }

        .support-module .support-action-group {
            gap: .3rem;
        }
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
