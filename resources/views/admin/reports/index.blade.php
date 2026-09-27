@extends('layouts.admin')

@section('title', 'Reports')

@section('content')

<div class="reports-page container-fluid px-4 pt-3 pb-4">


{{-- ========================================================= --}}
{{-- PAGE HEADER --}}
{{-- ========================================================= --}}

<div class="reports-header mb-4">
    <div>
        <div class="reports-eyebrow">
            <i class="fas fa-chart-line me-2"></i>
            POSO ANALYTICS
        </div>

        <h2 class="reports-title">
            Reports Dashboard
        </h2>

        <p class="reports-subtitle">
            Analyze traffic violations, identify trends, and support enforcement decision-making.
        </p>
    </div>
</div>


{{-- ========================================================= --}}
{{-- SUMMARY CARDS --}}
{{-- ========================================================= --}}

<div class="row g-3 mb-4">

    {{-- Total Violations --}}
    <div class="col-xl-3 col-md-6">
        <div class="summary-card summary-danger h-100">
            <div class="summary-card-body">

                <div class="summary-main">
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

                <div class="summary-icon">
                    <i class="fas fa-file-lines"></i>
                </div>

            </div>
        </div>
    </div>


    {{-- Today's Violations --}}
    <div class="col-xl-3 col-md-6">
        <div class="summary-card summary-primary h-100">
            <div class="summary-card-body">

                <div class="summary-main">
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

                <div class="summary-icon">
                    <i class="fas fa-calendar-day"></i>
                </div>

            </div>
        </div>
    </div>


    {{-- This Month --}}
    <div class="col-xl-3 col-md-6">
        <div class="summary-card summary-success h-100">
            <div class="summary-card-body">

                <div class="summary-main">
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

                <div class="summary-icon">
                    <i class="fas fa-chart-column"></i>
                </div>

            </div>
        </div>
    </div>


    {{-- Most Common Violation --}}
    <div class="col-xl-3 col-md-6">
        <div class="summary-card summary-warning h-100">
            <div class="summary-card-body">

                <div class="summary-main summary-main-common">

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

                <div class="summary-icon">
                    <i class="fas fa-triangle-exclamation"></i>
                </div>

            </div>
        </div>
    </div>

</div>


{{-- ========================================================= --}}
{{-- FILTERS --}}
{{-- ========================================================= --}}

<div class="report-card filter-card mb-4">

    <div class="filter-card-header">

        <div class="section-icon">
            <i class="fas fa-filter"></i>
        </div>

        <div>
            <div class="section-title">
                Report Filters
            </div>

            <div class="section-subtitle">
                Narrow the report by date, violation type, officer, or location.
            </div>
        </div>

    </div>


    <div class="filter-card-body">

        <form
            method="GET"
            action="{{ route('admin.reports.index') }}"
        >

            <div class="row g-3">

                {{-- Date From --}}
                <div class="col-xl-3 col-lg-6 col-md-6">

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
                <div class="col-xl-3 col-lg-6 col-md-6">

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
                <div class="col-xl-3 col-lg-6 col-md-6">

                    <label class="form-label">
                        <i class="fas fa-ban text-danger me-2"></i>
                        Violation Type
                    </label>

                    <select
                        name="violation_type"
                        class="form-select"
                    >

                        <option value="">
                            All Violation Types
                        </option>

                        <option
                            value="other"
                            {{ request('violation_type') === 'other' ? 'selected' : '' }}
                        >
                            Other
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
                <div class="col-xl-3 col-lg-6 col-md-6">

                    <label class="form-label">
                        <i class="fas fa-user-shield text-success me-2"></i>
                        Officer
                    </label>

                    <select
                        name="officer"
                        class="form-select"
                    >

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
                <div class="col-xl-6 col-lg-6 col-md-12">

                    <label class="form-label">
                        <i class="fas fa-location-dot text-danger me-2"></i>
                        Location
                    </label>

                    <select
                        name="location"
                        class="form-select"
                    >

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


            {{-- Filter Footer --}}
            <div class="filter-footer">

                <div class="filter-information">
                    <i class="fas fa-circle-info"></i>

                    <span>
                        Filters apply to the report analytics and summary cards.
                    </span>
                </div>


                <div class="filter-actions">

                    <a
                        href="{{ route('admin.reports.index') }}"
                        class="btn btn-light border"
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

<div class="analytics-grid">


    {{-- ===================================================== --}}
    {{-- MONTHLY TREND --}}
    {{-- ===================================================== --}}

    <div class="analytics-card analytics-wide">

        <div class="analytics-header">

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


        <div class="analytics-body">

            <div class="chart-container chart-line">
                <canvas id="monthlyTrendChart"></canvas>
            </div>

        </div>

    </div>


    {{-- ===================================================== --}}
    {{-- VIOLATION DISTRIBUTION --}}
    {{-- ===================================================== --}}

    <div class="analytics-card analytics-narrow">

        <div class="analytics-header">

            <div class="analytics-title">

                <div class="analytics-icon purple">
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


        <div class="analytics-body">

            <div class="chart-container chart-doughnut">
                <canvas id="violationPieChart"></canvas>
            </div>

        </div>

    </div>


    {{-- ===================================================== --}}
    {{-- LOCATION --}}
    {{-- ===================================================== --}}

    <div class="analytics-card analytics-half">

        <div class="analytics-header">

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


        <div class="analytics-body">

            <div class="chart-container chart-location">
                <canvas id="locationChart"></canvas>
            </div>

        </div>

    </div>


    {{-- ===================================================== --}}
    {{-- OFFICER --}}
    {{-- ===================================================== --}}

    <div class="analytics-card analytics-half">

        <div class="analytics-header">

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


        <div class="analytics-body">

            <div class="chart-container chart-officer">
                <canvas id="officerChart"></canvas>
            </div>

        </div>

    </div>

</div>


{{-- ========================================================= --}}
{{-- GENERATE REPORT --}}
{{-- ========================================================= --}}

<div class="generate-card mt-4">

    <div class="generate-content">

        <div class="generate-info">

            <div class="generate-icon">
                <i class="fas fa-file-export"></i>
            </div>

            <div>

                <h5>
                    Generate Report
                </h5>

                <p>
                    Export traffic violation data based on the selected filters.
                </p>

            </div>

        </div>


        <div class="generate-actions">

            <a
                href="{{ route('admin.reports.export.excel', request()->query()) }}"
                class="btn btn-success px-4"
            >
                <i class="fas fa-file-excel me-2"></i>
                Export Excel
            </a>

        </div>

    </div>

</div>


</div>

@endsection

@section('scripts')

<script>

    // =========================================================
    // DATA
    // =========================================================

    const monthlyLabels = @json(
        $monthlyTrend->pluck('month')->map(function ($month) {
            return \Carbon\Carbon::create()
                ->month($month)
                ->format('M');
        })
    );

    const monthlyTotals = @json(
        $monthlyTrend->pluck('total')
    );


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


    // =========================================================
    // CHART DEFAULTS
    // =========================================================

    Chart.defaults.font.family =
        '-apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Arial, sans-serif';

    Chart.defaults.color = '#64748b';


    // =========================================================
    // MONTHLY TREND
    // =========================================================

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

                    backgroundColor:
                        'rgba(13, 110, 253, 0.08)',

                    fill: true,

                    borderWidth: 2.5,

                    pointRadius: 3,

                    pointHoverRadius: 5,

                    pointBackgroundColor: '#0d6efd',

                    pointBorderWidth: 2,

                    pointBorderColor: '#ffffff',

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

                        padding: 11,

                        displayColors: false,

                        cornerRadius: 8,

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
                        },

                        ticks: {
                            padding: 8
                        }

                    },


                    y: {

                        beginAtZero: true,

                        border: {
                            display: false
                        },

                        ticks: {
                            precision: 0,
                            padding: 8
                        },

                        grid: {
                            color:
                                'rgba(100, 116, 139, 0.10)'
                        }

                    }

                }

            }

        });

    }


    // =========================================================
    // VIOLATION DISTRIBUTION
    // =========================================================

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
                        '#0dcaf0',
                        '#6610f2',
                        '#d63384'
                    ],

                    borderWidth: 3,

                    borderColor: '#ffffff',

                    hoverOffset: 6
                }]

            },


            options: {

                responsive: true,

                maintainAspectRatio: false,

                cutout: '64%',

                plugins: {

                    legend: {

                        position: 'bottom',

                        labels: {

                            padding: 14,

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

                        padding: 11,

                        cornerRadius: 8,

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


    // =========================================================
    // VIOLATIONS BY LOCATION
    // =========================================================

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

                    barThickness: 18,

                    maxBarThickness: 20,

                    categoryPercentage: 0.72,

                    barPercentage: 0.78

                }]

            },


            options: {

                indexAxis: 'y',

                responsive: true,

                maintainAspectRatio: false,

                layout: {

                    padding: {
                        top: 8,
                        right: 12,
                        bottom: 8,
                        left: 4
                    }

                },


                plugins: {

                    legend: {
                        display: false
                    },


                    tooltip: {

                        backgroundColor: '#1e293b',

                        padding: 11,

                        cornerRadius: 8,

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

                            precision: 0,

                            padding: 8

                        },

                        grid: {

                            color:
                                'rgba(100, 116, 139, 0.10)'

                        }

                    },


                    y: {

                        border: {
                            display: false
                        },

                        offset: true,

                        ticks: {

                            color: '#475569',

                            padding: 10,

                            font: {
                                size: 12,
                                weight: '500'
                            },

                            autoSkip: false

                        },

                        grid: {

                            display: false

                        }

                    }

                }

            }

        });

    }


    // =========================================================
    // VIOLATIONS BY OFFICER
    // =========================================================

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

                    barThickness: 24,

                    maxBarThickness: 28,

                    categoryPercentage: 0.70,

                    barPercentage: 0.80

                }]

            },


            options: {

                responsive: true,

                maintainAspectRatio: false,

                layout: {

                    padding: {
                        top: 8,
                        right: 10,
                        bottom: 8,
                        left: 4
                    }

                },


                plugins: {

                    legend: {
                        display: false
                    },


                    tooltip: {

                        backgroundColor: '#1e293b',

                        padding: 11,

                        cornerRadius: 8,

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
                                size: 11,
                                weight: '500'
                            },

                            maxRotation: 0,

                            minRotation: 0,

                            padding: 8

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

                            precision: 0,

                            padding: 8

                        },

                        grid: {

                            color:
                                'rgba(100, 116, 139, 0.10)'

                        }

                    }

                }

            }

        });

    }

