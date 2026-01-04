<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Car Profile - Tesla Model X</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">

    <style>
        body {
            background-color: #0e0e0e;
            color: #e0e0e0;
            font-family: 'Poppins', sans-serif;
            line-height: 1.6;
        }

        .card {
            border-radius: 15px;
            background-color: #181818;
            border: 1px solid #ffc107;
            padding: 25px;
            box-shadow: 0 0 20px rgba(255, 193, 7, 0.1);
        }

        h4,
        h5,
        h6 {
            color: #ffc107;
            font-weight: 600;
        }

        p,
        td,
        th,
        li,
        div {
            font-size: 0.95rem;
        }

        table {
            color: #e0e0e0;
            font-size: 0.95rem;
        }

        table thead {
            color: #ffc107;
            font-weight: 600;
        }

        .feed-item {
            width: 120px;
            height: 80px;
            object-fit: cover;
            border-radius: 8px;
            cursor: pointer;
            transition: transform 0.25s, box-shadow 0.25s;
        }

        .feed-item:hover {
            transform: scale(1.05);
            box-shadow: 0 0 8px rgba(255, 193, 7, 0.4);
        }

        .media-feed {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            overflow-x: auto;
            padding-bottom: 8px;
            scroll-behavior: smooth;
            max-height: 450px;
        }

        .media-feed::-webkit-scrollbar {
            height: 8px;
        }

        .media-feed::-webkit-scrollbar-thumb {
            background: #ffc107;
            border-radius: 4px;
        }

        .video-modal {
            display: none;
            position: fixed;
            z-index: 9999;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.9);
            justify-content: center;
            align-items: center;
            flex-direction: column;
        }

        .video-modal.fade-in {
            animation: fadeIn 0.4s ease;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        .modal-content-container {
            position: relative;
            display: flex;
            justify-content: center;
            align-items: center;
            width: 100%;
        }

        .modal-content-container img,
        .modal-content-container video {
            width: 90%;
            max-width: 800px;
            border-radius: 10px;
            transition: opacity 0.3s;
        }

        .close-btn {
            position: absolute;
            top: 20px;
            right: 30px;
            font-size: 35px;
            color: #fff;
            cursor: pointer;
            z-index: 10000;
        }

        .nav-btn {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            background: rgba(255, 255, 255, 0.3);
            color: white;
            border: none;
            font-size: 2rem;
            cursor: pointer;
            border-radius: 50%;
            padding: 8px 15px;
            transition: 0.3s;
            user-select: none;
        }

        .nav-btn:hover {
            background: rgba(255, 255, 255, 0.6);
        }

        .left-btn {
            left: 40px;
        }

        .right-btn {
            right: 40px;
        }

        @media (max-width: 768px) {
            .feed-item {
                width: 100px;
                height: 70px;
            }

            .card {
                padding: 15px;
            }
        }
    </style>
</head>

<body>
    <div class="container my-4">
        <div class="card">
            @php
                $profilepublicUrl = $car_data->profile_pic;
                $images = json_decode($car_data->images, true);
                $videos = json_decode($car_data->videos, true);
                $media = [];
                if (!empty($images)) {
                    foreach ($images as $img) {
                        $media[] = ['type' => 'image', 'src' => asset($img)];
                    }
                }
                if (!empty($videos)) {
                    foreach ($videos as $vid) {
                        $media[] = ['type' => 'video', 'src' => asset($vid)];
                    }
                }
            @endphp

            <div class="row g-4">
                <!-- Left Column -->
                <div class="col-lg-5 col-md-6 text-center">
                    <img src="{{ asset($profilepublicUrl) }}" class="img-fluid rounded mb-2 shadow-sm" alt="Car Photo">
                    <h4 class="fw-bold text-warning mb-1">{{ $car_data->brand }}</h4>
                    <p class="text-white mb-0">{{ $car_data->car_desc }}</p>

                    <div class="info-tag text-warning-emphasis my-3">
                        <i class="bi bi-info-circle-fill me-1 text-warning"></i> Latest update: 2025-09-10
                    </div>

                    <h6>360° View</h6>
                    <div class="mb-4 text-center">
                        <img src="https://cdn.motor1.com/images/mgl/0AN2o/s1/tesla-model-x.jpg"
                            alt="360 View Placeholder" class="img-fluid rounded" style="cursor: grab;">
                        <small class="d-block mt-2 text-secondary">Rotate to view</small>
                    </div>

                    <h6>Media Feed</h6>
                    <div class="media-feed mb-3">
                        @foreach ($media as $index => $item)
                            @if ($item['type'] === 'image')
                                <img src="{{ $item['src'] }}" class="feed-item media-thumb"
                                    data-index="{{ $index }}" alt="Image">
                            @else
                                <div class="video-thumbnail media-thumb" data-index="{{ $index }}">
                                    <video class="feed-item rounded" muted>
                                        <source src="{{ $item['src'] }}" type="video/mp4">
                                    </video>
                                </div>
                            @endif
                        @endforeach
                        @if (empty($media))
                            <p>No media available.</p>
                        @endif
                    </div>

                    <div id="mediaModal" class="video-modal">
                        <span class="close-btn">&times;</span>
                        <button class="nav-btn left-btn">&#10094;</button>
                        <button class="nav-btn right-btn">&#10095;</button>
                        <div class="modal-content-container">
                            <img id="modalImage" style="display:none;">
                            <video id="modalVideo" style="display:none;" controls autoplay>
                                <source src="" type="video/mp4">
                            </video>
                        </div>
                    </div>

                    <div class="share-buttons mt-3">
                        <a href="https://wa.me/?text=Check%20this%20car%20profile%3A%20{{ urlencode($car_data->name) }}%20-%20{{ urlencode(request()->fullUrl()) }}"
                          target="_blank"
                          class="btn btn-share btn-whatsapp"
                          style="color:#e0e0e0; background:transparent; border:1px solid rgba(224,224,224,0.08); font-family:'Poppins',sans-serif; font-weight:500; padding:6px 12px;">
                          <i class="bi bi-whatsapp"></i> WhatsApp
                        </a>
                        <a href="mailto:?subject=Car%20Profile%20-%20{{ $car_data->name }}&body=Check%20this%20car%20profile%3A%20{{ urlencode(request()->fullUrl()) }}"
                          class="btn btn-share btn-email"
                          style="color:#e0e0e0; background:transparent; border:1px solid rgba(224,224,224,0.08); font-family:'Poppins',sans-serif; font-weight:500; padding:6px 12px;">
                          <i class="bi bi-envelope-fill"></i> Email
                        </a>
                    </div>
                </div>

                <!-- Right Column -->
                <div class="col-lg-7 col-md-6">
                    <h5 class="fw-bold mb-3">Car Details</h5>
                    <div class="row mb-2">
                        <div class="col-sm-6 text-white"><strong class="text-warning">Mileage:</strong>
                            {{ $car_data->mileage }}</div>
                        <div class="col-sm-6 text-white"><strong class="text-warning">Fuel Type:</strong>
                            {{ $car_data->fuel_type }}</div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-sm-6 text-white"><strong class="text-warning">Seats:</strong>
                            {{ $car_data->seat_capacity }}</div>
                        <div class="col-sm-6 text-white"><strong class="text-warning">Engine:</strong>
                            {{ $car_data->engine }}</div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-sm-6 text-white"><strong class="text-warning">Registration Number:</strong>
                            {{ $car_data->registration_no }}</div>
                        <div class="col-sm-6 text-white"><strong class="text-warning">Status:</strong>
                            @if ($car_data->status == 0)
                                Blacklisted
                            @elseif($car_data->status == 1)
                                Available
                            @elseif($car_data->status == 2)
                                Booked
                            @endif
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-sm-6 text-white"><strong class="text-warning">Location:</strong>
                            {{ $car_data->location }}</div>
                        <div class="col-sm-6 text-white"><strong class="text-warning">Next Availability:</strong>
                            {{ $car_data->next_availability }}</div>
                    </div>

                    <div class="col-sm-6 text-white"><strong class="text-warning">Duplicate Key's:</strong>
                        {{ $car_data->duplicate_keys }}</div>

                    <hr class="border-warning">
                    <h6>Documents</h6>
                    <div class="table-responsive">
                        <table class="table table-dark table-striped table-bordered align-middle">
                            <tr>
                                <th>
                                    RC book
                                </th>
                                <td>

                                    @if (!empty($car_data->rc_book))
                                        <a href="{{ asset($car_data->rc_book) }}" target="_blank"
                                            class="btn btn-sm btn-primary mt-2">View File</a>
                                    @else
                                        <p class="text-muted mt-2">No file uploaded</p>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    Insurance
                                </th>
                                <td>
                                    @if (!empty($car_data->insurance))
                                        <a href="{{ asset($car_data->insurance) }}" target="_blank"
                                            class="btn btn-sm btn-primary mt-2">View File</a>
                                    @else
                                        <p class="text-muted mt-2">No file uploaded</p>
                                    @endif
                                </td>
                            </tr>

                            <tr>
                                <th>
                                    Pollution
                                </th>
                                <td>
                                    @if (!empty($car_data->pollution))
                                        <a href="{{ asset($car_data->pollution) }}" target="_blank"
                                            class="btn btn-sm btn-primary mt-2">View File</a>
                                    @else
                                        <p class="text-muted mt-2">No file uploaded</p>
                                    @endif
                                </td>
                            </tr>
                        </table>
                    </div>

                    <hr class="border-warning">
                   <div class="table-responsive">
    <table class="table table-dark table-striped table-bordered align-middle">
        <thead>
            <tr>
                <th>Date</th>
                <th>Service Type</th>
                <th>Garage</th>
                <th>Cost (₹)</th>
                <th>Amount Paid</th>
                <th>Next Due</th>
                <th>Invoice</th> {{-- Naya Column --}}
            </tr>
        </thead>
        <tbody>
            @foreach ($car_service as $service)
                <tr>
                    <td>{{ $service->billed_on_date }}</td>
                    <td>
                        {{ DB::table('service_master')->where('id', $service->service_type_id)->value('service_type') }}
                    </td>
                    <td>
                        {{ DB::table('garage_master')->where('id', $service->garage_id)->value('name') }}
                    </td>
                    <td>₹ {{ $service->cost }}</td>
                    <td>₹ {{ $service->bill_paid }}</td>
                    <td>₹ {{ $service->due }}</td>
                    <td>
                        @php
                            // JSON string ko array mein convert karna
                            $invoices = json_decode($service->invoice, true);
                            $sl = 1;
                        @endphp

                        @if(!empty($invoices) && is_array($invoices))
                            @foreach ($invoices as $file)
                                {{-- Direct Public Path se link --}}
                                <a target="_blank" href="{{ asset($file) }}" class="me-1">
                                    <span class="badge bg-info text-dark" title="View Bill">
                                        <i class="fas fa-file-invoice"></i> Bill {{ $sl++ }}
                                    </span>
                                </a>
                            @endforeach
                        @else
                            <span class="text-muted small">No Invoice</span>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
                    <hr class="border-warning">
                    <h6>Additional Info</h6>
                    <ul class="mb-0 text-white">
                        {!! $car_data->additional_details !!}
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <script>
        const mediaItems = @json($media);
        const modal = document.getElementById('mediaModal');
        const modalImg = document.getElementById('modalImage');
        const modalVid = document.getElementById('modalVideo');
        const modalVidSrc = modalVid.querySelector('source');
        let currentIndex = 0;

        function showMedia(index) {
            const item = mediaItems[index];
            if (!item) return;
            modal.classList.add('fade-in');
            modal.style.display = 'flex';
            if (item.type === 'image') {
                modalVid.pause();
                modalVid.style.display = 'none';
                modalImg.style.display = 'block';
                modalImg.src = item.src;
            } else {
                modalImg.style.display = 'none';
                modalVid.style.display = 'block';
                modalVidSrc.src = item.src;
                modalVid.load();
                modalVid.play();
            }
            currentIndex = index;
        }

        document.querySelectorAll('.media-thumb').forEach(el => {
            el.addEventListener('click', () => showMedia(parseInt(el.dataset.index)));
        });

        document.querySelector('.close-btn').onclick = () => {
            modal.style.display = 'none';
            modalVid.pause();
        };

        document.querySelector('.left-btn').onclick = () => {
            currentIndex = (currentIndex - 1 + mediaItems.length) % mediaItems.length;
            showMedia(currentIndex);
        };
        document.querySelector('.right-btn').onclick = () => {
            currentIndex = (currentIndex + 1) % mediaItems.length;
            showMedia(currentIndex);
        };

        document.addEventListener('keydown', e => {
            if (modal.style.display === 'flex') {
                if (e.key === 'ArrowLeft') document.querySelector('.left-btn').click();
                if (e.key === 'ArrowRight') document.querySelector('.right-btn').click();
                if (e.key === 'Escape') document.querySelector('.close-btn').click();
            }
        });

        modal.addEventListener('click', e => {
            if (e.target === modal) document.querySelector('.close-btn').click();
        });
    </script>
</body>

</html>
