@extends('layouts.app')

@section('content')
    @php($title = 'Customer Satisfaction')
    @php($subtitle = 'Monitor satisfaction feedback and ratings')

    @include('components.page-header', ['title' => $title, 'subtitle' => $subtitle])

    <div class="card p-4">
        <div class="d-flex flex-column flex-lg-row align-items-lg-center justify-content-between gap-3 mb-3">
            <div>
                <h5 class="fw-bold mb-1">CSAT & Feedback Center</h5>
                <div class="text-muted small">Review survey results, identify detractors, and track service improvements.</div>
            </div>

            <div class="d-flex gap-2 flex-wrap">
                <div class="input-group input-group-sm" style="min-width: 260px;">
                    <span class="input-group-text" style="background: rgba(83,71,206,.08); border-color: rgba(83,71,206,.2);">
                        <i class="bi bi-search"></i>
                    </span>
                    <input type="text" class="form-control" placeholder="Search feedback (e.g., FB-7001)" aria-label="Search feedback" />
                </div>

                <select class="form-select form-select-sm" style="min-width: 190px;" aria-label="Filter by rating">
                    <option selected>Rating: 5 (Very Satisfied)</option>
                    <option>Rating: 4</option>
                    <option>Rating: 3</option>
                    <option>Rating: 1–2 (Detractors)</option>
                </select>

                <button class="btn btn-sm" style="background:#5347CE;color:#fff;border:1px solid rgba(255,255,255,.25);">
                    <i class="bi bi-chat-left-dots me-1"></i> Analyze
                </button>
            </div>
        </div>

        {{-- Summary cards --}}
        <div class="row g-3 mb-4">
            <div class="col-md-3">
                <div class="p-3 rounded-3" style="background: rgba(22,200,199,.10);">
                    <div class="text-muted small">Average Rating</div>
                    <div class="fw-bold fs-5">4.6 / 5</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="p-3 rounded-3" style="background: rgba(83,71,206,.08);">
                    <div class="text-muted small">5-Star Ratings</div>
                    <div class="fw-bold fs-5">56%</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="p-3 rounded-3" style="background: rgba(245,158,11,.10);">
                    <div class="text-muted small">Lowest Ratings</div>
                    <div class="fw-bold fs-5">1–2: 8%</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="p-3 rounded-3" style="background: rgba(239,68,68,.08);">
                    <div class="text-muted small">Responses</div>
                    <div class="fw-bold fs-5">210</div>
                </div>
            </div>
        </div>


        <div class="row g-4">
            <div class="col-lg-8">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead>
                            <tr>
                                <th style="min-width: 150px;">Feedback #</th>
                                <th>Ticket</th>
                                <th style="min-width: 120px;">Rating</th>
                                <th style="min-width: 200px;">Level</th>
                                <th style="min-width: 250px;">Comment</th>
                                <th class="text-end" style="min-width: 160px;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="fw-semibold">FB-7001</td>
                                <td><a class="text-decoration-none" style="color:#5347CE; font-weight:700;" href="{{ route('support.tickets') }}">TK-1003</a></td>
                                <td>
                                    <span class="text-warning">
                                        <i class="bi bi-star-fill"></i>
                                        <i class="bi bi-star-fill"></i>
                                        <i class="bi bi-star-fill"></i>
                                        <i class="bi bi-star-fill"></i>
                                        <i class="bi bi-star-fill"></i>
                                    </span>
                                </td>
                                <td><span class="badge bg-success">Very Satisfied</span></td>

                                <td class="text-muted">Quick resolution and clear communication from the support team.</td>
                                <td class="text-end"><a class="btn btn-sm btn-outline-success" href="#">View</a></td>
                            </tr>
                            <tr>
                                <td class="fw-semibold">FB-7008</td>
                                <td><a class="text-decoration-none" style="color:#5347CE; font-weight:700;" href="{{ route('support.tickets') }}">TK-1002</a></td>
                                <td>
                                    <span class="text-warning">
                                        <i class="bi bi-star-fill"></i>
                                        <i class="bi bi-star-fill"></i>
                                        <i class="bi bi-star-fill"></i>
                                        <i class="bi bi-star-fill"></i>
                                        <i class="bi bi-star"></i>
                                    </span>
                                </td>
                                <td><span class="badge bg-primary">Satisfied</span></td>

                                <td class="text-muted">Service was good; would like more frequent updates on scheduling.</td>
                                <td class="text-end"><a class="btn btn-sm btn-outline-primary" href="#">Follow up</a></td>
                            </tr>
                            <tr>
                                <td class="fw-semibold">FB-7016</td>
                                <td><a class="text-decoration-none" style="color:#5347CE; font-weight:700;" href="{{ route('support.tickets') }}">TK-1005</a></td>
                                <td><span class="badge bg-warning text-dark">3</span></td>
                                <td><span class="badge bg-warning text-dark">Neutral</span></td>
                                <td class="text-muted">Expected faster parts delivery, but the final outcome was okay.</td>
                                <td class="text-end"><a class="btn btn-sm btn-outline-warning" href="#">Improve</a></td>
                            </tr>
                            <tr>
                                <td class="fw-semibold">FB-7022</td>
                                <td><a class="text-decoration-none" style="color:#5347CE; font-weight:700;" href="{{ route('support.tickets') }}">TK-1020</a></td>
                                <td><span class="badge bg-danger">2</span></td>
                                <td><span class="badge bg-danger">Dissatisfied</span></td>
                                <td class="text-muted">Repeated issues affected operations; escalation handling needs work.</td>
                                <td class="text-end"><a class="btn btn-sm btn-outline-danger" href="{{ route('support.resolution-tracking') }}">Escalate</a></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card p-3" style="box-shadow: none; border: 1px solid rgba(0,0,0,.06); background: rgba(255,255,255,.7);">
                    <h6 class="fw-bold mb-2">Feedback Highlights (Placeholder)</h6>
                    <div class="bg-light rounded-3 p-3">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <div class="text-muted small">Top Theme</div>
                            <span class="badge bg-primary">Communication</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <div class="text-muted small">Main Driver</div>
                            <span class="badge bg-success">First-time Fix</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <div class="text-muted small">Improvement Focus</div>
                            <span class="badge bg-warning text-dark">SLA Updates</span>
                        </div>

                        <div class="mt-3">
                            <div class="text-muted small mb-1">Recent CSAT Distribution</div>
                            <div class="row g-2">
                                <div class="col-6"><div class="bg-success rounded-2" style="height: 36px; opacity:.35"></div></div>
                                <div class="col-6"><div class="bg-primary rounded-2" style="height: 36px; opacity:.35"></div></div>
                                <div class="col-6"><div class="bg-warning rounded-2" style="height: 36px; opacity:.35"></div></div>
                                <div class="col-6"><div class="bg-danger rounded-2" style="height: 36px; opacity:.35"></div></div>
                            </div>
                            <div class="text-muted small mt-2">Replace with real analytics after backend integration.</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-2 mt-4">
            <div class="text-muted small">Showing 4 of 210 feedback entries (placeholder).</div>
            <nav aria-label="Feedback pagination">
                <ul class="pagination pagination-sm mb-0">
                    <li class="page-item disabled"><span class="page-link">Previous</span></li>
                    <li class="page-item active"><span class="page-link">1</span></li>
                    <li class="page-item"><a class="page-link" href="#">2</a></li>
                    <li class="page-item"><a class="page-link" href="#">3</a></li>
                    <li class="page-item"><a class="page-link" href="#">Next</a></li>
                </ul>
            </nav>
        </div>
    </div>
@endsection


