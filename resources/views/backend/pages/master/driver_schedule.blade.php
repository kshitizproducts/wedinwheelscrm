@extends('backend.layouts.main')
@section('main-section')

<section class="dashboard row g-4">

  <!-- Topbar -->
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h2 class="text-warning fw-bold"><i class="bi bi-person-badge"></i> Driver Management</h2>
    <button class="btn btn-warning text-dark fw-semibold" data-bs-toggle="modal" data-bs-target="#addDriverModal">
      + Add Driver
    </button>
  </div>

  <!-- Driver List -->
  <div class="col-12">
    <div class="card bg-dark text-white p-3 shadow-lg mb-4">
      <h5 class="mb-3 text-warning">All Registered Drivers</h5>
      <div class="table-responsive">
        <table id="driverList" class="table table-dark table-striped table-hover align-middle">
          <thead class="text-warning">
            <tr>
              <th>#</th>
              <th>Name</th>
              <th>Phone</th>
              <th>License No.</th>
              <th>Status</th>
            </tr>
          </thead>
          <tbody>
            @foreach($drivers as $driver)
            <tr>
              <td>{{ $loop->iteration }}</td>
              <td>{{ $driver->name }}</td>
              <td>NA</td>
              <td>NA</td>

              @php
              $driver_id = $driver->id;
              $schedule = DB::table('driver_schedules')->where('driver_id', $driver_id)->first();
              @endphp
              <td>
                @if($schedule)
                  @if($schedule->status == 0)
                   <span class="badge bg-warning">Pending</span>
                  @elseif($schedule->status == 1)
                   <span class="badge bg-success">Completed</span>
                  @elseif($schedule->status == 2)
                   <span class="badge bg-primary">Trip Ongoing</span>
                  @elseif($schedule->status == 3 )
                   <span class="badge bg-danger">Terminated</span>
                  @endif
                  @else
                   <span class="badge bg-danger">No records </span>
                  @endif
               </td>
           
            </tr>
            @endforeach
          
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <!-- Driver Assignment Table -->
  <div class="col-12">
    <div class="card bg-dark text-white p-3 shadow-lg mb-4">
      <h5 class="mb-3 text-warning">Assign Drivers to Cars & Clients</h5>
      <div class="table-responsive">
        <table id="driverAssign" class="table table-dark table-striped table-hover align-middle">
          <thead class="text-warning">
            <tr>
              <th>#</th>
              <th>Car</th>
              <th>Client</th>
              <th>Driver</th>
              <th>Date & Time</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>1</td>
              <td>
                <select class="form-select bg-secondary text-white border-0 select-car">
                 <option selected disabled>Please Select</option>
                 @foreach($cars as $car)
                   <option value="{{ $car->id }}">{{ $car->brand }} - {{ $car->registration_no }}</option>  
                  @endforeach
                </select>
              </td>
              <td>
                <select class="form-select bg-secondary text-white border-0 select-client">
                  <option value="">-- Select Client --</option>
                  <option>Rohit Sharma</option>
                  <option>Priya Singh</option>
                </select>
              </td>
              <td>
                <select class="form-select bg-secondary text-white border-0 assign-driver">
                  <option value="">-- Select Driver --</option>
                  <option>Kshitiz Kumar</option>
                  <option>Rahul Verma</option>
                </select>
              </td>
              <td><input type="datetime-local" class="form-control bg-secondary text-white border-0"></td>
              <td><button class="btn btn-sm btn-warning text-dark assign-btn">Assign</button></td>
            </tr>
          
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <!-- Assigned Driver Data -->
  <div class="col-12">
    <div class="card bg-dark text-white p-3 shadow-lg">
      <h5 class="mb-3 text-warning">Assigned Driver Records</h5>
      <div class="table-responsive">
        <table id="assignedTable" class="table table-dark table-striped table-hover align-middle">
          <thead class="text-warning">
            <tr>
              <th>#</th>
              <th>Car</th>
              <th>Client</th>
              <th>Driver</th>
            </tr>
          </thead>
          <tbody>
            @foreach($driver_schedules as $schedule)

            <tr>
              <td>{{ $loop->iteration }}</td>
              <td>{{ $schedule->car_id }}</td>
              <td>{{ $schedule->client_id }}</td>
              <td>{{ $schedule->driver_id }}</td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </div>

