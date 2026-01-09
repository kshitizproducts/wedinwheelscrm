@extends('backend.layouts.main')
@section('main-section')
    <section class="dashboard row g-4">
        <!-- Topbar -->
        <h2 class="text-warning fw-bold">My Leads</h2>
        <div class="col-12">
            <div class="card bg-dark text-white p-3 shadow-lg  mt-2">
                <h5 class="mb-3 text-warning">Lead assigned to me !</h5>
                <div class="table-responsive">
                    <table id="leadTable" class="table table-dark table-striped table-hover align-middle">
                        <thead class="text-warning">
                            <tr>
                                <th>#</th>
                                <th>Lead ID</th>
                                <th>Lead Name</th>
                                <th>Manager</th>
                                <th>Contact</th>
                                <th>Source</th>
                                <th>Assigned to <small>Manager.?</small></th>
                                <th>Share Car details to Client</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($leads as $lead)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $lead->unique_id }}</td>
                                    <td>{{ $lead->client_name }}</td>
                                    <td>
                                        @php
                                            $manager_id = $lead->manager_id;
                                            $manager = DB::table('users')->where('id', $manager_id)->first();
                                            echo $manager ? $manager->name : 'Unassigned';
                                        @endphp
                                    </td>
                                    <td>{{ $lead->contact ?? 'NA' }}</td>
                                    <td><span class="badge bg-warning text-dark">{{ $lead->source ?? 'NA' }}</span></td>
                                    <td>
                                        @php
                                            $manager_data = DB::table('users')->where('id', $lead->manager_id)->first();
                                        @endphp
                                        {{ $manager_data->name ?? 'NA' }}
                                    </td>
                                    <td>

                                        {{-- share code start here --}}

                                        <button class="btn btn-primary text-dark fw-semibold" data-bs-toggle="modal"
                                            data-bs-target="#sharetoclient{{ $lead->id }}"> <svg
                                                xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                stroke-linecap="round" stroke-linejoin="round" style="cursor:pointer;">
                                                <circle cx="18" cy="5" r="3"></circle>
                                                <circle cx="6" cy="12" r="3"></circle>
                                                <circle cx="18" cy="19" r="3"></circle>
                                                <line x1="8.59" y1="13.51" x2="15.42" y2="17.49"></line>
                                                <line x1="15.41" y1="6.51" x2="8.59" y2="10.49"></line>
                                            </svg>
                                        </button>


                                        <div class="modal fade" id="sharetoclient{{ $lead->id }}" tabindex="-1"
                                            aria-labelledby="sharetoclient{{ $lead->id }}Label" aria-hidden="true">
                                            <div class="modal-dialog modal-xl modal-dialog-centered">
                                                <div class="modal-content bg-dark text-white border border-warning">
                                                    <div class="modal-header border-warning">
                                                        <h5 class="modal-title text-warning" id="addLeadModalLabel">
                                                            Share Car Details to {{ $lead->client_name }} !
                                                        </h5>
                                                        <button type="button" class="btn-close btn-close-white"
                                                            data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
