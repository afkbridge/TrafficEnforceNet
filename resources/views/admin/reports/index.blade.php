@extends('layouts.admin')

@section('title', 'Reports')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h2 class="fw-bold">
                <i class="fas fa-chart-line"></i>
                Reports Dashboard
            </h2>

            <p class="text-muted">
                Monitor traffic violation statistics and generate reports.
            </p>
        </div>

    </div>


    <!-- Summary Cards -->
    <div class="row g-4">


        <!-- Total Violations -->
        <div class="col-md-3">

            <div class="card shadow-sm border-0">

                <div class="card-body">

                    <div class="d-flex justify-content-between">

                        <div>
                            <h6 class="text-muted">
                                Total Violations
                            </h6>

                            <h2 class="fw-bold">
                                {{ $totalViolations }}
                            </h2>
                        </div>


                        <div>
                            <i class="fas fa-file-lines fa-2x text-danger"></i>
                        </div>

                    </div>

                </div>

            </div>

        </div>



        <!-- Today -->
        <div class="col-md-3">

            <div class="card shadow-sm border-0">

                <div class="card-body">

                    <div class="d-flex justify-content-between">

                        <div>

                            <h6 class="text-muted">
                                Today's Violations
                            </h6>

                            <h2 class="fw-bold">
                                {{ $todayViolations }}
                            </h2>

                        </div>


                        <i class="fas fa-calendar-day fa-2x text-primary"></i>

                    </div>

                </div>

            </div>

        </div>



        <!-- Monthly -->
        <div class="col-md-3">

            <div class="card shadow-sm border-0">

                <div class="card-body">

                    <div class="d-flex justify-content-between">

                        <div>

                            <h6 class="text-muted">
                                This Month
                            </h6>

                            <h2 class="fw-bold">
                                {{ $monthlyViolations }}
                            </h2>

                        </div>


                        <i class="fas fa-chart-bar fa-2x text-success"></i>

                    </div>

                </div>

            </div>

        </div>



        <!-- Common Violation -->
        <div class="col-md-3">

            <div class="card shadow-sm border-0">

                <div class="card-body">

                    <h6 class="text-muted">
                        Most Common Violation
                    </h6>


                    @if($mostCommonViolation && $mostCommonViolation->violationType)

                    <h5 class="fw-bold">
                        {{ $mostCommonViolation->violationType->name }}
                    </h5>

                    @else

                    <h5>
                        No Data
                    </h5>

                    @endif


                </div>

            </div>

        </div>


    </div>



    <!-- Analytics Placeholder -->
    <div class="row mt-5">


        <div class="col-md-8">

            <div class="card shadow-sm border-0">

                <div class="card-body">

                    <h5>
                        <i class="fas fa-chart-column"></i>
                        Monthly Violation Trends
                    </h5>


                    <div class="text-center text-muted py-5">

                        Chart will be displayed here.

                    </div>


                </div>

            </div>

        </div>



        <div class="col-md-4">


            <div class="card shadow-sm border-0">


                <div class="card-body">


                    <h5>
                        <i class="fas fa-chart-pie"></i>
                        Violation Distribution
                    </h5>


                    <div class="text-center text-muted py-5">

                        Chart will be displayed here.

                    </div>


                </div>


            </div>


        </div>


    </div>




    <!-- Report Generator -->

    <div class="card shadow-sm border-0 mt-4">


        <div class="card-body">


            <h5>
                <i class="fas fa-file-export"></i>
                Generate Report
            </h5>


            <p class="text-muted">
                Export traffic violation reports based on selected criteria.
            </p>



            <button class="btn btn-danger">
                <i class="fas fa-file-pdf"></i>
                Export PDF
            </button>


            <button class="btn btn-success">
                <i class="fas fa-file-excel"></i>
                Export Excel
            </button>


        </div>


    </div>


</div>

@endsection