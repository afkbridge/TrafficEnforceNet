@extends('layouts.admin')

@section('title', 'Driver History')

@section('content')

<div class="container-fluid px-4 pt-3">

    {{-- ========================================================= --}}
    {{-- PAGE HEADER --}}
    {{-- ========================================================= --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">

        <div>
            <h1 class="h3 mb-1 fw-bold">
                Driver History
            </h1>

            <p class="text-muted mb-0">
                Complete traffic violation history of the selected driver.
            </p>
        </div>

        <div class="mt-3 mt-md-0">

            <a href="{{ route('violations.index') }}"
               class="btn btn-outline-secondary">

                <i class="fa-solid fa-arrow-left me-1"></i>
                Back to Violation Records

            </a>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- DRIVER INFORMATION --}}
    {{-- ========================================================= --}}
    <div class="card border-0 shadow-sm mb-4">

        <div class="card-header bg-white border-bottom py-3">

            <div class="d-flex align-items-center">

                <div class="rounded-circle bg-primary bg-opacity-10
                            d-flex align-items-center justify-content-center me-3"
                     style="width: 45px; height: 45px;">

                    <i class="fa-solid fa-user text-primary"></i>

                </div>

                <div>

                    <h5 class="mb-0 fw-bold">
                        Driver Information
                    </h5>

                    <small class="text-muted">
                        Registered driver details
                    </small>

                </div>

            </div>

        </div>


        <div class="card-body px-4 py-5">

            <div class="row">

                {{-- FIRST NAME --}}
                <div class="col-md-6 col-lg-4 mb-5">

                    <div class="driver-information-item">

                        <div class="text-muted small">
                            First Name
                        </div>

                        <div class="mt-3 fw-semibold fs-6">
                            {{ $driver->first_name ?: 'N/A' }}
                        </div>

                    </div>

                </div>


                {{-- MIDDLE NAME --}}
                <div class="col-md-6 col-lg-4 mb-5">

                    <div class="driver-information-item">

                        <div class="text-muted small">
                            Middle Name
                        </div>

                        <div class="mt-3 fw-semibold fs-6">
                            {{ $driver->middle_name ?: 'N/A' }}
                        </div>

                    </div>

                </div>


                {{-- LAST NAME --}}
                <div class="col-md-6 col-lg-4 mb-5">

                    <div class="driver-information-item">

                        <div class="text-muted small">
                            Last Name
                        </div>

                        <div class="mt-3 fw-semibold fs-6">
                            {{ $driver->last_name ?: 'N/A' }}
                        </div>

                    </div>

                </div>


                {{-- LICENSE NUMBER --}}
                <div class="col-md-6 col-lg-4 mb-2 mb-lg-0">

                    <div class="driver-information-item">

                        <div class="text-muted small">
                            License Number
                        </div>

                        <div class="mt-3 fw-semibold fs-6">
                            {{ $driver->license_number ?: 'N/A' }}
                        </div>

                    </div>

                </div>


                {{-- ADDRESS --}}
                <div class="col-md-12 col-lg-8">

                    <div class="driver-information-item">

                        <div class="text-muted small">
                            Address
                        </div>

                        <div class="mt-3 fw-semibold fs-6">
                            {{ $driver->address ?: 'N/A' }}
                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- SUMMARY CARDS --}}
    {{-- ========================================================= --}}
    <div class="row g-3 mb-4">

        {{-- TOTAL --}}
        <div class="col-12 col-md-4">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex align-items-center">

                        <div class="rounded-circle bg-primary bg-opacity-10
                                    d-flex align-items-center justify-content-center me-3"
                             style="width: 48px; height: 48px;">

                            <i class="fa-solid fa-file-lines text-primary"></i>

                        </div>

                        <div>

                            <div class="text-muted small">
                                Total Violations
                            </div>

                            <div class="fs-4 fw-bold">
                                {{ $totalViolations }}
                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- PENDING --}}
        <div class="col-12 col-md-4">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex align-items-center">

                        <div class="rounded-circle bg-warning bg-opacity-10
                                    d-flex align-items-center justify-content-center me-3"
                             style="width: 48px; height: 48px;">

                            <i class="fa-solid fa-clock text-warning"></i>

                        </div>

                        <div>

                            <div class="text-muted small">
                                Pending Violations
                            </div>

                            <div class="fs-4 fw-bold">
                                {{ $pendingViolations }}
                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- SETTLED --}}
        <div class="col-12 col-md-4">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex align-items-center">

                        <div class="rounded-circle bg-success bg-opacity-10
                                    d-flex align-items-center justify-content-center me-3"
                             style="width: 48px; height: 48px;">

                            <i class="fa-solid fa-circle-check text-success"></i>

                        </div>

                        <div>

                            <div class="text-muted small">
                                Settled Violations
                            </div>

                            <div class="fs-4 fw-bold">
                                {{ $settledViolations }}
                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- REGISTERED VEHICLES --}}
    {{-- ========================================================= --}}
    <div class="card border-0 shadow-sm mb-4">

        <div class="card-header bg-white border-bottom py-3">

            <div class="d-flex align-items-center">

                <div class="rounded-circle bg-info bg-opacity-10
                            d-flex align-items-center justify-content-center me-3"
                     style="width: 45px; height: 45px;">

                    <i class="fa-solid fa-car text-info"></i>

                </div>

                <div>

                    <h5 class="mb-0 fw-bold">
                        Registered Vehicles
                    </h5>

                    <small class="text-muted">
                        Vehicles associated with this driver
                    </small>

                </div>

            </div>

        </div>


        <div class="card-body p-0">

            @if ($driver->vehicles && $driver->vehicles->count())

                <div class="table-responsive">

                    <table class="table table-hover align-middle mb-0">

                        <thead class="table-light">

                            <tr>

                                <th class="px-4">
                                    Plate Number
                                </th>

                                <th>
                                    Vehicle Type
                                </th>

                                <th>
                                    Make / Model
                                </th>

                                <th>
                                    Color
                                </th>

                            </tr>

                        </thead>

                        <tbody>

                            @foreach ($driver->vehicles as $vehicle)

                                <tr>

                                    <td class="px-4 fw-semibold">
                                        {{ $vehicle->plate_number ?: 'N/A' }}
                                    </td>

                                    <td>
                                        {{ $vehicle->vehicle_type ?: 'N/A' }}
                                    </td>

                                    <td>
                                        {{ $vehicle->make_model ?: 'N/A' }}
                                    </td>

                                    <td>
                                        {{ $vehicle->color ?: 'N/A' }}
                                    </td>

                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>

            @else

                <div class="text-center py-5">

                    <i class="fa-solid fa-car text-muted fs-2 mb-3"></i>

                    <p class="text-muted mb-0">
                        No registered vehicles found for this driver.
                    </p>

                </div>

            @endif

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- VIOLATION HISTORY --}}
    {{-- ========================================================= --}}
    <div class="card border-0 shadow-sm mb-4">

        <div class="card-header bg-white border-bottom py-3">

            <div class="d-flex flex-wrap justify-content-between align-items-center">

                <div class="d-flex align-items-center">

                    <div class="rounded-circle bg-danger bg-opacity-10
                                d-flex align-items-center justify-content-center me-3"
                         style="width: 45px; height: 45px;">

                        <i class="fa-solid fa-clock-rotate-left text-danger"></i>

                    </div>

                    <div>

                        <h5 class="mb-0 fw-bold">
                            Violation History
                        </h5>

                        <small class="text-muted">
                            Recorded traffic violations for this driver
                        </small>

                    </div>

                </div>

                <div class="mt-2 mt-md-0">

                    <span class="badge bg-light text-dark border">

                        {{ $totalViolations }}

                        {{ $totalViolations == 1 ? 'Record' : 'Records' }}

                    </span>

                </div>

            </div>

        </div>


        <div class="card-body p-0">

            @if ($violations->count())

                <div class="table-responsive">

                    <table class="table table-hover align-middle mb-0">

                        <thead class="table-light">

                            <tr>

                                <th class="px-4">
                                    Ticket No.
                                </th>

                                <th>
                                    Violation
                                </th>

                                <th>
                                    Plate No.
                                </th>

                                <th>
                                    Location
                                </th>

                                <th>
                                    Date
                                </th>

                                <th>
                                    Time
                                </th>

                                <th>
                                    Status
                                </th>

                                <th class="text-center">
                                    Action
                                </th>

                            </tr>

                        </thead>

                        <tbody>

                            @foreach ($violations as $violation)

                                <tr>

                                    {{-- TICKET NUMBER --}}
                                    <td class="px-4">

                                        <span class="fw-semibold">
                                            {{ $violation->ticket_number ?: 'N/A' }}
                                        </span>

                                    </td>


                                    {{-- VIOLATION --}}
                                    <td>

                                        @php

                                            $violationTypes =
                                                $violation->violationTypes;

                                            if (
                                                !$violationTypes ||
                                                $violationTypes->count() === 0
                                            ) {

                                                $violationTypes = collect();

                                                if ($violation->violationType) {

                                                    $violationTypes->push(
                                                        $violation->violationType
                                                    );

                                                }

                                            }

                                        @endphp


                                        @if ($violationTypes->count())

                                            <div class="d-flex flex-wrap gap-1">

                                                @foreach ($violationTypes as $type)

                                                    <span class="badge bg-light text-dark border">
                                                        {{ $type->name }}
                                                    </span>

                                                @endforeach

                                            </div>

                                        @else

                                            <span class="text-muted">
                                                N/A
                                            </span>

                                        @endif

                                    </td>


                                    {{-- PLATE NUMBER --}}
                                    <td>

                                        @if ($violation->vehicle)

                                            <span class="fw-semibold">
                                                {{ $violation->vehicle->plate_number ?: 'N/A' }}
                                            </span>

                                        @else

                                            <span class="text-muted">
                                                N/A
                                            </span>

                                        @endif

                                    </td>


                                    {{-- LOCATION --}}
                                    <td>

                                        <span class="d-inline-block"
                                              style="max-width: 220px;">

                                            {{ $violation->location ?: 'N/A' }}

                                        </span>

                                    </td>


                                    {{-- DATE --}}
                                    <td>

                                        @if ($violation->violation_date)

                                            {{ \Carbon\Carbon::parse($violation->violation_date)->format('M d, Y') }}

                                        @else

                                            <span class="text-muted">
                                                N/A
                                            </span>

                                        @endif

                                    </td>


                                    {{-- TIME --}}
                                    <td>

                                        @if ($violation->violation_time)

                                            {{ \Carbon\Carbon::parse($violation->violation_time)->format('h:i A') }}

                                        @else

                                            <span class="text-muted">
                                                N/A
                                            </span>

                                        @endif

                                    </td>


                                    {{-- STATUS --}}
                                    <td>

                                        @if (strtolower($violation->status ?? '') === 'settled')

                                            <span class="badge bg-success">
                                                Settled
                                            </span>

                                        @else

                                            <span class="badge bg-warning text-dark">
                                                Pending
                                            </span>

                                        @endif

                                    </td>


                                    {{-- ACTION --}}
                                    <td class="text-center">

                                        <a href="{{ route('violations.show', $violation->id) }}"
                                           class="btn btn-sm btn-primary"
                                           title="View violation">

                                            <i class="fa-solid fa-eye"></i>

                                            <span class="d-none d-lg-inline ms-1">
                                                View
                                            </span>

                                        </a>

                                    </td>

                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>

            @else

                <div class="text-center py-5">

                    <div class="mb-3">

                        <i class="fa-solid fa-file-circle-xmark text-muted fs-1"></i>

                    </div>

                    <h6 class="fw-bold">
                        No Violation History
                    </h6>

                    <p class="text-muted mb-0">
                        This driver has no recorded traffic violations.
                    </p>

                </div>

            @endif

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- BACK BUTTON --}}
    {{-- ========================================================= --}}
    <div class="d-flex justify-content-end mb-4">

        <a href="{{ route('violations.index') }}"
           class="btn btn-outline-secondary">

            <i class="fa-solid fa-arrow-left me-1"></i>
            Back to Violation Records

        </a>

    </div>

</div>

@endsection