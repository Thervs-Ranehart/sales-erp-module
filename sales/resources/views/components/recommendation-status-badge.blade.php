@props(['status'])
@php($class = match($status) {'Completed' => 'text-bg-success', 'In Progress' => 'text-bg-primary', 'Approved' => 'text-bg-info', 'Rejected' => 'text-bg-danger', 'Dismissed' => 'text-bg-secondary', 'Under Review' => 'text-bg-warning', default => 'text-bg-light border text-dark'})
<span class="badge rounded-pill {{ $class }} text-nowrap">{{ $status }}</span>
