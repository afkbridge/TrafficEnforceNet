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

    /*
    |--------------------------------------------------------------------------
    | STATUS EDITOR
    |--------------------------------------------------------------------------
    */

    .review-status-form {
        display: flex;
        align-items: center;
        gap: 6px;
        white-space: nowrap;
    }

    .review-status-select {
        height: 34px;
        min-width: 100px;
        border: 1px solid #d9dee7;
        border-radius: 7px;
        padding: 0 8px;
        background: #ffffff;
        color: #374151;
        font-size: 12px;
        outline: none;
        cursor: pointer;
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

    @media (max-width: 900px) {
        .review-filter-form {
            grid-template-columns: 1fr 1fr;
        }

        .review-btn {
            width: 100%;
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
                    <option value="all" {{ $status === 'all' ? 'selected' : '' }}>
                        All Status
                    </option>

                    <option value="Pending" {{ $status === 'Pending' ? 'selected' : '' }}>
                        Pending
                    </option>

                    <option value="Settled" {{ $status === 'Settled' ? 'selected' : '' }}>
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
                                        class="review-status-select"
                                    >
                                        <option
                                            value="Pending"
                                            {{ $violation->status === 'Pending' ? 'selected' : '' }}
                                        >
                                            Pending
                                        </option>

                                        <option
                                            value="Settled"
                                            {{ $violation->status === 'Settled' ? 'selected' : '' }}
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
                            <td colspan="7" class="empty-state">

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

@include('partials.bplo-violation-modal')

<script>
    document.addEventListener('DOMContentLoaded', function() {

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
        // VIEW DETAILS
        // ==========================================================

        document.querySelectorAll('.violation-view-trigger').forEach(function(button) {

            button.addEventListener('click', function() {

                setDetail('detailTicket', button.dataset.ticket);
                setDetail('detailName', button.dataset.name);
                setDetail('detailVehicle', button.dataset.vehicle);
                setDetail('detailViolation', button.dataset.violation);
                setDetail('detailOfficer', button.dataset.officer);
                setDetail('detailDate', button.dataset.date);
                setDetail('detailTime', button.dataset.time);
                setDetail('detailLocation', button.dataset.location);
                setDetail('detailRemarks', button.dataset.remarks);
                setDetail('detailStatus', button.dataset.status);

                if (modal) {
                    modal.classList.add('show');
                }

            });

        });

        // ==========================================================
        // CLOSE MODAL
        // ==========================================================

        function closeModal() {

            if (modal) {
                modal.classList.remove('show');
            }

        }

        if (modalClose) {
            modalClose.addEventListener('click', closeModal);
        }

        if (modalX) {
            modalX.addEventListener('click', closeModal);
        }

        if (modal) {

            modal.addEventListener('click', function(event) {

                if (event.target === modal) {
                    closeModal();
                }

            });

        }

        document.addEventListener('keydown', function(event) {

            if (event.key === 'Escape') {
                closeModal();
            }

        });

        // ==========================================================
        // COPY DIRECTLY FROM TABLE
        // ==========================================================

        document.querySelectorAll('.violation-copy-trigger').forEach(function(button) {

            button.addEventListener('click', async function() {

                const text = [
                    'Ticket ID: ' + (button.dataset.ticket || 'N/A'),
                    'Name: ' + (button.dataset.name || 'N/A'),
                    'Vehicle: ' + (button.dataset.vehicle || 'N/A'),
                    'Violation: ' + (button.dataset.violation || 'N/A'),
                    'Officer: ' + (button.dataset.officer || 'N/A'),
                    'Date: ' + (button.dataset.date || 'N/A'),
                    'Time: ' + (button.dataset.time || 'N/A'),
                    'Location: ' + (button.dataset.location || 'N/A'),
                    'Remarks: ' + (button.dataset.remarks || 'N/A'),
                    'Status: ' + (button.dataset.status || 'N/A')
                ].join('\n');

                try {

                    await navigator.clipboard.writeText(text);

                    const originalHTML = button.innerHTML;

                    button.innerHTML =
                        '<i class="fa-solid fa-check"></i>';

                    setTimeout(function() {
                        button.innerHTML = originalHTML;
                    }, 1200);

                } catch (error) {

                    console.error('Copy failed:', error);

                }

            });

        });

        // ==========================================================
        // COPY FROM MODAL
        // ==========================================================

        if (modalCopy) {

            modalCopy.addEventListener('click', async function() {

                const text = [
                    'Ticket ID: ' + (
                        document.getElementById('detailTicket')?.textContent ||
                        'N/A'
                    ),

                    'Name: ' + (
                        document.getElementById('detailName')?.textContent ||
                        'N/A'
                    ),

                    'Vehicle: ' + (
                        document.getElementById('detailVehicle')?.textContent ||
                        'N/A'
                    ),

                    'Violation: ' + (
                        document.getElementById('detailViolation')?.textContent ||
                        'N/A'
                    ),

                    'Officer: ' + (
                        document.getElementById('detailOfficer')?.textContent ||
                        'N/A'
                    ),

                    'Date: ' + (
                        document.getElementById('detailDate')?.textContent ||
                        'N/A'
                    ),

                    'Time: ' + (
                        document.getElementById('detailTime')?.textContent ||
                        'N/A'
                    ),

                    'Location: ' + (
                        document.getElementById('detailLocation')?.textContent ||
                        'N/A'
                    ),

                    'Remarks: ' + (
                        document.getElementById('detailRemarks')?.textContent ||
                        'N/A'
                    ),

                    'Status: ' + (
                        document.getElementById('detailStatus')?.textContent ||
                        'N/A'
                    )

                ].join('\n');

                try {

                    await navigator.clipboard.writeText(text);

                    const originalHTML = modalCopy.innerHTML;

                    modalCopy.innerHTML =
                        '<i class="fa-solid fa-check me-1"></i> Copied';

                    setTimeout(function() {
                        modalCopy.innerHTML = originalHTML;
                    }, 1200);

                } catch (error) {

                    console.error('Copy failed:', error);

                }

            });

        }

    });
</script>

@endsection