@extends('backend.layouts.main')

@section('main-section')
<div class="container-fluid py-4">
    <h2 class="text-warning fw-bold mb-4">Company Profile</h2>

    {{-- 1. BASIC DETAILS --}}
    <div class="card bg-dark text-white shadow-lg border-0 mb-4">
        <div class="card-header border-secondary bg-transparent">
            <h5 class="text-warning mb-0"><i class="fas fa-info-circle me-2"></i>Basic Details</h5>
        </div>
        <div class="card-body" id="businessContainer">
            <div class="text-center py-3"><div class="spinner-border text-warning" role="status"></div></div>
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
        {{-- 2. LEGAL SECTION --}}
        <div class="col-lg-12">
            <div class="card bg-dark text-white shadow-lg border-0">
                <div class="card-header border-secondary bg-transparent d-flex justify-content-between align-items-center">
                    <h5 class="text-warning mb-0"><i class="fas fa-file-invoice me-2"></i>Legal & Compliance</h5>
                    <button type="button" class="btn btn-warning btn-sm" onclick="toggleEdit('legal')">Update Docs</button>
                </div>
                <div class="card-body">
                    @if(session('success_legal'))
                        <div class="alert alert-success bg-success text-white border-0">{{ session('success_legal') }}</div>
                    @endif
                    <form method="POST" action="{{ route('legal_update') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="table-responsive">
                            <table class="table table-dark table-hover align-middle border-secondary">
                                <thead class="text-muted small">
                                    <tr>
                                        <th>Document</th><th>ID Number</th><th>Expiry Date</th><th>Notify</th><th>Status</th><th>File</th><th class="edit-legal d-none">Replace</th>
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
                            <button type="submit" class="btn btn-success">Save Legal</button>
                            <button type="button" class="btn btn-secondary" onclick="location.reload()">Cancel</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- 3. BANKING SECTION --}}
        <div class="col-lg-12">
            <div class="card bg-dark text-white shadow-lg border-0">
                <div class="card-header border-secondary bg-transparent d-flex justify-content-between align-items-center">
                    <h5 class="text-warning mb-0"><i class="fas fa-university me-2"></i>Banking & Finance</h5>
                    <button type="button" class="btn btn-warning btn-sm" onclick="toggleEdit('bank')">Update Bank</button>
                </div>
                <div class="card-body">
                    @if(session('success_bank'))
                        <div class="alert alert-success bg-success text-white border-0">{{ session('success_bank') }}</div>
                    @endif
                    <form method="POST" action="{{ route('banking_update') }}">
                        @csrf
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="text-warning small">HOLDER NAME</label>
                                <p class="view-bank fw-bold">{{ $bank->acc_holder ?? '—' }}</p>
                                <input type="text" name="acc_holder" value="{{ $bank->acc_holder ?? '' }}" class="form-control edit-bank d-none">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="text-warning small">IFSC</label>
                                <p class="view-bank">{{ $bank->ifsc ?? '—' }}</p>
                                <input type="text" name="ifsc" value="{{ $bank->ifsc ?? '' }}" class="form-control edit-bank d-none">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="text-warning small">ACC NO</label>
                                <p class="view-bank">{{ $bank->acc_number ?? '—' }}</p>
                                <input type="text" name="acc_number" value="{{ $bank->acc_number ?? '' }}" class="form-control edit-bank d-none">
                            </div>
                            <div class="col-md-12">
                                <div class="d-flex align-items-center bg-secondary bg-opacity-10 p-3 rounded">
                                    <img id="qrImage" src="https://api.qrserver.com/v1/create-qr-code/?size=80x80&data={{ $bank->upi_id ?? 'NA' }}" class="me-3 rounded border border-warning">
                                    <div class="w-100">
                                        <label class="text-warning small">UPI ID</label>
                                        <p class="view-bank mb-0"><code>{{ $bank->upi_id ?? '—' }}</code></p>
                                        <input type="text" id="upiInput" name="upi_id" value="{{ $bank->upi_id ?? '' }}" class="form-control edit-bank d-none" oninput="updateQRCode(this.value)">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="text-end mt-3 edit-bank d-none">
                            <button type="submit" class="btn btn-success">Save Bank</button>
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
        fetch(`{{ route('basic_information_get') }}`).then(r => r.json()).then(r => {
            const container = document.getElementById('businessContainer');
            container.innerHTML = "";
            r.data.forEach((b) => {
                container.insertAdjacentHTML('beforeend', `
                    <div class="row align-items-center g-4 p-2">
                        <div class="col-md-auto text-center"><img src="/${b.profile_picture}" class="rounded-3 shadow" style="width:100px; height:100px; object-fit:cover; border: 2px solid #ffc107;"></div>
                        <div class="col-md">
                            <div class="row g-3">
                                <div class="col-md-4"><label class="text-warning small d-block">COMPANY</label><h5 class="mb-0 text-white">${b.company_name}</h5></div>
                                <div class="col-6 col-md-2 text-center"><label class="text-warning small d-block">ESTD</label><span>${b.year_established ?? '-'}</span></div>
                                <div class="col-6 col-md-2 text-center"><label class="text-warning small d-block">STATUS</label><span class="badge ${b.status == 1 ? 'bg-success' : 'bg-danger'}">${b.status == 1 ? 'Active' : 'Inactive'}</span></div>
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

    function updateQRCode(upiId) {
        const qrImg = document.getElementById('qrImage');
        const data = upiId.trim() !== "" ? encodeURIComponent(upiId) : "NA";
        qrImg.src = `https://api.qrserver.com/v1/create-qr-code/?size=80x80&data=${data}`;
    }
</script>
@endsection