@extends('backend.layouts.main')

@section('main-section')
<section class="dashboard row g-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="text-warning fw-bold">Service Master</h2>
        <button class="btn btn-warning text-dark fw-semibold" onclick="openAddModal()">
            + Add Service
        </button>
    </div>

    <div class="col-12">
        <div class="card bg-dark text-white p-3 shadow-lg">
            <h5 class="mb-3 text-warning">Registered Services</h5>
            <div class="table-responsive">
                <table id="serviceTable" class="table table-dark table-striped table-hover align-middle">
                    <thead class="text-warning">
                        <tr>
                            <th>#</th>
                            <th>Unique ID</th>
                            <th>Service Type</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody id="serviceTableBody"></tbody>
                </table>
                <div class="mt-3 text-center" id="paginationControls"></div>
            </div>
        </div>
    </div>
</section>

<!-- Add/Edit Service Modal -->
<div class="modal fade" id="addServiceModal" tabindex="-1" aria-labelledby="addServiceModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content bg-dark text-white border border-warning">
            <div class="modal-header border-warning">
                <h5 class="modal-title text-warning" id="addServiceModalLabel">Add New Service</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="addServiceForm" method="post">
                @csrf
                <div class="modal-body">
                    <input type="hidden" name="id" id="service_id">
                    <div class="mb-3">
                        <label class="form-label text-warning">Service Type</label>
                        <input type="text" name="service_type" id="service_type" class="form-control bg-secondary text-white border-0" required>
                    </div>
                </div>
                <div class="modal-footer border-warning">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" id="submitBtn" onclick="saveService()" class="btn btn-warning text-dark fw-semibold">Add Service</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
let currentPage = 1;

document.addEventListener('DOMContentLoaded', fetchServices);

// Fetch all services with pagination
function fetchServices(page = 1) {
    const url = `{{ route('service_get') }}?page=${page}`;
    fetch(url)
        .then(res => res.json())
        .then(data => {
            const tbody = document.getElementById('serviceTableBody');
            tbody.innerHTML = '';
            if (data.success && data.data.data.length > 0) {
                data.data.data.forEach((s, i) => {
                    tbody.insertAdjacentHTML('beforeend', `
                        <tr>
                            <td>${data.data.from + i}</td>
                            <td>${s.unique_id}</td>
                            <td>${s.service_type}</td>
                            <td>
                                <button class="btn btn-sm btn-warning text-dark me-1" onclick="editService(${s.id})">Edit</button>
                                <button class="btn btn-sm btn-danger text-white" onclick="deleteService(${s.id})">Delete</button>
                            </td>
                        </tr>
                    `);
                });
            } else {
                tbody.innerHTML = `<tr><td colspan="4" class="text-center text-secondary">No services found</td></tr>`;
            }
            renderPagination(data.data);
        });
}

// Render pagination
function renderPagination(pagination) {
    const container = document.getElementById('paginationControls');
    container.innerHTML = '';
    if (pagination.last_page > 1) {
        for (let i = 1; i <= pagination.last_page; i++) {
            container.insertAdjacentHTML('beforeend', `
                <button class="btn btn-sm ${i === pagination.current_page ? 'btn-warning' : 'btn-outline-warning'} me-1" onclick="fetchServices(${i})">${i}</button>
            `);
        }
    }
}

// Open Add Modal
function openAddModal() {
    document.getElementById('addServiceForm').reset();
    document.getElementById('service_id').value = '';
    document.getElementById('submitBtn').innerText = "Add Service";
    document.getElementById('addServiceModalLabel').innerText = "Add New Service";
    new bootstrap.Modal(document.getElementById('addServiceModal')).show();
}

// Save (Add or Update) service
function saveService() {
    const form = document.getElementById('addServiceForm');
    const formData = new FormData(form);
    const id = document.getElementById('service_id').value;

    let url = "";
    let method = "POST";

    if (id) {
        // Edit Mode
        url = `/service/update/${id}`; // ensure your route matches this structure
        formData.append('_method', 'PUT');
    } else {
        // Add Mode
        url = `{{ route('service_store') }}`;
    }

    fetch(url, {
        method: method,
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
            bootstrap.Modal.getInstance(document.getElementById('addServiceModal')).hide();
            fetchServices();
        }
    })
    .catch(err => console.error(err));
}

// Edit Service
function editService(id) {
    fetch(`/service/edit/${id}`)
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                const s = data.data;
                document.getElementById('service_id').value = s.id;
                document.getElementById('service_type').value = s.service_type;
                document.getElementById('submitBtn').innerText = "Update Service";
                document.getElementById('addServiceModalLabel').innerText = "Edit Service";
                new bootstrap.Modal(document.getElementById('addServiceModal')).show();
            } else {
                Swal.fire('Error', data.message, 'error');
            }
        })
        .catch(err => Swal.fire('Error', 'Failed to load service.', 'error'));
}

// Delete Service
function deleteService(id) {
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
            fetch(`/service/delete/${id}`, {
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
                if (data.success) fetchServices();
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
