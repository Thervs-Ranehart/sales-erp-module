@props(['priority'])
@php($class = match($priority) {'High' => 'text-bg-danger', 'Medium' => 'text-bg-warning', default => 'text-bg-info'})
<span class="badge rounded-pill {{ $class }}">{{ $priority }}</span>