<form id="add_new_lead_form" method="post" action="{{ url('save_client_car_filter') }}" class="p-3">
    @csrf


    <input type="hidden" value="{{ $lead->id }}" name="lead_id">
    <div class="modal-body">
        <div class="row">
          
        </div>

        <div class="mb-3">
            <label class="form-label text-warning">Booking Date: </label>
            <span class="badge bg-warning text-danger px-3 py-2">
                {{ $lead->booking_date ?? 'NA' }}
            </span>
        </div>

        <hr class="border-secondary">

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="text-white mb-0">Select Available Cars ({{ count($cars) }})</h5>
            <input type="text" id="carSearch" class="form-control form-control-sm w-50" placeholder="Search car by name or reg no...">
        </div>

        <div class="car-selection-container" style="max-height: 500px; overflow-y: auto; overflow-x: hidden; padding-right: 5px;">
            <div class="row" id="carList">
                @foreach ($cars as $car)
                    @php
                        $available = empty($car->next_availability) || $car->next_availability <= $lead->booking_date;
                        $profilepublicUrl = $car->profile_pic;
                    @endphp

                    <div class="col-md-4 car-item" data-name="{{ strtolower($car->brand . ' ' . $car->model . ' ' . $car->registration_no) }}">
                        <div class="card mb-3 shadow-sm car-card" 
                            onclick="toggleCheckbox('car_{{ $car->id }}')"
                            style="border: 1px solid {{ $available ? '#444' : '#ffc107' }}; border-radius: 12px; background-color: {{ $available ? '#2d2d2d' : '#1a1a1a' }}; cursor: pointer; transition: 0.3s;">
                            
                            <div class="card-body p-2 text-center">
                                <div style="position: relative;">
                                    <img src="{{ asset($profilepublicUrl) }}" alt="{{ $car->brand }}"
                                        class="img-fluid mb-2 rounded"
                                        style="height:110px; width: 100%; object-fit:cover; filter: {{ $available ? 'none' : 'grayscale(100%)' }};">
                                    
                                    <input type="checkbox" class="car-checkbox" 
                                        name="selected_cars[]" 
                                        value="{{ $car->brand }} {{ $car->model }} ({{ $car->registration_no }})" 
                                        id="car_{{ $car->id }}"
                                        style="position: absolute; top: 10px; right: 10px; width: 20px; height: 20px; accent-color: #ffc107;">
                                </div>

                                <h6 class="mb-1 fw-bold {{ $available ? 'text-white' : 'text-warning' }}" style="font-size: 0.9rem;">
                                    {{ $car->brand }} {{ $car->model }}
                                </h6>
                                <small class="text-muted d-block" style="font-size: 0.75rem;">{{ $car->registration_no }}</small>
                                
                                @if ($available)
                                    <span class="badge bg-success-subtle text-success mt-1" style="font-size: 0.7rem;">Available</span>
                                @else
                                    <span class="badge bg-danger-subtle text-danger mt-1" style="font-size: 0.7rem;">Engaged</span>
                                    <div class="mt-1">
                                        <small class="text-warning" style="font-size: 0.65rem;">
                                            Until: <strong>{{ date('d M', strtotime($car->next_availability)) }}</strong>
                                        </small>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <div class="modal-footer justify-content-center border-top border-warning bg-dark">
        <button type="button" onclick="shareViaEmail()" class="btn btn-warning text-dark shadow-sm d-flex align-items-center px-4" style="border-radius:30px;">
            <img src="https://cdn-icons-png.flaticon.com/512/732/732200.png" width="20" class="me-2"> Email
        </button>

        <button type="button" onclick="shareViaWhatsApp()" class="btn btn-success shadow-sm d-flex align-items-center px-4" style="border-radius:30px;">
            <img src="https://cdn-icons-png.flaticon.com/512/733/733585.png" width="20" class="me-2"> WhatsApp
        </button>
        
        <button type="submit" class="btn btn-outline-light px-4" style="border-radius:30px;">Save Data</button>
    </div>
</form>

