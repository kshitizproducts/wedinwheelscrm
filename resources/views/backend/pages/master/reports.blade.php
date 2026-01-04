@extends('backend.layouts.main')
@section('main-section')

<section class="dashboard row g-4">

  <!-- Topbar -->
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h2 class="text-warning fw-bold">Reports & Statistics</h2>
  </div>

  <!-- Filters Section -->
  <div class="col-12 mb-3">
    <div class="card bg-dark text-white p-3 shadow-lg">
      <h5 class="mb-3 text-warning">Filters</h5>
      <form id="reportFilters" class="row g-3">
        <div class="col-md-3">
          <label class="form-label">Start Date</label>
          <input type="date" class="form-control bg-secondary text-white border-0" id="startDate">
        </div>
        <div class="col-md-3">
          <label class="form-label">End Date</label>
          <input type="date" class="form-control bg-secondary text-white border-0" id="endDate">
        </div>
        <div class="col-md-3">
          <label class="form-label">Payment Status</label>
          <select class="form-control bg-secondary text-white border-0" id="paymentStatus">
            <option value="">All</option>
            <option value="paid">Paid</option>
            <option value="pending">Pending</option>
          </select>
        </div>
        <div class="col-md-3 d-flex align-items-end">
          <button type="button" class="btn btn-warning text-dark w-100" id="applyFilters">Apply Filters</button>
        </div>
      </form>
    </div>
  </div>

  <!-- Summary Stats -->
  <div class="col-12">
    <div class="row g-4">
      <div class="col-md-3">
        <div class="card bg-dark text-white p-3 shadow-lg">
          <h6 class="text-warning">Total Income</h6>
          <h3 id="totalIncome">₹0</h3>
        </div>
      </div>
      <div class="col-md-3">
        <div class="card bg-dark text-white p-3 shadow-lg">
          <h6 class="text-warning">Total Users</h6>
          <h3 id="totalUsers">0</h3>
        </div>
      </div>
      <div class="col-md-3">
        <div class="card bg-dark text-white p-3 shadow-lg">
          <h6 class="text-warning">Pending Payments</h6>
          <h3 id="pendingPayments">₹0</h3>
        </div>
      </div>
      <div class="col-md-3">
        <div class="card bg-dark text-white p-3 shadow-lg">
          <h6 class="text-warning">Total Enquiries</h6>
          <h3 id="totalEnquiries">0</h3>
        </div>
      </div>
    </div>
  </div>

  <!-- Report Table -->
  <div class="col-12 mt-3">
    <div class="card bg-dark text-white p-3 shadow-lg">
      <h5 class="mb-3 text-warning">Detailed Report</h5>
      <div class="table-responsive">
        <table id="reportTable" class="table table-dark table-striped table-hover align-middle">
          <thead class="text-warning">
            <tr>
              <th>#</th>
              <th>User Name</th>
              <th>Email</th>
              <th>Booking Date</th>
              <th>Amount</th>
              <th>Payment Status</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>1</td>
              <td>Rohit Sharma</td>
              <td>rohit@example.com</td>
              <td>2025-10-01</td>
              <td>₹5000</td>
              <td>Paid</td>
            </tr>
            <tr>
              <td>2</td>
              <td>Priya Singh</td>
              <td>priya@example.com</td>
              <td>2025-10-02</td>
              <td>₹3000</td>
              <td>Pending</td>
            </tr>
            <tr>
              <td>3</td>
              <td>Rahul Mehta</td>
              <td>rahul@example.com</td>
              <td>2025-10-03</td>
              <td>₹4500</td>
              <td>Paid</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>

</section>

@endsection

@section('scripts')
<!-- jQuery & DataTables -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

<!-- DataTables Buttons for Excel/PDF -->
<link href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.bootstrap5.min.css" rel="stylesheet">
<script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.bootstrap5.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>

<!-- Bootstrap 5 JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
$(document).ready(function() {

  // Initialize DataTable with Buttons
  var table = $('#reportTable').DataTable({
    pageLength: 5,
    lengthChange: false,
    ordering: true,
    language: { search: "🔍 Search:", paginate: { previous: "⬅️", next: "➡️" } },
    dom: 'Bfrtip',
    buttons: [
      { extend: 'excelHtml5', className: 'btn btn-warning text-dark mb-2', title: 'Report' },
      { extend: 'pdfHtml5', className: 'btn btn-warning text-dark mb-2', title: 'Report' },
      { extend: 'print', className: 'btn btn-warning text-dark mb-2', title: 'Report' }
    ]
  });

  // Dummy summary stats calculation
  function calculateStats() {
    let totalIncome = 0, totalUsers = 0, pendingPayments = 0, totalEnquiries = 0;
    $('#reportTable tbody tr').each(function() {
      totalUsers++;
      totalEnquiries++;
      let amount = parseInt($(this).find('td:eq(4)').text().replace('₹',''));
      let status = $(this).find('td:eq(5)').text();
      if(status.toLowerCase() === 'paid') totalIncome += amount;
      if(status.toLowerCase() === 'pending') pendingPayments += amount;
    });
    $('#totalIncome').text(`₹${totalIncome}`);
    $('#totalUsers').text(totalUsers);
    $('#pendingPayments').text(`₹${pendingPayments}`);
    $('#totalEnquiries').text(totalEnquiries);
  }

  calculateStats();

  // Filter button (dummy for now)
  $('#applyFilters').click(function() {
    alert("Filters applied! Fetch data from backend to update table and stats.");
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
  .form-control:focus { box-shadow: none; }
  .dt-buttons .btn { margin-right: 5px; }
</style>

@endsection
