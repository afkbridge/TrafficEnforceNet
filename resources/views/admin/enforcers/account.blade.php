@extends('layouts.admin')

@section('title', 'Enforcer Account')

@section('content')

<div class="container-fluid px-4 py-4">


{{-- Header --}}
<div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-semibold mb-1">Enforcer Account</h4>
        <p class="text-muted mb-0">
            View the enforcer's personal and account information.
        </p>
    </div>

    <a href="{{ route('enforcers.index') }}"
       class="btn btn-outline-secondary mt-2 mt-md-0">
        <i class="fas fa-arrow-left me-2"></i>
        Back to Enforcers
    </a>
</div>


{{-- Main Card --}}
<div class="card border-0 shadow-sm">
    <div class="card-body p-4 p-md-5">

        {{-- Enforcer Header --}}
        <div class="pb-4 mb-4 border-bottom">

            <div class="text-muted small fw-semibold mb-2">
                ENFORCER
            </div>

            <h4 class="fw-semibold mb-1 text-break">
                {{ $enforcer->first_name }}
                @if($enforcer->middle_name)
                    {{ $enforcer->middle_name }}
                @endif
                {{ $enforcer->last_name }}
            </h4>

            <div class="text-muted">
                {{ $enforcer->position ?? 'Position Not Set' }}
            </div>

        </div>


        {{-- Personal Information --}}
        <section class="mb-5">

            <h6 class="fw-semibold mb-3">
                <i class="fas fa-id-card text-primary me-2"></i>
                Personal Information
            </h6>

            <div class="row">

                <div class="col-md-6">
                    <div class="detail-row">
                        <span class="detail-label">First Name</span>
                        <span class="detail-value">
                            {{ $enforcer->first_name ?: 'Not Set' }}
                        </span>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="detail-row">
                        <span class="detail-label">Middle Name</span>
                        <span class="detail-value">
                            {{ $enforcer->middle_name ?: 'Not Set' }}
                        </span>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="detail-row">
                        <span class="detail-label">Last Name</span>
                        <span class="detail-value">
                            {{ $enforcer->last_name ?: 'Not Set' }}
                        </span>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="detail-row">
                        <span class="detail-label">Position</span>
                        <span class="detail-value">
                            {{ $enforcer->position ?: 'Not Set' }}
                        </span>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="detail-row">
                        <span class="detail-label">Badge Number</span>
                        <span class="detail-value">
                            {{ $enforcer->badge_number ?: 'Not Set' }}
                        </span>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="detail-row">
                        <span class="detail-label">Contact Number</span>
                        <span class="detail-value">
                            {{ $enforcer->contact_number ?: 'Not Set' }}
                        </span>
                    </div>
                </div>

            </div>

        </section>


        {{-- Account Information --}}
        <section>

            <h6 class="fw-semibold mb-3">
                <i class="fas fa-user-lock text-primary me-2"></i>
                Account Information
            </h6>

            <div class="row">

                <div class="col-md-6">
                    <div class="detail-row">
                        <span class="detail-label">Account ID</span>
                        <span class="detail-value">
                            {{ $enforcer->user->id ?? '—' }}
                        </span>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="detail-row">
                        <span class="detail-label">Username</span>
                        <span class="detail-value text-break">
                            {{ $enforcer->user->username ?? '—' }}
                        </span>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="detail-row">
                        <span class="detail-label">Role</span>
                        <span class="detail-value text-break">
                            {{ $enforcer->user->role->name ?? 'POSO Enforcer' }}
                        </span>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="detail-row">
                        <span class="detail-label">Account Status</span>
                        <span class="detail-value">

                            @if($enforcer->user)

                                @if($enforcer->user->account_status === 'Active')
                                    <span class="text-success fw-semibold">
                                        <i class="fas fa-circle small me-1"></i>
                                        Active
                                    </span>
                                @else
                                    <span class="text-danger fw-semibold">
                                        <i class="fas fa-circle small me-1"></i>
                                        Disabled
                                    </span>
                                @endif

                            @else
                                <span class="text-muted">Not Set</span>
                            @endif

                        </span>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="detail-row">
                        <span class="detail-label">Password</span>
                        <span class="detail-value text-muted">
                            ••••••••••••••••
                        </span>
                    </div>
                </div>

            </div>

        </section>


        {{-- Security Note --}}
        <div class="mt-4 pt-3 border-top">

            <div class="text-muted small">
                <i class="fas fa-info-circle text-primary me-2"></i>
                Passwords are encrypted and cannot be viewed. Only authorized
                administrators can reset an enforcer's password.
            </div>

        </div>

    </div>
</div>


</div>

<style>
    .detail-row {
        display: flex;
        flex-direction: column;
        padding: 12px 0;
        border-bottom: 1px solid #f0f0f0;
        min-width: 0;
    }

    .detail-label {
        font-size: 0.75rem;
        font-weight: 600;
        color: #6c757d;
        text-transform: uppercase;
        letter-spacing: 0.02em;
        margin-bottom: 5px;
    }

    .detail-value {
        display: block;
        font-size: 0.94rem;
        font-weight: 500;
        color: #212529;
        line-height: 1.45;
        overflow-wrap: anywhere;
        word-break: break-word;
    }

    .detail-row .small {
        font-size: 0.7rem;
    }

    @media (max-width: 767.98px) {
        .container-fluid {
            padding-left: 15px !important;
            padding-right: 15px !important;
        }
    }
</style>

@endsection