<style>
    /* Scrollbar styling for a better dark look */
    .car-selection-container::-webkit-scrollbar { width: 6px; }
    .car-selection-container::-webkit-scrollbar-track { background: #1a1a1a; }
    .car-selection-container::-webkit-scrollbar-thumb { background: #ffc107; border-radius: 10px; }
    .car-card:hover { transform: translateY(-3px); border-color: #ffc107 !important; }
</style>

<script>
    // 1. Search Logic
    document.getElementById('carSearch').addEventListener('keyup', function() {
        let filter = this.value.toLowerCase();
        let items = document.querySelectorAll('.car-item');
        items.forEach(item => {
            if (item.getAttribute('data-name').includes(filter)) {
                item.style.display = "";
            } else {
                item.style.display = "none";
            }
        });
    });

    // 2. Click on Card to Checkbox
    function toggleCheckbox(id) {
        const cb = document.getElementById(id);
        cb.checked = !cb.checked;
    }

    // 3. Get Selected Cars Text
    function getSelectedCars() {
        let selected = [];
        document.querySelectorAll('.car-checkbox:checked').forEach(cb => {
            selected.push(cb.value);
        });
        return selected;
    }

    // 4. WhatsApp Share
    function shareViaWhatsApp() {
        let cars = getSelectedCars();
        if (cars.length === 0) { alert('Please select at least one car'); return; }
        
        let text = "Hello, here are the available car details:\n\n" + cars.join("\n") + "\n\nRegards.";
        window.open("https://api.whatsapp.com/send?text=" + encodeURIComponent(text), "_blank");
    }

    // 5. Email Share
    function shareViaEmail() {
        let cars = getSelectedCars();
        if (cars.length === 0) { alert('Please select at least one car'); return; }
        
        let body = "Hello,\n\nRequested car details:\n" + cars.join("\n");
        window.location.href = "mailto:?subject=Available Car Details&body=" + encodeURIComponent(body);
    }
</script>
                                                </div>
                                            </div>
                                        </div>

                                        <style>
                                            @media (max-width: 480px) {
                                                .modal-footer a {
                                                    padding: 6px 10px;
                                                    font-size: 14px;
                                                }
                                            }
                                        </style>

                                        {{-- end of share code --}}
                                        <!-- Email -->

                                    </td>

                                    <td>
                                        @php
                                            $lead_status = $lead->status;
                                            if ($lead_status == 1) {
                                                echo "<span class='badge bg-info text-dark'>Need to Contact</span>";
                                            } elseif ($lead_status == 2) {
                                                echo "<span class='badge bg-success text-dark'>Contacted</span>";
                                            } elseif ($lead_status == 3) {
                                                echo "<span class='badge bg-secondary text-dark'>Closed</span>";
                                            } else {
                                                echo "<span class='badge bg-dark text-light'>NA</span>";
                                            }
                                        @endphp

                                    </td>


                                    </td>
                                    <td>
                                        <button class="btn btn-warning text-dark fw-semibold" data-bs-toggle="modal"
                                            data-bs-target="#updatedlead{{ $lead->id }}"> Update Leads </button>



                                        <div class="modal fade" id="updatedlead{{ $lead->id }}" tabindex="-1"
                                            aria-labelledby="updatedlead{{ $lead->id }}Label" aria-hidden="true">
                                            <div class="modal-dialog modal-xl modal-dialog-centered">
                                                <div class="modal-content bg-dark text-white border border-warning">
                                                    <div class="modal-header border-warning">
                                                        <h5 class="modal-title text-warning" id="addLeadModalLabel">Update
                                                            {{ $lead->client_name }} Info !</h5>
                                                        <button type="button" class="btn-close btn-close-white"
                                                            data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>



                                                    <form id="add_new_lead_form" method="post"
                                                        action="{{ url('save_client_data') }}" class="p-3">
                                                        @csrf
                                                        <div class="modal-body">

                                                            <!-- Client Name -->
                                                            <div class="mb-4">
                                                                <label class="form-label text-warning">Client Name</label>
                                                                <input type="text"
                                                                    class="form-control bg-secondary text-white border-0"
                                                                    placeholder="Enter Client Name"
                                                                    value="{{ $lead->client_name }}" name="client_name"
                                                                    required>
                                                            </div>

                                                            <div class="mb-4">
                                                                <label class="form-label text-warning">Contact</label>
                                                                <input type="number"
                                                                    class="form-control bg-secondary text-white border-0"
                                                                    placeholder="Enter Contact"
                                                                    value="{{ $lead->contact }}" name="contact" required>
                                                            </div>


                                                            <div class="mb-4">
                                                                <label class="form-label text-warning">Booking Date</label>
                                                                <input type="datetime-local" id="booking_datetime"
                                                                    class="form-control" name="booking_datetime">

                                                                <script>
                                                                    const bookingInput = document.getElementById('booking_datetime');

                                                                    // Set current date & time as the minimum
                                                                    const now = new Date();
                                                                    const localISOTime = now.toISOString().slice(0, 16); // YYYY-MM-DDTHH:MM
                                                                    bookingInput.min = localISOTime;

                                                                    // Prevent manual entry of past date/time
                                                                    bookingInput.addEventListener('change', function() {
                                                                        const selected = new Date(this.value);
                                                                        if (selected < now) {
                                                                            alert('You cannot select a past date or time!');
                                                                            this.value = ''; // Clear invalid value
                                                                        }
                                                                    });
                                                                </script>



                                                            </div>

                                                            <div class="mb-4">
                                                                <label class="form-label text-warning">Assign Car</label>
                                                                <select name="car" class="form-control">
                                                                    <option disabled selected>Please Select</option>
                                                                    @foreach ($cars as $car)
                                                                        <option value="{{ $car->id }}">
                                                                            {{ $car->brand }} -
                                                                            {{ $car->registration_no }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>


                                                    </form>



                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <script src="{{ asset('backend/js/sweetalert2.min.js') }}"></script>
                                    <script>
                                        function update_lead_form_btn(leadId) {
                                            const form = document.getElementById('update_lead_form_' + leadId);
                                            const formData = new FormData(form);

                                            fetch(form.action, {
                                                    method: 'POST',
                                                    body: formData,
                                                    headers: {
                                                        'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                                                    }
                                                })
                                                .then(res => res.json())
                                                .then(data => {
                                                    if (data.success) {
                                                        Swal.fire({
                                                            icon: 'success',
                                                            title: 'Manager Assigned Successfully!',
                                                            showConfirmButton: false,
                                                            timer: 1500
                                                        }).then(() => {
                                                            location.reload();
                                                        });
                                                    } else {
                                                        Swal.fire({
                                                            icon: 'error',
                                                            title: 'Update Failed',
                                                            text: data.message || 'Something went wrong!',
                                                        });
                                                    }
                                                })
                                                .catch(error => {
                                                    console.error('Error:', error);
                                                    Swal.fire({
                                                        icon: 'error',
                                                        title: 'Network Error',
                                                        text: 'Error occurred while submitting the form.',
                                                    });
                                                });
                                        }
                                    </script>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>



    <!-- Add Lead Modal -->
    <!-- Add Lead Modal -->
    <div class="modal fade" id="addLeadModal" tabindex="-1" aria-labelledby="addLeadModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content bg-dark text-white border border-warning">
                <div class="modal-header border-warning">
                    <h5 class="modal-title text-warning" id="addLeadModalLabel">Add New Lead</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>

                <style>
                    .radio-card {
                        position: relative;
                        display: flex;
                        flex-direction: column;
                        align-items: center;
                        background: #1f1f1f;
                        border: 1px solid #444;
                        border-radius: 12px;
                        padding: 15px 20px;
                        cursor: pointer;
                        transition: all 0.2s ease-in-out;
                        width: 160px;
                        text-align: center;
                    }

                    .radio-card:hover {
                        background: #2a2a2a;
                        border-color: #ffc107;
                    }

                    .radio-card input[type="radio"] {
                        display: none;
                    }

                    .radio-card img.radio-icon {
                        width: 50px;
                        height: auto;
                        margin-bottom: 8px;
                        filter: brightness(0.9);
                    }

                    .radio-card img.branch-logo {
                        width: 80px !important;
                        /* Larger branch logos */
                        margin-bottom: 10px;
                        filter: brightness(1);
                    }

                    .radio-card span {
                        font-size: 14px;
                        color: #bbb;
                    }

                    .radio-card input[type="radio"]:checked+img+span {
                        color: #000;
                        background: #ffc107;
                        padding: 4px 8px;
                        border-radius: 6px;
                    }
                </style>



                <form id="add_new_lead_form" method="post" action="{{ url('save_client_data') }}" class="p-3">
                    @csrf
                    <div class="modal-body">

                        <!-- Client Name -->
                        <div class="mb-4">
                            <label class="form-label text-warning">Client Name</label>
                            <input type="text" class="form-control bg-secondary text-white border-0"
                                placeholder="Enter Client Name" name="client_name" required>
                        </div>

                        <!-- Enquiry Type -->
                        <div class="mb-4">
                            <label class="form-label text-warning">Enquiry Type</label>
                            <div class="d-flex flex-wrap gap-3">
                                <label class="radio-card">
                                    <input type="radio" name="enquiry_type" value="Potential">
                                    <img style="width: 180px;"
                                        src="https://wedinwheels.com/wp-content/uploads/2024/01/wedinwheels-logo.png"
                                        alt="Potential" class="radio-icon">
                                    <span>Potential</span>
                                </label>
                                <label class="radio-card">
                                    <input type="radio" name="enquiry_type" value="Regular">
                                    <img style="width: 180px;"
                                        src="https://wedinwheels.com/wp-content/uploads/2024/01/wedinwheels-logo.png"
                                        alt="Regular" class="radio-icon">
                                    <span>Regular</span>
                                </label>
                                <label class="radio-card">
                                    <input type="radio" name="enquiry_type" value="Hot Leads">
                                    <img style="width: 180px;"
                                        src="https://wedinwheels.com/wp-content/uploads/2024/01/wedinwheels-logo.png"
                                        alt="Hot Leads" class="radio-icon">
                                    <span>Hot Leads</span>
                                </label>
                                <label class="radio-card">
                                    <input type="radio" name="enquiry_type" value="Instant">
                                    <img style="width: 180px;"
                                        src="https://wedinwheels.com/wp-content/uploads/2024/01/wedinwheels-logo.png"
                                        alt="Instant" class="radio-icon">
                                    <span>Instant</span>
                                </label>
                                <label class="radio-card">
                                    <input type="radio" name="enquiry_type" value="VIP">
                                    <img style="width: 180px;"
                                        src="https://wedinwheels.com/wp-content/uploads/2024/01/wedinwheels-logo.png"
                                        alt="VIP" class="radio-icon">
                                    <span>VIP</span>
                                </label>
                                <label class="radio-card">
                                    <input type="radio" name="enquiry_type" value="Friends">
                                    <img style="width: 180px;"
                                        src="https://wedinwheels.com/wp-content/uploads/2024/01/wedinwheels-logo.png"
                                        alt="Friends" class="radio-icon">
                                    <span>Friends</span>
                                </label>
                            </div>
                        </div>

                        <!-- Branch -->
                        {{-- branch  --}}
                        <div class="mb-4">
                            <label class="form-label text-warning">Branch</label>
                            <div class="d-flex flex-wrap gap-3">
                                <label class="radio-card">
                                    <input type="radio" name="branch" value="FM">
                                    <img src="https://studiofotome.com/wp-content/uploads/2024/04/logo.png"
                                        class="radio-icon branch-logo">
                                    <span>FotoMe (FM)</span>
                                </label>
                                <label class="radio-card">
                                    <input type="radio" name="branch" value="FA">
                                    <img src="https://studiofotome.com/wp-content/uploads/2024/04/logo.png"
                                        class="radio-icon branch-logo">
                                    <span>FotoMe A (FA)</span>
                                </label>
                                <label class="radio-card">
                                    <input type="radio" name="branch" value="FB">
                                    <img src="https://studiofotome.com/wp-content/uploads/2024/04/logo.png"
                                        class="radio-icon branch-logo">
                                    <span>FotoMe B (FB)</span>
                                </label>
                                <label class="radio-card">
                                    <input type="radio" name="branch" value="FC">
                                    <img src="https://studiofotome.com/wp-content/uploads/2024/04/logo.png"
                                        class="radio-icon branch-logo">
                                    <span>FotoMe C (FC)</span>
                                </label>
                            </div>
                        </div>


                        <!-- Source -->
                        <div class="mb-4">
                            <label class="form-label text-warning">Source</label>
                            <div class="d-flex flex-wrap gap-3">
                                <label class="radio-card"><input type="radio" name="source" value="Facebook"><img
                                        src="{{ asset('logos/fb.png') }}"
                                        class="radio-icon"><span>Facebook</span></label>
                                <label class="radio-card"><input type="radio" name="source" value="Google"><img
                                        src="{{ asset('logos/google.png') }}"
                                        class="radio-icon"><span>Google</span></label>
                                <label class="radio-card"><input type="radio" name="source" value="Instagram"><img
                                        src="{{ asset('logos/insta.png') }}"
                                        class="radio-icon"><span>Instagram</span></label>
                                <label class="radio-card"><input type="radio" name="source" value="WhatsApp"><img
                                        src="{{ asset('logos/whatsapp.png') }}"
                                        class="radio-icon"><span>WhatsApp</span></label>
                                <label class="radio-card"><input type="radio" name="source" value="Walk-In"><img
                                        src="{{ asset('logos/walking.png') }}"
                                        class="radio-icon"><span>Walk-In</span></label>
                                <label class="radio-card"><input type="radio" name="source" value="Reference"><img
                                        src="{{ asset('logos/reference.png') }}"
                                        class="radio-icon"><span>Reference</span></label>
                            </div>
                        </div>

                        <!-- Event Type -->
                        <div class="mb-4">
                            <label class="form-label text-warning">Event Type</label>
                            <div class="d-flex flex-wrap gap-3">
                                <label class="radio-card"><input type="radio" name="event_type" value="Wedding"><img
                                        src="{{ asset('logos/event/wedding.png') }}"
                                        class="radio-icon"><span>Wedding</span></label>
                                <label class="radio-card"><input type="radio" name="event_type"
                                        value="Prewedding"><img src="{{ asset('logos/event/prewedding.png') }}"
                                        class="radio-icon"><span>Prewedding</span></label>
                                <label class="radio-card"><input type="radio" name="event_type"
                                        value="Engagement"><img src="{{ asset('logos/event/engagement.png') }}"
                                        class="radio-icon"><span>Engagement</span></label>
                                <label class="radio-card"><input type="radio" name="event_type"
                                        value="Anniversary"><img src="{{ asset('logos/event/anniversary.png') }}"
                                        class="radio-icon"><span>Anniversary</span></label>
                                <label class="radio-card"><input type="radio" name="event_type" value="Birthday"><img
                                        src="{{ asset('logos/event/birthday.png') }}"
                                        class="radio-icon"><span>Birthday</span></label>
                                <label class="radio-card"><input type="radio" name="event_type"
                                        value="Corporate Shoot"><img src="{{ asset('logos/event/corporate.png') }}"
                                        class="radio-icon"><span>Corporate Shoot</span></label>
                                <label class="radio-card"><input type="radio" name="event_type"
                                        value="Musical Night"><img src="{{ asset('logos/event/musical.png') }}"
                                        class="radio-icon"><span>Musical Night</span></label>
                            </div>
                        </div>

                        <!-- Contact -->
                        <div class="mb-4">
                            <label class="form-label text-warning">Contact</label>
                            <input type="text" name="contact" class="form-control bg-secondary text-white border-0"
                                placeholder="Enter Contact Number" required>
                        </div>

                        <!-- Status -->
                        <div class="mb-4">
                            <label class="form-label text-warning">Status</label>
                            <div class="d-flex gap-3">
                                <select name="status" class="form-control">
                                    <option selected disabled>Please Select</option>
                                    <option value="1">Need to Contact</option>
                                    <option value="2">Contacted</option>
                                    <option value="3">Closed</option>
                                </select>
                            </div>
                        </div>

                    </div>

                    <div class="modal-footer border-warning">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button onclick="add_new_lead()" type="button" class="btn btn-warning text-dark fw-semibold">Add
                            Lead</button>
                    </div>
                </form>


                <script src="{{ asset('backend/js/sweetalert2.min.js') }}"></script>
                <script>
                    function add_new_lead() {
                        const form = document.getElementById('add_new_lead_form');
                        const formData = new FormData(form);

                        fetch(form.action, {
                                method: 'POST',
                                body: formData,
                                headers: {
                                    'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                                }
                            })
                            .then(res => res.json())
                            .then(data => {
                                if (data.success) {
                                    // alert('aa gya ');
                                    // code for sweat alert start
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Lead Added Successfully!',
                                        showConfirmButton: false,
                                        timer: 1500
                                    }).then(() => {
                                        location.reload();
                                    });
                                    // code for sweat alert end
                                } else {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Update Failed',
                                        // showConfirmButton:false,
                                        text: data.message || 'Something went wrong!',
                                    });
                                }
                            })
                            .catch(error => {
                                console.error('Error:', error);
                                alert('Error occurred while submitting the form.');
                            })
                    }
                </script>
            </div>
        </div>
    </div>

    <!-- Styles -->
    <style>
        .radio-card {
            position: relative;
            display: flex;
            flex-direction: column;
            align-items: center;
            background: #1f1f1f;
            border: 1px solid #444;
            border-radius: 12px;
            padding: 15px 20px;
            cursor: pointer;
            transition: all 0.2s ease-in-out;
            width: 110px;
            text-align: center;
        }

        .radio-card:hover {
            background: #2a2a2a;
            border-color: #ffc107;
        }

        .radio-card input[type="radio"] {
            display: none;
        }

        .radio-card img.radio-icon {
            width: 40px;
            height: 40px;
            margin-bottom: 8px;
            filter: brightness(0.9);
        }

        .radio-card span {
            font-size: 14px;
            color: #bbb;
        }

        .radio-card input[type="radio"]:checked+img+span {
            color: #000;
            background: #ffc107;
            padding: 4px 8px;
            border-radius: 6px;
        }
    </style>
    @endsection @section('scripts')
    <!-- jQuery & DataTables -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <script>
        $(document).ready(function() {
                    var table = $('#leadTable').DataTable({
                        pageLength: 5,
                        lengthChange: false,
                        ordering: true,
                        language: {
                            search: "🔍 Search Lead:",
                            paginate: {
                                previous: "⬅️",
                                next: "➡️"
                            }
                        },
                        columnDefs: [{
                            orderable: false,
                            targets: [5, 6]
                        }]
                    }); // Assign alert $('.assign-user').change(function() { const lead = $(this).closest('tr').find('td:eq(1)').text(); const user = $(this).val(); if(user) alert(✅ Lead "${lead}" assigned to ${user}!); }); // Add Lead Modal Submit (dummy) $('#addLeadForm').submit(function(e){ e.preventDefault(); const formData = $(this).serializeArray(); const name = formData.find(f=>f.name==='')[0]?.value || $('input').eq(0).val(); const contact = $('input').eq(1).val(); const source = $('select').eq(0).val(); const status = $('select').eq(1).val(); const role = "Client"; // Add new row to DataTable const newRow = table.row.add([ table.rows().count() + 1, name || "Dummy Name", contact || "+91 0000000000", source || "Website", <span class="badge bg-success">${status || "New"}</span>, <select class="form-select bg-secondary text-white border-0 assign-user"> <option value="">-- Select User --</option> <option>Kshitiz Kumar</option> <option>Rahul Verma</option> <option>Awantika Sharma</option> <option>Priya Nair</option> </select>, <button class="btn btn-sm btn-warning text-dark">Update</button> ]).draw().node(); // Reset modal $('#addLeadForm')[0].reset(); $('#addLeadModal').modal('hide'); alert('✅ Lead added successfully!'); }); }); 
    </script>
    <style>
        body {
            background-color: #121212;
        }

        .card {
            border-radius: 15px;
            background-color: #1e1e1e;
            border: 1px solid #333;
        }

        .btn-warning {
            background-color: #ffc107;
            border: none;
        }

        .btn-warning:hover {
            background-color: #ffca2c;
        }

        .dataTables_filter input {
            background-color: #1e1e1e !important;
            border: 1px solid #444 !important;
            color: #fff !important;
            border-radius: 8px;
            padding: 6px 10px;
        }

        .dataTables_filter label {
            color: #ffc107 !important;
            font-weight: 600;
        }

        .paginate_button {
            color: #ffc107 !important;
        }

        table.dataTable tbody tr:hover {
            background-color: #2b2b2b;
        }

        .badge {
            font-size: 0.85rem;
        }

        .modal-content {
            border-radius: 10px;
        }
    </style>
@endsection
