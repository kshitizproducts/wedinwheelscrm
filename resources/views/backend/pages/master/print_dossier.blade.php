<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Employee Dossier - {{ $item->name }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #121212; color: #e0e0e0; font-family: 'Inter', sans-serif; }
        .dossier-page { max-width: 850px; margin: 40px auto; background: #1e1e1e; padding: 50px; border-radius: 12px; border: 1px solid #333; }
        .header-section { border-bottom: 2px solid #f37021; margin-bottom: 40px; padding-bottom: 20px; }
        .doc-box { margin-bottom: 50px; page-break-inside: avoid; }
        .doc-title { font-weight: 700; color: #f37021; text-transform: uppercase; font-size: 0.85rem; letter-spacing: 1px; margin-bottom: 15px; display: block; border-left: 4px solid #f37021; padding-left: 12px; }
        .img-container { background: #252525; border: 1px solid #333; border-radius: 8px; padding: 15px; text-align: center; }
        .img-container img { max-width: 100%; height: auto; border-radius: 4px; filter: grayscale(20%); transition: 0.3s; }
        .img-container img:hover { filter: grayscale(0%); }
        .no-doc { padding: 40px; color: #555; font-style: italic; border: 1px dashed #444; border-radius: 8px; }
        @media print {
            body { background: white; color: black; }
            .dossier-page { margin: 0; width: 100%; border: none; background: white; }
            .no-print, .btn-print { display: none !important; }
            .doc-title { color: black; border-left-color: #000; }
        }
    </style>
</head>
<body>

<div class="text-center mt-4 no-print">
    <button onclick="window.print()" class="btn btn-warning px-5 fw-bold shadow">PRINT DOSSIER (PDF)</button>
    <p class="text-white mt-2 small">Format: Vertical Printable Dossier</p>
</div>

<div class="dossier-page">
    <div class="header-section d-flex justify-content-between align-items-end">
        <div>
            <h1 class="h3 fw-bold mb-1 text-warning">Employee Dossier</h1>
            <p class="text-white mb-0 small">Official Document Verification Report</p>
        </div>
        <div class="text-end">
            <h4 class="mb-0 text-white">{{ $item->name }}</h4>
            <p class="mb-0 small text-white">{{ $item->email }}</p>
        </div>
    </div>

    @php
        $docs = [
            'National ID (Aadhar)' => $item->aadhar,
            'Tax ID (PAN Card)' => $item->pan,
            'Professional Resume' => $item->resume,
            'Educational Qualification' => $item->education_certificates,
            'Bank Passbook / Cancelled Cheque' => $item->bank_passbook,
            'Offer Letter' => $item->offer_letter
        ];
    @endphp

    @foreach($docs as $title => $path)
    <div class="doc-box">
        <span class="doc-title">{{ $title }}</span>
        <div class="img-container">
            @if($path && $path !== 'NA')
                @php $ext = pathinfo($path, PATHINFO_EXTENSION); @endphp
                @if(in_array(strtolower($ext), ['jpg', 'jpeg', 'png', 'webp']))
                    <img src="{{ asset($path) }}" alt="{{ $title }}">
                @else
                    <div class="py-5">
                        <i class="fas fa-file-pdf mb-3 fs-1"></i>
                        <p class="mb-0 text-info">Document in {{ strtoupper($ext) }} format</p>
                        <a href="{{ asset($path) }}" target="_blank" class="btn btn-sm btn-outline-info mt-2">Open File</a>
                    </div>
                @endif
            @else
                <div class="no-doc">Document not submitted by employee</div>
            @endif
        </div>
    </div>
    @endforeach

    <div class="text-center mt-5 pt-5 border-top border-secondary opacity-50 small text-white">
        This is a system-generated dossier for {{ $item->name }} | {{ date('d M Y, H:i') }}
    </div>
</div>

</body>
</html>