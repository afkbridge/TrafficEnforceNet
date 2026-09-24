@extends('layouts.admin')

@section('title', 'Settings')

@section('content')

<div class="container-fluid">


{{-- Page Header --}}
<div class="page-header mb-4">
    <h2 class="fw-bold mb-1">Settings</h2>
    <p class="text-muted mb-0">
        Manage your administrator account and system violation types.
    </p>
</div>

{{-- Success Message --}}
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button"
                class="btn-close"
                data-bs-dismiss="alert">
        </button>
    </div>
@endif

{{-- Error Message --}}
@if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <ul class="mb-0">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button"
                class="btn-close"
                data-bs-dismiss="alert">
        </button>
    </div>
@endif


{{-- Account Settings --}}
<div class="card border-0 shadow-sm mb-4">

    <div class="card-header bg-white py-3">
        <h5 class="fw-bold mb-0">
            <i class="fas fa-user-cog me-2"></i>
            Account Settings
        </h5>
    </div>

    <div class="card-body">

        {{-- Account Information --}}
        <form action="{{ route('admin.settings.account') }}" method="POST">

            @csrf
            @method('PUT')

            <div class="row g-3">

                {{-- Name --}}
                <div class="col-md-6">
                    <label for="name" class="form-label fw-semibold">
                        Name
                    </label>

                    <input type="text"
                           id="name"
                           name="name"
                           class="form-control @error('name') is-invalid @enderror"
                           value="{{ old('name', Auth::user()->name) }}"
                           required>

                    @error('name')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                {{-- Username --}}
                <div class="col-md-6">
                    <label for="username" class="form-label fw-semibold">
                        Username
                    </label>

                    <input type="text"
                           id="username"
                           name="username"
                           class="form-control @error('username') is-invalid @enderror"
                           value="{{ old('username', Auth::user()->username) }}"
                           required>

                    @error('username')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

            </div>

            <div class="mt-3">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-1"></i>
                    Save Changes
                </button>
            </div>

        </form>

        <hr class="my-4">

        {{-- Change Password --}}
        <div class="mb-3">
            <h6 class="fw-bold mb-1">
                Change Password
            </h6>

            <p class="text-muted small mb-0">
                Update your administrator account password.
            </p>
        </div>

        <form action="{{ route('admin.settings.password') }}" method="POST">

            @csrf
            @method('PUT')

            <div class="row g-3">

                {{-- Current Password --}}
                <div class="col-md-4">
                    <label for="current_password"
                           class="form-label fw-semibold">
                        Current Password
                    </label>

                    <input type="password"
                           id="current_password"
                           name="current_password"
                           class="form-control @error('current_password') is-invalid @enderror"
                           required>

                    @error('current_password')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                {{-- New Password --}}
                <div class="col-md-4">
                    <label for="password"
                           class="form-label fw-semibold">
                        New Password
                    </label>

                    <input type="password"
                           id="password"
                           name="password"
                           class="form-control @error('password') is-invalid @enderror"
                           required>

                    @error('password')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                {{-- Confirm New Password --}}
                <div class="col-md-4">
                    <label for="password_confirmation"
                           class="form-label fw-semibold">
                        Confirm New Password
                    </label>

                    <input type="password"
                           id="password_confirmation"
                           name="password_confirmation"
                           class="form-control"
                           required>
                </div>

            </div>

            <div class="mt-3">
                <button type="submit" class="btn btn-outline-primary">
                    <i class="fas fa-key me-1"></i>
                    Change Password
                </button>
            </div>

        </form>

    </div>
</div>


{{-- Violation Management --}}
<div class="card border-0 shadow-sm mb-4">

    <div class="card-header bg-white py-3">
        <h5 class="fw-bold mb-0">
            <i class="fas fa-list-check me-2"></i>
            Violation Management
        </h5>
    </div>

    <div class="card-body">

        <div class="d-flex justify-content-between align-items-center">

            <div>
                <h6 class="fw-bold mb-1">
                    Violation Types
                </h6>

                <p class="text-muted small mb-0">
                    Add, edit, and manage the violation types used by
                    POSO Enforcers when recording violations.
                </p>
            </div>

            <a href="{{ route('admin.violation-types.index') }}"
               class="btn btn-outline-primary">

                <i class="fas fa-list me-1"></i>
                Manage Violations

            </a>

        </div>

    </div>
</div>


</div>

@endsection
