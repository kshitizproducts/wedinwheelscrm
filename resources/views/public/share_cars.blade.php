<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Car Selection</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body{
            background: #0f1115;
            color: #fff;
        }
        .topbar{
            background: #12151d;
            border-bottom: 1px solid #2a2f3b;
            padding: 14px 0;
            position: sticky;
            top: 0;
            z-index: 100;
        }
        .brand-logo{
            width: 36px;
            height: 36px;
            object-fit: contain;
            border-radius: 8px;
            background: #1c2230;
            padding: 6px;
        }
        .heading{
            color: #ffc107;
            font-weight: 800;
            margin: 0;
            font-size: 1.1rem;
        }
        .sub{
            color: #b7bcc9;
            margin: 0;
            font-size: 0.9rem;
        }

        .car-card{
            border: 1px solid #2a2f3b;
            background: #141824;
            border-radius: 16px;
            overflow: hidden;
            transition: .2s ease;
            cursor: pointer;
            height: 100%;
        }
        .car-card:hover{
            transform: translateY(-3px);
            border-color: #ffc107;
        }
        .car-img{
            height: 190px;
            width: 100%;
            object-fit: cover;
            background: #0d0f15;
        }
        .pill{
            font-size: 12px;
            border-radius: 999px;
            padding: 6px 10px;
            background: rgba(255, 193, 7, 0.12);
            color: #ffc107;
            border: 1px solid rgba(255, 193, 7, 0.30);
        }

        .confirm-bar{
            position: sticky;
            bottom: 0;
            background: rgba(15,17,21,.92);
            backdrop-filter: blur(10px);
            border-top: 1px solid #2a2f3b;
            padding: 12px 0;
            z-index: 100;
        }
        .btn-confirm{
            border-radius: 999px;
            padding: 12px 18px;
            font-weight: 700;
        }
        .radio-big{
            width: 20px;
            height: 20px;
        }

        .empty-box{
            border: 1px dashed #2a2f3b;
            border-radius: 14px;
            padding: 20px;
            background: #141824;
        }
    </style>
</head>

<body>

{{-- ✅ TOPBAR --}}
<div class="topbar">
    <div class="container">
        <div class="d-flex align-items-center gap-3">
            <img class="brand-logo" src="https://wedinwheels.com/wp-content/uploads/2024/01/wedinwheels-logo.png" alt="logo">
            <div class="flex-grow-1">
                <p class="heading">Select Car for {{ $lead->client_name ?? 'Client' }}</p>
                <p class="sub">Choose one car and confirm booking.</p>
            </div>
        </div>
    </div>
</div>

<div class="container py-4">

    {{-- ✅ success message --}}
    @if(session('success'))
        <div class="alert alert-success fw-semibold">
            {{ session('success') }}
        </div>
    @endif

    {{-- ✅ if already confirmed --}}
    @if($share->status == 1)
        <div class="alert alert-info fw-semibold">
            ✅ Car Already Confirmed (Car ID: {{ $share->client_selected_car_id }})
        </div>
    @endif

    {{-- ✅ if no cars --}}
    @if(empty($cars) || count($cars) == 0)
        <div class="empty-box text-center">
            <h5 class="text-warning mb-2">No Cars Found</h5>
            <p class="text-muted mb-0">
                Cars list is empty or share link invalid. Please contact manager.
            </p>
        </div>
    @else

        <form id="confirmForm" method="POST" action="{{ url('/car-share/'.$token.'/confirm') }}">
            @csrf

            <div class="row g-3">
                @foreach($cars as $car)

                    @php
                        // ✅ if image missing fallback
                        $img = $car->profile_pic ? asset($car->profile_pic) : 'https://via.placeholder.com/600x300?text=Car+Image';
                    @endphp

                    <div class="col-12 col-sm-6 col-lg-4">
                        <div class="car-card" onclick="selectCar('car_{{ $car->id }}')">

                            <img src="{{ $img }}" class="car-img" alt="Car image">

                            <div class="p-3">
                                <div class="d-flex justify-content-between align-items-start gap-2">
                                    <div>
                                        <div class="d-flex gap-2 flex-wrap">
                                            <span class="pill">{{ $car->brand ?? 'Brand' }}</span>
                                            <span class="pill">{{ $car->model ?? 'Model' }}</span>
                                        </div>
                                        <h6 class="mt-2 mb-1 fw-bold">
                                            {{ ($car->brand ?? '') }} {{ ($car->model ?? '') }}
                                        </h6>
                                        <div class="text-muted small">
                                            Reg No: <b class="text-white">{{ $car->registration_no ?? 'NA' }}</b>
                                        </div>
                                    </div>

                                    <div class="text-end">
                                        <input class="form-check-input radio-big"
                                               type="radio"
                                               name="selected_car"
                                               value="{{ $car->id }}"
                                               id="car_{{ $car->id }}"
                                               onclick="event.stopPropagation()"
                                               {{ ($share->client_selected_car_id == $car->id) ? 'checked' : '' }}
                                               required>
                                    </div>
                                </div>

                                <div class="mt-2 small text-muted">
                                    Fuel: <b class="text-white">{{ $car->fuel_type ?? 'NA' }}</b> |
                                    Seats: <b class="text-white">{{ $car->seats ?? 'NA' }}</b>
                                </div>
                            </div>

                        </div>
                    </div>
                @endforeach
            </div>

            {{-- ✅ sticky confirm --}}
            <div class="confirm-bar mt-4">
                <div class="container d-flex flex-column flex-sm-row gap-2 justify-content-between align-items-center">
                    <div class="text-muted small">
                        Select 1 car then click confirm.
                    </div>
                    <button id="confirmBtn" class="btn btn-warning text-dark btn-confirm" type="submit" disabled>
                        ✅ Confirm Selected Car
                    </button>
                </div>
            </div>

        </form>
    @endif
</div>

<script>
    // ✅ select radio when clicking card
    function selectCar(id){
        const el = document.getElementById(id);
        el.checked = true;
        validateConfirm();
    }

    // ✅ enable confirm after selection
    function validateConfirm(){
        const selected = document.querySelector('input[name="selected_car"]:checked');
        document.getElementById('confirmBtn').disabled = !selected;
    }

    // initial check
    document.querySelectorAll('input[name="selected_car"]').forEach(el=>{
        el.addEventListener('change', validateConfirm);
    });

    window.onload = validateConfirm;
</script>

</body>
</html>
