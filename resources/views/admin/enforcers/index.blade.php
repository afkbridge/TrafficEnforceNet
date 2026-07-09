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
        <div class="row mb-4">

            <div class="col-lg-3 col-md-6 mb-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-muted mb-1">Total Enforcers</h6>
                                <h2 class="fw-bold mb-0">{{ $totalEnforcers }}</h2>
                            </div>
                            <div class="fs-1 text-primary">
                                <i class="fas fa-users"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 mb-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-muted mb-1">Active Employees</h6>
                                <h2 class="fw-bold text-success mb-0">{{ $activeEnforcers }}</h2>
                            </div>
                            <div class="fs-1 text-success">
                                <i class="fas fa-user-check"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 mb-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-muted mb-1">Inactive Employees</h6>
                                <h2 class="fw-bold text-danger mb-0">{{ $inactiveEnforcers }}</h2>
                            </div>
                            <div class="fs-1 text-danger">
                                <i class="fas fa-user-times"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 mb-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-muted mb-1">Online Enforcers</h6>
                                <h2 class="fw-bold text-info mb-0">{{ $onlineEnforcers }}</h2>
                            </div>
                            <div class="fs-1 text-info">
                                <i class="fas fa-circle"></i>
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
                                <th width="180">Actions</th>
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

                                        <span class="badge bg-secondary">
                                            Offline
                                        </span>

                                    </td>

                                    <td>

                                        <div class="d-flex gap-2">

                                            <a href="{{ route('enforcers.edit', $enforcer->id) }}"
                                                class="btn btn-warning btn-sm">

                                                <i class="fas fa-edit"></i>

                                            </a>

                                            <form action="{{ route('enforcers.destroy', $enforcer->id) }}" method="POST">

                                                @csrf
                                                @method('DELETE')

                                                <button type="submit" class="btn btn-danger btn-sm"
                                                    onclick="return confirm('Are you sure you want to delete this enforcer?')">

                                                    <i class="fas fa-trash"></i>

                                                </button>

                                            </form>

                                        </div>

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
