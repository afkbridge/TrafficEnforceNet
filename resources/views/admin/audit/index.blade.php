@extends('layouts.admin')

@section('title', 'Audit Monitoring | TrafficEnforceNet')

@section('content')

<div class="container-fluid">


{{-- ==========================================================
     PAGE HEADER
     ========================================================== --}}
<div class="d-flex justify-content-between align-items-center mb-4">

    <div>
        <h1 class="fw-bold mb-1">Audit Monitoring</h1>
        <p class="text-muted mb-0">
            Monitor user activities and important system transactions.
        </p>
    </div>

</div>

{{-- ==========================================================
     FILTERS
     ========================================================== --}}
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body">

        <form method="GET" action="{{ route('admin.audit.index') }}">

            <div class="row g-3">

                {{-- Search --}}
                <div class="col-md-4">
                    <label class="form-label fw-semibold">
                        Search
                    </label>

                    <input
                        type="text"
                        name="search"
                        class="form-control"
                        placeholder="Search user, action, description..."
                        value="{{ $search }}"
                    >
                </div>

                {{-- Action --}}
                <div class="col-md-2">
                    <label class="form-label fw-semibold">
                        Action
                    </label>

                    <select name="action" class="form-select">

                        <option value="">
                            All Actions
                        </option>

                        @foreach ($actions as $item)

                            <option
                                value="{{ $item }}"
                                {{ $action === $item ? 'selected' : '' }}
                            >
                                {{ $item }}
                            </option>

                        @endforeach

                    </select>
                </div>

                {{-- User --}}
                <div class="col-md-2">
                    <label class="form-label fw-semibold">
                        User
                    </label>

                    <select name="user_id" class="form-select">

                        <option value="">
                            All Users
                        </option>

                        @foreach ($users as $user)

                            <option
                                value="{{ $user->id }}"
                                {{ (string) $userId === (string) $user->id ? 'selected' : '' }}
                            >
                                {{ $user->name }}
                            </option>

                        @endforeach

                    </select>
                </div>

                {{-- Date From --}}
                <div class="col-md-2">
                    <label class="form-label fw-semibold">
                        Date From
                    </label>

                    <input
                        type="date"
                        name="date_from"
                        class="form-control"
                        value="{{ $dateFrom }}"
                    >
                </div>

                {{-- Date To --}}
                <div class="col-md-2">
                    <label class="form-label fw-semibold">
                        Date To
                    </label>

                    <input
                        type="date"
                        name="date_to"
                        class="form-control"
                        value="{{ $dateTo }}"
                    >
                </div>

            </div>

            {{-- Filter Buttons --}}
            <div class="d-flex gap-2 mt-3">

                <button
                    type="submit"
                    class="btn btn-primary"
                >
                    <i class="fas fa-filter me-1"></i>
                    Apply Filters
                </button>

                <a
                    href="{{ route('admin.audit.index') }}"
                    class="btn btn-outline-secondary"
                >
                    <i class="fas fa-rotate-left me-1"></i>
                    Reset
                </a>

            </div>

        </form>

    </div>
</div>

{{-- ==========================================================
     AUDIT LOG TABLE
     ========================================================== --}}
<div class="card border-0 shadow-sm">

    <div class="card-body p-0">

        <div class="table-responsive">

            <table class="table table-hover align-middle mb-0">

                <thead class="table-light">

                    <tr>

                        <th class="px-4">
                            Date & Time
                        </th>

                        <th>
                            User
                        </th>

                        <th>
                            Role
                        </th>

                        <th>
                            Action
                        </th>

                        <th>
                            Description
                        </th>

                    </tr>

                </thead>

                <tbody>

                    @forelse ($auditLogs as $log)

                        <tr>

                            {{-- Date & Time --}}
                            <td class="px-4">

                                <div class="fw-semibold">
                                    {{ $log->created_at->format('M d, Y') }}
                                </div>

                                <small class="text-muted">
                                    {{ $log->created_at->format('h:i A') }}
                                </small>

                            </td>

                            {{-- User --}}
                            <td>

                                @if ($log->user)

                                    <div class="fw-semibold">
                                        {{ $log->user->name }}
                                    </div>

                                @else

                                    <span class="text-muted">
                                        System / Unknown
                                    </span>

                                @endif

                            </td>

                            {{-- Role --}}
                            <td>

                                @if ($log->user && $log->user->role)

                                    <span class="badge bg-secondary">
                                        {{ $log->user->role->name }}
                                    </span>

                                @else

                                    <span class="text-muted">
                                        N/A
                                    </span>

                                @endif

                            </td>

                            {{-- Action --}}
                            <td>

                                @php

                                    $actionClass = match ($log->action) {

                                        'LOGIN' => 'bg-success',

                                        'LOGOUT' => 'bg-secondary',

                                        'CREATE' => 'bg-primary',

                                        'UPDATE' => 'bg-warning text-dark',

                                        'STATUS_UPDATE' => 'bg-info text-dark',

                                        'DELETE' => 'bg-danger',

                                        default => 'bg-dark',

                                    };

                                @endphp

                                <span class="badge {{ $actionClass }}">
                                    {{ $log->action }}
                                </span>

                            </td>

                            {{-- Description --}}
                            <td>

                                <span>
                                    {{ $log->description ?? 'No description available.' }}
                                </span>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td
                                colspan="5"
                                class="text-center py-5"
                            >

                                <div class="text-muted">

                                    <i class="fas fa-clock-rotate-left fa-2x mb-3"></i>

                                    <p class="mb-0">
                                        No audit records found.
                                    </p>

                                </div>

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

        {{-- ==================================================
             PAGINATION
             ================================================== --}}
        @if ($auditLogs->hasPages())

            <div class="p-3 border-top">

                {{ $auditLogs->links() }}

            </div>

        @endif

    </div>

</div>


</div>

@endsection
