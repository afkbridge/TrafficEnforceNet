@extends('layouts.admin')

@section('title', 'Reports')

@section('content')

    <div class="container-fluid px-4 pt-3">

        {{-- ========================================================= --}}
        {{-- PAGE HEADER --}}
        {{-- ========================================================= --}}

        <div class="mb-4">
            <h2 class="fw-bold mb-1">
                <i class="fas fa-chart-line text-primary me-2"></i>
                Reports Dashboard
            </h2>

            <p class="text-muted mb-0">
                Analyze traffic violations, identify trends, and support enforcement decision-making.
            </p>
        </div>


        {{-- ========================================================= --}}
        {{-- SUMMARY CARDS --}}
        {{-- ========================================================= --}}

        <div class="row g-3 mb-4">

            {{-- Total Violations --}}
            <div class="col-xl-3 col-md-6">
                <div class="card summary-card h-100">
                    <div class="card-body">
                        <div class="summary-content">
                            <div>
                                <div class="summary-label">
                                    Total Violations
                                </div>

                                <div class="summary-value">
                                    {{ $totalViolations }}
                                </div>

                                <div class="summary-description">
                                    Based on selected filters
                                </div>
                            </div>

                            <div class="summary-icon danger">
                                <i class="fas fa-file-lines"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            {{-- Today's Violations --}}
            <div class="col-xl-3 col-md-6">
                <div class="card summary-card h-100">
                    <div class="card-body">
                        <div class="summary-content">
                            <div>
                                <div class="summary-label">
                                    Today's Violations
                                </div>

                                <div class="summary-value">
                                    {{ $todayViolations }}
                                </div>

                                <div class="summary-description">
                                    Recorded today
                                </div>
                            </div>

                            <div class="summary-icon primary">
                                <i class="fas fa-calendar-day"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            {{-- This Month --}}
            <div class="col-xl-3 col-md-6">
                <div class="card summary-card h-100">
                    <div class="card-body">
                        <div class="summary-content">
                            <div>
                                <div class="summary-label">
                                    This Month
                                </div>

                                <div class="summary-value">
                                    {{ $monthlyViolations }}
                                </div>

                                <div class="summary-description">
                                    Current month
                                </div>
                            </div>

                            <div class="summary-icon success">
                                <i class="fas fa-chart-column"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            {{-- Most Common Violation --}}
            <div class="col-xl-3 col-md-6">
                <div class="card summary-card h-100">
                    <div class="card-body">
                        <div class="summary-content">

                            <div class="summary-text">
                                <div class="summary-label">
                                    Most Common
                                </div>

                                <div
                                    class="summary-value summary-value-text"
                                    title="{{ $mostCommonViolation && $mostCommonViolation->violationType
                                        ? $mostCommonViolation->violationType->name
                                        : 'No Data' }}"
                                >
                                    @if ($mostCommonViolation && $mostCommonViolation->violationType)
                                        {{ $mostCommonViolation->violationType->name }}
                                    @else
                                        No Data
                                    @endif
                                </div>

                                <div class="summary-description">
                                    Most recorded violation
                                </div>
                            </div>

                            <div class="summary-icon warning">
                                <i class="fas fa-triangle-exclamation"></i>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

        </div>


        {{-- ========================================================= --}}
        {{-- FILTERS --}}
        {{-- ========================================================= --}}

        <div class="card report-card mb-4">

            <div class="card-header report-card-header">

                <div class="section-icon">
                    <i class="fas fa-filter"></i>
                </div>

                <div>
                    <h5 class="fw-bold mb-1">
                        Report Filters
                    </h5>

                    <small class="text-muted">
                        Filter the analytics by period, violation type, officer, or location.
                    </small>
                </div>

            </div>


            <div class="card-body">

                <form method="GET" action="{{ route('admin.reports.index') }}">

                    <div class="row g-3">

                        {{-- Date From --}}
                        <div class="col-lg-3 col-md-6">

                            <label class="form-label">
                                <i class="fas fa-calendar-alt text-primary me-2"></i>
                                Date From
                            </label>

                            <input
                                type="date"
                                name="date_from"
                                value="{{ request('date_from') }}"
                                class="form-control"
                            >

                        </div>


                        {{-- Date To --}}
                        <div class="col-lg-3 col-md-6">

                            <label class="form-label">
                                <i class="fas fa-calendar-check text-primary me-2"></i>
                                Date To
                            </label>

                            <input
                                type="date"
                                name="date_to"
                                value="{{ request('date_to') }}"
                                class="form-control"
                            >

                        </div>


                        {{-- Violation Type --}}
                        <div class="col-lg-3 col-md-6">

                            <label class="form-label">
                                <i class="fas fa-ban text-danger me-2"></i>
                                Violation Type
                            </label>

                            <select name="violation_type" class="form-select">

                                <option value="">
                                    All Violation Types
                                </option>

                                @foreach ($filterViolationTypes as $type)

                                    <option
                                        value="{{ $type->id }}"
                                        {{ request('violation_type') == $type->id ? 'selected' : '' }}
                                    >
                                        {{ $type->name }}
                                    </option>

                                @endforeach

                            </select>

                        </div>


                        {{-- Officer --}}
                        <div class="col-lg-3 col-md-6">

                            <label class="form-label">
                                <i class="fas fa-user-shield text-success me-2"></i>
                                Officer
                            </label>

                            <select name="officer" class="form-select">

                                <option value="">
                                    All Officers
                                </option>

                                @foreach ($filterOfficers as $officer)

                                    <option
                                        value="{{ $officer->id }}"
                                        {{ request('officer') == $officer->id ? 'selected' : '' }}
                                    >
                                        {{ $officer->name }}
                                    </option>

                                @endforeach

                            </select>

                        </div>


                        {{-- Location --}}
                        <div class="col-lg-6">

                            <label class="form-label">
                                <i class="fas fa-location-dot text-danger me-2"></i>
                                Location
                            </label>

                            <select name="location" class="form-select">

                                <option value="">
                                    All Locations
                                </option>

                                @foreach ($filterLocations as $location)

                                    <option
                                        value="{{ $location }}"
                                        {{ request('location') == $location ? 'selected' : '' }}
                                    >
                                        {{ $location }}
                                    </option>

                                @endforeach

                            </select>

                        </div>

                    </div>


                    <div class="filter-footer mt-4 pt-3">

                        <div class="text-muted small">
                            <i class="fas fa-circle-info text-primary me-1"></i>
                            Filters apply to the report analytics and summary cards.
                        </div>

                        <div class="d-flex gap-2">

                            <a
                                href="{{ route('admin.reports.index') }}"
                                class="btn btn-outline-secondary"
                            >
                                <i class="fas fa-rotate-left me-2"></i>
                                Reset
                            </a>

                            <button
                                type="submit"
                                class="btn btn-primary px-4"
                            >
                                <i class="fas fa-filter me-2"></i>
                                Apply Filters
                            </button>

                        </div>

                    </div>

                </form>

            </div>

        </div>


        {{-- ========================================================= --}}
        {{-- ANALYTICS --}}
        {{-- ========================================================= --}}

        <div class="row g-4 analytics-grid">


            {{-- ===================================================== --}}
            {{-- MONTHLY TREND --}}
            {{-- ===================================================== --}}

            <div class="col-xl-7 col-lg-7">

                <div class="card analytics-card">

                    <div class="card-header analytics-header">

                        <div class="analytics-title">

                            <div class="analytics-icon primary">
                                <i class="fas fa-chart-line"></i>
                            </div>

                            <div>
                                <h5>
                                    Monthly Violation Trends
                                </h5>

                                <small>
                                    Number of recorded violations by month
                                </small>
                            </div>

                        </div>

                    </div>


                    <div class="card-body">

                        <div class="chart-container chart-line">
                            <canvas id="monthlyTrendChart"></canvas>
                        </div>

                    </div>

                </div>

            </div>


            {{-- ===================================================== --}}
            {{-- VIOLATION DISTRIBUTION --}}
            {{-- ===================================================== --}}

            <div class="col-xl-5 col-lg-5">

                <div class="card analytics-card">

                    <div class="card-header analytics-header">

                        <div class="analytics-title">

                            <div class="analytics-icon primary">
                                <i class="fas fa-chart-pie"></i>
                            </div>

                            <div>
                                <h5>
                                    Violation Distribution
                                </h5>

                                <small>
                                    Breakdown by violation type
                                </small>
                            </div>

                        </div>

                    </div>


                    <div class="card-body">

                        <div class="chart-container chart-doughnut">
                            <canvas id="violationPieChart"></canvas>
                        </div>

                    </div>

                </div>

            </div>


            {{-- ===================================================== --}}
            {{-- LOCATION --}}
            {{-- ===================================================== --}}

            <div class="col-xl-6 col-lg-6">

                <div class="card analytics-card">

                    <div class="card-header analytics-header">

                        <div class="analytics-title">

                            <div class="analytics-icon danger">
                                <i class="fas fa-location-dot"></i>
                            </div>

                            <div>
                                <h5>
                                    Violations by Location
                                </h5>

                                <small>
                                    Areas with the highest recorded violations
                                </small>
                            </div>

                        </div>

                    </div>


                    <div class="card-body">

                        <div class="chart-container chart-location">
                            <canvas id="locationChart"></canvas>
                        </div>

                    </div>

                </div>

            </div>


            {{-- ===================================================== --}}
            {{-- OFFICER --}}
            {{-- ===================================================== --}}

            <div class="col-xl-6 col-lg-6">

                <div class="card analytics-card">

                    <div class="card-header analytics-header">

                        <div class="analytics-title">

                            <div class="analytics-icon success">
                                <i class="fas fa-user-shield"></i>
                            </div>

                            <div>
                                <h5>
                                    Violations by Officer
                                </h5>

                                <small>
                                    Number of violations recorded by each officer
                                </small>
                            </div>

                        </div>

                    </div>


                    <div class="card-body">

                        <div class="chart-container chart-officer">
                            <canvas id="officerChart"></canvas>
                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- ========================================================= --}}
        {{-- GENERATE REPORT --}}
        {{-- ========================================================= --}}

        <div class="card report-card generate-card mt-4 mb-4">

            <div class="card-body">

                <div class="generate-content">

                    <div>

                        <h5 class="fw-semibold mb-1">
                            <i class="fas fa-file-export text-primary me-2"></i>
                            Generate Report
                        </h5>

                        <p class="text-muted mb-0">
                            Export traffic violation data based on the selected filters.
                        </p>

                    </div>


                    <div class="d-flex gap-2 flex-wrap">

                        <button
                            type="button"
                            class="btn btn-danger px-4"
                            onclick="alert('PDF export will be connected next.')"
                        >
                            <i class="fas fa-file-pdf me-2"></i>
                            Export PDF
                        </button>


                        <button
                            type="button"
                            class="btn btn-success px-4"
                            onclick="alert('Excel export will be connected next.')"
                        >
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

    /* =========================================================
       DATA
    ========================================================= */

    const monthlyLabels = @json(
        $monthlyTrend->pluck('month')->map(function ($month) {
            return \Carbon\Carbon::create()
                ->month($month)
                ->format('M');
        })
    );

    const monthlyTotals = @json($monthlyTrend->pluck('total'));


    const violationLabels = @json(
        $violationTypes->map(function ($item) {
            return optional($item->violationType)->name ?? 'Unknown';
        })
    );

    const violationTotals = @json(
        $violationTypes->pluck('total')
    );


    const locationLabels = @json(
        $locationData->pluck('location')
    );

    const locationTotals = @json(
        $locationData->pluck('total')
    );


    const officerLabels = @json(
        $officerData->pluck('name')
    );

    const officerTotals = @json(
        $officerData->pluck('total')
    );


    /* =========================================================
       CHART DEFAULTS
    ========================================================= */

    Chart.defaults.font.family =
        '-apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Arial, sans-serif';

    Chart.defaults.color = '#64748b';


    /* =========================================================
       MONTHLY TREND
    ========================================================= */

    const monthlyTrendElement =
        document.getElementById('monthlyTrendChart');

    if (monthlyTrendElement) {

        new Chart(monthlyTrendElement, {

            type: 'line',

            data: {

                labels: monthlyLabels,

                datasets: [{

                    label: 'Violations',

                    data: monthlyTotals,

                    borderColor: '#0d6efd',

                    backgroundColor: 'rgba(13, 110, 253, 0.08)',

                    fill: true,

                    borderWidth: 2.5,

                    pointRadius: 3,

                    pointHoverRadius: 5,

                    pointBackgroundColor: '#0d6efd',

                    tension: 0.35

                }]

            },


            options: {

                responsive: true,

                maintainAspectRatio: false,

                interaction: {

                    intersect: false,

                    mode: 'index'

                },


                plugins: {

                    legend: {
                        display: false
                    },

                    tooltip: {

                        backgroundColor: '#1e293b',

                        padding: 10,

                        displayColors: false,

                        callbacks: {

                            label: function(context) {

                                return ' Violations: ' +
                                    context.parsed.y;

                            }

                        }

                    }

                },


                scales: {

                    x: {

                        grid: {
                            display: false
                        },

                        border: {
                            display: false
                        }

                    },


                    y: {

                        beginAtZero: true,

                        border: {
                            display: false
                        },

                        ticks: {
                            precision: 0
                        },

                        grid: {
                            color: 'rgba(100, 116, 139, 0.10)'
                        }

                    }

                }

            }

        });

    }


    /* =========================================================
       VIOLATION DISTRIBUTION
    ========================================================= */

    const violationPieElement =
        document.getElementById('violationPieChart');

    if (violationPieElement) {

        new Chart(violationPieElement, {

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

                    borderWidth: 3,

                    borderColor: '#ffffff',

                    hoverOffset: 5

                }]

            },


            options: {

                responsive: true,

                maintainAspectRatio: false,

                cutout: '62%',


                plugins: {

                    legend: {

                        position: 'bottom',

                        labels: {

                            padding: 12,

                            usePointStyle: true,

                            pointStyle: 'circle',

                            boxWidth: 8,

                            font: {
                                size: 12
                            }

                        }

                    },


                    tooltip: {

                        backgroundColor: '#1e293b',

                        padding: 10,

                        callbacks: {

                            label: function(context) {

                                const label =
                                    context.label || '';

                                const value =
                                    context.parsed || 0;

                                return ' ' +
                                    label +
                                    ': ' +
                                    value;

                            }

                        }

                    }

                }

            }

        });

    }


    /* =========================================================
       VIOLATIONS BY LOCATION
    ========================================================= */

    const locationElement =
        document.getElementById('locationChart');

    if (locationElement) {

        new Chart(locationElement, {

            type: 'bar',

            data: {

                labels: locationLabels,

                datasets: [{

                    label: 'Violations',

                    data: locationTotals,

                    backgroundColor: '#dc3545',

                    borderRadius: 6,

                    borderSkipped: false,

                    barThickness: 22,

                    maxBarThickness: 26

                }]

            },


            options: {

                indexAxis: 'y',

                responsive: true,

                maintainAspectRatio: false,


                plugins: {

                    legend: {
                        display: false
                    },


                    tooltip: {

                        backgroundColor: '#1e293b',

                        padding: 10,

                        displayColors: false,

                        callbacks: {

                            label: function(context) {

                                return ' Violations: ' +
                                    context.parsed.x;

                            }

                        }

                    }

                },


                scales: {

                    x: {

                        beginAtZero: true,

                        border: {
                            display: false
                        },

                        ticks: {
                            precision: 0
                        },

                        grid: {
                            color: 'rgba(100, 116, 139, 0.10)'
                        }

                    },


                    y: {

                        border: {
                            display: false
                        },

                        ticks: {

                            color: '#475569',

                            font: {
                                size: 12
                            }

                        },

                        grid: {
                            display: false
                        }

                    }

                }

            }

        });

    }


    /* =========================================================
       VIOLATIONS BY OFFICER
    ========================================================= */

    const officerElement =
        document.getElementById('officerChart');

    if (officerElement) {

        new Chart(officerElement, {

            type: 'bar',

            data: {

                labels: officerLabels,

                datasets: [{

                    label: 'Violations',

                    data: officerTotals,

                    backgroundColor: '#198754',

                    borderRadius: 6,

                    borderSkipped: false,

                    barThickness: 26,

                    maxBarThickness: 30

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

                        backgroundColor: '#1e293b',

                        padding: 10,

                        displayColors: false,

                        callbacks: {

                            label: function(context) {

                                return ' Violations: ' +
                                    context.parsed.y;

                            }

                        }

                    }

                },


                scales: {

                    x: {

                        border: {
                            display: false
                        },

                        ticks: {

                            color: '#475569',

                            font: {
                                size: 11
                            },

                            maxRotation: 0,

                            minRotation: 0

                        },

                        grid: {
                            display: false
                        }

                    },


                    y: {

                        beginAtZero: true,

                        border: {
                            display: false
                        },

                        ticks: {
                            precision: 0
                        },

                        grid: {
                            color: 'rgba(100, 116, 139, 0.10)'
                        }

                    }

                }

            }

        });

    }

