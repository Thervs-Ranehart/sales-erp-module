(function () {
    const openButton = document.getElementById('ff-open-filter');
    const overlay = document.getElementById('ff-filter-overlay');
    const drawer = document.getElementById('ff-filter-drawer');
    const closeButton = document.getElementById('ff-close-filter');
    if (!openButton || !overlay || !drawer || !closeButton || overlay.dataset.initialized) return;
    overlay.dataset.initialized = 'true';
    let previousFocus = null;

    function close() {
        overlay.classList.remove('open'); overlay.setAttribute('aria-hidden', 'true'); openButton.setAttribute('aria-expanded', 'false');
        if (previousFocus) previousFocus.focus();
    }
    function open() {
        previousFocus = document.activeElement; overlay.classList.add('open'); overlay.setAttribute('aria-hidden', 'false'); openButton.setAttribute('aria-expanded', 'true'); closeButton.focus();
    }
    openButton.addEventListener('click', open);
    closeButton.addEventListener('click', close);
    overlay.addEventListener('click', (event) => { if (event.target === overlay) close(); });
    document.addEventListener('keydown', (event) => {
        if (!overlay.classList.contains('open')) return;
        if (event.key === 'Escape') close();
        if (event.key !== 'Tab') return;

        const focusable = drawer.querySelectorAll('button:not([disabled]), select:not([disabled]), [href], [tabindex]:not([tabindex="-1"])');
        const first = focusable[0];
        const last = focusable[focusable.length - 1];
        if (event.shiftKey && document.activeElement === first) { event.preventDefault(); last.focus(); }
        else if (!event.shiftKey && document.activeElement === last) { event.preventDefault(); first.focus(); }
    });
    document.getElementById('ff-reset')?.addEventListener('click', () => window.location.assign(window.location.pathname));
    document.getElementById('ff-apply')?.addEventListener('click', () => {
        const parameters = new URLSearchParams();
        drawer.querySelectorAll('[name]').forEach((field) => parameters.set(field.name, field.value));
        window.location.assign(`${window.location.pathname}?${parameters.toString()}`);
    });
})();
