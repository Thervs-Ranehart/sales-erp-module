@props(['title' => 'Title', 'value' => '0', 'description' => null, 'icon' => 'graph-up', 'tone' => 'primary'])

<div class="card p-4 h-100">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h6 class="text-muted mb-2">{{ $title }}</h6>
            <h2 class="fw-bold mb-0">{{ $value }}</h2>
        </div>
        <div class="rounded-circle p-3 bg-{{ $tone }} bg-opacity-10 text-{{ $tone }}">
            <i class="bi bi-{{ $icon }} fs-4"></i>
        </div>
    </div>

    @if ($description)
        <p class="mt-3 mb-0 text-muted">{{ $description }}</p>
    @endif
</div>
