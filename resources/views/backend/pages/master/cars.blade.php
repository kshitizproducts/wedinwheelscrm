@extends('backend.layouts.main')
@section('main-section')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <section class="dashboard container-fluid py-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="text-warning fw-bold mb-0">Car Master</h4>
            @can('create cars')
            <button class="btn btn-warning btn-sm text-dark fw-bold px-3" data-bs-toggle="modal" data-bs-target="#carModal">
                <i class="fas fa-plus"></i> Add New
            </button>
            @endcan
        </div>   

        <div class="col-12">
            <div class="card bg-dark text-white shadow-sm">
                <div class="card-header border-secondary bg-transparent py-3">
                    <h6 class="mb-0 text-warning"><i class="fas fa-car me-2"></i>Car List</h6>
                </div>
                <div class="table-responsive p-2">
                    <table id="carTable" class="table table-dark table-hover align-middle mb-0" style="font-size: 0.9rem;">
                        <thead class="text-warning small text-uppercase">
                            <tr>
                                <th>#</th>
                                <th>Brand</th>
                                <th>Model</th>
                                <th>Rate (₹/Km)</th>
                                <th>Status</th>
                                <th class="text-center">View</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $sl = 1; @endphp
                            @foreach ($cars as $car)
                                <tr>
                                    <td class="text-muted small">{{ $sl++ }}</td>
                                    <td class="fw-bold">{{ $car->brand }}</td>
                                    <td>{{ $car->model }}</td>
                                    <td class="text-warning fw-semibold">₹{{ $car->rate_per_km }}</td>
                                    <td>
                                        @php
                                            $badgeClass = [
                                                1 => 'bg-success',
                                                2 => 'bg-secondary',
                                                3 => 'bg-info text-dark',
                                                4 => 'bg-warning text-dark',
                                                5 => 'bg-danger'
                                            ][$car->status] ?? 'bg-secondary';
                                            
                                            $statusText = [
                                                1 => 'Running', 2 => 'Blacklisted', 3 => 'Sold', 4 => 'Booked', 5 => 'Break-down'
                                            ][$car->status] ?? 'Unknown';
                                        @endphp
                                        <span class="badge {{ $badgeClass }}" style="font-size: 0.7rem;">{{ $statusText }}</span>
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group gap-1">
                                            <a target="_blank" href="{{ url('car_profile/' . $car->unique_id) }}" class="btn btn-outline-info btn-sm border-0" title="View Profile">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            @can('edit cars')
                                            <a target="_blank" href="{{ url('update_car_profile/' . $car->unique_id) }}" class="btn btn-outline-warning btn-sm border-0" title="Complete Profile">
                                                <i class="fas fa-id-card"></i>
                                            </a>
                                            @endcan
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <div class="d-flex justify-content-center gap-2">
                                            @can('edit cars')
                                            <button class="btn btn-sm btn-warning text-dark px-2" data-bs-toggle="modal" data-bs-target="#carModalforedit{{ $car->id }}" title="Quick Edit">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            @endcan

                                            @can('delete cars')
                                            <form id="deletenewcarform{{ $car->id }}" method="post" action="{{ url('delete_new_cars') }}" class="d-inline">
                                                @csrf
                                                <input type="hidden" name="unique_id" value="{{ $car->unique_id }}">
                                                <button type="button" class="btn btn-sm btn-danger px-2" onclick="delete_car_function({{ $car->id }})" title="Delete">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                            @endcan
                                        </div>

                                        <div class="modal fade" id="carModalforedit{{ $car->id }}" tabindex="-1" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content bg-dark text-white border border-warning">
                                                    <div class="modal-header border-secondary">
                                                        <h6 class="modal-title text-warning">Quick Edit Car</h6>
                                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <form id="updatenewcarform{{ $car->id }}" action="{{ url('update_new_cars') }}" method="post">
                                                        @csrf
                                                        <input type="hidden" value="{{ $car->unique_id }}" name="unique_id">
                                                        <div class="modal-body p-4 text-start">
                                                            <div class="row g-3">
                                                                <div class="col-12">
                                                                    <label class="form-label text-warning small">Car Nickname</label>
                                                                    <input type="text" name="car_name" value="{{ $car->name }}" class="form-control bg-secondary text-white border-0">
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <label class="form-label text-warning small">Brand</label>
                                                                    <input type="text" name="brand_name" value="{{ $car->brand }}" class="form-control bg-secondary text-white border-0">
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <label class="form-label text-warning small">Model / Year</label>
                                                                    <input type="text" name="model_name" value="{{ $car->model }}" class="form-control bg-secondary text-white border-0">
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <label class="form-label text-warning small">Rate (₹/Km)</label>
                                                                    <input type="number" name="rate_per_km" value="{{ $car->rate_per_km }}" class="form-control bg-secondary text-white border-0">
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <label class="form-label text-warning small">Availability Status</label>
                                                                    <select class="form-select bg-secondary text-white border-0" name="status">
                                                                        <option value="1" {{ $car->status == 1 ? 'selected' : '' }}>Running</option>
                                                                        <option value="2" {{ $car->status == 2 ? 'selected' : '' }}>Blacklisted</option>
                                                                        <option value="3" {{ $car->status == 3 ? 'selected' : '' }}>Sold</option>
                                                                        <option value="4" {{ $car->status == 4 ? 'selected' : '' }}>Booked</option>
                                                                        <option value="5" {{ $car->status == 5 ? 'selected' : '' }}>Break-down</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer border-secondary">
                                                            <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                                                            <button type="button" id="updBtn{{ $car->id }}" class="btn btn-warning text-dark fw-bold btn-sm" onclick="update_car_function({{ $car->id }})">Save Changes</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>

    <div class="modal fade" id="carModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content bg-dark text-white border border-warning shadow-lg">
                <div class="modal-header border-secondary">
                    <h6 class="modal-title text-warning">Register New Car</h6>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form id="addnewcarform" action="{{ url('add_new_cars') }}" method="post">
                    @csrf
                    <div class="modal-body p-4">
                        <div class="row g-3">
                            <input type="hidden" value="NA" name="car_name">
                            <div class="col-12">
                                <label class="form-label text-warning small">Brand</label>
                                <input type="text" class="form-control bg-secondary text-white border-0" name="brand_name" placeholder="Toyota, Mahindra etc.">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-warning small">Model</label>
                                <input type="text" class="form-control bg-secondary text-white border-0" name="model_name" placeholder="Swift 2024">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-warning small">Rate (₹/Km)</label>
                                <input type="number" class="form-control bg-secondary text-white border-0" name="rate_per_km" placeholder="15">
                            </div>
                            <div class="col-12">
                                <label class="form-label text-warning small">Initial Status</label>
                                <select class="form-select bg-secondary text-white border-0" name="status">
                                    <option value="1">Running</option>
                                    <option value="4">Booked</option>
                                    <option value="5">Break-down</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-secondary">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-warning text-dark fw-bold" onclick="add_new_car_function()">Save Car</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(document).ready(function() {
            $('#carTable').DataTable({
                "pageLength": 10,
                "lengthChange": false,
                "language": { "search": "🔍" }
            });
        });

        // AJAX Update Function
        function update_car_function(id) {
            const form = document.getElementById('updatenewcarform' + id);
            const btn = document.getElementById('updBtn' + id);
            btn.disabled = true;
            btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span>';

            fetch(form.action, {
                method: 'POST',
                body: new FormData(form),
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({ icon: 'success', title: 'Updated!', showConfirmButton: false, timer: 1000 }).then(() => location.reload());
                } else {
                    Swal.fire({ icon: 'error', title: 'Oops!', text: data.message });
                    btn.disabled = false; btn.innerHTML = 'Save Changes';
                }
            });
        }

        // AJAX Add Function
        function add_new_car_function() {
            const form = document.getElementById('addnewcarform');
            fetch(form.action, {
                method: 'POST',
                body: new FormData(form),
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({ icon: 'success', title: 'Added!', showConfirmButton: false, timer: 1000 }).then(() => location.reload());
                } else {
                    Swal.fire({ icon: 'error', title: 'Error', text: data.message });
                }
            });
        }

        // AJAX Delete Function (Unified)
        function delete_car_function(id) {
            Swal.fire({
                title: 'Delete Car?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ffc107',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.getElementById('deletenewcarform' + id);
                    fetch(form.action, {
                        method: 'POST',
                        body: new FormData(form),
                        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) location.reload();
                        else Swal.fire('Error', data.message, 'error');
                    });
                }
            });
        }
    </script>

    <style>
        .card { border: none; border-radius: 10px; }
        .table thead th { border-top: none; }
        .form-control:focus { box-shadow: none; border-color: #ffc107; }
        .btn-action-small { padding: 0.25rem 0.5rem; font-size: 0.8rem; }
        /* Professional scrollbar for dark theme */
        ::-webkit-scrollbar { width: 5px; }
        ::-webkit-scrollbar-track { background: #121212; }
        ::-webkit-scrollbar-thumb { background: #333; border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: #ffc107; }
    </style>
@endsection