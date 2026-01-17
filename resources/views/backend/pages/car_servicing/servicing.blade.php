@extends('backend.layouts.main')
@section('main-section')

<div class="container-fluid p-3 p-md-4 page-wrap">

    {{-- HEADER --}}
    <div class="d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between gap-3 mb-4">
        <div>
            <h3 class="mb-1 fw-bold text-warning d-flex align-items-center gap-2">
                <span class="icon-badge"><i class="fas fa-tools"></i></span>
                Last Servicing Reports
            </h3>
            <div class="text-secondary small">
                Add / Update / Delete servicing records with invoices
            </div>
        </div>

        <div class="d-flex align-items-center gap-2">
            <span class="badge badge-soft border border-warning text-warning">
                <i class="fas fa-car me-1"></i> Car ID: {{ $id }}
            </span>

            <button class="btn btn-warning fw-bold text-dark rounded-pill px-4"
                data-bs-toggle="collapse" data-bs-target="#addServiceCollapse" aria-expanded="true">
                <i class="fas fa-plus-circle me-1"></i> Add New Service
            </button>
        </div>
    </div>

    {{-- ADD FORM (COLLAPSIBLE) --}}
    <div class="collapse show mb-4" id="addServiceCollapse">
        <div class="card glass-card border border-warning">
            <div class="card-body p-3 p-md-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div class="fw-bold text-white">
                        <i class="fas fa-file-medical me-2 text-warning"></i> New Servicing Entry
                    </div>
                    <span class="badge bg-dark border border-secondary text-secondary">
                        <i class="fas fa-circle-info me-1"></i> All fields are important
                    </span>
                </div>

                <form id="newservicing_form" method="post" action="{{ url('add_servicing') }}"
                      enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" value="{{ $id }}" name="car_id">

                    <div class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label small text-secondary">Service type <span class="text-warning">*</span></label>
                            <select name="service_master" class="form-select form-select-sm dark-input" required>
                                <option selected disabled>Please Select</option>
                                @foreach ($service_master as $sm)
                                    <option value="{{ $sm->id }}">{{ $sm->service_type }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-2">
                            <label class="form-label small text-secondary">Service Date <span class="text-warning">*</span></label>
                            <input type="date" class="form-control form-control-sm dark-input"
                                   name="service_date" required>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label small text-secondary">Garage Name <span class="text-warning">*</span></label>
                            <select name="garage" class="form-select form-select-sm dark-input" required>
                                <option selected disabled>Please Select</option>
                                @foreach ($garage_master as $gm)
                                    <option value="{{ $gm->id }}">{{ $gm->name }} ({{ $gm->location }})</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-2">
                            <label class="form-label small text-secondary">Total Cost</label>
                            <input type="number" class="form-control form-control-sm dark-input"
                                   name="total_amount" id="add_total" placeholder="₹">
                        </div>

                        <div class="col-md-2">
                            <label class="form-label small text-secondary">Amount Paid</label>
                            <input type="number" class="form-control form-control-sm dark-input"
                                   name="amount_paid" id="add_paid" placeholder="₹">
                        </div>

                        <div class="col-md-2">
                            <label class="form-label small text-secondary">Due Amount</label>
                            <input type="number" class="form-control form-control-sm dark-input readonly-input"
                                   name="due_amount" id="add_due" readonly placeholder="₹">
                        </div>

                        <div class="col-md-2">
                            <label class="form-label small text-secondary">Payment Status</label>
                            <select name="payment_status" class="form-select form-select-sm dark-input">
                                <option selected disabled>Select</option>
                                <option value="0">Paid</option>
                                <option value="1">Pending</option>
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label small text-secondary">
                                Invoice <span class="text-secondary">(Multiple allowed)</span>
                            </label>
                            <input type="file" class="form-control form-control-sm dark-input"
                                   name="invoice[]" multiple>
                        </div>

                        <div class="col-md-4 d-flex align-items-end gap-2">
                            <button id="add_btn" onclick="uploadnew_servicing()" type="button"
                                    class="btn btn-success w-100 rounded-pill fw-bold">
                                <i class="fas fa-cloud-upload-alt me-1"></i> Add Record
                            </button>

                            <button type="button" class="btn btn-outline-secondary rounded-pill px-4"
                                    onclick="document.getElementById('newservicing_form').reset(); document.getElementById('add_due').value='';">
                                <i class="fas fa-rotate-left"></i>
                            </button>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>

    {{-- TABLE --}}
    <div class="card glass-card border border-secondary">
        <div class="card-body p-0">

            <div class="p-3 p-md-4 border-bottom border-secondary d-flex flex-column flex-md-row gap-2 justify-content-between">
                <div class="text-white fw-bold">
                    <i class="fas fa-table me-2 text-warning"></i> Servicing History
                </div>

                <div class="d-flex gap-2 flex-wrap">
                    <div class="searchbox">
                        <i class="fas fa-search"></i>
                        <input id="serviceSearch" type="text" class="form-control form-control-sm dark-input"
                               placeholder="Search service / garage / status...">
                    </div>

                    <select id="serviceFilter" class="form-select form-select-sm dark-input" style="max-width: 180px;">
                        <option value="all" selected>All Status</option>
                        <option value="paid">Paid</option>
                        <option value="pending">Pending</option>
                    </select>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-dark table-hover align-middle mb-0" id="serviceTable">
                    <thead class="thead-sticky">
                        <tr>
                            <th style="min-width:140px;">Service</th>
                            <th style="min-width:120px;">Date</th>
                            <th style="min-width:220px;">Garage</th>
                            <th style="min-width:120px;">Cost</th>
                            <th style="min-width:120px;">Paid</th>
                            <th style="min-width:120px;">Due</th>
                            <th style="min-width:140px;">Status</th>
                            <th style="min-width:180px;">Invoices</th>
                            <th style="min-width:160px;">Action</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($car_service_data as $service)
                            @php
                                $s_data = DB::table('service_master')->where('id', $service->service_type_id)->first();
                                $g_data = DB::table('garage_master')->where('id', $service->garage_id)->first();

                                $statusText = ($service->status == 0) ? 'Paid' : 'Pending';
                                $statusKey  = ($service->status == 0) ? 'paid' : 'pending';
                            @endphp

                            <tr data-status="{{ $statusKey }}">
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="mini-dot {{ $service->status == 0 ? 'dot-success' : 'dot-warning' }}"></span>
                                        <span class="fw-semibold text-white">
                                            {{ $s_data->service_type ?? 'N/A' }}
                                        </span>
                                    </div>
                                </td>

                                <td>
                                    <div class="text-white">
                                        {{ \Carbon\Carbon::parse($service->billed_on_date)->format('d M Y') }}
                                    </div>
                                    <div class="text-secondary small">
                                        {{ \Carbon\Carbon::parse($service->billed_on_date)->format('l') }}
                                    </div>
                                </td>

                                <td>
                                    <div class="fw-semibold text-white">{{ $g_data->name ?? 'N/A' }}</div>
                                    <div class="text-secondary small">
                                        <i class="fas fa-location-dot me-1"></i>{{ $g_data->location ?? '' }}
                                    </div>
                                </td>

                                <td class="fw-bold text-info">₹{{ number_format($service->cost ?? 0) }}</td>
                                <td class="fw-bold text-success">₹{{ number_format($service->bill_paid ?? 0) }}</td>

                                <td class="fw-bold {{ ($service->due ?? 0) > 0 ? 'text-warning' : 'text-secondary' }}">
                                    ₹{{ number_format($service->due ?? 0) }}
                                </td>

                                <td>
                                    @if ($service->status == 0)
                                        <span class="badge badge-paid">
                                            <i class="fas fa-check-circle me-1"></i> Paid
                                        </span>
                                    @else
                                        <span class="badge badge-pending">
                                            <i class="fas fa-hourglass-half me-1"></i> Pending
                                        </span>
                                    @endif
                                </td>

                                <td>
                                    @php
                                        $images = json_decode($service->invoice, true);
                                        $sl = 1;
                                    @endphp

                                    @if (!empty($images))
                                        <div class="d-flex flex-wrap gap-1">
                                            @foreach ($images as $img)
                                                <a target="_blank" href="{{ asset($img) }}" class="btn btn-xs btn-outline-info rounded-pill">
                                                    <i class="fas fa-file-invoice me-1"></i> Bill {{ $sl++ }}
                                                </a>
                                            @endforeach
                                        </div>
                                    @else
                                        <span class="text-muted small">No File</span>
                                    @endif
                                </td>

                                <td>
                                    <div class="d-flex gap-2">
                                        <button class="btn btn-sm btn-warning text-dark rounded-pill px-3"
                                            data-bs-toggle="modal" data-bs-target="#editModal{{ $service->id }}">
                                            <i class="fas fa-pen me-1"></i> Edit
                                        </button>

                                        <button id="del_btn{{ $service->id }}"
                                            onclick="delete_servicing({{ $service->id }})"
                                            class="btn btn-sm btn-danger rounded-pill px-3">
                                            <i class="fas fa-trash me-1"></i> Del
                                        </button>

                                        <form id="deleteForm{{ $service->id }}"
                                            action="{{ url('delete_btn_servicing') }}" method="POST" style="display:none;">
                                            @csrf
                                            <input type="hidden" name="table_id" value="{{ $service->id }}">
                                        </form>
                                    </div>

                                    {{-- EDIT MODAL --}}
                                    <div class="modal fade" id="editModal{{ $service->id }}" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog modal-lg modal-dialog-centered">
                                            <div class="modal-content bg-dark text-white border border-warning modal-soft">
                                                <div class="modal-header border-warning">
                                                    <h5 class="modal-title text-warning">
                                                        <i class="fas fa-pen-to-square me-2"></i> Update Service Record
                                                    </h5>
                                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                                </div>

                                                <form id="updateForm{{ $service->id }}" action="{{ url('update_servicing') }}"
                                                      method="POST" enctype="multipart/form-data">
                                                    @csrf
                                                    <input type="hidden" name="table_id" value="{{ $service->id }}">
                                                    <input type="hidden" name="car_id" value="{{ $id }}">

                                                    <div class="modal-body">
                                                        <div class="row g-3">
                                                            <div class="col-md-4">
                                                                <label class="form-label small text-secondary">Service Type</label>
                                                                <select name="service_master" class="form-select dark-input">
                                                                    @foreach ($service_master as $st)
                                                                        <option value="{{ $st->id }}"
                                                                            {{ $service->service_type_id == $st->id ? 'selected' : '' }}>
                                                                            {{ $st->service_type }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                            </div>

                                                            <div class="col-md-4">
                                                                <label class="form-label small text-secondary">Date</label>
                                                                <input type="date" class="form-control dark-input"
                                                                       name="service_date" value="{{ $service->billed_on_date }}">
                                                            </div>

                                                            <div class="col-md-4">
                                                                <label class="form-label small text-secondary">Garage</label>
                                                                <select name="garage" class="form-select dark-input">
                                                                    @foreach ($garage_master as $g)
                                                                        <option value="{{ $g->id }}"
                                                                            {{ $service->garage_id == $g->id ? 'selected' : '' }}>
                                                                            {{ $g->name }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                            </div>

                                                            <div class="col-md-4">
                                                                <label class="form-label small text-secondary">Total Cost</label>
                                                                <input type="number" class="form-control dark-input edit-total"
                                                                       name="total_amount" value="{{ $service->cost }}">
                                                            </div>

                                                            <div class="col-md-4">
                                                                <label class="form-label small text-secondary">Paid</label>
                                                                <input type="number" class="form-control dark-input edit-paid"
                                                                       name="amount_paid" value="{{ $service->bill_paid }}">
                                                            </div>

                                                            <div class="col-md-4">
                                                                <label class="form-label small text-secondary">Due</label>
                                                                <input type="number" class="form-control dark-input readonly-input edit-due"
                                                                       name="due_amount" value="{{ $service->due }}" readonly>
                                                            </div>

                                                            <div class="col-md-4">
                                                                <label class="form-label small text-secondary">Status</label>
                                                                <select name="payment_status" class="form-select dark-input">
                                                                    <option value="0" {{ $service->status == 0 ? 'selected' : '' }}>Paid</option>
                                                                    <option value="1" {{ $service->status == 1 ? 'selected' : '' }}>Pending</option>
                                                                </select>
                                                            </div>

                                                            <div class="col-md-8">
                                                                <label class="form-label small text-secondary">
                                                                    Invoice
                                                                    <small class="text-warning ms-1">(Uploading new files will delete old ones)</small>
                                                                </label>
                                                                <input type="file" class="form-control dark-input" name="invoice[]" multiple>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="modal-footer border-warning">
                                                        <button type="button" class="btn btn-outline-secondary rounded-pill px-4" data-bs-dismiss="modal">
                                                            Close
                                                        </button>

                                                        <button id="upd_btn{{ $service->id }}"
                                                            onclick="update_servicing({{ $service->id }})"
                                                            type="button"
                                                            class="btn btn-warning text-dark fw-bold rounded-pill px-4">
                                                            <i class="fas fa-save me-1"></i> Save Changes
                                                        </button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    {{-- END MODAL --}}

                                </td>
                            </tr>

                        @empty
                            <tr>
                                <td colspan="9">
                                    <div class="text-center p-5">
                                        <div class="text-warning mb-2">
                                            <i class="fas fa-screwdriver-wrench fa-2x opacity-50"></i>
                                        </div>
                                        <h5 class="text-white mb-1">No Servicing Records Found</h5>
                                        <div class="text-secondary">Add first servicing entry from above form.</div>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>
    </div>

</div>

{{-- STYLES --}}
<style>
    .page-wrap{
        background: radial-gradient(800px circle at 10% 0%, rgba(255,193,7,0.08), transparent 45%),
                    radial-gradient(800px circle at 90% 10%, rgba(13,110,253,0.06), transparent 45%),
                    #0f0f0f;
        min-height: 100vh;
    }

    .glass-card{
        background: rgba(30,30,30,0.85);
        border-radius: 16px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.35);
        overflow: hidden;
    }

    .icon-badge{
        width: 40px; height: 40px;
        display: inline-flex; align-items:center; justify-content:center;
        border-radius: 12px;
        background: rgba(255,193,7,0.12);
        border: 1px solid rgba(255,193,7,0.4);
        color: #ffc107;
    }

    .badge-soft{
        padding: 10px 12px;
        border-radius: 12px;
        background: rgba(0,0,0,0.35);
    }

    .dark-input{
        background:#141414 !important;
        border:1px solid rgba(255,255,255,0.10) !important;
        color:#fff !important;
        border-radius: 12px !important;
        outline: none !important;
        box-shadow: none !important;
    }
    .dark-input:focus{
        border-color: rgba(255,193,7,0.6) !important;
        box-shadow: 0 0 0 0.2rem rgba(255,193,7,0.12) !important;
    }
    .readonly-input{
        background: rgba(108,117,125,0.25) !important;
        border-color: rgba(255,255,255,0.12) !important;
    }

    .thead-sticky th{
        position: sticky;
        top: 0;
        z-index: 5;
        background: #1a1a1a !important;
        border-bottom: 1px solid rgba(255,255,255,0.12) !important;
        font-size: 12px;
        text-transform: uppercase;
        letter-spacing: .04em;
        color: rgba(255,255,255,0.85);
    }

    .table-dark{
        --bs-table-bg: transparent;
        --bs-table-striped-bg: rgba(255,255,255,0.02);
        --bs-table-hover-bg: rgba(255,193,7,0.06);
        border-color: rgba(255,255,255,0.07) !important;
    }
    .table td, .table th{ border-color: rgba(255,255,255,0.07) !important; }

    .mini-dot{
        width:10px;height:10px;border-radius:999px;display:inline-block;
        box-shadow: 0 0 0 4px rgba(255,255,255,0.03);
    }
    .dot-success{ background:#198754; box-shadow: 0 0 0 4px rgba(25,135,84,0.15); }
    .dot-warning{ background:#ffc107; box-shadow: 0 0 0 4px rgba(255,193,7,0.15); }

    .badge-paid{
        background: rgba(25,135,84,0.15);
        color: #7dffb0;
        border: 1px solid rgba(25,135,84,0.35);
        border-radius: 999px;
        padding: 7px 10px;
    }
    .badge-pending{
        background: rgba(255,193,7,0.15);
        color: #ffd36b;
        border: 1px solid rgba(255,193,7,0.35);
        border-radius: 999px;
        padding: 7px 10px;
    }

    .btn-xs{
        font-size: 11px;
        padding: 4px 10px;
    }

    .modal-soft{
        border-radius: 18px;
        overflow: hidden;
        box-shadow: 0 20px 60px rgba(0,0,0,0.55);
    }

    .searchbox{
        position: relative;
        min-width: 240px;
    }
    .searchbox i{
        position:absolute;
        top: 50%;
        transform: translateY(-50%);
        left: 12px;
        color: rgba(255,255,255,0.35);
        font-size: 12px;
    }
    .searchbox input{
        padding-left: 34px !important;
    }

    @media(max-width: 576px){
        .searchbox{ min-width: 100%; }
    }
</style>

{{-- SCRIPTS --}}
<script>
    /** 1) AUTO CALC (Add + Edit) **/
    function handleCalculation(totalInput, paidInput, dueInput) {
        if (!totalInput || !paidInput || !dueInput) return;

        const calc = () => {
            const total = parseFloat(totalInput.value) || 0;
            const paid  = parseFloat(paidInput.value) || 0;
            const due = Math.max(total - paid, 0);
            dueInput.value = due;
        };
        totalInput.addEventListener('input', calc);
        paidInput.addEventListener('input', calc);
        calc();
    }

    // Add Form Calc
    handleCalculation(
        document.getElementById('add_total'),
        document.getElementById('add_paid'),
        document.getElementById('add_due')
    );

    // Edit Modals Calc (on modal shown)
    document.querySelectorAll('.modal').forEach(modal => {
        modal.addEventListener('shown.bs.modal', function () {
            handleCalculation(
                modal.querySelector('.edit-total'),
                modal.querySelector('.edit-paid'),
                modal.querySelector('.edit-due')
            );
        });
    });

    /** 2) LOADER BTN **/
    function setBtnState(btn, state) {
        if (!btn) return;
        if (state === 'loading') {
            btn.disabled = true;
            btn.dataset.oldHtml = btn.innerHTML;
            btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Please Wait...';
        } else {
            btn.disabled = false;
            btn.innerHTML = btn.dataset.oldHtml || 'Submit';
        }
    }

    /** 3) ADD AJAX **/
    function uploadnew_servicing() {
        const btn = document.getElementById('add_btn');
        setBtnState(btn, 'loading');

        const form = document.getElementById('newservicing_form');
        const formData = new FormData(form);

        fetch(form.action, {
            method: 'POST',
            body: formData,
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Added!',
                    text: data.message,
                    timer: 1500,
                    showConfirmButton: false
                }).then(() => location.reload());
            } else {
                Swal.fire('Error', data.message || 'Something went wrong', 'error');
                setBtnState(btn, 'reset');
            }
        })
        .catch(() => {
            Swal.fire('Error', 'Request failed', 'error');
            setBtnState(btn, 'reset');
        });
    }

    /** 4) UPDATE AJAX **/
    function update_servicing(id) {
        const btn = document.getElementById('upd_btn' + id);
        setBtnState(btn, 'loading');

        const form = document.getElementById('updateForm' + id);
        const formData = new FormData(form);

        fetch(form.action, {
            method: 'POST',
            body: formData,
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Updated!',
                    text: data.message,
                    timer: 1500,
                    showConfirmButton: false
                }).then(() => location.reload());
            } else {
                Swal.fire('Error', data.message || 'Something went wrong', 'error');
                setBtnState(btn, 'reset');
            }
        })
        .catch(() => {
            Swal.fire('Error', 'Request failed', 'error');
            setBtnState(btn, 'reset');
        });
    }

    /** 5) DELETE AJAX **/
    function delete_servicing(id) {
        Swal.fire({
            title: 'Delete this record?',
            text: "Invoices will also be permanently removed from server!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, Delete!',
            cancelButtonText: 'No'
        }).then((result) => {
            if (result.isConfirmed) {
                const btn = document.getElementById('del_btn' + id);
                setBtnState(btn, 'loading');

                const form = document.getElementById('deleteForm' + id);
                const formData = new FormData(form);

                fetch(form.action, {
                    method: 'POST',
                    body: formData,
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire('Deleted!', data.message, 'success')
                            .then(() => location.reload());
                    } else {
                        Swal.fire('Failed', data.message || 'Something went wrong', 'error');
                        setBtnState(btn, 'reset');
                    }
                })
                .catch(() => {
                    Swal.fire('Error', 'Request failed', 'error');
                    setBtnState(btn, 'reset');
                });
            }
        });
    }

    /** 6) TABLE SEARCH + STATUS FILTER **/
    (function(){
        const searchEl = document.getElementById('serviceSearch');
        const filterEl = document.getElementById('serviceFilter');
        const table = document.getElementById('serviceTable');
        if(!table) return;

        const rows = Array.from(table.querySelectorAll('tbody tr'));

        const apply = () => {
            const q = (searchEl?.value || '').toLowerCase().trim();
            const f = filterEl?.value || 'all';

            rows.forEach(row => {
                // ignore empty-state row
                if(row.querySelector('td[colspan]')) return;

                const status = row.getAttribute('data-status');
                const text = row.innerText.toLowerCase();

                const matchQ = !q || text.includes(q);
                const matchF = (f === 'all') || (status === f);

                row.style.display = (matchQ && matchF) ? '' : 'none';
            });
        };

        searchEl?.addEventListener('input', apply);
        filterEl?.addEventListener('change', apply);
        apply();
    })();
</script>

@endsection
