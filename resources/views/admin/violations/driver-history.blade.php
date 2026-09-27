@extends('layouts.admin')

@section('title', 'Driver History')

@section('content')

<style>

    /* =========================================================
       DRIVER HISTORY - PAGE DESIGN
    ========================================================= */

    .driver-history-page {
        padding: 24px 28px 35px;
        background: #f6f8fb;
        min-height: calc(100vh - 70px);
    }

    /* =========================================================
       PAGE HEADER
    ========================================================= */

    .driver-page-header {
        margin-bottom: 24px;
    }

    .driver-page-header h1 {
        font-size: 26px;
        letter-spacing: -0.3px;
        color: #172033;
    }

    .driver-page-header p {
        font-size: 13px;
    }

    .driver-back-btn {
        border-radius: 10px;
        padding: 9px 15px;
        font-size: 13px;
        font-weight: 600;
        transition: all 0.2s ease;
    }

    .driver-back-btn:hover {
        transform: translateY(-1px);
    }

    /* =========================================================
       MAIN CARDS
    ========================================================= */

    .history-card {
        border: 0 !important;
        border-radius: 18px !important;
        overflow: hidden;
        box-shadow: 0 4px 18px rgba(15, 23, 42, 0.055) !important;
        background: #ffffff;
    }

    .history-card-header {
        background: #ffffff;
        border-bottom: 1px solid #edf0f4 !important;
        padding: 17px 22px !important;
    }

    .section-icon {
        width: 43px;
        height: 43px;
        min-width: 43px;
        border-radius: 13px !important;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 16px;
    }

    .section-title {
        font-size: 15px;
        color: #172033;
        margin-bottom: 2px;
    }

    .section-subtitle {
        font-size: 11px;
        color: #8a94a6;
    }

    /* =========================================================
       DRIVER INFORMATION
    ========================================================= */

    .driver-info-body {
        padding: 27px 28px 25px !important;
    }

    .driver-information-item {
        min-height: 65px;
        padding: 0 5px;
    }

    .driver-information-item .label {
        font-size: 11px;
        font-weight: 600;
        color: #8b95a7;
        text-transform: uppercase;
        letter-spacing: 0.35px;
    }

    .driver-information-item .value {
        margin-top: 8px;
        font-size: 14px;
        font-weight: 600;
        color: #273142;
        line-height: 1.5;
        word-break: break-word;
    }

    /* =========================================================
       SUMMARY CARDS
    ========================================================= */

    .summary-wrapper {
        display: flex;
        justify-content: center;
        width: 100%;
        margin-bottom: 24px;
    }

    .summary-grid {
        width: 100%;
        max-width: 930px;
    }

    .summary-card {
        position: relative;
        border: 0 !important;
        border-radius: 20px !important;
        min-height: 125px;
        background: #ffffff;
        box-shadow: 0 5px 20px rgba(15, 23, 42, 0.065) !important;
        overflow: hidden;
        transition: all 0.22s ease;
    }

    .summary-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 28px rgba(15, 23, 42, 0.09) !important;
    }

    .summary-card::after {
        content: "";
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 4px;
        background: #0d6efd;
    }

    .summary-card.pending::after {
        background: #f0ad00;
    }

    .summary-card.settled::after {
        background: #20a464;
    }

    .summary-card-body {
        height: 100%;
        min-height: 125px;
        padding: 24px 26px !important;
        display: flex;
        align-items: center;
    }

    .summary-icon {
        width: 53px;
        height: 53px;
        min-width: 53px;
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 19px;
        margin-right: 16px;
    }

    .summary-icon.total {
        background: #eaf2ff;
        color: #0d6efd;
    }

    .summary-icon.pending {
        background: #fff5d9;
        color: #e0a000;
    }

    .summary-icon.settled {
        background: #e6f7ef;
        color: #198754;
    }

    .summary-label {
        color: #8b95a7;
        font-size: 11px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.35px;
        margin-bottom: 4px;
    }

    .summary-number {
        color: #172033;
        font-size: 26px;
        line-height: 1.1;
        font-weight: 700;
    }

    /* =========================================================
       TABLES
    ========================================================= */

    .history-table {
        margin-bottom: 0 !important;
    }

    .history-table thead th {
        background: #f8f9fb !important;
        border-bottom: 1px solid #e9edf2;
        color: #687386;
        font-size: 10px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.35px;
        padding: 14px 16px;
        white-space: nowrap;
    }

    .history-table tbody td {
        padding: 15px 16px;
        border-color: #f0f2f5;
        color: #3c4656;
        font-size: 12px;
        vertical-align: middle;
    }

    .history-table tbody tr {
        transition: background 0.15s ease;
    }

    .history-table tbody tr:hover {
        background: #fafbfd;
    }

    .history-table tbody tr:last-child td {
        border-bottom: 0;
    }

    .ticket-number {
        font-weight: 700;
        color: #263246;
    }

    .plate-number {
        font-weight: 700;
        color: #263246;
    }

    .location-text {
        display: inline-block;
        max-width: 220px;
        line-height: 1.4;
    }

    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        border-radius: 999px;
        padding: 5px 10px;
        font-size: 10px;
        font-weight: 700;
    }

    .status-badge.pending {
        background: #fff4d6;
        color: #9a6a00;
    }

    .status-badge.settled {
        background: #e6f7ef;
        color: #197347;
    }

    .view-btn {
        border-radius: 8px;
        padding: 6px 10px;
        font-size: 11px;
        font-weight: 600;
    }

    /* =========================================================
       EMPTY STATES
    ========================================================= */

    .empty-state {
        padding: 55px 20px;
        text-align: center;
    }

    .empty-state-icon {
        width: 58px;
        height: 58px;
        border-radius: 16px;
        background: #f1f3f6;
        color: #a0a8b5;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 23px;
        margin-bottom: 15px;
    }

    .empty-state h6 {
        color: #394355;
        font-size: 14px;
    }

    .empty-state p {
        color: #8a94a6;
        font-size: 12px;
    }

    /* =========================================================
       RECORD COUNT
    ========================================================= */

    .record-count {
        background: #f5f6f8 !important;
        border: 1px solid #e8ebef !important;
        color: #5f6978 !important;
        border-radius: 999px !important;
        padding: 6px 11px !important;
        font-size: 10px;
        font-weight: 700;
    }

    /* =========================================================
       BOTTOM BACK BUTTON
    ========================================================= */

    .bottom-back {
        margin-top: 20px;
        padding-bottom: 5px;
    }

    /* =========================================================
       RESPONSIVE
    ========================================================= */

    @media (max-width: 991.98px) {

        .driver-history-page {
            padding: 20px 18px 30px;
        }

        .summary-grid {
            max-width: 720px;
        }

    }

    @media (max-width: 767.98px) {

        .driver-history-page {
            padding: 18px 14px 25px;
        }

        .driver-page-header h1 {
            font-size: 22px;
        }

        .driver-info-body {
            padding: 22px 20px !important;
        }

        .summary-grid {
            max-width: 430px;
        }

        .summary-card {
            min-height: 112px;
        }

        .summary-card-body {
            min-height: 112px;
            padding: 20px 22px !important;
        }

        .summary-number {
            font-size: 23px;
        }

        .history-card-header {
            padding: 15px 17px !important;
        }

        .history-table thead th,
        .history-table tbody td {
            padding-left: 12px;
            padding-right: 12px;
        }

    }

