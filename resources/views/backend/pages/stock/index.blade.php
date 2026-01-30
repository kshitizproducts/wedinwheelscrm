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
                                        <div class="text-warning extra-small font-monospace">{{ $item->unique_id ?? 'NA' }}
                                        </div>
                                        <div class="text-white-50 small">Receipt: <span
                                                class="text-white">{{ $item->receipt_no ?? 'NA' }}</span></div>
                                    </td>
                                    <td>{{ $item->company_name ?? 'NA' }}</td>
                                    <td>{{ $item->category_name ?? 'NA' }}</td>
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
                <form id="addstockform" action="{{ url('store-stock') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="id" id="stock_id">

                    <div id="form-progress-container" class="d-none mb-3">
                        <div class="progress" style="height: 10px; background-color: #333;">
                            <div id="form-progress-bar"
                                class="progress-bar progress-bar-striped progress-bar-animated bg-warning"
                                role="progressbar" style="width: 0%"></div>
                        </div>
                        <small class="text-warning mt-1 d-block text-center">Saving record, please wait...</small>
                    </div>

                    <div class="modal-body p-4">
                        <div class="row g-4">

                            <div class="col-lg-8">
                                <div class="row g-3">
                                    <div class="col-md-5">
                                        <label class="form-label fw-semibold">Product Name *</label>
                                        <input required type="text" name="product_name" class="form-control shadow-sm"
                                            required>
                                    </div>

                                    <div class="col-md-4">
                                        <label class="form-label fw-semibold">Receipt No.</label>
                                        <input required type="text" name="receipt_no" class="form-control shadow-sm">
                                    </div>

                                    <div class="col-md-3">
                                        <label class="form-label fw-semibold">Condition</label>
                                        <select class="form-select shadow-sm" name="condition_type">
                                            <option value="new">Brand New</option>
                                            <option value="second-hand">Pre-owned</option>
                                        </select>
                                    </div>




                                    {{--  --}}
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">Company</label>
                                        <select class="form-select shadow-sm" name="company_id">
                                            @foreach ($company_list as $company)
                                                <option value="{{ $company->id }}">{{ $company->company_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>


                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">Product Category</label>
                                        <select class="form-select shadow-sm" name="category_id">
                                            @foreach ($category_master as $category)
                                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    {{--  --}}
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">Warranty Start Date</label>
                                        <input type="date" name="warranty_start_date" id="w_start"
                                            class="form-control shadow-sm">
                                    </div>

                                    <div class="col-md-3">
                                        <label class="form-label fw-semibold">Warranty Period</label>
                                        <select id="w_years" name="warranty_years" class="form-select shadow-sm">
                                            <option value="0">No Warranty</option>
                                            @for ($i = 1; $i <= 10; $i++)
                                                <option value="{{ $i }}">{{ $i }}
                                                    Year{{ $i > 1 ? 's' : '' }}</option>
                                            @endfor
                                        </select>
                                    </div>

                                    <div class="col-md-3">
                                        <label class="form-label fw-semibold text-danger">Warranty End</label>
                                        <input type="text" id="w_end_text" class="form-control bg-light shadow-sm"
                                            readonly>
                                        <input type="hidden" name="warranty_end_date" id="w_end_db">
                                    </div>

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
                                            <input type="number" min="0" name="purchase_price"
                                                class="form-control">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-4 border-start">
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Product Photos</label>
                                    <input required type="file" id="product_img" name="product_img[]"
                                        class="form-control" multiple accept="image/*">
                                    <div id="img_preview" class="d-flex flex-wrap gap-2 mt-2"></div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Product Video</label>
                                    <input type="file" name="product_video" class="form-control" accept="video/*">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Warranty Card</label>
                                    <input type="file" name="warranty_card" class="form-control"
                                        accept="image/*,application/pdf">
                                </div>

                                <div>
                                    <label class="form-label fw-semibold">Invoices</label>
                                    <input type="file" id="doc_file" name="invoice_file[]" class="form-control"
                                        multiple>
                                    <div id="doc_preview" class="mt-2 small text-white"></div>
                                </div>
                            </div>
                        </div>

                        <div class="row g-3 mt-4 pt-3 border-top">
                            <div class="col-md-4">
                                <label class="fw-semibold small">Seller Name</label>
                                <input required type="text" name="seller_name"
                                    class="form-control form-control-sm mb-1">
                                <label class="fw-semibold small">Seller Contact</label>
                                <input required type="text" name="seller_contact"
                                    class="form-control form-control-sm">
                            </div>

                            <div class="col-md-4">
                                <label class="fw-semibold small">Payer Name</label>
                                <input required type="text" name="payer_name"
                                    class="form-control form-control-sm mb-1">
                                <label class="fw-semibold small">Payer Contact</label>
                                <input required type="text" name="payer_contact" class="form-control form-control-sm">
                            </div>

                            <div class="col-md-4">
                                <label class="fw-semibold small">Receiver Name</label>
                                <input required type="text" name="receiver_name"
                                    class="form-control form-control-sm mb-1">
                                <label class="fw-semibold small">Receiver Contact</label>
                                <input required type="text" name="receiver_contact"
                                    class="form-control form-control-sm">
                            </div>

                            <div class="col-12">
                                <button type="button" id="submit-btn" onclick="add_new_stock()"
                                    class="btn btn-warning w-100 fw-bold py-2 shadow-sm">SAVE RECORD</button>
                            </div>
                        </div>
                    </div>
                </form>

                <script>
                    function add_new_stock() {
                        const form = document.getElementById('addstockform');

                        // Check HTML5 validation (required attributes)
                        if (!form.checkValidity()) {
                            form.reportValidity();
                            return;
                        }

                        const formData = new FormData(form);
                        const submitBtn = document.getElementById('submit-btn');
                        const progressContainer = document.getElementById('form-progress-container');
                        const progressBar = document.getElementById('form-progress-bar');

                        progressContainer.classList.remove('d-none');
                        submitBtn.disabled = true;

                        const xhr = new XMLHttpRequest();
                        xhr.open('POST', form.action, true);
                        xhr.setRequestHeader('X-CSRF-TOKEN', document.querySelector('input[name="_token"]').value);
                        xhr.setRequestHeader('Accept', 'application/json'); // Crucial for Laravel to return JSON errors

                        xhr.upload.onprogress = function(e) {
                            if (e.lengthComputable) {
                                const percentComplete = (e.loaded / e.total) * 100;
                                progressBar.style.width = percentComplete + '%';
                            }
                        };

                        xhr.onload = function() {
                            const response = JSON.parse(xhr.responseText);
                            if (xhr.status === 200 && response.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Success!',
                                    text: response.message,
                                    timer: 1500
                                });
                                setTimeout(() => location.reload(), 1500);
                            } else {
                                // This catches Validation errors (size, missing fields) from Laravel
                                resetFormState(submitBtn, progressContainer, progressBar);
                                let errorMsg = response.message || "Something went wrong";
                                if (response.errors) {
                                    errorMsg = Object.values(response.errors).flat().join("<br>");
                                }
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    html: errorMsg
                                });
                            }
                        };

                        xhr.onerror = function() {
                            resetFormState(submitBtn, progressContainer, progressBar);
                            Swal.fire({
                                icon: 'error',
                                title: 'Network Error',
                                text: 'Please check your connection.'
                            });
                        };

                        xhr.send(formData);
                    }

                    function resetFormState(btn, container, bar) {
                        btn.disabled = false;
                        container.classList.add('d-none');
                        bar.style.width = '0%';
                    }

                    document.addEventListener("DOMContentLoaded", function() {
                        // Warranty and Preview scripts remain exactly the same as your original
                        const startDate = document.getElementById("w_start");
                        const years = document.getElementById("w_years");
                        const endText = document.getElementById("w_end_text");
                        const endDB = document.getElementById("w_end_db");

                        function calculateWarranty() {
                            if (!startDate.value || years.value == 0) {
                                endText.value = "";
                                endDB.value = "";
                                return;
                            }
                            let start = new Date(startDate.value);
                            start.setFullYear(start.getFullYear() + parseInt(years.value));
                            let day = String(start.getDate()).padStart(2, '0');
                            let month = String(start.getMonth() + 1).padStart(2, '0');
                            let year = start.getFullYear();
                            endText.value = `${day}-${month}-${year}`;
                            endDB.value = `${year}-${month}-${day}`;
                        }

                        startDate.addEventListener("change", calculateWarranty);
                        years.addEventListener("change", calculateWarranty);

                        document.getElementById("product_img").addEventListener("change", function(e) {
                            const preview = document.getElementById("img_preview");
                            preview.innerHTML = "";
                            Array.from(e.target.files).forEach(file => {
                                const reader = new FileReader();
                                reader.onload = function(event) {
                                    const img = document.createElement("img");
                                    img.src = event.target.result;
                                    img.className = "rounded border";
                                    img.style.width = "70px";
                                    img.style.height = "70px";
                                    img.style.objectFit = "cover";
                                    preview.appendChild(img);
                                };
                                reader.readAsDataURL(file);
                            });
                        });

                        document.getElementById("doc_file").addEventListener("change", function(e) {
                            const preview = document.getElementById("doc_preview");
                            preview.innerHTML = "";
                            Array.from(e.target.files).forEach(file => {
                                const div = document.createElement("div");
                                div.textContent = "📄 " + file.name;
                                preview.appendChild(div);
                            });
                        });
                    });
                </script>
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
