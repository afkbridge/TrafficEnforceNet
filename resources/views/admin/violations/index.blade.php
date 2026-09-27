@extends('layouts.admin')

@section('title', 'Violation Records')

@section('content')

    <div class="container-fluid">

        {{-- PAGE HEADER --}}
        <div class="mb-4 d-flex justify-content-between align-items-center">

            <div>
                <h2 class="fw-bold mb-1">
                    Violation Records
                </h2>

                <p class="text-muted mb-0">
                    Monitor and review recorded traffic violations.
                </p>
            </div>

            <div class="d-flex gap-2">

                <a href="{{ route('admin.violations.create') }}" class="btn btn-primary">

                    <i class="fa-solid fa-plus me-1"></i>
                    Add Citation

                </a>

                <a href="{{ route('admin.violations.export', [
                    'search' => request('search'),
                    'status' => request('status'),
                    'date_from' => request('date_from'),
                    'date_to' => request('date_to'),
                ]) }}"
                    class="btn btn-success">

                    <i class="fa-solid fa-file-excel me-1"></i>
                    Export Excel

                </a>

            </div>

        </div>


        {{-- ========================================================= --}}
        {{-- FILTER CARD --}}
        {{-- ========================================================= --}}
        <div class="card shadow-sm mb-4 violation-filter-card">

            <div class="card-body">

                <form method="GET" action="{{ route('violations.index') }}" class="violation-filter-form">

                    {{-- SEARCH --}}
                    <div class="filter-field filter-search">

                        <label for="search" class="form-label fw-semibold">

                            Search

                        </label>

                        <div class="input-group">

                            <span class="input-group-text bg-white">
                                <i class="fa-solid fa-magnifying-glass text-muted"></i>
                            </span>

                            <input type="text" id="search" name="search" class="form-control"
                                placeholder="Ticket, driver, license, or plate..." value="{{ request('search') }}">

                        </div>

                    </div>


                    {{-- STATUS --}}
                    <div class="filter-field">

                        <label for="status" class="form-label fw-semibold">

                            Status

                        </label>

                        <select name="status" id="status" class="form-select">

                            <option value="">
                                All Statuses
                            </option>

                            <option value="Pending" {{ request('status') == 'Pending' ? 'selected' : '' }}>

                                Pending

                            </option>

                            <option value="Settled" {{ request('status') == 'Settled' ? 'selected' : '' }}>

                                Settled

                            </option>

                        </select>

                    </div>


                    {{-- FROM DATE --}}
                    <div class="filter-field">

                        <label for="date_from" class="form-label fw-semibold">

                            From Date

                        </label>

                        <input type="date" name="date_from" id="date_from" class="form-control"
                            value="{{ request('date_from') }}">

                    </div>


                    {{-- TO DATE --}}
                    <div class="filter-field">

                        <label for="date_to" class="form-label fw-semibold">

                            To Date

                        </label>

                        <input type="date" name="date_to" id="date_to" class="form-control"
                            value="{{ request('date_to') }}">

                    </div>


                    {{-- BUTTONS --}}
                    <div class="filter-actions">

                        <button type="submit" class="btn btn-primary">

                            <i class="fa-solid fa-filter me-1"></i>

                            Search

                        </button>

                        <a href="{{ route('violations.index') }}" class="btn btn-light border" title="Clear Filters">

                            <i class="fa-solid fa-rotate-left"></i>

                        </a>

                    </div>

                </form>

            </div>

        </div>


        {{-- ========================================================= --}}
        {{-- FILTER CARD STYLING --}}
        {{-- ========================================================= --}}
        <style>
            .violation-filter-card {
                border: 1px solid #e5e7eb;
                border-radius: 12px;
                overflow: visible;
            }


            .violation-filter-card .card-body {
                padding: 20px;
            }


            /*
        ============================================================
        FILTER GRID
        ============================================================
        */

            .violation-filter-form {

                display: grid;

                grid-template-columns:
                    minmax(280px, 1.8fr) minmax(150px, 0.8fr) minmax(155px, 0.9fr) minmax(155px, 0.9fr) auto;

                gap: 16px;

                align-items: end;

                width: 100%;

            }


            /*
        ============================================================
        FILTER FIELDS
        ============================================================
        */

            .filter-field {
                min-width: 0;
            }


            .filter-field .form-label {
                display: block;

                margin-bottom: 7px;

                color: #374151;

                font-size: 13px;

                line-height: 1.2;

            }


            .filter-field .form-control,
            .filter-field .form-select {

                height: 42px;

                min-height: 42px;

                border-color: #d1d5db;

                border-radius: 7px;

                font-size: 14px;

                box-shadow: none;

            }


            .filter-field .form-control:focus,
            .filter-field .form-select:focus {

                border-color: #86b7fe;

                box-shadow: 0 0 0 0.15rem rgba(13, 110, 253, 0.12);

            }


            /*
        ============================================================
        SEARCH
        ============================================================
        */

            .filter-search .input-group {

                width: 100%;

            }


            .filter-search .input-group-text {

                height: 42px;

                border-color: #d1d5db;

                border-right: 0;

                border-radius: 7px 0 0 7px;

                padding-left: 13px;

                padding-right: 10px;

            }


            .filter-search .form-control {

                border-left: 0;

                border-radius: 0 7px 7px 0;

            }


            .filter-search .form-control:focus {

                border-left: 0;

                box-shadow: none;

            }


            .filter-search .input-group:focus-within {

                box-shadow: 0 0 0 0.15rem rgba(13, 110, 253, 0.12);

                border-radius: 7px;

            }


            .filter-search .input-group:focus-within .input-group-text,
            .filter-search .input-group:focus-within .form-control {

                border-color: #86b7fe;

            }


            /*
        ============================================================
        ACTION BUTTONS
        ============================================================
        */

            .filter-actions {

                display: flex;

                align-items: end;

                gap: 8px;

                height: 42px;

                white-space: nowrap;

            }


            .filter-actions .btn-primary {

                height: 42px;

                min-width: 105px;

                border-radius: 7px;

                font-size: 14px;

                font-weight: 500;

            }


            .filter-actions .btn-light {

                width: 42px;

                height: 42px;

                padding: 0;

                display: inline-flex;

                align-items: center;

                justify-content: center;

                border-radius: 7px;

                color: #4b5563;

            }


            .filter-actions .btn-light:hover {

                background-color: #f3f4f6;

                color: #1f2937;

            }


            /*
        ============================================================
        MEDIUM DESKTOP
        ============================================================
        */

            @media (max-width: 1200px) {

                .violation-filter-form {

                    grid-template-columns:
                        minmax(240px, 1.6fr) minmax(140px, 0.8fr) minmax(145px, 0.9fr) minmax(145px, 0.9fr) auto;

                    gap: 12px;

                }

            }


            /*
        ============================================================
        TABLET / SMALL DESKTOP
        ============================================================
        */

            @media (max-width: 992px) {

                .violation-filter-form {

                    grid-template-columns:
                        minmax(0, 1fr) minmax(0, 1fr);

                    gap: 14px;

                }


                .filter-search {

                    grid-column: 1 / -1;

                }


                .filter-actions {

                    grid-column: 1 / -1;

                    justify-content: flex-end;

                }


                .filter-actions .btn-primary {

                    min-width: 120px;

                }

            }


            /*
        ============================================================
        MOBILE
        ============================================================
        */

            @media (max-width: 576px) {

                .violation-filter-card .card-body {

                    padding: 16px;

                }


                .violation-filter-form {

                    grid-template-columns: 1fr;

                    gap: 14px;

                }


                .filter-search {

                    grid-column: auto;

                }


                .filter-actions {

                    grid-column: auto;

                    width: 100%;

                    justify-content: stretch;

                }


                .filter-actions .btn-primary {

                    flex: 1;

                }


                .filter-actions .btn-light {

                    width: 42px;

                    flex-shrink: 0;

                }

            }
        </style>


        {{-- TABLE CARD --}}
        <div class="card shadow-sm">

            <div class="card-body">

                <div class="table-responsive">

                    <table class="table table-hover align-middle mb-0">

                        <thead class="table-light">

                            <tr>

                                <th>
                                    Ticket No.
                                </th>

                                <th>
                                    Driver
                                </th>

                                <th>
                                    Plate No.
                                </th>

                                <th>
                                    Violation
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

                            @forelse($violations as $violation)

                                <tr>

                                    {{-- TICKET --}}
                                    <td class="fw-semibold">

                                        {{ $violation->ticket_number }}

                                    </td>


                                    {{-- DRIVER --}}
                                    <td>

                                        {{ $violation->driver->full_name ?? 'N/A' }}

                                    </td>


                                    {{-- PLATE --}}
                                    <td>

                                        @if ($violation->vehicle)
                                            <span class="fw-semibold">

                                                {{ $violation->vehicle->plate_number }}

                                            </span>
                                        @else
                                            <span class="text-muted">

                                                N/A

                                            </span>
                                        @endif

                                    </td>


                                    {{-- VIOLATION --}}
                                    <td>

                                        @php

                                            /*
                                            |--------------------------------------------------------------------------
                                            | BUILD COMPLETE VIOLATION LIST
                                            |--------------------------------------------------------------------------
                                            |
                                            | A violation can contain:
                                            |
                                            | 1. Primary official violation
                                            | 2. Primary custom "Other" violation
                                            | 3. Additional official violations
                                            | 4. Additional custom "Other" violations
                                            |
                                            */

                                            $displayedViolations = [];

                                            /*
                                            |--------------------------------------------------------------------------
                                            | PRIMARY OFFICIAL VIOLATION
                                            |--------------------------------------------------------------------------
                                            */

                                            if ($violation->violationType) {
                                                $name = trim((string) $violation->violationType->name);

                                                if ($name !== '' && !in_array($name, $displayedViolations, true)) {
                                                    $displayedViolations[] = $name;
                                                }
                                            }

                                            /*
                                            |--------------------------------------------------------------------------
                                            | PRIMARY CUSTOM "OTHERS"
                                            |--------------------------------------------------------------------------
                                            */

                                            if (!empty($violation->other_violation)) {
                                                $name = trim((string) $violation->other_violation);

                                                if ($name !== '' && !in_array($name, $displayedViolations, true)) {
                                                    $displayedViolations[] = $name;
                                                }
                                            }

                                            /*
                                            |--------------------------------------------------------------------------
                                            | ADDITIONAL OFFICIAL VIOLATIONS
                                            |--------------------------------------------------------------------------
                                            */

                                            if ($violation->violationTypes && $violation->violationTypes->count()) {
                                                foreach ($violation->violationTypes as $type) {
                                                    if (empty($type->name)) {
                                                        continue;
                                                    }

                                                    $name = trim((string) $type->name);

                                                    if ($name !== '' && !in_array($name, $displayedViolations, true)) {
                                                        $displayedViolations[] = $name;
                                                    }
                                                }
                                            }

                                            /*
                                            |--------------------------------------------------------------------------
                                            | ADDITIONAL CUSTOM "OTHERS"
                                            |--------------------------------------------------------------------------
                                            */

                                            if (
                                                $violation->violationOtherTypes &&
                                                $violation->violationOtherTypes->count()
                                            ) {
                                                foreach ($violation->violationOtherTypes as $otherType) {
                                                    if (empty($otherType->name)) {
                                                        continue;
                                                    }

                                                    $name = trim((string) $otherType->name);

                                                    if ($name !== '' && !in_array($name, $displayedViolations, true)) {
                                                        $displayedViolations[] = $name;
                                                    }
                                                }
                                            }
                                        @endphp


                                        @if (count($displayedViolations))
                                            <div class="d-flex flex-column gap-1">

                                                @foreach ($displayedViolations as $index => $violationName)
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


                                    {{-- LOCATION --}}
                                    <td>

                                        <span title="{{ $violation->location }}">

                                            {{ \Illuminate\Support\Str::limit($violation->location, 30) }}

                                        </span>

                                    </td>


                                    {{-- DATE --}}
                                    <td>

                                        {{ \Carbon\Carbon::parse($violation->violation_date)->format('M d, Y') }}

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

                                        @php

                                            $status = strtolower($violation->status ?? '');

                                        @endphp


                                        @if ($status === 'pending')
                                            <span class="badge bg-warning text-dark">

                                                Pending

                                            </span>
                                        @elseif ($status === 'settled')
                                            <span class="badge bg-success">

                                                Settled

                                            </span>
                                        @else
                                            <span class="badge bg-secondary">

                                                {{ $violation->status ?? 'Unknown' }}

                                            </span>
                                        @endif

                                    </td>


                                    {{-- ACTION --}}
                                    <td class="text-center">

                                        <div class="d-flex justify-content-center gap-1">

                                            {{-- VIEW VIOLATION --}}

                                            <a href="{{ route('violations.show', $violation->id) }}"
                                                class="btn btn-sm btn-primary" title="View violation">

                                                <i class="fa-solid fa-eye"></i>

                                                <span class="d-none d-lg-inline ms-1">
                                                    View
                                                </span>

                                            </a>


                                            {{-- DRIVER HISTORY --}}

                                            @if ($violation->driver)
                                                <a href="{{ route('violations.driver-history', $violation->driver->id) }}"
                                                    class="btn btn-sm btn-outline-secondary" title="View driver history">

                                                    <i class="fa-solid fa-clock-rotate-left"></i>

                                                    <span class="d-none d-lg-inline ms-1">
                                                        History
                                                    </span>

                                                </a>
                                            @endif

                                        </div>

                                    </td>

                                </tr>

                            @empty

                                <tr>

                                    <td colspan="9" class="text-center py-5">

                                        <div class="text-muted">

                                            <i class="fa-solid fa-file-circle-xmark fa-2x mb-3"></i>

                                            <div class="fw-semibold">

                                                No violation records found.

                                            </div>

                                            <small>

                                                Try adjusting your search or filters.

                                            </small>

                                        </div>

                                    </td>

                                </tr>
                            @endforelse

                        </tbody>

                    </table>

                </div>


                {{-- PAGINATION --}}

                @if ($violations->hasPages())
                    <div class="mt-4 d-flex justify-content-center">

                        {{ $violations->links() }}

                    </div>
                @endif

            </div>

        </div>

    </div>

@endsection
