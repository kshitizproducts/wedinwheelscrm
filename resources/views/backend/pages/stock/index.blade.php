@extends('backend.layouts.main')
@section('main-section')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <!-- SweetAlert2 CSS (optional) -->
    {{-- tables --}}



    <section class="dashboard container-fluid py-4">
        <div
            class="d-flex justify-content-between align-items-center mb-4 bg-dark p-3 rounded-3 shadow-sm border-start border-warning border-4">
            <div>
                <h4 class="text-warning fw-bold mb-0">Inventory Management</h4>
                <p class="text-white-50 small mb-0">Track and manage your product stock efficiently</p>
            </div>
            <button class="btn btn-warning btn-md text-dark fw-bold rounded-pill shadow-sm px-4" data-bs-toggle="modal"
                data-bs-target="#stockModal" onclick="$('#addstockform')[0].reset(); $('#stock_id').val('');">
                <i class="fas fa-plus-circle me-2"></i>New Stock
            </button>
        </div>

        <div class="card bg-dark border-0 shadow-lg">
            <div class="card-body p-4">
                <div class="table-responsive">
                    <table id="stockTable" class="table table-dark table-hover align-middle mb-0 custom-table w-100">
                        <thead>
                            <tr class="text-warning border-secondary">
                                <th class="ps-3">#</th>
                                <th>Product Details</th>
                                <th>Company</th>
                                <th>Product Category</th>
                                <th>Item</th>
                                <th>Status</th>
                                <th>Pricing</th>
                                <th>Warranty</th>
                                <th class="text-center">Media/Docs</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="text-white">
                            @foreach ($stocks as $key => $item)
                                <tr>
                                    <td class="text-white-50 small ps-3">{{ $key + 1 }}</td>
                                  <td>
    <div class="fw-bold text-white mb-0">{{ $item->product_name ?? 'NA' }}</div>
    
    <a href="{{ url('print-invoice/'.$item->id) }}" target="_blank" class="text-warning extra-small font-monospace text-decoration-none hover-glow">
        <i class="fas fa-print me-1"></i> {{ $item->unique_id ?? 'NA' }}
    </a>

    <div class="text-white-50 small">Receipt: <span class="text-white">{{ $item->receipt_no ?? 'NA' }}</span></div>
