<div class="modal fade" id="warrantyViewModal" tabindex="-1" aria-labelledby="warrantyViewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content" style="border-radius:16px;">
            <div class="modal-header" style="background:rgba(83,71,206,.08);">
                <div>
                    <h5 class="modal-title fw-bold" id="warrantyViewModalLabel">Warranty Details</h5>
                    <div class="text-muted small" id="warrantyViewModalSubtitle">—</div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-danger d-none mb-3" id="warrantyViewError" role="alert">Unable to load warranty details. Please try again.</div>
                <div class="row g-3">
                    <div class="col-md-6"><div class="text-muted small">Customer</div><div class="fw-semibold" id="warrantyCustomer">—</div></div>
                    <div class="col-md-6"><div class="text-muted small">Customer Email</div><div class="fw-semibold" id="warrantyCustomerEmail">—</div></div>
                    <div class="col-md-6"><div class="text-muted small">Product</div><div class="fw-semibold" id="warrantyProduct">—</div></div>
                    <div class="col-md-6"><div class="text-muted small">Related Order</div><div class="fw-semibold" id="warrantyOrder">—</div></div>
                    <div class="col-md-6"><div class="text-muted small">Warranty Start</div><div class="fw-semibold" id="warrantyStart">—</div></div>
                    <div class="col-md-6"><div class="text-muted small">Warranty End</div><div class="fw-semibold" id="warrantyEnd">—</div></div>
                    <div class="col-md-6"><div class="text-muted small">Warranty Status</div><span class="badge bg-secondary" id="warrantyBadge">—</span></div>
                    <div class="col-md-6"><div class="text-muted small">Purchase Date</div><div class="fw-semibold" id="warrantyPurchaseDate">—</div></div>
                    <div class="col-md-6"><div class="text-muted small">Created</div><div class="fw-semibold" id="warrantyCreatedAt">—</div></div>
                    <div class="col-md-6"><div class="text-muted small">Related Claims</div><div class="fw-semibold" id="warrantyClaimCount">—</div></div>
                </div>
            </div>
            <div class="modal-footer"><button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button></div>
        </div>
    </div>
</div>
