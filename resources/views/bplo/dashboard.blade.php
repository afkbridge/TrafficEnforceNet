@extends('layouts.bplo')

@section('title', 'BPLO Dashboard')

@section('content')

<style>

    .bplo-dashboard {
        padding: 24px;
        background: #f5f7fb;
        min-height: calc(100vh - 70px);
    }

    .bplo-page-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 20px;
        margin-bottom: 24px;
    }

    .bplo-page-header h1 {
        margin: 0;
        color: #172033;
        font-size: 28px;
        font-weight: 700;
    }

    .bplo-page-header p {
        margin: 6px 0 0;
        color: #6b7280;
        font-size: 14px;
    }

    .bplo-header-date {
        background: #ffffff;
        border: 1px solid #e5e7eb;
        border-radius: 10px;
        padding: 10px 15px;
        color: #4b5563;
        font-size: 13px;
        white-space: nowrap;
    }

    /* =========================================================
       SUMMARY CARDS
       ========================================================= */

    .summary-grid {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 18px;
        margin-bottom: 24px;
    }

    .summary-card {
        position: relative;
        overflow: hidden;
        background: #ffffff;
        border: 1px solid #e5e7eb;
        border-radius: 14px;
        padding: 20px;
        box-shadow: 0 2px 8px rgba(15, 23, 42, 0.04);
    }

    .summary-card::after {
        content: "";
        position: absolute;
        width: 75px;
        height: 75px;
        right: -25px;
        bottom: -25px;
        border-radius: 50%;
        background: rgba(37, 99, 235, 0.06);
    }

    .summary-top {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
    }

    .summary-label {
        color: #6b7280;
        font-size: 13px;
        font-weight: 500;
    }

    .summary-value {
        margin-top: 8px;
        color: #111827;
        font-size: 29px;
        line-height: 1;
        font-weight: 700;
    }

    .summary-description {
        margin-top: 10px;
        color: #9ca3af;
        font-size: 11px;
    }

    .summary-icon {
        width: 42px;
        height: 42px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 17px;
    }

    .total-icon {
        background: #e8f1ff;
        color: #2563eb;
    }

    .pending-icon {
        background: #fff4df;
        color: #d97706;
    }

    .settled-icon {
        background: #e8f8ef;
        color: #15803d;
    }

    .month-icon {
        background: #f0eafd;
        color: #7c3aed;
    }

    /* =========================================================
       CARDS
       ========================================================= */

    .dashboard-card {
        background: #ffffff;
        border: 1px solid #e5e7eb;
        border-radius: 14px;
        box-shadow: 0 2px 8px rgba(15, 23, 42, 0.04);
        overflow: hidden;
    }

    .card-header {
        padding: 14px 18px;
        border-bottom: 1px solid #e5e7eb;
    }

    .card-title {
        margin: 0;
        color: #172033;
        font-size: 16px;
        font-weight: 700;
    }

    .card-subtitle {
        margin: 4px 0 0;
        color: #9ca3af;
        font-size: 12px;
    }

    .card-body {
        padding: 15px 18px;
    }

    /* =========================================================
       CHARTS
       ========================================================= */

    .charts-grid {
        display: grid;
        grid-template-columns: 1.6fr 1fr;
        gap: 18px;
        margin-bottom: 18px;
    }

    .chart-container {
        position: relative;
        height: 280px;
    }

    .status-chart-container {
        position: relative;
        height: 280px;
        display: flex;
        justify-content: center;
    }

    /* =========================================================
       STATISTICS ROW
       ========================================================= */

    .statistics-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 18px;
        margin-bottom: 18px;
    }

    .type-stat-list {
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    .type-stat-item {
        display: grid;
        grid-template-columns: minmax(0, 1fr) 55px;
        align-items: center;
        gap: 12px;
    }

    .type-stat-info {
        min-width: 0;
    }

    .type-stat-name {
        display: block;
        color: #374151;
        font-size: 12px;
        font-weight: 600;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        margin-bottom: 6px;
    }

    .type-stat-bar {
        height: 7px;
        width: 100%;
        background: #eef2f7;
        border-radius: 20px;
        overflow: hidden;
    }

    .type-stat-fill {
        height: 100%;
        background: #2563eb;
        border-radius: 20px;
    }

    .type-stat-number {
        color: #172033;
        font-size: 13px;
        font-weight: 700;
        text-align: right;
    }

    .today-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 12px;
    }

    .today-stat {
        padding: 15px;
        border: 1px solid #edf0f4;
        border-radius: 10px;
        background: #fafbfc;
    }

    .today-stat-label {
        color: #6b7280;
        font-size: 11px;
    }

    .today-stat-value {
        margin-top: 6px;
        color: #172033;
        font-size: 22px;
        font-weight: 700;
    }

    /* =========================================================
       PENDING TRANSACTIONS
       ========================================================= */

    .pending-card {
        margin-bottom: 18px;
    }

    .pending-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 15px;
    }

    .review-link {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        padding: 8px 12px;
        border-radius: 8px;
        background: #2563eb;
        color: #ffffff;
        text-decoration: none;
        font-size: 12px;
        font-weight: 600;
        transition: 0.2s;
    }

    .review-link:hover {
        background: #1d4ed8;
        color: #ffffff;
    }

    .pending-list {
        display: flex;
        flex-direction: column;
    }

    .pending-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 12px;
        padding: 9px 0;
        border-bottom: 1px solid #eef1f5;
    }

    .pending-item:last-child {
        border-bottom: none;
    }

    .pending-main {
        min-width: 0;
    }

    .pending-ticket {
        color: #2563eb;
        font-size: 11px;
        font-weight: 700;
    }

    .pending-name {
        margin-top: 2px;
        color: #374151;
        font-size: 12px;
        font-weight: 600;
    }

    .pending-meta {
        margin-top: 2px;
        color: #9ca3af;
        font-size: 10px;
    }

    .pending-status {
        flex-shrink: 0;
        padding: 4px 8px;
        border-radius: 20px;
        background: #fff4df;
        color: #b45309;
        font-size: 9px;
        font-weight: 700;
    }

    .empty-small {
        padding: 20px 10px;
        text-align: center;
        color: #9ca3af;
        font-size: 12px;
    }

    .empty-small i {
        display: block;
        margin-bottom: 8px;
        font-size: 24px;
        color: #cbd5e1;
    }

    /* =========================================================
       RECENT RECORDS
       ========================================================= */

    .recent-card {
        margin-bottom: 18px;
    }

    .recent-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 15px;
    }

    .view-all-link {
        color: #2563eb;
        text-decoration: none;
        font-size: 12px;
        font-weight: 600;
    }

    .view-all-link:hover {
        text-decoration: underline;
    }

    .table-container {
        width: 100%;
        overflow-x: auto;
    }

    .bplo-table {
        width: 100%;
        border-collapse: collapse;
        min-width: 760px;
    }

    .bplo-table th {
        background: #f8fafc;
        color: #64748b;
        font-size: 9px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.03em;
        padding: 9px 12px;
        border-bottom: 1px solid #e5e7eb;
        white-space: nowrap;
    }

    .bplo-table td {
        padding: 9px 12px;
        border-bottom: 1px solid #eef1f5;
        color: #374151;
        font-size: 11px;
        vertical-align: middle;
    }

    .bplo-table tbody tr:hover {
        background: #f8fbff;
    }

    .ticket-number {
        color: #2563eb;
        font-weight: 700;
    }

    .driver-name {
        color: #1f2937;
        font-weight: 600;
    }

    .violation-badges {
        display: flex;
        flex-wrap: wrap;
        gap: 3px;
        max-width: 220px;
    }

    .violation-badge {
        display: inline-flex;
        padding: 3px 6px;
        border-radius: 5px;
        background: #eff6ff;
        color: #1d4ed8;
        border: 1px solid #dbeafe;
        font-size: 9px;
        font-weight: 600;
    }

    .date-main {
        color: #374151;
        font-weight: 600;
    }

    .time-text {
        display: block;
        margin-top: 2px;
        color: #9ca3af;
        font-size: 9px;
    }

    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 4px 8px;
        border-radius: 20px;
        font-size: 9px;
        font-weight: 700;
    }

    .status-badge.pending {
        background: #fff4df;
        color: #b45309;
    }

    .status-badge.settled {
        background: #e8f8ef;
        color: #15803d;
    }

    .view-button {
        width: 29px;
        height: 29px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border: 1px solid #dbe2ea;
        border-radius: 7px;
        background: #ffffff;
        color: #2563eb;
        cursor: pointer;
        transition: 0.2s;
    }

    .view-button:hover {
        background: #f8fafc;
        border-color: #bfdbfe;
    }

    /* =========================================================
       EMPTY STATE
       ========================================================= */

    .empty-table {
        padding: 35px 20px !important;
        text-align: center;
        color: #9ca3af;
    }

    .empty-table i {
        display: block;
        margin-bottom: 9px;
        color: #cbd5e1;
        font-size: 28px;
    }

    /* =========================================================
       RESPONSIVE
       ========================================================= */

    @media (max-width: 1100px) {

        .summary-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .charts-grid,
        .statistics-grid {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 700px) {

        .bplo-dashboard {
            padding: 15px;
        }

        .bplo-page-header {
            flex-direction: column;
        }

        .bplo-header-date {
            width: 100%;
        }

        .summary-grid {
            grid-template-columns: 1fr;
        }

        .today-grid {
            grid-template-columns: 1fr;
        }

        .pending-header,
        .recent-header {
            align-items: flex-start;
            flex-direction: column;
        }

        .review-link {
            width: 100%;
            justify-content: center;
        }

        .card-header,
        .card-body {
            padding: 14px;
        }
    }

</style>

<div class="bplo-dashboard">

    {{-- =====================================================
         PAGE HEADER
         ====================================================== --}}

    <div class="bplo-page-header">

        <div>

            <h1>BPLO Dashboard</h1>

            <p>
                Monitor traffic violation records and transaction status.
            </p>

        </div>

    </div>

    {{-- =====================================================
         SUMMARY CARDS
         ====================================================== --}}

    <div class="summary-grid">

        <div class="summary-card">

            <div class="summary-top">

                <div>

                    <div class="summary-label">
                        Total Records
                    </div>

                    <div class="summary-value">
                        {{ number_format($totalViolations) }}
                    </div>

                    <div class="summary-description">
                        All recorded violations
                    </div>

                </div>

                <div class="summary-icon total-icon">
                    <i class="fa-solid fa-file-lines"></i>
                </div>

            </div>

        </div>


        <div class="summary-card">

            <div class="summary-top">

                <div>

                    <div class="summary-label">
                        Pending Transactions
                    </div>

                    <div class="summary-value">
                        {{ number_format($pendingViolations) }}
                    </div>

                    <div class="summary-description">
                        Records awaiting settlement update
                    </div>

                </div>

                <div class="summary-icon pending-icon">
                    <i class="fa-solid fa-clock"></i>
                </div>

            </div>

        </div>


        <div class="summary-card">

            <div class="summary-top">

                <div>

                    <div class="summary-label">
                        Settled Transactions
                    </div>

                    <div class="summary-value">
                        {{ number_format($settledViolations) }}
                    </div>

                    <div class="summary-description">
                        Records marked as settled
                    </div>

                </div>

                <div class="summary-icon settled-icon">
                    <i class="fa-solid fa-circle-check"></i>
                </div>

            </div>

        </div>


        <div class="summary-card">

            <div class="summary-top">

                <div>

                    <div class="summary-label">
                        Records This Month
                    </div>

                    <div class="summary-value">
                        {{ number_format($thisMonthViolations) }}
                    </div>

                    <div class="summary-description">
                        {{ now()->format('F Y') }}
                    </div>

                </div>

                <div class="summary-icon month-icon">
                    <i class="fa-solid fa-calendar-days"></i>
                </div>

            </div>

        </div>

    </div>

    {{-- =====================================================
         CHARTS
         ====================================================== --}}

    <div class="charts-grid">

        {{-- Monthly Records --}}

        <div class="dashboard-card">

            <div class="card-header">

                <h2 class="card-title">
                    Monthly Violation Records
                </h2>

                <p class="card-subtitle">
                    Number of violation records recorded each month
                    for {{ now()->year }}.
                </p>

            </div>

            <div class="card-body">

                <div class="chart-container">
                    <canvas id="monthlyViolationsChart"></canvas>
                </div>

            </div>

        </div>


        {{-- Pending vs Settled --}}

        <div class="dashboard-card">

            <div class="card-header">

                <h2 class="card-title">
                    Transaction Status
                </h2>

                <p class="card-subtitle">
                    Current status of recorded violation transactions.
                </p>

            </div>

            <div class="card-body">

                <div class="status-chart-container">
                    <canvas id="statusChart"></canvas>
                </div>

            </div>

        </div>

    </div>

    {{-- =====================================================
         VIOLATION TYPE + TODAY'S ACTIVITY
         ====================================================== --}}

    <div class="statistics-grid">

        {{-- Violation Type Statistics --}}

        <div class="dashboard-card">

            <div class="card-header">

                <h2 class="card-title">
                    Violation Type Statistics
                </h2>

                <p class="card-subtitle">
                    Recorded violation types, including multiple violations
                    attached to one ticket.
                </p>

            </div>

            <div class="card-body">

                @if ($violationTypeStatistics->isNotEmpty())

                    @php
                        $maxViolationType = $violationTypeStatistics->max('total');
                    @endphp

                    <div class="type-stat-list">

                        @foreach ($violationTypeStatistics->take(8) as $type)

                            @php
                                $percentage = $maxViolationType > 0
                                    ? ($type->total / $maxViolationType) * 100
                                    : 0;
                            @endphp

                            <div class="type-stat-item">

                                <div class="type-stat-info">

                                    <span class="type-stat-name">
                                        {{ $type->name }}
                                    </span>

                                    <div class="type-stat-bar">

                                        <div
                                            class="type-stat-fill"
                                            style="width: {{ $percentage }}%;"
                                        ></div>

                                    </div>

                                </div>

                                <div class="type-stat-number">
                                    {{ number_format($type->total) }}
                                </div>

                            </div>

                        @endforeach

                    </div>

                @else

                    <div class="empty-small">

                        <i class="fa-regular fa-chart-bar"></i>

                        No violation type statistics available.

                    </div>

                @endif

            </div>

        </div>


        {{-- Today's Activity --}}

        <div class="dashboard-card">

            <div class="card-header">

                <h2 class="card-title">
                    Today's Activity
                </h2>

                <p class="card-subtitle">
                    Traffic violation records recorded today.
                </p>

            </div>

            <div class="card-body">

                <div class="today-grid">

                    <div class="today-stat">

                        <div class="today-stat-label">
                            Today's Records
                        </div>

                        <div class="today-stat-value">
                            {{ number_format($todayViolations) }}
                        </div>

                    </div>


                    <div class="today-stat">

                        <div class="today-stat-label">
                            Pending
                        </div>

                        <div class="today-stat-value">
                            {{ number_format($todayPending) }}
                        </div>

                    </div>


                    <div class="today-stat">

                        <div class="today-stat-label">
                            Settled
                        </div>

                        <div class="today-stat-value">
                            {{ number_format($todaySettled) }}
                        </div>

                    </div>

                </div>


                <div style="margin-top: 18px; padding-top: 15px; border-top: 1px solid #eef1f5;">

                    <div style="color:#6b7280; font-size:11px; margin-bottom:7px;">
                        Monthly Transaction Overview
                    </div>

                    <div style="display:flex; justify-content:space-between; gap:15px;">

                        <div>

                            <div style="font-size:10px; color:#9ca3af;">
                                Pending This Month
                            </div>

                            <div style="margin-top:4px; font-size:19px; font-weight:700; color:#d97706;">
                                {{ number_format($thisMonthPending) }}
                            </div>

                        </div>


                        <div style="text-align:right;">

                            <div style="font-size:10px; color:#9ca3af;">
                                Settled This Month
                            </div>

                            <div style="margin-top:4px; font-size:19px; font-weight:700; color:#15803d;">
                                {{ number_format($thisMonthSettled) }}
                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- =====================================================
         PENDING TRANSACTIONS
         ====================================================== --}}

    <div class="dashboard-card pending-card">

        <div class="card-header">

            <div class="pending-header">

                <div>

                    <h2 class="card-title">
                        Pending Transactions
                    </h2>

                    <p class="card-subtitle">
                        Records that may require transaction status updating.
                    </p>

                </div>

                <a
                    href="{{ route('bplo.violations.index', ['status' => 'Pending']) }}"
                    class="review-link"
                >
                    <i class="fa-solid fa-list-check"></i>
                    Review Pending Records
                </a>

            </div>

        </div>


        <div class="card-body">

            @if ($pendingRecords->isNotEmpty())

                <div class="pending-list">

                    {{-- Show only the latest 3 pending records on dashboard --}}

                    @foreach ($pendingRecords->take(3) as $violation)

                        @php

                            $driverName = trim(
                                ($violation->driver->first_name ?? '') .
                                ' ' .
                                ($violation->driver->middle_name ?? '') .
                                ' ' .
                                ($violation->driver->last_name ?? '')
                            );

                            $driverName = $driverName ?: 'N/A';

                            $violationNames = $violation->violationTypes
                                ->pluck('name')
                                ->filter()
                                ->values();

                            $violationName = $violationNames->isNotEmpty()
                                ? $violationNames->implode(', ')
                                : ($violation->violationType?->name ?? 'N/A');

                        @endphp

                        <div class="pending-item">

                            <div class="pending-main">

                                <div class="pending-ticket">
                                    #{{ $violation->ticket_number }}
                                </div>

                                <div class="pending-name">
                                    {{ $driverName }}
                                </div>

                                <div class="pending-meta">

                                    {{ $violationName }}

                                    @if ($violation->vehicle?->plate_number)

                                        · {{ $violation->vehicle->plate_number }}

                                    @endif

                                </div>

                            </div>

                            <div class="pending-status">
                                Pending
                            </div>

                        </div>

                    @endforeach

                </div>

            @else

                <div class="empty-small">

                    <i class="fa-solid fa-circle-check"></i>

                    No pending transactions at this time.

                </div>

            @endif

        </div>

    </div>


    {{-- =====================================================
         RECENT RECORDS
         ====================================================== --}}

    <div class="dashboard-card recent-card">

        <div class="card-header">

            <div class="recent-header">

                <div>

                    <h2 class="card-title">
                        Recent Violation Records
                    </h2>

                    <p class="card-subtitle">
                        Latest traffic violation records received by BPLO.
                    </p>

                </div>

                <a
                    href="{{ route('bplo.violations.index') }}"
                    class="view-all-link"
                >
                    View All Records
                    <i class="fa-solid fa-arrow-right ms-1"></i>
                </a>

            </div>

        </div>


        <div class="table-container">

            <table class="bplo-table">

                <thead>

                    <tr>

                        <th>Ticket</th>

                        <th>Violator</th>

                        <th>Violation</th>

                        <th>Date & Time</th>

                        <th>Status</th>

                        <th>Action</th>

                    </tr>

                </thead>


                <tbody>

                    {{-- Show only the latest 5 records on dashboard --}}

                    @forelse ($recentViolations->take(5) as $violation)

                        @php

                            $driverName = trim(
                                ($violation->driver->first_name ?? '') .
                                ' ' .
                                ($violation->driver->middle_name ?? '') .
                                ' ' .
                                ($violation->driver->last_name ?? '')
                            );

                            $driverName = $driverName ?: 'N/A';

                            $violationNames = $violation->violationTypes
                                ->pluck('name')
                                ->filter()
                                ->values();

                            $violationName = $violationNames->isNotEmpty()
                                ? $violationNames->implode(', ')
                                : ($violation->violationType?->name ?? 'N/A');

                            $officerName = $violation->user->name ?? 'N/A';

                            $formattedDate = $violation->violation_date
                                ? \Carbon\Carbon::parse(
                                    $violation->violation_date
                                )->format('M d, Y')
                                : 'N/A';

                            $formattedTime = $violation->violation_time
                                ? \Carbon\Carbon::parse(
                                    $violation->violation_time
                                )->format('h:i A')
                                : 'N/A';

                            $vehicleInfo = trim(
                                ($violation->vehicle->plate_number ?? 'N/A') .
                                ' ' .
                                ($violation->vehicle->vehicle_type ?? '')
                            );

                            $location = $violation->location ?? 'N/A';

                            $remarks = $violation->remarks ?? 'N/A';

                        @endphp


                        <tr>

                            <td>

                                <span class="ticket-number">
                                    #{{ $violation->ticket_number }}
                                </span>

                            </td>


                            <td>

                                <span class="driver-name">
                                    {{ $driverName }}
                                </span>

                            </td>


                            <td>

                                <div class="violation-badges">

                                    @foreach ($violationNames as $name)

                                        <span class="violation-badge">
                                            {{ $name }}
                                        </span>

                                    @endforeach

                                    @if ($violationNames->isEmpty())

                                        <span class="violation-badge">
                                            {{ $violationName }}
                                        </span>

                                    @endif

                                </div>

                            </td>


                            <td>

                                <span class="date-main">
                                    {{ $formattedDate }}
                                </span>

                                <span class="time-text">
                                    {{ $formattedTime }}
                                </span>

                            </td>


                            <td>

                                @if ($violation->status === 'Settled')

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


                            <td>

                                <button
                                    type="button"
                                    class="view-button violation-view-trigger"
                                    title="View Details"
                                    data-ticket="{{ $violation->ticket_number }}"
                                    data-name="{{ $driverName }}"
                                    data-vehicle="{{ $vehicleInfo }}"
                                    data-violation="{{ $violationName }}"
                                    data-officer="{{ $officerName }}"
                                    data-date="{{ $formattedDate }}"
                                    data-time="{{ $formattedTime }}"
                                    data-location="{{ $location }}"
                                    data-remarks="{{ $remarks }}"
                                    data-status="{{ $violation->status }}"
                                >

                                    <i class="fa-solid fa-eye"></i>

                                </button>

                            </td>

                        </tr>


                    @empty

                        <tr>

                            <td
                                colspan="6"
                                class="empty-table"
                            >

                                <i class="fa-regular fa-folder-open"></i>

                                No violation records available.

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>


{{-- =========================================================
     SHARED VIOLATION MODAL
     ========================================================== --}}

@include('partials.bplo-violation-modal')


{{-- =========================================================
     CHART.JS
     ========================================================== --}}

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>

    document.addEventListener('DOMContentLoaded', function () {

        /* ==========================================================
           MONTHLY VIOLATION CHART
           ========================================================== */

        const monthlyCanvas =
            document.getElementById('monthlyViolationsChart');

        if (monthlyCanvas) {

            new Chart(monthlyCanvas, {

                type: 'line',

                data: {

                    labels: [
                        'Jan',
                        'Feb',
                        'Mar',
                        'Apr',
                        'May',
                        'Jun',
                        'Jul',
                        'Aug',
                        'Sep',
                        'Oct',
                        'Nov',
                        'Dec'
                    ],

                    datasets: [{

                        label: 'Violation Records',

                        data: @json($monthlyViolations),

                        borderColor: '#2563eb',

                        backgroundColor:
                            'rgba(37, 99, 235, 0.08)',

                        borderWidth: 2,

                        fill: true,

                        tension: 0.35,

                        pointRadius: 3,

                        pointHoverRadius: 5

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

                            grid: {
                                color: '#eef1f5'
                            }

                        },

                        x: {

                            grid: {
                                display: false
                            }

                        }

                    }

                }

            });

        }


        /* ==========================================================
           STATUS CHART
           ========================================================== */

        const statusCanvas =
            document.getElementById('statusChart');

        if (statusCanvas) {

            new Chart(statusCanvas, {

                type: 'doughnut',

                data: {

                    labels: [
                        'Pending',
                        'Settled'
                    ],

                    datasets: [{

                        data: [

                            {{ $statusStatistics['Pending'] }},

                            {{ $statusStatistics['Settled'] }}

                        ],

                        backgroundColor: [

                            '#f59e0b',

                            '#22c55e'

                        ],

                        borderWidth: 0

                    }]

                },

                options: {

                    responsive: true,

                    maintainAspectRatio: false,

                    cutout: '68%',

                    plugins: {

                        legend: {

                            position: 'bottom',

                            labels: {

                                usePointStyle: true,

                                padding: 18,

                                font: {
                                    size: 11
                                }

                            }

                        }

                    }

                }

            });

        }


        /* ==========================================================
           VIEW MODAL
           ========================================================== */

        const modal =
            document.getElementById('violationDetailsModal');

        const modalClose =
            document.getElementById('violationModalClose');

        const modalX =
            document.getElementById('violationModalX');


        function setDetail(id, value) {

            const element =
                document.getElementById(id);

            if (element) {

                element.textContent =
                    value || 'N/A';

            }

        }


        document
            .querySelectorAll('.violation-view-trigger')
            .forEach(function (button) {

                button.addEventListener('click', function () {

                    setDetail(
                        'detailTicket',
                        button.dataset.ticket
                    );

                    setDetail(
                        'detailName',
                        button.dataset.name
                    );

                    setDetail(
                        'detailVehicle',
                        button.dataset.vehicle
                    );

                    setDetail(
                        'detailViolation',
                        button.dataset.violation
                    );

                    setDetail(
                        'detailOfficer',
                        button.dataset.officer
                    );

                    setDetail(
                        'detailDate',
                        button.dataset.date
                    );

                    setDetail(
                        'detailTime',
                        button.dataset.time
                    );

                    setDetail(
                        'detailLocation',
                        button.dataset.location
                    );

                    setDetail(
                        'detailRemarks',
                        button.dataset.remarks
                    );

                    setDetail(
                        'detailStatus',
                        button.dataset.status
                    );


                    if (modal) {

                        modal.classList.add('show');

                    }

                });

            });


        function closeModal() {

            if (modal) {

                modal.classList.remove('show');

            }

        }


        if (modalClose) {

            modalClose.addEventListener(
                'click',
                closeModal
            );

        }


        if (modalX) {

            modalX.addEventListener(
                'click',
                closeModal
            );

        }


        if (modal) {

            modal.addEventListener(
                'click',
                function (event) {

                    if (event.target === modal) {

                        closeModal();

                    }

                }
            );

        }


        document.addEventListener(
            'keydown',
            function (event) {

                if (event.key === 'Escape') {

                    closeModal();

                }

            }
        );

    });

</script>

@endsection