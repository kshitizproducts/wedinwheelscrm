@extends('backend.layouts.main')
@section('main-section')

<div class="row">
    <div class="col-12 col-sm-4 col-xl-4 col-md-12"> 

    <section class="dashboard row g-4">
        <!-- Topbar -->
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2 class="text-warning fw-bold">Lead Generation</h2> <button class="btn btn-warning text-dark fw-semibold"
                data-bs-toggle="modal" data-bs-target="#addLeadModal"> + Add Lead </button>
        </div> <!-- Leads Table -->
        <div class="col-12">
            <div class="card bg-dark text-white p-3 shadow-lg">
                <h5 class="mb-3 text-warning">Un-assigned leads !</h5>
                <div class="table-responsive">
                    <table id="leadTable" class="table table-dark table-striped table-hover align-middle">
                        <thead class="text-warning">
                            <tr>
                                <th>#</th>
                                <th>Lead Name</th>
                                <th>Contact</th>
                                <th>Source</th>
                                <th>Assign To (Managers)</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($un_assigned_leads as $lead)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $lead->client_name }}</td>
                                    <td>{{ $lead->contact ?? 'NA' }}</td>
                                    <td>{{ $lead->source ?? 'NA' }}</td>
                                    <td>

                                        <form id="update_lead_form_{{ $lead->id }}" method="post"
                                            action="{{ url('assign_lead_to_manager') }}">
                                            @csrf

                                            <input type="hidden" value="{{ $lead->id }}" name="lead_id">
                                            <select class="form-select bg-secondary text-white border-0 assign-user"
                                                name="manager_id">
                                                <option value="">-- Select User --</option>
                                                @foreach ($manager_list as $manager)
                                                    <option @if ($lead->manager_id == $manager->id) selected @endif
                                                        value="{{ $manager->id }}">
                                                        {{ $manager->name }}
                                                    </option>
                                                @endforeach
                                            </select>

                                    <td>
                                        <button onclick="update_lead_form_btn({{ $lead->id }})" type="button"
                                            class="btn btn-sm btn-warning text-dark">
                                            Update
                                        </button>
                                    </td>
                                    </form>

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


</div>



<div class="col-12 col-sm-8 col-xl-8 col-md-12 mt-1">
{{-- updated lead data --}}

   <section class="dashboard row g-4">
        <!-- Topbar -->
      <h2 class="text-warning fw-bold">Assigned Leads</h2>
        <div class="col-12">
            <div class="card bg-dark text-white p-3 shadow-lg  mt-2">
                <h5 class="mb-3 text-warning">Lead assigned to managers !</h5>
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
                                <th>Status</th>
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
                                        $manager_id= $lead->manager_id;
                                        $manager = DB::table('users')->where('id', $manager_id)->first();
                                        echo $manager ? $manager->name : 'Unassigned';  
                                        @endphp
                                    </td>
                                    <td>{{ $lead->contact ?? 'NA' }}</td>
                                    <td><span class="badge bg-warning text-dark">{{ $lead->source ?? 'NA' }}</span></td>
                                    <td>
                                        @php
                                        $lead_status = $lead->status;
                                        if($lead_status == 1){
                                            echo "<span class='badge bg-info text-dark'>Need to Contact</span>";    
                                        } elseif($lead_status == 2){
                                            echo "<span class='badge bg-success text-dark'>Contacted</span>";
                                        } elseif($lead_status == 3){
                                            echo "<span class='badge bg-secondary text-dark'>Closed</span>";
                                        } else {
                                            echo "<span class='badge bg-dark text-light'>NA</span>";
                                        }
                                        @endphp

                                    </td>
                                  
                                     
                                    </td>

                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>



