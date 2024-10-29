@extends('layouts.app')

@section('content')
<div class="pagetitle mb-4">
    <h1>Indoor Tracking Dashboard</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Home</a></li>
            <li class="breadcrumb-item active">Dashboard</li>
        </ol>
    </nav>
</div>

<section class="section">
    <div class="row">
        <!-- Total Sites -->
        <div class="col-xl-3 col-md-6">
            <div class="card info-card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title mb-2">Total Sites</h5>
                    <div class="d-flex align-items-center">
                        <div class="card-icon bg-primary">
                            <i class="bi bi-geo-alt-fill"></i>
                        </div>
                        <div class="ps-3">
                            <h6 class="mb-0">{{ $totalSites }}</h6>
                            <small class="text-success"><i class="bi bi-arrow-up"></i> +8% from last month</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Devices -->
        <div class="col-xl-3 col-md-6">
            <div class="card info-card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title mb-2">Total Devices</h5>
                    <div class="d-flex align-items-center">
                        <div class="card-icon bg-success">
                            <i class="bi bi-phone-fill"></i>
                        </div>
                        <div class="ps-3">
                            <h6 class="mb-0">{{ $activeDevices }}</h6>
                            <small class="text-danger"><i class="bi bi-arrow-down"></i> -5% from last month</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Anchors -->
        <div class="col-xl-3 col-md-6">
            <div class="card info-card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title mb-2">Total Anchors</h5>
                    <div class="d-flex align-items-center">
                        <div class="card-icon bg-warning">
                            <i class="bi bi-people-fill"></i>
                        </div>
                        <div class="ps-3">
                            <h6 class="mb-0">{{ $totalAnchors }}</h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Alerts -->
        <div class="col-xl-3 col-md-6">
            <div class="card info-card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title mb-2">Alerts <span class="text-muted">| This Month</span></h5>
                    <div class="d-flex align-items-center">
                        <div class="card-icon bg-danger">
                            <i class="bi bi-bell-fill"></i>
                        </div>
                        <div class="ps-3">
                            <h6 class="mb-0">{{ $alertsCount }}</h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div><!-- End of Cards Row -->

    <div class="row">
        <!-- Tracking Map -->
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Tracking Map</h5>
                    <div id="tracking-map" style="height: 400px;"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <!-- Activity Overview Chart -->
        <div class="col-lg-8 col-md-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Activity Overview</h5>
                    <canvas id="activityChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Recent Activities -->
        <div class="col-lg-4 col-md-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Recent Activities</h5>
                    <ul class="list-group">
                        @foreach ($recentActivities as $activity)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            {{ $activity->description }}
                            <span class="badge bg-primary rounded-pill">{{ $activity->created_at->diffForHumans() }}</span>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
<script>
    // Activity Chart Setup
    const ctx = document.getElementById('activityChart').getContext('2d');
    const activityChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: @json($activityLabels),
            datasets: [{
                label: 'Device Activity',
                data: @json($activityData),
                borderColor: '#4CAF50',
                backgroundColor: 'rgba(76, 175, 80, 0.2)',
                borderWidth: 2,
                tension: 0.4,
                fill: true,
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: { beginAtZero: true }
            }
        }
    });

    // Leaflet Map Setup
    const map = L.map('tracking-map').setView([51.505, -0.09], 13);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '© OpenStreetMap'
    }).addTo(map);
</script>

<!-- Styles -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
<style>
    .info-card {
        border-radius: 12px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        transition: transform 0.2s, box-shadow 0.2s;
    }

    .info-card:hover {
        transform: scale(1.03);
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.15);
    }

    .card-icon {
        width: 55px;
        height: 55px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
    }

    #tracking-map {
        border-radius: 8px;
        overflow: hidden;
    }

    .list-group-item {
        transition: background-color 0.2s;
    }

    .list-group-item:hover {
        background-color: #f0f0f0;
    }
</style>
@endsection
