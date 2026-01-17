@extends('backend.layouts.main')

@section('main-section')

<section class="dashboard row g-4">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="text-warning fw-bold">Financial & Banking</h2>

        <button class="btn btn-warning text-dark fw-semibold" onclick="openModal()">
            + Add Bank
        </button>
    </div>

    <div class="card bg-dark text-white p-3 shadow-lg">

        <table class="table table-dark table-striped align-middle">
            <thead class="text-warning">
                <tr>
                    <th>#</th>
                    <th>Bank</th>
                    <th>Account Holder</th>
                    <th>IFSC</th>
                    <th>Account Number</th>
                    <th>UPI ID</th>
                    <th>QR Code</th>
                    <th width="140">Action</th>
                </tr>
            </thead>

            <tbody id="bankTableBody"></tbody>
        </table>

    </div>

</section>


<!-- MODAL -->
<div class="modal fade" id="bankModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content bg-dark text-white border border-warning">

            <div class="modal-header border-warning">
                <h5 class="modal-title text-warning" id="modalTitle">Add Bank</h5>
                <button class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <form id="bankForm" enctype="multipart/form-data">
                @csrf

                <input type="hidden" name="id" id="id">

                <div class="modal-body">

                    <div class="row g-3">

                        <div class="col-md-6">
                            <label class="text-warning">Bank Name</label>
                            <input class="form-control bg-secondary text-white" name="bank_name" required>
                        </div>

                        <div class="col-md-6">
                            <label class="text-warning">Account Holder</label>
                            <input class="form-control bg-secondary text-white" name="account_holder">
                        </div>

                        <div class="col-md-4">
                            <label class="text-warning">IFSC</label>
                            <input class="form-control bg-secondary text-white" name="ifsc">
                        </div>

                        <div class="col-md-4">
                            <label class="text-warning">Account Number</label>
                            <input class="form-control bg-secondary text-white" name="account_number">
                        </div>

                        <div class="col-md-4">
                            <label class="text-warning">UPI ID</label>
                            <input class="form-control bg-secondary text-white" name="upi_id">
                        </div>

                        <div class="col-md-6">
                            <label class="text-warning">QR Code</label>
                            <input type="file" class="form-control bg-secondary text-white" name="qr_code" id="qr_code">
                        </div>

                        <div class="col-md-6 text-center">
                            <span class="text-muted">Preview</span><br>
                            <img id="qrPreview" width="120" style="display:none;border-radius:10px;margin-top:8px;">
                        </div>

                    </div>

                </div>

                <div class="modal-footer border-warning">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>

                    <button type="button"
                            class="btn btn-warning text-dark fw-semibold"
                            onclick="saveBank()">
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
    modal = new bootstrap.Modal(document.getElementById('bankModal'));
    loadBanks();

    // QR preview on choose
    document.getElementById("qr_code").addEventListener("change", function(){
        const file = this.files[0];
        if(file){
            let img = document.getElementById('qrPreview');
            img.src = URL.createObjectURL(file);
            img.style.display = "block";
        }
    });
});

function loadBanks() {
    fetch(`{{ route('financial_banking_get') }}`)
        .then(r => r.json())
        .then(r => {
            const tbody = document.getElementById('bankTableBody');
            tbody.innerHTML = "";

            r.data.forEach((b,i) => {
                tbody.insertAdjacentHTML('beforeend', `
                    <tr>
                        <td>${i+1}</td>
                        <td>${b.bank_name}</td>
                        <td>${b.account_holder ?? '-'}</td>
                        <td>${b.ifsc ?? '-'}</td>
                        <td>${b.account_number ?? '-'}</td>
                        <td>${b.upi_id ?? '-'}</td>

                        <td>
                            ${b.qr_code
                                ? `<img src="/${b.qr_code}" width="55" style="border-radius:8px;">`
                                : '<span class="text-muted">No QR</span>'}
                        </td>

                        <td>
                            <button class="btn btn-sm btn-warning text-dark"
                                onclick="editBank(${b.id})">Edit</button>

                            <button class="btn btn-sm btn-danger"
                                onclick="deleteBank(${b.id})">Delete</button>
                        </td>
                    </tr>
                `)
            })
        })
        .catch(()=> Swal.fire("Error", "Failed to load data", "error"))
}

function openModal() {
    document.getElementById('bankForm').reset();
    document.getElementById('qrPreview').style.display = "none";
    document.getElementById('id').value = "";
    document.getElementById('modalTitle').innerText = "Add Bank";
    modal.show();
}

function saveBank() {
    let formData = new FormData(document.getElementById('bankForm'));

    fetch(`{{ route('financial_banking_save') }}`, {
        method: "POST",
        headers: { "X-CSRF-TOKEN": "{{ csrf_token() }}" },
        body: formData
    })
    .then(r => r.json())
    .then(r => {
        Swal.fire(r.message ?? "Done", "", r.success ? "success" : "error");
        if (r.success) {
            modal.hide();
            loadBanks();
        }
    })
    .catch(()=> Swal.fire("Error", "Save request failed", "error"))
}

/**
 * ✅ EDIT FIX:
 * - correct url
 * - response parse fix
 * - modal always open after data filled
 */
function editBank(id) {
    let url = `{{ url('/financial_banking/edit') }}/${id}`;

    fetch(url)
        .then(async res => {
            let data = await res.json().catch(()=>null);

            if(!res.ok || !data){
                Swal.fire("API Error", `Edit API failed (${res.status})`, "error");
                console.log("Edit API failed:", res.status, data);
                return;
            }

            if(!data.success || !data.data){
                Swal.fire("Not Found", data.message ?? "Record not found", "error");
                console.log("Invalid edit response:", data);
                return;
            }

            const b = data.data;

            // form reset first
            document.getElementById('bankForm').reset();

            // fill inputs
            document.querySelector('[name="bank_name"]').value       = b.bank_name ?? '';
            document.querySelector('[name="account_holder"]').value  = b.account_holder ?? '';
            document.querySelector('[name="ifsc"]').value            = b.ifsc ?? '';
            document.querySelector('[name="account_number"]').value  = b.account_number ?? '';
            document.querySelector('[name="upi_id"]').value          = b.upi_id ?? '';
            document.getElementById('id').value                      = b.id ?? '';

            // qr preview only
            if (b.qr_code) {
                let img = document.getElementById('qrPreview');
                img.src = "/" + b.qr_code;
                img.style.display = "block";
            } else {
                document.getElementById('qrPreview').style.display = "none";
            }

            document.getElementById('modalTitle').innerText = "Edit Bank";

            // ✅ ensure modal open
            modal.show();
        })
        .catch((e)=>{
            console.log("Edit fetch error:", e);
            Swal.fire("Something went wrong", "Edit request failed", "error");
        });
}

/**
 * ✅ DELETE CONFIRMATION
 */
function deleteBank(id) {

    Swal.fire({
        title: "Are you sure?",
        text: "This record will be deleted permanently!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#d33",
        cancelButtonColor: "#6c757d",
        confirmButtonText: "Yes, Delete",
        cancelButtonText: "Cancel",
    }).then((result) => {

        if(!result.isConfirmed) return;

        let url = `{{ url('/financial_banking/delete') }}/${id}`;

        fetch(url, {
            method: "DELETE",
            headers: { "X-CSRF-TOKEN": "{{ csrf_token() }}" }
        })
        .then(r => r.json())
        .then(r => {
            Swal.fire(r.message ?? "Deleted", "", r.success ? "success" : "error");
            if(r.success) loadBanks();
        })
        .catch(()=> Swal.fire("Error", "Delete request failed", "error"));

    });
}
</script>

@endsection
