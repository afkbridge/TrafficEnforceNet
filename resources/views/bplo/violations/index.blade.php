@extends('layouts.bplo')

@section('title', 'Violation Review')

@section('content')

<style>
    .bplo-review {
        padding: 24px;
        background: #f5f7fb;
        min-height: calc(100vh - 70px);
    }

    .review-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 20px;
        margin-bottom: 24px;
    }

    .review-header h1 {
        margin: 0;
        color: #172033;
        font-size: 28px;
        font-weight: 700;
    }

    .review-header p {
        margin: 6px 0 0;
        color: #6b7280;
        font-size: 14px;
    }

    .review-header-badge {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        padding: 9px 13px;
        border-radius: 9px;
        background: #eff6ff;
        border: 1px solid #dbeafe;
        color: #2563eb;
        font-size: 12px;
        font-weight: 600;
        white-space: nowrap;
    }

    .review-card {
        background: #ffffff;
        border: 1px solid #e5e7eb;
        border-radius: 14px;
        box-shadow: 0 2px 8px rgba(15, 23, 42, 0.04);
        overflow: hidden;
    }

    .review-card-header {
        padding: 20px 22px;
        border-bottom: 1px solid #e5e7eb;
    }

    .review-title-row {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 20px;
        margin-bottom: 18px;
    }

    .review-title {
        margin: 0;
        color: #172033;
        font-size: 19px;
        font-weight: 700;
    }

    .review-subtitle {
        margin: 4px 0 0;
        color: #6b7280;
        font-size: 13px;
    }

    .review-count {
        color: #64748b;
        font-size: 12px;
        white-space: nowrap;
    }

    .review-filter-form {
        display: grid;
        grid-template-columns: minmax(0, 1fr) 170px auto auto;
        gap: 10px;
        align-items: center;
    }

    .review-search-wrapper {
        position: relative;
    }

    .review-search-wrapper i {
        position: absolute;
        left: 13px;
        top: 50%;
        transform: translateY(-50%);
        color: #9ca3af;
        pointer-events: none;
    }

    .review-search {
        width: 100%;
        height: 42px;
        border: 1px solid #d9dee7;
        border-radius: 9px;
        padding: 0 14px 0 38px;
        outline: none;
        color: #1f2937;
        background: #ffffff;
        font-size: 13px;
    }

    .review-search:focus {
        border-color: #2563eb;
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.08);
    }

    .review-status {
        height: 42px;
        border: 1px solid #d9dee7;
        border-radius: 9px;
        padding: 0 12px;
        background: #ffffff;
        color: #374151;
        font-size: 13px;
        outline: none;
    }

    .review-status:focus {
        border-color: #2563eb;
    }

    .review-btn {
        height: 42px;
        border-radius: 9px;
        padding: 0 15px;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 7px;
        text-decoration: none;
        white-space: nowrap;
    }

    .review-btn-primary {
        border: 1px solid #2563eb;
        background: #2563eb;
        color: #ffffff;
    }

    .review-btn-primary:hover {
        background: #1d4ed8;
        color: #ffffff;
    }

    .review-btn-secondary {
        border: 1px solid #d9dee7;
        background: #ffffff;
        color: #64748b;
    }

    .review-btn-secondary:hover {
        background: #f8fafc;
        border-color: #bfdbfe;
        color: #2563eb;
    }

    .review-table-container {
        width: 100%;
        overflow-x: auto;
    }

    .review-table {
        width: 100%;
        min-width: 900px;
        border-collapse: collapse;
    }

    .review-table th {
        padding: 13px 16px;
        background: #f8fafc;
        border-bottom: 1px solid #e5e7eb;
        color: #64748b;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        white-space: nowrap;
    }

    .review-table td {
        padding: 15px 16px;
        border-bottom: 1px solid #eef1f5;
        color: #374151;
        font-size: 13px;
        vertical-align: middle;
    }

    .review-table tbody tr:hover {
        background: #f8fbff;
    }

    .ticket-number {
        color: #2563eb;
        font-weight: 700;
        white-space: nowrap;
    }

    .driver-name {
        color: #1f2937;
        font-weight: 600;
    }

    .license-number {
        display: block;
        margin-top: 3px;
        color: #9ca3af;
        font-size: 11px;
    }

    .vehicle-plate {
        color: #374151;
        font-weight: 600;
        white-space: nowrap;
    }

    .vehicle-type {
        display: block;
        margin-top: 3px;
        color: #9ca3af;
        font-size: 11px;
    }

    .violation-badges {
        display: flex;
        flex-wrap: wrap;
        gap: 5px;
        max-width: 260px;
    }

    .violation-badge {
        display: inline-flex;
        align-items: center;
        padding: 4px 8px;
        border-radius: 6px;
        background: #eff6ff;
        border: 1px solid #dbeafe;
        color: #1d4ed8;
        font-size: 11px;
        font-weight: 600;
    }

    .record-date {
        color: #374151;
        font-weight: 600;
        white-space: nowrap;
    }

    .record-time {
        display: block;
        margin-top: 3px;
        color: #9ca3af;
        font-size: 11px;
    }

    /* ==========================================================
       STATUS EDITOR
       ========================================================== */

    .review-status-form {
        display: flex;
        align-items: center;
        gap: 6px;
        white-space: nowrap;
    }

    .review-status-select {
        height: 34px;
        min-width: 100px;
        border: 1px solid #fcd34d;
        border-radius: 7px;
        padding: 0 8px;
        background: #fef3c7;
        color: #92400e;
        font-size: 12px;
        font-weight: 600;
        outline: none;
        cursor: pointer;
        transition: 0.2s ease;
    }

    .review-status-select.status-pending {
        border-color: #fcd34d;
        background: #fef3c7;
        color: #92400e;
    }

    .review-status-select.status-settled {
        border-color: #86efac;
        background: #dcfce7;
        color: #166534;
    }

    .review-status-select:focus {
        border-color: #2563eb;
        box-shadow: 0 0 0 2px rgba(37, 99, 235, 0.08);
    }

    .review-status-save {
        height: 34px;
        min-width: 52px;
        padding: 0 12px;
        border: 1px solid #2563eb;
        border-radius: 7px;
        background: #2563eb;
        color: #ffffff;
        font-size: 12px;
        font-weight: 600;
        line-height: 32px;
        text-align: center;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: background 0.2s ease, border-color 0.2s ease;
    }

    .review-status-save:hover {
        background: #1d4ed8;
        border-color: #1d4ed8;
        color: #ffffff;
    }

    /* ==========================================================
       ACTION BUTTONS
       ========================================================== */

    .action-buttons {
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .table-action {
        width: 34px;
        height: 34px;
        border: 1px solid #dbe2ea;
        border-radius: 7px;
        background: #ffffff;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: 0.2s;
    }

    .view-action {
        color: #2563eb;
    }

    .copy-action {
        color: #64748b;
    }

    .table-action:hover {
        background: #f8fafc;
        border-color: #bfdbfe;
    }

    /* ==========================================================
       EMPTY STATE
       ========================================================== */

    .empty-state {
        text-align: center;
        padding: 60px 20px !important;
        color: #9ca3af;
    }

    .empty-state i {
        display: block;
        margin-bottom: 10px;
        color: #cbd5e1;
        font-size: 32px;
    }

    .empty-state strong {
        display: block;
        margin-bottom: 4px;
        color: #64748b;
    }

    .empty-state span {
        font-size: 12px;
    }

    .results-footer {
        padding: 13px 18px;
        background: #fafbfc;
        border-top: 1px solid #eef1f5;
        color: #64748b;
        font-size: 12px;
    }

    /* ==========================================================
       DRIVER HISTORY MODAL
       ========================================================== */

    .driver-history-modal {
        position: fixed;
        inset: 0;
        z-index: 10000;
        display: none;
        align-items: center;
        justify-content: center;
        padding: 20px;
        background: rgba(15, 23, 42, 0.55);
    }

    .driver-history-modal.show {
        display: flex;
    }

    .driver-history-dialog {
        width: min(1100px, 100%);
        max-height: 90vh;
        overflow: hidden;
        background: #ffffff;
        border-radius: 16px;
        box-shadow: 0 20px 60px rgba(15, 23, 42, 0.22);
        display: flex;
        flex-direction: column;
    }

    .driver-history-header {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 20px;
        padding: 20px 24px;
        border-bottom: 1px solid #e5e7eb;
        background: #ffffff;
    }

    .driver-history-header-content {
        display: flex;
        align-items: flex-start;
        gap: 13px;
    }

    .driver-history-icon {
        width: 42px;
        height: 42px;
        border-radius: 10px;
        background: #eff6ff;
        color: #2563eb;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .driver-history-header h2 {
        margin: 0;
        color: #172033;
        font-size: 20px;
        font-weight: 700;
    }

    .driver-history-header p {
        margin: 4px 0 0;
        color: #6b7280;
        font-size: 12px;
    }

    .driver-history-close {
        width: 34px;
        height: 34px;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        background: #ffffff;
        color: #64748b;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .driver-history-close:hover {
        background: #f8fafc;
        color: #1f2937;
    }

    .driver-history-body {
        padding: 22px 24px;
        overflow-y: auto;
        background: #f8fafc;
    }

    .driver-history-driver-card {
        background: #ffffff;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        padding: 18px;
        margin-bottom: 16px;
    }

    .driver-history-driver-top {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 20px;
        margin-bottom: 16px;
    }

    .driver-history-name {
        margin: 0;
        color: #172033;
        font-size: 19px;
        font-weight: 700;
    }

    .driver-history-license {
        margin-top: 4px;
        color: #64748b;
        font-size: 12px;
    }

    .driver-history-details {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 12px;
    }

    .driver-history-detail {
        padding: 11px 12px;
        border-radius: 8px;
        background: #f8fafc;
        border: 1px solid #eef1f5;
    }

    .driver-history-detail-label {
        display: block;
        margin-bottom: 4px;
        color: #9ca3af;
        font-size: 10px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.04em;
    }

    .driver-history-detail-value {
        color: #374151;
        font-size: 12px;
        font-weight: 600;
        word-break: break-word;
    }

    .driver-history-summary {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 12px;
        margin-bottom: 16px;
    }

    .history-summary-card {
        background: #ffffff;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        padding: 15px 16px;
    }

    .history-summary-label {
        display: flex;
        align-items: center;
        gap: 7px;
        color: #64748b;
        font-size: 11px;
        font-weight: 600;
    }

    .history-summary-value {
        display: block;
        margin-top: 5px;
        color: #172033;
        font-size: 23px;
        font-weight: 700;
    }

    .history-summary-card.total .history-summary-label i {
        color: #2563eb;
    }

    .history-summary-card.pending .history-summary-label i {
        color: #d97706;
    }

    .history-summary-card.settled .history-summary-label i {
        color: #16a34a;
    }

    .driver-history-section {
        background: #ffffff;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        margin-bottom: 16px;
        overflow: hidden;
    }

    .driver-history-section-header {
        padding: 15px 17px;
        border-bottom: 1px solid #eef1f5;
    }

    .driver-history-section-header h3 {
        margin: 0;
        color: #172033;
        font-size: 14px;
        font-weight: 700;
    }

    .driver-history-section-header p {
        margin: 3px 0 0;
        color: #9ca3af;
        font-size: 11px;
    }

    .history-vehicles {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        padding: 15px 17px;
    }

    .history-vehicle {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        padding: 7px 10px;
        border: 1px solid #dbeafe;
        border-radius: 7px;
        background: #eff6ff;
        color: #1d4ed8;
        font-size: 11px;
        font-weight: 600;
    }

    .history-empty {
        padding: 20px;
        text-align: center;
        color: #9ca3af;
        font-size: 12px;
    }

    .history-table-container {
        width: 100%;
        overflow-x: auto;
    }

    .history-table {
        width: 100%;
        min-width: 780px;
        border-collapse: collapse;
    }

    .history-table th {
        padding: 11px 13px;
        background: #f8fafc;
        border-bottom: 1px solid #e5e7eb;
        color: #64748b;
        font-size: 10px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        white-space: nowrap;
    }

    .history-table td {
        padding: 12px 13px;
        border-bottom: 1px solid #eef1f5;
        color: #374151;
        font-size: 12px;
        vertical-align: middle;
    }

    .history-table tbody tr:last-child td {
        border-bottom: none;
    }

    .history-ticket {
        color: #2563eb;
        font-weight: 700;
        white-space: nowrap;
    }

    .history-status {
        display: inline-flex;
        align-items: center;
        padding: 5px 9px;
        border-radius: 999px;
        font-size: 10px;
        font-weight: 700;
        white-space: nowrap;
    }

    .history-status.pending {
        background: #fef3c7;
        color: #92400e;
    }

    .history-status.settled {
        background: #dcfce7;
        color: #166534;
    }

    .history-location {
        max-width: 180px;
        color: #64748b;
    }

    .driver-history-loading {
        padding: 50px 20px;
        text-align: center;
        color: #64748b;
    }

    .driver-history-loading i {
        display: block;
        margin-bottom: 12px;
        color: #2563eb;
        font-size: 28px;
    }

    .driver-history-error {
        display: none;
        padding: 30px 20px;
        text-align: center;
        color: #b91c1c;
    }

    .driver-history-error i {
        display: block;
        margin-bottom: 10px;
        font-size: 28px;
    }

    .driver-history-footer {
        display: flex;
        justify-content: flex-end;
        padding: 14px 24px;
        border-top: 1px solid #e5e7eb;
        background: #ffffff;
    }

    .driver-history-footer button {
        height: 36px;
        padding: 0 14px;
        border: 1px solid #d9dee7;
        border-radius: 8px;
        background: #ffffff;
        color: #64748b;
        font-size: 12px;
        font-weight: 600;
        cursor: pointer;
    }

    .driver-history-footer button:hover {
        background: #f8fafc;
        color: #2563eb;
        border-color: #bfdbfe;
    }

    /* ==========================================================
       DRIVER HISTORY BUTTON IN VIOLATION MODAL
       ========================================================== */

    .driver-history-trigger {
        border: 1px solid #2563eb;
        background: #eff6ff;
        color: #2563eb;
    }

    .driver-history-trigger:hover {
        background: #dbeafe;
        border-color: #1d4ed8;
        color: #1d4ed8;
    }

    @media (max-width: 900px) {
        .review-filter-form {
            grid-template-columns: 1fr 1fr;
        }

        .review-btn {
            width: 100%;
        }

        .driver-history-details {
            grid-template-columns: 1fr 1fr;
        }
    }

    @media (max-width: 650px) {
        .bplo-review {
            padding: 15px;
        }

        .review-header {
            flex-direction: column;
        }

        .review-header-badge {
            width: 100%;
            justify-content: center;
        }

        .review-filter-form {
            grid-template-columns: 1fr;
        }

        .review-title-row {
            flex-direction: column;
        }

        .review-status-form {
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .review-status-select {
            min-width: 100px;
        }

        .review-status-save {
            min-width: 52px;
        }

        .driver-history-modal {
            padding: 10px;
        }

        .driver-history-dialog {
            max-height: 94vh;
        }

        .driver-history-header,
        .driver-history-body,
        .driver-history-footer {
            padding-left: 15px;
            padding-right: 15px;
        }

        .driver-history-details {
            grid-template-columns: 1fr;
        }

        .driver-history-summary {
            grid-template-columns: 1fr;
        }

        .driver-history-driver-top {
            flex-direction: column;
        }
    }
</style>

<div class="bplo-review">

    <div class="review-header">

        <div>
            <h1>Violation Review</h1>

            <p>
                Review and monitor traffic violation records submitted by POSO personnel.
            </p>
        </div>

        <div class="review-header-badge">
            <i class="fa-solid fa-clipboard-check"></i>
            BPLO Traffic Records
        </div>

    </div>

    <div class="review-card">

        <div class="review-card-header">

            <div class="review-title-row">

                <div>
                    <h2 class="review-title">
                        Violation Records
                    </h2>

                    <p class="review-subtitle">
                        Search and filter submitted traffic violations.
                    </p>
                </div>

                <div class="review-count">
                    {{ $violations->count() }}
                    {{ $violations->count() === 1 ? 'record' : 'records' }}
                </div>

            </div>

            <form
                method="GET"
                action="{{ route('bplo.violations.index') }}"
                class="review-filter-form"
            >

                <div class="review-search-wrapper">

                    <i class="fa-solid fa-magnifying-glass"></i>

                    <input
                        type="text"
                        name="search"
                        value="{{ $search }}"
                        class="review-search"
                        placeholder="Search ticket, driver, license, plate, violation, officer, location..."
                        autocomplete="off"
                    >

                </div>

                <select name="status" class="review-status">

                    <option
                        value="all"
                        {{ $status === 'all' ? 'selected' : '' }}
                    >
                        All Status
                    </option>

                    <option
                        value="Pending"
                        {{ $status === 'Pending' ? 'selected' : '' }}
                    >
                        Pending
                    </option>

                    <option
                        value="Settled"
                        {{ $status === 'Settled' ? 'selected' : '' }}
                    >
                        Settled
                    </option>

                </select>

                <button
                    type="submit"
                    class="review-btn review-btn-primary"
                >
                    <i class="fa-solid fa-filter"></i>
                    Search
                </button>

                @if (!empty($search) || $status !== 'all')

                    <a
                        href="{{ route('bplo.violations.index') }}"
                        class="review-btn review-btn-secondary"
                    >
                        <i class="fa-solid fa-xmark"></i>
                        Clear
                    </a>

                @else

                    <button
                        type="reset"
                        class="review-btn review-btn-secondary"
                        onclick="this.form.querySelector('[name=search]').value=''; this.form.querySelector('[name=status]').value='all';"
                    >
                        <i class="fa-solid fa-rotate-left"></i>
                        Reset
                    </button>

                @endif

            </form>

        </div>

        <div class="review-table-container">

            <table class="review-table">

                <thead>

                    <tr>
                        <th>Ticket Number</th>
                        <th>Driver</th>
                        <th>Vehicle</th>
                        <th>Violation</th>
                        <th>Date & Time</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>

                </thead>

                <tbody>

                    @forelse($violations as $violation)

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

                            $formattedDate = $violation->violation_date
                                ? \Carbon\Carbon::parse($violation->violation_date)->format('M d, Y')
                                : 'N/A';

                            $formattedTime = $violation->violation_time
                                ? \Carbon\Carbon::parse($violation->violation_time)->format('h:i A')
                                : 'N/A';

                            $vehicleInfo = trim(
                                ($violation->vehicle->plate_number ?? 'N/A') .
                                ' ' .
                                ($violation->vehicle->vehicle_type ?? '')
                            );

                            $location = $violation->location ?? 'N/A';

                            $remarks = $violation->remarks ?? 'N/A';

                            $officerName = $violation->user->name ?? 'N/A';

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

                                @if ($violation->driver?->license_number)

                                    <span class="license-number">
                                        License: {{ $violation->driver->license_number }}
                                    </span>

                                @endif

                            </td>

                            <td>

                                <span class="vehicle-plate">
                                    {{ $violation->vehicle->plate_number ?? 'N/A' }}
                                </span>

                                @if ($violation->vehicle?->vehicle_type)

                                    <span class="vehicle-type">
                                        {{ $violation->vehicle->vehicle_type }}
                                    </span>

                                @endif

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

                                <span class="record-date">
                                    {{ $formattedDate }}
                                </span>

                                <span class="record-time">
                                    {{ $formattedTime }}
                                </span>

                            </td>

                            <td>

                                <form
                                    method="POST"
                                    action="{{ route('bplo.violations.status', $violation) }}"
                                    class="review-status-form"
                                >

                                    @csrf
                                    @method('PATCH')

                                    <select
                                        name="status"
                                        class="review-status-select {{ strtolower($violation->status) === 'settled' ? 'status-settled' : 'status-pending' }}"
                                    >

                                        <option
                                            value="Pending"
                                            {{ strtolower($violation->status) === 'pending' ? 'selected' : '' }}
                                        >
                                            Pending
                                        </option>

                                        <option
                                            value="Settled"
                                            {{ strtolower($violation->status) === 'settled' ? 'selected' : '' }}
                                        >
                                            Settled
                                        </option>

                                    </select>

                                    <button
                                        type="submit"
                                        class="review-status-save"
                                        title="Save Status"
                                    >
                                        Save
                                    </button>

                                </form>

                            </td>

                            <td>

                                <div class="action-buttons">

                                    <button
                                        type="button"
                                        class="table-action view-action violation-view-trigger"
                                        title="View Details"

                                        data-driver-id="{{ $violation->driver?->id }}"

                                        data-ticket="{{ $violation->ticket_number }}"
                                        data-name="{{ $driverName }}"
                                        data-license="{{ $violation->driver->license_number ?? 'N/A' }}"
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

                                    <button
                                        type="button"
                                        class="table-action copy-action violation-copy-trigger"
                                        title="Copy Information"

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

                                        <i class="fa-regular fa-copy"></i>

                                    </button>

                                </div>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td
                                colspan="7"
                                class="empty-state"
                            >

                                <i class="fa-regular fa-folder-open"></i>

                                <strong>
                                    No violation records found.
                                </strong>

                                <span>
                                    Try changing your search term or status filter.
                                </span>

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

        <div class="results-footer">

            Showing {{ $violations->count() }}

            {{ $violations->count() === 1 ? 'violation record' : 'violation records' }}

        </div>

    </div>

</div>


{{-- ==========================================================
     EXISTING VIOLATION DETAILS MODAL
     ========================================================== --}}

@include('partials.bplo-violation-modal')


{{-- ==========================================================
     DRIVER HISTORY MODAL
     ========================================================== --}}

<div
    id="driverHistoryModal"
    class="driver-history-modal"
    aria-hidden="true"
>

    <div
        class="driver-history-dialog"
        role="dialog"
        aria-modal="true"
        aria-labelledby="driverHistoryTitle"
    >

        <div class="driver-history-header">

            <div class="driver-history-header-content">

                <div class="driver-history-icon">
                    <i class="fa-solid fa-user-clock"></i>
                </div>

                <div>

                    <h2 id="driverHistoryTitle">
                        Driver History
                    </h2>

                    <p>
                        Complete traffic violation history for the selected driver.
                    </p>

                </div>

            </div>

            <button
                type="button"
                class="driver-history-close"
                id="driverHistoryClose"
                title="Close"
            >
                <i class="fa-solid fa-xmark"></i>
            </button>

        </div>


        <div class="driver-history-body">

            {{-- Loading --}}

            <div
                id="driverHistoryLoading"
                class="driver-history-loading"
            >

                <i class="fa-solid fa-spinner fa-spin"></i>

                Loading driver history...

            </div>


            {{-- Error --}}

            <div
                id="driverHistoryError"
                class="driver-history-error"
            >

                <i class="fa-solid fa-circle-exclamation"></i>

                <strong>
                    Unable to load driver history.
                </strong>

                <div id="driverHistoryErrorMessage">
                    Please try again.
                </div>

            </div>


            {{-- Actual Content --}}

            <div id="driverHistoryContent" style="display: none;">

                {{-- Driver Information --}}

                <div class="driver-history-driver-card">

                    <div class="driver-history-driver-top">

                        <div>

                            <h3
                                id="historyDriverName"
                                class="driver-history-name"
                            >
                                N/A
                            </h3>

                            <div
                                id="historyDriverLicense"
                                class="driver-history-license"
                            >
                                License: N/A
                            </div>

                        </div>

                    </div>


                    <div class="driver-history-details">

                        <div class="driver-history-detail">

                            <span class="driver-history-detail-label">
                                Address
                            </span>

                            <span
                                id="historyDriverAddress"
                                class="driver-history-detail-value"
                            >
                                N/A
                            </span>

                        </div>

                        <div class="driver-history-detail">

                            <span class="driver-history-detail-label">
                                Contact Number
                            </span>

                            <span
                                id="historyDriverContact"
                                class="driver-history-detail-value"
                            >
                                N/A
                            </span>

                        </div>

                        <div class="driver-history-detail">

                            <span class="driver-history-detail-label">
                                License Type
                            </span>

                            <span
                                id="historyDriverLicenseType"
                                class="driver-history-detail-value"
                            >
                                N/A
                            </span>

                        </div>

                    </div>

                </div>


                {{-- Summary --}}

                <div class="driver-history-summary">

                    <div class="history-summary-card total">

                        <div class="history-summary-label">

                            <i class="fa-solid fa-file-lines"></i>

                            Total Violations

                        </div>

                        <span
                            id="historyTotal"
                            class="history-summary-value"
                        >
                            0
                        </span>

                    </div>


                    <div class="history-summary-card pending">

                        <div class="history-summary-label">

                            <i class="fa-solid fa-clock"></i>

                            Pending

                        </div>

                        <span
                            id="historyPending"
                            class="history-summary-value"
                        >
                            0
                        </span>

                    </div>


                    <div class="history-summary-card settled">

                        <div class="history-summary-label">

                            <i class="fa-solid fa-circle-check"></i>

                            Settled

                        </div>

                        <span
                            id="historySettled"
                            class="history-summary-value"
                        >
                            0
                        </span>

                    </div>

                </div>


                {{-- Associated Vehicles --}}

                <div class="driver-history-section">

                    <div class="driver-history-section-header">

                        <h3>
                            Associated Vehicles
                        </h3>

                        <p>
                            Vehicles recorded under this driver's profile.
                        </p>

                    </div>

                    <div
                        id="historyVehicles"
                        class="history-vehicles"
                    >
                    </div>

                </div>


                {{-- Violation History --}}

                <div class="driver-history-section">

                    <div class="driver-history-section-header">

                        <h3>
                            Complete Violation History
                        </h3>

                        <p>
                            All recorded traffic violations associated with this driver.
                        </p>

                    </div>

                    <div class="history-table-container">

                        <table class="history-table">

                            <thead>

                                <tr>
                                    <th>Ticket</th>
                                    <th>Date & Time</th>
                                    <th>Violation</th>
                                    <th>Vehicle</th>
                                    <th>Location</th>
                                    <th>Status</th>
                                </tr>

                            </thead>

                            <tbody id="historyViolationsBody">
                            </tbody>

                        </table>

                    </div>

                </div>

            </div>

        </div>


        <div class="driver-history-footer">

            <button
                type="button"
                id="driverHistoryDone"
            >
                Close
            </button>

        </div>

    </div>

</div>


<script>

document.addEventListener('DOMContentLoaded', function () {

    // ==========================================================
    // EXISTING VIOLATION MODAL
    // ==========================================================

    const modal = document.getElementById('violationDetailsModal');
    const modalClose = document.getElementById('violationModalClose');
    const modalX = document.getElementById('violationModalX');
    const modalCopy = document.getElementById('violationModalCopy');


    function setDetail(id, value) {

        const element = document.getElementById(id);

        if (element) {
            element.textContent = value || 'N/A';
        }

    }


    // ==========================================================
    // DRIVER HISTORY MODAL ELEMENTS
    // ==========================================================

    const driverHistoryModal =
        document.getElementById('driverHistoryModal');

    const driverHistoryClose =
        document.getElementById('driverHistoryClose');

    const driverHistoryDone =
        document.getElementById('driverHistoryDone');

    const driverHistoryLoading =
        document.getElementById('driverHistoryLoading');

    const driverHistoryError =
        document.getElementById('driverHistoryError');

    const driverHistoryErrorMessage =
        document.getElementById('driverHistoryErrorMessage');

    const driverHistoryContent =
        document.getElementById('driverHistoryContent');


    // ==========================================================
    // DRIVER HISTORY DATA ELEMENTS
    // ==========================================================

    const historyDriverName =
        document.getElementById('historyDriverName');

    const historyDriverLicense =
        document.getElementById('historyDriverLicense');

    const historyDriverAddress =
        document.getElementById('historyDriverAddress');

    const historyDriverContact =
        document.getElementById('historyDriverContact');

    const historyDriverLicenseType =
        document.getElementById('historyDriverLicenseType');

    const historyTotal =
        document.getElementById('historyTotal');

    const historyPending =
        document.getElementById('historyPending');

    const historySettled =
        document.getElementById('historySettled');

    const historyVehicles =
        document.getElementById('historyVehicles');

    const historyViolationsBody =
        document.getElementById('historyViolationsBody');


    // ==========================================================
    // SAFE TEXT HELPER
    // ==========================================================

    function safeText(value, fallback = 'N/A') {

        if (
            value === null ||
            value === undefined ||
            String(value).trim() === ''
        ) {
            return fallback;
        }

        return String(value);

    }


    // ==========================================================
    // FORMAT DATE
    // ==========================================================

    function formatHistoryDate(dateValue) {

        if (!dateValue) {
            return 'N/A';
        }

        const date = new Date(dateValue);

        if (Number.isNaN(date.getTime())) {
            return dateValue;
        }

        return date.toLocaleDateString('en-US', {
            month: 'short',
            day: '2-digit',
            year: 'numeric'
        });

    }


    // ==========================================================
    // FORMAT TIME
    // ==========================================================

    function formatHistoryTime(timeValue) {

        if (!timeValue) {
            return 'N/A';
        }

        const parts = String(timeValue).split(':');

        if (parts.length < 2) {
            return timeValue;
        }

        let hour = parseInt(parts[0], 10);
        const minute = parts[1];

        if (Number.isNaN(hour)) {
            return timeValue;
        }

        const period = hour >= 12 ? 'PM' : 'AM';

        hour = hour % 12 || 12;

        return `${hour}:${minute} ${period}`;

    }


    // ==========================================================
    // RESET DRIVER HISTORY
    // ==========================================================

    function resetDriverHistory() {

        if (driverHistoryLoading) {
            driverHistoryLoading.style.display = 'block';
        }

        if (driverHistoryError) {
            driverHistoryError.style.display = 'none';
        }

        if (driverHistoryContent) {
            driverHistoryContent.style.display = 'none';
        }

        if (historyDriverName) {
            historyDriverName.textContent = 'N/A';
        }

        if (historyDriverLicense) {
            historyDriverLicense.textContent = 'License: N/A';
        }

        if (historyDriverAddress) {
            historyDriverAddress.textContent = 'N/A';
        }

        if (historyDriverContact) {
            historyDriverContact.textContent = 'N/A';
        }

        if (historyDriverLicenseType) {
            historyDriverLicenseType.textContent = 'N/A';
        }

        if (historyTotal) {
            historyTotal.textContent = '0';
        }

        if (historyPending) {
            historyPending.textContent = '0';
        }

        if (historySettled) {
            historySettled.textContent = '0';
        }

        if (historyVehicles) {
            historyVehicles.innerHTML = '';
        }

        if (historyViolationsBody) {
            historyViolationsBody.innerHTML = '';
        }

    }


    // ==========================================================
    // CLOSE DRIVER HISTORY
    // ==========================================================

    function closeDriverHistory() {

        if (driverHistoryModal) {

            driverHistoryModal.classList.remove('show');

            driverHistoryModal.setAttribute(
                'aria-hidden',
                'true'
            );

        }

    }


    if (driverHistoryClose) {
        driverHistoryClose.addEventListener(
            'click',
            closeDriverHistory
        );
    }


    if (driverHistoryDone) {
        driverHistoryDone.addEventListener(
            'click',
            closeDriverHistory
        );
    }


    if (driverHistoryModal) {

        driverHistoryModal.addEventListener(
            'click',
            function (event) {

                if (event.target === driverHistoryModal) {
                    closeDriverHistory();
                }

            }
        );

    }


    // ==========================================================
    // LOAD DRIVER HISTORY
    // ==========================================================

    async function loadDriverHistory(driverId) {

        if (!driverId) {

            if (driverHistoryLoading) {
                driverHistoryLoading.style.display = 'none';
            }

            if (driverHistoryError) {
                driverHistoryError.style.display = 'block';
            }

            if (driverHistoryErrorMessage) {
                driverHistoryErrorMessage.textContent =
                    'No driver record is associated with this violation.';
            }

            return;

        }


        resetDriverHistory();


        try {

            const url =
                "{{ url('/bplo/violations/driver-history') }}/" +
                encodeURIComponent(driverId);


            const response = await fetch(url, {

                method: 'GET',

                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }

            });


            if (!response.ok) {

                let message =
                    'Unable to load the driver history.';

                try {

                    const errorData =
                        await response.json();

                    if (errorData.message) {
                        message = errorData.message;
                    }

                } catch (error) {
                    // Ignore JSON parsing errors.
                }

                throw new Error(message);

            }


            const data = await response.json();


            // ==================================================
            // DRIVER INFORMATION
            // ==================================================

            if (historyDriverName) {
                historyDriverName.textContent =
                    safeText(data.driver?.name);
            }

            if (historyDriverLicense) {
                historyDriverLicense.textContent =
                    'License: ' +
                    safeText(data.driver?.license_number);
            }

            if (historyDriverAddress) {
                historyDriverAddress.textContent =
                    safeText(data.driver?.address);
            }

            if (historyDriverContact) {
                historyDriverContact.textContent =
                    safeText(data.driver?.contact_number);
            }

            if (historyDriverLicenseType) {
                historyDriverLicenseType.textContent =
                    safeText(data.driver?.license_type);
            }


            // ==================================================
            // SUMMARY
            // ==================================================

            if (historyTotal) {
                historyTotal.textContent =
                    safeText(data.summary?.total, '0');
            }

            if (historyPending) {
                historyPending.textContent =
                    safeText(data.summary?.pending, '0');
            }

            if (historySettled) {
                historySettled.textContent =
                    safeText(data.summary?.settled, '0');
            }


            // ==================================================
            // ASSOCIATED VEHICLES
            // ==================================================

            if (historyVehicles) {

                historyVehicles.innerHTML = '';

                const vehicles =
                    Array.isArray(data.vehicles)
                        ? data.vehicles
                        : [];


                if (vehicles.length === 0) {

                    historyVehicles.innerHTML =
                        '<div class="history-empty">' +
                        'No associated vehicles recorded.' +
                        '</div>';

                } else {

                    vehicles.forEach(function (vehicle) {

                        const item =
                            document.createElement('div');

                        item.className =
                            'history-vehicle';


                        const icon =
                            document.createElement('i');

                        icon.className =
                            'fa-solid fa-car';


                        const text =
                            document.createElement('span');

                        const plate =
                            safeText(
                                vehicle.plate_number,
                                'No Plate'
                            );

                        const type =
                            safeText(
                                vehicle.vehicle_type,
                                ''
                            );


                        text.textContent =
                            type
                                ? `${plate} (${type})`
                                : plate;


                        item.appendChild(icon);
                        item.appendChild(text);

                        historyVehicles.appendChild(item);

                    });

                }

            }


            // ==================================================
            // VIOLATION HISTORY
            // ==================================================

            if (historyViolationsBody) {

                historyViolationsBody.innerHTML = '';

                const violations =
                    Array.isArray(data.violations)
                        ? data.violations
                        : [];


                if (violations.length === 0) {

                    historyViolationsBody.innerHTML = `
                        <tr>
                            <td
                                colspan="6"
                                class="history-empty"
                            >
                                No violation history found.
                            </td>
                        </tr>
                    `;

                } else {

                    violations.forEach(function (violation) {

                        const row =
                            document.createElement('tr');


                        // Ticket
                        const ticketCell =
                            document.createElement('td');

                        const ticket =
                            document.createElement('span');

                        ticket.className =
                            'history-ticket';

                        ticket.textContent =
                            '#' +
                            safeText(
                                violation.ticket_number
                            );

                        ticketCell.appendChild(ticket);


                        // Date & Time
                        const dateCell =
                            document.createElement('td');

                        const date =
                            document.createElement('strong');

                        date.textContent =
                            formatHistoryDate(
                                violation.date
                            );

                        const time =
                            document.createElement('span');

                        time.style.display = 'block';
                        time.style.marginTop = '3px';
                        time.style.color = '#9ca3af';
                        time.style.fontSize = '11px';

                        time.textContent =
                            formatHistoryTime(
                                violation.time
                            );

                        dateCell.appendChild(date);
                        dateCell.appendChild(time);


                        // Violation
                        const violationCell =
                            document.createElement('td');

                        violationCell.textContent =
                            safeText(
                                violation.violation
                            );


                        // Vehicle
                        const vehicleCell =
                            document.createElement('td');

                        vehicleCell.textContent =
                            safeText(
                                violation.vehicle
                            );


                        // Location
                        const locationCell =
                            document.createElement('td');

                        locationCell.className =
                            'history-location';

                        locationCell.textContent =
                            safeText(
                                violation.location
                            );


                        // Status
                        const statusCell =
                            document.createElement('td');

                        const status =
                            document.createElement('span');

                        const normalizedStatus =
                            String(
                                violation.status || ''
                            ).toLowerCase();


                        if (normalizedStatus === 'settled') {

                            status.className =
                                'history-status settled';

                            status.textContent =
                                'Settled';

                        } else {

                            status.className =
                                'history-status pending';

                            status.textContent =
                                'Pending';

                        }


                        statusCell.appendChild(status);


                        row.appendChild(ticketCell);
                        row.appendChild(dateCell);
                        row.appendChild(violationCell);
                        row.appendChild(vehicleCell);
                        row.appendChild(locationCell);
                        row.appendChild(statusCell);

                        historyViolationsBody.appendChild(row);

                    });

                }

            }


            // ==================================================
            // SHOW CONTENT
            // ==================================================

            if (driverHistoryLoading) {
                driverHistoryLoading.style.display = 'none';
            }

            if (driverHistoryContent) {
                driverHistoryContent.style.display = 'block';
            }

        } catch (error) {

            console.error(
                'Driver history error:',
                error
            );


            if (driverHistoryLoading) {
                driverHistoryLoading.style.display = 'none';
            }

            if (driverHistoryContent) {
                driverHistoryContent.style.display = 'none';
            }

            if (driverHistoryError) {
                driverHistoryError.style.display = 'block';
            }

            if (driverHistoryErrorMessage) {
                driverHistoryErrorMessage.textContent =
                    error.message ||
                    'Unable to load the driver history.';

            }

        }

    }


    // ==========================================================
    // VIEW DETAILS
    // ==========================================================

    document
        .querySelectorAll('.violation-view-trigger')
        .forEach(function (button) {

            button.addEventListener(
                'click',
                function () {

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


                    // ==================================================
                    // DRIVER HISTORY BUTTON
                    // ==================================================

                    const existingDriverHistoryButton =
                        document.getElementById(
                            'dynamicDriverHistoryButton'
                        );


                    if (existingDriverHistoryButton) {
                        existingDriverHistoryButton.remove();
                    }


                    const modalBody =
                        modal
                            ? modal.querySelector(
                                '.violation-modal-body'
                            )
                            : null;


                    if (
                        modalBody &&
                        button.dataset.driverId
                    ) {

                        const historyButton =
                            document.createElement('button');

                        historyButton.type = 'button';

                        historyButton.id =
                            'dynamicDriverHistoryButton';

                        historyButton.className =
                            'driver-history-trigger';

                        historyButton.style.width =
                            '100%';

                        historyButton.style.height =
                            '40px';

                        historyButton.style.marginTop =
                            '15px';

                        historyButton.style.borderRadius =
                            '8px';

                        historyButton.style.fontSize =
                            '12px';

                        historyButton.style.fontWeight =
                            '600';

                        historyButton.style.cursor =
                            'pointer';

                        historyButton.innerHTML =
                            '<i class="fa-solid fa-user-clock me-1"></i>' +
                            'View Driver History';


                        historyButton.addEventListener(
                            'click',
                            function () {

                                if (modal) {
                                    modal.classList.remove('show');
                                }


                                if (driverHistoryModal) {

                                    resetDriverHistory();

                                    driverHistoryModal.classList.add(
                                        'show'
                                    );

                                    driverHistoryModal.setAttribute(
                                        'aria-hidden',
                                        'false'
                                    );

                                }


                                loadDriverHistory(
                                    button.dataset.driverId
                                );

                            }
                        );


                        modalBody.appendChild(
                            historyButton
                        );

                    }


                    if (modal) {
                        modal.classList.add('show');
                    }

                }
            );

        });


    // ==========================================================
    // CLOSE EXISTING VIOLATION MODAL
    // ==========================================================

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


    // ==========================================================
    // ESCAPE KEY
    // ==========================================================

    document.addEventListener(
        'keydown',
        function (event) {

            if (event.key === 'Escape') {

                if (
                    driverHistoryModal &&
                    driverHistoryModal.classList.contains('show')
                ) {

                    closeDriverHistory();

                } else {

                    closeModal();

                }

            }

        }
    );


    // ==========================================================
    // COPY DIRECTLY FROM TABLE
    // ==========================================================

    document
        .querySelectorAll('.violation-copy-trigger')
        .forEach(function (button) {

            button.addEventListener(
                'click',
                async function () {

                    const text = [

                        'Ticket ID: ' +
                            (button.dataset.ticket || 'N/A'),

                        'Name: ' +
                            (button.dataset.name || 'N/A'),

                        'Vehicle: ' +
                            (button.dataset.vehicle || 'N/A'),

                        'Violation: ' +
                            (button.dataset.violation || 'N/A'),

                        'Officer: ' +
                            (button.dataset.officer || 'N/A'),

                        'Date: ' +
                            (button.dataset.date || 'N/A'),

                        'Time: ' +
                            (button.dataset.time || 'N/A'),

                        'Location: ' +
                            (button.dataset.location || 'N/A'),

                        'Remarks: ' +
                            (button.dataset.remarks || 'N/A'),

                        'Status: ' +
                            (button.dataset.status || 'N/A')

                    ].join('\n');


                    try {

                        await navigator.clipboard.writeText(
                            text
                        );


                        const originalHTML =
                            button.innerHTML;


                        button.innerHTML =
                            '<i class="fa-solid fa-check"></i>';


                        setTimeout(
                            function () {

                                button.innerHTML =
                                    originalHTML;

                            },
                            1200
                        );

                    } catch (error) {

                        console.error(
                            'Copy failed:',
                            error
                        );

                    }

                }
            );

        });


    // ==========================================================
    // COPY FROM EXISTING MODAL
    // ==========================================================

    if (modalCopy) {

        modalCopy.addEventListener(
            'click',
            async function () {

                const text = [

                    'Ticket ID: ' +
                        (
                            document.getElementById(
                                'detailTicket'
                            )?.textContent || 'N/A'
                        ),

                    'Name: ' +
                        (
                            document.getElementById(
                                'detailName'
                            )?.textContent || 'N/A'
                        ),

                    'Vehicle: ' +
                        (
                            document.getElementById(
                                'detailVehicle'
                            )?.textContent || 'N/A'
                        ),

                    'Violation: ' +
                        (
                            document.getElementById(
                                'detailViolation'
                            )?.textContent || 'N/A'
                        ),

                    'Officer: ' +
                        (
                            document.getElementById(
                                'detailOfficer'
                            )?.textContent || 'N/A'
                        ),

                    'Date: ' +
                        (
                            document.getElementById(
                                'detailDate'
                            )?.textContent || 'N/A'
                        ),

                    'Time: ' +
                        (
                            document.getElementById(
                                'detailTime'
                            )?.textContent || 'N/A'
                        ),

                    'Location: ' +
                        (
                            document.getElementById(
                                'detailLocation'
                            )?.textContent || 'N/A'
                        ),

                    'Remarks: ' +
                        (
                            document.getElementById(
                                'detailRemarks'
                            )?.textContent || 'N/A'
                        ),

                    'Status: ' +
                        (
                            document.getElementById(
                                'detailStatus'
                            )?.textContent || 'N/A'
                        )

                ].join('\n');


                try {

                    await navigator.clipboard.writeText(
                        text
                    );


                    const originalHTML =
                        modalCopy.innerHTML;


                    modalCopy.innerHTML =
                        '<i class="fa-solid fa-check me-1"></i> Copied';


                    setTimeout(
                        function () {

                            modalCopy.innerHTML =
                                originalHTML;

                        },
                        1200
                    );

                } catch (error) {

                    console.error(
                        'Copy failed:',
                        error
                    );

                }

            }
        );

    }


    // ==========================================================
    // STATUS DROPDOWN COLOR
    // ==========================================================

    document
        .querySelectorAll('.review-status-select')
        .forEach(function (select) {

            function updateStatusColor() {

                if (
                    select.value.toLowerCase() === 'settled'
                ) {

                    select.classList.remove(
                        'status-pending'
                    );

                    select.classList.add(
                        'status-settled'
                    );

                } else {

                    select.classList.remove(
                        'status-settled'
                    );

                    select.classList.add(
                        'status-pending'
                    );

                }

            }


            updateStatusColor();


            select.addEventListener(
                'change',
                updateStatusColor
            );

        });

});

</script>

@endsection