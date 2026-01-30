@extends('backend.layouts.main')

@section('main-section')

<div class="container-fluid py-4 bg-black min-vh-100">
    <div class="mb-4">
        <h2 class="text-white fw-bold">Welcome back, <span class="text-warning">{{ Auth::user()->name }}</span>!</h2>
        <p class="text-white-50">Here is what's happening with your inventory today.</p>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="card bg-dark border-start border-warning border-4 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-white-50 text-uppercase small">Total Products</h6>
                            <h3 class="text-white fw-bold mb-0">{{ $stats['total_products'] }}</h3>
                        </div>
                        <div class="bg-warning rounded-circle p-3 text-dark">
                            <i class="fas fa-box fa-lg"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card bg-dark border-start border-success border-4 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-white-50 text-uppercase small">Total Investment</h6>
                            <h3 class="text-white fw-bold mb-0">₹{{ number_format($stats['total_investment']) }}</h3>
                        </div>
                        <div class="bg-success rounded-circle p-3 text-white">
                            <i class="fas fa-wallet fa-lg"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card bg-dark border-start border-info border-4 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-white-50 text-uppercase small">Brand New Items</h6>
                            <h3 class="text-white fw-bold mb-0">{{ $stats['new_items'] }}</h3>
                        </div>
                        <div class="bg-info rounded-circle p-3 text-dark">
                            <i class="fas fa-tag fa-lg"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card bg-dark border-start border-danger border-4 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-white-50 text-uppercase small">Warranty Expired</h6>
                            <h3 class="text-white fw-bold mb-0">{{ $stats['expired_warranty'] }}</h3>
                        </div>
                        <div class="bg-danger rounded-circle p-3 text-white">
                            <i class="fas fa-exclamation-triangle fa-lg"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card bg-dark border-0 shadow-lg">
                <div class="card-header bg-transparent border-secondary py-3">
                    <h6 class="text-white mb-0"><i class="fas fa-chart-line text-warning me-2"></i>Stock Addition Trend</h6>
                </div>
                <div class="card-body">
                    <canvas id="inventoryChart" style="height: 300px;"></canvas>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card bg-dark border-0 shadow-lg">
                <div class="card-header bg-transparent border-secondary py-3">
                    <h6 class="text-white mb-0"><i class="fas fa-history text-warning me-2"></i>Quick Actions</h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ url('stock') }}" class="btn btn-outline-warning py-3 fw-bold">
                            <i class="fas fa-plus me-2"></i>Add New Stock
                        </a>
                        <a href="{{ url('stock') }}" class="btn btn-outline-light py-3 fw-bold">
                            <i class="fas fa-list me-2"></i>View Full Inventory
                        </a>
                    </div>
                    <hr class="border-secondary my-4">
                    <p class="text-white-50 small mb-0 text-center italic">Dashboard filters data based on your uploads.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('inventoryChart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: {!! json_encode($chart['labels']) !!},
            datasets: [{
                label: 'Products Added',
                data: {!! json_encode($chart['data']) !!},
                borderColor: '#ffc107',
                backgroundColor: 'rgba(255, 193, 7, 0.1)',
                fill: true,
                tension: 0.4,
                pointRadius: 5
            }]
        },
        options: {
            responsive: true,
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