@extends('backend.layouts.main')

@section('main-section')
<section class="dashboard row g-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="text-warning fw-bold">Accounts Management</h2>
        <button class="btn btn-warning text-dark fw-semibold" data-bs-toggle="modal" data-bs-target="#addAccountModal">
            + Add Account
        </button>
    </div>

    <div class="col-12">
        <div class="card bg-dark text-white p-3 shadow-lg">
            <h5 class="mb-3 text-warning">My Accounts</h5>
            <div class="table-responsive">
                <table id="accountTable" class="table table-dark table-striped table-hover align-middle">
                    <thead class="text-warning">
                        <tr>
                            <th>#</th>
                            <th>Bank</th>
                            <th>Beneficiary</th>
                            <th>Account No.</th>
                            <th>IFSC</th>
                            <th>Branch</th>
                            <th>Contact</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody id="accountTableBody"></tbody>
                </table>
                <div class="mt-3 text-center" id="paginationControls"></div>
            </div>
        </div>
    </div>
</section>

<!-- Add/Edit Account Modal -->
<div class="modal fade" id="addAccountModal" tabindex="-1" aria-labelledby="addAccountModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content bg-dark text-white border border-warning">
            <div class="modal-header border-warning">
                <h5 class="modal-title text-warning" id="addAccountModalLabel">Add New Account</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="addAccountForm" method="post" action="{{ url('accounts') }}">
                @csrf
                <div class="modal-body">
                    <input type="hidden" name="account_id" id="account_id">
                    <div class="mb-3">
                        <label class="form-label text-warning">Bank Name</label>
                        <input type="text" name="bank_name" id="bank_name" class="form-control bg-secondary text-white border-0" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-warning">Beneficiary Name</label>
                        <input type="text" name="beneficiary_name" id="beneficiary_name" class="form-control bg-secondary text-white border-0" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-warning">Account Number</label>
                        <input type="text" name="account_number" id="account_number" class="form-control bg-secondary text-white border-0" required>
                        <div class="form-text text-muted">Account number will be encrypted in the database.</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-warning">IFSC</label>
                        <input type="text" name="ifsc" id="ifsc" class="form-control bg-secondary text-white border-0" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-warning">Branch</label>
                        <input type="text" name="branch" id="branch" class="form-control bg-secondary text-white border-0">
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-warning">Contact Email / Phone</label>
                        <input type="text" name="contact" id="contact" class="form-control bg-secondary text-white border-0">
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
                    <button type="submit" id="submitBtn" onclick="()" class="btn btn-warning text-dark fw-semibold">Add Account</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
let currentPage = 1;
let isEditing = false;

document.addEventListener('DOMContentLoaded', () => fetchAccounts());

function fetchAccounts(page = 1) {
    const url = `{{ url('accounts/get') }}?page=${page}`;
    fetch(url)
        .then(res => res.json())
        .then(data => {
            const tbody = document.getElementById('accountTableBody');
            tbody.innerHTML = '';
            if (data.success && data.data.data.length > 0) {
              data.data.data.forEach((a, i) => {
    // truncate IFSC to max 8 chars (or first 4 + last 3)
    let truncatedIFSC = a.ifsc.length > 8 ? a.ifsc.slice(0, 4) + '…' + a.ifsc.slice(-3) : a.ifsc;

    tbody.insertAdjacentHTML('beforeend', `
        <tr>
            <td>${data.data.from + i}</td>
            <td>${a.bank_name}</td>
            <td>${a.beneficiary_name}</td>
            <td>${a.masked_account_number}</td>
            <td title="${a.ifsc}">${truncatedIFSC}</td>
            <td>${a.branch ?? ''}</td>
            <td>${a.contact ?? ''}</td>
            <td><span class="badge ${a.status == 1 ? 'bg-success' : 'bg-danger'}">${a.status == 1 ? 'Active' : 'Inactive'}</span></td>
            <td>
                <button class="btn btn-sm btn-warning text-dark me-1" onclick="editAccount(${a.id})">Edit</button>
                <button class="btn btn-sm btn-danger text-white" onclick="deleteAccount(${a.id})">Delete</button>
            </td>
        </tr>
    `);
});

            } else {
                tbody.innerHTML = `<tr><td colspan="9" class="text-center text-secondary">No accounts found</td></tr>`;
            }
            renderPagination(data.data);
        });
}

function renderPagination(pagination) {
    const container = document.getElementById('paginationControls');
    container.innerHTML = '';
    if (pagination && pagination.last_page > 1) {
        for (let i = 1; i <= pagination.last_page; i++) {
            container.insertAdjacentHTML('beforeend', `
                <button class="btn btn-sm ${i === pagination.current_page ? 'btn-warning' : 'btn-outline-warning'} me-1" onclick="fetchAccounts(${i})">${i}</button>
            `);
        }
    }
}

function addAccount() {
    const form = document.getElementById('addAccountForm');
    const formData = new FormData(form);
    const url = isEditing ? `{{ url('accounts/update') }}` : form.action;

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
            bootstrap.Modal.getInstance(document.getElementById('addAccountModal')).hide();
            isEditing = false;
            document.getElementById('submitBtn').innerText = "Add Account";
            document.getElementById('addAccountModalLabel').innerText = "Add New Account";
            fetchAccounts();
        }
    });
}

function editAccount(id) {
    fetch(`/accounts/${id}/edit`)
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                const a = data.data;
                document.getElementById('account_id').value = a.id;
                document.getElementById('bank_name').value = a.bank_name;
                document.getElementById('beneficiary_name').value = a.beneficiary_name;
                // account_number is decrypted server-side and returned; careful with permissions
                document.getElementById('account_number').value = a.account_number;
                document.getElementById('ifsc').value = a.ifsc;
                document.getElementById('branch').value = a.branch;
                document.getElementById('contact').value = a.contact;
                document.getElementById('status').value = a.status;

                isEditing = true;
                document.getElementById('submitBtn').innerText = "Update Account";
                document.getElementById('addAccountModalLabel').innerText = "Edit Account";
                new bootstrap.Modal(document.getElementById('addAccountModal')).show();
            } else {
                Swal.fire('Error', data.message, 'error');
            }
        });
}

function deleteAccount(id) {
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
            fetch(`/accounts/${id}`, {
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
                if (data.success) fetchAccounts();
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

<script src="{{ asset('backend/js/bootstrap.bundle.min.js') }}"></script>
@endsection
