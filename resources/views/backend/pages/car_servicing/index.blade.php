@extends('backend.layouts.main')
@section('main-section')

<div class="container-fluid p-3 p-md-4 page-wrap">

    {{-- TOP BAR --}}
    <div class="topbar d-flex flex-column flex-lg-row justify-content-between align-items-start align-items-lg-center gap-3 mb-4">
        <div class="d-flex align-items-start gap-3">
            <div class="icon-wrap">
                <i class="fas fa-screwdriver-wrench"></i>
            </div>
            <div>
                <h2 class="text-warning fw-bold mb-1">Update Car Servicing</h2>
                <div class="text-secondary small">
                    Search, filter and open any car for servicing & complete profile update.
                </div>
            </div>
        </div>

        <div class="d-flex flex-wrap gap-2">
            <span class="badge bg-dark border border-warning text-warning px-3 py-2 rounded-pill">
                <i class="fas fa-car me-1"></i> Total Cars: {{ count($cars) }}
            </span>

        </div>
    </div>

    {{-- MAIN CARD --}}
    <div class="card glass-card border border-secondary">
        <div class="card-body p-0">

            {{-- CARD HEADER --}}
            <div style="background-color: #1a1a1a"  class="px-3 px-md-4 py-3 py-md-4 border-bottom border-secondary d-flex flex-column flex-md-row justify-content-between gap-3">
                <div class="fw-bold text-white d-flex align-items-center gap-2">
                    <span class="mini-icon text-warning"><i class="fas fa-list"></i></span>
                    Car List
                </div>

                <div class="d-flex flex-wrap gap-2 align-items-center">
                    <div class="hint d-none d-md-flex align-items-center gap-2">
                        <i class="fas fa-circle-info text-warning"></i>
                        <span class="text-secondary small">Click <b class="text-warning">Update Servicing</b> to open the profile</span>
                    </div>
                </div>
            </div>

            {{-- TABLE --}}
            <div class="table-responsive">
                <table id="carTable" class="table table-dark table-hover align-middle mb-0 w-100">
                    <thead class="thead-sticky">
                        <tr>
                            <th style="width:70px;">#</th>
                            <th style="min-width:160px;">Brand</th>
                            <th style="min-width:180px;">Model</th>
                            <th style="min-width:140px;">Rate (₹/Km)</th>
                            <th style="min-width:170px;">Availability</th>
                            <th style="min-width:200px;">Action</th>
                        </tr>
                    </thead>

                    <tbody>
                        @php $sl = 1; @endphp
                        @foreach ($cars as $car)
                            <tr>
                                <td class="text-secondary fw-semibold">{{ $sl++ }}</td>

                                <td class="fw-semibold text-white">
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="dot"></span>
                                        {{ $car->brand }}
                                    </div>
                                </td>

                                <td class="text-white">{{ $car->model }}</td>

                                <td class="fw-bold text-info">₹{{ $car->rate_per_km }}</td>

                                <td>
                                    @if ($car->status == 1)
                                        <span class="badge badge-running">
                                            <i class="fas fa-circle-check me-1"></i> Running
                                        </span>
                                    @elseif($car->status == 2)
                                        <span class="badge badge-blacklisted">
                                            <i class="fas fa-ban me-1"></i> Blacklisted
                                        </span>
                                    @elseif($car->status == 3)
                                        <span class="badge badge-sold">
                                            <i class="fas fa-handshake me-1"></i> Sold
                                        </span>
                                    @elseif($car->status == 4)
                                        <span class="badge badge-booked">
                                            <i class="fas fa-calendar-check me-1"></i> Booked
                                        </span>
                                    @elseif($car->status == 5)
                                        <span class="badge badge-breakdown">
                                            <i class="fas fa-triangle-exclamation me-1"></i> Break-down
                                        </span>
                                    @else
                                        <span class="badge bg-secondary">
                                            <i class="fas fa-question-circle me-1"></i> Unknown
                                        </span>
                                    @endif
                                </td>

                                <td>
                                    <a target="_blank"
                                       href="{{ url('update_car_servicing/' . $car->unique_id) }}"
                                       class="btn btn-sm btn-warning text-dark fw-bold rounded-pill px-3 w-100 w-md-auto">
                                        <i class="fas fa-tools me-1"></i> Update Servicing
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

        </div>
    </div>

