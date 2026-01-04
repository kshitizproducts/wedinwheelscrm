@extends('backend.layouts.main')
@section('main-section')

<section class="dashboard row g-4">

  <!-- Topbar -->
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h2 class="text-warning fw-bold">Transactions</h2>
 
  </div>

  <!-- Transactions Table -->
  <div class="col-12">
    <div class="card bg-dark text-white p-3 shadow-lg">
      <h5 class="mb-3 text-warning">Transaction Records</h5>
      <div class="table-responsive">
        <table id="transactionTable" class="table table-dark table-striped table-hover align-middle">
          <thead class="text-warning">
            <tr>
              <th>#</th>
              <th>Customer Name</th>
              <th>Amount</th>
              <th>Payment Mode</th>
              <th>Status</th>
              <th>Date</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>1</td>
              <td>Rohit Sharma</td>
              <td>₹5000</td>
              <td>Credit Card</td>
              <td><span class="badge bg-success">Completed</span></td>
              <td>2025-10-05</td>
              <td><button class="btn btn-sm btn-warning text-dark" data-bs-toggle="modal" data-bs-target="#addTransactionModal">Update</button></td>
            </tr>
            <tr>
              <td>2</td>
              <td>Priya Nair</td>
              <td>₹12000</td>
              <td>UPI</td>
              <td><span class="badge bg-warning text-dark">Pending</span></td>
              <td>2025-10-04</td>
              <td><button class="btn btn-sm btn-warning text-dark" data-bs-toggle="modal" data-bs-target="#addTransactionModal">Update</button></td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>

</section>

<!-- Add Transaction Modal -->
<div class="modal fade" id="addTransactionModal" tabindex="-1" aria-labelledby="addTransactionModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content bg-dark text-white border border-warning">
      <div class="modal-header border-warning">
        <h5 class="modal-title text-warning" id="addTransactionModalLabel">Add New Transaction</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form id="addTransactionForm" class="p-3">
        <div class="modal-body">
          <div class="mb-3">
            <label class="form-label text-warning">Customer Name</label>
            <input type="text" class="form-control bg-secondary text-white border-0" placeholder="Enter Customer Name" required>
          </div>
          <div class="mb-3">
            <label class="form-label text-warning">Amount (₹)</label>
            <input type="number" class="form-control bg-secondary text-white border-0" placeholder="Enter Amount" required>
          </div>
          <div class="mb-3">
            <label class="form-label text-warning">Payment Mode</label>
            <select class="form-select bg-secondary text-white border-0" required>
              <option value="">-- Select Payment Mode --</option>
              <option>Credit Card</option>
              <option>Debit Card</option>
              <option>UPI</option>
              <option>Cash</option>
              <option>Net Banking</option>
            </select>
          </div>
          <div class="mb-3">
            <label class="form-label text-warning">Status</label>
            <select class="form-select bg-secondary text-white border-0">
              <option value="Completed">Completed</option>
              <option value="Pending">Pending</option>
              <option value="Failed">Failed</option>
            </select>
          </div>
          <div class="mb-3">
            <label class="form-label text-warning">Date</label>
            <input type="date" class="form-control bg-secondary text-white border-0" required>
          </div>
        </div>
        <div class="modal-footer border-warning">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-warning text-dark fw-semibold">Add Transaction</button>
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
$(document).ready(function() {

  var table = $('#transactionTable').DataTable({
    pageLength: 5,
    lengthChange: false,
    ordering: true,
    language: {
      search: "🔍 Search Transactions:",
      paginate: { previous: "⬅️", next: "➡️" }
    },
    columnDefs: [
      { orderable: false, targets: [6] }
    ]
  });

  // Add Transaction Modal Submit
  $('#addTransactionForm').submit(function(e){
    e.preventDefault();
    const inputs = $(this).find('input, select');
    const name = inputs.eq(0).val() || "Dummy Name";
    const amount = inputs.eq(1).val() || "0";
    const mode = inputs.eq(2).val() || "Cash";
    const status = inputs.eq(3).val() || "Pending";
    const date = inputs.eq(4).val() || "2025-10-05";

    table.row.add([
      table.rows().count() + 1,
      name,
      `₹${amount}`,
      mode,
      `<span class="badge ${status==="Completed"?"bg-success":status==="Pending"?"bg-warning text-dark":"bg-danger"}">${status}</span>`,
      date,
      `<button class="btn btn-sm btn-warning text-dark">Update</button>`
    ]).draw().node();

    $(this)[0].reset();
    $('#addTransactionModal').modal('hide');
    alert('✅ Transaction added successfully!');
  });

});
</script>

<style>
body { background-color: #121212; }
.card { border-radius: 15px; background-color: #1e1e1e; border: 1px solid #333; }
.btn-warning { background-color: #ffc107; border: none; }
.btn-warning:hover { background-color: #ffca2c; }
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