</script>


<style>

    /* =========================================================
       GENERAL
    ========================================================= */

    .report-card,
    .analytics-card,
    .summary-card {

        border: 0;

        border-radius: 16px;

        box-shadow:
            0 2px 12px rgba(15, 23, 42, 0.06);

    }


    .card {
        border: 0;
    }


    /* =========================================================
       SUMMARY CARDS
    ========================================================= */

    .summary-card {
        min-height: 145px;
    }


    .summary-card .card-body {

        padding: 24px;

        display: flex;

        align-items: center;

    }


    .summary-content {

        width: 100%;

        display: flex;

        align-items: center;

        justify-content: space-between;

        gap: 15px;

    }


    .summary-label {

        color: #64748b;

        font-size: 12px;

        font-weight: 700;

        text-transform: uppercase;

        letter-spacing: .04em;

    }


    .summary-value {

        color: #1e293b;

        font-size: 30px;

        font-weight: 700;

        line-height: 1.2;

        margin-top: 8px;

    }


    .summary-value-text {

        max-width: 180px;

        white-space: nowrap;

        overflow: hidden;

        text-overflow: ellipsis;

        font-size: 19px;

    }


    .summary-description {

        color: #94a3b8;

        font-size: 12px;

        margin-top: 5px;

    }


    .summary-icon {

        width: 48px;

        height: 48px;

        min-width: 48px;

        border-radius: 12px;

        display: flex;

        align-items: center;

        justify-content: center;

        font-size: 19px;

    }


    .summary-icon.primary {

        background: rgba(13, 110, 253, .10);

        color: #0d6efd;

    }


    .summary-icon.danger {

        background: rgba(220, 53, 69, .10);

        color: #dc3545;

    }


    .summary-icon.success {

        background: rgba(25, 135, 84, .10);

        color: #198754;

    }


    .summary-icon.warning {

        background: rgba(255, 193, 7, .12);

        color: #d89b00;

    }


    /* =========================================================
       FILTER CARD
    ========================================================= */

    .report-card-header {

        background: #fff;

        border: 0;

        padding: 22px 24px 10px;

        display: flex;

        align-items: center;

    }


    .section-icon {

        width: 42px;

        height: 42px;

        border-radius: 10px;

        display: flex;

        align-items: center;

        justify-content: center;

        margin-right: 14px;

        background: rgba(13, 110, 253, .10);

        color: #0d6efd;

    }


    .report-card .card-body {

        padding: 20px 24px 24px;

    }


    .form-label {

        font-size: 13px;

        font-weight: 600;

        color: #334155;

        margin-bottom: 7px;

    }


    .form-control,
    .form-select {

        min-height: 43px;

        border-radius: 9px;

        border-color: #dbe2ea;

        font-size: 14px;

    }


    .form-control:focus,
    .form-select:focus {

        border-color: #86b7fe;

        box-shadow:
            0 0 0 .2rem rgba(13, 110, 253, .10);

    }


    .filter-footer {

        display: flex;

        align-items: center;

        justify-content: space-between;

        flex-wrap: wrap;

        gap: 15px;

        border-top: 1px solid #eef2f6;

    }


    /* =========================================================
       ANALYTICS GRID
    ========================================================= */

    .analytics-grid {

        align-items: stretch;

    }


    .analytics-grid > [class*="col-"] {

        display: flex;

    }


    /* =========================================================
       ANALYTICS CARDS
    ========================================================= */

    .analytics-card {

        width: 100%;

        height: 100%;

        border: 0;

        border-radius: 16px;

        overflow: hidden;

        background: #fff;

        box-shadow:
            0 2px 12px rgba(15, 23, 42, 0.06);

        display: flex;

        flex-direction: column;

    }


    /* =========================================================
       ANALYTICS HEADER
    ========================================================= */

    .analytics-header {

        background: #fff;

        border: 0;

        padding: 20px 24px 10px;

        flex-shrink: 0;

    }


    .analytics-title {

        display: flex;

        align-items: center;

        gap: 13px;

    }


    .analytics-title h5 {

        font-size: 16px;

        font-weight: 700;

        margin: 0 0 3px;

        color: #1e293b;

    }


    .analytics-title small {

        color: #94a3b8;

        font-size: 12px;

    }


    .analytics-icon {

        width: 40px;

        height: 40px;

        border-radius: 10px;

        display: flex;

        align-items: center;

        justify-content: center;

        flex-shrink: 0;

    }


    .analytics-icon.primary {

        background: rgba(13, 110, 253, .10);

        color: #0d6efd;

    }


    .analytics-icon.danger {

        background: rgba(220, 53, 69, .10);

        color: #dc3545;

    }


    .analytics-icon.success {

        background: rgba(25, 135, 84, .10);

        color: #198754;

    }


    /* =========================================================
       ANALYTICS BODY
    ========================================================= */

    .analytics-card .card-body {

        padding: 10px 24px 24px;

        flex: 1;

        min-height: 0;

        display: flex;

        flex-direction: column;

    }


    /* =========================================================
       CHART CONTAINERS
    ========================================================= */

    .chart-container {

        position: relative;

        width: 100%;

        flex: 1;

        min-height: 0;

    }


    .chart-container canvas {

        display: block;

        width: 100% !important;

        height: 100% !important;

    }


    /* =========================================================
       FIRST ROW
       MONTHLY + DISTRIBUTION
    ========================================================= */

    .analytics-grid > .col-xl-7,
    .analytics-grid > .col-xl-5 {

        min-height: 390px;

    }


    .analytics-grid > .col-xl-7 .analytics-card,
    .analytics-grid > .col-xl-5 .analytics-card {

        min-height: 390px;

    }


    .chart-line,
    .chart-doughnut {

        height: 300px;

        min-height: 300px;

    }


    /* =========================================================
       SECOND ROW
       LOCATION + OFFICER
    ========================================================= */

    .analytics-grid > .col-xl-6 {

        min-height: 420px;

    }


    .analytics-grid > .col-xl-6 .analytics-card {

        min-height: 420px;

    }


    .chart-location,
    .chart-officer {

        height: 330px;

        min-height: 330px;

    }


    /* =========================================================
       LOCATION / OFFICER CANVAS
    ========================================================= */

    .chart-location canvas,
    .chart-officer canvas {

        width: 100% !important;

        height: 100% !important;

    }


    /* =========================================================
       TABLET / SMALL DESKTOP
    ========================================================= */

    @media (max-width: 1199px) {

        .analytics-grid > .col-xl-7,
        .analytics-grid > .col-xl-5 {

            min-height: 380px;

        }


        .analytics-grid > .col-xl-7 .analytics-card,
        .analytics-grid > .col-xl-5 .analytics-card {

            min-height: 380px;

        }


        .analytics-grid > .col-xl-6 {

            min-height: 400px;

        }


        .analytics-grid > .col-xl-6 .analytics-card {

            min-height: 400px;

        }


        .chart-line,
        .chart-doughnut {

            height: 290px;

            min-height: 290px;

        }


        .chart-location,
        .chart-officer {

            height: 310px;

            min-height: 310px;

        }

    }


    /* =========================================================
       TABLET
    ========================================================= */

    @media (max-width: 991px) {

        .analytics-grid > [class*="col-"] {

            min-height: auto;

        }


        .analytics-grid > [class*="col-"] .analytics-card {

            min-height: 380px;

        }


        .chart-line,
        .chart-doughnut {

            height: 290px;

            min-height: 290px;

        }


        .chart-location,
        .chart-officer {

            height: 300px;

            min-height: 300px;

        }

    }


    /* =========================================================
       MOBILE
    ========================================================= */

    @media (max-width: 767px) {

        .container-fluid {

            padding-left: 15px !important;

            padding-right: 15px !important;

        }


        .summary-card {

            min-height: 135px;

        }


        .summary-card .card-body {

            padding: 20px;

        }


        .summary-value {

            font-size: 26px;

        }


        .analytics-header {

            padding: 18px 20px 10px;

        }


        .analytics-card .card-body {

            padding: 10px 20px 20px;

        }


        .analytics-grid > [class*="col-"] .analytics-card {

            min-height: 350px;

        }


        .chart-line {

            height: 250px;

            min-height: 250px;

        }


        .chart-doughnut {

            height: 270px;

            min-height: 270px;

        }


        .chart-location,
        .chart-officer {

            height: 280px;

            min-height: 280px;

        }


        .generate-content {

            align-items: flex-start;

            flex-direction: column;

        }


        .filter-footer {

            align-items: flex-start;

            flex-direction: column;

        }

    }


    /* =========================================================
       SMALL MOBILE
    ========================================================= */

    @media (max-width: 480px) {

        .analytics-header,
        .report-card-header {

            padding-left: 18px;

            padding-right: 18px;

        }


        .analytics-card .card-body,
        .report-card .card-body {

            padding-left: 18px;

            padding-right: 18px;

        }


        .analytics-grid > [class*="col-"] .analytics-card {

            min-height: 330px;

        }


        .chart-line {

            height: 230px;

            min-height: 230px;

        }


        .chart-doughnut {

            height: 250px;

            min-height: 250px;

        }


        .chart-location,
        .chart-officer {

            height: 260px;

            min-height: 260px;

        }

    }


    /* =========================================================
       GENERATE REPORT
    ========================================================= */

    .generate-card .card-body {

        padding: 22px 24px;

    }


    .generate-content {

        display: flex;

        align-items: center;

        justify-content: space-between;

        gap: 20px;

    }


    .generate-content h5 {

        color: #1e293b;

    }

</style>

@endsection