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

        {{-- Search + Filters (functional) --}}
        <div class="card p-3 mb-4" style="background: rgba(255,255,255,.7); border: 1px solid rgba(0,0,0,.06); box-shadow: none;">
            <form method="GET" action="{{ route('support.customer-satisfaction') }}">
                <div class="d-flex flex-column flex-lg-row align-items-lg-center justify-content-between gap-3">
                    <div class="input-group input-group-sm" style="min-width: 260px;">
                        <span class="input-group-text" style="background: rgba(83,71,206,.08); border-color: rgba(83,71,206,.2);">
                            <i class="bi bi-search"></i>
                        </span>
                        <input
                            type="text"
                            name="search"
                            class="form-control"
                            value="{{ $search ?? '' }}"
                            placeholder="Search feedback (e.g., FB-7001)"
                            aria-label="Search feedback"
                        />
                    </div>

                    <div class="d-flex gap-2 flex-wrap">
                        <select class="form-select form-select-sm" style="min-width: 190px;" aria-label="Filter by rating" name="rating">
                            <option value="all" {{ ($rating ?? 'all') === 'all' ? 'selected' : '' }}>Rating: All</option>
                            <option value="5" {{ ($rating ?? '') === 5 || ($rating ?? '') === '5' ? 'selected' : '' }}>Rating: 5</option>
                            <option value="4" {{ ($rating ?? '') === 4 || ($rating ?? '') === '4' ? 'selected' : '' }}>Rating: 4</option>
                            <option value="3" {{ ($rating ?? '') === 3 || ($rating ?? '') === '3' ? 'selected' : '' }}>Rating: 3</option>
                            <option value="1" {{ ($rating ?? '') === 1 || ($rating ?? '') === '1' ? 'selected' : '' }}>Rating: 1</option>
                            <option value="2" {{ ($rating ?? '') === 2 || ($rating ?? '') === '2' ? 'selected' : '' }}>Rating: 2</option>
                        </select>

                        <button class="btn btn-sm" type="submit" style="background:#5347CE;color:#fff;border:1px solid rgba(255,255,255,.25);">
                            <i class="bi bi-chat-left-dots me-1"></i> Analyze
                        </button>
                    </div>
                </div>
            </form>
        </div>


        {{-- Summary cards --}}
        <div class="row g-3 mb-4">
            <div class="col-md-3">
                <div class="p-3 rounded-3" style="background: rgba(22,200,199,.10);">
                    <div class="text-muted small">Average Rating</div>
                    <div class="fw-bold fs-5">{{ $averageRating ?? 0 }} / 5</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="p-3 rounded-3" style="background: rgba(83,71,206,.08);">
                    <div class="text-muted small">5-Star Ratings</div>
                    <div class="fw-bold fs-5">{{ $fiveStarPct ?? 0 }}%</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="p-3 rounded-3" style="background: rgba(245,158,11,.10);">
                    <div class="text-muted small">Lowest Ratings</div>
                    <div class="fw-bold fs-5">1–2: {{ $lowestRatingsPct ?? 0 }}%</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="p-3 rounded-3" style="background: rgba(239,68,68,.08);">
                    <div class="text-muted small">Responses</div>
                    <div class="fw-bold fs-5">{{ $responsesCount ?? 0 }}</div>
                </div>
            </div>
        </div>



        <div class="row g-4">
            <div class="col-lg-8">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0" style="min-width: 650px;">
                        <thead>
                            <tr>
                                <th style="min-width: 150px;">Feedback #</th>
                                <th>Ticket</th>
                                <th style="min-width: 120px;">Rating</th>
                                <th style="min-width: 250px;">Feedback</th>
                                <th style="min-width: 200px;">Date Submitted</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse($satisfactions as $feedback)
                                <tr>
                                    <td class="fw-semibold">FB-{{ $feedback->feedback_id }}</td>
                                    <td><a class="text-decoration-none" style="color:#5347CE; font-weight:700;" href="{{ route('support.tickets') }}">TK-{{ $feedback->supportTicket->ticket_id ?? '—' }}</a></td>

                                    <td>
                                        @if($feedback->rating !== null)
                                            <span class="text-warning">{{ (int) $feedback->rating }}</span>
                                        @else
                                            <span class="text-muted">—</span>
                                        @endif
                                    </td>
                                    <td>
                                        @php($lvl = $feedback->satisfaction_level ?? null)
                                        @if($lvl)
                                            <span class="badge bg-primary">{{ $lvl }}</span>
                                        @else
                                            <span class="badge bg-secondary">—</span>
                                        @endif
                                    </td>
                                    <td class="text-muted">{{ $feedback->comments ?? '—' }}</td>
                                    <td class="text-end">{{ $feedback->submitted_at ? $feedback->submitted_at->format('Y-m-d') : '—' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-4">No satisfaction feedback found.</td>
                                </tr>
                            @endforelse
                        </tbody>

                    </table>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card p-3" style="box-shadow: none; border: 1px solid rgba(0,0,0,.06); background: rgba(255,255,255,.7);">
                    <h6 class="fw-bold mb-2">Feedback Highlights</h6>

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
Insight summary based on recent feedback.
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-2 mt-4">
            <div class="text-muted small">Showing results.</div>

            <nav aria-label="Feedback pagination">
                {{ $satisfactions->links() }}
            </nav>
        </div>
    </div>
@endsection