</section>

<!-- Add Driver Modal -->
<div class="modal fade" id="addDriverModal" tabindex="-1" aria-labelledby="addDriverLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content bg-dark text-white border border-warning">
      <div class="modal-header border-warning">
        <h5 class="modal-title text-warning" id="addDriverLabel">Add New Driver</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <form id="addDriverForm" method="post" enctype="multipart/form-data">
        <div class="modal-body">
          <div class="row g-3">
            <div class="col-md-6">
              <label class="form-label text-warning">Driver Name</label>
              <input type="text" class="form-control bg-secondary text-white border-0" placeholder="Enter driver name">
            </div>
            <div class="col-md-6">
              <label class="form-label text-warning">Phone</label>
              <input type="text" class="form-control bg-secondary text-white border-0" placeholder="Enter phone number">
            </div>
            <div class="col-md-6">
              <label class="form-label text-warning">License Number</label>
              <input type="text" class="form-control bg-secondary text-white border-0" placeholder="Enter license no.">
            </div>
            <div class="col-md-6">
              <label class="form-label text-warning">Status</label>
              <select class="form-select bg-secondary text-white border-0">
                <option>Available</option>
                <option>On Duty</option>
                <option>Unavailable</option>
              </select>
            </div>
            <div class="col-md-12">
              <label class="form-label text-warning">Upload License / ID Proof</label>
              <input type="file" class="form-control bg-secondary text-white border-0">
            </div>
            <div class="col-md-12">
              <label class="form-label text-warning">Notes</label>
              <textarea rows="2" class="form-control bg-secondary text-white border-0" placeholder="Optional"></textarea>
            </div>
          </div>
        </div>
        <div class="modal-footer border-warning">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-warning text-dark fw-semibold">Save Driver</button>
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
<link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">

<script>
$(document).ready(function() {
  $('#driverList, #driverAssign, #assignedTable').DataTable({
    pageLength: 5,
    lengthChange: false,
    ordering: false,
    language: {
      search: "🔍 Search:",
      paginate: { previous: "⬅️", next: "➡️" }
    }
  });

  // Assign button click
  $('#driverAssign').on('click', '.assign-btn', function() {
    const row = $(this).closest('tr');
    const car = row.find('.select-car').val();
    const client = row.find('.select-client').val();
    const driver = row.find('.assign-driver').val();

    if(car && client && driver) {
      alert(`✅ ${driver} assigned to ${car} for ${client}`);
      $('#assignedTable').DataTable().row.add([
        $('#assignedTable').DataTable().rows().count() + 1,
        car, client, driver
      ]).draw();
    } else {
      alert('⚠️ Please select all fields before assigning!');
    }
  });
});
</script>

<style>
body { background-color: #121212; }
.card { border-radius: 15px; background-color: #1e1e1e; border: 1px solid #333; }
.btn-warning { background-color: #ffc107; border: none; }
.btn-warning:hover { background-color: #ffca2c; }
.table-hover tbody tr:hover { background-color: #2b2b2b; }
.dataTables_filter label { color: #ffc107; font-weight: 600; }
.dataTables_filter input {
  background-color: #1e1e1e !important;
  border: 1px solid #444 !important;
  color: #fff !important;
  border-radius: 8px;
  padding: 6px 10px;
}
.badge { font-size: 0.85rem; }
.modal-content { border-radius: 10px; }
.form-control:focus, .form-select:focus {
  box-shadow: 0 0 0 0.25rem rgba(255, 193, 7, 0.3);
  border-color: #ffc107;
}
</style>
@endsection
