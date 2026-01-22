@extends('backend.layouts.main')
@section('main-section')
    <!-- Page Content -->
    <section class="dashboard row g-4">

        <!-- Topbar -->
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2 class="text-warning fw-bold">Car Master</h2>
              @can('create cars')
            <button class="btn btn-warning text-dark fw-semibold" data-bs-toggle="modal" data-bs-target="#carModal">
                + Add New Car
            </button>
            @endcan

        </div>   

        <!-- Car Table -->
        <div class="col-12">
            <div class="card bg-dark text-white p-3">
                <h5 class="mb-3 text-warning">Car List</h5>
                <div class="table-responsive">
                    <table id="carTable" class="table table-dark table-striped table-hover align-middle">
                        <thead class="text-warning">
                            <tr>
                                <th>#</th>
                                <th>Brand</th>
                                <th>Model</th>
                                <th>Rate (₹/Km)</th>
                                <th>Availability</th>
                                <th>Visit</th>
                                <th>Update Profile</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>

                            @php
                                $sl = 1;
                            @endphp
                            @foreach ($cars as $car)
                                <tr>
                                    <td>{{ $sl++ }}</td>
                                    <td>{{ $car->brand }}</td>
                                    <td>{{ $car->model }}</td>
                                    <td>{{ $car->rate_per_km }}</td>
                                    <td>
                                       @if ($car->status == 1)
    <span class="badge bg-success">Running</span>
@elseif($car->status == 2)
    <span class="badge bg-dark">Blacklisted</span>
@elseif($car->status == 3)
    <span class="badge bg-info text-dark">Sold</span>
@elseif($car->status == 4)
    <span class="badge bg-warning text-dark">Booked</span>
@elseif($car->status == 5)
    <span class="badge bg-danger">Break-down</span>
@else
    <span class="badge bg-secondary">Unknown</span>
@endif
						
						
						
                                    </td>
                                    <td>
                                        <a target="_blank" href="{{ url('car_profile/' . $car->unique_id) }}"><span
                                                class="badge bg-info text-dark">👀 Profile</span></a>
                                    </td>
                                    <td>

                                        @can('edit cars')
                                        <a target="_blank" href="{{ url('update_car_profile/' . $car->unique_id) }}"><span
                                                class="badge bg-warning text-dark">Complete Profile</span></a>
                                                @endcan
                                    </td> 
                                    <td>
                                        <!-- <button class="btn btn-sm btn-warning text-dark">Edit</button> -->

                                        <!-- Edit codes starts here -->

                                        @can('edit cars')
                                        <button class="btn btn-warning text-dark fw-semibold" data-bs-toggle="modal" 
                                            data-bs-target="#carModalforedit{{ $car->id }}">
                                            Edit
                                        </button>

                                        @endcan
                                        <div class="modal fade" id="carModalforedit{{ $car->id }}" tabindex="-1"
                                            aria-labelledby="carModalforedit{{ $car->id }}Label" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content bg-dark text-white border border-warning">
                                                    <div class="modal-header border-warning">
                                                        <h5 class="modal-title text-warning"
                                                            id="carModalforedit{{ $car->id }}Label">Add New Car</h5>
                                                        <button type="button" class="btn-close btn-close-white"
                                                            data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
