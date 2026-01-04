@extends('backend.layouts.main')
@section('main-section')
    <section class="dashboard row g-4">
        <!-- Topbar -->
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2 class="text-warning fw-bold">Add Notification</h2> <button class="btn btn-warning text-dark fw-semibold"
                data-bs-toggle="modal" data-bs-target="#addLeadModal"> + Add Notification </button>
        </div> <!-- Leads Table -->
        <div class="col-12">
            <div class="card bg-dark text-white p-3 shadow-lg">
                <h5 class="mb-3 text-warning">All Notifications</h5>
                <div class="table-responsive">
                    <table id="leadTable" class="table table-dark table-striped table-hover align-middle">
                        <thead class="text-warning">
                            <tr>
                                <th>#</th>
                                <th>Visibility</th>
                                <th>Title</th>
                                <th>Description</th>
                                <th>Created om</th>
                                
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($data as $item)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $item->visible_to }}</td>
                                <td>{{ $item->title }}</td>
                                <td>{{ $item->description }}</td>
                                <td>{{ $item->created_at }}</td>
                              
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section> <!-- Add Lead Modal -->
    <div class="modal fade" id="addLeadModal" tabindex="-1" aria-labelledby="addLeadModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content bg-dark text-white border border-warning">
                <div class="modal-header border-warning">
                    <h5 class="modal-title text-warning" id="addLeadModalLabel">Add New Notification</h5> <button type="button"
                        class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="add_notification" class="p-3" method="post" action="{{ url('new_notification') }}">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label text-warning">Visibility</label>

                            <div class="form-check mb-2">
                                <input type="checkbox" id="select_all_roles" class="form-check-input">
                                <label for="select_all_roles" class="form-check-label fw-bold text-primary">Select
                                    All</label>
                            </div>

                            <div id="roles_list">
                                @foreach(\Spatie\Permission\Models\Role::all() as $role)
                                    <div class="form-check">
                                        <input type="checkbox" name="visibility[]" value="{{ $role->id }}" id="role_{{ $role->id }}"
                                            class="form-check-input role-checkbox" @if(!empty($userRoles) && in_array($role->name, $userRoles)) checked @endif>
                                        <label for="role_{{ $role->id }}" class="form-check-label">
                                            {{ ucfirst($role->name) }}
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        {{-- ✅ Script for Select All --}}
                        <script>
                            document.addEventListener("DOMContentLoaded", function () {
                                const selectAll = document.getElementById("select_all_roles");
                                const checkboxes = document.querySelectorAll(".role-checkbox");

                                selectAll.addEventListener("change", function () {
                                    checkboxes.forEach(cb => cb.checked = this.checked);
                                });

                                // Uncheck "Select All" if any individual role is unchecked
                                checkboxes.forEach(cb => {
                                    cb.addEventListener("change", function () {
                                        if (!this.checked) {
                                            selectAll.checked = false;
                                        } else if (document.querySelectorAll(".role-checkbox:checked").length === checkboxes.length) {
                                            selectAll.checked = true;
                                        }
                                    });
                                });
                            });
                        </script>


                        <div class="mb-3"> <label class="form-label text-warning">Heading of Notification</label> <input
                                type="text" class="form-control bg-secondary text-white border-0"
                                placeholder="Enter Notification Title" name="title" required> </div>

                        <div class="mb-3"> <label class="form-label text-warning">Write your message</label> <input
                                type="text" class="form-control bg-secondary text-white border-0"
                                placeholder="Write Notification" name="description" required> </div>
                        
                    </div>
                    <div class="modal-footer border-warning"> <button type="button" class="btn btn-secondary"
                            data-bs-dismiss="modal">Cancel</button> <button onclick="add_notification_function()" type="button"
                            class="btn btn-warning text-dark fw-semibold">Add Notification</button> </div>
                </form>

                            <script src="{{ asset('backend/js/sweetalert2.min.js') }}"></script>

                     <script>
                                          function add_notification_function(){
                                            const form = document.getElementById('add_notification');
                                            const formData = new FormData(form);
                                         
                                            fetch(form.action,{
                                              method: 'POST',
                                              body: formData,
                                              headers:{
                                                'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                                              }
                                            })
                                            .then(res=>res.json())
                                            .then(data=>{
                                              if(data.success){
                                                // alert('aa gya ');
                                                // code for sweat alert start
                                                Swal.fire({
                                                  icon:'success',
                                                  title:'Sent Successfully!',
                                                  showConfirmButton:false,
                                                  timer:1500
                                                }).then(()=>{
                                                  location.reload();
                                                });
                                                // code for sweat alert end
                                              }
                                              else
                                              {
                                               Swal.fire({
                                                    icon: 'error',
                                                    title: 'Update Failed',
                                                    // showConfirmButton:false,
                                                    text: data.message || 'Something went wrong!',
                                   });
                                              }
                                            })
                                            .catch(error=>{
                                              console.error('Error:',error);
                                              alert('Error occurred while submitting the form.');
                                            })
                                          }
                                </script>
            </div>
        </div>
</div> @endsection @section('scripts') <!-- jQuery & DataTables -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <script> $(document).ready(function () { var table = $('#leadTable').DataTable({ pageLength: 5, lengthChange: false, ordering: true, language: { search: "🔍 Search Lead:", paginate: { previous: "⬅️", next: "➡️" } }, columnDefs: [{ orderable: false, targets: [5, 6] }] }); // Assign alert $('.assign-user').change(function() { const lead = $(this).closest('tr').find('td:eq(1)').text(); const user = $(this).val(); if(user) alert(✅ Lead "${lead}" assigned to ${user}!); }); // Add Lead Modal Submit (dummy) $('#addLeadForm').submit(function(e){ e.preventDefault(); const formData = $(this).serializeArray(); const name = formData.find(f=>f.name==='')[0]?.value || $('input').eq(0).val(); const contact = $('input').eq(1).val(); const source = $('select').eq(0).val(); const status = $('select').eq(1).val(); const role = "Client"; // Add new row to DataTable const newRow = table.row.add([ table.rows().count() + 1, name || "Dummy Name", contact || "+91 0000000000", source || "Website", <span class="badge bg-success">${status || "New"}</span>, <select class="form-select bg-secondary text-white border-0 assign-user"> <option value="">-- Select User --</option> <option>Kshitiz Kumar</option> <option>Rahul Verma</option> <option>Awantika Sharma</option> <option>Priya Nair</option> </select>, <button class="btn btn-sm btn-warning text-dark">Update</button> ]).draw().node(); // Reset modal $('#addLeadForm')[0].reset(); $('#addLeadModal').modal('hide'); alert('✅ Lead added successfully!'); }); }); </script>
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
</style> @endsection