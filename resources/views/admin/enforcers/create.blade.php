@extends('layouts.admin')

@section('title', 'Add Enforcer')

@section('content')

<div class="container-fluid px-4 pt-3">


{{-- Page Header --}}
<div class="mb-4">
    <h1 class="h3 mb-1">Add Enforcer</h1>
    <p class="text-muted mb-0">
        Create an enforcer profile and login account.
    </p>
</div>

{{-- Validation Errors --}}
@if ($errors->any())
    <div class="alert alert-danger">
        <strong>Please check the following:</strong>
        <ul class="mb-0 mt-2">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="card shadow-sm border-0">
    <div class="card-body p-4">

        <form action="{{ route('enforcers.store') }}" method="POST">
            @csrf

            {{-- ================================================= --}}
            {{-- PERSONAL INFORMATION --}}
            {{-- ================================================= --}}

            <h5 class="fw-semibold mb-3">Personal Information</h5>

            <div class="row">

                <div class="col-md-4 mb-3">
                    <label class="form-label">
                        First Name <span class="text-danger">*</span>
                    </label>

                    <input
                        type="text"
                        name="first_name"
                        class="form-control"
                        value="{{ old('first_name') }}"
                        required
                    >
                </div>

                <div class="col-md-4 mb-3">
                    <label class="form-label">
                        Middle Name
                    </label>

                    <input
                        type="text"
                        name="middle_name"
                        class="form-control"
                        value="{{ old('middle_name') }}"
                    >
                </div>

                <div class="col-md-4 mb-3">
                    <label class="form-label">
                        Last Name <span class="text-danger">*</span>
                    </label>

                    <input
                        type="text"
                        name="last_name"
                        class="form-control"
                        value="{{ old('last_name') }}"
                        required
                    >
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">
                        Contact Number
                    </label>

                    <input
                        type="text"
                        name="contact_number"
                        class="form-control"
                        value="{{ old('contact_number') }}"
                        placeholder="Optional"
                    >
                </div>

            </div>

            <hr class="my-4">

            {{-- ================================================= --}}
            {{-- EMPLOYMENT INFORMATION --}}
            {{-- ================================================= --}}

            <h5 class="fw-semibold mb-3">Employment Information</h5>

            <div class="row">

                <div class="col-md-6 mb-3">
                    <label class="form-label">
                        Badge Number <span class="text-danger">*</span>
                    </label>

                    <input
                        type="text"
                        name="badge_number"
                        class="form-control"
                        value="{{ old('badge_number') }}"
                        required
                    >
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">
                        Position <span class="text-danger">*</span>
                    </label>

                    <select
                        name="position"
                        class="form-select"
                        required
                    >
                        <option value="">Select Position</option>

                        <option
                            value="Traffic Enforcer"
                            {{ old('position') == 'Traffic Enforcer' ? 'selected' : '' }}
                        >
                            Traffic Enforcer
                        </option>

                        <option
                            value="Traffic Aide"
                            {{ old('position') == 'Traffic Aide' ? 'selected' : '' }}
                        >
                            Traffic Aide
                        </option>
                    </select>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">
                        Employment Status <span class="text-danger">*</span>
                    </label>

                    <select
                        name="employment_status"
                        class="form-select"
                        required
                    >
                        <option
                            value="Active"
                            {{ old('employment_status', 'Active') == 'Active' ? 'selected' : '' }}
                        >
                            Active
                        </option>

                        <option
                            value="Inactive"
                            {{ old('employment_status') == 'Inactive' ? 'selected' : '' }}
                        >
                            Inactive
                        </option>
                    </select>
                </div>

            </div>

            <hr class="my-4">

            {{-- ================================================= --}}
            {{-- ACCOUNT CREDENTIALS --}}
            {{-- ================================================= --}}

            <h5 class="fw-semibold mb-1">Account Credentials</h5>

            <p class="text-muted small mb-3">
                These credentials will be used by the enforcer to access TrafficEnforceNet.
            </p>

            <div class="row">

                <div class="col-md-6 mb-3">
                    <label class="form-label">
                        Username <span class="text-danger">*</span>
                    </label>

                    <input
                        type="text"
                        name="username"
                        class="form-control"
                        value="{{ old('username') }}"
                        autocomplete="username"
                        required
                    >
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">
                        Password <span class="text-danger">*</span>
                    </label>

                    <input
                        type="password"
                        name="password"
                        class="form-control"
                        autocomplete="new-password"
                        required
                    >
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">
                        Confirm Password <span class="text-danger">*</span>
                    </label>

                    <input
                        type="password"
                        name="password_confirmation"
                        class="form-control"
                        autocomplete="new-password"
                        required
                    >
                </div>

            </div>

            {{-- ================================================= --}}
            {{-- ACTIONS --}}
            {{-- ================================================= --}}

            <div class="d-flex gap-2 mt-3">

                <button
                    type="submit"
                    class="btn btn-primary"
                >
                    Save Enforcer
                </button>

                <a
                    href="{{ route('enforcers.index') }}"
                    class="btn btn-secondary"
                >
                    Cancel
                </a>

            </div>

        </form>

    </div>
</div>


</div>

@endsection