</td>
                                    <td>{{ $item->company_name ?? 'NA' }}</td>
                                    <td>{{ $item->category_name ?? 'NA' }}</td>
                                    <td>

                                        @php
                                        $item_d= DB::table('product_item_master')->where('id',$item->item_id)->first();
                                        @endphp
                                        {{ $item_d->item_name ?? 'NA' }}

                                    </td>
                                    <td>
                                        
                                        <span
                                            class="badge {{ $item->condition_type == 'new' ? 'bg-success text-white' : 'bg-info text-dark' }} rounded-pill px-3">
                                            {{ strtoupper($item->condition_type ?? 'NA') }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="fw-bold text-white">
                                            ₹{{ number_format((float) ($item->purchase_price ?? 0)) }}</div>
                                        <div class="text-white-50 extra-small text-decoration-line-through">
                                            ₹{{ number_format((float) ($item->mrp ?? 0)) }}</div>
                                    </td>
                                    <td>
                                        @php
                                            $endDate = $item->warranty_end_date ?? null;
                                            $isExpired = $endDate && $endDate < date('Y-m-d');
                                        @endphp
                                        <div
                                            class="d-flex align-items-center {{ $isExpired ? 'text-danger' : 'text-success' }}">
                                            <i
                                                class="fas {{ $isExpired ? 'fa-calendar-times' : 'fa-calendar-check' }} me-2"></i>
                                            <span
                                                class="small fw-bold">{{ $endDate ? date('d M, Y', strtotime($endDate)) : 'NA' }}</span>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <div class="d-flex justify-content-center gap-2">
                                            @if ($item->product_img && $item->product_img != 'NA')
                                                <a target="_blank"
                                                    href="{{ asset('uploads/stock/' . explode(',', $item->product_img)[0]) }}"
                                                    class="media-preview shadow-sm border border-secondary rounded overflow-hidden">
                                                    <img src="{{ asset('uploads/stock/' . explode(',', $item->product_img)[0]) }}"
                                                        alt="img"
                                                        style="width: 35px; height: 35px; object-fit: cover;">
                                                </a>
                                            @endif

                                            @if ($item->product_video && $item->product_video != 'NA')
                                                <a target="_blank"
                                                    href="{{ asset('uploads/stock/' . $item->product_video) }}"
                                                    class="btn btn-sm btn-outline-danger border-0 p-1" title="View Video">
                                                    <i class="fas fa-play-circle fa-lg"></i>
                                                </a>
                                            @endif

                                            @if (($item->warranty_card && $item->warranty_card != 'NA') || ($item->invoice_file && $item->invoice_file != 'NA'))
                                                <a target="_blank"
                                                    href="{{ asset('uploads/stock/' . ($item->warranty_card != 'NA' ? $item->warranty_card : explode(',', $item->invoice_file)[0])) }}"
                                                    class="btn btn-sm btn-outline-warning border-0 p-1"
                                                    title="View Documents">
                                                    <i class="fas fa-file-pdf fa-lg"></i>
                                                </a>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        {{-- <div class="d-flex justify-content-center gap-2">
                                            <button class="btn btn-sm btn-action edit-btn" data-id="{{ $item->id }}"
                                                data-product_name="{{ $item->product_name }}"
                                                data-receipt_no="{{ $item->receipt_no }}"
                                                data-condition="{{ $item->condition_type }}"
                                                data-mrp="{{ $item->mrp }}" data-price="{{ $item->purchase_price }}"
                                                data-w_start="{{ $item->warranty_start_date }}"
                                                data-w_years="{{ $item->warranty_years }}"
                                                data-seller="{{ $item->seller_name }}"
                                                data-seller_contact="{{ $item->seller_contact }}"
                                                data-payer="{{ $item->payer_name }}"
                                                data-payer_contact="{{ $item->payer_contact }}"
                                                data-receiver="{{ $item->receiver_name }}"
                                                data-receiver_contact="{{ $item->receiver_contact }}">
                                                <i class="fas fa-pen"></i>
                                            </button>
                                            <button class="btn btn-sm btn-action delete-btn text-danger"
                                                data-id="{{ $item->id }}">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div> --}}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>


   

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        // Use 'jQuery' instead of '$' to avoid conflicts with other libraries
        jQuery(document).ready(function($) {
            console.log("DataTable loading..."); // Console me check karein ye print ho raha h ya nahi

            if ($.fn.DataTable) {
                $('#stockTable').DataTable({
                    "pageLength": 10,
                    "lengthMenu": [10, 25, 50, 100],
                    "responsive": true,
                    "dom": '<"d-flex justify-content-between align-items-center mb-3"lf>rt<"d-flex justify-content-between align-items-center mt-3"ip>',
                    "language": {
                        "search": "",
                        "searchPlaceholder": "Search inventory...",
                        "paginate": {
                            "previous": "<i class='fas fa-angle-left'></i>",
                            "next": "<i class='fas fa-angle-right'></i>"
                        }
                    }
                });
            } else {
                console.error("DataTable plugin not loaded!");
            }
        });
    </script>

    <style>

        /* Search box ko wide aur yellow border dene ke liye */
.dataTables_filter {
    width: 100%;
}
.dataTables_filter input {
    width: 300px !important;
    border: 1px solid #444 !important;
}
.dataTables_filter input:focus {
    border-color: #ffc107 !important;
    outline: none;
    box-shadow: 0 0 5px rgba(255, 193, 7, 0.3);
}

/* Pagination buttons spacing */
.dataTables_paginate .paginate_button {
    padding: 0px !important;
    margin: 0px 2px !important;
    border: none !important;
}



        .btn-action {
            background: #1e1e1e;
            color: #ffc107;
            border: 1px solid #333;
            transition: 0.3s;
        }

        .btn-action:hover {
            background: #ffc107;
            color: #000;
        }

        .media-preview {
            display: inline-block;
            transition: transform 0.2s;
        }

        .media-preview:hover {
            transform: scale(1.2);
            border-color: #ffc107 !important;
        }

        /* DataTable Pagination & Search Styling */
        .dataTables_filter input {
            background: #1e1e1e !important;
            color: #fff !important;
            border: 1px solid #333 !important;
            border-radius: 20px !important;
            padding: 5px 15px !important;
        }

        .dataTables_length select {
            background: #1e1e1e !important;
            color: #fff !important;
            border: 1px solid #333 !important;
        }

        .page-item.active .page-link {
            background-color: #ffc107 !important;
            border-color: #ffc107 !important;
            color: #000 !important;
        }

        .page-link {
            background-color: #1e1e1e !important;
            border-color: #333 !important;
            color: #ffc107 !important;
        }
    </style>

    {{-- end of tables --}}
    <div class="modal fade bg-dark" id="stockModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div style="background-color:#000" class="modal-content bg-dark-custom text-white border-0 shadow-lg ">
                <div class="modal-header border-secondary-subtle py-3">
                    <h5 class="modal-title text-warning fw-bold"><i class="fas fa-box-open me-2"></i>Inventory Entry</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>

                <div class="progress d-none" style="height: 4px; border-radius: 0;">
                    <div id="uploadProgress" class="progress-bar bg-warning progress-bar-striped progress-bar-animated"
                        role="progressbar" style="width: 0%"></div>
                </div>
              {{-- addition form ui starts --}}

                <style>
    /* Dark Theme Styles */
    #addstockform {
        background-color: #1a1a1a; 
        color: #ffffff;
        padding: 20px;
        border-radius: 8px;
    }

    #addstockform .form-control, 
    #addstockform .form-select,
    #addstockform .input-group-text {
        background-color: #2d2d2d !important;
        color: #ffffff !important;
        border: 1px solid #444;
    }

    #addstockform label {
        color: #e0e0e0;
    }

    /* Required field - Default Red Border */
    #addstockform input[required]:invalid, 
    #addstockform select[required]:invalid {
        border: 2px solid #ff4d4d !important;
    }

    /* Required field - Green Border when filled */
    #addstockform input[required]:valid, 
    #addstockform select[required]:valid {
        border: 2px solid #28a745 !important;
    }

    /* Non-required fields - Normal Border */
    #addstockform input:not([required]), 
    #addstockform select:not([required]) {
        border: 1px solid #666 !important;
    }

    #addstockform input:focus, #addstockform select:focus {
        box-shadow: 0 0 5px rgba(255, 193, 7, 0.5);
        outline: none;
    }



    /* 1. Normal State Control (Dark Grey) */
