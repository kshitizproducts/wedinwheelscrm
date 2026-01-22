@extends('backend.layouts.main')

@section('main-section')

<section class="dashboard container-fluid py-4">
    <h2 class="text-warning fw-bold mb-4 dashboard-title">Admin Dashboard Overview</h2>

    {{-- ====== Stats Cards Row ====== --}}
    <div class="row g-3 mb-4">
        @php
            $stats = [
                ['title' => 'Total Cars', 'value' => $totalCars, 'bg' => '#36b9cc', 'icon' => 'fa-car'],
                ['title' => 'Total Users', 'value' => $totalUsers, 'bg' => '#f6c23e', 'icon' => 'fa-users'],
                ['title' => 'Garage Units', 'value' => $totalGarages, 'bg' => '#1cc88a', 'icon' => 'fa-warehouse'],
                ['title' => 'Total Bookings', 'value' => $totalBookings, 'bg' => '#e74a3b', 'icon' => 'fa-calendar-check'],
                ['title' => 'Total Revenue', 'value' => '₹'.number_format($totalRevenue), 'bg' => '#4e73df', 'icon' => 'fa-indian-rupee-sign'],
                ['title' => 'Service Dues', 'value' => '₹'.number_format($totalDue), 'bg' => '#d63384', 'icon' => 'fa-exclamation-circle'],
                ['title' => 'New Leads', 'value' => $newLeads, 'bg' => '#6f42c1', 'icon' => 'fa-bullhorn'],
                ['title' => 'Jobs Pending', 'value' => $jobsPending, 'bg' => '#fd7e14', 'icon' => 'fa-clock'],
            ];
        @endphp

        @foreach($stats as $stat)
            <div class="col-12 col-sm-6 col-lg-4 col-xl-3">
                <div class="card border-0 shadow-sm text-white p-3 stat-card"
                     style="background: {{ $stat['bg'] }};">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-uppercase small fw-bold opacity-75 mb-1">{{ $stat['title'] }}</h6>
                            <h3 class="mb-0 fw-bold stat-value">{{ $stat['value'] }}</h3>
                        </div>
                        <div class="opacity-50 stat-icon">
                            <i class="fas {{ $stat['icon'] }}"></i>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="row g-4">
        {{-- ======= Chart Section ======= --}}
        <div class="col-12 col-lg-8">
            <div class="card bg-dark border-secondary text-white p-4 shadow-sm h-100">
                <h5 class="text-warning mb-4 d-flex align-items-center flex-wrap gap-2">
                    <i class="fas fa-chart-line me-1"></i> Business Overview
                </h5>
                <div class="chart-wrap">
                    <canvas id="chart"></canvas>
                </div>
            </div>
        </div>

        {{-- ======= System Health Section ======= --}}
        <div class="col-12 col-lg-4">
            <div class="card bg-dark border-warning text-white p-4 shadow-sm h-100 border-opacity-25">
                <h5 class="text-warning mb-3">System Health</h5>

                <div class="mb-4">
                    <p class="mb-1 small">Talked Leads (Conversion)</p>
                    <div class="progress bg-black" style="height: 10px;">
                        <div class="progress-bar bg-warning"
                             style="width: {{ $totalLeads > 0 ? ($talkedLeads/$totalLeads)*100 : 0 }}%;">
                        </div>
                    </div>
                    <p class="small text-muted mt-2 mb-0">
                        {{ $totalLeads > 0 ? round(($talkedLeads/$totalLeads)*100, 1) : 0 }}% Conversion
                    </p>
                </div>

                <div class="mb-4 text-center">
                    <h1 class="fw-bold text-white jobs-count">{{ $jobsCompleted }}</h1>
                    <p class="text-success fw-bold mb-0">Jobs Successfully Completed</p>
                </div>

                <button class="btn btn-warning w-100 fw-bold text-dark dashboard-btn">
                    DOWNLOAD REPORTS
                </button>
            </div>
        </div>

        {{-- ======= Recent Bookings Table ======= --}}
        <div class="col-12">
            <div class="card bg-dark border-secondary text-white shadow-sm p-4">
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
                    <h5 class="text-warning mb-0">
                        <i class="fas fa-list me-2"></i> Recent Booking Requests
                    </h5>
                </div>

                <div class="table-responsive recent-table">
                    <table class="table table-dark table-borderless table-hover align-middle mb-0">
                        <thead class="text-muted small text-uppercase border-bottom border-secondary">
                        <tr>
                            <th>Client</th>
                            <th>Vehicle</th>
                            <th>Date</th>
                            <th>Payment</th>
                            <th>Status</th>
                        </tr>
                        </thead>

                        <tbody>
                        @foreach($recentBookings as $rb)
                            <tr>
                                <td class="fw-bold">{{ $rb->client_name }}</td>
                                <td class="text-info">{{ $rb->brand }} {{ $rb->model }}</td>
                                <td>{{ $rb->booking_date }}</td>
                                <td>
                                    <span class="badge {{ $rb->payment_status == 'Paid' ? 'bg-success' : 'bg-warning text-dark' }}">
                                        {{ $rb->payment_status }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-outline-light border border-secondary">
                                        {{ $rb->status }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Mobile Table Hint --}}
                <p class="small text-muted mt-3 mb-0 d-block d-md-none">
                    <i class="fa-solid fa-hand-point-right me-1"></i> Swipe left/right to view full table
                </p>
            </div>
        </div>
    </div>
</section>


{{-- Chart.js Script --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('chart').getContext('2d');

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
            datasets: [{
                label: 'Bookings',
                data: [12, 19, 3, 5, 2, 3],
                borderColor: '#ffc107',
                tension: 0.4,
                fill: true,
                backgroundColor: 'rgba(255, 193, 7, 0.1)'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false, // ✅ important for mobile
            plugins: { legend: { display: false } },
            scales: {
                y: { grid: { color: '#333' }, ticks: { color: '#888' } },
                x: { grid: { display: false }, ticks: { color: '#888' } }
            }
        }
    });
</script>


<style>
    /* ✅ Common UI */
    .card { transition: transform 0.2s ease; border-radius: 15px; }
    .card:hover { transform: translateY(-5px); }
    .table-dark { background: transparent; }
    .progress { border-radius: 20px; }

    /* ✅ Stats cards tweaks */
    .stat-card { border-radius: 12px; }
    .stat-icon i { font-size: 34px; }

    /* ✅ Chart responsive box */
    .chart-wrap{
        width: 100%;
        height: 320px;  /* default desktop */
        position: relative;
    }

    /* ✅ Table smooth scrolling on mobile */
    .recent-table{
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }

    /* ✅ Mobile Responsive Fixes */
    @media (max-width: 768px) {
        .dashboard-title{
            font-size: 20px;
            margin-bottom: 16px !important;
        }

        .stat-value{
            font-size: 22px;
        }

        .stat-icon i{
            font-size: 26px;
        }

        .chart-wrap{
            height: 240px; /* smaller for mobile */
        }

        .jobs-count{
            font-size: 42px;
        }

        .dashboard-btn{
            padding: 12px 14px;
            font-size: 14px;
        }

        /* cards hover disable for mobile */
        .card:hover { transform: none; }
    }

    /* ✅ Extra small devices */
    @media (max-width: 420px){
        .chart-wrap{ height: 210px; }

        .stat-card{
            padding: 12px !important;
        }

        .stat-value{
            font-size: 20px;
        }

        .stat-icon{
            display: none; /* ✅ very small screen: hide icon to save space */
        }
    }
</style>

@endsection
