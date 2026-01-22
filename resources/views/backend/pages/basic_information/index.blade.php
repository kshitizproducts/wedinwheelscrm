@extends('backend.layouts.main')

@section('main-section')
    <section class="dashboard row g-4">

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2 class="text-warning fw-bold">Basic Information</h2>
            @can('create companyprofile')
                <button class="btn btn-warning text-dark fw-semibold" onclick="openAddModal()">
                    + Add Information
                </button>
            @endcan
        </div>

        <div class="col-12">
            <div class="card bg-dark text-white p-3 shadow-lg">
                <h5 class="mb-3 text-warning">Business Records</h5>

                <div class="table-responsive">
                    <table class="table table-dark table-striped align-middle">
                        <thead class="text-warning">
                            <tr>
                                <th>#</th>
                                <th>Logo</th>
                                <th>Company</th>
                                <th>Display Name</th>
                                <th>Industry</th>
                                <th>Business Type</th>
                                <th>Year</th>
                                <th>Status</th>
                                <th width="140">Action</th>
                            </tr>
                        </thead>

                        <tbody id="businessTableBody"></tbody>
                    </table>
                </div>
            </div>
        </div>

    </section>


    <!-- MODAL -->
    <div class="modal fade" id="businessModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content bg-dark text-white border border-warning">

                <div class="modal-header border-warning">
                    <h5 class="modal-title text-warning" id="modalTitle">Add Information</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>

                <form id="businessForm" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="id" id="id">

                    <div class="modal-body">

                        <div class="mb-2">
                            <label class="text-warning">Company Name</label>
                            <input class="form-control bg-secondary text-white" name="company_name" id="company_name"
                                required>
                        </div>

                        <div class="mb-2">
                            <label class="text-warning">Display Name</label>
                            <input class="form-control bg-secondary text-white" name="display_name" id="display_name"
                                required>
                        </div>

                        <div class="mb-2">
                            <label class="text-warning">Profile Picture</label>
                            <input class="form-control bg-secondary text-white" type="file" name="profile_picture">
                        </div>

                        <div class="mb-2">
                            <label class="text-warning">Tag Line</label>
                            <input class="form-control bg-secondary text-white" name="tagline" id="tagline">
                        </div>

                        <div class="mb-2">
                            <label class="text-warning">Business Type</label>
                            <input class="form-control bg-secondary text-white" name="business_type" id="business_type">
                        </div>

                        <div class="mb-2">
                            <label class="text-warning">Industry</label>
                            <input class="form-control bg-secondary text-white" name="industry" id="industry">
                        </div>

                        <div class="mb-2">
                            <label class="text-warning">Year of Establishment</label>
                            <input class="form-control bg-secondary text-white" type="number" name="year_established"
                                id="year_established">
                        </div>

                        <div class="mb-2">
                            <label class="text-warning">Status</label>
                            <select class="form-select bg-secondary text-white" name="status" id="status">
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                            </select>
                        </div>

                    </div>

                    <div class="modal-footer border-warning">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            Cancel
                        </button>

                        <button type="button" class="btn btn-warning text-dark fw-semibold" onclick="saveBusiness()">
                            Save
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>


    <script>
        let modal;

        document.addEventListener("DOMContentLoaded", () => {
            modal = new bootstrap.Modal(document.getElementById('businessModal'));
            loadBusiness();
        });


        function loadBusiness() {
            fetch(`{{ route('basic_information_get') }}`)
                .then(r => r.json())
                .then(r => {

                    const tbody = document.getElementById('businessTableBody');
                    tbody.innerHTML = "";

                    r.data.forEach((b, i) => {

                        tbody.insertAdjacentHTML('beforeend', `
                    <tr>
                        <td>${i+1}</td>

                      <td>
    ${b.profile_picture
        ? `<img src="/${b.profile_picture}" width="45" height="45" style="object-fit:cover;border-radius:8px;">`
        : `<span class="text-muted">No Logo</span>`
    }
</td>


                        <td>${b.company_name}</td>
                        <td>${b.display_name}</td>
                        <td>${b.industry ?? '-'}</td>
                        <td>${b.business_type ?? '-'}</td>
                        <td>${b.year_established ?? '-'}</td>

                        <td>
                            <span class="badge ${b.status==1?'bg-success':'bg-danger'}">
                                ${b.status==1?'Active':'Inactive'}
                            </span>
                        </td>

                        <td>

                            @can('edit companyprofile')
                            <button class="btn btn-sm btn-warning text-dark"
                                onclick="editBusiness(${b.id})">
                                Edit
                            </button>
                            @endcan


                            @can('delete companyprofile')
                            <button class="btn btn-sm btn-danger"
                                onclick="deleteBusiness(${b.id})">
                                Delete
                            </button>
                            @endcan
                        </td>
                    </tr>
                `)
                    })
                })
        }


        function openAddModal() {
            document.getElementById('businessForm').reset();
            document.getElementById('id').value = "";
            document.getElementById('modalTitle').innerText = "Add Information";
            modal.show();
        }


        function saveBusiness() {

            let formData = new FormData(document.getElementById('businessForm'));

            fetch(`{{ route('basic_information_save') }}`, {
                    method: "POST",
                    headers: {
                        "X-CSRF-TOKEN": "{{ csrf_token() }}",
                        "Accept": "application/json"
                    },
                    body: formData
                })
                .then(async res => {

                    if (!res.ok) {
                        let error = await res.json();
                        if (error.errors) {
                            Swal.fire(Object.values(error.errors).join("<br>"), "", "error");
                        }
                        return;
                    }

                    return res.json();
                })
                .then(r => {
                    if (!r) return;

                    Swal.fire(r.message, "", r.success ? "success" : "error");

                    if (r.success) {
                        modal.hide();
                        loadBusiness();
                    }
                });
        }


        function editBusiness(id) {

            fetch(`/basic-information/edit/${id}`)
                .then(r => r.json())
                .then(r => {

                    let b = r.data;

                    document.getElementById('id').value = b.id;
                    document.getElementById('company_name').value = b.company_name;
                    document.getElementById('display_name').value = b.display_name;
                    document.getElementById('tagline').value = b.tagline ?? '';
                    document.getElementById('business_type').value = b.business_type ?? '';
                    document.getElementById('industry').value = b.industry ?? '';
                    document.getElementById('year_established').value = b.year_established ?? '';
                    document.getElementById('status').value = b.status;

                    document.getElementById('modalTitle').innerText = "Edit Information";

                    modal.show();
                })
        }


        function deleteBusiness(id) {

            Swal.fire({
                title: "Delete?",
                icon: "warning",
                showCancelButton: true
            }).then(res => {

                if (res.isConfirmed) {

                    fetch(`/basic-information/delete/${id}`, {
                            method: "DELETE",
                            headers: {
                                "X-CSRF-TOKEN": "{{ csrf_token() }}"
                            }
                        })
                        .then(r => r.json())
                        .then(r => {
                            Swal.fire(r.message, "", r.success ? "success" : "error");
                            if (r.success) loadBusiness();
                        })
                }
            })
        }
    </script>


    <style>
        body {
            background: #121212;
        }

        .card {
            border-radius: 15px;
            background: #1e1e1e;
        }
    </style>
@endsection
