@extends('backend.layouts.main')

@section('main-section')
<section class="dashboard row g-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="text-warning fw-bold">Driver Task</h2>
        <button class="btn btn-warning text-dark fw-semibold" data-bs-toggle="modal" data-bs-target="#addGarageModaltoaddnew">
            + Add Garage
        </button>
    </div>

    <div class="col-12">
        <div class="card bg-dark text-white p-3 shadow-lg">
            <h5 class="mb-3 text-warning">Registered Garages</h5>
            <div class="table-responsive">
                <table id="garageTable" class="table table-dark table-striped table-hover align-middle">
                    <thead class="text-warning">
                        <tr>
                            <th>#</th>
                            <th>Garage Name</th>
                            <th>Location</th>
                            <th>Contact</th>
                            <th>Email</th>
                            <th>Manager</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody id="garageTableBody"></tbody>
                </table>
                <div class="mt-3 text-center" id="paginationControls"></div>
            </div>
        </div>
    </div>
</section>

<!-- Add/Edit Garage Modal -->
<div class="modal fade" id="addGarageModaltoaddnew" tabindex="-1" aria-labelledby="addGarageModaltoaddnewLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content bg-dark text-white border border-warning">
            <div class="modal-header border-warning">
                <h5 class="modal-title text-warning" id="addGarageModaltoaddnewLabel">Add New Garage</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="addGarageForm" method="post" action="{{ url('add_new_garage') }}">
                @csrf
                <div class="modal-body">
                    <input type="hidden" name="garage_id" id="garage_id">
                    <div class="mb-3">
                        <label class="form-label text-warning">Garage Name</label>
                        <input type="text" name="garage_name" id="garage_name" class="form-control bg-secondary text-white border-0" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-warning">Location</label>
                        <input type="text" name="location" id="location" class="form-control bg-secondary text-white border-0" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-warning">Mobile</label>
                        <input type="number" name="mobile" id="mobile" class="form-control bg-secondary text-white border-0" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-warning">Email</label>
                        <input type="email" name="email" id="email" class="form-control bg-secondary text-white border-0" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-warning">Google Map Navigation</label>
                        <input type="text" name="navigation" id="navigation" class="form-control bg-secondary text-white border-0" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-warning">Manager Name</label>
                        <input type="text" name="manager" id="manager" class="form-control bg-secondary text-white border-0" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-warning">Status</label>
                        <select name="status" id="status" class="form-select bg-secondary text-white border-0" required>
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer border-warning">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" id="submitBtn" onclick="addGarage()" class="btn btn-warning text-dark fw-semibold">Add Garage</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
let currentPage = 1;
let isEditing = false;

document.addEventListener('DOMContentLoaded', fetchGarages);

function fetchGarages(page = 1) {
    const url = `{{ route('garage_get') }}?page=${page}`;
    fetch(url)
        .then(res => res.json())
        .then(data => {
            const tbody = document.getElementById('garageTableBody');
            tbody.innerHTML = '';
            if (data.success && data.data.data.length > 0) {
                data.data.data.forEach((g, i) => {
                    tbody.insertAdjacentHTML('beforeend', `
                        <tr>
                            <td>${data.data.from + i}</td>
                            <td>${g.name}</td>
                            <td>${g.location}</td>
                            <td>${g.mobile}</td>
                            <td>${g.mail}</td>
                            <td>${g.manager}</td>
                            <td><span class="badge ${g.status == 1 ? 'bg-success' : 'bg-danger'}">${g.status == 1 ? 'Active' : 'Inactive'}</span></td>
                            <td>
                                <button class="btn btn-sm btn-warning text-dark me-1" onclick="editGarage(${g.id})">Edit</button>
                                <button class="btn btn-sm btn-danger text-white" onclick="deleteGarage(${g.id})">Delete</button>
                            </td>
                        </tr>
                    `);
                });
            } else {
                tbody.innerHTML = `<tr><td colspan="8" class="text-center text-secondary">No garages found</td></tr>`;
            }
            renderPagination(data.data);
        });
}

function renderPagination(pagination) {
    const container = document.getElementById('paginationControls');
    container.innerHTML = '';
    if (pagination.last_page > 1) {
        for (let i = 1; i <= pagination.last_page; i++) {
            container.insertAdjacentHTML('beforeend', `
                <button class="btn btn-sm ${i === pagination.current_page ? 'btn-warning' : 'btn-outline-warning'} me-1" onclick="fetchGarages(${i})">${i}</button>
            `);
        }
    }
}

function addGarage() {
    const form = document.getElementById('addGarageForm');
    const formData = new FormData(form);
    const url = isEditing ? "{{ route('garage_update') }}" : form.action;

    fetch(url, {
        method: 'POST',
        body: formData,
        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
    })
    .then(res => res.json())
    .then(data => {
        Swal.fire({
            icon: data.success ? 'success' : 'error',
            title: data.message,
            timer: 1500,
            showConfirmButton: false
        });
        if (data.success) {
            form.reset();
            bootstrap.Modal.getInstance(document.getElementById('addGarageModaltoaddnew')).hide();
            isEditing = false;
            document.getElementById('submitBtn').innerText = "Add Garage";
            document.getElementById('addGarageModaltoaddnewLabel').innerText = "Add New Garage";
            fetchGarages();
        }
    });
}

function editGarage(id) {
    fetch(`/garage/edit/${id}`)
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                const g = data.data;
                document.getElementById('garage_id').value = g.id;
                document.getElementById('garage_name').value = g.name;
                document.getElementById('location').value = g.location;
                document.getElementById('mobile').value = g.mobile;
                document.getElementById('email').value = g.mail;
                document.getElementById('navigation').value = g.navigation;
                document.getElementById('manager').value = g.manager;
                document.getElementById('status').value = g.status;

                isEditing = true;
                document.getElementById('submitBtn').innerText = "Update Garage";
                document.getElementById('addGarageModaltoaddnewLabel').innerText = "Edit Garage";
                new bootstrap.Modal(document.getElementById('addGarageModaltoaddnew')).show();
            } else {
                Swal.fire('Error', data.message, 'error');
            }
        });
}

function deleteGarage(id) {
    Swal.fire({
        title: 'Are you sure?',
        text: 'This action cannot be undone!',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Yes, delete it!'
    }).then(result => {
        if (result.isConfirmed) {
            fetch(`/garage/delete/${id}`, {
                method: 'DELETE',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
            })
            .then(res => res.json())
            .then(data => {
                Swal.fire({
                    icon: data.success ? 'success' : 'error',
                    title: data.message,
                    timer: 1500,
                    showConfirmButton: false
                });
                if (data.success) fetchGarages();
            });
        }
    });
}
</script>

<style>
body { background-color: #121212; }
.card { border-radius: 15px; background-color: #1e1e1e; border: 1px solid #333; }
.btn-warning { background-color: #ffc107; border: none; }
.btn-warning:hover { background-color: #ffca2c; }
table.dataTable tbody tr:hover { background-color: #2b2b2b; }
.badge { font-size: 0.85rem; }
.modal-content { border-radius: 10px; }
</style>
@endsection
