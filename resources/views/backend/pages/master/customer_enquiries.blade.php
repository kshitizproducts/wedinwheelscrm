@extends('backend.layouts.main')
@section('main-section')

    <section class="dashboard row g-4">

        <!-- Topbar -->
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2 class="text-warning fw-bold">Customer Enquiries  (UI Only)</h2>

        </div>

        <!-- Enquiries Table -->
        <div class="col-12">
            <div class="card bg-dark text-white p-3 shadow-lg">
                <h5 class="mb-3 text-warning">Customer Enquiries</h5>
                <div class="table-responsive">
                    <table id="enquiryTable" class="table table-dark table-striped table-hover align-middle">
                        <thead class="text-warning">
                            <tr>
                                <th>#</th>
                                <th>Customer Name</th>
                                <th>Email</th>
                                <th>Contact</th>
                                <th>Query</th>
                                <th>Status</th>
                                <th>Email</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>1</td>
                                <td>Rohit Sharma</td>
                                <td>rohit@example.com</td>
                                <td>+91 9876543210</td>
                                <td>Interested in product demo</td>
                                <td><span class="badge bg-success">New</span></td>
                               
                                <td>
                                    <button class="btn btn-warning text-dark fw-semibold" data-bs-toggle="modal"
                                        data-bs-target="#addEnquiryModal">
                                        Email
                                    </button>
                                </td>
                                <td><button class="btn btn-sm btn-warning text-dark">Update</button></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </section>

    <!-- Add Enquiry Modal -->
    <div class="modal fade" id="addEnquiryModal" tabindex="-1" aria-labelledby="addEnquiryModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content bg-dark text-white border border-warning">
                <div class="modal-header border-warning">
                    <h5 class="modal-title text-warning" id="addEnquiryModalLabel">Add Customer Enquiry</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <form id="addEnquiryForm" class="p-3">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label text-warning">Customer Name</label>
                            <input type="text" class="form-control bg-secondary text-white border-0"
                                placeholder="Enter Customer Name" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-warning">Email</label>
                            <input type="email" class="form-control bg-secondary text-white border-0"
                                placeholder="Enter Email" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-warning">Contact</label>
                            <input type="text" class="form-control bg-secondary text-white border-0"
                                placeholder="Enter Contact Number" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-warning">Query</label>
                            <textarea class="form-control bg-secondary text-white border-0"
                                placeholder="Enter customer query" rows="3" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-warning">Status</label>
                            <select class="form-select bg-secondary text-white border-0">
                                <option value="New">New</option>
                                <option value="In Progress">In Progress</option>
                                <option value="Resolved">Resolved</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer border-warning">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-warning text-dark fw-semibold">Add Enquiry</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@section('scripts')

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">

    <script>
        $(document).ready(function () {

            var table = $('#enquiryTable').DataTable({
                pageLength: 5,
                lengthChange: false,
                ordering: true,
                language: {
                    search: "🔍 Search Enquiry:",
                    paginate: { previous: "⬅️", next: "➡️" }
                },
                columnDefs: [
                    { orderable: false, targets: [6, 7] }
                ]
            });

            // Assign alert
            $('#enquiryTable').on('change', '.assign-user', function () {
                const name = $(this).closest('tr').find('td:eq(1)').text();
                const user = $(this).val();
                if (user) alert(`✅ Enquiry "${name}" assigned to ${user}!`);
            });

            // Add Enquiry Modal Submit
            $('#addEnquiryForm').submit(function (e) {
                e.preventDefault();
                const inputs = $(this).find('input, textarea, select');
                const name = inputs.eq(0).val() || "Dummy Name";
                const email = inputs.eq(1).val() || "dummy@example.com";
                const contact = inputs.eq(2).val() || "+91 0000000000";
                const query = inputs.eq(3).val() || "Sample query";
                const status = inputs.eq(4).val() || "New";

                // Add row
                table.row.add([
                    table.rows().count() + 1,
                    name,
                    email,
                    contact,
                    query,
                    `<span class="badge bg-success">${status}</span>`,
                    `<select class="form-select bg-secondary text-white border-0 assign-user">
              <option value="">-- Select User --</option>
              <option>Kshitiz Kumar</option>
              <option>Rahul Verma</option>
              <option>Awantika Sharma</option>
              <option>Priya Nair</option>
           </select>`,
                    `<button class="btn btn-sm btn-warning text-dark">Update</button>`
                ]).draw().node();

                $(this)[0].reset();
                $('#addEnquiryModal').modal('hide');
                alert('✅ Enquiry added successfully!');
            });

        });
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