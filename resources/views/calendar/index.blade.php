@extends('layouts.app')

@section('title', 'Calendar')

@section('content')

{{-- Header --}}
<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-2xl font-bold" :class="dark ? 'text-white' : 'text-gray-900'">Calendar</h1>
        <p class="text-gray-400 text-sm mt-0.5">Upcoming events, jobs & deadlines</p>
    </div>
    <div class="flex items-center gap-3 text-xs">
        <div class="flex items-center gap-1.5">
            <div class="w-3 h-3 rounded-full bg-blue-600"></div>
            <span class="text-gray-400">Job Event</span>
        </div>
        <div class="flex items-center gap-1.5">
            <div class="w-3 h-3 rounded-full bg-red-600"></div>
            <span class="text-gray-400">Job Delivery</span>
        </div>
        <div class="flex items-center gap-1.5">
            <div class="w-3 h-3 rounded-full" style="background:#9333ea"></div>
            <span class="text-gray-400">Deliverable</span>
        </div>
        <div class="flex items-center gap-1.5">
            <div class="w-3 h-3 rounded-full bg-yellow-500"></div>
            <span class="text-gray-400">Quotation Expiry</span>
        </div>
    </div>
</div>

{{-- Calendar Container --}}
<div class="rounded-xl p-5" :style="dark ? 'background:#1a1d2e;border:1px solid #252840' : 'background:#fff;border:1px solid #e5e7eb'">
    <div id="calendar"></div>
</div>

{{-- Event Detail Modal --}}
<div id="eventModal" class="hidden fixed inset-0 bg-black/60 backdrop-blur-sm z-50 flex items-center justify-center">
    <div class="rounded-xl w-full max-w-sm mx-4 p-5" style="background:#1a1d2e;border:1px solid #252840">
        <div class="flex items-center justify-between mb-4">
            <h3 id="modalTitle" class="font-semibold text-white"></h3>
            <button onclick="closeModal()" class="text-gray-400 hover:text-white">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        <div class="space-y-2 mb-4">
            <div class="flex justify-between text-sm">
                <span class="text-gray-400">Type</span>
                <span id="modalType" class="text-white"></span>
            </div>
            <div class="flex justify-between text-sm">
                <span class="text-gray-400">Client</span>
                <span id="modalClient" class="text-white"></span>
            </div>
            <div class="flex justify-between text-sm">
                <span class="text-gray-400">Status</span>
                <span id="modalStatus" class="text-white"></span>
            </div>
            <div class="flex justify-between text-sm">
                <span class="text-gray-400">Date</span>
                <span id="modalDate" class="text-white"></span>
            </div>
        </div>
        <a id="modalUrl" href="#"
           class="block text-center bg-primary hover:bg-primary-hover text-white text-sm font-medium py-2 rounded-lg transition-colors">
            View Details →
        </a>
    </div>
</div>

{{-- FullCalendar CDN --}}
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.css" rel="stylesheet"/>
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js"></script>

<style>
    .fc {
        font-family: 'Inter', sans-serif;
        font-size: 13px;
    }
    .fc-toolbar-title {
        font-size: 1rem !important;
        font-weight: 600 !important;
        color: #e5e7eb !important;
    }
    .fc-button {
        background: #7C3AED !important;
        border-color: #7C3AED !important;
        font-size: 12px !important;
        padding: 4px 10px !important;
        border-radius: 6px !important;
        color: #fff !important;
    }
    .fc-button:hover {
        background: #6D28D9 !important;
        border-color: #6D28D9 !important;
    }
    .fc-button-active {
        background: #6D28D9 !important;
    }
    .fc-daygrid-day-number,
    .fc-col-header-cell-cushion {
        color: #9ca3af !important;
        text-decoration: none !important;
    }
    .fc-daygrid-day.fc-day-today {
        background: rgba(124, 58, 237, 0.15) !important;
    }
    .fc-event {
        cursor: pointer !important;
        border: none !important;
        border-radius: 4px !important;
        padding: 1px 4px !important;
        font-size: 11px !important;
    }
    .fc-event-title {
        font-weight: 500 !important;
        color: #fff !important;
    }
    .fc-theme-standard td,
    .fc-theme-standard th,
    .fc-theme-standard .fc-scrollgrid {
        border-color: #252840 !important;
    }
    .fc-day-other .fc-daygrid-day-number {
        color: #4b5563 !important;
    }
    .fc-toolbar {
        margin-bottom: 16px !important;
    }
    /* List view styles */
    .fc-list-day-cushion {
        background: #252840 !important;
    }
    .fc-list-day-text,
    .fc-list-day-side-text {
        color: #9ca3af !important;
        text-decoration: none !important;
    }
    .fc-list-event {
        background: #1a1d2e !important;
    }
    .fc-list-event:hover td {
        background: #252840 !important;
    }
    .fc-list-event-title a {
        color: #e5e7eb !important;
        text-decoration: none !important;
    }
    .fc-list-event-time {
        color: #9ca3af !important;
    }
    .fc-list-empty {
        background: #1a1d2e !important;
        color: #6b7280 !important;
    }
    .fc-list-table td {
        border-color: #252840 !important;
    }
    .fc-list-table th {
        border-color: #252840 !important;
    }
    .fc-sticky {
        background: #252840 !important;
    }
    /* Week view */
    .fc-timegrid-slot-label-cushion {
        color: #9ca3af !important;
    }
    .fc-timegrid-now-indicator-line {
        border-color: #7C3AED !important;
    }
    /* Scrollbar */
    .fc-scroller::-webkit-scrollbar {
        width: 4px;
    }
    .fc-scroller::-webkit-scrollbar-track {
        background: #1a1d2e;
    }
    .fc-scroller::-webkit-scrollbar-thumb {
        background: #7C3AED;
        border-radius: 2px;
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');

    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,listMonth'
        },
        buttonText: {
            today: 'Today',
            month: 'Month',
            week: 'Week',
            list: 'List'
        },
        events: '{{ route("calendar.events") }}',
        eventClick: function(info) {
            info.jsEvent.preventDefault();
            openModal(info.event);
        },
        eventDidMount: function(info) {
            info.el.title = info.event.title;
        },
        height: 'auto',
        eventDisplay: 'block',
        listDayFormat: { weekday: 'long', month: 'long', day: 'numeric', year: 'numeric' },
        listDaySideFormat: false,
        noEventsText: 'No events for this period',
    });

    calendar.render();
});

function openModal(event) {
    document.getElementById('modalTitle').textContent = event.title;
    document.getElementById('modalType').textContent = event.extendedProps.type || '-';
    document.getElementById('modalClient').textContent = event.extendedProps.client || '-';
    document.getElementById('modalStatus').textContent = event.extendedProps.status || '-';
    document.getElementById('modalDate').textContent = event.startStr;
    document.getElementById('modalUrl').href = event.url || '#';
    document.getElementById('eventModal').classList.remove('hidden');
}

function closeModal() {
    document.getElementById('eventModal').classList.add('hidden');
}

document.getElementById('eventModal').addEventListener('click', function(e) {
    if (e.target === this) closeModal();
});
</script>

@endsection