</script>

<style>

/* =========================================================
   PAGE
   ========================================================= */

.reports-page {
    max-width: 1800px;
    margin: 0 auto;
}

.reports-header {
    display: flex;
    align-items: flex-end;
    justify-content: space-between;
}

.reports-eyebrow {
    color: #0d6efd;
    font-size: 11px;
    font-weight: 800;
    letter-spacing: .10em;
    text-transform: uppercase;
    margin-bottom: 5px;
}

.reports-title {
    color: #172033;
    font-size: 28px;
    font-weight: 750;
    letter-spacing: -.02em;
    margin: 0;
}

.reports-subtitle {
    color: #7b8798;
    font-size: 13px;
    margin: 6px 0 0;
}


/* =========================================================
   SHARED CARDS
   ========================================================= */

.summary-card,
.report-card,
.analytics-card,
.generate-card {
    background: #ffffff;
    border: 1px solid #edf1f5;
    border-radius: 16px;
    box-shadow:
        0 3px 14px rgba(15, 23, 42, 0.045);
}


/* =========================================================
   SUMMARY CARDS
   ========================================================= */

.summary-card {
    position: relative;
    overflow: hidden;
    min-height: 145px;
    transition:
        transform .18s ease,
        box-shadow .18s ease;
}

.summary-card:hover {
    transform: translateY(-2px);

    box-shadow:
        0 8px 22px rgba(15, 23, 42, 0.08);
}

