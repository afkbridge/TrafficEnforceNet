@extends('layouts.admin')

@section('title', 'Enforcers')

@section('content')

{{-- Success Message --}}

@if (session('success'))

    <div class="alert alert-success alert-dismissible fade show">
        <i class="fas fa-check-circle me-2"></i>
        {{ session('success') }}

        <button
            type="button"
            class="btn-close"
            data-bs-dismiss="alert"
        ></button>
    </div>

@endif

<div class="container-fluid">

    {{-- ===================================================== --}}
    {{-- Page Header --}}
    {{-- ===================================================== --}}

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h2 class="mb-1 fw-bold">
                Enforcers
            </h2>

            <p class="text-muted mb-0">
                Manage POSO traffic enforcers and their assigned accounts.
            </p>

        </div>

        <a
            href="{{ route('enforcers.create') }}"
            class="btn btn-primary"
        >
            <i class="fas fa-plus me-1"></i>
            Add Enforcer
        </a>

    </div>


    {{-- ===================================================== --}}
    {{-- Summary Cards --}}
    {{-- ===================================================== --}}

    <div class="d-flex justify-content-center mb-4">

        <div
            class="row g-3 w-100"
            style="max-width: 900px;"
        >

            {{-- Total Enforcers --}}

            <div class="col-md-4">

                <div
                    class="card border-0 h-100"
                    style="
                        border-radius: 16px;
                        box-shadow: 0 3px 12px rgba(0, 0, 0, 0.06);
                    "
                >

                    <div class="card-body py-3 px-4">

                        <div class="d-flex align-items-center justify-content-center">

                            <div
                                class="d-flex align-items-center justify-content-center me-3"
                                style="
                                    width: 46px;
                                    height: 46px;
                                    border-radius: 12px;
                                    background: #eef4ff;
                                    color: #1d5fbf;
                                    flex-shrink: 0;
                                "
                            >
                                <i class="fas fa-users"></i>
                            </div>

                            <div>

                                <div class="text-muted small fw-medium">
                                    Total Enforcers
                                </div>

                                <div
                                    class="fw-bold"
                                    style="
                                        font-size: 25px;
                                        line-height: 1.1;
                                    "
                                >
                                    {{ $totalEnforcers }}
                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            </div>


            {{-- Online Enforcers --}}

            <div class="col-md-4">

                <div
                    class="card border-0 h-100"
                    style="
                        border-radius: 16px;
                        box-shadow: 0 3px 12px rgba(0, 0, 0, 0.06);
                    "
                >

                    <div class="card-body py-3 px-4">

                        <div class="d-flex align-items-center justify-content-center">

                            <div
                                class="d-flex align-items-center justify-content-center me-3"
                                style="
                                    width: 46px;
                                    height: 46px;
                                    border-radius: 12px;
                                    background: #ecfdf3;
                                    color: #198754;
                                    flex-shrink: 0;
                                "
                            >
                                <i class="fas fa-user-check"></i>
                            </div>

                            <div>

                                <div class="text-muted small fw-medium">
                                    Online Enforcers
                                </div>

                                <div
                                    class="fw-bold text-success"
                                    style="
                                        font-size: 25px;
                                        line-height: 1.1;
                                    "
                                >
                                    {{ $onlineEnforcers }}
                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            </div>


            {{-- Offline Enforcers --}}

            <div class="col-md-4">

                <div
                    class="card border-0 h-100"
                    style="
                        border-radius: 16px;
                        box-shadow: 0 3px 12px rgba(0, 0, 0, 0.06);
                    "
                >

                    <div class="card-body py-3 px-4">

                        <div class="d-flex align-items-center justify-content-center">

                            <div
                                class="d-flex align-items-center justify-content-center me-3"
                                style="
                                    width: 46px;
                                    height: 46px;
                                    border-radius: 12px;
                                    background: #f1f3f5;
                                    color: #6c757d;
                                    flex-shrink: 0;
                                "
                            >
                                <i class="fas fa-user-clock"></i>
                            </div>

                            <div>

                                <div class="text-muted small fw-medium">
                                    Offline Enforcers
                                </div>

                                <div
                                    class="fw-bold text-secondary"
                                    style="
                                        font-size: 25px;
                                        line-height: 1.1;
                                    "
                                >
                                    {{ $offlineEnforcers }}
                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- ===================================================== --}}
    {{-- Search & Filters --}}
    {{-- ===================================================== --}}

    <div class="card border-0 shadow-sm mb-4">

        <div class="card-header bg-white border-bottom py-3">

            <div class="d-flex align-items-center">

                <div
                    class="d-flex align-items-center justify-content-center bg-primary bg-opacity-10 text-primary rounded-3 me-3"
                    style="
                        width: 42px;
                        height: 42px;
                    "
                >
                    <i class="fas fa-search"></i>
                </div>

                <div>

                    <h5 class="mb-0 fw-bold">
                        Search & Filters
                    </h5>

                    <small class="text-muted">
                        Search for an enforcer or filter the list by position and employment status.
                    </small>

                </div>

            </div>

        </div>


        <div class="card-body p-4">

            <form
                action="{{ route('enforcers.index') }}"
                method="GET"
            >

                <div class="row g-3 align-items-end">

                    {{-- Search --}}

                    <div class="col-lg-6">

                        <label
                            for="search"
                            class="form-label fw-semibold"
                        >
                            Search Enforcer
                        </label>

                        <div class="input-group">

                            <span class="input-group-text bg-white">
                                <i class="fas fa-search text-muted"></i>
                            </span>

                            <input
                                type="text"
                                id="search"
                                name="search"
                                class="form-control"
                                placeholder="Username, name, badge number, or contact number"
                                value="{{ $search ?? '' }}"
                            >

                        </div>

                    </div>


                    {{-- Position Filter --}}

                    <div class="col-lg-3 col-md-6">

                        <label
                            for="position"
                            class="form-label fw-semibold"
                        >
                            Position
                        </label>

                        <select
                            name="position"
                            id="position"
                            class="form-select"
                        >

                            <option value="">
                                All Positions
                            </option>

                            <option
                                value="Traffic Enforcer"
                                {{ ($position ?? '') == 'Traffic Enforcer' ? 'selected' : '' }}
                            >
                                Traffic Enforcer
                            </option>

                            <option
                                value="Traffic Aide"
                                {{ ($position ?? '') == 'Traffic Aide' ? 'selected' : '' }}
                            >
                                Traffic Aide
                            </option>

                        </select>

                    </div>


                    {{-- Employment Status Filter --}}

                    <div class="col-lg-3 col-md-6">

                        <label
                            for="status"
                            class="form-label fw-semibold"
                        >
                            Employment Status
                        </label>

                        <select
                            name="status"
                            id="status"
                            class="form-select"
                        >

                            <option value="">
                                All Employment Status
                            </option>

                            <option
                                value="Active"
                                {{ ($status ?? '') == 'Active' ? 'selected' : '' }}
                            >
                                Active
                            </option>

                            <option
                                value="Inactive"
                                {{ ($status ?? '') == 'Inactive' ? 'selected' : '' }}
                            >
                                Inactive
                            </option>

                        </select>

                    </div>


                    {{-- Buttons --}}

                    <div class="col-12">

                        <div class="d-flex justify-content-end gap-2 pt-2">

                            <a
                                href="{{ route('enforcers.index') }}"
                                class="btn btn-outline-secondary px-4"
                            >
                                <i class="fas fa-undo me-1"></i>
                                Reset
                            </a>

                            <button
                                type="submit"
                                class="btn btn-primary px-4"
                            >
                                <i class="fas fa-search me-1"></i>
                                Search
                            </button>

                        </div>

                    </div>

                </div>

            </form>

        </div>

    </div>


    {{-- ===================================================== --}}
    {{-- Enforcers Table --}}
    {{-- ===================================================== --}}

    <div class="card border-0 shadow-sm">

        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-hover align-middle mb-0">

                    <thead class="table-light">

                        <tr>

                            <th class="px-3 py-3">
                                Full Name
                            </th>

                            <th class="py-3">
                                Employment
                            </th>

                            <th class="py-3">
                                Account
                            </th>

                            <th class="py-3">
                                Online
                            </th>

                            <th
                                class="py-3 text-center"
                                width="110"
                            >
                                Actions
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        @forelse($enforcers as $enforcer)

                            @php
                                /*
                                |--------------------------------------------------------------------------
                                | Enforcer Profile
                                |--------------------------------------------------------------------------
                                |
                                | The User record is the main record now.
                                | The Enforcer profile may or may not exist.
                                |
                                */
                                $profile = $enforcer->enforcer;
                            @endphp

                            <tr>

                                {{-- ================================================= --}}
                                {{-- Full Name --}}
                                {{-- ================================================= --}}

                                <td class="px-3">

                                    @if ($profile)

                                        <strong>
                                            {{ $profile->last_name }},
                                            {{ $profile->first_name }}

                                            @if ($profile->middle_name)
                                                {{ $profile->middle_name }}
                                            @endif
                                        </strong>

                                    @else

                                        <strong>
                                            {{ $enforcer->name }}
                                        </strong>

                                    @endif

                                    @if (!$profile)

                                        <div class="mt-1">

                                            <span
                                                class="badge rounded-pill bg-light text-muted border"
                                            >
                                                No Enforcer Profile
                                            </span>

                                        </div>

                                    @endif

                                </td>


                                {{-- ================================================= --}}
                                {{-- Employment --}}
                                {{-- ================================================= --}}

                                <td>

                                    @if ($profile && $profile->employment_status === 'Active')

                                        <span
                                            class="badge rounded-pill bg-success bg-opacity-10 text-success px-3 py-2"
                                        >
                                            Active
                                        </span>

                                    @elseif ($profile && $profile->employment_status === 'Inactive')

                                        <span
                                            class="badge rounded-pill bg-secondary bg-opacity-10 text-secondary px-3 py-2"
                                        >
                                            Inactive
                                        </span>

                                    @else

                                        <span class="text-muted">
                                            —
                                        </span>

                                    @endif

                                </td>


                                {{-- ================================================= --}}
                                {{-- Account Status --}}
                                {{-- ================================================= --}}

                                <td>

                                    @if ($enforcer->account_status === 'Active')

                                        <span
                                            class="badge rounded-pill bg-success bg-opacity-10 text-success px-3 py-2"
                                        >
                                            <i class="fas fa-check-circle me-1"></i>
                                            Active
                                        </span>

                                    @else

                                        <span
                                            class="badge rounded-pill bg-secondary bg-opacity-10 text-secondary px-3 py-2"
                                        >
                                            <i class="fas fa-ban me-1"></i>
                                            Disabled
                                        </span>

                                    @endif

                                </td>


                                {{-- ================================================= --}}
                                {{-- Online Status --}}
                                {{-- ================================================= --}}

                                <td>

                                    @if (
                                        $enforcer->last_seen_at &&
                                        \Carbon\Carbon::parse($enforcer->last_seen_at)->gte(now()->subMinute())
                                    )

                                        <span
                                            class="badge rounded-pill bg-success bg-opacity-10 text-success px-3 py-2"
                                        >
                                            <i
                                                class="fas fa-circle me-1"
                                                style="font-size: 7px;"
                                            ></i>
                                            Online
                                        </span>

                                    @else

                                        <span
                                            class="badge rounded-pill bg-secondary bg-opacity-10 text-secondary px-3 py-2"
                                        >
                                            <i
                                                class="fas fa-circle me-1"
                                                style="font-size: 7px;"
                                            ></i>
                                            Offline
                                        </span>

                                    @endif

                                </td>


                                {{-- ================================================= --}}
                                {{-- Actions --}}
                                {{-- ================================================= --}}

                                <td>

                                    <div class="d-flex justify-content-center gap-1">

                                        @if ($profile)

                                            {{-- View --}}

                                            <a
                                                href="{{ route('enforcers.show', $profile->id) }}"
                                                class="btn btn-sm btn-outline-primary"
                                                title="View Account"
                                            >
                                                <i class="fas fa-eye"></i>
                                            </a>


                                            {{-- Edit --}}

                                            <a
                                                href="{{ route('enforcers.edit', $profile->id) }}"
                                                class="btn btn-sm btn-outline-secondary"
                                                title="Edit Enforcer"
                                            >
                                                <i class="fas fa-edit"></i>
                                            </a>


                                            {{-- Delete --}}

                                            <form
                                                action="{{ route('enforcers.destroy', $profile->id) }}"
                                                method="POST"
                                                class="d-inline"
                                            >

                                                @csrf

                                                @method('DELETE')

                                                <button
                                                    type="submit"
                                                    class="btn btn-sm btn-outline-danger"
                                                    title="Delete Enforcer"
                                                    onclick="return confirm('Are you sure you want to delete this enforcer?')"
                                                >
                                                    <i class="fas fa-trash"></i>
                                                </button>

                                            </form>

                                        @else

                                            <span
                                                class="badge rounded-pill bg-light text-muted border px-3 py-2"
                                                title="This account does not have an Enforcer profile yet."
                                            >
                                                No Profile
                                            </span>

                                        @endif

                                    </div>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td
                                    colspan="5"
                                    class="text-center py-5"
                                >

                                    <i
                                        class="fas fa-user-slash fa-2x text-muted mb-3"
                                    ></i>

                                    <br>

                                    <span class="text-muted">
                                        No enforcers found.
                                    </span>

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>


            {{-- Pagination --}}

            <div class="p-3">
                {{ $enforcers->links() }}
            </div>

        </div>

    </div>

</div>

@endsection