</style>


<div class="driver-history-page">

    {{-- =========================================================
         PAGE HEADER
    ========================================================= --}}

    <div class="driver-page-header d-flex flex-wrap justify-content-between align-items-center">

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
               class="btn btn-outline-secondary driver-back-btn">

                <i class="fa-solid fa-arrow-left me-1"></i>
                Back to Violation Records

            </a>

        </div>

    </div>


    {{-- =========================================================
         DRIVER INFORMATION
    ========================================================= --}}

    <div class="card history-card mb-4">

        <div class="card-header history-card-header">

            <div class="d-flex align-items-center">

                <div class="section-icon bg-primary bg-opacity-10 text-primary me-3">
                    <i class="fa-solid fa-user"></i>
                </div>

                <div>

                    <h5 class="section-title fw-bold">
                        Driver Information
                    </h5>

                    <div class="section-subtitle">
                        Registered driver details
                    </div>

                </div>

            </div>

        </div>


        <div class="card-body driver-info-body">

            <div class="row g-4">

                {{-- FIRST NAME --}}
                <div class="col-md-6 col-lg-4">

                    <div class="driver-information-item">

                        <div class="label">
                            First Name
                        </div>

                        <div class="value">
                            {{ $driver->first_name ?: 'N/A' }}
                        </div>

                    </div>

                </div>


                {{-- MIDDLE NAME --}}
                <div class="col-md-6 col-lg-4">

                    <div class="driver-information-item">

                        <div class="label">
                            Middle Name
                        </div>

                        <div class="value">
                            {{ $driver->middle_name ?: 'N/A' }}
                        </div>

                    </div>

                </div>


                {{-- LAST NAME --}}
                <div class="col-md-6 col-lg-4">

                    <div class="driver-information-item">

                        <div class="label">
                            Last Name
                        </div>

                        <div class="value">
                            {{ $driver->last_name ?: 'N/A' }}
                        </div>

                    </div>

                </div>


                {{-- LICENSE NUMBER --}}
                <div class="col-md-6 col-lg-4">

                    <div class="driver-information-item">

                        <div class="label">
                            License Number
                        </div>

                        <div class="value">
                            {{ $driver->license_number ?: 'N/A' }}
                        </div>

                    </div>

                </div>


                {{-- ADDRESS --}}
                <div class="col-md-12 col-lg-8">

                    <div class="driver-information-item">

                        <div class="label">
                            Address
                        </div>

                        <div class="value">
                            {{ $driver->address ?: 'N/A' }}
                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
         SUMMARY CARDS
    ========================================================= --}}

    <div class="summary-wrapper">

        <div class="row g-3 summary-grid">

            {{-- TOTAL --}}
            <div class="col-12 col-md-4">

                <div class="card summary-card h-100">

                    <div class="card-body summary-card-body">

                        <div class="summary-icon total">
                            <i class="fa-solid fa-file-lines"></i>
                        </div>

                        <div>

                            <div class="summary-label">
                                Total Violations
                            </div>

                            <div class="summary-number">
                                {{ $totalViolations }}
                            </div>

                        </div>

                    </div>

                </div>

            </div>


            {{-- PENDING --}}
            <div class="col-12 col-md-4">

                <div class="card summary-card pending h-100">

                    <div class="card-body summary-card-body">

                        <div class="summary-icon pending">
                            <i class="fa-solid fa-clock"></i>
                        </div>

                        <div>

                            <div class="summary-label">
                                Pending Violations
                            </div>

                            <div class="summary-number">
                                {{ $pendingViolations }}
                            </div>

                        </div>

                    </div>

                </div>

            </div>


            {{-- SETTLED --}}
            <div class="col-12 col-md-4">

                <div class="card summary-card settled h-100">

                    <div class="card-body summary-card-body">

                        <div class="summary-icon settled">
                            <i class="fa-solid fa-circle-check"></i>
                        </div>

                        <div>

                            <div class="summary-label">
                                Settled Violations
                            </div>

                            <div class="summary-number">
                                {{ $settledViolations }}
                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
         REGISTERED VEHICLES
    ========================================================= --}}

    <div class="card history-card mb-4">

        <div class="card-header history-card-header">

            <div class="d-flex align-items-center">

                <div class="section-icon bg-info bg-opacity-10 text-info me-3">
                    <i class="fa-solid fa-car"></i>
                </div>

                <div>

                    <h5 class="section-title fw-bold">
                        Registered Vehicles
                    </h5>

                    <div class="section-subtitle">
                        Vehicles associated with this driver
                    </div>

                </div>

            </div>

        </div>


        <div class="card-body p-0">

            @if ($driver->vehicles && $driver->vehicles->count())

                <div class="table-responsive">

                    <table class="table table-hover align-middle history-table">

                        <thead>

                            <tr>

                                <th class="px-4">
                                    Plate Number
                                </th>

                                <th>
                                    Vehicle Type
                                </th>

                            </tr>

                        </thead>


                        <tbody>

                            @foreach ($driver->vehicles as $vehicle)

                                <tr>

                                    {{-- PLATE NUMBER --}}
                                    <td class="px-4">

                                        <span class="plate-number">
                                            {{ $vehicle->plate_number ?: 'N/A' }}
                                        </span>

                                    </td>


                                    {{-- VEHICLE TYPE --}}
                                    <td>
                                        {{ $vehicle->vehicle_type ?: 'N/A' }}
                                    </td>

                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>

            @else

                <div class="empty-state">

                    <div class="empty-state-icon">
                        <i class="fa-solid fa-car"></i>
                    </div>

                    <h6 class="fw-bold mb-1">
                        No Registered Vehicles
                    </h6>

                    <p class="mb-0">
                        No registered vehicles were found for this driver.
                    </p>

                </div>

            @endif

        </div>

    </div>


    {{-- =========================================================
         VIOLATION HISTORY
    ========================================================= --}}

    <div class="card history-card mb-4">

        <div class="card-header history-card-header">

            <div class="d-flex flex-wrap justify-content-between align-items-center">

                <div class="d-flex align-items-center">

                    <div class="section-icon bg-danger bg-opacity-10 text-danger me-3">
                        <i class="fa-solid fa-clock-rotate-left"></i>
                    </div>

                    <div>

                        <h5 class="section-title fw-bold">
                            Violation History
                        </h5>

                        <div class="section-subtitle">
                            Recorded traffic violations for this driver
                        </div>

                    </div>

                </div>


                <div class="mt-2 mt-md-0">

                    <span class="badge record-count">

                        {{ $totalViolations }}

                        {{ $totalViolations == 1 ? 'Record' : 'Records' }}

                    </span>

                </div>

            </div>

        </div>


        <div class="card-body p-0">

            @if ($violations->count())

                <div class="table-responsive">

                    <table class="table table-hover align-middle history-table">

                        <thead>

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

                                        <span class="ticket-number">
                                            {{ $violation->ticket_number ?: 'N/A' }}
                                        </span>

                                    </td>


                                    {{-- VIOLATION --}}
                                    <td>

                                        @php

                                            $displayedViolations = [];

                                            /*
                                             * PRIMARY OFFICIAL VIOLATION
                                             */
                                            if ($violation->violationType) {

                                                $name = trim(
                                                    (string) $violation->violationType->name
                                                );

                                                if (
                                                    $name !== '' &&
                                                    !in_array(
                                                        $name,
                                                        $displayedViolations,
                                                        true
                                                    )
                                                ) {
                                                    $displayedViolations[] = $name;
                                                }
                                            }


                                            /*
                                             * PRIMARY CUSTOM "OTHER" VIOLATION
                                             */
                                            if (!empty($violation->other_violation)) {

                                                $name = trim(
                                                    (string) $violation->other_violation
                                                );

                                                if (
                                                    $name !== '' &&
                                                    !in_array(
                                                        $name,
                                                        $displayedViolations,
                                                        true
                                                    )
                                                ) {
                                                    $displayedViolations[] = $name;
                                                }
                                            }


                                            /*
                                             * ADDITIONAL OFFICIAL VIOLATIONS
                                             */
                                            if (
                                                $violation->violationTypes &&
                                                $violation->violationTypes->count()
                                            ) {

                                                foreach (
                                                    $violation->violationTypes as $type
                                                ) {

                                                    if (empty($type->name)) {
                                                        continue;
                                                    }

                                                    $name = trim(
                                                        (string) $type->name
                                                    );

                                                    if (
                                                        $name !== '' &&
                                                        !in_array(
                                                            $name,
                                                            $displayedViolations,
                                                            true
                                                        )
                                                    ) {
                                                        $displayedViolations[] = $name;
                                                    }

                                                }

                                            }


                                            /*
                                             * ADDITIONAL CUSTOM "OTHER" VIOLATIONS
                                             */
                                            if (
                                                $violation->violationOtherTypes &&
                                                $violation->violationOtherTypes->count()
                                            ) {

                                                foreach (
                                                    $violation->violationOtherTypes as $otherType
                                                ) {

                                                    if (empty($otherType->name)) {
                                                        continue;
                                                    }

                                                    $name = trim(
                                                        (string) $otherType->name
                                                    );

                                                    if (
                                                        $name !== '' &&
                                                        !in_array(
                                                            $name,
                                                            $displayedViolations,
                                                            true
                                                        )
                                                    ) {
                                                        $displayedViolations[] = $name;
                                                    }

                                                }

                                            }

                                        @endphp


                                        @if (count($displayedViolations))

                                            <div class="d-flex flex-column gap-1">

                                                @foreach (
                                                    $displayedViolations
                                                    as $index => $violationName
                                                )

                                                    <div>

                                                        @if (count($displayedViolations) > 1)

                                                            <span class="text-muted me-1">
                                                                {{ $index + 1 }}.
                                                            </span>

                                                        @endif

                                                        {{ $violationName }}

                                                    </div>

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

                                            <span class="plate-number">
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

                                        <span class="location-text">
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

                                            <span class="status-badge settled">

                                                <i class="fa-solid fa-circle-check"></i>

                                                Settled

                                            </span>

                                        @else

                                            <span class="status-badge pending">

                                                <i class="fa-solid fa-clock"></i>

                                                Pending

                                            </span>

                                        @endif

                                    </td>


                                    {{-- ACTION --}}
                                    <td class="text-center">

                                        <a href="{{ route('violations.show', $violation->id) }}"
                                           class="btn btn-sm btn-primary view-btn"
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

                <div class="empty-state">

                    <div class="empty-state-icon">
                        <i class="fa-solid fa-file-circle-xmark"></i>
                    </div>

                    <h6 class="fw-bold mb-1">
                        No Violation History
                    </h6>

                    <p class="mb-0">
                        This driver has no recorded traffic violations.
                    </p>

                </div>

            @endif

        </div>

    </div>


    {{-- =========================================================
         BACK BUTTON
    ========================================================= --}}

    <div class="d-flex justify-content-end bottom-back">

        <a href="{{ route('violations.index') }}"
           class="btn btn-outline-secondary driver-back-btn">

            <i class="fa-solid fa-arrow-left me-1"></i>

            Back to Violation Records

        </a>

    </div>

</div>

@endsection