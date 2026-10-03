@extends('layouts.admin')

@section('title', 'Enforcer Account')

@section('content')

<div class="container-fluid px-4 pt-3">


{{-- ===================================================== --}}
{{-- Header --}}
{{-- ===================================================== --}}

<div class="d-flex justify-content-between align-items-center mb-4">

    <div>
        <h3 class="fw-bold mb-1">
            <i class="fas fa-user-circle text-primary me-2"></i>
            Enforcer Account
        </h3>

        <p class="text-muted mb-0">
            View the account and employment information linked to this enforcer.
        </p>
    </div>

    <a
        href="{{ route('enforcers.index') }}"
        class="btn btn-secondary rounded-pill px-4"
    >
        <i class="fas fa-arrow-left me-2"></i>
        Back
    </a>

</div>


<div class="row">

    {{-- ================================================= --}}
    {{-- LEFT PROFILE --}}
    {{-- ================================================= --}}

    <div class="col-lg-4 mb-4">

        <div class="card border-0 shadow-sm h-100">

            <div class="card-body text-center p-4">

                {{-- Profile Icon --}}
                <div class="mb-4">

                    <div
                        class="rounded-circle bg-primary text-white d-inline-flex justify-content-center align-items-center"
                        style="width:120px;height:120px;"
                    >
                        <i class="fas fa-user-shield fa-4x"></i>
                    </div>

                </div>


                {{-- Full Name --}}
                <h3 class="fw-bold mb-1">

                    {{ $enforcer->first_name }}

                    @if($enforcer->middle_name)
                        {{ $enforcer->middle_name }}
                    @endif

                    {{ $enforcer->last_name }}

                </h3>


                {{-- Position --}}
                <p class="text-muted mb-3">

                    {{ $enforcer->position ?? 'Position Not Set' }}

                </p>


                {{-- Badge Number --}}
                <span class="badge bg-primary px-3 py-2 fs-6 rounded-pill">

                    <i class="fas fa-id-badge me-1"></i>

                    Badge #

                    {{ $enforcer->badge_number ?: 'Not Set' }}

                </span>


                <hr class="my-4">


                {{-- Position --}}
                <div class="mb-3">

                    <small class="text-muted d-block mb-1">
                        Position
                    </small>

                    @if($enforcer->position)

                        <span class="badge bg-info text-dark px-3 py-2 rounded-pill">

                            <i class="fas fa-briefcase me-1"></i>

                            {{ $enforcer->position }}

                        </span>

                    @else

                        <span class="badge bg-secondary px-3 py-2 rounded-pill">

                            Not Set

                        </span>

                    @endif

                </div>


                {{-- Employment Status --}}
                <div class="mb-4">

                    <small class="text-muted d-block mb-1">
                        Employment Status
                    </small>


                    @if($enforcer->employment_status === 'Permanent')

                        <span class="badge bg-success px-3 py-2 rounded-pill">

                            <i class="fas fa-check-circle me-1"></i>

                            Permanent

                        </span>

                    @elseif($enforcer->employment_status === 'Job Order')

                        <span class="badge bg-info text-dark px-3 py-2 rounded-pill">

                            <i class="fas fa-user-clock me-1"></i>

                            Job Order

                        </span>

                    @else

                        <span class="badge bg-secondary px-3 py-2 rounded-pill">

                            Not Set

                        </span>

                    @endif

                </div>


                <hr>


                {{-- Basic Account Details --}}
                <div class="text-start">

                    {{-- Username --}}
                    <div class="mb-3">

                        <small class="text-muted d-block">
                            Username
                        </small>

                        <div class="fw-semibold">

                            {{ $enforcer->user->username ?? '—' }}

                        </div>

                    </div>


                    {{-- Account Status --}}
                    <div>

                        <small class="text-muted d-block">
                            Account Status
                        </small>


                        @if($enforcer->user)

                            @if($enforcer->user->account_status === 'Active')

                                <span class="badge bg-success px-3 py-2 rounded-pill">

                                    <i class="fas fa-check-circle me-1"></i>

                                    Active

                                </span>

                            @else

                                <span class="badge bg-danger px-3 py-2 rounded-pill">

                                    <i class="fas fa-ban me-1"></i>

                                    Inactive

                                </span>

                            @endif

                        @else

                            <span class="badge bg-secondary px-3 py-2 rounded-pill">

                                Not Set

                            </span>

                        @endif

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- ================================================= --}}
    {{-- RIGHT SIDE --}}
    {{-- ================================================= --}}

    <div class="col-lg-8">


        {{-- ================================================= --}}
        {{-- ACCOUNT INFORMATION --}}
        {{-- ================================================= --}}

        <div class="card border-0 shadow-sm mb-4">

            <div class="card-header bg-primary text-white py-3">

                <h5 class="mb-0">

                    <i class="fas fa-key me-2"></i>

                    Account Information

                </h5>

            </div>


            <div class="card-body p-4">


                {{-- Account ID --}}
                <div class="row mb-4">

                    <div class="col-md-4 text-muted">
                        Account ID
                    </div>

                    <div class="col-md-8 fw-semibold">

                        {{ $enforcer->user->id ?? '—' }}

                    </div>

                </div>


                {{-- Username --}}
                <div class="row mb-4">

                    <div class="col-md-4 text-muted">
                        Username
                    </div>

                    <div class="col-md-8 fw-semibold">

                        {{ $enforcer->user->username ?? '—' }}

                    </div>

                </div>


                {{-- Role --}}
                <div class="row mb-4">

                    <div class="col-md-4 text-muted">
                        Role
                    </div>

                    <div class="col-md-8">

                        @if(isset($enforcer->user->role))

                            <span class="badge bg-success px-3 py-2 rounded-pill">

                                {{ $enforcer->user->role->name }}

                            </span>

                        @else

                            <span class="badge bg-success px-3 py-2 rounded-pill">

                                POSO Enforcer

                            </span>

                        @endif

                    </div>

                </div>


                {{-- Password --}}
                <div class="row mb-4">

                    <div class="col-md-4 text-muted">
                        Password
                    </div>

                    <div class="col-md-8 fw-semibold text-secondary">

                        ••••••••••••••••

                    </div>

                </div>


                {{-- Account Status --}}
                <div class="row">

                    <div class="col-md-4 text-muted">
                        Account Status
                    </div>

                    <div class="col-md-8">

                        @if($enforcer->user)

                            @if($enforcer->user->account_status === 'Active')

                                <span class="badge bg-success px-3 py-2 rounded-pill">

                                    <i class="fas fa-check-circle me-1"></i>

                                    Active

                                </span>

                            @else

                                <span class="badge bg-danger px-3 py-2 rounded-pill">

                                    <i class="fas fa-ban me-1"></i>

                                    Inactive

                                </span>

                            @endif

                        @else

                            <span class="badge bg-secondary px-3 py-2 rounded-pill">

                                Not Set

                            </span>

                        @endif

                    </div>

                </div>

            </div>

        </div>


        {{-- ================================================= --}}
        {{-- EMPLOYMENT INFORMATION --}}
        {{-- ================================================= --}}

        <div class="card border-0 shadow-sm mb-4">

            <div class="card-header bg-light py-3">

                <h5 class="mb-0">

                    <i class="fas fa-briefcase me-2 text-primary"></i>

                    Employment Information

                </h5>

            </div>


            <div class="card-body p-4">


                {{-- Position --}}
                <div class="row mb-4">

                    <div class="col-md-4 text-muted">
                        Position
                    </div>

                    <div class="col-md-8">

                        @if($enforcer->position)

                            <span class="badge bg-info text-dark px-3 py-2 rounded-pill">

                                {{ $enforcer->position }}

                            </span>

                        @else

                            <span class="text-muted">
                                Not Set
                            </span>

                        @endif

                    </div>

                </div>


                {{-- Employment Status --}}
                <div class="row mb-4">

                    <div class="col-md-4 text-muted">
                        Employment Status
                    </div>

                    <div class="col-md-8">

                        @if($enforcer->employment_status === 'Permanent')

                            <span class="badge bg-success px-3 py-2 rounded-pill">

                                <i class="fas fa-check-circle me-1"></i>

                                Permanent

                            </span>

                        @elseif($enforcer->employment_status === 'Job Order')

                            <span class="badge bg-info text-dark px-3 py-2 rounded-pill">

                                <i class="fas fa-user-clock me-1"></i>

                                Job Order

                            </span>

                        @else

                            <span class="text-muted">
                                Not Set
                            </span>

                        @endif

                    </div>

                </div>


                {{-- Badge Number --}}
                <div class="row mb-4">

                    <div class="col-md-4 text-muted">
                        Badge Number
                    </div>

                    <div class="col-md-8 fw-semibold">

                        {{ $enforcer->badge_number ?: '—' }}

                    </div>

                </div>


                {{-- Contact Number --}}
                <div class="row">

                    <div class="col-md-4 text-muted">
                        Contact Number
                    </div>

                    <div class="col-md-8 fw-semibold">

                        {{ $enforcer->contact_number ?: '—' }}

                    </div>

                </div>

            </div>

        </div>


        {{-- ================================================= --}}
        {{-- SECURITY INFORMATION --}}
        {{-- ================================================= --}}

        <div class="card border-0 shadow-sm">

            <div class="card-header bg-warning py-3">

                <h5 class="mb-0">

                    <i class="fas fa-shield-alt me-2"></i>

                    Security Information

                </h5>

            </div>


            <div class="card-body">


                <div class="mb-3">

                    <i class="fas fa-lock text-success me-2"></i>

                    Passwords are securely encrypted and cannot be viewed.

                </div>


                <div class="mb-3">

                    <i class="fas fa-user-shield text-primary me-2"></i>

                    Only administrators can reset an enforcer's password.

                </div>


                <div>

                    <i class="fas fa-ban text-danger me-2"></i>

                    Disabled accounts cannot log in to the system.

                </div>

            </div>

        </div>

    </div>

</div>


</div>

@endsection
