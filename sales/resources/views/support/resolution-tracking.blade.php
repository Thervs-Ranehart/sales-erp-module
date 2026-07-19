@extends('layouts.app')

@section('content')
    @php($title = 'Resolution Tracking')
    @php($subtitle = 'Track resolutions and corrective actions')

    @include('components.page-header', ['title' => $title, 'subtitle' => $subtitle])

        <div class="card p-4">
        <form method="GET">



        <div class="d-flex flex-column flex-lg-row align-items-lg-center justify-content-between gap-3 mb-3">

            <div>
                <h5 class="fw-bold mb-1">Resolutions & Corrective Actions</h5>
                <div class="text-muted small">Monitor case closure progress, ownership, and quality gates.</div>
            </div>

            <div class="d-flex gap-2 flex-wrap">
                <div class="input-group input-group-sm" style="min-width: 260px;">

                    <span class="input-group-text" style="background: rgba(83,71,206,.08); border-color: rgba(83,71,206,.2);">
                        <i class="bi bi-search"></i>
                    </span>
                    <input type="text" name="search" class="form-control" placeholder="Search resolution (e.g., RS-6001)" aria-label="Search resolutions" value="{{ $search ?? '' }}" />
                </div>

                <select class="form-select form-select-sm" style="min-width: 190px;" aria-label="Filter by resolution status" name="status">
                    <option value="all" {{ ($status ?? '') === '' || ($status ?? '') === 'all' ? 'selected' : '' }}>Status: All</option>
                    <option value="Resolved" {{ ($status ?? '') === 'Resolved' ? 'selected' : '' }}>Resolved</option>
                    <option value="Pending QC" {{ ($status ?? '') === 'Pending QC' ? 'selected' : '' }}>Pending QC</option>
                    <option value="Reopened" {{ ($status ?? '') === 'Reopened' ? 'selected' : '' }}>Reopened</option>
                    <option value="In Review" {{ ($status ?? '') === 'In Review' ? 'selected' : '' }}>In Review</option>
                </select>

                <button class="btn btn-sm" type="submit" style="background:#5347CE;color:#fff;border:1px solid rgba(255,255,255,.25);">
                    <i class="bi bi-lightning-charge me-1"></i> Apply Filters
                </button>
            </div>
        </div>

            <div class="row g-3 mb-4">
            <div class="col-md-3">
                <div class="p-3 rounded-3" style="background: rgba(83,71,206,.08);">
                    <div class="text-muted small">Resolved This Month</div>
                    <div class="fw-bold fs-5">{{ $resolvedThisMonthCount ?? 0 }}</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="p-3 rounded-3" style="background: rgba(22,200,199,.10);">
                    <div class="text-muted small">QC Passed</div>
                    <div class="fw-bold fs-5">{{ $qcPassedPct ?? 0 }}%</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="p-3 rounded-3" style="background: rgba(245,158,11,.10);">
                    <div class="text-muted small">Pending QC</div>
                    <div class="fw-bold fs-5">{{ $pendingQcCount ?? 0 }}</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="p-3 rounded-3" style="background: rgba(239,68,68,.08);">
                    <div class="text-muted small">Reopened Cases</div>
                    <div class="fw-bold fs-5">{{ $reopenedCasesCount ?? 0 }}</div>
                </div>
            </div>
        </div>


    @include('support.resolution-details-modal')

        {{-- Resolution Table --}}


        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" style="min-width: 750px;">
                <thead>
                    <tr>
                        <th style="min-width: 160px;">Ticket</th>
                        <th style="min-width: 170px;">Resolved By</th>
                        <th style="min-width: 260px;">Root Cause</th>
                        <th style="min-width: 260px;">Corrective Action</th>
                        <th style="min-width: 180px;">Resolution Time</th>
                        <th style="min-width: 180px;">Resolved Date</th>
                        <th style="min-width: 200px;" class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($resolutionTrackings as $resolution)
                        <tr>
                            <td>
                                <a class="text-decoration-none" style="color:#5347CE; font-weight:700;" href="{{ route('support.tickets') }}">TK-{{ $resolution->supportTicket->ticket_id ?? '—' }}</a>
                                <div class="text-muted small">RS-{{ $resolution->resolution_id }}</div>
                            </td>
                            <td>{{ $resolution->employee->getFullNameAttribute() ?? '—' }}</td>
                            <td>{{ $resolution->root_cause ?? '—' }}</td>
                            <td>{{ $resolution->corrective_action ?? '—' }}</td>
                            <td>
                                <span class="badge bg-secondary">{{ $resolution->resolution_time_hours !== null ? $resolution->resolution_time_hours . 'h' : '—' }}</span>
                            </td>
                            <td>{{ $resolution->resolved_at ? $resolution->resolved_at->format('Y-m-d') : '—' }}</td>
                            <td class="text-end" style="min-width: 260px; white-space: nowrap;">
                                <div class="d-flex align-items-center justify-content-end flex-nowrap gap-2">
                                    <button type="button"
                                            class="btn btn-sm btn-outline-primary js-resolution-view"
                                            data-resolution-id="{{ $resolution->resolution_id }}"
                                            data-bs-toggle="modal"
                                            data-bs-target="#resolutionDetailsModal"
                                            aria-label="View">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">No resolution records found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>


        <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-2 mt-4">
            <div class="text-muted small">Showing results.</div>

            <nav aria-label="Resolution pagination">
                {{ $resolutionTrackings->links() }}
            </nav>
        </div>
    </div>

    <script>
    (function(){
      const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
      const modalEl = document.getElementById('resolutionDetailsModal');
      if(!modalEl) return;

      const mapText = (id, value) => {
        const el = document.getElementById(id);
        if(el) el.textContent = (value !== undefined && value !== null && value !== '' ? value : '—');
      };

      const mapBadge = (badgeId, text, desiredClass) => {
        const badge = document.getElementById(badgeId);
        if(!badge) return;
        badge.textContent = (text !== undefined && text !== null && text !== '' ? text : '—');
        if(desiredClass) badge.className = desiredClass;
      };

      // Determine badge classes based on workflow/resolution outcomes
      const getOutcomeBadgeClass = (outcome) => {
        const o = (outcome ?? '').toString().toLowerCase();
        if(o === 'closed') return 'badge bg-success';
        if(o === 'pending qc') return 'badge bg-warning text-dark';
        if(o === 'in review') return 'badge bg-primary';
        if(o === 'reopened') return 'badge bg-danger';
        return 'badge bg-secondary';
      };

      const getWorkflowBadgeClass = (workflowOutcome) => {
        const o = (workflowOutcome ?? '').toString().toLowerCase();
        if(o === 'closed') return 'badge bg-success';
        if(o === 'in review') return 'badge bg-primary';
        if(o === 'pending qc') return 'badge bg-warning text-dark';
        return 'badge bg-secondary';
      };

      document.querySelectorAll('.js-resolution-view').forEach(btn => {
        btn.addEventListener('click', async () => {
          const resolutionId = btn.getAttribute('data-resolution-id');
          if(!resolutionId) return;

          // Set loading state
          mapText('resolutionRootCauseText', '—');
          mapText('resolutionRootCauseNarrativeText', '—');
          mapText('resolutionCorrectiveActionText', '—');
          mapText('resolutionResolvedByText', '—');
          mapText('resolutionTimeHoursText', '—');
          mapText('resolutionResolvedDateText', '—');
          mapText('resolutionTicketNumberText', '—');
          mapText('resolutionQualityNotesText', '—');
          mapText('resolutionEvidenceSummaryText', '—');
          mapText('resolutionEvidenceCountBadge', '—');
          mapText('resolutionWorkflowStatusText', '—');
          mapText('resolutionWorkflowOutcomeBadge', '—');

          const outcomeBadge = document.getElementById('resolutionOutcomeBadge');
          if(outcomeBadge) outcomeBadge.className = 'badge bg-secondary';
          const workflowOutcomeBadge = document.getElementById('resolutionWorkflowOutcomeBadge');
          if(workflowOutcomeBadge) workflowOutcomeBadge.className = 'badge bg-success mt-2';

          try {
            const res = await fetch(`/support/resolution-tracking/${resolutionId}/show`, {
              headers: {
                'X-Requested-With': 'XMLHttpRequest',
                ...(csrf ? {'X-CSRF-TOKEN': csrf} : {})
              }
            });
            if(!res.ok) throw new Error('Failed to load resolution details');
            const data = await res.json();

            const m = data?.modal ?? {};

            mapText('resolutionRootCauseText', m.resolutionRootCauseText);
            mapText('resolutionRootCauseNarrativeText', m.resolutionRootCauseNarrativeText);
            mapText('resolutionCorrectiveActionText', m.resolutionCorrectiveActionText);
            mapText('resolutionResolvedByText', m.resolutionResolvedByText);
            mapText('resolutionTimeHoursText', m.resolutionTimeHoursText);
            mapText('resolutionResolvedDateText', m.resolutionResolvedDateText);
            mapText('resolutionTicketNumberText', m.resolutionTicketNumberText);
            mapText('resolutionQualityNotesText', m.resolutionQualityNotesText);
            mapText('resolutionEvidenceSummaryText', m.resolutionEvidenceSummaryText);

            const evidenceCountBadge = document.getElementById('resolutionEvidenceCountBadge');
            if(evidenceCountBadge) {
              evidenceCountBadge.textContent = (m.resolutionEvidenceCountBadge ?? '—');
            }

            mapText('resolutionWorkflowStatusText', m.resolutionWorkflowStatusText);

            // outcome badges
            if(outcomeBadge) {
              const cls = getOutcomeBadgeClass(m.resolutionOutcomeBadge);
              outcomeBadge.className = cls;
              outcomeBadge.textContent = m.resolutionOutcomeBadge ?? '—';
            }

            if(workflowOutcomeBadge) {
              const cls = getWorkflowBadgeClass(m.resolutionWorkflowOutcomeBadge);
              // keep existing spacing class if present
              workflowOutcomeBadge.className = cls + ' mt-2';
              workflowOutcomeBadge.textContent = m.resolutionWorkflowOutcomeBadge ?? '—';
            }

          } catch (err) {
            console.error(err);
          }
        });
      });
    })();
    </script>
@endsection






