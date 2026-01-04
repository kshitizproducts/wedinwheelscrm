@extends('backend.layouts.main')

@section('main-section')

<section class="dashboard row g-4">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="text-warning fw-bold">Address & Contacts</h2>

        <button class="btn btn-warning text-dark fw-semibold" onclick="openModal()">
            + Add Branch
        </button>
    </div>

    <div class="card bg-dark text-white p-3 shadow-lg">

        <table class="table table-dark table-striped align-middle">
            <thead class="text-warning">
                <tr>
                    <th>#</th>
                    <th>Type</th>
                    <th>City</th>
                    <th>State</th>
                    <th>Pincode</th>
                    <th>Phone</th>
                    <th>WhatsApp</th>
                    <th>Email</th>
                    <th>Website</th>
                    <th>Google Business</th>
                    <th width="130">Action</th>
                </tr>
            </thead>

            <tbody id="addressTableBody"></tbody>
        </table>

    </div>

</section>


<!-- MODAL -->
<div class="modal fade" id="addressModal" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content bg-dark text-white border border-warning">

            <div class="modal-header border-warning">
                <h5 class="modal-title text-warning" id="modalTitle">Add Branch</h5>
                <button class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <form id="addressForm">
                @csrf
                <input type="hidden" name="id" id="id">

                <div class="modal-body">

                    <div class="mb-3 pb-2 border-bottom border-secondary">
                        <h6 class="text-warning mb-1">Branch Details</h6>
                        <small class="text-muted">Basic location details</small>
                    </div>

                    <div class="row g-3">

                        <div class="col-md-3">
                            <label class="text-warning">Is Head Office?</label>
                            <select class="form-select bg-secondary text-white" name="is_head_office">
                                <option value="0">No</option>
                                <option value="1">Yes</option>
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label class="text-warning">City</label>
                            <input class="form-control bg-secondary text-white"
                                   name="city" placeholder="e.g. Ranchi" required>
                        </div>

                        <div class="col-md-3">
                            <label class="text-warning">State</label>
                            <input class="form-control bg-secondary text-white"
                                   name="state" placeholder="e.g. Jharkhand" required>
                        </div>

                        <div class="col-md-3">
                            <label class="text-warning">Pincode</label>
                            <input class="form-control bg-secondary text-white"
                                   name="pincode" placeholder="e.g. 834001">
                        </div>

                    </div>


                    <div class="mt-4 mb-3 pb-2 border-bottom border-secondary">
                        <h6 class="text-warning mb-1">Contact Information</h6>
                        <small class="text-muted">Phone / WhatsApp / Email</small>
                    </div>

                    <div class="row g-3">

                        <div class="col-md-4">
                            <label class="text-warning">WhatsApp Number</label>
                            <input class="form-control bg-secondary text-white"
                                   id="whatsapp" name="whatsapp"
                                   placeholder="+91XXXXXXXXXX">
                        </div>

                        <div class="col-md-4">
                            <label class="text-warning d-flex justify-content-between align-items-center">
                                <span>Phone Number</span>

                                <span class="ms-2">
                                    <input type="checkbox" id="same_as_whatsapp">
                                    <small>Same as WhatsApp</small>
                                </span>
                            </label>

                            <input class="form-control bg-secondary text-white"
                                   id="phone" name="phone" placeholder="+91XXXXXXXXXX">
                        </div>

                        <div class="col-md-4">
                            <label class="text-warning">Email</label>
                            <input class="form-control bg-secondary text-white"
                                   name="email" placeholder="example@email.com">
                        </div>

                    </div>


                    <div class="mt-4 mb-3 pb-2 border-bottom border-secondary">
                        <h6 class="text-warning mb-1">Online Presence</h6>
                        <small class="text-muted">Business links</small>
                    </div>

                    <div class="row g-3">

                        <div class="col-md-6">
                            <label class="text-warning">Website URL</label>
                            <input class="form-control bg-secondary text-white"
                                   name="website_url" placeholder="https://yourwebsite.com">
                        </div>

                        <div class="col-md-6">
                            <label class="text-warning">Google Business Profile URL</label>
                            <input class="form-control bg-secondary text-white"
                                   name="google_business_url" placeholder="https://g.page/...">
                        </div>

                    </div>

                </div>

                <div class="modal-footer border-warning">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">
                        Cancel
                    </button>

                    <button type="button"
                            class="btn btn-warning text-dark fw-semibold"
                            onclick="saveAddress()">
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
    modal = new bootstrap.Modal(document.getElementById('addressModal'));
    loadAddresses();
});

