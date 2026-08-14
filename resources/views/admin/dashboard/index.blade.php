@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
    <div class="container-fluid">

        {{-- ========================================================= --}}
        {{-- HEADER --}}
        {{-- ========================================================= --}}

        <div class="dashboard-header mb-4">

            <h2 class="page-title">
                Dashboard
            </h2>

            <p class="page-subtitle">
                Monitor traffic violations and enforcement activities.
            </p>

        </div>


        {{-- ========================================================= --}}
        {{-- SUMMARY CARDS --}}
        {{-- ========================================================= --}}

        <div class="row g-3 mb-4">

            {{-- TOTAL VIOLATIONS --}}
            <div class="col-lg-3 col-md-6">

                <div class="dashboard-card stat-card">

                    <div>

                        <span class="stat-title">
                            Today's Violations
                        </span>

                        <h3 class="stat-number">
                            {{ $todayTickets }}
                        </h3>

                    </div>

                    <div class="card-icon blue">
                        <i class="fas fa-calendar-day"></i>
                    </div>

                </div>

            </div>


            {{-- THIS MONTH --}}
            <div class="col-lg-3 col-md-6">

                <div class="dashboard-card stat-card">

                    <div>

                        <span class="stat-title">
                            This Month
                        </span>

                        <h3 class="stat-number">
                            {{ number_format($thisMonthViolations) }}
                        </h3>

                        <small class="{{ $monthlyChange >= 0 ? 'text-danger' : 'text-success' }}">

                            @if ($monthlyChange >= 0)
                                <i class="fas fa-arrow-up"></i>
                            @else
                                <i class="fas fa-arrow-down"></i>
                            @endif

                            {{ number_format(abs($monthlyChange), 1) }}%
                            vs last month

                        </small>

                    </div>

                    <div class="card-icon green">
                        <i class="fas fa-chart-line"></i>
                    </div>

                </div>

            </div>


            {{-- MOST COMMON VIOLATION --}}
            <div class="col-lg-3 col-md-6">

                <div class="dashboard-card stat-card">

                    <div>

                        <span class="stat-title">
                            Most Common Violation
                        </span>

                        <h3 class="stat-number" style="font-size: 18px;">
                            {{ $mostCommonViolationName }}
                        </h3>

                    </div>

                    <div class="card-icon orange">
                        <i class="fas fa-triangle-exclamation"></i>
                    </div>

                </div>

            </div>


            {{-- ACTIVE ENFORCERS --}}
            <div class="col-lg-3 col-md-6">

                <div class="dashboard-card stat-card">

                    <div>

                        <span class="stat-title">
                            Active Enforcers
                        </span>

                        <h3 class="stat-number">
                            {{ number_format($activeEnforcers) }}
                        </h3>

                    </div>

                    <div class="card-icon purple">
                        <i class="fas fa-user-shield"></i>
                    </div>

                </div>

            </div>

        </div>


        {{-- ========================================================= --}}
        {{-- VIOLATION TREND + TOP VIOLATIONS --}}
        {{-- ========================================================= --}}

        <div class="row g-3 mb-4">

            {{-- VIOLATION TREND --}}
            <div class="col-lg-8">

                <div class="dashboard-card chart-card">

                    <div class="section-header">

                        <div>

                            <h5>
                                Violation Trend
                            </h5>

                            <small class="text-muted">
                                Monthly violations for {{ now()->year }}
                            </small>

                        </div>

                    </div>

                    <div style="height: 320px;">
                        <canvas id="monthlyChart"></canvas>
                    </div>

                </div>

            </div>


            {{-- TOP VIOLATIONS --}}
            <div class="col-lg-4">

                <div class="dashboard-card chart-card">

                    <div class="section-header">

                        <div>

                            <h5>
                                Top Violation Types
                            </h5>

                            <small class="text-muted">
                                Most frequently recorded
                            </small>

                        </div>

                    </div>

                    <div style="height: 320px;">
                        <canvas id="distributionChart"></canvas>
                    </div>

                </div>

            </div>

        </div>


        {{-- ========================================================= --}}
        {{-- HOTSPOT MAP --}}
        {{-- ========================================================= --}}

        <div class="row g-3 mb-4">

            <div class="col-12">

                <div class="dashboard-card map-card">

                    <div class="section-header">

                        <div>

                            <h5>
                                Traffic Violation Hotspots
                            </h5>

                            <small class="text-muted">
                                Locations with recorded traffic violations
                            </small>

                        </div>

                    </div>

                    <div class="map-container">
                        <div id="map"></div>
                    </div>

                </div>

            </div>

        </div>


        {{-- ========================================================= --}}
        {{-- PEAK HOURS + ENFORCER ACTIVITY --}}
        {{-- ========================================================= --}}

        <div class="row g-3 mb-4">

            {{-- PEAK HOURS --}}
            <div class="col-lg-7">

                <div class="dashboard-card chart-card">

                    <div class="section-header">

                        <div>

                            <h5>
                                Violation Activity by Hour
                            </h5>

                            <small class="text-muted">
                                Helps identify peak enforcement periods
                            </small>

                        </div>

                    </div>

                    <div style="height: 320px;">
                        <canvas id="peakHoursChart"></canvas>
                    </div>

                </div>

            </div>


            {{-- ENFORCER ACTIVITY --}}
            <div class="col-lg-5">

                <div class="dashboard-card chart-card">

                    <div class="section-header">

                        <div>

                            <h5>
                                Enforcer Activity
                            </h5>

                            <small class="text-muted">
                                Tickets recorded by enforcer
                            </small>

                        </div>

                    </div>

                    <div style="height: 320px;">
                        <canvas id="enforcerChart"></canvas>
                    </div>

                </div>

            </div>

        </div>


        {{-- ========================================================= --}}
        {{-- RECENT VIOLATIONS --}}
        {{-- ========================================================= --}}

        <div class="row g-3">

            <div class="col-12">

                <div class="dashboard-card table-card">

                    <div class="section-header">

                        <div>

                            <h5>
                                Recent Violation Records
                            </h5>

                            <small class="text-muted">
                                Latest recorded violations
                            </small>

                        </div>

                        <a href="{{ route('violations.index') }}" class="btn btn-sm btn-primary">
                            View All
                        </a>

                    </div>


                    <div class="table-responsive">

                        <table class="table align-middle">

                            <thead>

                                <tr>

                                    <th>
                                        Ticket No.
                                    </th>

                                    <th>
                                        Violator
                                    </th>

                                    <th>
                                        Violation
                                    </th>

                                    <th>
                                        Status
                                    </th>

                                    <th>
                                        Date
                                    </th>

                                </tr>

                            </thead>


                            <tbody>

                                @forelse($recentViolations as $violation)
                                    <tr>

                                        <td>
                                            <strong>
                                                {{ $violation->ticket_number }}
                                            </strong>
                                        </td>


                                        <td>

                                            @if ($violation->driver)
                                                {{ $violation->driver->first_name }}
                                                {{ $violation->driver->last_name }}
                                            @else
                                                Unknown
                                            @endif

                                        </td>


                                        <td>

                                            {{ $violation->violationType->name ?? 'Unknown' }}

                                        </td>


                                        <td>

                                            @if ($violation->status === 'Pending')
                                                <span class="badge bg-warning text-dark">
                                                    Pending
                                                </span>
                                            @elseif($violation->status === 'Resolved')
                                                <span class="badge bg-success">
                                                    Resolved
                                                </span>
                                            @else
                                                <span class="badge bg-secondary">
                                                    {{ $violation->status ?? 'Unknown' }}
                                                </span>
                                            @endif

                                        </td>


                                        <td>

                                            {{ $violation->violation_date ? \Carbon\Carbon::parse($violation->violation_date)->format('M d, Y') : '—' }}

                                        </td>

                                    </tr>

                                @empty

                                    <tr>

                                        <td colspan="5" class="text-center py-5 text-muted">

                                            No violation records found.

                                        </td>

                                    </tr>
                                @endforelse

                            </tbody>

                        </table>

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- ============================================================= --}}
    {{-- DASHBOARD ANALYTICS --}}
    {{-- ============================================================= --}}

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>


    <script>
        document.addEventListener('DOMContentLoaded', function() {

                    console.log('Dashboard analytics initialized');


                    /* ============================================================
                       MONTHLY VIOLATION TREND
                       ============================================================ */

                    const monthlyCanvas =
                        document.getElementById('monthlyChart');

                    if (monthlyCanvas && typeof Chart !== 'undefined') {

                        new Chart(monthlyCanvas, {

                            type: 'line',

                            data: {

                                labels: [
                                    'January',
                                    'February',
                                    'March',
                                    'April',
                                    'May',
                                    'June',
                                    'July',
                                    'August',
                                    'September',
                                    'October',
                                    'November',
                                    'December'
                                ],

                                datasets: [{

                                    label: 'Violations',

                                    data: @json($monthlyViolations),

                                    borderWidth: 3,

                                    tension: 0.35,

                                    fill: true,

                                    pointRadius: 4,

                                    pointHoverRadius: 6

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
                                        },

                                        title: {
                                            display: true,
                                            text: 'Violation Count'
                                        }

                                    },

                                    x: {

                                        title: {
                                            display: true,
                                            text: 'Month'
                                        }

                                    }

                                }

                            }

                        });

                    }


                    /* ============================================================
                       TOP VIOLATION TYPES
                       ============================================================ */

                    const distributionCanvas =
                        document.getElementById('distributionChart');

                    if (
                        distributionCanvas &&
                        typeof Chart !== 'undefined'
                    ) {

                        new Chart(distributionCanvas, {

                            type: 'doughnut',

                            data: {

                                labels: @json($violationLabels),

                                datasets: [{

                                    data: @json($violationCounts),

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

                    }


                    /* ============================================================
                       PEAK VIOLATION PERIODS
                       ============================================================ */

                    const peakHoursCanvas =
                        document.getElementById('peakHoursChart');

                    if (
                        peakHoursCanvas &&
                        typeof Chart !== 'undefined'
                    ) {

                        new Chart(peakHoursCanvas, {

                            type: 'bar',

                            data: {

                                labels: @json($peakPeriodLabels),

                                datasets: [{

                                    label: 'Violations',

                                    data: @json($peakPeriodCounts),

                                    borderWidth: 1,

                                    borderRadius: 6

                                }]

                            },

                            options: {

                                responsive: true,

                                maintainAspectRatio: false,

                                plugins: {

                                    legend: {
                                        display: false
                                    },

                                    tooltip: {

                                        callbacks: {

                                            label: function(context) {

                                                return context.raw +
                                                    ' violations';

                                            }

                                        }

                                    }

                                },

                                scales: {

                                    y: {

                                        beginAtZero: true,

                                        ticks: {
                                            precision: 0
                                        },

                                        title: {

                                            display: true,

                                            text: 'Violation Count'

                                        }

                                    },

                                    x: {

                                        title: {

                                            display: true,

                                            text: 'Time Period'

                                        }

                                    }

                                }

                            }

                        });

                    }


                    /* ============================================================
                       ENFORCER ACTIVITY
                       ============================================================ */

                    const enforcerCanvas =
                        document.getElementById('enforcerChart');

                    if (
                        enforcerCanvas &&
                        typeof Chart !== 'undefined'
                    ) {

                        new Chart(enforcerCanvas, {

                            type: 'bar',

                            data: {

                                labels: @json($enforcerLabels),

                                datasets: [{

                                    label: 'Tickets Issued',

                                    data: @json($enforcerCounts),

                                    borderWidth: 1,

                                    borderRadius: 5

                                }]

                            },

                            options: {

                                indexAxis: 'y',

                                responsive: true,

                                maintainAspectRatio: false,

                                plugins: {

                                    legend: {
                                        display: false
                                    }

                                },

                                scales: {

                                    x: {

                                        beginAtZero: true,

                                        ticks: {
                                            precision: 0
                                        },

                                        title: {

                                            display: true,

                                            text: 'Tickets'

                                        }

                                    }

                                }

                            }

                        });

                    }


                    /* ============================================================
           TRAFFIC VIOLATION HOTSPOT MAP
           ============================================================ */

                    const mapElement =
                        document.getElementById('map');

                    if (
                        mapElement &&
                        typeof L !== 'undefined'
                    ) {

                        const hotspots = @json($hotspots);


                        /*
                        |--------------------------------------------------------------------------
                        | CREATE MAP
                        |--------------------------------------------------------------------------
                        */

                        const map = L.map('map').setView(
                            [15.4800, 120.5900],
                            13
                        );


                        /*
                        |--------------------------------------------------------------------------
                        | OPENSTREETMAP
                        |--------------------------------------------------------------------------
                        */

                        L.tileLayer(
                            'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                                attribution: '&copy; OpenStreetMap contributors'
                            }
                        ).addTo(map);


                        /*
                        |--------------------------------------------------------------------------
                        | HOTSPOT COLORS
                        |--------------------------------------------------------------------------
                        */

                        function getHotspotColor(level) {

                            if (level === 'high') {

                                return '#dc2626';

                            }

                            if (level === 'moderate') {

                                return '#f59e0b';

                            }

                            return '#16a34a';
                        }


                        /*
                        |--------------------------------------------------------------------------
                        | HOTSPOT MARKERS
                        |--------------------------------------------------------------------------
                        */

                        const validPoints = [];


                        hotspots.forEach(function(hotspot) {

                            const latitude =
                                parseFloat(hotspot.latitude);

                            const longitude =
                                parseFloat(hotspot.longitude);


                            if (
                                !isNaN(latitude) &&
                                !isNaN(longitude)
                            ) {

                                validPoints.push([
                                    latitude,
                                    longitude
                                ]);


                                const color =
                                    getHotspotColor(
                                        hotspot.level
                                    );


                                /*
                                |--------------------------------------------------------------------------
                                | CREATE CIRCLE
                                |--------------------------------------------------------------------------
                                */

                                L.circleMarker(
                                        [latitude, longitude], {

                                            radius: Math.min(
                                                10 +
                                                (hotspot.violation_count * 1.5),
                                                30
                                            ),

                                            color: color,

                                            fillColor: color,

                                            fillOpacity: 0.55,

                                            weight: 2

                                        }
                                    )
                                    .bindPopup(`

                <div style="min-width: 190px;">

                    <strong>
                        Violation Hotspot
                    </strong>

                    <hr style="margin: 6px 0;">

                    <div>
                        <strong>Level:</strong>
                        ${hotspot.level
                            .charAt(0)
                            .toUpperCase()
                            +
                            hotspot.level.slice(1)}
                    </div>

                    <div>
                        <strong>Recorded Violations:</strong>
                        ${hotspot.violation_count}
                    </div>

                    <div>
                        <strong>Most Common:</strong>
                        ${hotspot.most_common_violation}
                    </div>

                </div>

            `)
                                    .addTo(map);

                            }

                        });


                        /*
                        |--------------------------------------------------------------------------
                        | FIT MAP TO HOTSPOTS
                        |--------------------------------------------------------------------------
                        */

                        if (validPoints.length > 0) {

                            map.fitBounds(
                                validPoints, {
                                    padding: [30, 30]
                                }
                            );

                        }


                        /*
                        |--------------------------------------------------------------------------
                        | MAP LEGEND
                        |--------------------------------------------------------------------------
                        */

                        const legend =
                            L.control({
                                position: 'bottomright'
                            });


                        legend.onAdd = function() {

                            const div =
                                L.DomUtil.create(
                                    'div',
                                    'map-legend'
                                );


                            div.innerHTML = `

            <strong>
                Violation Hotspots
            </strong>

            <div class="legend-item">
                <span
                    class="legend-circle low">
                </span>
                Low (1–3)
            </div>

            <div class="legend-item">
                <span
                    class="legend-circle moderate">
                </span>
                Moderate (4–7)
            </div>

            <div class="legend-item">
                <span
                    class="legend-circle high">
                </span>
                High (8+)
            </div>

        `;


                            return div;

                        };


                        legend.addTo(map);


                        /*
                        |--------------------------------------------------------------------------
                        | FIX LEAFLET SIZE
                        |--------------------------------------------------------------------------
                        */

                        setTimeout(function() {

                            map.invalidateSize();

                        }, 300);

                    }
                                 }); // CLOSE DOMContentLoaded
    </script>


    <style>
        .map-container {

            width: 100%;

            height: 400px;

            overflow: hidden;

            border-radius: 10px;

        }


        #map {

            width: 100%;

            height: 100%;

            position: relative;

            z-index: 1;

            border-radius: 10px;

        }


        .dashboard-card {

            overflow: hidden;

        }


        .leaflet-container {

            width: 100%;

            height: 100%;

        }

        /* ============================================================
       MAP LEGEND
       ============================================================ */

        .map-legend {

            background: white;

            padding: 12px 14px;

            border-radius: 8px;

            box-shadow:
                0 2px 8px rgba(0, 0, 0, 0.15);

            font-size: 12px;

            line-height: 1.8;

        }

        .map-legend strong {

            display: block;

            margin-bottom: 4px;

            font-size: 13px;

        }

        .legend-item {

            display: flex;

            align-items: center;

            gap: 7px;

        }

        .legend-circle {

            width: 11px;

            height: 11px;

            border-radius: 50%;

            display: inline-block;

        }

        .legend-circle.low {

            background: #16a34a;

        }

        .legend-circle.moderate {

            background: #f59e0b;

        }

        .legend-circle.high {

            background: #dc2626;

        }
    </style>
@endsection
