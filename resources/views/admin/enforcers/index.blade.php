@extends('layouts.admin')

@section('title', 'Enforcers')

@section('content')

@if (session('success')) <div class="alert alert-success alert-dismissible fade show">
{{ session('success') }}


    <button
        type="button"
        class="btn-close"
        data-bs-dismiss="alert">
    </button>
</div>


@endif

<div class="container-fluid">


{{-- Page Header --}}
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0">Enforcers</h2>

    <a
        href="{{ route('enforcers.create') }}"
        class="btn btn-primary">
        <i class="fas fa-plus"></i>
        Add Enforcer
    </a>
</div>

{{-- Summary Cards --}}

<div class="d-flex justify-content-center gap-3 flex-wrap mb-4">


{{-- Total Enforcers --}}
<div style="width: 280px;">
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

{{-- Online Enforcers --}}
<div style="width: 280px;">
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

{{-- Offline Enforcers --}}
<div style="width: 280px;">
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


{{-- Enforcers Table --}}
<div class="card shadow-sm">

    <div class="card-body">

        <div class="table-responsive">

            <table class="table table-bordered table-hover align-middle">

                <thead class="table-dark">

                    <tr>
                        <th>Badge No.</th>
                        <th>Full Name</th>
                        <th>Contact</th>
                        <th>Position</th>
                        <th>Employment</th>
                        <th>Online</th>
                        <th width="100">Actions</th>
                    </tr>

                </thead>

                <tbody>

                    @forelse($enforcers as $enforcer)

                        <tr>

                            <td>
                                {{ $enforcer->badge_number }}
                            </td>

                            <td>
                                {{ $enforcer->last_name }},
                                {{ $enforcer->first_name }}
                                {{ $enforcer->middle_name }}
                            </td>

                            <td>
                                {{ $enforcer->contact_number ?: '—' }}
                            </td>

                            <td>
                                {{ $enforcer->position }}
                            </td>

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
                                    $enforcer->user->last_seen_at->greaterThanOrEqualTo(
                                        now()->subMinute()
                                    )
                                )

                                    <span class="badge rounded-pill bg-success px-3 py-2">
                                        <i
                                            class="fas fa-circle me-1"
                                            style="font-size: 7px;">
                                        </i>
                                        Online
                                    </span>

                                @else

                                    <span class="badge rounded-pill bg-secondary px-3 py-2">
                                        <i
                                            class="fas fa-circle me-1"
                                            style="font-size: 7px;">
                                        </i>
                                        Offline
                                    </span>

                                @endif

                            </td>

                            <td class="text-center">

                                <a
                                    href="{{ route('enforcers.edit', $enforcer->id) }}"
                                    class="btn btn-warning btn-sm">

                                    <i class="fas fa-edit me-1"></i>
                                    Edit

                                </a>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td
                                colspan="7"
                                class="text-center py-5">

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
