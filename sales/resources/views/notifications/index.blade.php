@extends('layouts.app')

@section('content')
    @php($title = 'Notifications')
    @php($subtitle = 'Stay updated with system alerts and activities')

    @include('components.page-header', ['title' => $title, 'subtitle' => $subtitle])

    <div class="card p-4">
        <ul class="list-group list-group-flush">
            <li class="list-group-item">New sales order approved</li>
            <li class="list-group-item">5 CRM leads added today</li>
            <li class="list-group-item">Support ticket assigned to team</li>
            <li class="list-group-item">Monthly forecast report generated</li>
        </ul>
    </div>
@endsection
