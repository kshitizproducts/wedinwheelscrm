@extends('backend.layouts.main')

@section('main-section')
<div class="container-fluid p-4" style="background-color: #0f0f0f; min-height: 100vh;">

    <div class="row mb-4 align-items-center">
        <div class="col-md-8">
            <h2 class="text-warning fw-bold mb-1">
                <i class="fas fa-map-marked-alt me-2"></i>Live Fleet Tracking
            </h2>
            <p class="text-secondary mb-0">Real-time GPS tracking & vehicle status</p>
        </div>
        <div class="col-md-4 text-end">
            <span class="badge bg-dark border border-warning text-warning p-2">
                <i class="fas fa-car me-1"></i> Total Vehicles: {{ $stats['total'] }}
            </span>
        </div>
    </div>

    <div class="row g-3 mb-5">
        <div class="col-6 col-md-3">
            <div class="p-3 rounded-3 bg-dark border border-secondary d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted text-uppercase" style="font-size: 10px;">Total</span>
                    <h3 class="text-white m-0 fw-bold">{{ $stats['total'] }}</h3>
                </div>
                <i class="fas fa-list text-secondary fa-2x opacity-25"></i>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="p-3 rounded-3 bg-dark border border-success d-flex align-items-center justify-content-between shadow-success">
                <div>
                    <span class="text-success text-uppercase" style="font-size: 10px;">Moving</span>
                    <h3 class="text-white m-0 fw-bold">{{ $stats['moving'] }}</h3>
                </div>
                <div class="spinner-grow text-success" role="status" style="width: 1rem; height: 1rem;"></div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="p-3 rounded-3 bg-dark border border-warning d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-warning text-uppercase" style="font-size: 10px;">Idle</span>
                    <h3 class="text-white m-0 fw-bold">{{ $stats['idle'] }}</h3>
                </div>
                <i class="fas fa-clock text-warning fa-2x opacity-25"></i>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="p-3 rounded-3 bg-dark border border-danger d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-danger text-uppercase" style="font-size: 10px;">Stopped</span>
                    <h3 class="text-white m-0 fw-bold">{{ $stats['stopped'] }}</h3>
                </div>
                <i class="fas fa-ban text-danger fa-2x opacity-25"></i>
            </div>
        </div>
    </div>

    <div class="row g-4">
        @forelse($vehicleList as $vehicle)
            @php
                // Status Logic
                $isMoving = $vehicle['ignition'] && $vehicle['speed'] > 0;
                $isIdle = $vehicle['ignition'] && $vehicle['speed'] == 0;
                
                $statusColor = 'danger'; 
                $statusText = 'STOPPED';
                $badgeIcon = 'fa-parking';
                $glowClass = '';

                if ($isMoving) {
                    $statusColor = 'success';
                    $statusText = 'MOVING';
                    $badgeIcon = 'fa-tachometer-alt';
                    $glowClass = 'pulse-active';
                } elseif ($isIdle) {
                    $statusColor = 'warning';
                    $statusText = 'IDLE';
                    $badgeIcon = 'fa-pause-circle';
                }

                // Base64 Image provided
             $carImage = "https://wedinwheels.com/wp-content/uploads/2025/01/wed-in.jpg";
            @endphp

            <div class="col-md-6 col-lg-4">
                <div class="card h-100 border-0 shadow-lg text-white" 
                     style="background-color: #1e1e1e; border-radius: 16px; overflow: hidden; transition: transform 0.3s;"
                     onmouseover="this.style.transform='translateY(-5px)'" 
                     onmouseout="this.style.transform='translateY(0)'">
                    
                    <div class="position-relative">
                        <img src="{{ $carImage }}" 
                             alt="Vehicle Image" 
                             class="card-img-top" 
                             style="height: 180px; object-fit: cover; width: 100%; filter: brightness(0.8);">
                        
                        <div class="position-absolute top-0 end-0 m-3">
                            <span class="badge bg-{{ $statusColor }} {{ $glowClass }} d-flex align-items-center gap-1 shadow">
                                <i class="fas {{ $badgeIcon }}"></i> {{ $statusText }}
                            </span>
                        </div>

                        <div class="position-absolute bottom-0 start-0 w-100 p-3" 
                             style="background: linear-gradient(to top, rgba(0,0,0,0.9), transparent);">
                            <h4 class="fw-bold m-0 text-white" style="text-shadow: 0 2px 4px rgba(0,0,0,0.8);">
                                {{ $vehicle['vehicleNumber'] }}
                            </h4>
                            <small class="text-warning fw-semibold">
                                {{ $vehicle['venndorName'] }}
                            </small>
                        </div>
                    </div>

                    <div class="card-body p-3">
                        <div class="row g-2 text-center">
                            <div class="col-4">
                                <div class="p-2 rounded-3" style="background-color: #2a2a2a;">
                                    <i class="fas fa-tachometer-alt text-secondary mb-1"></i>
                                    <div class="fw-bold {{ $vehicle['speed'] > 0 ? 'text-info' : 'text-white' }}">
                                        {{ $vehicle['speed'] }} <small>km/h</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="p-2 rounded-3" style="background-color: #2a2a2a;">
                                    <i class="fas fa-key text-secondary mb-1"></i>
                                    <div class="{{ $vehicle['ignition'] ? 'text-success' : 'text-danger' }} fw-bold">
                                        {{ $vehicle['ignition'] ? 'ON' : 'OFF' }}
                                    </div>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="p-2 rounded-3" style="background-color: #2a2a2a;">
                                    <i class="fas fa-clock text-secondary mb-1"></i>
                                    <div class="text-white" style="font-size: 12px; padding-top: 2px;">
                                        {{ \Carbon\Carbon::createFromTimestamp($vehicle['serverTime'] ?? time())->format('H:i') }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between align-items-center mt-3 px-1 text-secondary" style="font-size: 12px;">
                            <span><i class="fas fa-calendar-alt me-1"></i> {{ \Carbon\Carbon::createFromTimestamp($vehicle['serverTime'] ?? time())->format('d M') }}</span>
                            <span><i class="fas fa-compass me-1"></i> {{ $vehicle['angle'] }}°</span>
                        </div>
                    </div>

                    <div class="card-footer p-2 bg-transparent border-0">
                        <a href="https://www.google.com/maps?q={{ $vehicle['latitude'] }},{{ $vehicle['longitude'] }}" 
                           target="_blank" 
                           class="btn btn-warning w-100 fw-bold rounded-pill text-dark shadow-warning">
                            <i class="fas fa-location-arrow me-1"></i> Track Location
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-dark border-warning text-center text-warning p-5 rounded-3">
                    <i class="fas fa-satellite-dish fa-2x mb-3 opacity-50"></i>
                    <h4>No Vehicles Online</h4>
                    <p class="text-secondary">Please check your API connection or GPS devices.</p>
                </div>
            </div>
        @endforelse
    </div>
</div>

{{-- 4. Custom CSS for Animations & Shadows --}}
<style>
    /* Status Badge Pulse */
    .pulse-active {
        box-shadow: 0 0 0 0 rgba(25, 135, 84, 0.7);
        animation: pulse-green 2s infinite;
    }
    
    @keyframes pulse-green {
        0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(25, 135, 84, 0.7); }
        70% { transform: scale(1); box-shadow: 0 0 0 10px rgba(25, 135, 84, 0); }
        100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(25, 135, 84, 0); }
    }

    /* Glow effects for summary cards */
    .shadow-success { box-shadow: 0 4px 15px rgba(25, 135, 84, 0.2); }
    
    /* Button Hover */
    .shadow-warning:hover {
        box-shadow: 0 0 15px rgba(255, 193, 7, 0.6);
        transform: scale(1.02);
        transition: all 0.2s ease-in-out;
    }
</style>
@endsection