</div>
</div>

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
                                <label class="radio-card">
                                    <input type="radio" name="enquiry_type" value="Event Organizers">
                                    <img style="width: 180px;"
                                        src="https://wedinwheels.com/wp-content/uploads/2024/01/wedinwheels-logo.png"
                                        alt="Friends" class="radio-icon">
                                    <span>Event Organizers</span>
                                </label>
                            </div>
                        </div>

                           <!-- Source -->
                        <div class="mb-4">
                            <label class="form-label text-warning">Source of Enquiry</label>
                            <div class="d-flex flex-wrap gap-3">
                               

                                  <label class="radio-card"><input type="radio" name="source" value="Google"><img
                                        src="{{ asset('logos/google.png') }}"
                                        class="radio-icon"><span>Google</span></label>
                                      <label class="radio-card"><input type="radio" name="source" value="WhatsApp"><img
                                        src="{{ asset('logos/whatsapp.png') }}"
                                        class="radio-icon"><span>WhatsApp</span></label>
                                         <label class="radio-card"><input type="radio" name="source" value="Facebook">
                                    <img src="{{ asset('logos/fb.png') }}"class="radio-icon"><span>Facebook</span></label>

                                     <label class="radio-card"><input type="radio" name="source" value="Instagram">
                                    <img src="{{ asset('logos/insta.png') }}"class="radio-icon"><span>Instagram</span></label>

                                     <label class="radio-card"><input type="radio" name="source" value="Walking">
                                    <img src="{{ asset('logos/walking.png') }}"class="radio-icon"><span>Walking</span></label>

                              
                               
                                <label class="radio-card"><input type="radio" name="source" value="Reference"><img
                                        src="{{ asset('logos/reference.png') }}"
                                        class="radio-icon"><span>Direct call / Reference</span></label>
                            </div>
                        </div>

                        <!-- Client Name -->
                        <div class="mb-4">
                            <label class="form-label text-warning">Client Name</label>
                            <input type="text" class="form-control bg-secondary text-white border-0"
                                placeholder="Enter Client Name" name="client_name" required>
                        </div>


                        
                        <!-- Event Type -->
                        <div class="mb-4">
                            <label class="form-label text-warning">Event Type</label>
                            <div class="d-flex flex-wrap gap-3">
                                <label class="radio-card"><input type="radio" name="event_type" value="Barat Entry"><img
                                        src="{{ asset('logos/event/wedding.png') }}"
                                        class="radio-icon"><span>Barat Entry</span></label>
                                <label class="radio-card"><input type="radio" name="event_type"
                                        value="Prewedding"><img src="{{ asset('logos/event/prewedding.png') }}"
                                        class="radio-icon"><span>Prewedding</span></label>
                                <label class="radio-card"><input type="radio" name="event_type"
                                        value="Small Event"><img src="{{ asset('logos/event/engagement.png') }}"
                                        class="radio-icon"><span>Small Event</span></label>
                                <label class="radio-card"><input type="radio" name="event_type"
                                        value="Corporate"><img src="{{ asset('logos/event/anniversary.png') }}"
                                        class="radio-icon"><span>Corporate</span></label>
                            
                            </div>
                        </div>
 

                        <!-- Branch -->
                        {{-- branch  --}}
                        <div class="mb-4">
                            <label class="form-label text-warning">Branch</label>
                            <div class="d-flex flex-wrap gap-3">
                                <label class="radio-card">
                                    <input type="radio" name="branch" value="WW">
                                    <img src="https://studiofotome.com/wp-content/uploads/2024/04/logo.png"
                                        class="radio-icon branch-logo">
                                    <span>Wedding Wheel (WW)</span>
                                </label>
                                <label class="radio-card">
                                    <input type="radio" name="branch" value="FW">
                                    <img src="https://studiofotome.com/wp-content/uploads/2024/04/logo.png"
                                        class="radio-icon branch-logo">
                                    <span>FotoMe with Wedding wheel (FW)</span>
                                </label>
                                <label class="radio-card">
                                    <input type="radio" name="branch" value="SW">
                                    <img src="https://studiofotome.com/wp-content/uploads/2024/04/logo.png"
                                        class="radio-icon branch-logo">
                                    <span>Saycheese with Wedinwheel (SW)</span>
                                </label>
                                
                            </div>
                        </div>


                    


                        <!-- Contact -->
                        <div class="mb-4">
                            <label class="form-label text-warning">Contact</label>
                            <input type="text" name="contact" class="form-control bg-secondary text-white border-0"
                                placeholder="Enter Contact Number" required>
                        </div>

                        {{-- booking date --}}
                             <div class="mb-4">
                            <label class="form-label text-warning">Booking Date (Onwards...)</label>
                            <input type="datetime-local" name="booking_date" class="form-control bg-secondary text-white border-0"
                                placeholder="Enter Booking Date" required>
                        </div>

                        <!-- Status -->
                        <div class="mb-4">
                            <label class="form-label text-warning">Status</label>
                            <div class="d-flex gap-3">
                                <select name="status" class="form-control">
                                    <option selected disabled>Please Select</option>
                                      <option value="0">⌚ Need to Contact</option>
                            <option value="1">✅ Client Confirmed booking</option>
                            <option value="2">📞 Did Not pick up call</option>
                            <option value="3">❌ Client did not respond</option>
                            <option value="4">⏳ Client said call later</option>
                            <option value="5">🚫 Client said not interested</option>
                            <option value="6">📝 Other</option>
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
