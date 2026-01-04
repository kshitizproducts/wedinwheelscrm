@extends('backend.layouts.main')
@section('main-section')

<section class="dashboard row g-4">

  <!-- Topbar -->
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h2 class="text-warning fw-bold">Invoices</h2>
    <button class="btn btn-warning text-dark fw-semibold" data-bs-toggle="modal" data-bs-target="#addInvoiceModal">
      + Create Invoice
    </button>
  </div>

  <!-- Clients & Invoices Table -->
  <div class="col-12">
    <div class="card bg-dark text-white p-3 shadow-lg">
      <h5 class="mb-3 text-warning">Client Invoices</h5>
      <div class="table-responsive">
        <table id="invoiceTable" class="table table-dark table-striped table-hover align-middle">
          <thead class="text-warning">
            <tr>
              <th>#</th>
              <th>Client Name</th>
              <th>Invoice No.</th>
              <th>Amount</th>
              <th>Status</th>
              <th>Date</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>1</td>
              <td>Rohit Sharma</td>
              <td>INV-001</td>
              <td>₹5000</td>
              <td><span class="badge bg-success">Paid</span></td>
              <td>2025-10-05</td>
              <td>
                <button class="btn btn-sm btn-info text-dark me-1" data-bs-toggle="modal" data-bs-target="#sendInvoiceModal">Send</button>
                <a href="{{ url('/dummy-invoice') }}" class="btn btn-sm btn-warning text-dark">Download</a>
              </td>
            </tr>
            <tr>
              <td>2</td>
              <td>Priya Nair</td>
              <td>INV-002</td>
              <td>₹12000</td>
              <td><span class="badge bg-warning text-dark">Pending</span></td>
              <td>2025-10-04</td>
              <td>
                <button class="btn btn-sm btn-info text-dark me-1" data-bs-toggle="modal" data-bs-target="#sendInvoiceModal">Send</button>
                <a href="{{ url('/dummy-invoice') }}" class="btn btn-sm btn-warning text-dark">Download</a>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>

</section>

<!-- Send Invoice Modal -->
<div class="modal fade" id="sendInvoiceModal" tabindex="-1" aria-labelledby="sendInvoiceModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content bg-dark text-white border border-warning">
      <div class="modal-header border-warning">
        <h5 class="modal-title text-warning" id="sendInvoiceModalLabel">Send Invoice</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body p-3">
        <h6 class="text-warning mb-3">Invoice Preview</h6>
        <div class="border p-3 bg-secondary rounded mb-3">
          <p><strong>Invoice No:</strong> INV-001</p>
          <p><strong>Client Name:</strong> Rohit Sharma</p>
          <p><strong>Amount:</strong> ₹5000</p>
          <p><strong>Date:</strong> 2025-10-05</p>
          <hr>
          <p>Thank you for your business. Please make the payment at the earliest.</p>
        </div>

        <div class="d-flex flex-column flex-md-row gap-2">
          <button class="btn btn-warning text-dark flex-fill">Send via Email</button>
          <button class="btn btn-success text-dark flex-fill">Send via WhatsApp</button>
        </div>
      </div>
      <div class="modal-footer border-warning">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
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
$(document).ready(function() {
  $('#invoiceTable').DataTable({
    pageLength: 5,
    lengthChange: false,
    ordering: true,
    language: {
      search: "🔍 Search Client:",
      paginate: { previous: "⬅️", next: "➡️" }
    },
    columnDefs: [{ orderable: false, targets: [6] }]
  });
});
</script>

<style>
body { background-color: #121212; }
.card { border-radius: 15px; background-color: #1e1e1e; border: 1px solid #333; }
.btn-warning { background-color: #ffc107; border: none; }
.btn-warning:hover { background-color: #ffca2c; }
.btn-info { background-color: #17a2b8; border: none; }
.btn-info:hover { background-color: #20c0d0; }
.btn-success { background-color: #28a745; border: none; }
.btn-success:hover { background-color: #3ed35e; }
.dataTables_filter input {
  background-color: #1e1e1e !important; border: 1px solid #444 !important; color: #fff !important;
  border-radius: 8px; padding: 6px 10px;
}
.dataTables_filter label { color: #ffc107 !important; font-weight: 600; }
.paginate_button { color: #ffc107 !important; }
table.dataTable tbody tr:hover { background-color: #2b2b2b; }
.badge { font-size: 0.85rem; }
.modal-content { border-radius: 10px; }
</style>

@endsection
