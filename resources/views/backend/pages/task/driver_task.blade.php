@extends('backend.layouts.main')
@section('main-section')

{{-- ================= REQUIRED LIBRARIES ================= --}}
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

{{-- ================= STYLES ================= --}}
<style>
    body { background:#121212; color: #fff; font-family: 'Segoe UI', Roboto, sans-serif; }

    .card-main {
        background:#1e1e1e;
        border:1px solid #333;
        border-radius:20px;
        overflow: hidden;
    }

    /* ===== Desktop Layout Fixes ===== */
    .table td { vertical-align: middle; max-width: 180px; }
    .wrap-cell {
        white-space: normal !important;
        word-break: break-all !important;
        font-size: 12px;
    }

    .location-link {
        display: block;
        background: rgba(255,255,255,0.05);
        border: 1px solid rgba(255,255,255,0.1);
        padding: 6px 10px;
        border-radius: 8px;
        color: #ddd;
        text-decoration: none;
        margin-bottom: 5px;
    }

    .location-link:hover { background: rgba(255,255,255,0.1); color: #ffc107; }

    /* ===== Mobile Card Styling ===== */
    .mobile-cards { display: none; padding: 10px 5px; }
    
    .task-card-premium {
        background: linear-gradient(145deg, #232323, #1a1a1a);
        border: 1px solid rgba(255,255,255,0.08);
        border-radius: 20px;
        padding: 18px;
        margin-bottom: 20px;
        box-shadow: 0 10px 20px rgba(0,0,0,0.4);
    }

    .card-top {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 15px;
    }

    .client-info h6 { margin: 0; font-weight: 700; color: #fff; font-size: 17px; letter-spacing: 0.3px; }
    .client-info .date { font-size: 11px; color: #888; display: block; margin-top: 2px; }

    /* Contact Quick Actions */
    .contact-actions {
        display: flex;
        gap: 10px;
        margin-top: 10px;
    }
    .btn-contact {
        flex: 1;
        padding: 8px;
        border-radius: 12px;
        font-size: 13px;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        text-decoration: none;
        font-weight: 600;
        transition: 0.3s;
    }
    .btn-call { background: rgba(13, 110, 253, 0.15); color: #0d6efd; border: 1px solid rgba(13, 110, 253, 0.3); }
    .btn-whatsapp { background: rgba(37, 211, 102, 0.15); color: #25d366; border: 1px solid rgba(37, 211, 102, 0.3); }

    /* Path Styling */
    .trip-path {
        position: relative;
        padding-left: 25px;
        margin: 18px 0;
    }
    .trip-path::before {
        content: '';
        position: absolute;
        left: 7px; top: 10px; bottom: 10px;
        width: 2px; border-left: 2px dashed rgba(255,255,255,0.1);
    }

    .path-step { position: relative; margin-bottom: 15px; }
    .path-step i { position: absolute; left: -25px; top: 2px; font-size: 14px; }

    .step-label { font-size: 10px; text-transform: uppercase; color: #ffc107; font-weight: 700; opacity: 0.8; }
    .step-value { font-size: 13px; color: #eee; display: block; word-break: break-all; }

    /* Footer Buttons */
    .card-footer-actions {
        display: flex;
        justify-content: space-between;
        background: rgba(0,0,0,0.25);
        margin: 15px -18px -18px -18px;
        padding: 15px 10px;
        border-radius: 0 0 20px 20px;
        border-top: 1px solid rgba(255,255,255,0.05);
    }

    .action-item {
        flex: 1;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 5px;
        background: none; border: none; color: #fff; font-size: 10px; font-weight: 500;
    }
    .action-item:disabled { opacity: 0.2; filter: grayscale(1); }
    .action-item i {
        width: 38px; height: 38px;
        display: flex; align-items: center; justify-content: center;
        border-radius: 12px; font-size: 18px;
    }

    .bg-soft-success { background: rgba(40, 167, 69, 0.2); color: #28a745; }
    .bg-soft-warning { background: rgba(255, 193, 7, 0.2); color: #ffc107; }
    .bg-soft-info { background: rgba(23, 162, 184, 0.2); color: #17a2b8; }

    .badge { border-radius: 6px; font-size: 10px; padding: 5px 10px; }

    @media(max-width:768px){
        .desktop-table { display:none; }
        .mobile-cards { display:block; }
    }
</style>

<section class="dashboard container-fluid py-3">
    <div class="card-main p-3">
        <div class="d-flex justify-content-between align-items-center mb-4 px-2">
            <h4 class="text-warning fw-bold m-0">🚖 Driver Tasks</h4>
            <span class="badge bg-dark border border-secondary text-secondary">{{ date('d M Y') }}</span>
        </div>

        {{-- ================= DESKTOP TABLE ================= --}}
        <div class="table-responsive desktop-table">
            <table class="table table-dark table-hover align-middle">
                <thead class="text-warning small text-uppercase">
                    <tr>
                        <th>#</th>
                        <th>Client Detail</th>
                        <th>Vehicle</th>
                        <th>Pickup & Drop</th>
                        <th>Status</th>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($driver_schedules as $task)
                    @php
                        $car = DB::table('cars')->where('id',$task->car_id)->first();
                        $client = DB::table('leads')->where('id',$task->client_id)->first();
                    @endphp
                    <tr>
                        <td class="text-muted">{{ $loop->iteration }}</td>
                        <td>
                            <div class="fw-bold text-white">{{ $client->client_name ?? '-' }}</div>
                            <div class="d-flex gap-2 mt-1">
                                <a href="tel:{{ $client->contact }}" class="text-info text-decoration-none small"><i class="bi bi-telephone"></i> Call</a>
                                <a href="https://wa.me/{{ $client->whatsapp }}" target="_blank" class="text-success text-decoration-none small"><i class="bi bi-whatsapp"></i> Chat</a>
                            </div>
                        </td>
                        <td>
                            <div class="small">{{ $car->brand ?? '-' }}</div>
                            <span class="badge bg-secondary" style="font-size:9px">{{ $car->registration_no ?? '-' }}</span>
                        </td>
                        <td class="wrap-cell">
                            <a target="_blank" href="http://google.com/maps/search/?api=1&query={{ urlencode($task->source_location) }}" class="location-link">
                                <i class="bi bi-geo-alt-fill text-warning me-1"></i> {{ $task->source_location }}
                            </a>
                            <a target="_blank" href="http://google.com/maps/search/?api=1&query={{ urlencode($task->destination_location) }}" class="location-link">
                                <i class="bi bi-geo-fill text-success me-1"></i> {{ $task->destination_location }}
                            </a>
                        </td>
                        <td>
                            @if($task->status==1) <span class="badge bg-warning text-dark">Assigned</span>
                            @elseif($task->status==2) <span class="badge bg-info text-dark">In Progress</span>
                            @elseif($task->status==3) <span class="badge bg-success">Completed</span> @endif
                        </td>
                        <td>
                            <div class="d-flex justify-content-center gap-1">
                                <button class="btn btn-sm btn-success startTripBtn" data-id="{{ $task->id }}" {{ $task->status!=1?'disabled':'' }}><i class="bi bi-play-fill"></i></button>
                                <button class="btn btn-sm btn-warning endTripBtn" data-id="{{ $task->id }}" {{ $task->status!=2?'disabled':'' }}><i class="bi bi-stop-fill"></i></button>
                                <button class="btn btn-sm btn-outline-info remarkBtn" data-id="{{ $task->id }}"><i class="bi bi-chat-dots"></i></button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- ================= PREMIUM MOBILE VIEW ================= --}}
        <div class="mobile-cards">
            @foreach($driver_schedules as $task)
            @php
                $car = DB::table('cars')->where('id',$task->car_id)->first();
                $client = DB::table('leads')->where('id',$task->client_id)->first();
            @endphp

            <div class="task-card-premium">
                <div class="card-top">
                    <div class="client-info">
                        <h6>{{ $client->client_name ?? 'Unknown Client' }}</h6>
                        <span class="date"><i class="bi bi-calendar-check me-1"></i> {{ $task->booked_date }}</span>
                    </div>
                    @if($task->status==1) <span class="badge bg-warning text-dark shadow-sm">Assigned</span>
                    @elseif($task->status==2) <span class="badge bg-info text-dark shadow-sm">In Transit</span>
                    @elseif($task->status==3) <span class="badge bg-success shadow-sm">Finished</span> @endif
                </div>

                {{-- Call & WhatsApp Quick Buttons --}}
                <div class="contact-actions">
                    <a href="tel:{{ $client->contact }}" class="btn-contact btn-call">
                        <i class="bi bi-telephone-fill"></i> Call Client
                    </a>
                    <a href="https://wa.me/91{{ $client->whatsapp }}" target="_blank" class="btn-contact btn-whatsapp">
                        <i class="bi bi-whatsapp"></i> WhatsApp
                    </a>
                </div>

                <div class="trip-path">
                    <div class="path-step">
                        <i class="bi bi-geo-alt-fill text-warning"></i>
                        <span class="step-label">Pickup Point</span>
                        <a href="http://google.com/maps/search/?api=1&query={{ urlencode($task->source_location) }}" target="_blank" class="step-value text-decoration-none">
                            {{ $task->source_location }} <i class="bi bi-box-arrow-up-right ms-1" style="font-size:10px"></i>
                        </a>
                    </div>
                    <div class="path-step">
                        <i class="bi bi-geo-fill text-success"></i>
                        <span class="step-label">Drop Destination</span>
                        <a href="http://google.com/maps/search/?api=1&query={{ urlencode($task->destination_location) }}" target="_blank" class="step-value text-decoration-none">
                            {{ $task->destination_location }} <i class="bi bi-box-arrow-up-right ms-1" style="font-size:10px"></i>
                        </a>
                    </div>
                </div>

                <div class="d-flex align-items-center p-2 mb-2" style="background:rgba(255,255,255,0.03); border-radius:12px; border:1px solid rgba(255,255,255,0.05)">
                    <i class="bi bi-car-front-fill text-warning me-2"></i>
                    <span style="font-size:12px; color:#aaa;">{{ $car->brand ?? 'N/A' }} <span class="mx-1">|</span> <b class="text-white">{{ $car->registration_no ?? 'N/A' }}</b></span>
                </div>

                <div class="card-footer-actions">
                    <button class="action-item startTripBtn" data-id="{{ $task->id }}" {{ $task->status!=1?'disabled':'' }}>
                        <i class="bi bi-play-circle-fill bg-soft-success"></i>
                        <span>Start Trip</span>
                    </button>
                    <button class="action-item endTripBtn" data-id="{{ $task->id }}" {{ $task->status!=2?'disabled':'' }}>
                        <i class="bi bi-stop-circle-fill bg-soft-warning"></i>
                        <span>End Trip</span>
                    </button>
                    <button class="action-item remarkViewBtn" data-id="{{ $task->id }}">
                        <i class="bi bi-clock-history bg-soft-info"></i>
                        <span>History</span>
                    </button>
                    <button class="action-item remarkBtn" data-id="{{ $task->id }}">
                        <i class="bi bi-plus-circle-fill" style="background:rgba(255,255,255,0.1)"></i>
                        <span>Update</span>
                    </button>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ================= REMARK MODAL ================= --}}
<div class="modal fade" id="remarkViewModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content bg-dark text-white" style="border-radius:20px; border:1px solid #444;">
            <div class="modal-header border-secondary">
                <h6 class="modal-title text-warning fw-bold">TRIP UPDATE HISTORY</h6>
                <button class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-0">
                <div id="remarkChatBox" style="max-height:400px; overflow-y:auto; padding:20px;"></div>
            </div>
        </div>
    </div>
</div>

<script>
    /* AJAX Header Setup */
    $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': "{{ csrf_token() }}" } });

    /* START TRIP */
    $(document).on('click','.startTripBtn',function(){
        let id=$(this).data('id');
        Swal.fire({
            title: 'Ready to Start?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#28a745',
            confirmButtonText: 'Yes, Start Trip'
        }).then((result) => {
            if (result.isConfirmed) {
                $.post("{{ url('driver_task_start') }}/"+id, function(res){
                    location.reload();
                });
            }
        });
    });

    /* END TRIP */
    $(document).on('click','.endTripBtn',function(){
        let id=$(this).data('id');
        Swal.fire({
            title: 'End this Trip?',
            text: "Please ensure you have reached the destination.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ffc107',
            confirmButtonText: 'Yes, End Now'
        }).then((result) => {
            if (result.isConfirmed) {
                $.post("{{ url('driver_task_end') }}/"+id, function(res){
                    location.reload();
                });
            }
        });
    });

    /* ADD REMARK */
    $(document).on('click','.remarkBtn',function(){
        let id=$(this).data('id');
        Swal.fire({
            title: 'Trip Update',
            input: 'textarea',
            inputPlaceholder: 'Enter any update or remark here...',
            showCancelButton: true,
            confirmButtonText: 'Save Update'
        }).then((result) => {
            if (result.isConfirmed && result.value) {
                $.post("{{ url('driver_task_remark') }}/"+id, {remark: result.value}, function(res){
                    Swal.fire('Saved!', '', 'success').then(()=>location.reload());
                });
            }
        });
    });

    /* VIEW REMARKS */
    $(document).on('click','.remarkViewBtn',function(){
        let id=$(this).data('id');
        $('#remarkChatBox').html('<div class="text-center py-5"><div class="spinner-border text-warning spinner-border-sm"></div></div>');
        const modal = new bootstrap.Modal(document.getElementById('remarkViewModal'));
        modal.show();

        $.get("{{ url('driver-task-remarks') }}/"+id, function(res){
            $('#remarkChatBox').html('');
            if(res.length === 0){
                $('#remarkChatBox').html('<div class="text-center text-muted py-5">No history found.</div>');
                return;
            }
            res.forEach(r => {
                $('#remarkChatBox').append(`
                    <div style="background:rgba(255,255,255,0.04); border-radius:12px; padding:15px; margin-bottom:12px; border:1px solid rgba(255,255,255,0.05)">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="text-warning fw-bold" style="font-size:12px">${r.user_name} <small class="text-muted">(${r.user_role})</small></span>
                            <span class="text-muted" style="font-size:10px">${r.created_at}</span>
                        </div>
                        <div style="font-size:13px; color:#ddd;">${r.remark}</div>
                    </div>
                `);
            });
        });
    });
</script>

@endsection