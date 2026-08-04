@extends('layouts.admin')

@section('title', 'Reports')

@section('content')

<div class="container-fluid px-4 pt-2">

    <!-- Header (kept compact) -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h2 class="fw-bold mb-1">
                <i class="fas fa-chart-line me-2 text-primary"></i>
                Reports Dashboard
            </h2>
            <p class="text-muted mb-0">
                Monitor traffic violation statistics and generate reports.
            </p>
        </div>
    </div>

    <!-- Summary Cards - larger & wider -->
    <div class="row g-3 mb-4">

        <!-- Total Violations -->
        <div class="col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body py-4 px-4 d-flex align-items-center justify-content-between">
                    <div>
                        <p class="text-muted small mb-2 text-uppercase fw-semibold">Total Violations</p>
                        <h2 class="fw-bold mb-0 display-6">{{ $totalViolations }}</h2>
                    </div>
                    <div class="bg-danger bg-opacity-10 rounded-3 p-3">
                        <i class="fas fa-file-lines fa-2x text-danger"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Today's Violations -->
        <div class="col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body py-4 px-4 d-flex align-items-center justify-content-between">
                    <div>
                        <p class="text-muted small mb-2 text-uppercase fw-semibold">Today's Violations</p>
                        <h2 class="fw-bold mb-0 display-6">{{ $todayViolations }}</h2>
                    </div>
                    <div class="bg-primary bg-opacity-10 rounded-3 p-3">
                        <i class="fas fa-calendar-day fa-2x text-primary"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- This Month -->
        <div class="col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body py-4 px-4 d-flex align-items-center justify-content-between">
                    <div>
                        <p class="text-muted small mb-2 text-uppercase fw-semibold">This Month</p>
                        <h2 class="fw-bold mb-0 display-6">{{ $monthlyViolations }}</h2>
                    </div>
                    <div class="bg-success bg-opacity-10 rounded-3 p-3">
                        <i class="fas fa-chart-bar fa-2x text-success"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Most Common Violation -->
        <div class="col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body py-4 px-4 d-flex align-items-center justify-content-between">
                    <div>
                        <p class="text-muted small mb-2 text-uppercase fw-semibold">Most Common</p>
                        <h4 class="fw-bold mb-0">
                            @if($mostCommonViolation && $mostCommonViolation->violationType)
                                {{ $mostCommonViolation->violationType->name }}
                            @else
                                No Data
                            @endif
                        </h4>
                    </div>
                    <div class="bg-warning bg-opacity-10 rounded-3 p-3">
                        <i class="fas fa-exclamation-triangle fa-2x text-warning"></i>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- Charts Row -->
    <div class="row g-3 mb-4">

        <!-- Monthly Trends -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-0 pt-3 pb-0">
                    <h5 class="mb-0 fw-semibold">
                        <i class="fas fa-chart-column me-2 text-primary"></i>
                        Monthly Violation Trends
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-flex flex-column align-items-center justify-content-center text-muted py-5">
                        <i class="fas fa-chart-line fa-3x mb-3 opacity-25"></i>
                        <p class="mb-0">Chart will be displayed here.</p>
                        <small class="text-muted">Connect your chart library to visualize monthly trends</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Violation Distribution -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-0 pt-3 pb-0">
                    <h5 class="mb-0 fw-semibold">
                        <i class="fas fa-chart-pie me-2 text-primary"></i>
                        Violation Distribution
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-flex flex-column align-items-center justify-content-center text-muted py-5">
                        <i class="fas fa-chart-pie fa-3x mb-3 opacity-25"></i>
                        <p class="mb-0">Chart will be displayed here.</p>
                        <small class="text-muted">Pie chart of violation types</small>
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