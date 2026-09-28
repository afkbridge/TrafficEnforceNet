@extends('layouts.superadmin')

@section('title', 'My Account')

@section('content')

<div class="container-fluid">

    {{-- =========================================================
         SUCCESS MESSAGE
    ========================================================== --}}

    @if (session('success'))

        <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">

            <i class="fas fa-check-circle me-2"></i>

            {{ session('success') }}

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert"
                aria-label="Close">
            </button>

        </div>

    @endif


    {{-- =========================================================
         VALIDATION ERRORS
    ========================================================== --}}

    @if ($errors->any())

        <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">

            <strong>
                Please fix the following errors:
            </strong>

            <ul class="mb-0 mt-2">

                @foreach ($errors->all() as $error)

                    <li>{{ $error }}</li>

                @endforeach

            </ul>

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert"
                aria-label="Close">
            </button>

        </div>

    @endif


    {{-- =========================================================
         PAGE HEADER
    ========================================================== --}}

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h2 class="fw-bold mb-1">

                <i class="fas fa-user-cog text-primary me-2"></i>

                My Account

            </h2>

            <p class="text-muted mb-0">

                Manage your Super Administrator account information and password.

            </p>

        </div>


        <a
            href="{{ route('super-admin.users') }}"
            class="btn btn-outline-secondary">

            <i class="fas fa-arrow-left me-2"></i>

            Back to User Management

        </a>

    </div>


    {{-- =========================================================
         ACCOUNT INFORMATION
    ========================================================== --}}

    <div class="card border-0 shadow-sm mb-4">

        <div class="card-header bg-white border-0 py-4">

            <div class="d-flex align-items-center">

                <div class="bg-primary bg-opacity-10 rounded-circle p-3 me-3">

                    <i class="fas fa-user text-primary fa-lg"></i>

                </div>


                <div>

                    <h5 class="fw-bold mb-1">

                        Account Information

                    </h5>

                    <p class="text-muted mb-0">

                        Update your name and username.

                    </p>

                </div>

            </div>

        </div>


        <div class="card-body">

            <form
                method="POST"
                action="{{ route('super-admin.profile.update') }}">

                @csrf

                @method('PUT')


                <div class="row g-4">

                    {{-- NAME --}}

                    <div class="col-md-6">

                        <label
                            for="name"
                            class="form-label fw-semibold">

                            Name

                        </label>


                        <input
                            type="text"
                            id="name"
                            name="name"
                            class="form-control"
                            value="{{ old('name', $user->name) }}"
                            maxlength="255"
                            autocomplete="name"
                            required
                        >


                        @error('name')

                            <div class="text-danger small mt-1">

                                {{ $message }}

                            </div>

                        @enderror

                    </div>


                    {{-- USERNAME --}}

                    <div class="col-md-6">

                        <label
                            for="username"
                            class="form-label fw-semibold">

                            Username

                        </label>


                        <input
                            type="text"
                            id="username"
                            name="username"
                            class="form-control"
                            value="{{ old('username', $user->username) }}"
                            maxlength="255"
                            pattern="[A-Za-z0-9._-]+"
                            autocomplete="username"
                            required
                        >


                        <small class="text-muted">

                            Letters, numbers, periods, dashes, and underscores are allowed.

                        </small>


                        @error('username')

                            <div class="text-danger small mt-1">

                                {{ $message }}

                            </div>

                        @enderror

                    </div>


                    {{-- ROLE --}}

                    <div class="col-md-6">

                        <label
                            for="role"
                            class="form-label fw-semibold">

                            Role

                        </label>


                        <input
                            type="text"
                            id="role"
                            class="form-control bg-light"
                            value="Super Administrator"
                            readonly
                        >

                    </div>


                    {{-- ACCOUNT STATUS --}}

                    <div class="col-md-6">

                        <label
                            for="account_status"
                            class="form-label fw-semibold">

                            Account Status

                        </label>


                        <input
                            type="text"
                            id="account_status"
                            class="form-control bg-light"
                            value="{{ $user->account_status }}"
                            readonly
                        >

                    </div>

                </div>


                {{-- SAVE BUTTON --}}

                <div class="mt-4">

                    <button
                        type="submit"
                        class="btn btn-primary">

                        <i class="fas fa-save me-2"></i>

                        Save Account Information

                    </button>

                </div>

            </form>

        </div>

    </div>


    {{-- =========================================================
         CHANGE PASSWORD
    ========================================================== --}}

    <div class="card border-0 shadow-sm mb-4">

        <div class="card-header bg-white border-0 py-4">

            <div class="d-flex align-items-center">

                <div class="bg-warning bg-opacity-10 rounded-circle p-3 me-3">

                    <i class="fas fa-key text-warning fa-lg"></i>

                </div>


                <div>

                    <h5 class="fw-bold mb-1">

                        Change Password

                    </h5>

                    <p class="text-muted mb-0">

                        Change the password used to sign in to TrafficEnforceNet.

                    </p>

                </div>

            </div>

        </div>


        <div class="card-body">

            <form
                method="POST"
                action="{{ route('super-admin.profile.password') }}">

                @csrf

                @method('PUT')


                {{-- CURRENT PASSWORD --}}

                <div class="mb-4">

                    <label
                        for="current_password"
                        class="form-label fw-semibold">

                        Current Password

                    </label>


                    <input
                        type="password"
                        id="current_password"
                        name="current_password"
                        class="form-control"
                        autocomplete="current-password"
                        required
                    >


                    @error('current_password')

                        <div class="text-danger small mt-1">

                            {{ $message }}

                        </div>

                    @enderror

                </div>


                <div class="row g-4">

                    {{-- NEW PASSWORD --}}

                    <div class="col-md-6">

                        <label
                            for="password"
                            class="form-label fw-semibold">

                            New Password

                        </label>


                        <input
                            type="password"
                            id="password"
                            name="password"
                            class="form-control"
                            minlength="8"
                            autocomplete="new-password"
                            required
                        >


                        <small class="text-muted">

                            Minimum of 8 characters.

                        </small>


                        @error('password')

                            <div class="text-danger small mt-1">

                                {{ $message }}

                            </div>

                        @enderror

                    </div>


                    {{-- CONFIRM PASSWORD --}}

                    <div class="col-md-6">

                        <label
                            for="password_confirmation"
                            class="form-label fw-semibold">

                            Confirm New Password

                        </label>


                        <input
                            type="password"
                            id="password_confirmation"
                            name="password_confirmation"
                            class="form-control"
                            minlength="8"
                            autocomplete="new-password"
                            required
                        >

                    </div>

                </div>


                {{-- PASSWORD CHANGE WARNING --}}

                <div class="alert alert-warning mt-4 mb-4">

                    <i class="fas fa-info-circle me-2"></i>

                    After changing your password, you will be logged out and
                    must sign in again using your new password.

                </div>


                {{-- CHANGE PASSWORD BUTTON --}}

                <button
                    type="submit"
                    class="btn btn-warning">

                    <i class="fas fa-lock me-2"></i>

                    Change Password

                </button>

            </form>

        </div>

    </div>


    {{-- =========================================================
         ACCOUNT SECURITY INFORMATION
    ========================================================== --}}

    <div class="card border-0 shadow-sm">

        <div class="card-header bg-white border-0 py-4">

            <h5 class="fw-bold mb-1">

                Account Security

            </h5>

            <p class="text-muted mb-0">

                Information about your current account.

            </p>

        </div>


        <div class="card-body">

            <div class="row g-4">

                {{-- ACCOUNT ID --}}

                <div class="col-md-4">

                    <small class="text-muted d-block">

                        Account ID

                    </small>


                    <strong>

                        #{{ $user->id }}

                    </strong>

                </div>


                {{-- ACCOUNT CREATED --}}

                <div class="col-md-4">

                    <small class="text-muted d-block">

                        Account Created

                    </small>


                    <strong>

                        {{ optional($user->created_at)->format('M d, Y') ?? 'N/A' }}

                    </strong>

                </div>


                {{-- LAST UPDATED --}}

                <div class="col-md-4">

                    <small class="text-muted d-block">

                        Last Updated

                    </small>


                    <strong>

                        {{ optional($user->updated_at)->format('M d, Y h:i A') ?? 'N/A' }}

                    </strong>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection