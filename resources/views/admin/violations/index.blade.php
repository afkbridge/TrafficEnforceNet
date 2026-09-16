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

            <div>
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


        {{-- FILTER CARD --}}
        <div class="card shadow-sm mb-4">
            <div class="card-body">

                <form method="GET" action="{{ route('violations.index') }}" class="row g-3 align-items-end">

                    {{-- SEARCH --}}
                    <div class="col-md-4">

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
                    <div class="col-md-2">

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
                    <div class="col-md-2">

                        <label for="date_from" class="form-label fw-semibold">
                            From Date
                        </label>

                        <input type="date" name="date_from" id="date_from" class="form-control"
                            value="{{ request('date_from') }}">

                    </div>


                    {{-- TO DATE --}}
                    <div class="col-md-2">

                        <label for="date_to" class="form-label fw-semibold">
                            To Date
                        </label>

                        <input type="date" name="date_to" id="date_to" class="form-control"
                            value="{{ request('date_to') }}">

                    </div>


                    {{-- BUTTONS --}}
                    <div class="col-md-2 d-flex gap-2">

                        <button type="submit" class="btn btn-primary flex-fill">

                            <i class="fa-solid fa-filter me-1"></i>
                            Filter

                        </button>

                        <a href="{{ route('violations.index') }}" class="btn btn-light border">

                            <i class="fa-solid fa-rotate-left"></i>

                        </a>

                    </div>

                </form>

            </div>
        </div>


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

                                        {{ $violation->violationType->name ?? 'N/A' }}

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


                                    {{-- STATUS --}}
                                    <td>

                                        @php
                                            $status = strtolower($violation->status ?? '');
                                        @endphp

                                        @if ($status === 'pending')
                                            <span class="badge bg-warning text-dark">
                                                Pending
                                            </span>
                                        @elseif($status === 'settled')
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
                                            <a href="{{ route('violations.show', $violation->id) }}"
                                                class="btn btn-sm btn-primary" title="View violation">
                                                <i class="fa-solid fa-eye"></i>
                                                <span class="d-none d-lg-inline ms-1">
                                                    View
                                                </span>
                                            </a>
                                        </div>
                                    </td>

                                </tr>

                            @empty

                                <tr>

                                    <td colspan="8" class="text-center py-5">

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
