
@extends('layouts.admin')

@section('title', 'Edit Enforcer')

@section('content')

<div class="container-fluid">

    {{-- ===================================================== --}}
    {{-- Alerts --}}
    {{-- ===================================================== --}}
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show rounded-3 shadow-sm mb-4">
            <i class="fas fa-check-circle me-2"></i>
            {{ session('success') }}

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert">
            </button>
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show rounded-3 shadow-sm mb-4">
            <div class="fw-semibold mb-1">
                <i class="fas fa-exclamation-circle me-2"></i>
                Please fix the following errors:
            </div>

            <ul class="mb-0 mt-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert">
            </button>
        </div>
    @endif


    {{-- ===================================================== --}}
    {{-- Page Header --}}
    {{-- ===================================================== --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <div class="d-flex align-items-center mb-1">
                <div
                    class="d-flex align-items-center justify-content-center bg-primary bg-opacity-10 text-primary rounded-3 me-3"
                    style="width: 44px; height: 44px;"
                >
                    <i class="fas fa-user-edit"></i>
                </div>

                <h2 class="fw-bold mb-0">
                    Edit Enforcer
                </h2>
            </div>

            <p class="text-muted mb-0 ms-1">
                Update the enforcer's personal, employment, and account information.
            </p>
        </div>

        <a
            href="{{ route('enforcers.index') }}"
            class="btn btn-outline-secondary rounded-3 px-4"
        >
            <i class="fas fa-arrow-left me-2"></i>
            Back to Enforcers
        </a>

    </div>


    {{-- ===================================================== --}}
    {{-- Main Profile Card --}}
    {{-- ===================================================== --}}
    <div class="card border-0 shadow-sm rounded-4 mb-4">

        {{-- Card Header --}}
        <div class="card-header bg-white border-bottom py-3 px-4">

            <div class="d-flex align-items-center">

                <div
                    class="d-flex align-items-center justify-content-center bg-primary bg-opacity-10 text-primary rounded-3 me-3"
                    style="width: 42px; height: 42px;"
                >
                    <i class="fas fa-id-card"></i>
                </div>

                <div>
                    <h5 class="fw-bold mb-0">
                        Enforcer Profile
                    </h5>

                    <small class="text-muted">
                        Personal and employment details
                    </small>
                </div>

            </div>

        </div>


        <div class="card-body p-4">

            <form
                action="{{ route('enforcers.update', $enforcer->id) }}"
                method="POST"
            >

                @csrf
                @method('PUT')


                {{-- ================================================= --}}
                {{-- Personal Information --}}
                {{-- ================================================= --}}
                <div class="mb-4">

                    <div class="d-flex align-items-center mb-3">

                        <div
                            class="d-flex align-items-center justify-content-center bg-light text-primary rounded-circle me-2"
                            style="width: 34px; height: 34px;"
                        >
                            <i class="fas fa-user"></i>
                        </div>

                        <div>
                            <h6 class="fw-bold mb-0">
                                Personal Information
                            </h6>

                            <small class="text-muted">
                                Basic information of the enforcer
                            </small>
                        </div>

                    </div>


                    <div class="row g-3">

                        {{-- First Name --}}
                        <div class="col-md-4">

                            <label class="form-label fw-semibold">
                                First Name
                                <span class="text-danger">*</span>
                            </label>

                            <input
                                type="text"
                                name="first_name"
                                class="form-control rounded-3"
                                value="{{ old('first_name', $enforcer->first_name) }}"
                                placeholder="Enter first name"
                                required
                            >

                        </div>


                        {{-- Middle Name --}}
                        <div class="col-md-4">

                            <label class="form-label fw-semibold">
                                Middle Name
                                <span class="text-muted fw-normal">(Optional)</span>
                            </label>

                            <input
                                type="text"
                                name="middle_name"
                                class="form-control rounded-3"
                                value="{{ old('middle_name', $enforcer->middle_name) }}"
                                placeholder="Enter middle name"
                            >

                        </div>


                        {{-- Last Name --}}
                        <div class="col-md-4">

                            <label class="form-label fw-semibold">
                                Last Name
                                <span class="text-danger">*</span>
                            </label>

                            <input
                                type="text"
                                name="last_name"
                                class="form-control rounded-3"
                                value="{{ old('last_name', $enforcer->last_name) }}"
                                placeholder="Enter last name"
                                required
                            >

                        </div>


                        {{-- Contact Number --}}
                        <div class="col-md-6">

                            <label class="form-label fw-semibold">
                                Contact Number
                                <span class="text-muted fw-normal">(Optional)</span>
                            </label>

                            <input
                                type="text"
                                name="contact_number"
                                class="form-control rounded-3"
                                value="{{ old('contact_number', $enforcer->contact_number) }}"
                                placeholder="Enter contact number"
                            >

                        </div>


                        {{-- Badge Number --}}
                        <div class="col-md-6">

                            <label class="form-label fw-semibold">
                                Badge Number
                                <span class="text-muted fw-normal">(Optional)</span>
                            </label>

                            <input
                                type="text"
                                name="badge_number"
                                class="form-control rounded-3"
                                value="{{ old('badge_number', $enforcer->badge_number) }}"
                                placeholder="Enter badge number"
                            >

                        </div>

                    </div>

                </div>


                {{-- Section Divider --}}
                <hr class="my-4">


                {{-- ================================================= --}}
                {{-- Employment Information --}}
                {{-- ================================================= --}}
                <div class="mb-4">

                    <div class="d-flex align-items-center mb-3">

                        <div
                            class="d-flex align-items-center justify-content-center bg-light text-primary rounded-circle me-2"
                            style="width: 34px; height: 34px;"
                        >
                            <i class="fas fa-briefcase"></i>
                        </div>

                        <div>
                            <h6 class="fw-bold mb-0">
                                Employment Information
                            </h6>

                            <small class="text-muted">
                                Position and employment classification
                            </small>
                        </div>

                    </div>


                    <div class="row g-3">

                        {{-- Position --}}
                        <div class="col-md-6">

                            <label class="form-label fw-semibold">
                                Position
                                <span class="text-danger">*</span>
                            </label>

                            <select
                                name="position"
                                class="form-select rounded-3"
                                required
                            >

                                <option value="">
                                    Select Position
                                </option>

                                <option
                                    value="Traffic Enforcer"
                                    {{ old('position', $enforcer->position) === 'Traffic Enforcer' ? 'selected' : '' }}
                                >
                                    Traffic Enforcer
                                </option>

                                <option
                                    value="Traffic Aide"
                                    {{ old('position', $enforcer->position) === 'Traffic Aide' ? 'selected' : '' }}
                                >
                                    Traffic Aide
                                </option>

                            </select>

                            <small class="text-muted">
                                Select the enforcer's assigned position.
                            </small>

                        </div>


                        {{-- Employment Status --}}
                        <div class="col-md-6">

                            <label class="form-label fw-semibold">
                                Employment Status
                                <span class="text-danger">*</span>
                            </label>

                            <select
                                name="employment_status"
                                class="form-select rounded-3"
                                required
                            >

                                <option value="">
                                    Select Employment Status
                                </option>

                                <option
                                    value="Permanent"
                                    {{ old('employment_status', $enforcer->employment_status) === 'Permanent' ? 'selected' : '' }}
                                >
                                    Permanent
                                </option>

                                <option
                                    value="Job Order"
                                    {{ old('employment_status', $enforcer->employment_status) === 'Job Order' ? 'selected' : '' }}
                                >
                                    Job Order
                                </option>

                            </select>

                            <small class="text-muted">
                                Select the enforcer's employment classification.
                            </small>

                        </div>

                    </div>

                </div>


                {{-- Section Divider --}}
                <hr class="my-4">


                {{-- ================================================= --}}
                {{-- Account Information --}}
                {{-- ================================================= --}}
                <div class="mb-2">

                    <div class="d-flex align-items-center mb-3">

                        <div
                            class="d-flex align-items-center justify-content-center bg-light text-primary rounded-circle me-2"
                            style="width: 34px; height: 34px;"
                        >
                            <i class="fas fa-user-cog"></i>
                        </div>

                        <div>
                            <h6 class="fw-bold mb-0">
                                Account Information
                            </h6>

                            <small class="text-muted">
                                Login credentials and account status
                            </small>
                        </div>

                    </div>


                    <div class="row g-3">

                        {{-- Username --}}
                        <div class="col-md-6">

                            <label class="form-label fw-semibold">
                                Username
                                <span class="text-danger">*</span>
                            </label>

                            <input
                                type="text"
                                name="username"
                                class="form-control rounded-3"
                                value="{{ old('username', $enforcer->user?->username) }}"
                                autocomplete="username"
                                placeholder="Enter username"
                                required
                            >

                            <small class="text-muted">
                                Used by the enforcer to log in to TrafficEnforceNet.
                            </small>

                        </div>


                        {{-- Account Status --}}
                        <div class="col-md-6">

                            <label class="form-label fw-semibold">
                                Account Status
                            </label>

                            <div
                                class="form-control rounded-3 bg-light d-flex align-items-center"
                                style="min-height: 38px;"
                            >

                                @if ($enforcer->user?->account_status === 'Active')

                                    <span
                                        class="badge rounded-pill bg-success px-3 py-2"
                                    >
                                        <i class="fas fa-check-circle me-1"></i>
                                        Active
                                    </span>

                                @elseif ($enforcer->user)

                                    <span
                                        class="badge rounded-pill bg-danger px-3 py-2"
                                    >
                                        <i class="fas fa-times-circle me-1"></i>
                                        Inactive
                                    </span>

                                @else

                                    <span
                                        class="badge rounded-pill bg-secondary px-3 py-2"
                                    >
                                        <i class="fas fa-question-circle me-1"></i>
                                        Not Set
                                    </span>

                                @endif

                            </div>

                            <small class="text-muted">
                                Account status is managed separately from employment status.
                            </small>

                        </div>

                    </div>

                </div>


                {{-- ================================================= --}}
                {{-- Form Actions --}}
                {{-- ================================================= --}}
                <div class="d-flex justify-content-end align-items-center gap-2 border-top mt-4 pt-4">

                    <a
                        href="{{ route('enforcers.index') }}"
                        class="btn btn-light border rounded-3 px-4"
                    >
                        Cancel
                    </a>

                    <button
                        type="submit"
                        class="btn btn-primary rounded-3 px-4"
                    >
                        <i class="fas fa-save me-2"></i>
                        Save Changes
                    </button>

                </div>

            </form>

        </div>

    </div>


    {{-- ===================================================== --}}
    {{-- Reset Password --}}
    {{-- ===================================================== --}}
    <div class="card border-0 shadow-sm rounded-4 mb-4">

        <div class="card-header bg-white border-bottom py-3 px-4">

            <div class="d-flex align-items-center">

                <div
                    class="d-flex align-items-center justify-content-center bg-warning bg-opacity-10 text-warning rounded-3 me-3"
                    style="width: 42px; height: 42px;"
                >
                    <i class="fas fa-key"></i>
                </div>

                <div>
                    <h5 class="fw-bold mb-0">
                        Reset Password
                    </h5>

                    <small class="text-muted">
                        Change the login password for this enforcer.
                    </small>
                </div>

            </div>

        </div>


        <div class="card-body p-4">

            <form
                action="{{ route('enforcers.resetPassword', $enforcer->id) }}"
                method="POST"
            >

                @csrf
                @method('PUT')

                <div class="row g-3">

                    {{-- New Password --}}
                    <div class="col-md-6">

                        <label class="form-label fw-semibold">
                            New Password
                        </label>

                        <input
                            type="password"
                            name="password"
                            class="form-control rounded-3"
                            placeholder="Enter new password"
                            required
                        >

                    </div>


                    {{-- Confirm Password --}}
                    <div class="col-md-6">

                        <label class="form-label fw-semibold">
                            Confirm Password
                        </label>

                        <input
                            type="password"
                            name="password_confirmation"
                            class="form-control rounded-3"
                            placeholder="Confirm new password"
                            required
                        >

                    </div>

                </div>


                <div class="d-flex justify-content-end mt-4">

                    <button
                        type="submit"
                        class="btn btn-warning text-dark rounded-3 px-4"
                    >
                        <i class="fas fa-key me-2"></i>
                        Reset Password
                    </button>

                </div>

            </form>

        </div>

    </div>


    {{-- ===================================================== --}}
    {{-- Danger Zone --}}
    {{-- ===================================================== --}}
    <div class="card border-0 shadow-sm rounded-4 mb-4">

        <div class="card-header bg-white border-bottom py-3 px-4">

            <div class="d-flex align-items-center">

                <div
                    class="d-flex align-items-center justify-content-center bg-danger bg-opacity-10 text-danger rounded-3 me-3"
                    style="width: 42px; height: 42px;"
                >
                    <i class="fas fa-exclamation-triangle"></i>
                </div>

                <div>
                    <h5 class="fw-bold mb-0 text-danger">
                        Danger Zone
                    </h5>

                    <small class="text-muted">
                        Permanent account actions
                    </small>
                </div>

            </div>

        </div>


        <div class="card-body p-4">

            <div class="row align-items-center">

                <div class="col-md-8">

                    <h6 class="fw-bold mb-1">
                        Delete Enforcer
                    </h6>

                    <p class="text-muted mb-0">
                        Permanently remove this enforcer record and their linked account.
                        This action cannot be undone.
                    </p>

                </div>


                <div class="col-md-4 text-md-end mt-3 mt-md-0">

                    <form
                        action="{{ route('enforcers.destroy', $enforcer->id) }}"
                        method="POST"
                        onsubmit="return confirm('Delete this enforcer?');"
                    >

                        @csrf
                        @method('DELETE')

                        <button
                            type="submit"
                            class="btn btn-outline-danger rounded-3 px-4"
                        >
                            <i class="fas fa-trash me-2"></i>
                            Delete Enforcer
                        </button>

                    </form>

                </div>

            </div>

        </div>

    </div>


    {{-- ===================================================== --}}
    {{-- Security Information --}}
    {{-- ===================================================== --}}
    <div class="card border-0 shadow-sm rounded-4 mb-4">

        <div class="card-header bg-white border-bottom py-3 px-4">

            <div class="d-flex align-items-center">

                <div
                    class="d-flex align-items-center justify-content-center bg-dark bg-opacity-10 text-dark rounded-3 me-3"
                    style="width: 42px; height: 42px;"
                >
                    <i class="fas fa-shield-alt"></i>
                </div>

                <div>
                    <h5 class="fw-bold mb-0">
                        Security Information
                    </h5>

                    <small class="text-muted">
                        Account and security notes
                    </small>
                </div>

            </div>

        </div>


        <div class="card-body p-4">

            <div class="row g-3">

                <div class="col-md-4">

                    <div class="d-flex align-items-start">

                        <i class="fas fa-lock text-success me-3 mt-1"></i>

                        <div>
                            <div class="fw-semibold">
                                Password Protection
                            </div>

                            <small class="text-muted">
                                Passwords are encrypted securely.
                            </small>
                        </div>

                    </div>

                </div>


                <div class="col-md-4">

                    <div class="d-flex align-items-start">

                        <i class="fas fa-user-shield text-primary me-3 mt-1"></i>

                        <div>
                            <div class="fw-semibold">
                                Access Control
                            </div>

                            <small class="text-muted">
                                Only administrators can manage accounts.
                            </small>
                        </div>

                    </div>

                </div>


                <div class="col-md-4">

                    <div class="d-flex align-items-start">

                        <i class="fas fa-history text-warning me-3 mt-1"></i>

                        <div>
                            <div class="fw-semibold">
                                Activity Tracking
                            </div>

                            <small class="text-muted">
                                Future login activities can be tracked.
                            </small>
                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection

