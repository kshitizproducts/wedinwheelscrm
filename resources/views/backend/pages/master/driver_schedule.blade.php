@extends('backend.layouts.main')
@section('main-section')

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

{{-- ✅ IMPORTANT: Bootstrap JS for modal --}}
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/themes/dark.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

<style>
    body { background: #121212; }

    .card {
        border-radius: 15px;
        background: #1e1e1e;
        border: 1px solid #333;
    }

    /* ✅ Driver highlight (NO disable) */
    .driver-assigned {
        background: #284b63 !important;
        color: #ffc107 !important;
        font-weight: 700;
    }

    /* ✅ Client highlight red/black */
    .client-assigned {
        background: #5c0000 !important;
        color: #fff !important;
        font-weight: 700;
    }

    .wrap-cell{
        max-width: 260px;
        white-space: normal !important;
        word-break: break-word;
        line-height: 1.3;
    }

    .location-box{
        background: rgba(255,255,255,0.06);
        border: 1px solid rgba(255,255,255,0.12);
        padding: 8px 10px;
        border-radius: 10px;
        font-size: 13px;
    }

    .dt-badge{
        display: inline-block;
        padding: 6px 10px;
        border-radius: 12px;
        background: rgba(255,193,7,0.12);
        border: 1px solid rgba(255,193,7,0.35);
        color: #ffc107;
        font-weight: 600;
        font-size: 13px;
    }

    .table thead th { white-space: nowrap; }
</style>

<section class="dashboard row g-4">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="text-warning fw-bold">
            <i class="bi bi-person-badge"></i> Driver Management
        </h2>
    </div>

    <!-- Assign Drivers -->
    <div class="col-12">
        <div class="card bg-dark text-white p-3 shadow-lg mb-4">
            <h5 class="mb-3 text-warning">Assign Drivers to Cars & Clients</h5>

            <div class="table-responsive">
                <table class="table table-dark table-striped table-hover align-middle">
                    <thead class="text-warning">
                        <tr>
                            <th>Car</th>
                            <th>Client</th>
                            <th>Driver</th>
                            <th>Date & Time</th>
                            <th>Source Location</th>
                            <th>Destination Location</th>
                            <th>Action</th>
                        </tr>
                    </thead>

                    <tbody>
                        <tr>
                            <td>
                                <form id="assignForm">
                                    @csrf
                                    <select class="form-select bg-secondary text-white border-0" name="car_id" required>
                                        <option selected disabled>Please Select</option>
                                        @foreach ($cars as $car)
                                            <option value="{{ $car->id }}">{{ $car->brand }} - {{ $car->registration_no }}</option>
                                        @endforeach
                                    </select>
                            </td>

                            <td>
                                <select class="form-select bg-secondary text-white border-0" name="client_id" required>
                                    <option value="">-- Select Client --</option>
                                    @foreach ($clients as $client)
                                        @php
                                            $isClientAssigned = in_array($client->id, $assignedClients);
                                        @endphp

                                        <option value="{{ $client->id }}"
                                            class="{{ $isClientAssigned ? 'client-assigned' : '' }}">
                                            {{ $client->client_name }} {{ $isClientAssigned ? '(Assigned)' : '' }}
                                        </option>
                                    @endforeach
                                </select>
                            </td>

                            <td>
                                <select class="form-select bg-secondary text-white border-0" name="driver_id" required>
                                    <option value="">-- Select Driver --</option>

                                    @foreach ($drivers as $driver)
                                        @php
                                            $isDriverAssigned = in_array($driver->id, $assignedDrivers);
                                        @endphp

                                        <option value="{{ $driver->id }}"
                                            class="{{ $isDriverAssigned ? 'driver-assigned' : '' }}">
                                            {{ $driver->name }} {{ $isDriverAssigned ? '(Assigned)' : '' }}
                                        </option>
                                    @endforeach
                                </select>
                            </td>

                            <td>
                                <input type="text" class="form-control bg-secondary text-white border-0"
                                       id="assignDateTimeUI" placeholder="📅 Select date & 🕒 time" required>

                                <input type="hidden" name="date_time" id="assignDateTimeHidden">
                            </td>

                            <td>
                                <textarea name="source_location" class="form-control bg-secondary text-white border-0"
                                    placeholder="Copy Google Map Location for Source Location" required></textarea>
                            </td>
                            <td>
                                <textarea class="form-control bg-secondary text-white border-0"
                                    name="destination_location"
                                    placeholder="Copy Google Map Location for Destination Location" required></textarea>
                            </td>

                            <td>
                                <button type="submit" class="btn btn-sm btn-warning text-dark" id="assignBtn">
                                    Assign
                                </button>
                                </form>
                            </td>

                        </tr>
                    </tbody>
                </table>
            </div>

        </div>
    </div>



    <!-- Assigned Records -->
    <div class="col-12">
        <div class="card bg-dark text-white p-3 shadow-lg">
            <h5 class="mb-3 text-warning">Assigned Driver Records</h5>

            <div class="table-responsive">
                <table class="table table-dark table-striped table-hover align-middle" id="assignedTable">
                    <thead class="text-warning">
                        <tr>
                            <th>#</th>
                            <th>Car</th>
                            <th>Client</th>
                            <th>Driver</th>
                            <th>Date & Time</th>
                            <th>Source Location</th>
                            <th>Destination Location</th>
                            <th>Action</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($driver_schedules as $schedule)
                            @php
                                $carRow = DB::table('cars')->where('id', $schedule->car_id)->first();
                                $clientRow = DB::table('leads')->where('id', $schedule->client_id)->first();
                                $driverRow = DB::table('users')->where('id', $schedule->driver_id)->first();
                            @endphp

                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $carRow->brand ?? '-' }}</td>
                                <td>{{ $clientRow->client_name ?? '-' }}</td>
                                <td>{{ $driverRow->name ?? '-' }}</td>

                                <td><span class="dt-badge">{{ $schedule->booked_date }}</span></td>

                                <td class="wrap-cell">
                                    <div class="location-box">{{ $schedule->source_location }}</div>
                                </td>

                                <td class="wrap-cell">
                                    <div class="location-box">{{ $schedule->destination_location }}</div>
                                </td>

                                <td class="d-flex gap-2">
                                    <button type="button" class="btn btn-sm btn-danger deleteScheduleBtn"
                                        data-id="{{ $schedule->id }}">
                                        Delete
                                    </button>

                                    <button type="button"
                                        class="btn btn-sm btn-info text-dark editBtn"
                                        data-bs-toggle="modal"
                                        data-bs-target="#editScheduleModal"
                                        data-id="{{ $schedule->id }}"
                                        data-car_id="{{ $schedule->car_id }}"
                                        data-client_id="{{ $schedule->client_id }}"
                                        data-driver_id="{{ $schedule->driver_id }}"
                                        data-booked_date="{{ $schedule->booked_date }}"
                                        data-source="{{ $schedule->source_location }}"
                                        data-destination="{{ $schedule->destination_location }}"
                                    >
                                        Edit
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>

                </table>
            </div>
        </div>
    </div>

</section>


<!-- ✅ EDIT MODAL -->
<div class="modal fade" id="editScheduleModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content bg-dark text-white border border-secondary rounded-4 shadow-lg">

            <div class="modal-header border-bottom border-secondary">
                <h5 class="modal-title text-warning fw-bold">
                    <i class="bi bi-pencil-square"></i> Edit Driver Schedule
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <form method="POST" id="editScheduleForm">
                @csrf

                <div class="modal-body">
                    <div class="row g-3">

                        <div class="col-md-6">
                            <label class="form-label text-warning fw-semibold">Car</label>
                            <select class="form-select bg-secondary text-white border-0" name="car_id" id="editCar" required>
                                <option value="" disabled>Select Car</option>
                                @foreach ($cars as $car)
                                    <option value="{{ $car->id }}">
                                        {{ $car->brand }} - {{ $car->registration_no }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label text-warning fw-semibold">Client</label>
                            <select class="form-select bg-secondary text-white border-0" name="client_id" id="editClient" required>
                                <option value="" disabled>Select Client</option>
                                @foreach ($clients as $client)
                                    <option value="{{ $client->id }}">{{ $client->client_name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label text-warning fw-semibold">Driver</label>
                            <select class="form-select bg-secondary text-white border-0" name="driver_id" id="editDriver" required>
                                <option value="" disabled>Select Driver</option>
                                @foreach ($drivers as $driver)
                                    <option value="{{ $driver->id }}">{{ $driver->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label text-warning fw-semibold">Date & Time</label>
                            <input type="text" class="form-control bg-secondary text-white border-0"
                                   id="editDateTimeUI" placeholder="📅 Select date & 🕒 time" required>
                            <input type="hidden" name="booked_date" id="editDateTimeHidden">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label text-warning fw-semibold">Source Location</label>
                            <textarea class="form-control bg-secondary text-white border-0"
                                name="source_location" id="editSource" rows="3" required></textarea>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label text-warning fw-semibold">Destination Location</label>
                            <textarea class="form-control bg-secondary text-white border-0"
                                name="destination_location" id="editDestination" rows="3" required></textarea>
                        </div>

                    </div>
                </div>

                <div class="modal-footer border-top border-secondary">
                    <button type="button" class="btn btn-outline-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-warning text-dark fw-bold" id="updateBtn">Update</button>
                </div>

            </form>

        </div>
    </div>
</div>


<script>
    // ✅ flatpickr assign
    flatpickr("#assignDateTimeUI", {
        enableTime: true,
        dateFormat: "Y-m-d H:i",
        altInput: true,
        altFormat: "d M Y, h:i K",
        time_24hr: false,
        minDate: "today",
        minuteIncrement: 5,
        onChange: function(selectedDates, dateStr) {
            $('#assignDateTimeHidden').val(dateStr + ":00");
        }
    });

    // ✅ edit flatpickr
    const editFp = flatpickr("#editDateTimeUI", {
        enableTime: true,
        dateFormat: "Y-m-d H:i",
        altInput: true,
        altFormat: "d M Y, h:i K",
        time_24hr: false,
        minDate: "today",
        minuteIncrement: 5,
        onChange: function(selectedDates, dateStr) {
            $('#editDateTimeHidden').val(dateStr + ":00");
        }
    });

    // ✅ Edit button fill data
    $(document).on('click', '.editBtn', function(){
        let id = $(this).data('id');

        $('#editScheduleForm').attr('action', "{{ url('update_driver_schedule') }}/" + id);

        $('#editCar').val($(this).data('car_id'));
        $('#editClient').val($(this).data('client_id'));
        $('#editDriver').val($(this).data('driver_id'));

        $('#editSource').val($(this).data('source'));
        $('#editDestination').val($(this).data('destination'));

        let booked_date = $(this).data('booked_date');
        if(booked_date){
            let clean = booked_date.toString().substring(0, 16);
            editFp.setDate(clean, true, "Y-m-d H:i");
            $('#editDateTimeHidden').val(clean + ":00");
        }
    });

    // ✅ Assign AJAX + Swal
    $(document).on('submit', '#assignForm', function(e){
        e.preventDefault();
        let url = "{{ url('assign_task_to_drivers') }}";

        $('#assignBtn').prop('disabled', true).text('Assigning...');

        $.ajax({
            url: url,
            type: "POST",
            data: $(this).serialize(),
            success: function(res){
                $('#assignBtn').prop('disabled', false).text('Assign');

                if(res.success){
                    Swal.fire("Assigned!", res.message, "success").then(()=>location.reload());
                }else{
                    Swal.fire("Failed!", res.message, "error");
                }
            },
            error: function(){
                $('#assignBtn').prop('disabled', false).text('Assign');
                Swal.fire("Error!", "Something went wrong!", "error");
            }
        });
    });

    // ✅ ✅✅ FIXED: UPDATE MODAL AJAX + SweetAlert (SUCCESS + FAIL)
    $(document).on('submit', '#editScheduleForm', function(e){
        e.preventDefault();

        let form = $(this);
        let url = form.attr('action');

        $('#updateBtn').prop('disabled', true).text('Updating...');

        $.ajax({
            url: url,
            type: "POST",
            data: form.serialize(),
            success: function(res){
                $('#updateBtn').prop('disabled', false).text('Update');

                if(res.success){
                    Swal.fire("Updated!", res.message, "success").then(()=>location.reload());
                }else{
                    Swal.fire("Failed!", res.message, "error");
                }
            },
            error: function(xhr){
                $('#updateBtn').prop('disabled', false).text('Update');

                let msg = "Something went wrong!";
                if(xhr.responseJSON && xhr.responseJSON.message){
                    msg = xhr.responseJSON.message;
                }

                Swal.fire("Failed!", msg, "error");
            }
        });
    });

    // ✅ SweetAlert confirm delete
    $(document).on('click', '.deleteScheduleBtn', function(){
        let id = $(this).data('id');
        let url = "{{ url('delete_driver_schedule') }}/" + id;

        Swal.fire({
            title: "Are you sure?",
            text: "This schedule will be deleted permanently!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Yes, delete it",
            cancelButtonText: "Cancel",
            confirmButtonColor: "#d33",
            cancelButtonColor: "#6c757d"
        }).then((result)=>{
            if(result.isConfirmed){
                $.ajax({
                    url: url,
                    type: "GET",
                    success: function(res){
                        if(res.success){
                            Swal.fire("Deleted!", res.message, "success").then(()=>location.reload());
                        }else{
                            Swal.fire("Failed!", res.message, "error");
                        }
                    },
                    error: function(){
                        Swal.fire("Error!", "Something went wrong!", "error");
                    }
                });
            }
        });
    });
</script>

@endsection
