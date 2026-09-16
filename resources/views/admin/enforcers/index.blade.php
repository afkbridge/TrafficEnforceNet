@extends('layouts.admin')

@section('title', 'Enforcers')

@section('content')

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="container-fluid">

        <!-- Page Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="mb-0">Enforcers</h2>

            <a href="{{ route('enforcers.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Add Enforcer
            </a>
        </div>

        <!-- Summary Cards -->
        <div class="row justify-content-center g-3 mb-4">

            <!-- Total Enforcers -->
            <div class="col-xl-3 col-lg-3 col-md-5 col-sm-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body py-3 px-4">

                        <div class="d-flex align-items-center justify-content-between">

                            <div>
                                <div class="text-muted small fw-semibold mb-1">
                                    Total Enforcers
                                </div>

                                <h3 class="fw-bold mb-0 text-dark">
                                    {{ $totalEnforcers }}
                                </h3>
                            </div>

                            <div class="rounded-3 bg-primary bg-opacity-10 p-3">
                                <i class="fas fa-users text-primary fs-5"></i>
                            </div>

                        </div>

                    </div>
                </div>
            </div>


            <!-- Online Enforcers -->
            <div class="col-xl-3 col-lg-3 col-md-5 col-sm-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body py-3 px-4">

                        <div class="d-flex align-items-center justify-content-between">

                            <div>
                                <div class="text-muted small fw-semibold mb-1">
                                    Online Enforcers
                                </div>

                                <h3 class="fw-bold mb-0 text-success">
                                    {{ $onlineEnforcers }}
                                </h3>
                            </div>

                            <div class="rounded-3 bg-success bg-opacity-10 p-3">
                                <i class="fas fa-circle text-success fs-5"></i>
                            </div>

                        </div>

                    </div>
                </div>
            </div>


            <!-- Offline Enforcers -->
            <div class="col-xl-3 col-lg-3 col-md-5 col-sm-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body py-3 px-4">

                        <div class="d-flex align-items-center justify-content-between">

                            <div>
                                <div class="text-muted small fw-semibold mb-1">
                                    Offline Enforcers
                                </div>

                                <h3 class="fw-bold mb-0 text-secondary">
                                    {{ $offlineEnforcers }}
                                </h3>
                            </div>

                            <div class="rounded-3 bg-secondary bg-opacity-10 p-3">
                                <i class="fas fa-circle text-secondary fs-5"></i>
                            </div>

                        </div>

                    </div>
                </div>
            </div>

        </div>


        <!-- Search & Filters -->
        <div class="card shadow-sm mb-4">

            <div class="card-header bg-white">
                <h5 class="mb-0">Search & Filters</h5>
            </div>

            <div class="card-body">

                <form action="{{ route('enforcers.index') }}" method="GET">

                    <div class="row g-3">

                        <!-- Search -->
                        <div class="col-lg-8">

                            <label class="form-label fw-semibold">
                                Search Enforcer
                            </label>

                            <input type="text" name="search" class="form-control"
                                placeholder="Search by Badge Number, First Name or Last Name" value="{{ $search }}">

                        </div>

                        <!-- Buttons -->
                        <div class="col-lg-4">

                            <label class="form-label">&nbsp;</label>

                            <div class="d-flex justify-content-end gap-2">

                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-search"></i> Search
                                </button>

                                <a href="{{ route('enforcers.index') }}" class="btn btn-outline-secondary">
                                    Reset
                                </a>

                            </div>

                        </div>

                        <!-- Position -->
                        <div class="col-md-6">

                            <label class="form-label fw-semibold">
                                Position
                            </label>

                            <select name="position" class="form-select">

                                <option value="">All Positions</option>

                                <option value="Traffic Enforcer I"
                                    {{ $position == 'Traffic Enforcer I' ? 'selected' : '' }}>
                                    Traffic Enforcer I
                                </option>

                                <option value="Traffic Enforcer II"
                                    {{ $position == 'Traffic Enforcer II' ? 'selected' : '' }}>
                                    Traffic Enforcer II
                                </option>

                                <option value="Supervisor" {{ $position == 'Supervisor' ? 'selected' : '' }}>
                                    Supervisor
                                </option>

                                <option value="Chief" {{ $position == 'Chief' ? 'selected' : '' }}>
                                    Chief
                                </option>

                            </select>

                        </div>

                        <!-- Employment Status -->
                        <div class="col-md-6">

                            <label class="form-label fw-semibold">
                                Employment Status
                            </label>

                            <select name="status" class="form-select">

                                <option value="">All Status</option>

                                <option value="Active" {{ $status == 'Active' ? 'selected' : '' }}>
                                    Active
                                </option>

                                <option value="Inactive" {{ $status == 'Inactive' ? 'selected' : '' }}>
                                    Inactive
                                </option>

                            </select>

                        </div>

                    </div>

                </form>

            </div>

        </div>

        <!-- Enforcers Table -->
        <div class="card shadow-sm">

            <div class="card-body">

                <div class="table-responsive">

                    <table class="table table-bordered table-hover align-middle">

                        <thead class="table-dark">

                            <tr>
                                <th>Badge No.</th>
                                <th>Full Name</th>
                                <th>Contact</th>
                                <th>Email</th>
                                <th>Position</th>
                                <th>Employment</th>
                                <th>Online</th>
                                <th width="100">Actions</th>
                            </tr>

                        </thead>

                        <tbody>

                            @forelse($enforcers as $enforcer)
                                <tr>

                                    <td>{{ $enforcer->badge_number }}</td>

                                    <td>
                                        {{ $enforcer->last_name }},
                                        {{ $enforcer->first_name }}
                                        {{ $enforcer->middle_name }}
                                    </td>

                                    <td>{{ $enforcer->contact_number }}</td>

                                    <td>{{ $enforcer->email }}</td>

                                    <td>{{ $enforcer->position }}</td>

                                    <td>

                                        @if ($enforcer->employment_status == 'Active')
                                            <span class="badge rounded-pill bg-success px-3 py-2">
                                                <i class="fas fa-check-circle me-1"></i>
                                                Active
                                            </span>
                                        @else
                                            <span class="badge rounded-pill bg-danger px-3 py-2">
                                                <i class="fas fa-times-circle me-1"></i>
                                                Inactive
                                            </span>
                                        @endif

                                    </td>

                                    <td>
                                        @if (
                                            $enforcer->user &&
                                                $enforcer->user->last_seen_at &&
                                                $enforcer->user->last_seen_at->greaterThanOrEqualTo(now()->subMinute()))
                                            <span class="badge rounded-pill bg-success px-3 py-2">
                                                <i class="fas fa-circle me-1" style="font-size: 7px;"></i>
                                                Online
                                            </span>
                                        @else
                                            <span class="badge rounded-pill bg-secondary px-3 py-2">
                                                <i class="fas fa-circle me-1" style="font-size: 7px;"></i>
                                                Offline
                                            </span>
                                        @endif
                                    </td>

                                    <td class="text-center">

                                        <a href="{{ route('enforcers.edit', $enforcer->id) }}"
                                            class="btn btn-warning btn-sm">

                                            <i class="fas fa-edit me-1"></i>
                                            Edit

                                        </a>

                                    </td>

                                </tr>

                            @empty

                                <tr>

                                    <td colspan="8" class="text-center py-5">

                                        <i class="fas fa-user-slash fa-2x text-muted mb-3"></i>

                                        <br>

                                        No enforcers found.

                                    </td>

                                </tr>
                            @endforelse

                        </tbody>

                    </table>

                </div>

                {{ $enforcers->links() }}

            </div>

        </div>

    </div>

@endsection
