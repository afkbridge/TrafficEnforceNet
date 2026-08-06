@extends('layouts.admin')

@section('title', 'Reports')

@section('content')

    <div class="container-fluid px-4 pt-3">

        <!-- ===================================== -->
        <!-- Page Header -->
        <!-- ===================================== -->

        <div class="d-flex justify-content-between align-items-center mb-4">

            <div>

                <h2 class="fw-bold mb-1">
                    <i class="fas fa-chart-line text-primary me-2"></i>
                    Reports Dashboard
                </h2>

                <p class="text-muted mb-0">
                    Monitor traffic violations, analyze trends, and generate official reports.
                </p>

            </div>

        </div>



        <!-- ===================================== -->
        <!-- Summary Cards -->
        <!-- ===================================== -->

        <div class="row g-3 mb-4">

            <!-- Total Violations -->

            <div class="col-lg-3 col-md-6">

                <div class="card border-0 shadow-sm h-100">

                    <div class="card-body d-flex justify-content-between align-items-center">

                        <div>

                            <small class="text-muted text-uppercase fw-semibold">
                                Total Violations
                            </small>

                            <h2 class="fw-bold mt-2 mb-0">
                                {{ $totalViolations }}
                            </h2>

                        </div>

                        <div class="bg-danger bg-opacity-10 rounded-circle p-3">

                            <i class="fas fa-file-lines fa-2x text-danger"></i>

                        </div>

                    </div>

                </div>

            </div>



            <!-- Today's Violations -->

            <div class="col-lg-3 col-md-6">

                <div class="card border-0 shadow-sm h-100">

                    <div class="card-body d-flex justify-content-between align-items-center">

                        <div>

                            <small class="text-muted text-uppercase fw-semibold">
                                Today's Violations
                            </small>

                            <h2 class="fw-bold mt-2 mb-0">
                                {{ $todayViolations }}
                            </h2>

                        </div>

                        <div class="bg-primary bg-opacity-10 rounded-circle p-3">

                            <i class="fas fa-calendar-day fa-2x text-primary"></i>

                        </div>

                    </div>

                </div>

            </div>



            <!-- Monthly -->

            <div class="col-lg-3 col-md-6">

                <div class="card border-0 shadow-sm h-100">

                    <div class="card-body d-flex justify-content-between align-items-center">

                        <div>

                            <small class="text-muted text-uppercase fw-semibold">
                                This Month
                            </small>

                            <h2 class="fw-bold mt-2 mb-0">
                                {{ $monthlyViolations }}
                            </h2>

                        </div>

                        <div class="bg-success bg-opacity-10 rounded-circle p-3">

                            <i class="fas fa-chart-column fa-2x text-success"></i>

                        </div>

                    </div>

                </div>

            </div>



            <!-- Most Common -->

            <div class="col-lg-3 col-md-6">

                <div class="card border-0 shadow-sm h-100">

                    <div class="card-body d-flex justify-content-between align-items-center">

                        <div>

                            <small class="text-muted text-uppercase fw-semibold">
                                Most Common
                            </small>

                            <h5 class="fw-bold mt-2 mb-0">

                                @if ($mostCommonViolation && $mostCommonViolation->violationType)
                                    {{ $mostCommonViolation->violationType->name }}
                                @else
                                    No Data
                                @endif

                            </h5>

                        </div>

                        <div class="bg-warning bg-opacity-10 rounded-circle p-3">

                            <i class="fas fa-triangle-exclamation fa-2x text-warning"></i>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        <!-- ===================================== -->
        <!-- Report Filters -->
        <!-- ===================================== -->

        <div class="card border-0 shadow-sm mb-4">

            <div class="card-header bg-light border-0 py-3 rounded-top">
                <h5 class="fw-bold mb-1">
                    <i class="fas fa-filter me-2 text-primary"></i>
                    Report Filters
                </h5>

                <p class="text-muted mb-0">
                    Select filters below to generate customized reports and analytics.
                </p>
            </div>

            <div class="card-body">

                <form method="GET" action="{{ route('admin.reports.index') }}">

                    <div class="row g-4">

                        <!-- Date From -->
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">
                                <i class="fas fa-calendar-alt text-primary me-2"></i>
                                Date From
                            </label>

                            <input type="date" name="date_from" value="{{ request('date_from') }}"
                                class="form-control shadow-sm">
                        </div>

                        <!-- Date To -->
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">
                                <i class="fas fa-calendar-check text-primary me-2"></i>
                                Date To
                            </label>

                            <input type="date" name="date_to" value="{{ request('date_to') }}"
                                class="form-control shadow-sm">
                        </div>

                        <!-- Violation Type -->
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">
                                <i class="fas fa-ban text-danger me-2"></i>
                                Violation Type
                            </label>

                            <select name="violation_type" class="form-select shadow-sm">

                                <option value="">All Violation Types</option>

                                @foreach ($filterViolationTypes as $type)
                                    <option value="{{ $type->id }}"
                                        {{ request('violation_type') == $type->id ? 'selected' : '' }}>

                                        {{ $type->name }}

                                    </option>
                                @endforeach

                            </select>

                        </div>

                        <!-- Status -->
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">
                                <i class="fas fa-circle-check text-warning me-2"></i>
                                Status
                            </label>

                            <select name="status" class="form-select shadow-sm">

                                <option value="">All Status</option>

                                <option value="Pending" {{ request('status') == 'Pending' ? 'selected' : '' }}>
                                    Pending
                                </option>

                                <option value="Completed" {{ request('status') == 'Completed' ? 'selected' : '' }}>
                                    Completed
                                </option>

                            </select>

                        </div>

                        <!-- Officer -->
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">
                                <i class="fas fa-user-shield text-success me-2"></i>
                                Officer
                            </label>

                            <select name="officer" class="form-select shadow-sm">

                                <option value="">All Officers</option>

                                @foreach ($filterOfficers as $officer)
                                    <option value="{{ $officer->id }}"
                                        {{ request('officer') == $officer->id ? 'selected' : '' }}>

                                        {{ $officer->name }}

                                    </option>
                                @endforeach

                            </select>

                        </div>

                        <!-- Location -->
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">
                                <i class="fas fa-location-dot text-danger me-2"></i>
                                Location
                            </label>

                            <select name="location" class="form-select shadow-sm">

                                <option value="">All Locations</option>

                                @foreach ($filterLocations as $location)
                                    <option value="{{ $location }}"
                                        {{ request('location') == $location ? 'selected' : '' }}>

                                        {{ $location }}

                                    </option>
                                @endforeach

                            </select>

                        </div>

                    </div>

                    <hr class="my-4">

                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">

                        <div class="text-muted small">
                            <i class="fas fa-circle-info text-primary me-1"></i>
                            Apply filters to update charts, hotspot analysis, recent violations, and exported reports.
                        </div>

                        <div class="d-flex gap-2">

                            <a href="{{ route('admin.reports.index') }}" class="btn btn-outline-secondary">

                                <i class="fas fa-rotate-left me-2"></i>
                                Reset Filters

                            </a>

                            <button type="submit" class="btn btn-primary px-4">

                                <i class="fas fa-filter me-2"></i>
                                Apply Filters

                            </button>

                        </div>

                    </div>

                </form>

            </div>

        </div>

        <!-- ===================================== -->
        <!-- Analytics Charts -->
        <!-- ===================================== -->

        <div class="row g-4 mb-4">

            <!-- Monthly Violation Trends -->
            <div class="col-lg-6">

                <div class="card border-0 shadow-sm rounded-4 h-100">

                    <div class="card-header bg-white border-0 py-3">

                        <h5 class="fw-bold mb-0">
                            <i class="fas fa-chart-line text-primary me-2"></i>
                            Monthly Violation Trends
                        </h5>

                    </div>


                    <div class="card-body">

                        <div style="height:320px; width:500px;">

                            <canvas id="monthlyTrendChart"></canvas>

                        </div>

                    </div>


                </div>

            </div>



            <!-- Violation Distribution -->

            <div class="col-lg-6">

                <div class="card border-0 shadow-sm rounded-4 h-100">


                    <div class="card-header bg-white border-0 py-3">

                        <h5 class="fw-bold mb-0">
                            <i class="fas fa-chart-pie text-primary me-2"></i>
                            Violation Distribution
                        </h5>

                    </div>


                    <div class="card-body">

                        <div style="height:320px; width:500px;">

                            <canvas id="violationPieChart"></canvas>

                        </div>

                    </div>


                </div>

            </div>


        </div>





        <!-- ===================================== -->
        <!-- Traffic Violation Hotspot Map -->
        <!-- ===================================== -->

        <div class="row g-4 mb-4">

            <div class="col-lg-12">

                <div class="card border-0 shadow-sm rounded-4">


                    <div class="card-header bg-white border-0 py-3">

                        <h5 class="fw-bold mb-0">
                            <i class="fas fa-map-marker-alt text-danger me-2"></i>
                            Traffic Violation Hotspot Map
                        </h5>

                    </div>


                    <div class="d-flex justify-content-center">

                        <div id="hotspotMap"
                            style="
                           height:400px;
                             width:800px;
                             border-radius:12px;
                             ">
                        </div>

                    </div>


                </div>

            </div>

        </div>



    </div>

    <!-- Generate Report -->
    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3">
                <div>
                    <h5 class="mb-1 fw-semibold">
                        <i class="fas fa-file-export me-2 text-primary"></i>
                        Generate Report
                    </h5>
                    <p class="text-muted mb-0">
                        Export traffic violation reports based on selected criteria.
                    </p>
                </div>
                <div class="d-flex gap-2">
                    <button class="btn btn-danger px-4">
                        <i class="fas fa-file-pdf me-2"></i>
                        Export PDF
                    </button>
                    <button class="btn btn-success px-4">
                        <i class="fas fa-file-excel me-2"></i>
                        Export Excel
                    </button>
                </div>
            </div>
        </div>
    </div>

    </div>