.summary-card::before {
    content: "";
    position: absolute;
    left: 0;
    top: 0;
    bottom: 0;
    width: 4px;
}

.summary-primary::before {
    background: #0d6efd;
}

.summary-danger::before {
    background: #dc3545;
}

.summary-success::before {
    background: #198754;
}

.summary-warning::before {
    background: #f0ad00;
}

.summary-card-body {
    min-height: 145px;
    padding: 23px 24px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 18px;
}

.summary-main {
    min-width: 0;
}

.summary-label {
    color: #718096;
    font-size: 11px;
    font-weight: 750;
    letter-spacing: .06em;
    text-transform: uppercase;
}

.summary-value {
    color: #172033;
    font-size: 30px;
    font-weight: 750;
    line-height: 1.15;
    margin-top: 8px;
}

.summary-value-text {
    max-width: 185px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    font-size: 18px;
}

.summary-description {
    color: #9aa6b5;
    font-size: 11px;
    margin-top: 6px;
}

.summary-icon {
    width: 48px;
    height: 48px;
    min-width: 48px;
    border-radius: 13px;

    display: flex;
    align-items: center;
    justify-content: center;

    font-size: 18px;
}

.summary-primary .summary-icon {
    background: rgba(13, 110, 253, .09);
    color: #0d6efd;
}

