<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice - {{ $stock->unique_id }}</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        /* Eye-Friendly Deep Dark Theme */
        body { 
            background-color: #0f0f0f; 
            color: #e0e0e0; 
            font-family: 'Segoe UI', Roboto, sans-serif; 
            padding-bottom: 50px;
        }
        
        .invoice-box { 
            border: 1px solid #333; 
            background: #181818; 
            padding: 40px; 
            border-radius: 12px; 
            margin-top: 20px; 
            box-shadow: 0 10px 30px rgba(0,0,0,0.5); 
        }

        .header-title { 
            color: #ffc107; 
            font-weight: 800; 
            letter-spacing: 2px;
            border-bottom: 2px solid #ffc107; 
            display: inline-block; 
        }

        .info-label { 
            color: #777; 
            font-size: 11px; 
            text-transform: uppercase; 
            letter-spacing: 1.5px; 
            margin-bottom: 5px;
        }

        .info-value { 
            color: #fff; 
            font-weight: 600; 
            font-size: 15px; 
            margin-bottom: 20px; 
        }

        .media-card {
            background: #222;
            border: 1px solid #333;
            border-radius: 8px;
            padding: 10px;
            transition: 0.3s;
        }

        .media-card:hover {
            border-color: #ffc107;
            transform: translateY(-5px);
        }

        .doc-link {
            background: #252525;
            color: #fff;
            text-decoration: none;
            padding: 12px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 10px;
            border: 1px solid #333;
        }

        .doc-link:hover {
            background: #333;
            color: #ffc107;
        }

        /* Print Settings */
        @media print {
            body { background: white !important; color: black !important; padding: 0; }
            .invoice-box { border: none; box-shadow: none; width: 100%; margin: 0; padding: 0; background: white !important; }
            .btn-print, .no-print { display: none !important; }
            .info-value, .text-white { color: black !important; }
            .header-title { color: black !important; border-bottom: 2px solid black !important; }
            .media-card { border: 1px solid #ddd !important; background: white !important; }
            .doc-link { border: 1px solid #ddd !important; color: black !important; background: white !important; }
        }
    </style>
</head>
<body>

<div class="container">
    <div class="text-end mt-4 no-print">
        <button onclick="window.print()" class="btn btn-warning fw-bold px-4 shadow-sm">
            <i class="fas fa-print me-2"></i> PRINT INVOICE
        </button>
    </div>

    <div class="invoice-box">
        <div class="row">
            <div class="col-6">
                <h2 class="header-title">Stock Details</h2>
                <p class="mt-2 text-warning font-monospace fs-5">ID: {{ $stock->unique_id }}</p>
            </div>
            <div class="col-6 text-end">
                <h3 class="text-white mb-1">Wed in Wheels</h3>
                <p class="small text-secondary mb-0">Ranchi, Jharkhand</p>
                <p class="small text-secondary">Contact: +91 9006042011</p>
            </div>
        </div>

        <hr class="border-secondary my-4 opacity-25">

        <div class="row mt-2">
            <div class="col-md-3">
                <div class="info-label">Product Name</div>
                <div class="info-value">{{ $stock->product_name ?? 'NA' }}</div>
                
                <div class="info-label">Category</div>
                <div class="info-value">{{ $stock->category_name ?? 'NA' }}</div>
            </div>
            <div class="col-md-3">
                <div class="info-label">Receipt Number</div>
                <div class="info-value">{{ $stock->receipt_no ?? 'NA' }}</div>

                <div class="info-label">Item Model</div>
                <div class="info-value text-warning">{{ $stock->item_name ?? 'NA' }}</div>
            </div>
            <div class="col-md-3">
                <div class="info-label">Purchase Price</div>
                <div class="info-value">₹{{ number_format((float)($stock->purchase_price ?? 0)) }}</div>

                <div class="info-label">Condition</div>
                <div class="info-value">
                    <span class="badge {{ $stock->condition_type == 'new' ? 'bg-success' : 'bg-info text-dark' }}">
                        {{ strtoupper($stock->condition_type ?? 'NA') }}
                    </span>
                </div>
            </div>
            <div class="col-md-3">
                <div class="info-label">Warranty End Date</div>
                <div class="info-value text-danger">
                    {{ $stock->warranty_end_date ? date('d M, Y', strtotime($stock->warranty_end_date)) : 'NO WARRANTY' }}
                </div>

                <div class="info-label">Company</div>
                <div class="info-value">{{ $stock->company_name ?? 'NA' }}</div>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-12">
                <h6 class="text-warning text-uppercase mb-3" style="letter-spacing: 2px; font-size: 13px;">Product Gallery</h6>
                <div class="d-flex flex-wrap gap-3">
                    @if($stock->product_img && $stock->product_img != 'NA')
                        @foreach(explode(',', $stock->product_img) as $img)
                            <div class="media-card">
                                <img src="{{ asset('uploads/stock/' . $img) }}" 
                                     style="width: 120px; height: 120px; object-fit: cover; border-radius: 6px;">
                                <div class="text-center mt-2">
                                    <a href="{{ asset('uploads/stock/' . $img) }}" target="_blank" class="text-warning extra-small text-decoration-none">View Full</a>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <p class="text-secondary small italic">No images attached.</p>
                    @endif
                </div>
            </div>
        </div>

        <div class="row mt-5 pt-4 border-top border-secondary">
            <div class="col-md-6">
                <h6 class="text-warning text-uppercase mb-3" style="letter-spacing: 2px; font-size: 13px;">Attachments & Docs</h6>
                
                {{-- Warranty Card --}}
                @if($stock->warranty_card && $stock->warranty_card != 'NA')
                    <a href="{{ asset('uploads/stock/' . $stock->warranty_card) }}" target="_blank" class="doc-link">
                        <span><i class="fas fa-shield-alt text-warning me-2"></i> Warranty Card</span>
                        <i class="fas fa-external-link-alt"></i>
                    </a>
                @endif

                {{-- Invoices --}}
                @if($stock->invoice_file && $stock->invoice_file != 'NA')
                    @foreach(explode(',', $stock->invoice_file) as $i => $file)
                        <a href="{{ asset('uploads/stock/' . $file) }}" target="_blank" class="doc-link">
                            <span><i class="fas fa-file-invoice text-info me-2"></i> Purchase Invoice {{ $i+1 }}</span>
                            <i class="fas fa-external-link-alt"></i>
                        </a>
                    @endforeach
                @endif

                {{-- Video --}}
                @if($stock->product_video && $stock->product_video != 'NA')
                    <a href="{{ asset('uploads/stock/' . $stock->product_video) }}" target="_blank" class="doc-link">
                        <span><i class="fas fa-video text-danger me-2"></i> Product Video Clipping</span>
                        <i class="fas fa-play"></i>
                    </a>
                @endif
            </div>

            <div class="col-md-6">
                <h6 class="text-warning text-uppercase mb-3" style="letter-spacing: 2px; font-size: 13px;">Party Details</h6>
                <div class="row">
                    <div class="col-6">
                        <div class="info-label">Seller Information</div>
                        <div class="info-value small" style="line-height: 1.2;">
                            {{ $stock->seller_name }}<br>
                            <span class="text-secondary">{{ $stock->seller_contact }}</span>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="info-label">Payer Information</div>
                        <div class="info-value small" style="line-height: 1.2;">
                            {{ $stock->payer_name }}<br>
                            <span class="text-secondary">{{ $stock->payer_contact }}</span>
                        </div>
                    </div>
                </div>
                <div class="mt-5 text-end">
                    <p class="mb-0 text-white-50 small">Generated on: {{ date('d-m-Y H:i A') }}</p>
                    <div class="mt-3">
                        <span class="border-top border-secondary pt-2 px-4 text-white-50 small">Authorized Signatory</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>