@endsection

@section('scripts')

    <script>
        /*
                         |--------------------------------------------------------------------------
                       | Prepare Data
                         |--------------------------------------------------------------------------
                                                    */

        const monthlyLabels = @json(
            $monthlyTrend->pluck('month')->map(function ($month) {
                return \Carbon\Carbon::create()->month($month)->format('M');
            }));

        const monthlyTotals = @json($monthlyTrend->pluck('total'));

        const violationLabels = @json(
            $violationTypes->map(function ($item) {
                return optional($item->violationType)->name ?? 'Unknown';
            }));

        const violationTotals = @json($violationTypes->pluck('total'));



        /*
        |--------------------------------------------------------------------------
        | Monthly Trend Chart
        |--------------------------------------------------------------------------
        */

        new Chart(document.getElementById('monthlyTrendChart'), {

            type: 'line',

            data: {

                labels: monthlyLabels,

                datasets: [{

                    label: 'Violations',

                    data: monthlyTotals,

                    borderColor: '#0d6efd',

                    backgroundColor: 'rgba(13,110,253,0.15)',

                    fill: true,

                    borderWidth: 3,

                    pointRadius: 5,

                    pointHoverRadius: 7,

                    tension: 0.35

                }]

            },

            options: {

                responsive: true,

                maintainAspectRatio: false,

                plugins: {

                    legend: {

                        display: false

                    }

                },

                scales: {

                    y: {

                        beginAtZero: true,

                        ticks: {

                            precision: 0

                        }

                    }

                }

            }

        });





        /*
        |--------------------------------------------------------------------------
        | Violation Distribution
        |--------------------------------------------------------------------------
        */

        new Chart(document.getElementById('violationPieChart'), {

            type: 'doughnut',

            data: {

                labels: violationLabels,

                datasets: [{

                    data: violationTotals,

                    backgroundColor: [

                        '#0d6efd',
                        '#198754',
                        '#ffc107',
                        '#dc3545',
                        '#6f42c1',
                        '#fd7e14',
                        '#20c997',
                        '#0dcaf0'

                    ],

                    borderWidth: 2

                }]

            },

            options: {

                responsive: true,

                maintainAspectRatio: false,

                plugins: {

                    legend: {

                        position: 'bottom'

                    }

                }

            }

        });


        /*
        |--------------------------------------------------------------------------
        | Traffic Hotspot Map
        |--------------------------------------------------------------------------
        */

        const hotspotMap = L.map('hotspotMap').setView(
            [15.4881, 120.5980], // Tarlac City
            13
        );

        L.tileLayer(
            'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; OpenStreetMap contributors',
                maxZoom: 19
            }
        ).addTo(hotspotMap);
    </script>

@endsection
