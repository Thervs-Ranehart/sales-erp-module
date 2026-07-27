@props(['paginator'])

<style>
    .support-pagination-footer { align-items:center; border-top:1px solid #edf0f4; display:flex; gap:1rem; justify-content:space-between; margin-top:1.5rem; padding:1.2rem 1.35rem; }
    .support-pagination-summary { color:#6b7280; font-size:.8rem; }
    .support-pagination-footer .pagination { gap:.35rem; margin:0; }
    .support-pagination-footer .page-item .page-link { align-items:center; border:1px solid #dfe1ea; border-radius:8px; color:#4b5563; display:inline-flex; font-size:.8rem; height:34px; justify-content:center; margin:0; min-width:34px; padding:.35rem .6rem; transition:background-color .16s ease, border-color .16s ease, color .16s ease; }
    .support-pagination-footer .page-item:not(.active):not(.disabled) .page-link:hover, .support-pagination-footer .page-item:not(.active):not(.disabled) .page-link:focus { background:#f0efff; border-color:#b9b3eb; color:#4338ca; box-shadow:none; }
    .support-pagination-footer .page-item.active .page-link { background:#5347ce; border-color:#5347ce; color:#fff; }
    .support-pagination-footer .page-item.disabled .page-link { background:#f8f9fb; border-color:#edf0f4; color:#b1b6c1; }
    @media (max-width:575.98px) { .support-pagination-footer { align-items:center; flex-direction:column; gap:.85rem; margin-top:1.25rem; padding:1.1rem 1rem; text-align:center; }.support-pagination-footer nav { max-width:100%; overflow-x:auto; }.support-pagination-footer .pagination { justify-content:center; } }
</style>

<div class="support-pagination-footer">
    <div class="support-pagination-summary">Showing {{ $paginator->firstItem() ?? 0 }} to {{ $paginator->lastItem() ?? 0 }} of {{ $paginator->total() }} results</div>
    <nav aria-label="Pagination">
        {{ $paginator->links('pagination::bootstrap-5') }}
    </nav>
</div>