.summary-danger .summary-icon {
    background: rgba(220, 53, 69, .09);
    color: #dc3545;
}

.summary-success .summary-icon {
    background: rgba(25, 135, 84, .09);
    color: #198754;
}

.summary-warning .summary-icon {
    background: rgba(255, 193, 7, .13);
    color: #c78e00;
}


/* =========================================================
   FILTER CARD
   ========================================================= */

.filter-card {
    overflow: hidden;
}

.filter-card-header {
    padding: 22px 24px 17px;

    display: flex;
    align-items: center;

    border-bottom: 1px solid #f0f3f7;
}

.section-icon {
    width: 42px;
    height: 42px;
    min-width: 42px;

    border-radius: 11px;

    display: flex;
    align-items: center;
    justify-content: center;

    margin-right: 14px;

    background: rgba(13, 110, 253, .09);
    color: #0d6efd;
}

.section-title {
    color: #172033;
    font-size: 15px;
    font-weight: 700;
}

.section-subtitle {
    color: #97a2b1;
    font-size: 11px;
    margin-top: 3px;
}

.filter-card-body {
    padding: 21px 24px 23px;
}

.form-label {
    color: #435066;
    font-size: 12px;
    font-weight: 650;
    margin-bottom: 7px;
}

.form-control,
.form-select {
    min-height: 43px;

    border: 1px solid #dce3eb;
    border-radius: 9px;

    color: #334155;
    background-color: #fff;

    font-size: 13px;

    transition:
        border-color .15s ease,
        box-shadow .15s ease;
}

.form-control:hover,
.form-select:hover {
    border-color: #c8d2df;
}

.form-control:focus,
.form-select:focus {
    border-color: #86b7fe;

    box-shadow:
        0 0 0 .18rem rgba(13, 110, 253, .09);
}

.filter-footer {
    margin-top: 21px;
    padding-top: 17px;

    border-top: 1px solid #edf1f5;

    display: flex;
    align-items: center;
    justify-content: space-between;

    gap: 15px;
    flex-wrap: wrap;
}

