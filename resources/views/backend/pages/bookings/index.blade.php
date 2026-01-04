@extends('backend.layouts.main')
@section('main-section')

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

<section class="dashboard container-fluid py-4">
    
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
        <div>
            <h3 class="text-warning fw-bold mb-0">Booking Management</h3>
            <p class="text-white-50 small mb-0">Manage fleet bookings and payments</p>
        </div>
        <div class="d-flex gap-2">
            <input type="text" id="searchInput" class="form-control bg-dark text-white border-secondary" placeholder="Search client/car...">
            <button class="btn btn-warning text-dark fw-bold px-4 shadow-sm" data-bs-toggle="modal" data-bs-target="#addBookingModal">
                <i class="fas fa-plus-circle me-1"></i> Add Booking
            </button>
        </div>
    </div>

    <div class="col-12">
        <div class="card bg-dark border-0 shadow-lg" style="border-radius: 15px;">
            <div class="card-body p-0">
                <div class="table-responsive p-3">
                    <table id="bookingTable" class="table table-dark table-hover align-middle mb-0 text-center">
                        <thead class="bg-black text-warning small text-uppercase">
                            <tr>
                                <th class="text-white">#</th>
                                <th class="text-white">Client</th>
                                <th class="text-white">Car</th>
                                <th class="text-white">Schedule</th>
                                <th class="text-white">Payment</th>
                                <th class="text-white">Action</th>
                            </tr>
                        </thead>
                        <tbody id="bookingTableBody">
                            @foreach($bookings as $key => $b)
                            <tr class="border-bottom border-secondary">
                                <td class="text-white-50">{{ $key + 1 }}</td>
                                <td class="fw-bold text-start text-white">{{ $b->client_name }}</td>
                                <td class="text-info">{{ $b->brand }}</td>
                                <td class="text-white">
                                    {{ $b->booking_date }} <br> 
                                    <small class="text-warning">{{ $b->booking_time }}</small>
                                </td>
                                <td>
                                    <span class="badge rounded-pill {{ $b->payment_status == 'Paid' ? 'bg-success' : 'bg-warning text-dark' }}">
                                        {{ $b->payment_status }}
                                    </span>
                                </td>
                                <td>
                                    <div class="d-flex justify-content-center gap-2">
                                        <button class="btn btn-sm btn-outline-warning" data-bs-toggle="modal" data-bs-target="#editModal{{ $b->id }}">
                                            <i class="fas fa-edit text-warning"></i>
                                        </button>
                                        <button class="btn btn-sm btn-outline-danger" onclick="deleteBooking({{ $b->id }})">
                                            <i class="fas fa-trash text-danger"></i>
                                        </button>
                                    </div>

                                    <div class="modal fade" id="editModal{{ $b->id }}" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered modal-lg">
                                            <div class="modal-content bg-dark text-white border border-warning">
                                                <div class="modal-header border-warning bg-black">
                                                    <h5 class="modal-title text-warning fw-bold">EDIT BOOKING #{{ $b->id }}</h5>
                                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                                </div>
                                                <form id="editForm{{ $b->id }}" onsubmit="updateBooking(event, {{ $b->id }})">
                                                    @csrf
                                                    <input type="hidden" name="booking_id" value="{{ $b->id }}">
                                                    <div class="modal-body p-4 text-start">
                                                        <div class="row g-3">
                                                            <div class="col-md-6">
                                                                <label class="small text-warning fw-bold mb-1">CLIENT NAME</label>
                                                                <input type="text" name="client" value="{{ $b->client_name }}" class="form-control bg-black text-white border-secondary shadow-none">
                                                            </div>
                                                            <div class="col-md-6">
                                                                <label class="small text-warning fw-bold mb-1">CAR</label>
                                                                <select name="car" class="form-select bg-black text-white border-secondary shadow-none">
                                                                    @foreach($cars as $car)
                                                                        <option value="{{ $car->id }}" {{ $b->car_id == $car->id ? 'selected' : '' }}>{{ $car->brand }} ({{ $car->model }})</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <label class="small text-warning fw-bold mb-1">DATE</label>
                                                                <input type="date" name="date" value="{{ $b->booking_date }}" class="form-control bg-black text-white border-secondary">
                                                            </div>
                                                            <div class="col-md-6">
                                                                <label class="small text-warning fw-bold mb-1">TIME</label>
                                                                <input type="time" name="time" value="{{ $b->booking_time }}" class="form-control bg-black text-white border-secondary">
                                                            </div>
                                                            <div class="col-md-12">
                                                                <label class="small text-warning fw-bold mb-1">VENUE</label>
                                                                <input type="text" name="venue" value="{{ $b->venue }}" class="form-control bg-black text-white border-secondary">
                                                            </div>
                                                            <div class="col-md-6">
                                                                <label class="small text-warning fw-bold mb-1">PAYMENT STATUS</label>
                                                                <select name="payment_status" class="form-select bg-black text-white border-secondary">
                                                                    <option value="Pending" {{ $b->payment_status == 'Pending' ? 'selected' : '' }}>Pending</option>
                                                                    <option value="Advance Paid" {{ $b->payment_status == 'Advance Paid' ? 'selected' : '' }}>Advance Paid</option>
                                                                    <option value="Paid" {{ $b->payment_status == 'Paid' ? 'selected' : '' }}>Paid</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer border-secondary">
                                                        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                                                        <button type="submit" id="updBtn{{ $b->id }}" class="btn btn-warning text-dark fw-bold px-4">UPDATE RECORD</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>

