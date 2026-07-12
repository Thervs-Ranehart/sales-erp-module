@props(['title' => 'Dashboard', 'subtitle' => 'Enterprise dashboard'])

<nav class="topbar px-4 py-3 d-flex justify-content-between align-items-center">
    <div class="flex-grow-1 d-flex justify-content-center">
        <form action="" method="GET" class="w-100" style="max-width: 520px;">
            <div class="input-group">
                <input
                    type="text"
                    name="q"
                    class="form-control"
                    placeholder="Search..."
                    aria-label="Search"
                />
                <button
                    class="btn"
                    type="submit"
                    style="background: #5347CE; color: #fff; border: 1px solid rgba(255,255,255,.25);"
                    aria-label="Search"
                    title="Search"
                >
                    <i class="bi bi-search"></i>
                </button>
            </div>
        </form>
    </div>

    <div class="d-flex align-items-center gap-3">
        <i class="bi bi-bell fs-4" title="See notifications"></i>
        <i class="bi bi-envelope fs-4" title="See messages"></i>
        <i class="bi bi-question-circle fs-4" title="Need help?"></i>
        <i class="bi bi-person-circle fs-3" title="Check your account"></i>
    </div>
</nav>