.filter-information {
    color: #8b97a7;
    font-size: 11px;

    display: flex;
    align-items: center;
    gap: 7px;
}

.filter-information i {
    color: #0d6efd;
}

.filter-actions {
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
}

.filter-actions .btn {
    min-height: 39px;
    border-radius: 8px;
    font-size: 12px;
    font-weight: 600;
}


/* =========================================================
   ANALYTICS GRID
   ========================================================= */

.analytics-grid {
    display: grid;

    grid-template-columns:
        minmax(0, 1.4fr)
        minmax(0, 1fr);

    gap: 18px;

    align-items: stretch;
}

.analytics-wide {
    min-height: 405px;
}

.analytics-narrow {
    min-height: 405px;
}

.analytics-half {
    min-height: 450px;
}


/* =========================================================
   ANALYTICS CARDS
   ========================================================= */

.analytics-card {
    overflow: hidden;

    display: flex;
    flex-direction: column;

    min-width: 0;
}

.analytics-header {
    flex-shrink: 0;

    padding: 20px 23px 13px;

    background: #fff;
}

.analytics-title {
    display: flex;
    align-items: center;
    gap: 12px;
}

.analytics-title h5 {
    color: #1e293b;

    font-size: 15px;
    font-weight: 700;

    margin: 0 0 3px;
}

.analytics-title small {
    color: #98a3b2;
    font-size: 11px;
}

.analytics-icon {
    width: 40px;
    height: 40px;
    min-width: 40px;

    border-radius: 11px;

    display: flex;
    align-items: center;
    justify-content: center;

    font-size: 15px;
}

.analytics-icon.primary {
    color: #0d6efd;
    background: rgba(13, 110, 253, .09);
}

.analytics-icon.purple {
    color: #6f42c1;
    background: rgba(111, 66, 193, .09);
}

.analytics-icon.danger {
    color: #dc3545;
    background: rgba(220, 53, 69, .09);
}

.analytics-icon.success {
    color: #198754;
    background: rgba(25, 135, 84, .09);
}

.analytics-body {
    position: relative;

    flex: 1;

    min-height: 0;

    padding: 4px 22px 22px;

    display: flex;
    flex-direction: column;
}

.chart-container {
    position: relative;

    width: 100%;
    height: 100%;

    flex: 1;

    min-height: 0;
}

.chart-container canvas {
    display: block;

    width: 100% !important;
    height: 100% !important;
}


/* =========================================================
   CHART HEIGHTS
   ========================================================= */

.chart-line,
.chart-doughnut {
    height: 315px;
    min-height: 315px;
}

.chart-location,
.chart-officer {
    height: 360px;
    min-height: 360px;
}


/* =========================================================
   LOCATION CHART
   Extra space between horizontal bars
   ========================================================= */

.chart-location {
    padding-top: 4px;
    padding-bottom: 4px;
}


/* =========================================================
   GENERATE REPORT
   ========================================================= */

.generate-card {
    overflow: hidden;
}

.generate-content {
    min-height: 88px;

    padding: 18px 22px;

    display: flex;
    align-items: center;
    justify-content: space-between;

    gap: 20px;
}

.generate-info {
    display: flex;
    align-items: center;
    gap: 13px;
}

.generate-icon {
    width: 42px;
    height: 42px;
    min-width: 42px;

    border-radius: 11px;

    display: flex;
    align-items: center;
    justify-content: center;

    background: rgba(13, 110, 253, .09);
    color: #0d6efd;
}

.generate-info h5 {
    color: #172033;
    font-size: 14px;
    font-weight: 700;
    margin: 0 0 3px;
}

.generate-info p {
    color: #98a3b2;
    font-size: 11px;
    margin: 0;
}

.generate-actions .btn {
    min-height: 40px;

    border-radius: 8px;

    font-size: 12px;
    font-weight: 650;

    box-shadow:
        0 3px 8px rgba(25, 135, 84, .12);
}


