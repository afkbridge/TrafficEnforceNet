@extends('layouts.admin')

@section('title', 'Settings')

@section('content')

<style>
    /* =========================================================
       ADMIN SETTINGS
       ========================================================= */

    .settings-page {
        padding: 24px;
        background: #f5f7fb;
        min-height: calc(100vh - 70px);
    }

    /* Page Header */
    .settings-page-header {
        margin-bottom: 24px;
    }

    .settings-page-header h2 {
        color: #1f2937;
        font-size: 28px;
        letter-spacing: -0.3px;
    }

    .settings-page-header p {
        color: #6b7280;
        font-size: 14px;
    }

    /* Alerts */
    .settings-alert {
        border: 0;
        border-radius: 14px;
        padding: 14px 18px;
        box-shadow: 0 4px 14px rgba(15, 23, 42, 0.05);
    }

    /* Main Cards */
    .settings-card {
        border: 0;
        border-radius: 18px;
        overflow: hidden;
        background: #ffffff;
        box-shadow: 0 6px 20px rgba(15, 23, 42, 0.06);
        margin-bottom: 24px;
    }

    .settings-card-header {
        background: #ffffff;
        border-bottom: 1px solid #eef1f5;
        padding: 18px 22px;
        display: flex;
        align-items: center;
    }

    .settings-card-header h5 {
        color: #1f2937;
        font-size: 16px;
        margin: 0;
    }

    .settings-card-header i {
        color: #2563eb;
        font-size: 16px;
    }

    .settings-card-body {
        padding: 24px;
    }

    /* Section Titles */
    .settings-section-title {
        color: #1f2937;
        font-size: 15px;
        font-weight: 700;
    }

    .settings-section-description {
        color: #6b7280;
        font-size: 13px;
        line-height: 1.6;
    }

    /* Form Labels */
    .settings-page .form-label {
        color: #374151;
        font-size: 13px;
        margin-bottom: 7px;
    }

    /* Form Controls */
    .settings-page .form-control {
        min-height: 44px;
        border: 1px solid #dfe3e8;
        border-radius: 11px;
        padding: 9px 13px;
        font-size: 14px;
        color: #1f2937;
        background: #ffffff;
        box-shadow: none;
        transition: all 0.2s ease;
    }

    .settings-page .form-control:focus {
        border-color: #86b7fe;
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.10);
    }

    .settings-page .form-control::placeholder {
        color: #9ca3af;
    }

    /* Buttons */
    .settings-page .btn {
        border-radius: 10px;
        padding: 9px 16px;
        font-size: 13px;
        font-weight: 600;
        transition: all 0.2s ease;
    }

    .settings-page .btn-primary {
        box-shadow: 0 4px 10px rgba(13, 110, 253, 0.16);
    }

    .settings-page .btn-primary:hover {
        transform: translateY(-1px);
        box-shadow: 0 6px 14px rgba(13, 110, 253, 0.20);
    }

    .settings-page .btn-outline-primary {
        border-width: 1px;
    }

    .settings-page .btn-outline-primary:hover {
        transform: translateY(-1px);
    }

    /* Divider */
    .settings-divider {
        border: 0;
        border-top: 1px solid #edf0f4;
        margin: 28px 0;
        opacity: 1;
    }

    /* Password Section */
    .password-section {
        background: #f8fafc;
        border: 1px solid #edf1f5;
        border-radius: 14px;
        padding: 20px;
    }

    /* Violation Management */
    .violation-management-box {
        background: #f8fafc;
        border: 1px solid #edf1f5;
        border-radius: 14px;
        padding: 20px;
    }

    .violation-icon-box {
        width: 44px;
        height: 44px;
        border-radius: 12px;
        background: #eff6ff;
        color: #2563eb;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .violation-icon-box i {
        font-size: 17px;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .settings-page {
            padding: 16px;
        }

        .settings-page-header h2 {
            font-size: 24px;
        }

        .settings-card-body {
            padding: 18px;
        }

        .settings-card-header {
            padding: 16px 18px;
        }

        .violation-management-box {
            padding: 16px;
        }

        .violation-management-box .d-flex {
            align-items: flex-start !important;
            gap: 16px;
        }

        .violation-management-box .btn {
            width: 100%;
            margin-top: 4px;
        }
    }
</style>

<div class="settings-page">


{{-- =========================================================
     PAGE HEADER
     ========================================================= --}}
<div class="settings-page-header">
    <h2 class="fw-bold mb-1">
        Settings
    </h2>

    <p class="mb-0">
        Manage your administrator account and system violation types.
    </p>
</div>


{{-- =========================================================
     SUCCESS MESSAGE
     ========================================================= --}}
@if(session('success'))

    <div
        class="alert alert-success alert-dismissible fade show settings-alert mb-4"
        role="alert"
    >
        <i class="fas fa-circle-check me-2"></i>

        {{ session('success') }}

        <button
            type="button"
            class="btn-close"
            data-bs-dismiss="alert"
        ></button>
    </div>

@endif


{{-- =========================================================
     ERROR MESSAGE
     ========================================================= --}}