<div class="modal fade" id="addBookingModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content bg-dark text-white border border-warning">
            <div class="modal-header border-warning bg-black">
                <h5 class="modal-title text-warning fw-bold">ADD NEW BOOKING</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="addBookingForm" action="{{ url('add_booking') }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="small text-warning fw-bold">CLIENT NAME</label>
                            <input type="text" name="client" class="form-control bg-black text-white border-secondary shadow-none" placeholder="Enter client name" required>
                        </div>
                        <div class="col-md-6">
                            <label class="small text-warning fw-bold">SELECT CAR</label>
                            <select name="car" class="form-select bg-black text-white border-secondary shadow-none" required>
                                <option value="" disabled selected>Select Vehicle</option>
                                @foreach($cars as $car)
                                    <option value="{{ $car->id }}">{{ $car->brand }} ({{ $car->model }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6"><label class="small text-warning fw-bold">DATE</label><input type="date" name="date" class="form-control bg-black text-white border-secondary" required></div>
                        <div class="col-md-6"><label class="small text-warning fw-bold">TIME</label><input type="time" name="time" class="form-control bg-black text-white border-secondary" required></div>
                        <div class="col-md-12"><label class="small text-warning fw-bold">VENUE</label><input type="text" name="venue" class="form-control bg-black text-white border-secondary" required></div>
                        <div class="col-md-6">
                            <label class="small text-warning fw-bold">PAYMENT</label>
                            <select name="payment_status" class="form-select bg-black text-white border-secondary">
                                <option value="Pending">Pending</option>
                                <option value="Advance Paid">Advance Paid</option>
                                <option value="Paid">Paid</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="small text-warning fw-bold">EVENT TYPE</label>
                            <select name="event_type" class="form-select bg-black text-white border-secondary">
                                <option>Wedding</option>
                                <option>Engagement</option>
                                <option>Reception</option>
                                <option>Pre-Wedding</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-secondary">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" id="saveBtn" class="btn btn-warning text-dark fw-bold px-4">SAVE BOOKING</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    body { background-color: #0b0b0b !important; color: #f8f9fa; }
    .card { background-color: #141414; }
    .bg-black { background-color: #000 !important; }
    .form-control, .form-select { border-radius: 8px; padding: 12px; border: 1px solid #333; }
    .form-control:focus, .form-select:focus { background-color: #000; color: #fff; border-color: #ffc107; box-shadow: none; }
    ::placeholder { color: #555 !important; }
    .badge { font-weight: 600; letter-spacing: 0.5px; }
</style>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // AJAX UPDATE
    function updateBooking(e, id) {
        e.preventDefault();
        const btn = document.getElementById('updBtn' + id);
        const originalHTML = btn.innerHTML;
        
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...';

        const formData = new FormData(document.getElementById('editForm' + id));

        fetch("{{ url('update_booking') }}", {
            method: 'POST',
            body: formData,
            headers: { 
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            }
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                Swal.fire({ icon: 'success', title: 'Success', text: data.message, showConfirmButton: false, timer: 1500 })
                .then(() => location.reload());
            } else {
                Swal.fire('Error', data.message, 'error');
                btn.disabled = false;
                btn.innerHTML = originalHTML;
            }
        })
        .catch(err => {
            Swal.fire('Error', 'Server connection failed', 'error');
            btn.disabled = false;
            btn.innerHTML = originalHTML;
        });
    }

    // AJAX SAVE
    document.getElementById('addBookingForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const btn = document.getElementById('saveBtn');
        btn.disabled = true;
        btn.innerHTML = 'Saving...';

        fetch(this.action, {
            method: 'POST',
            body: new FormData(this),
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
        })
        .then(res => res.json())
        .then(data => {
            if(data.success) location.reload();
            else { btn.disabled = false; btn.innerHTML = 'SAVE BOOKING'; }
        });
    });

    // DELETE WITH CONFIRM
    function deleteBooking(id) {
        Swal.fire({
            title: 'Are you sure?',
            text: "This booking will be deleted permanently!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it'
        }).then((result) => {
            if (result.isConfirmed) {
                fetch("{{ url('delete_booking') }}", {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    body: JSON.stringify({ id: id })
                })
                .then(res => res.json())
                .then(data => {
                    if(data.success) location.reload();
                    else Swal.fire('Error', 'Deletion failed', 'error');
                });
            }
        });
    }

    // Live Search Logic
    document.getElementById("searchInput").addEventListener("keyup", function() {
        let value = this.value.toLowerCase();
        let rows = document.querySelectorAll("#bookingTableBody tr");
        rows.forEach(row => {
            row.style.display = (row.innerText.toLowerCase().includes(value)) ? "" : "none";
        });
    });
</script>

@endsection