/* =========================================================
   TABLET / SMALL DESKTOP
   ========================================================= */

@media (max-width: 1199px) {

    .analytics-grid {
        grid-template-columns:
            minmax(0, 1fr)
            minmax(0, 1fr);
    }

    .analytics-wide {
        min-height: 395px;
    }

    .analytics-narrow {
        min-height: 395px;
    }

    .chart-line,
    .chart-doughnut {
        height: 300px;
        min-height: 300px;
    }

    .chart-location,
    .chart-officer {
        height: 345px;
        min-height: 345px;
    }

}


/* =========================================================
   TABLET
   ========================================================= */

@media (max-width: 991px) {

    .reports-page {
        padding-left: 18px !important;
        padding-right: 18px !important;
    }

    .analytics-grid {
        grid-template-columns: 1fr;
    }

    .analytics-wide,
    .analytics-narrow,
    .analytics-half {
        min-height: 390px;
    }

    .chart-line,
    .chart-doughnut {
        height: 295px;
        min-height: 295px;
    }

    .chart-location,
    .chart-officer {
        height: 320px;
        min-height: 320px;
    }

}


/* =========================================================
   MOBILE
   ========================================================= */

@media (max-width: 767px) {

    .reports-page {
        padding-left: 14px !important;
        padding-right: 14px !important;
    }

    .reports-title {
        font-size: 24px;
    }

    .reports-subtitle {
        font-size: 12px;
        line-height: 1.5;
    }

    .summary-card-body {
        min-height: 130px;
        padding: 20px;
    }

    .summary-value {
        font-size: 27px;
    }

    .summary-value-text {
        max-width: 175px;
        font-size: 17px;
    }

    .filter-card-header {
        padding: 19px;
    }

    .filter-card-body {
        padding: 19px;
    }

    .filter-footer {
        align-items: flex-start;
        flex-direction: column;
    }

    .filter-actions {
        width: 100%;
    }

    .filter-actions .btn {
        flex: 1;
    }

    .analytics-wide,
    .analytics-narrow,
    .analytics-half {
        min-height: 365px;
    }

    .analytics-header {
        padding: 18px 19px 12px;
    }

    .analytics-body {
        padding: 4px 18px 18px;
    }

    .chart-line {
        height: 265px;
        min-height: 265px;
    }

    .chart-doughnut {
        height: 280px;
        min-height: 280px;
    }

    .chart-location,
    .chart-officer {
        height: 300px;
        min-height: 300px;
    }

    .generate-content {
        align-items: flex-start;
        flex-direction: column;
    }

    .generate-actions {
        width: 100%;
    }

    .generate-actions .btn {
        width: 100%;
    }

}


/* =========================================================
   SMALL MOBILE
   ========================================================= */

@media (max-width: 480px) {

    .reports-title {
        font-size: 22px;
    }

    .reports-eyebrow {
        font-size: 10px;
    }

    .summary-card-body {
        padding: 18px;
    }

    .summary-icon {
        width: 43px;
        height: 43px;
        min-width: 43px;
    }

    .analytics-wide,
    .analytics-narrow,
    .analytics-half {
        min-height: 345px;
    }

    .analytics-header {
        padding-left: 17px;
        padding-right: 17px;
    }

    .analytics-body {
        padding-left: 16px;
        padding-right: 16px;
    }

    .analytics-icon {
        width: 37px;
        height: 37px;
        min-width: 37px;
    }

    .analytics-title h5 {
        font-size: 14px;
    }

    .analytics-title small {
        font-size: 10px;
    }

    .chart-line {
        height: 245px;
        min-height: 245px;
    }

    .chart-doughnut {
        height: 255px;
        min-height: 255px;
    }

    .chart-location,
    .chart-officer {
        height: 280px;
        min-height: 280px;
    }

    .generate-content {
        padding: 18px;
    }

}

</style>

@endsection