</div>

{{-- ADD CAR MODAL --}}
<div class="modal fade" id="carModal" tabindex="-1" aria-labelledby="carModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content bg-dark text-white border border-warning modal-soft">
            <div class="modal-header border-warning">
                <h5 class="modal-title text-warning" id="carModalLabel">
                    <i class="fas fa-plus-circle me-2"></i> Add New Car
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
            </div>

            <form id="addnewcarform" class="p-3" method="post" action="{{ url('add_new_cars') }}">
                @csrf
                <div class="modal-body">
                    <input type="hidden" value="NA" name="car_name">

                    <div class="mb-3">
                        <label class="form-label text-secondary small">Brand</label>
                        <input type="text" class="form-control dark-input" name="brand_name"
                               placeholder="Enter Brand Name">
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-secondary small">Model / Year</label>
                        <input type="text" class="form-control dark-input" name="model_name"
                               placeholder="Enter Model Year">
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-secondary small">Rate (₹/Km)</label>
                        <input type="number" class="form-control dark-input" name="rate_per_km"
                               placeholder="Enter Rate">
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-secondary small">Availability</label>
                        <select class="form-select dark-input" name="status">
                            <option value="1">Running</option>
                            <option value="2">Blacklisted</option>
                            <option value="3">Sold</option>
                            <option value="4">Booked</option>
                            <option value="5">Break-down</option>
                        </select>
                    </div>
                </div>

                <div class="modal-footer border-warning">
                    <button type="button" class="btn btn-outline-secondary rounded-pill px-4" data-bs-dismiss="modal">
                        Cancel
                    </button>
                    <button type="button" id="saveCarBtn"
                            class="btn btn-warning text-dark fw-bold rounded-pill px-4"
                            onclick="add_new_car_function()">
                        <i class="fas fa-save me-1"></i> Save Car
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>

@endsection


