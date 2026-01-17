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
    <form id="share_form_{{ $lead->id }}" method="post" action="{{ url('share_available_cars') }}" class="p-3">

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

        <div class="col-md-4 car-item"
            data-name="{{ strtolower($car->brand . ' ' . $car->model . ' ' . $car->registration_no) }}">
            
            <div class="card mb-3 shadow-sm car-card"
                onclick="toggleCheckbox('car_{{ $car->id }}')"
                style="border: 1px solid {{ $available ? '#444' : '#ffc107' }}; border-radius: 12px; background-color: {{ $available ? '#2d2d2d' : '#1a1a1a' }}; cursor: pointer; transition: 0.3s;">

                <div class="card-body p-2 text-center">
                    <div style="position: relative;">
                        <img src="{{ asset($profilepublicUrl) }}" alt="{{ $car->brand }}"
                            class="img-fluid mb-2 rounded"
                            style="height:110px; width: 100%; object-fit:cover; filter: {{ $available ? 'none' : 'grayscale(100%)' }};">

                        {{-- ✅ DEFAULT SELECTED --}}
                        <input type="checkbox"
                            class="car-checkbox"
                            name="selected_cars[]"
                            value="{{ $car->id }} "
                            id="car_{{ $car->id }}"
                            checked
                            onclick="event.stopPropagation()"
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
       
        
        <button type="button" onclick="generateShareLink({{ $lead->id }})"
    class="btn btn-outline-light px-4" style="border-radius:30px;">
    Generate Link
</button>

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
function generateShareLink(leadId) {

    let form = document.getElementById("share_form_" + leadId);
    let formData = new FormData(form);

    fetch("{{ url('share_available_cars') }}", {
        method: "POST",
        body: formData,
        headers: {
            "X-CSRF-TOKEN": "{{ csrf_token() }}"
        }
    })
    .then(res => res.json())
    .then(data => {
        if(data.success){

            let url = data.url;
            let token = data.token;
            let shared = false; // ✅ track if clicked on whatsapp/email

            Swal.fire({
                title: "✅ Share Link Generated",
                html: `
                    <div style="text-align:left;">
                        <b>Share this link:</b><br>
                        <input id="shareLinkInput" class="form-control mt-2" value="${url}" readonly>
                        <small class="text-muted">Copy and share to client</small>
                    </div>
                `,
                icon: "success",
                showCancelButton: true,
                cancelButtonText: "Close",
                confirmButtonText: "WhatsApp",
                denyButtonText: "Email",
                showDenyButton: true,
                allowOutsideClick: true,
                didOpen: () => {
                    // auto select input
                    document.getElementById("shareLinkInput").addEventListener("click", function(){
                        this.select();
                    });
                }
            }).then((result) => {

                if(result.isConfirmed){
                    shared = true;
                    // ✅ WhatsApp share
                    window.open("https://api.whatsapp.com/send?text=" + encodeURIComponent("Please select car: " + url), "_blank");
                }
                else if(result.isDenied){
                    shared = true;
                    // ✅ Email share
                    window.location.href = "mailto:?subject=Select Car&body=" + encodeURIComponent("Please select your car from: " + url);
                }

                // ✅ If not shared, delete token record
                if(!shared){
                    deleteToken(token);
                }
            });

            // ✅ If user clicks outside / presses ESC / closes alert
            Swal.getPopup()?.addEventListener('mouseleave', function(){
                // nothing
            });

        } else {
            Swal.fire("Error", data.message || "Something went wrong!", "error");
        }
    })
    .catch(err => {
        console.log(err);
        Swal.fire("Error", "Network error!", "error");
    });
}

// ✅ delete token ajax
function deleteToken(token){
    fetch("{{ url('delete_car_share_token') }}", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": "{{ csrf_token() }}"
        },
        body: JSON.stringify({ token: token })
    })
    .then(res => res.json())
    .then(data => {
        console.log("Token deleted:", token);
    });
}
</script>

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
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content border-0 shadow-lg"
            style="background:#0f1115; color:#fff; border-radius:18px; overflow:hidden;">

            {{-- Header --}}
            <div class="modal-header px-4 py-3"
                style="background:#141824; border-bottom:1px solid #2a2f3b;">
                <div>
                    <h5 class="modal-title text-warning fw-bold mb-0" id="updatedlead{{ $lead->id }}Label">
                        Update Lead Status
                    </h5>
                    <small class="text-white">Client: <b class="text-white">{{ $lead->client_name }}</b></small>
                </div>

                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>

            {{-- Body --}}
            <div class="modal-body px-4 py-4">

                <form id="lead_status_form_{{ $lead->id }}" method="post" action="{{ url('update_lead_status') }}">
                    @csrf
                    <input type="hidden" name="lead_id" value="{{ $lead->id }}">

                    {{-- Status --}}
                    <div class="mb-3">
                        <label class="form-label text-warning fw-semibold mb-1">
                            What is Status?
                        </label>

                        <select name="choose_status"
                            class="form-select text-white border-0"
                            style="background:#1c2230; border-radius:12px; padding:12px;">
                            <option selected disabled>-- Please Select --</option>
                             <option value="0">⌚ Need to Contact</option>
                            <option value="1">✅ Client Confirmed booking</option>
                            <option value="2">📞 Did Not pick up call</option>
                            <option value="3">❌ Client did not respond</option>
                            <option value="4">⏳ Client said call later</option>
                            <option value="5">🚫 Client said not interested</option>
                            <option value="6">📝 Other</option>
                        </select>
                    </div>

                    {{-- Remark --}}
                    <div class="mb-3">
                        <label class="form-label text-warning fw-semibold mb-1">
                            Remark
                        </label>
                        <textarea name="remark"
                            rows="4"
                            class="form-control text-white border-0"
                            style="background:#1c2230; border-radius:12px; padding:12px;"
                            placeholder="Write remark..."></textarea>
                    </div>

                </form>

            </div>

            {{-- Footer --}}
            <div class="modal-footer px-4 py-3"
                style="background:#141824; border-top:1px solid #2a2f3b;">
                <button type="button" class="btn btn-outline-light px-4"
                    style="border-radius:999px;" data-bs-dismiss="modal">
                    Cancel
                </button>

                <button type="button" onclick="submitLeadStatus({{ $lead->id }})"
                    class="btn btn-warning text-dark fw-bold px-4"
                    style="border-radius:999px;">
                    Update
                </button>
            </div>

        </div>
    </div>
</div>

<script>
function submitLeadStatus(leadId){
    const form = document.getElementById('lead_status_form_' + leadId);
    const formData = new FormData(form);

    fetch("{{ url('update_lead_status') }}", {
        method: "POST",
        body: formData,
        headers: {
            "X-CSRF-TOKEN": "{{ csrf_token() }}"
        }
    })
    .then(res => res.json())
    .then(data => {
        if(data.success){
            Swal.fire({
                icon: 'success',
                title: 'Updated!',
                showConfirmButton: false,
                timer: 1200
            }).then(()=>location.reload());
        }else{
            Swal.fire("Error", data.message || "Something went wrong!", "error");
        }
    })
    .catch(err=>{
        console.log(err);
        Swal.fire("Error", "Network error!", "error");
    });
}
</script>

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
