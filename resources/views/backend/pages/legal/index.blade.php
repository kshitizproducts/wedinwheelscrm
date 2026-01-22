@extends('backend.layouts.main')

@section('main-section')
    <section class="dashboard row g-4">

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2 class="text-warning fw-bold">Legal & Compliance</h2>
            @can('create legal')
                <button class="btn btn-warning text-dark fw-semibold" onclick="openModal()">
                    + Add Record
                </button>
            @endcan
        </div>

        <div class="card bg-dark text-white p-3 shadow-lg">

            <table class="table table-dark table-striped align-middle">
                <thead class="text-warning">
                    <tr>
                        <th>#</th>

                        <th>GST</th>
                        <th>PAN</th>
                        <th>Trade</th>
                        <th>MSME</th>
                        <th>Rent</th>
                        <th>Electricity</th>

                        <th width="140">Action</th>
                    </tr>
                </thead>

                <tbody id="legalTableBody"></tbody>
            </table>

        </div>

    </section>


    <!-- MODAL -->
    <div class="modal fade" id="legalModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content bg-dark text-white border border-warning">

                <div class="modal-header border-warning">
                    <h5 class="modal-title text-warning" id="modalTitle">Add Legal Document</h5>
                    <button class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>

                <form id="legalForm" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="id" id="id">

                    <div class="modal-body">

                        <div class="row g-3">

                            <div class="col-md-4">
                                <label>GST Number</label>
                                <input class="form-control bg-secondary text-white" name="gst_number">
                                <input type="file" class="form-control mt-1" name="gst_file">
                                <input type="date" class="form-control mt-1" name="gst_expiry">
                            </div>

                            <div class="col-md-4">
                                <label>PAN Number</label>
                                <input class="form-control bg-secondary text-white" name="pan_number">
                                <input type="file" class="form-control mt-1" name="pan_file">
                                <input type="date" class="form-control mt-1" name="pan_expiry">
                            </div>

                            <div class="col-md-4">
                                <label>Trade License Number</label>
                                <input class="form-control bg-secondary text-white" name="trade_number">
                                <input type="file" class="form-control mt-1" name="trade_file">
                                <input type="date" class="form-control mt-1" name="trade_expiry">
                            </div>

                            <div class="col-md-4">
                                <label>MSME Number</label>
                                <input class="form-control bg-secondary text-white" name="msme_number">
                                <input type="file" class="form-control mt-1" name="msme_file">
                                <input type="date" class="form-control mt-1" name="msme_expiry">
                            </div>

                            <div class="col-md-4">
                                <label>Rent Agreement</label>
                                <input type="file" class="form-control" name="rent_agreement_file">
                                <input type="date" class="form-control mt-1" name="rent_agreement_expiry">
                            </div>

                            <div class="col-md-4">
                                <label>Electricity Bill</label>
                                <input type="file" class="form-control" name="electricity_bill_file">
                                <input type="date" class="form-control mt-1" name="electricity_bill_expiry">
                            </div>

                        </div>

                    </div>

                    <div class="modal-footer border-warning">
                        <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-warning text-dark" onclick="saveLegal()">Save</button>
                    </div>

                </form>

            </div>
        </div>
    </div>



    <script>
        let modal;

        document.addEventListener("DOMContentLoaded", () => {
            modal = new bootstrap.Modal(document.getElementById('legalModal'));
            loadLegal();
        });

        function badge(date) {
            if (!date) return `<span class="badge bg-secondary">NA</span>`;

            let today = new Date().toISOString().split("T")[0];

            return date < today ?
                `<span class="badge bg-danger">Expired</span>` :
                `<span class="badge bg-success">Valid</span>`;
        }

        function fileLink(path) {
            if (!path) return `<span class="text-muted">No File</span>`;
            return `<a href="/${path}" target="_blank" class="btn btn-sm btn-outline-info">View</a>`;
        }

        function loadLegal() {

            fetch(`{{ route('legal_get') }}`)
                .then(r => r.json())
                .then(r => {

                    const tbody = document.getElementById('legalTableBody');
                    tbody.innerHTML = "";

                    r.data.forEach((d, i) => {

                        tbody.insertAdjacentHTML('beforeend', `
                    <tr>
                        <td>${i+1}</td>

                        <td>
                            ${fileLink(d.gst_file)}<br>
                            <small>${d.gst_expiry ?? '-'}</small><br>
                            ${badge(d.gst_expiry)}
                        </td>

                        <td>
                            ${fileLink(d.pan_file)}<br>
                            <small>${d.pan_expiry ?? '-'}</small><br>
                            ${badge(d.pan_expiry)}
                        </td>

                        <td>
                            ${fileLink(d.trade_file)}<br>
                            <small>${d.trade_expiry ?? '-'}</small><br>
                            ${badge(d.trade_expiry)}
                        </td>

                        <td>
                            ${fileLink(d.msme_file)}<br>
                            <small>${d.msme_expiry ?? '-'}</small><br>
                            ${badge(d.msme_expiry)}
                        </td>

                        <td>
                            ${fileLink(d.rent_agreement_file)}<br>
                            <small>${d.rent_agreement_expiry ?? '-'}</small><br>
                            ${badge(d.rent_agreement_expiry)}
                        </td>

                        <td>
                            ${fileLink(d.electricity_bill_file)}<br>
                            <small>${d.electricity_bill_expiry ?? '-'}</small><br>
                            ${badge(d.electricity_bill_expiry)}
                        </td>

                        <td>

                            @can('edit legal')
                            <button class="btn btn-sm btn-warning text-dark" onclick="editLegal(${d.id})">
                                Edit
                            </button>
                            @endcan


                            @can('delete legal')
                            <button class="btn btn-sm btn-danger" onclick="deleteLegal(${d.id})">
                                Delete
                            </button>
                            @endcan 
                        </td>
                    </tr>
                `);
                    });

                });
        }

        function openModal() {
            document.getElementById('legalForm').reset();
            document.getElementById('id').value = "";
            document.getElementById('modalTitle').innerText = "Add Legal Document";
            modal.show();
        }

        function saveLegal() {

            let formData = new FormData(document.getElementById('legalForm'));

            fetch(`{{ route('legal_save') }}`, {
                    method: "POST",
                    headers: {
                        "X-CSRF-TOKEN": "{{ csrf_token() }}"
                    },
                    body: formData
                })
                .then(r => r.json())
                .then(r => {
                    Swal.fire(r.message, "", r.success ? "success" : "error");
                    if (r.success) {
                        modal.hide();
                        loadLegal();
                    }
                });
        }

        function editLegal(id) {

            fetch(`/legal-compliance/edit/${id}`)
                .then(r => r.json())
                .then(r => {

                    let d = r.data;

                    Object.keys(d).forEach(key => {
                        let field = document.querySelector(`[name="${key}"]`);
                        if (field && field.type !== "file") {
                            field.value = d[key] ?? '';
                        }
                    });

                    document.getElementById('id').value = d.id;
                    document.getElementById('modalTitle').innerText = "Edit Legal Document";

                    modal.show();
                });
        }

        function deleteLegal(id) {

            fetch(`/legal-compliance/delete/${id}`, {
                    method: "DELETE",
                    headers: {
                        "X-CSRF-TOKEN": "{{ csrf_token() }}"
                    }
                })
                .then(r => r.json())
                .then(r => {
                    Swal.fire(r.message, "", r.success ? "success" : "error");
                    loadLegal();
                });
        }
    </script>
@endsection
