@extends('backend.layouts.main')

@section('main-section')

<section class="dashboard container-fluid py-4">
    <h2 class="text-warning fw-bold mb-4">Admin Dashboard Overview</h2>

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
            <div class="col-xl-3 col-md-6">
                <div class="card border-0 shadow-sm text-white p-3 mb-2" style="background: {{ $stat['bg'] }}; border-radius: 12px;">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-uppercase small fw-bold opacity-75">{{ $stat['title'] }}</h6>
                            <h3 class="mb-0 fw-bold">{{ $stat['value'] }}</h3>
                        </div>
                        <div class="opacity-50">
                            <i class="fas {{ $stat['icon'] }} fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="row g-4">
        {{-- ======= Chart Section ======= --}}
        <div class="col-lg-8">
            <div class="card bg-dark border-secondary text-white p-4 shadow-sm h-100">
                <h5 class="text-warning mb-4"><i class="fas fa-chart-line me-2"></i>Business Overview</h5>
                <canvas id="chart" style="max-height: 300px;"></canvas>
            </div>
        </div>

        {{-- ======= Premium Section ======= --}}
        <div class="col-lg-4">
            <div class="card bg-dark border-warning text-white p-4 shadow-sm h-100 border-opacity-25">
                <h5 class="text-warning mb-3">System Health</h5>
                <div class="mb-4">
                    <p class="mb-1 small">Talked Leads (Conversion)</p>
                    <div class="progress bg-black" style="height: 10px;">
                        <div class="progress-bar bg-warning" style="width: {{ $totalLeads > 0 ? ($talkedLeads/$totalLeads)*100 : 0 }}%;"></div>
                    </div>
                </div>
                <div class="mb-4 text-center">
                    <h1 class="display-4 fw-bold text-white">{{ $jobsCompleted }}</h1>
                    <p class="text-success fw-bold">Jobs Successfully Completed</p>
                </div>
                <button class="btn btn-warning w-100 fw-bold text-dark">DOWNLOAD REPORTS</button>
            </div>
        </div>

        {{-- ======= Recent Bookings Table ======= --}}
        <div class="col-12">
            <div class="card bg-dark border-secondary text-white shadow-sm p-4">
                <h5 class="text-warning mb-3"><i class="fas fa-list me-2"></i>Recent Booking Requests</h5>
                <div class="table-responsive">
                    <table class="table table-dark table-borderless table-hover align-middle">
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
                                    <span class="badge bg-outline-light border border-secondary">{{ $rb->status }}</span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
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
            plugins: { legend: { display: false } },
            scales: {
                y: { grid: { color: '#333' }, ticks: { color: '#888' } },
                x: { grid: { display: false }, ticks: { color: '#888' } }
            }
        }
    });
</script>

<style>
    .card { transition: transform 0.2s ease; border-radius: 15px; }
    .card:hover { transform: translateY(-5px); }
    .table-dark { background: transparent; }
    .progress { border-radius: 20px; }
</style>

@endsection