#addstockform .form-control, 
#addstockform .form-select {
    background-color: #1e1e1e !important; /* Soft Dark Grey */
    color: #ffffff !important;
}

/* 2. Browser Auto-fill Fix (Jo white box dikh raha hai uske liye) */
#addstockform input:-webkit-autofill,
#addstockform input:-webkit-autofill:hover, 
#addstockform input:-webkit-autofill:focus,
#addstockform input:-webkit-autofill:active {
    -webkit-box-shadow: 0 0 0 30px #1e1e1e inset !important; /* Background color fix */
    -webkit-text-fill-color: white !important; /* Text color fix */
    transition: background-color 5000s ease-in-out 0s;
}

/* 3. File Input Control (Choose File button styling) */
#addstockform input[type="file"]::file-selector-button {
    background-color: #333 !important;
    color: #ffc107 !important;
    border: 1px solid #444;
    padding: 5px 10px;
    border-radius: 4px;
    cursor: pointer;
}

#addstockform input[type="file"] {
    background-color: #1e1e1e !important;
    color: #757575 !important; /* "No file chosen" text color */
}

/* 4. Dropdown (Select) options control */
#addstockform select option {
    background-color: #1e1e1e !important;
    color: white !important;
}
</style>

<form id="addstockform" action="{{ url('store-stock') }}" method="post" enctype="multipart/form-data">
    @csrf
    <input type="hidden" name="id" id="stock_id">

    <div id="form-progress-container" class="d-none mb-3">
        <div class="progress" style="height: 10px; background-color: #333;">
            <div id="form-progress-bar" class="progress-bar progress-bar-striped progress-bar-animated bg-warning" role="progressbar" style="width: 0%"></div>
        </div>
        <small class="text-warning mt-1 d-block text-center">Saving record, please wait...</small>
    </div>

    <div class="modal-body p-4">
        <div class="row g-4">
            <div class="col-lg-8">
                <div class="row g-3">
                    <div class="col-md-5">
                        <label class="form-label fw-semibold">Product Name *</label>
                        <input required type="text" name="product_name" class="form-control shadow-sm">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Receipt No. *</label>
                        <input required type="text" name="receipt_no" class="form-control shadow-sm">
                    </div>

                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Condition</label>
                        <select class="form-select shadow-sm" name="condition_type">
                            <option value="new">Brand New</option>
                            <option value="second-hand">Pre-owned</option>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Company</label>
                        <select class="form-select shadow-sm" name="company_id">
                            @foreach ($company_list as $company)
                                <option value="{{ $company->id }}">{{ $company->company_name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Product Category *</label>
                        <select class="form-select shadow-sm" name="category_id" id="main_category_id" required onchange="fetchItems(this.value)">
                            <option value="">-- Select Category --</option>
                            @foreach ($category_master as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Select Item *</label>
                        <select class="form-select shadow-sm" name="item_id" id="item_dropdown" required>
                            <option value="">-- First Select Category --</option>
                        </select>
                    </div>

                    {{-- <div class="col-md-6">
                        <label class="form-label fw-semibold">Warranty Start Date</label>
                        <input type="date" name="warranty_start_date" id="w_start" class="form-control shadow-sm">
                    </div>

                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Warranty Period</label>
                        <select id="w_years" name="warranty_years" class="form-select shadow-sm">
                            <option value="0">No Warranty</option>
                            @for ($i = 1; $i <= 10; $i++)
                                <option value="{{ $i }}">{{ $i }} Year{{ $i > 1 ? 's' : '' }}</option>
                            @endfor
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label fw-semibold text-danger">Warranty End</label>
                        <input type="text" id="w_end_text" class="form-control bg-light shadow-sm" readonly>
                        <input type="hidden" name="warranty_end_date" id="w_end_db">
                    </div> --}}
<div class="col-md-4">
    <label class="form-label fw-semibold">Warranty Start Date</label>
    <input type="date" name="warranty_start_date" id="w_start" class="form-control shadow-sm">
</div>

<div class="col-md-2">
    <label class="form-label fw-semibold">Years</label>
    <select id="w_years" name="warranty_years" class="form-select shadow-sm">
        @for ($i = 0; $i <= 10; $i++)
            <option value="{{ $i }}">{{ $i }} Yr</option>
        @endfor
    </select>
</div>

<div class="col-md-2">
    <label class="form-label fw-semibold">Months</label>
    <select id="w_months" name="warranty_months" class="form-select shadow-sm">
        @for ($i = 0; $i <= 11; $i++)
            <option value="{{ $i }}">{{ $i }} Mo</option>
        @endfor
    </select>
</div>

<div class="col-md-4">
    <label class="form-label fw-semibold text-danger">Warranty End Date</label>
    <input type="text" id="w_end_text" class="form-control shadow-sm" readonly style="background-color: #121212 !important; color: #ff4444 !important; font-weight: bold;">
    <input type="hidden" name="warranty_end_date" id="w_end_db">
</div>
<script>
    document.addEventListener("DOMContentLoaded", function() {
    const startDate = document.getElementById("w_start");
    const yearsSelect = document.getElementById("w_years");
    const monthsSelect = document.getElementById("w_months");
    const endText = document.getElementById("w_end_text");
    const endDB = document.getElementById("w_end_db");

    function calculateWarranty() {
        const dateVal = startDate.value;
        const years = parseInt(yearsSelect.value) || 0;
        const months = parseInt(monthsSelect.value) || 0;

        if (!dateVal || (years === 0 && months === 0)) {
            endText.value = "No Warranty";
            endDB.value = "";
            return;
        }

        let start = new Date(dateVal);
        
        // Months aur Years dono add karein
        start.setFullYear(start.getFullYear() + years);
        start.setMonth(start.getMonth() + months);

        // Formating for Display (DD-MM-YYYY)
        let day = String(start.getDate()).padStart(2, '0');
        let month = String(start.getMonth() + 1).padStart(2, '0');
        let year = start.getFullYear();

        endText.value = `${day}-${month}-${year}`;
        
        // Formating for Database (YYYY-MM-DD)
        endDB.value = `${year}-${month}-${day}`;
    }

    // Event listeners teeno inputs par lagayein
    startDate.addEventListener("change", calculateWarranty);
    yearsSelect.addEventListener("change", calculateWarranty);
    monthsSelect.addEventListener("change", calculateWarranty);
});
</script>

                    {{-- ///////////// --}}

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">MRP</label>
                        <div class="input-group shadow-sm">
                            <span class="input-group-text">₹</span>
                            <input type="number" min="0" name="mrp" class="form-control">
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Purchase Price</label>
                        <div class="input-group shadow-sm">
                            <span class="input-group-text">₹</span>
                            <input type="number" min="0" name="purchase_price" class="form-control">
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 border-start border-secondary">
                <div class="mb-3">
                    <label class="form-label fw-semibold">Product Photos *</label>
                    <input required type="file" id="product_img" name="product_img[]" class="form-control" multiple accept="image/*">
                    <div id="img_preview" class="d-flex flex-wrap gap-2 mt-2"></div>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Product Video</label>
                    <input type="file" name="product_video" class="form-control" accept="video/*">
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Warranty Card</label>
                    <input type="file" name="warranty_card" class="form-control" accept="image/*,application/pdf">
                </div>

                <div>
                    <label class="form-label fw-semibold">Invoices</label>
                    <input type="file" id="doc_file" name="invoice_file[]" class="form-control" multiple>
                    <div id="doc_preview" class="mt-2 small text-secondary"></div>
                </div>
            </div>
        </div>

        <div class="row g-3 mt-4 pt-3 border-top border-secondary">
            {{-- <div class="col-md-4">
                <label class="fw-semibold small">Seller Name *</label>
                <input required type="text" name="seller_name" class="form-control form-control-sm mb-1">
                <label class="fw-semibold small">Seller Contact *</label>
                <input required type="text" name="seller_contact" class="form-control form-control-sm">
            </div> --}}

<div class="col-md-4">
    <label class="fw-semibold small text-warning">Select Seller *</label>
    <select name="seller_id" id="seller_id_select" class="form-select form-select-sm mb-2 bg-dark text-white border-secondary shadow-none" required onchange="handleSellerChange(this)">
        <option value="">-- Choose Seller --</option>
        @foreach($seller_masters as $seller)
            <option value="{{ $seller->id }}" data-contact="{{ $seller->seller_contact }}">{{ $seller->seller_name }}</option>
        @endforeach
        <option value="0">+ Add Other Seller</option>
    </select>

    <div id="other_seller_fields" class="d-none">
        <label class="fw-semibold extra-small text-white-50">Seller Name *</label>
        <input type="text" name="seller_name" id="custom_seller_name" class="form-control form-control-sm mb-1 bg-dark text-white border-secondary shadow-none">
    </div>

    <label class="fw-semibold small text-white-50">Seller Contact/Email *</label>
    <input required type="text" name="seller_contact" id="seller_contact_input" class="form-control form-control-sm bg-dark text-white border-secondary shadow-none" placeholder="Email or Phone">
</div>

<script>
function handleSellerChange(select) {
    const otherFields = document.getElementById('other_seller_fields');
    const contactInput = document.getElementById('seller_contact_input');
    const customNameInput = document.getElementById('custom_seller_name');
    
    // Get selected option data
    const selectedOption = select.options[select.selectedIndex];
    const contactData = selectedOption.getAttribute('data-contact');

    if (select.value === '0') {
        // Show Name field, Clear Contact, Enable Manual Entry
        otherFields.classList.remove('d-none');
        contactInput.value = '';
        contactInput.readOnly = false;
        customNameInput.required = true;
        contactInput.placeholder = "Enter Email or Phone";
    } else if (select.value !== "") {
        // Hide Name field, Auto-fill Contact, Make Read-only
        otherFields.classList.add('d-none');
        contactInput.value = contactData;
        contactInput.readOnly = true;
        customNameInput.required = false;
    } else {
        // Reset everything
        otherFields.classList.add('d-none');
        contactInput.value = '';
        contactInput.readOnly = false;
        customNameInput.required = false;
    }
}
    </script>
            {{--  --}}
            <div class="col-md-4">
                <label class="fw-semibold small">Payer Name *</label>
                <input required type="text" name="payer_name" class="form-control form-control-sm mb-1">
                <label class="fw-semibold small">Payer Contact *</label>
                <input required type="text" name="payer_contact" class="form-control form-control-sm">
            </div>

            <div class="col-md-4">
                <label class="fw-semibold small">Receiver Name *</label>
                <input required type="text" name="receiver_name" class="form-control form-control-sm mb-1">
                <label class="fw-semibold small">Receiver Contact *</label>
                <input required type="text" name="receiver_contact" class="form-control form-control-sm">
            </div>

            <div class="col-12">
                <button type="button" id="submit-btn" onclick="confirmAndSave()" class="btn btn-warning w-100 fw-bold py-2 shadow-sm">SAVE RECORD</button>
            </div>
        </div>
    </div>
</form>

<script>
    // Preview function before final submit
    function confirmAndSave() {
        const form = document.getElementById('addstockform');

        if (!form.checkValidity()) {
            form.reportValidity();
            return;
        }

        // Collecting data for Preview
        const pName = form.querySelector('[name="product_name"]').value;
        const pPrice = form.querySelector('[name="purchase_price"]').value || 'N/A';
        const sName = form.querySelector('[name="seller_name"]').value;

        Swal.fire({
            title: 'Cross Check Details',
            html: `
                <div style="text-align: left; font-size: 14px;">
                    <p><b>Product:</b> ${pName}</p>
                    <p><b>Price:</b> ₹${pPrice}</p>
                    <p><b>Seller:</b> ${sName}</p>
                    <hr>
                    <p class="text-danger"><b>Note:</b> You cannot change these details later. Please verify everything!</p>
                </div>
            `,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ffc107',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, Save it!',
            cancelButtonText: 'Review Again'
        }).then((result) => {
            if (result.isConfirmed) {
                execute_ajax_submit();
            }
        });
    }

    function execute_ajax_submit() {
        const form = document.getElementById('addstockform');
        const formData = new FormData(form);
        const submitBtn = document.getElementById('submit-btn');
        const progressContainer = document.getElementById('form-progress-container');
        const progressBar = document.getElementById('form-progress-bar');

        progressContainer.classList.remove('d-none');
        submitBtn.disabled = true;

        const xhr = new XMLHttpRequest();
        xhr.open('POST', form.action, true);
        xhr.setRequestHeader('X-CSRF-TOKEN', document.querySelector('input[name="_token"]').value);
        xhr.setRequestHeader('Accept', 'application/json');

        xhr.upload.onprogress = function(e) {
            if (e.lengthComputable) {
                const percentComplete = (e.loaded / e.total) * 100;
                progressBar.style.width = percentComplete + '%';
            }
        };

        xhr.onload = function() {
            const response = JSON.parse(xhr.responseText);
            if (xhr.status === 200 && response.success) {
                Swal.fire({ icon: 'success', title: 'Saved!', text: response.message, timer: 1500 });
                setTimeout(() => location.reload(), 1500);
            } else {
                resetFormState(submitBtn, progressContainer, progressBar);
                let errorMsg = response.message || "Validation Failed";
                Swal.fire({ icon: 'error', title: 'Error', html: errorMsg });
            }
        };

        xhr.send(formData);
    }

    function resetFormState(btn, container, bar) {
        btn.disabled = false;
        container.classList.add('d-none');
        bar.style.width = '0%';
    }

    // Existing Fetch Items and Warranty Logic
    function fetchItems(catId) {
        const itemDropdown = $('#item_dropdown');
        itemDropdown.html('<option value="">Loading...</option>');
        if (!catId) { itemDropdown.html('<option value="">-- Select Category --</option>'); return; }

        $.ajax({
            url: "{{ url('get-items-by-category') }}/" + catId,
            type: "GET",
            success: function(response) {
                if (response.success && response.data.length > 0) {
                    let options = '<option value="">-- Select Item --</option>';
                    $.each(response.data, function(i, item) {
                        options += `<option value="${item.id}">${item.item_name}</option>`;
                    });
                    itemDropdown.html(options);
                } else {
                    itemDropdown.html('<option value="">No items found</option>');
                }
            }
        });
    }

    // Handle Warranty Calculation & File Previews... (Rest of your code remains same)
</script>



              {{-- end of addition form --}}
            @endsection
            @section('scripts')
                <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
                <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
                <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
                <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

                <script>
                    $(document).ready(function() {
                        // DataTables
                        $('#stockTable').DataTable({
                            "pageLength": 10,
                            "language": {
                                "search": "",
                                "searchPlaceholder": "Search inventory...",
                                "paginate": {
                                    "previous": "<",
                                    "next": ">"
                                }
                            }
                        });

                        // Set Today's Date
                        const d = new Date();
                        const today = d.getFullYear() + '-' + String(d.getMonth() + 1).padStart(2, '0') + '-' + String(d
                            .getDate()).padStart(2, '0');
                        $('#w_start').val(today);

                        // Warranty Calc
                        function doCalc() {
                            const sDate = $('#w_start').val();
                            const yrs = parseInt($('#w_years').val());
                            if (sDate && !isNaN(yrs)) {
                                const dt = new Date(sDate);
                                dt.setFullYear(dt.getFullYear() + yrs);
                                const dd = String(dt.getDate()).padStart(2, '0');
                                const mm = String(dt.getMonth() + 1).padStart(2, '0');
                                const yyyy = dt.getFullYear();
                                $('#w_end_text').val(`${dd}-${mm}-${yyyy}`);
                                $('#w_end_db').val(`${yyyy}-${mm}-${dd}`);
                            }
                        }
                        $('#w_years, #w_start').on('change', doCalc);

                        // File Preview with FileName Display
                        $('.file-input').on('change', function() {
                            const target = $(this).data('preview');
                            const displayDiv = $('#' + target);
                            displayDiv.empty();

                            const files = this.files;
                            Array.from(files).forEach(file => {
                                const fileNameHtml =
                                    `<div class="extra-small text-warning text-truncate mt-1" style="max-width: 60px;">${file.name}</div>`;

                                if (file.type.startsWith('image/')) {
                                    const reader = new FileReader();
                                    reader.onload = function(e) {
                                        displayDiv.append(`
                                <div class="text-center">
                                    <div class="position-relative preview-card">
                                        <img src="${e.target.result}" class="img-preview-thumb shadow-sm border border-warning">
                                    </div>
                                    ${fileNameHtml}
                                </div>
                            `);
                                    }
                                    reader.readAsDataURL(file);
                                } else {
                                    displayDiv.append(`
                            <div class="text-info extra-small bg-dark p-2 rounded mb-1 border border-secondary">
                                <i class="fas fa-file-alt me-1"></i> ${file.name}
                            </div>
                        `);
                                }
                            });
                        });

                        // AJAX Submission

                    });
                </script>

                <style>
                    :root {
                        --dark-bg: #121212;
                        --card-bg: #1e1e1e;
                        --accent-warning: #ffc107;
                    }

                    body {
                        background: #0b0b0b;
                    }

                    .bg-dark-custom {
                        background-color: var(--card-bg);
                    }

                    .extra-small {
                        font-size: 0.65rem;
                    }

                    .label-custom {
                        font-size: 0.75rem;
                        font-weight: 600;
                        text-transform: uppercase;
                        color: #adb5bd;
                        letter-spacing: 0.5px;
                    }

                    .input-custom {
                        background-color: #2b2b2b !important;
                        border: 1px solid #3d3d3d !important;
                        color: white !important;
                        font-size: 0.9rem;
                        transition: 0.3s;
                    }

                    .input-custom:focus {
                        border-color: var(--accent-warning) !important;
                        box-shadow: 0 0 0 0.25rem rgba(255, 193, 7, 0.1);
                    }

                    .custom-table thead th {
                        background: #252525;
                        border-bottom: 2px solid var(--accent-warning);
                        padding: 15px 10px;
                        font-size: 0.75rem;
                        color: #888;
                    }

                    .custom-table tbody td {
                        border-bottom: 1px solid #2b2b2b;
                        padding: 12px 10px;
                    }

                    .btn-action {
                        background: #2b2b2b;
                        color: #888;
                        border: none;
                        width: 32px;
                        height: 32px;
                        border-radius: 8px;
                        transition: 0.2s;
                    }

                    .edit-btn:hover {
                        background: #ffc107;
                        color: #000;
                    }

                    .delete-btn:hover {
                        background: #dc3545;
                        color: #fff;
                    }

                    .upload-area {
                        border: 2px dashed #3d3d3d;
                        border-radius: 12px;
                        padding: 20px;
                        text-align: center;
                        cursor: pointer;
                        transition: 0.3s;
                    }

                    .upload-area:hover {
                        border-color: var(--accent-warning);
                        background: rgba(255, 193, 7, 0.05);
                    }

                    .img-preview-thumb {
                        width: 55px;
                        height: 55px;
                        object-fit: cover;
                        border-radius: 8px;
                    }

                    .preview-container {
                        max-height: 150px;
                        overflow-y: auto;
                    }
                </style>
