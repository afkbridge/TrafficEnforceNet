@extends('layouts.admin')

@section('title', 'Add Enforcer')

@section('content')

<div class="container-fluid px-4 pt-3">


{{-- ===================================================== --}}
{{-- Page Header --}}
{{-- ===================================================== --}}

<div class="d-flex justify-content-between align-items-center mb-4">

    <div>
        <h1 class="h3 mb-1 fw-bold">
            Add Enforcer
        </h1>

        <p class="text-muted mb-0">
            Create an enforcer profile and login account.
        </p>
    </div>

    <a
        href="{{ route('enforcers.index') }}"
        class="btn btn-outline-secondary rounded-pill px-4"
    >
        <i class="fas fa-arrow-left me-2"></i>
        Back
    </a>

</div>


{{-- ===================================================== --}}
{{-- Validation Errors --}}
{{-- ===================================================== --}}

@if ($errors->any())

    <div class="alert alert-danger rounded-3 shadow-sm">

        <strong>
            Please check the following:
        </strong>

        <ul class="mb-0 mt-2">

            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach

        </ul>

    </div>

@endif


{{-- ===================================================== --}}
{{-- Main Card --}}
{{-- ===================================================== --}}

<div class="card shadow-sm border-0 rounded-4">

    <div class="card-body p-4">

        <form
            action="{{ route('enforcers.store') }}"
            method="POST"
        >

            @csrf


            {{-- ================================================= --}}
            {{-- PERSONAL INFORMATION --}}
            {{-- ================================================= --}}

            <div class="d-flex align-items-center mb-3">

                <div
                    class="d-flex align-items-center justify-content-center rounded-3 me-3"
                    style="
                        width: 42px;
                        height: 42px;
                        background: #eef4ff;
                        color: #1d5fbf;
                    "
                >
                    <i class="fas fa-user"></i>
                </div>

                <div>
                    <h5 class="fw-semibold mb-0">
                        Personal Information
                    </h5>

                    <small class="text-muted">
                        Enter the enforcer's personal details.
                    </small>
                </div>

            </div>


            <div class="row">

                {{-- First Name --}}
                <div class="col-md-4 mb-3">

                    <label class="form-label fw-semibold">
                        First Name
                        <span class="text-danger">*</span>
                    </label>

                    <input
                        type="text"
                        name="first_name"
                        class="form-control rounded-3"
                        value="{{ old('first_name') }}"
                        required
                    >

                </div>


                {{-- Middle Name --}}
                <div class="col-md-4 mb-3">

                    <label class="form-label fw-semibold">
                        Middle Name
                    </label>

                    <input
                        type="text"
                        name="middle_name"
                        class="form-control rounded-3"
                        value="{{ old('middle_name') }}"
                        placeholder="Optional"
                    >

                </div>


                {{-- Last Name --}}
                <div class="col-md-4 mb-3">

                    <label class="form-label fw-semibold">
                        Last Name
                        <span class="text-danger">*</span>
                    </label>

                    <input
                        type="text"
                        name="last_name"
                        class="form-control rounded-3"
                        value="{{ old('last_name') }}"
                        required
                    >

                </div>


                {{-- Contact Number --}}
                <div class="col-md-6 mb-3">

                    <label class="form-label fw-semibold">
                        Contact Number
                        <span class="text-muted fw-normal">
                            (Optional)
                        </span>
                    </label>

                    <input
                        type="text"
                        name="contact_number"
                        class="form-control rounded-3"
                        value="{{ old('contact_number') }}"
                        placeholder="Enter contact number"
                    >

                </div>

            </div>


            <hr class="my-4">


            {{-- ================================================= --}}
            {{-- EMPLOYMENT INFORMATION --}}
            {{-- ================================================= --}}

            <div class="d-flex align-items-center mb-3">

                <div
                    class="d-flex align-items-center justify-content-center rounded-3 me-3"
                    style="
                        width: 42px;
                        height: 42px;
                        background: #fff4db;
                        color: #946200;
                    "
                >
                    <i class="fas fa-briefcase"></i>
                </div>

                <div>
                    <h5 class="fw-semibold mb-0">
                        Employment Information
                    </h5>

                    <small class="text-muted">
                        Enter the enforcer's employment details.
                    </small>
                </div>

            </div>


            <div class="row">

                {{-- Badge Number --}}
                <div class="col-md-6 mb-3">

                    <label class="form-label fw-semibold">
                        Badge Number
                        <span class="text-muted fw-normal">
                            (Optional)
                        </span>
                    </label>

                    <input
                        type="text"
                        name="badge_number"
                        class="form-control rounded-3"
                        value="{{ old('badge_number') }}"
                        placeholder="Enter badge number"
                    >

                </div>


                {{-- Position --}}
                <div class="col-md-6 mb-3">

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
                            {{ old('position') === 'Traffic Enforcer' ? 'selected' : '' }}
                        >
                            Traffic Enforcer
                        </option>

                        <option
                            value="Traffic Aide"
                            {{ old('position') === 'Traffic Aide' ? 'selected' : '' }}
                        >
                            Traffic Aide
                        </option>

                    </select>

                </div>


                {{-- Employment Status --}}
                <div class="col-md-6 mb-3">

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
                            {{ old('employment_status') === 'Permanent' ? 'selected' : '' }}
                        >
                            Permanent
                        </option>

                        <option
                            value="Job Order"
                            {{ old('employment_status') === 'Job Order' ? 'selected' : '' }}
                        >
                            Job Order
                        </option>

                    </select>

                </div>

            </div>


            <hr class="my-4">


            {{-- ================================================= --}}
            {{-- ACCOUNT CREDENTIALS --}}
            {{-- ================================================= --}}

            <div class="d-flex align-items-center mb-3">

                <div
                    class="d-flex align-items-center justify-content-center rounded-3 me-3"
                    style="
                        width: 42px;
                        height: 42px;
                        background: #ecfdf3;
                        color: #198754;
                    "
                >
                    <i class="fas fa-user-lock"></i>
                </div>

                <div>
                    <h5 class="fw-semibold mb-0">
                        Account Credentials
                    </h5>

                    <small class="text-muted">
                        These credentials will be used by the enforcer to access TrafficEnforceNet.
                    </small>
                </div>

            </div>


            <div class="row">

                {{-- Username --}}
                <div class="col-md-6 mb-3">

                    <label class="form-label fw-semibold">
                        Username
                        <span class="text-danger">*</span>
                    </label>

                    <input
                        type="text"
                        name="username"
                        class="form-control rounded-3"
                        value="{{ old('username') }}"
                        autocomplete="username"
                        placeholder="Enter username"
                        required
                    >

                </div>


                {{-- Password --}}
                <div class="col-md-6 mb-3">

                    <label class="form-label fw-semibold">
                        Password
                        <span class="text-danger">*</span>
                    </label>

                    <input
                        type="password"
                        name="password"
                        class="form-control rounded-3"
                        autocomplete="new-password"
                        placeholder="Enter password"
                        required
                    >

                </div>


                {{-- Confirm Password --}}
                <div class="col-md-6 mb-3">

                    <label class="form-label fw-semibold">
                        Confirm Password
                        <span class="text-danger">*</span>
                    </label>

                    <input
                        type="password"
                        name="password_confirmation"
                        class="form-control rounded-3"
                        autocomplete="new-password"
                        placeholder="Confirm password"
                        required
                    >

                </div>

            </div>


            {{-- ================================================= --}}
            {{-- ACTIONS --}}
            {{-- ================================================= --}}

            <hr class="my-4">

            <div class="d-flex justify-content-end gap-2">

                <a
                    href="{{ route('enforcers.index') }}"
                    class="btn btn-light border rounded-pill px-4"
                >
                    Cancel
                </a>

                <button
                    type="submit"
                    class="btn btn-primary rounded-pill px-4"
                >
                    <i class="fas fa-save me-2"></i>
                    Save Enforcer
                </button>

            </div>

        </form>

    </div>

</div>


</div>

@endsection
