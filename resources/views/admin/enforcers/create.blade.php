@extends('layouts.admin')

@section('title', 'Add Enforcer')

@section('content')
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="container-fluid">

        <div class="card shadow-sm">

            <div class="card-header">

                <h4>Add Enforcer</h4>

            </div>

            <div class="card-body">

                <form action="{{ route('enforcers.store') }}" method="POST">

                    @csrf

                    <div class="row">

                        <div class="col-md-6 mb-3">

                            <label class="form-label">Badge Number</label>

                            <input type="text" name="badge_number" class="form-control" required>

                        </div>

                        <div class="col-md-6 mb-3">

                            <label class="form-label">Employment Status</label>

                            <select name="employment_status" class="form-select" required>

                                <option value="Active">Active</option>

                                <option value="Inactive">Inactive</option>

                            </select>

                        </div>

                        <div class="col-md-4 mb-3">

                            <label class="form-label">First Name</label>

                            <input type="text" name="first_name" class="form-control" required>

                        </div>

                        <div class="col-md-4 mb-3">

                            <label class="form-label">Middle Name</label>

                            <input type="text" name="middle_name" class="form-control">

                        </div>

                        <div class="col-md-4 mb-3">

                            <label class="form-label">Last Name</label>

                            <input type="text" name="last_name" class="form-control" required>

                        </div>

                        <div class="col-md-6 mb-3">

                            <label class="form-label">Contact Number</label>

                            <input type="text" name="contact_number" class="form-control">

                        </div>

                        <div class="col-md-6 mb-3">

                            <label class="form-label">Email</label>

                            <input type="email" name="email" class="form-control">

                        </div>

                        <div class="col-md-6 mb-3">

                            <label class="form-label">Position</label>

                            <select name="position" class="form-select" required>

                                <option value="">Select Position</option>

                                <option value="Traffic Enforcer I">Traffic Enforcer I</option>

                                <option value="Traffic Enforcer II">Traffic Enforcer II</option>

                                <option value="Supervisor">Supervisor</option>

                                <option value="Chief Enforcer">Chief Enforcer</option>

                            </select>

                        </div>

                        <div class="col-md-6 mb-3">

                            <label class="form-label">Account Password</label>

                            <input type="password" name="password" class="form-control" required>

                        </div>


                        <div class="col-md-6 mb-3">

                            <label class="form-label">Confirm Password</label>

                            <input type="password" name="password_confirmation" class="form-control" required>

                        </div>

                    </div>

                    <button class="btn btn-primary">
                        Save Enforcer
                    </button>

                    <a href="{{ route('enforcers.index') }}" class="btn btn-secondary">

                        Cancel

                    </a>

                </form>

            </div>

        </div>

    </div>

@endsection