<form id="updatenewcarform{{ $car->id }}" action="{{ url('update_new_cars') }}" method="post">
                @csrf
                <input type="hidden" value="{{ $car->unique_id }}" name="unique_id">
                
                <div class="modal-body p-4 text-start">
                    <div class="mb-3">
                        <label class="form-label text-warning small">Car Nickname</label>
                        <input type="text" name="car_name" value="{{ $car->name }}" class="form-control bg-secondary text-white border-0">
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-warning small">Brand</label>
                        <input type="text" name="brand_name" value="{{ $car->brand }}" class="form-control bg-secondary text-white border-0">
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-warning small">Model / Year</label>
                        <input type="text" name="model_name" value="{{ $car->model }}" class="form-control bg-secondary text-white border-0">
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-warning small">Rate (₹/Km)</label>
                        <input type="number" name="rate_per_km" value="{{ $car->rate_per_km }}" class="form-control bg-secondary text-white border-0">
                    </div>
                    <div class="mb-3">
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
                <div class="modal-footer border-warning">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" id="updBtn{{ $car->id }}" class="btn btn-warning text-dark fw-bold btn-sm" onclick="update_car_function({{ $car->id }})">Update Car</button>
                </div>
            </form>


                                                    <script src="{{ asset('backend/js/sweetalert2.min.js') }}"></script>
                                                    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
                                                    <script>
                                                      function update_car_function(id) {
    // Sahi form ko target karna unique ID se
    const form = document.getElementById('updatenewcarform' + id);
    const btn = document.getElementById('updBtn' + id);
    
    // UI feedback
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span>...';

    const formData = new FormData(form);

    fetch(form.action, {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            Swal.fire({
                icon: 'success',
                title: 'Updated!',
                text: data.message,
                showConfirmButton: false,
                timer: 1500
            }).then(() => {
                location.reload();
            });
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Oops!',
                text: data.message
            });
            btn.disabled = false;
            btn.innerHTML = 'Update Car';
        }
    })
    .catch(error => {
        console.error('Error:', error);
        btn.disabled = false;
        btn.innerHTML = 'Update Car';
    });
}
                                                    </script>



                                                </div>
                                            </div>
                                        </div>

                                        <!-- end of edit codes -->
                                        <!-- <button class="btn btn-sm btn-danger">Delete</button> -->

                                        <!-- delete function starts here -->

                                        @can('delete cars')
                                        <form id="deletenewcarform" method="post" action="{{ url('delete_new_cars') }}">
                                            @csrf
                                            <input type="hidden" name="unique_id" value="{{ $car->unique_id }}">
                                            <button type="button" class="btn btn-danger fw-semibold"
                                                onclick="delete_car_function()">Delete</button>
                                        </form>
                                        @endcan
                                        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
                                        <script>
                                            function delete_car_function() {
                                                const form = document.getElementById('deletenewcarform');
                                                const formData = new FormData(form);

                                                fetch(form.action, {
                                                        method: 'POST',
                                                        body: formData,
                                                        headers: {
                                                            'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                                                        }
                                                    })
                                                    .then(res => res.json())
                                                    .then(data => {
                                                        if (data.success) {
                                                            Swal.fire({
                                                                icon: 'success',
                                                                title: 'Deleted Successfully!',
                                                                showConfirmButton: false,
                                                                timer: 1500
                                                            }).then(() => location.reload());
                                                        } else {
                                                            Swal.fire({
                                                                icon: 'error',
                                                                title: 'Deletion Failed',
                                                                text: data.message || 'Something went wrong!',
                                                            });
                                                        }
                                                    })
                                                    .catch(error => {
                                                        console.error('Error:', error);
                                                        Swal.fire({
                                                            icon: 'error',
                                                            title: 'Error Occurred!',
                                                            text: 'Please try again.',
                                                        });
                                                    });
                                            }
                                        </script>


                                        <!-- end of delete function -->
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </section>

    <!-- Add Car Modal -->
    <!-- Add Car Modal -->
    <div class="modal fade" id="carModal" tabindex="-1" aria-labelledby="carModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content bg-dark text-white border border-warning">
                <div class="modal-header border-warning">
                    <h5 class="modal-title text-warning" id="carModalLabel">Add New Car</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>

                <form id="addnewcarform" class="p-3" method="post" action="{{ url('add_new_cars') }}">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <!-- <label class="form-label text-warning">Car Name</label> -->
                            <input type="hidden" value="NA" class="form-control bg-secondary text-white border-0"
                                name="car_name" placeholder="Enter Car Name">
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-warning">Brand</label>
                            <input type="text" class="form-control bg-secondary text-white border-0" name="brand_name"
                                placeholder="Enter Brand Name">
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-warning">Model</label>
                            <input type="text" class="form-control bg-secondary text-white border-0" name="model_name"
                                placeholder="Enter Model Year">
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-warning">Rate (₹/Km)</label>
                            <input type="number" class="form-control bg-secondary text-white border-0"
                                name="rate_per_km" placeholder="Enter Rate">
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-warning">Availability</label>
                            <select class="form-select bg-secondary text-white border-0" name="status">
                                <option value="1">Running</option>
                                <option value="2">Blacklisted</option>
                                <option value="3">Sold</option>
                                <option value="4">Booked</option>
                                <option value="5">Break-down</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer border-warning">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-warning text-dark fw-semibold"
                            onclick="add_new_car_function()">Save
                            Car</button>
                    </div>
                </form>


                <script src="{{ asset('backend/js/sweetalert2.min.js') }}"></script>
                <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
                <script>
                    function add_new_car_function() {
                        const form = document.getElementById('addnewcarform');
                        const formData = new FormData(form);

                        fetch(form.action, {
                                method: 'POST',
                                body: formData,
                                headers: {
                                    'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                                }
                            })
                            .then(res => res.json())
                            .then(data => {
                                if (data.success) {
                                    // alert('aa gya ');
                                    // code for sweat alert start
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Added Successfully!',
                                        showConfirmButton: false,
                                        timer: 1500
                                    }).then(() => {
                                        location.reload();
                                    });
                                    // code for sweat alert end
                                } else {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Update Failed',
                                        // showConfirmButton:false,
                                        text: data.message || 'Something went wrong!',
                                    });
                                }
                            })
                            .catch(error => {
                                console.error('Error:', error);
                                alert('Error occurred while submitting the form.');
                            })
                    }
                </script>



            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <!-- jQuery + DataTables -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">

    <script>
        $(document).ready(function() {
            // Initialize DataTable
            $('#carTable').DataTable({
                "pageLength": 5,
                "lengthChange": false,
                "language": {
                    "search": "🔍 Search:"
                }
            });

            // Modal Controls
            const modal = document.getElementById('carModal');
            $('#openModal').on('click', function() {
                $(modal).fadeIn(200);
            });
            $('#closeModal, #closeModalFooter').on('click', function() {
                $(modal).fadeOut(200);
            });
            $(window).on('click', function(e) {
                if (e.target == modal) $(modal).fadeOut(200);
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

        .modal {
            display: none;
            position: fixed;
            z-index: 1050;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.8);
            overflow: auto;
        }

        .modal-content {
            border-radius: 10px;
        }

        table.dataTable tbody tr:hover {
            background-color: #2b2b2b;
        }


        .modal {
            display: none;
            position: fixed;
            justify-content: center;
            align-items: center;
            background-color: rgba(0, 0, 0, 0.8);
            inset: 0;
            z-index: 1050;
        }
    </style>



    <script>
        $(document).ready(function() {
            $('#openModal').click(function() {
                $('#carModal').fadeIn(200).css('display', 'flex').hide().fadeIn(200);
            });

            $('#closeModal, #closeModalFooter').click(function() {
                $('#carModal').fadeOut(200);
            });

            $(window).on('click', function(e) {
                if ($(e.target).is('#carModal')) {
                    $('#carModal').fadeOut(200);
                }
            });
        });
    </script>
@endsection
