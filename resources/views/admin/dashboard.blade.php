@extends('layouts.admin')

@section('title', 'POSO Dashboard')

@section('content')

<div class="container-fluid dashboard-content">

    <!-- ========================================= -->
    <!-- PAGE HEADER -->
    <!-- ========================================= -->

    <div class="dashboard-header">

        <div>

            <h2 class="page-title">
                Dashboard
            </h2>

            <p class="page-subtitle">
                Welcome to the TrafficEnforceNet Monitoring Dashboard
            </p>

        </div>

    </div>

    <!-- ========================================= -->
    <!-- SUMMARY CARDS -->
    <!-- ========================================= -->

    <div class="row dashboard-stats">

        <!-- Total Violations -->

        <div class="col-lg-3 col-md-6 mb-4">

            <div class="dashboard-card stat-card">

                <div class="stat-info">

                    <span class="stat-title">

                        Total Violations

                    </span>

                    <h2 class="stat-number">

                        --

                    </h2>

                </div>

                <div class="card-icon blue">

                    <i class="fas fa-file-circle-exclamation"></i>

                </div>

            </div>

        </div>

        <!-- Today's Tickets -->

        <div class="col-lg-3 col-md-6 mb-4">

            <div class="dashboard-card stat-card">

                <div class="stat-info">

                    <span class="stat-title">

                        Today's Tickets

                    </span>

                    <h2 class="stat-number">

                        --

                    </h2>

                </div>

                <div class="card-icon green">

                    <i class="fas fa-ticket-alt"></i>

                </div>

            </div>

        </div>

        <!-- Pending Cases -->

        <div class="col-lg-3 col-md-6 mb-4">

            <div class="dashboard-card stat-card">

                <div class="stat-info">

                    <span class="stat-title">

                        Pending Cases

                    </span>

                    <h2 class="stat-number">

                        --

                    </h2>

                </div>

                <div class="card-icon orange">

                    <i class="fas fa-hourglass-half"></i>

                </div>

            </div>

        </div>

        <!-- Active Enforcers -->

        <div class="col-lg-3 col-md-6 mb-4">

            <div class="dashboard-card stat-card">

                <div class="stat-info">

                    <span class="stat-title">

                        Active Enforcers

                    </span>

                    <h2 class="stat-number">

                        --

                    </h2>

                </div>

                <div class="card-icon purple">

                    <i class="fas fa-user-shield"></i>

                </div>

            </div>

        </div>

    </div>

    <!-- ========================================= -->
    <!-- CHARTS -->
    <!-- ========================================= -->

    <div class="row">

        <!-- Monthly Violations -->

        <div class="col-lg-8 mb-4">

            <div class="dashboard-card chart-card">

                <div class="section-header">

                    <h5>

                        Monthly Violations

                    </h5>

                    <button class="btn-view">

                        View Report

                    </button>

                </div>

                <canvas id="monthlyChart"></canvas>

            </div>

        </div>

        <!-- Pie Chart -->

        <div class="col-lg-4 mb-4">

            <div class="dashboard-card chart-card">

                <div class="section-header">

                    <h5>

                        Violation Distribution

                    </h5>

                </div>

                <canvas id="pieChart"></canvas>

            </div>

        </div>

    </div>

    <!-- ========================================= -->
    <!-- MAP & RECENT VIOLATIONS -->
    <!-- ========================================= -->

    <div class="row">

        <!-- Map -->

        <div class="col-lg-5 mb-4">

            <div class="dashboard-card map-card">

                <div class="section-header">

                    <h5>

                        Violation Heat Map

                    </h5>

                </div>

                <div id="map">

                    <div class="map-placeholder">

                        <i class="fas fa-map-marked-alt fa-3x mb-3"></i>

                        <p>

                            Heat Map will appear here

                        </p>

                    </div>

                </div>

            </div>

        </div>

        <!-- Recent Violations -->

        <div class="col-lg-7 mb-4">

            <div class="dashboard-card table-card">

                <div class="section-header">

                    <h5>

                        Recent Violation Records

                    </h5>

                    <button class="btn-view">

                        View All

                    </button>

                </div>

                <div class="table-responsive">

                    <table class="table align-middle">

                        <thead>

                            <tr>

                                <th>Ticket No.</th>

                                <th>Violator</th>

                                <th>Violation</th>

                                <th>Status</th>

                                <th>Date</th>

                            </tr>

                        </thead>

                        <tbody>

                            <tr>

                                <td colspan="5" class="text-center py-5 text-muted">

                                    No violation records found.

                                </td>

                            </tr>

                        </tbody>

                    </table>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection