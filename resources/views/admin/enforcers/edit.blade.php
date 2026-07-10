```php
@extends('layouts.admin')

@section('title', 'Edit Enforcer')

@section('content')

    <div class="container-fluid">

        {{-- Alerts --}}
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show rounded-3 shadow-sm">
                <i class="fas fa-check-circle me-2"></i>
                {{ session('success') }}

                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif


        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show rounded-3 shadow-sm">

                <strong>
                    Please fix the following errors:
                </strong>

                <ul class="mb-0 mt-2">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>

                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>

            </div>
        @endif



        {{-- Header --}}
        <div class="d-flex justify-content-between align-items-center mb-4">

            <div>
                <h2 class="fw-bold mb-1">

                    <i class="fas fa-user-shield text-primary me-2"></i>

                    Edit Enforcer

                </h2>

                <p class="text-muted mb-0">

                    Manage enforcer profile information.

                </p>

            </div>


            <a href="{{ route('enforcers.index') }}" class="btn btn-outline-secondary rounded-pill px-4">

                <i class="fas fa-arrow-left me-2"></i>

                Back

            </a>

        </div>




        {{-- ============================= --}}
        {{-- Enforcer Profile --}}
        {{-- ============================= --}}

        <div class="card border-0 shadow-sm rounded-4 mb-4">


            <div class="card-header bg-primary text-white rounded-top-4 py-3">


                <h5 class="mb-1 fw-bold">

                    <i class="fas fa-id-card me-2"></i>

                    Enforcer Profile

                </h5>


                <small>

                    Update personal and employment details.

                </small>


            </div>



            <div class="card-body p-4">


                <form action="{{ route('enforcers.update', $enforcer->id) }}" method="POST">

                    @csrf
                    @method('PUT')


                    <div class="row">


                        <div class="col-md-6 mb-3">

                            <label class="form-label fw-semibold">

                                Badge Number

                            </label>


                            <input type="text" name="badge_number" class="form-control rounded-3"
                                value="{{ old('badge_number', $enforcer->badge_number) }}" required>

                        </div>



                        <div class="col-md-6 mb-3">

                            <label class="form-label fw-semibold">

                                Employment Status

                            </label>


                            <select name="employment_status" class="form-select rounded-3">


                                <option value="Active" {{ $enforcer->employment_status == 'Active' ? 'selected' : '' }}>

                                    Active

                                </option>


                                <option value="Inactive" {{ $enforcer->employment_status == 'Inactive' ? 'selected' : '' }}>

                                    Inactive

                                </option>


                            </select>


                        </div>



                        <div class="col-md-4 mb-3">

                            <label class="form-label fw-semibold">

                                First Name

                            </label>


                            <input type="text" name="first_name" class="form-control rounded-3"
                                value="{{ old('first_name', $enforcer->first_name) }}" required>

                        </div>



                        <div class="col-md-4 mb-3">

                            <label class="form-label fw-semibold">

                                Middle Name

                            </label>


                            <input type="text" name="middle_name" class="form-control rounded-3"
                                value="{{ old('middle_name', $enforcer->middle_name) }}">


                        </div>




                        <div class="col-md-4 mb-3">


                            <label class="form-label fw-semibold">

                                Last Name

                            </label>


                            <input type="text" name="last_name" class="form-control rounded-3"
                                value="{{ old('last_name', $enforcer->last_name) }}" required>


                        </div>




                        <div class="col-md-6 mb-3">

                            <label class="form-label fw-semibold">

                                Contact Number

                            </label>


                            <input type="text" name="contact_number" class="form-control rounded-3"
                                value="{{ old('contact_number', $enforcer->contact_number) }}">


                        </div>




                        <div class="col-md-6 mb-3">


                            <label class="form-label fw-semibold">

                                Email Address

                            </label>


                            <input type="email" name="email" class="form-control rounded-3"
                                value="{{ old('email', $enforcer->email) }}">


                        </div>




                        <div class="col-md-6 mb-3">


                            <label class="form-label fw-semibold">

                                Position

                            </label>


                            <select name="position" class="form-select rounded-3">


                                <option value="">

                                    Select Position

                                </option>


                                <option value="Traffic Enforcer I"
                                    {{ $enforcer->position == 'Traffic Enforcer I' ? 'selected' : '' }}>

                                    Traffic Enforcer I

                                </option>


                                <option value="Traffic Enforcer II"
                                    {{ $enforcer->position == 'Traffic Enforcer II' ? 'selected' : '' }}>

                                    Traffic Enforcer II

                                </option>


                                <option value="Supervisor" {{ $enforcer->position == 'Supervisor' ? 'selected' : '' }}>

                                    Supervisor

                                </option>


                                <option value="Chief Enforcer"
                                    {{ $enforcer->position == 'Chief Enforcer' ? 'selected' : '' }}>

                                    Chief Enforcer

                                </option>


                            </select>


                        </div>


                    </div>




                    <hr class="my-4">



                    <div class="text-end">


                        <a href="{{ route('enforcers.index') }}" class="btn btn-light border rounded-pill px-4 me-2">

                            Cancel

                        </a>



                        <button class="btn btn-primary rounded-pill px-4">

                            <i class="fas fa-save me-2"></i>

                            Save Changes

                        </button>


                    </div>



                </form>


            </div>


        </div>





        {{-- ============================= --}}
        {{-- Reset Password --}}
        {{-- ============================= --}}


        <div class="card border-0 shadow-sm rounded-4 mb-4">


            <div class="card-header bg-warning rounded-top-4 py-3">


                <h5 class="fw-bold mb-1">

                    <i class="fas fa-key me-2"></i>

                    Reset Password

                </h5>


                <small>

                    Change the login password assigned to this enforcer.

                </small>


            </div>



            <div class="card-body p-4">

                <form action="{{ route('enforcers.resetPassword', $enforcer->id) }}" method="POST">

                    @csrf
                    @method('PUT')


                    <div class="row">


                        <div class="col-md-6 mb-3">

                            <label class="form-label fw-semibold">

                                New Password

                            </label>


                            <input type="password" name="password" class="form-control rounded-3"
                                placeholder="Enter new password" required>

                        </div>




                        <div class="col-md-6 mb-3">


                            <label class="form-label fw-semibold">

                                Confirm Password

                            </label>


                            <input type="password" name="password_confirmation" class="form-control rounded-3"
                                placeholder="Confirm password" required>


                        </div>


                    </div>



                    <div class="d-flex justify-content-end">

                        <button type="submit" class="btn btn-warning text-white rounded-pill px-4">

                            <i class="fas fa-lock me-2"></i>

                            Reset Password

                        </button>

                    </div>


                </form>


            </div>




        </div>





    </div>


    <






    {{-- ============================= --}}
    {{-- Danger Zone --}}
    {{-- ============================= --}}


    <div class="card border-danger shadow-sm rounded-4 mb-4">


        <div class="card-header bg-danger text-white rounded-top-4 py-3">


            <h5 class="fw-bold mb-1">

                <i class="fas fa-exclamation-triangle me-2"></i>

                Danger Zone

            </h5>


            <small>

                Permanent actions.

            </small>


        </div>



        <div class="card-body p-4">


            <div class="d-flex justify-content-between align-items-center">


                <div>

                    <h5 class="text-danger fw-bold">
                        Delete Enforcer
                    </h5>

                    <p class="text-muted">
                        Permanently remove this enforcer record and their linked account. This action cannot be undone.
                    </p>


                </div>




                <form action="{{ route('enforcers.destroy', $enforcer->id) }}" method="POST"
                    onsubmit="return confirm('Delete this enforcer?');">

                    @csrf
                    @method('DELETE')


                    <button class="btn btn-danger rounded-pill px-4">

                        <i class="fas fa-trash me-2"></i>

                        Delete

                    </button>


                </form>


            </div>


        </div>


    </div>





    {{-- ============================= --}}
    {{-- Security Information --}}
    {{-- ============================= --}}


    <div class="card border-0 shadow-sm rounded-4">


        <div class="card-header bg-dark text-white rounded-top-4 py-3">


            <h5 class="fw-bold mb-0">

                <i class="fas fa-shield-alt me-2"></i>

                Security Information

            </h5>


        </div>



        <div class="card-body p-4">


            <div class="row">


                <div class="col-md-6 mb-3">

                    <i class="fas fa-lock text-success me-2"></i>

                    Passwords are encrypted securely.

                </div>


                <div class="col-md-6 mb-3">

                    <i class="fas fa-user-shield text-primary me-2"></i>

                    Only administrators can manage accounts.

                </div>


                <div class="col-md-6">

                    <i class="fas fa-history text-warning me-2"></i>

                    Future login activities can be tracked.

                </div>


            </div>


        </div>


    </div>



    </div>


@endsection
```