@if($errors->any())

    <div
        class="alert alert-danger alert-dismissible fade show settings-alert mb-4"
        role="alert"
    >

        <div class="fw-semibold mb-1">
            Please correct the following:
        </div>

        <ul class="mb-0 ps-3">

            @foreach($errors->all() as $error)

                <li>
                    {{ $error }}
                </li>

            @endforeach

        </ul>

        <button
            type="button"
            class="btn-close"
            data-bs-dismiss="alert"
        ></button>

    </div>

@endif


{{-- =========================================================
     ACCOUNT SETTINGS
     ========================================================= --}}
<div class="settings-card">

    <div class="settings-card-header">

        <h5 class="fw-bold">
            <i class="fas fa-user-cog me-2"></i>
            Account Settings
        </h5>

    </div>


    <div class="settings-card-body">

        {{-- =================================================
             ACCOUNT INFORMATION
             ================================================= --}}
        <form
            action="{{ route('admin.settings.account') }}"
            method="POST"
        >

            @csrf
            @method('PUT')

            <div class="mb-4">

                <h6 class="settings-section-title mb-1">
                    Account Information
                </h6>

                <p class="settings-section-description mb-0">
                    Update the name and username associated with your administrator account.
                </p>

            </div>


            <div class="row g-3">

                {{-- Name --}}
                <div class="col-md-6">

                    <label
                        for="name"
                        class="form-label fw-semibold"
                    >
                        Name
                    </label>

                    <input
                        type="text"
                        id="name"
                        name="name"
                        class="form-control @error('name') is-invalid @enderror"
                        value="{{ old('name', Auth::user()->name) }}"
                        required
                    >

                    @error('name')

                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>

                    @enderror

                </div>


                {{-- Username --}}
                <div class="col-md-6">

                    <label
                        for="username"
                        class="form-label fw-semibold"
                    >
                        Username
                    </label>

                    <input
                        type="text"
                        id="username"
                        name="username"
                        class="form-control @error('username') is-invalid @enderror"
                        value="{{ old('username', Auth::user()->username) }}"
                        required
                    >

                    @error('username')

                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>

                    @enderror

                </div>

            </div>


            <div class="mt-4">

                <button
                    type="submit"
                    class="btn btn-primary"
                >
                    <i class="fas fa-save me-1"></i>
                    Save Changes
                </button>

            </div>

        </form>


        {{-- Divider --}}
        <hr class="settings-divider">


        {{-- =================================================
             CHANGE PASSWORD
             ================================================= --}}
        <div class="password-section">

            <div class="mb-4">

                <h6 class="settings-section-title mb-1">
                    <i class="fas fa-lock me-2 text-primary"></i>
                    Change Password
                </h6>

                <p class="settings-section-description mb-0">
                    Update your administrator account password.
                </p>

            </div>


            <form
                action="{{ route('admin.settings.password') }}"
                method="POST"
            >

                @csrf
                @method('PUT')


                <div class="row g-3">

                    {{-- Current Password --}}
                    <div class="col-md-4">

                        <label
                            for="current_password"
                            class="form-label fw-semibold"
                        >
                            Current Password
                        </label>

                        <input
                            type="password"
                            id="current_password"
                            name="current_password"
                            class="form-control @error('current_password') is-invalid @enderror"
                            required
                        >

                        @error('current_password')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- New Password --}}
                    <div class="col-md-4">

                        <label
                            for="password"
                            class="form-label fw-semibold"
                        >
                            New Password
                        </label>

                        <input
                            type="password"
                            id="password"
                            name="password"
                            class="form-control @error('password') is-invalid @enderror"
                            required
                        >

                        @error('password')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- Confirm New Password --}}
                    <div class="col-md-4">

                        <label
                            for="password_confirmation"
                            class="form-label fw-semibold"
                        >
                            Confirm New Password
                        </label>

                        <input
                            type="password"
                            id="password_confirmation"
                            name="password_confirmation"
                            class="form-control"
                            required
                        >

                    </div>

                </div>


                <div class="mt-4">

                    <button
                        type="submit"
                        class="btn btn-outline-primary"
                    >
                        <i class="fas fa-key me-1"></i>
                        Change Password
                    </button>

                </div>

            </form>

        </div>

    </div>

</div>


{{-- =========================================================
     VIOLATION MANAGEMENT
     ========================================================= --}}
<div class="settings-card">

    <div class="settings-card-header">

        <h5 class="fw-bold">
            <i class="fas fa-list-check me-2"></i>
            Violation Management
        </h5>

    </div>


    <div class="settings-card-body">

        <div class="violation-management-box">

            <div class="d-flex justify-content-between align-items-center">

                <div class="d-flex align-items-center gap-3">

                    <div class="violation-icon-box">

                        <i class="fas fa-list-check"></i>

                    </div>


                    <div>

                        <h6 class="settings-section-title mb-1">
                            Violation Types
                        </h6>

                        <p class="settings-section-description mb-0">
                            Add, edit, and manage the violation types used by
                            POSO Enforcers when recording violations.
                        </p>

                    </div>

                </div>


                <a
                    href="{{ route('admin.violation-types.index') }}"
                    class="btn btn-outline-primary"
                >
                    <i class="fas fa-list me-1"></i>
                    Manage Violations
                </a>

            </div>

        </div>

    </div>

</div>


</div>

@endsection
