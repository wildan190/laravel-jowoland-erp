@extends('layouts.app')

@section('title', 'Timeline Proyek')

@section('content')
<div class="container">
    {{-- Breadcrumb --}}
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('projects.index') }}">Daftar Proyek</a></li>
            <li class="breadcrumb-item active" aria-current="page">Timeline: {{ $project->name }}</li>
        </ol>
    </nav>

    {{-- Calendar --}}
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <div id="calendar"></div>
        </div>
    </div>
</div>

{{-- FullCalendar --}}
<link href="https://cdn.jsdelivr.net/npm/[email protected]/index.global.min.css" rel="stylesheet"/>
<script src="https://cdn.jsdelivr.net/npm/[email protected]/index.global.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    let calendarEl = document.getElementById('calendar');
    let calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        headerToolbar: { left: 'prev,next today', center: 'title', right: 'dayGridMonth,timeGridWeek' },
        events: @json($events)
    });
    calendar.render();
});
</script>
@endsection