@section('scripts')
    {{-- DATATABLES --}}
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

    <link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet" />

    {{-- SweetAlert --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(document).ready(function() {
            $('#carTable').DataTable({
                pageLength: 8,
                lengthChange: false,
                ordering: true,
                info: true,
                responsive: true,
                autoWidth: false,
                language: {
                    search: "",
                    searchPlaceholder: "Search brand / model / status ...",
                    paginate: {
                        previous: "‹",
                        next: "›"
                    }
                }
            });

            // Make search input look better
            setTimeout(() => {
                $('.dataTables_filter label').contents().filter(function() {
                    return this.nodeType === 3;
                }).remove();
            }, 50);
        });

        function setBtnState(btn, state) {
            if (!btn) return;
            if (state === 'loading') {
                btn.disabled = true;
                btn.dataset.old = btn.innerHTML;
                btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Saving...';
            } else {
                btn.disabled = false;
                btn.innerHTML = btn.dataset.old || 'Save';
            }
        }

        function add_new_car_function() {
            const btn = document.getElementById('saveCarBtn');
            setBtnState(btn, 'loading');

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
                        Swal.fire({
                            icon: 'success',
                            title: 'Added Successfully!',
                            showConfirmButton: false,
                            timer: 1500
                        }).then(() => location.reload());
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Failed',
                            text: data.message || 'Something went wrong!'
                        });
                        setBtnState(btn, 'reset');
                    }
                })
                .catch(() => {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Request failed! Please try again.'
                    });
                    setBtnState(btn, 'reset');
                });
        }
    </script>

    <style>
        .page-wrap {
            background: radial-gradient(900px circle at 10% 0%, rgba(255, 193, 7, 0.10), transparent 45%),
                        radial-gradient(900px circle at 90% 10%, rgba(13, 110, 253, 0.07), transparent 45%),
                        #0f0f0f;
            min-height: 100vh;
        }

        .topbar{
            padding: 10px 4px;
        }

        .icon-wrap{
            width: 46px;
            height: 46px;
            border-radius: 14px;
            display:flex;
            justify-content:center;
            align-items:center;
            background: rgba(255,193,7,0.12);
            border: 1px solid rgba(255,193,7,0.35);
            color: #ffc107;
            font-size: 18px;
            flex: 0 0 auto;
        }

        .glass-card {
            background: rgba(30, 30, 30, 0.88);
            border-radius: 18px;
            box-shadow: 0 12px 36px rgba(0, 0, 0, 0.35);
            overflow: hidden;
        }

        .shadow-warning {
            box-shadow: 0 0 18px rgba(255, 193, 7, 0.25);
        }

        .mini-icon{
            width: 34px;
            height: 34px;
            border-radius: 12px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: rgba(255,193,7,0.10);
            border: 1px solid rgba(255,193,7,0.25);
        }

        .dot{
            width: 10px;
            height: 10px;
            border-radius: 999px;
            background: rgba(255,193,7,0.8);
            box-shadow: 0 0 0 4px rgba(255,193,7,0.08);
            display: inline-block;
        }

        .dark-input {
            background: #141414 !important;
            border: 1px solid rgba(255, 255, 255, 0.10) !important;
            color: #fff !important;
            border-radius: 12px !important;
            outline: none !important;
            box-shadow: none !important;
        }

        .dark-input:focus {
            border-color: rgba(255, 193, 7, 0.6) !important;
            box-shadow: 0 0 0 0.2rem rgba(255, 193, 7, 0.12) !important;
        }

        .thead-sticky th {
            position: sticky;
            top: 0;
            z-index: 5;
            background: #1a1a1a !important;
            border-bottom: 1px solid rgba(255, 255, 255, 0.12) !important;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: .04em;
            color: rgba(255, 255, 255, 0.85);
        }

        .table-dark {
            --bs-table-bg: transparent;
            --bs-table-striped-bg: rgba(255, 255, 255, 0.02);
            --bs-table-hover-bg: rgba(255, 193, 7, 0.06);
            border-color: rgba(255, 255, 255, 0.07) !important;
        }

        .table td,
        .table th {
            border-color: rgba(255, 255, 255, 0.07) !important;
        }

        /* Status Badges */
        .badge {
            border-radius: 999px;
            padding: 7px 12px;
            font-weight: 700;
            letter-spacing: .2px;
        }

        .badge-running {
            background: rgba(25, 135, 84, 0.15);
            color: #7dffb0;
            border: 1px solid rgba(25, 135, 84, 0.35);
        }

        .badge-blacklisted {
            background: rgba(0, 0, 0, 0.35);
            color: rgba(255, 255, 255, 0.9);
            border: 1px solid rgba(255, 255, 255, 0.15);
        }

        .badge-sold {
            background: rgba(13, 110, 253, 0.15);
            color: #a8c7ff;
            border: 1px solid rgba(13, 110, 253, 0.35);
        }

        .badge-booked {
            background: rgba(255, 193, 7, 0.15);
            color: #ffd36b;
            border: 1px solid rgba(255, 193, 7, 0.35);
        }

        .badge-breakdown {
            background: rgba(220, 53, 69, 0.15);
            color: #ff9aa6;
            border: 1px solid rgba(220, 53, 69, 0.35);
        }

        .modal-soft {
            border-radius: 18px;
            overflow: hidden;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.55);
        }

        /* Datatable polish */
        .dataTables_wrapper .dataTables_filter input {
            margin-left: 0 !important;
            border-radius: 999px !important;
            padding: 8px 14px !important;
            background: #141414 !important;
            border: 1px solid rgba(255, 255, 255, 0.10) !important;
            color: white !important;
        }

        .dataTables_wrapper .dataTables_filter label {
            color: rgba(255, 255, 255, 0.5) !important;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button {
            border-radius: 10px !important;
            margin: 0 3px !important;
        }

        .dataTables_wrapper .dataTables_info {
            color: rgba(255, 255, 255, 0.55) !important;
            padding: 14px 16px;
        }

        /* Responsive action button */
        @media (max-width: 576px){
            .topbar .btn{
                width: 100%;
            }
            .hint{ display:none !important; }
        }
    </style>
@endsection
