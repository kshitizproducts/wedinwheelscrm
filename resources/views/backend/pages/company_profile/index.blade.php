@extends('backend.layouts.main')

@section('main-section')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="text-warning fw-bold mb-0">Company Profile</h2>
    </div>

    {{-- 1. BASIC DETAILS (Logo & Company Name) --}}
    <div class="card bg-dark text-white shadow-lg border-0 mb-4">
        <div class="card-header border-secondary bg-transparent d-flex justify-content-between align-items-center">
            <h5 class="text-warning mb-0"><i class="fas fa-info-circle me-2"></i>Basic Details</h5>
            <button type="button" class="btn btn-warning btn-sm" onclick="toggleEdit('basic')">Update Profile</button>
        </div>
        <div class="card-body">
            @if(session('success_basic'))
                <div class="alert alert-success border-0 bg-success text-white">{{ session('success_basic') }}</div>
            @endif
            <form method="POST" action="{{ route('basic_update') }}" enctype="multipart/form-data">
                @csrf
                <div class="row align-items-center g-4">
                    <div class="col-md-auto text-center">
                        <img src="{{ asset($doc->profile_picture ?? 'assets/img/default-logo.png') }}" class="rounded-3 shadow" style="width:120px; height:120px; object-fit:cover; border: 2px solid #ffc107;">
                        <div class="edit-basic d-none mt-2">
                            <input type="file" name="profile_picture" class="form-control form-control-sm">
                        </div>
                    </div>
                    <div class="col-md">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="text-warning small d-block">COMPANY NAME</label>
                                <h5 class="view-basic mb-0 text-white">{{ $doc->company_name ?? '—' }}</h5>
                                <input type="text" name="company_name" value="{{ $doc->company_name ?? '' }}" class="form-control edit-basic d-none">
                            </div>
                            <div class="col-md-4">
                                <label class="text-warning small d-block">INDUSTRY</label>
                                <span class="view-basic">{{ $doc->industry ?? '—' }}</span>
                                <input type="text" name="industry" value="{{ $doc->industry ?? '' }}" class="form-control edit-basic d-none">
                            </div>
                            <div class="col-md-2 text-center">
                                <label class="text-warning small d-block">ESTD</label>
                                <span class="view-basic">{{ $doc->year_established ?? '—' }}</span>
                                <input type="text" name="year_established" value="{{ $doc->year_established ?? '' }}" class="form-control edit-basic d-none">
                            </div>
                            <div class="col-md-2 text-center">
                                <label class="text-warning small d-block">STATUS</label>
                                <span class="badge {{ ($doc->status ?? 1) == 1 ? 'bg-success' : 'bg-danger' }} view-basic">{{ ($doc->status ?? 1) == 1 ? 'Active' : 'Inactive' }}</span>
                                <select name="status" class="form-select form-select-sm edit-basic d-none">
                                    <option value="1" {{ ($doc->status ?? 1) == 1 ? 'selected' : '' }}>Active</option>
                                    <option value="0" {{ ($doc->status ?? 1) == 0 ? 'selected' : '' }}>Inactive</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="text-end mt-3 edit-basic d-none">
                    <button type="submit" class="btn btn-success">Save Profile</button>
                    <button type="button" class="btn btn-secondary" onclick="location.reload()">Cancel</button>
                </div>
            </form>
        </div>
    </div>

    @php
    use Carbon\Carbon;
    $docs = [
        'GST' => 'gst', 'PAN' => 'pan', 'Trade License' => 'trade',
        'MSME' => 'msme', 'Rent Agreement' => 'rent', 'Electricity Bill' => 'electricity'
    ];

    function getStatusBadge($expiry, $notifyDays) {
        if (!$expiry) return ['No Date', 'secondary'];
        $today = Carbon::today();
        $exp = Carbon::parse($expiry);
        $days = $today->diffInDays($exp, false);
        if ($days < 0) return ['Expired', 'danger'];
        if ($days <= $notifyDays) return ['Expiring Soon', 'warning'];
        return ['Valid', 'success'];
    }
    @endphp

    <div class="row g-4">
        {{-- 2. LEGAL & COMPLIANCE SECTION --}}
        <div class="col-lg-12">
            <div class="card bg-dark text-white shadow-lg border-0">
                <div class="card-header border-secondary bg-transparent d-flex justify-content-between align-items-center">
                    <h5 class="text-warning mb-0"><i class="fas fa-file-invoice me-2"></i>Legal & Compliance</h5>
                    <button type="button" class="btn btn-warning btn-sm" onclick="toggleEdit('legal')">Update Legal Docs</button>
                </div>
                <div class="card-body">
                    @if(session('success_legal'))
                        <div class="alert alert-success alert-dismissible fade show bg-success text-white border-0">{{ session('success_legal') }}</div>
                    @endif
                    <form method="POST" action="{{ route('legal_update') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="table-responsive">
                            <table class="table table-dark table-hover align-middle border-secondary">
                                <thead class="text-muted small">
                                    <tr>
                                        <th>Document</th>
                                        <th>ID Number</th>
                                        <th>Expiry Date</th>
                                        <th>Notify Days</th>
                                        <th>Status</th>
                                        <th>File</th>
                                        <th class="edit-legal d-none">Replace File</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach($docs as $name => $key)
                                    @php
                                        $numField = ($key == 'rent' || $key == 'electricity') ? null : $key.'_number';
                                        $expiry = $doc->{$key.'_expiry'} ?? null;
                                        $notify = $doc->{$key.'_notify_days'} ?? 7;
                                        [$status,$color] = getStatusBadge($expiry,$notify);
                                    @endphp
                                    <tr>
                                        <td>{{ $name }}</td>
                                        <td>
                                            @if($numField)
                                                <span class="view-legal">{{ $doc->$numField ?? '—' }}</span>
                                                <input type="text" name="{{ $numField }}" value="{{ $doc->$numField ?? '' }}" class="form-control form-control-sm edit-legal d-none">
                                            @else <small class="text-muted">N/A</small> @endif
                                        </td>
                                        <td>
                                            <span class="view-legal">{{ $expiry ?? '—' }}</span>
                                            <input type="date" name="{{ $key }}_expiry" value="{{ $expiry }}" class="form-control form-control-sm edit-legal d-none">
                                        </td>
                                        <td>
                                            <span class="view-legal">{{ $notify }}</span>
                                            <input type="number" name="{{ $key }}_notify_days" value="{{ $notify }}" class="form-control form-control-sm edit-legal d-none">
                                        </td>
                                        <td><span class="badge bg-{{ $color }}">{{ $status }}</span></td>
                                        <td>
                                            @if(!empty($doc->{$key.'_file'}))
                                                <a href="{{ asset($doc->{$key.'_file'}) }}" target="_blank" class="btn btn-sm btn-info">View</a>
                                            @else — @endif
                                        </td>
                                        <td class="edit-legal d-none">
                                            <input type="file" name="{{ $key }}_file" class="form-control form-control-sm">
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="text-end mt-3 edit-legal d-none">
                            <button type="submit" class="btn btn-success">Save Changes</button>
                            <button type="button" class="btn btn-secondary" onclick="location.reload()">Cancel</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- 3. BANKING & FINANCE SECTION --}}
        <div class="col-lg-12 mt-4">
            <div class="card bg-dark text-white shadow-lg border-0">
                <div class="card-header border-secondary bg-transparent d-flex justify-content-between align-items-center">
                    <h5 class="text-warning mb-0"><i class="fas fa-university me-2"></i>Banking & Finance</h5>
                    <button type="button" class="btn btn-warning btn-sm" onclick="toggleEdit('bank')">Update Banking</button>
                </div>
                <div class="card-body">
                    @if(session('success_bank'))
                        <div class="alert alert-success alert-dismissible fade show bg-success text-white border-0">{{ session('success_bank') }}</div>
                    @endif
                    <form method="POST" action="{{ route('banking_update') }}">
                        @csrf
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="text-warning small d-block">ACCOUNT HOLDER</label>
                                <p class="mb-0 fw-bold view-bank">{{ $doc->acc_holder ?? 'Kshitiz Kumar' }}</p>
                                <input type="text" name="acc_holder" value="{{ $doc->acc_holder ?? 'Kshitiz Kumar' }}" class="form-control edit-bank d-none">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="text-warning small d-block">IFSC CODE</label>
                                <p class="mb-0 view-bank">{{ $doc->ifsc ?? 'HDFC0923423' }}</p>
                                <input type="text" name="ifsc" value="{{ $doc->ifsc ?? 'HDFC0923423' }}" class="form-control edit-bank d-none">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="text-warning small d-block">ACC NUMBER</label>
                                <p class="mb-0 view-bank">{{ $doc->acc_number ?? '4234823982' }}</p>
                                <input type="text" name="acc_number" value="{{ $doc->acc_number ?? '4234823982' }}" class="form-control edit-bank d-none">
                            </div>
                            <div class="col-md-12">
                                <div class="d-flex align-items-center bg-secondary bg-opacity-25 p-3 rounded">
                                    <div class="me-3">
                                        <img src="https://api.qrserver.com/v1/create-qr-code/?size=80x80&data={{ $doc->upi_id ?? 'salarykind@ybl' }}" class="rounded">
                                    </div>
                                    <div class="flex-grow-1">
                                        <label class="text-warning small d-block">UPI ID</label>
                                        <code class="text-white view-bank">{{ $doc->upi_id ?? 'salarykind@ybl' }}</code>
                                        <input type="text" name="upi_id" value="{{ $doc->upi_id ?? 'salarykind@ybl' }}" class="form-control edit-bank d-none mt-1">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="text-end mt-3 edit-bank d-none">
                            <button type="submit" class="btn btn-success">Save Banking Info</button>
                            <button type="button" class="btn btn-secondary" onclick="location.reload()">Cancel</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", loadBusiness);

    function loadBusiness() {
        fetch(`{{ route('basic_information_get') }}`)
            .then(r => r.json())
            .then(r => {
                const container = document.getElementById('businessContainer');
                container.innerHTML = "";
                if(r.data.length === 0) {
                    container.innerHTML = `<div class="text-center text-muted">No records found.</div>`;
                    return;
                }
                r.data.forEach((b) => {
                    container.insertAdjacentHTML('beforeend', `
                        <div class="row align-items-center g-4 p-2">
                            <div class="col-md-auto text-center">
                                <img src="/${b.profile_picture}" class="rounded-3 shadow" style="width:100px; height:100px; object-fit:cover; border: 2px solid #ffc107;">
                            </div>
                            <div class="col-md">
                                <div class="row g-3">
                                    <div class="col-md-4">
                                        <label class="text-warning small d-block">COMPANY</label>
                                        <h5 class="mb-0 text-white">${b.company_name}</h5>
                                        <span class="text-muted small">${b.display_name}</span>
                                    </div>
                                    <div class="col-6 col-md-2 text-center">
                                        <label class="text-warning small d-block">ESTD</label>
                                        <span>${b.year_established ?? '-'}</span>
                                    </div>
                                    <div class="col-6 col-md-2 text-center">
                                        <label class="text-warning small d-block">STATUS</label>
                                        <span class="badge ${b.status == 1 ? 'bg-success' : 'bg-danger'}">${b.status == 1 ? 'Active' : 'Inactive'}</span>
                                    </div>
                                    <div class="col-md-4 text-md-end">
                                        <button class="btn btn-sm btn-outline-warning" onclick="editBusiness(${b.id})">Update Company Details</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `);
                });
            });
    }

    function toggleEdit(type) {
        document.querySelectorAll(`.edit-${type}`).forEach(el => el.classList.remove('d-none'));
        document.querySelectorAll(`.view-${type}`).forEach(el => el.classList.add('d-none'));
    }
</script>

<style>
    .card { border-radius: 15px; background: #1e1e1e; }
    .form-control { background: #2b2b2b; color: white; border: 1px solid #444; }
    .form-control:focus { background: #333; color: white; border: 1px solid #ffc107; box-shadow: none; }
    .table-dark { --bs-table-bg: #1e1e1e; }
</style>
@endsection