function loadAddresses() {

    fetch(`{{ route('address_contacts_get') }}`)
        .then(r => r.json())
        .then(r => {

            const tbody = document.getElementById('addressTableBody');
            tbody.innerHTML = "";

            r.data.forEach((row, i) => {

                tbody.insertAdjacentHTML('beforeend', `
                    <tr>
                        <td>${i+1}</td>

                        <td>
                            ${row.is_head_office == 1 
                                ? '<span class="badge bg-success">Head Office</span>' 
                                : '<span class="badge bg-primary">Branch</span>'}
                        </td>

                        <td>${row.city ?? '-'}</td>
                        <td>${row.state ?? '-'}</td>
                        <td>${row.pincode ?? '-'}</td>

                        <td>${row.phone ?? '-'}</td>
                        <td>${row.whatsapp ?? '-'}</td>

                        <td>
                            <small>${row.email ?? '-'}</small>
                        </td>

                        <td>
                            ${row.website_url
                                ? `<a href="${row.website_url}" target="_blank">Visit</a>`
                                : '—'}
                        </td>

                        <td>
                            ${row.google_business_url
                                ? `<a href="${row.google_business_url}" target="_blank">Open</a>`
                                : '—'}
                        </td>

                        <td>
                            <button class="btn btn-sm btn-warning text-dark" onclick="editAddress(${row.id})">
                                Edit
                            </button>

                            <button class="btn btn-sm btn-danger" onclick="deleteAddress(${row.id})">
                                Delete
                            </button>
                        </td>
                    </tr>
                `)
            })
        })
}


function openModal() {
    document.getElementById('addressForm').reset();
    document.getElementById('id').value = "";
    document.getElementById('modalTitle').innerText = "Add Branch";

    sameCheckbox.checked = false;
    phoneInput.removeAttribute('readonly');
    phoneInput.value = "";

    modal.show();
}


function saveAddress() {

    let formData = new FormData(document.getElementById('addressForm'));

    fetch(`{{ route('address_contacts_save') }}`, {
        method: "POST",
        headers: { "X-CSRF-TOKEN": "{{ csrf_token() }}" },
        body: formData
    })
    .then(r => r.json())
    .then(r => {
        Swal.fire(r.message, "", r.success ? "success" : "error");
        if (r.success) {
            modal.hide();
            loadAddresses();
        }
    });
}


function editAddress(id) {

    fetch(`/address_contacts/edit/${id}`)
        .then(r => r.json())
        .then(r => {

            let d = r.data;

            Object.keys(d).forEach(key => {
                let field = document.querySelector(`[name="${key}"]`);
                if (field) field.value = d[key] ?? '';
            });

            document.getElementById('id').value = d.id;
            document.getElementById('modalTitle').innerText = "Edit Branch";

            sameCheckbox.checked = false;
            phoneInput.removeAttribute('readonly');

            modal.show();
        });
}


function deleteAddress(id) {

    fetch(`/address_contacts/delete/${id}`, {
        method: "DELETE",
        headers: { "X-CSRF-TOKEN": "{{ csrf_token() }}" }
    })
    .then(r => r.json())
    .then(r => {
        Swal.fire(r.message, "", r.success ? "success" : "error");
        loadAddresses();
    });
}


// SAME AS WHATSAPP FEATURE
const phoneInput = document.getElementById('phone');
const whatsappInput = document.getElementById('whatsapp');
const sameCheckbox = document.getElementById('same_as_whatsapp');

sameCheckbox?.addEventListener('change', () => {
    if (sameCheckbox.checked) {
        phoneInput.value = whatsappInput.value;
        phoneInput.setAttribute('readonly', true);
    } else {
        phoneInput.value = '';
        phoneInput.removeAttribute('readonly');
    }
});

whatsappInput?.addEventListener('input', () => {
    if (sameCheckbox.checked) {
        phoneInput.value = whatsappInput.value;
    }
});
</script>

@endsection
