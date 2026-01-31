@extends('backend.layouts.main')
@section('main-section')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">

    <section class="dashboard container-fluid py-4">
        <div class="d-flex justify-content-between align-items-center mb-4 bg-dark p-3 rounded-3 shadow-sm border-start border-warning border-4">
            <div>
                <h4 class="text-warning fw-bold mb-0">Company Master</h4>
                <p class="text-white-50 small mb-0">Manage and list all vendor companies</p>
            </div>
            <button class="btn btn-warning btn-md text-dark fw-bold rounded-pill shadow-sm px-4" data-bs-toggle="modal"
                data-bs-target="#companyModal" onclick="resetForm()">
                <i class="fas fa-plus-circle me-2"></i>New Company
            </button>
        </div>

        <div class="card bg-dark border-0 shadow-lg">
            <div class="card-body p-4">
                <div class="table-responsive">
                    <table id="companyTable" class="table table-dark table-hover align-middle mb-0 custom-table w-100">
                        <thead>
                            <tr class="text-warning border-secondary">
                                <th class="ps-3">#</th>
                                <th>Company Name</th>
                                <th>Created Date</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="text-white">
                            @foreach ($companies as $key => $item)
                                <tr>
                                    <td class="text-white-50 small ps-3">{{ $key + 1 }}</td>
                                    <td class="fw-bold">{{ $item->company_name }}</td>
                                    <td class="text-white-50 small">{{ date('d-m-Y', strtotime($item->created_at)) }}</td>
                                    <td class="text-center">
                                        <div class="d-flex justify-content-center gap-2">
                                            <button class="btn btn-sm btn-action edit-btn"
                                                onclick="editCompany('{{ $item->id }}', '{{ $item->company_name }}')">
                                                <i class="fas fa-pen"></i>
                                            </button>
                                            <button class="btn btn-sm btn-action delete-btn text-danger"
                                                onclick="deleteCompany('{{ $item->id }}')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>

    <div class="modal fade" id="companyModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
        <div class="modal-dialog modal-dialog-centered">
            <div style="background-color:#1e1e1e" class="modal-content text-white border-0 shadow-lg">
                <div class="modal-header border-secondary py-3">
                    <h5 class="modal-title text-warning fw-bold" id="modalTitle">Add New Company</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <form id="companyFormAction" method="post" action="{{ url('store-company') }}">
                    @csrf
                    <input type="hidden" name="id" id="company_id">

                    <div class="modal-body p-4">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Company Name <span class="text-warning">*</span></label>
                            <input required type="text" name="company_name" id="company_name"
                                class="form-control bg-dark text-white border-secondary shadow-none"
                                placeholder="Enter company name">
                        </div>

                        <div id="form-progress" class="d-none mt-3">
                            <div class="progress" style="height: 5px; background: #333;">
                                <div class="progress-bar progress-bar-striped progress-bar-animated bg-warning" style="width: 100%"></div>
                            </div>
                            <small class="text-warning d-block text-center mt-1">Processing...</small>
                        </div>
                    </div>

                    <div class="modal-footer border-0">
                        <button type="button" class="btn btn-outline-secondary text-white" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" id="save-btn" onclick="save_company()" class="btn btn-warning fw-bold px-4">SAVE DATA</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

     <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        // 1. DataTable ko document ready me rakhein
        $(document).ready(function() {
            if ($.fn.DataTable.isDataTable('#companyTable')) {
                $('#companyTable').DataTable().destroy();
            }
            $('#companyTable').DataTable({
                "pageLength": 10,
                "language": {
                    "search": "",
                    "searchPlaceholder": "Search companies...",
                    "paginate": { "previous": "<", "next": ">" }
                }
            });
        });

        // 2. Sare functions document.ready ke BAHAR honge taaki HTML buttons inhe dhoond sakein
        function save_company() {
            const form = document.getElementById('companyFormAction');
            const saveBtn = document.getElementById('save-btn');
            const progress = document.getElementById('form-progress');
            const companyName = document.getElementById('company_name').value;
            
            if(!companyName) {
                Swal.fire({ icon: 'error', title: 'Error', text: 'Company name is required!', background: '#1e1e1e', color: '#fff' });
                return;
            }

            saveBtn.disabled = true;
            progress.classList.remove('d-none');

            const formData = new FormData(form);

            fetch(form.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                    'Accept': 'application/json'
                }
            })
            .then(res => res.json())
            .then(data => {
                saveBtn.disabled = false;
                progress.classList.add('d-none');

                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: data.message,
                        background: '#1e1e1e',
                        color: '#fff',
                        showConfirmButton: false,
                        timer: 1500
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire({ icon: 'error', title: 'Oops...', text: data.message || 'Something went wrong!', background: '#1e1e1e', color: '#fff' });
                }
            })
            .catch(error => {
                saveBtn.disabled = false;
                progress.classList.add('d-none');
                Swal.fire('Error', 'Network or Server Error', 'error');
            });
        }

        function resetForm() {
            // Form check for safety
            const form = document.getElementById('companyFormAction');
            if(form) {
                form.reset();
                document.getElementById('company_id').value = '';
                document.getElementById('modalTitle').innerText = 'Add New Company';
                document.getElementById('form-progress').classList.add('none');
                document.getElementById('save-btn').disabled = false;
            }
        }

        function editCompany(id, name) {
            resetForm();
            document.getElementById('company_id').value = id;
            document.getElementById('company_name').value = name;
            document.getElementById('modalTitle').innerText = 'Edit Company';
            
            var myModal = new bootstrap.Modal(document.getElementById('companyModal'));
            myModal.show();
        }

        function deleteCompany(id) {
            Swal.fire({
                title: 'Are you sure?',
                text: "This company will be permanently deleted!",
                icon: 'warning',
                showCancelButton: true,
                background: '#1e1e1e',
                color: '#fff',
                confirmButtonColor: '#ffc107',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ url('delete-company') }}/" + id,
                        type: "GET",
                        success: function(res) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Deleted!',
                                text: res.message,
                                background: '#1e1e1e',
                                color: '#fff',
                                timer: 1000,
                                showConfirmButton: false
                            });
                            setTimeout(() => location.reload(), 1000);
                        }
                    });
                }
            });
        }
    </script>

    <style>
        .btn-action { background: #2b2b2b; color: #ffc107; border: 1px solid #444; width: 35px; height: 35px; border-radius: 8px; transition: 0.3s; }
        .edit-btn:hover { background: #ffc107; color: #000; border-color: #ffc107; }
        .delete-btn:hover { background: #dc3545; color: #fff; border-color: #dc3545; }
        .dataTables_filter input { background: #1e1e1e !important; color: #fff !important; border: 1px solid #333 !important; border-radius: 20px !important; padding: 5px 15px !important; }
        .form-control:focus { background-color: #1e1e1e; border-color: #ffc107; color: white; }
    </style>
@endsection

