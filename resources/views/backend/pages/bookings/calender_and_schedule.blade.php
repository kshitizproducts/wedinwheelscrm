@extends('backend.layouts.main')
@section('main-section')

<div class="container-fluid py-4">
    <h4 class="fw-bold text-warning mb-4">
        <i class="fas fa-calendar-alt me-2"></i> Calendar & Schedule
    </h4>

    <div class="card bg-dark text-light border border-warning shadow-lg" style="border-radius: 15px;">
        <div class="card-body p-4">
            <p class="text-muted small mb-3"><i class="fas fa-info-circle"></i> Click on yellow events to view details of bookings.</p>
            <div id="calendar" style="min-height: 75vh;"></div>
        </div>
    </div>
</div>

<div class="modal fade" id="bookingModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md">
        <div class="modal-content bg-dark text-light border border-warning">
            <div class="modal-header border-warning bg-black">
                <h5 class="modal-title text-warning fw-bold">Bookings on <span id="modalDate"></span></h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body bg-dark" id="bookingList" style="max-height: 450px; overflow-y: auto;">
                </div>
            <div class="modal-footer border-warning bg-black py-2">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const calendarEl = document.getElementById('calendar');

    // ✅ Controller se PHP data ko JS object mein convert karna
    const bookings = @json($bookingsData);

    // ✅ Events array taiyar karna (Calendar display ke liye)
    const events = Object.keys(bookings).map(date => ({
        title: bookings[date].length + (bookings[date].length > 1 ? ' Bookings' : ' Booking'),
        start: date,
        color: '#ffc107',
        textColor: '#000',
        allDay: true,
        className: 'hover-pointer'
    }));

    const calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        height: 'auto',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,dayGridYear,listYear'
        },
        events: events,

        // Click Logic
        eventClick: function (info) {
            const date = info.event.startStr;
            const list = bookings[date] || [];
            
            // Modal Date Header setup
            const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
            document.getElementById('modalDate').textContent = new Date(date).toLocaleDateString(undefined, options);

            let html = '';
            if (list.length === 0) {
                html = `<div class="text-center p-4 text-muted">No bookings found.</div>`;
            } else {
                list.forEach(b => {
                    // Status Badge colors
                    let badgeClass = b.status === 'Paid' ? 'bg-success' : 'bg-warning text-dark';
                    
                    html += `
                    <div class="booking-card mb-3 p-3 rounded border border-secondary shadow-sm" 
                         onclick="window.location.href='${b.booking_master_url}'">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <h6 class="text-warning fw-bold mb-0"><i class="fas fa-car me-2"></i>${b.car}</h6>
                            <span class="badge ${badgeClass} small">${b.status}</span>
                        </div>
                        <div class="small text-white-50">
                            <p class="mb-1"><i class="fas fa-user-circle me-2"></i>Client: <span class="text-white">${b.client}</span></p>
                            <p class="mb-1"><i class="fas fa-clock me-2"></i>Time: <span class="text-white">${b.time}</span></p>
                            <p class="mb-0 text-truncate"><i class="fas fa-map-marker-alt me-2"></i>${b.venue}</p>
                        </div>
                        <div class="mt-2 text-end">
                            <span class="extra-small text-warning">Click to open Booking Master →</span>
                        </div>
                    </div>`;
                });
            }
            
            document.getElementById('bookingList').innerHTML = html;
            new bootstrap.Modal(document.getElementById('bookingModal')).show();
        }
    });

    calendar.render();
});
</script>

<style>
/* ✅ Professional Dark Theme Customization */
.fc { background: transparent; }
.fc-theme-standard td, .fc-theme-standard th { border-color: #333 !important; }
.fc .fc-toolbar-title { color: #ffc107; font-weight: 800; font-size: 1.5rem; }

.fc-button-primary {
    background: #1a1a1a !important;
    border: 1px solid #ffc107 !important;
    color: #ffc107 !important;
    font-weight: 600 !important;
    text-transform: uppercase;
    font-size: 0.8rem !important;
}
.fc-button-primary:hover, .fc-button-active {
    background: #ffc107 !important;
    color: #000 !important;
}

.fc-daygrid-day { background: #111; color: #fff; }
.fc-day-today { background: rgba(255, 193, 7, 0.05) !important; }
.fc-daygrid-day-number { color: #ffc107; font-weight: bold; padding: 5px !important; }
.fc-col-header-cell { background: #000; padding: 10px 0 !important; }

/* Pointer on events */
.hover-pointer { cursor: pointer !important; }

/* Booking Card inside Modal */
.booking-card {
    background: #222;
    transition: 0.3s;
    cursor: pointer;
}
.booking-card:hover {
    background: #2a2a2a;
    border-color: #ffc107 !important;
    transform: translateY(-2px);
}
.extra-small { font-size: 0.7rem; }
.bg-black { background: #000 !important; }

/* Scrollbar styling */
#bookingList::-webkit-scrollbar { width: 5px; }
#bookingList::-webkit-scrollbar-track { background: #111; }
#bookingList::-webkit-scrollbar-thumb { background: #ffc107; border-radius: 10px; }
</style>

@endsection