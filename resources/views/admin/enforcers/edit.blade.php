@extends('layouts.admin')

@section('title', 'Edit Enforcer')

@section('content')

<div class="container-fluid">

    <div class="card shadow-sm">

        <div class="card-header">
            <h4>Edit Enforcer</h4>
        </div>

        <div class="card-body">

            <form action="{{ route('enforcers.update', $enforcer->id) }}" method="POST">

                @csrf
                @method('PUT')

                <div class="row">

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Badge Number</label>
                        <input
                            type="text"
                            name="badge_number"
                            class="form-control"
                            value="{{ old('badge_number', $enforcer->badge_number) }}"
                            required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Employment Status</label>

                        <select
                            name="employment_status"
                            class="form-select"
                            required>

                            <option value="Active"
                                {{ $enforcer->employment_status == 'Active' ? 'selected' : '' }}>
                                Active
                            </option>

                            <option value="Inactive"
                                {{ $enforcer->employment_status == 'Inactive' ? 'selected' : '' }}>
                                Inactive
                            </option>

                        </select>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">First Name</label>

                        <input
                            type="text"
                            name="first_name"
                            class="form-control"
                            value="{{ old('first_name', $enforcer->first_name) }}"
                            required>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">Middle Name</label>

                        <input
                            type="text"
                            name="middle_name"
                            class="form-control"
                            value="{{ old('middle_name', $enforcer->middle_name) }}">
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">Last Name</label>

                        <input
                            type="text"
                            name="last_name"
                            class="form-control"
                            value="{{ old('last_name', $enforcer->last_name) }}"
                            required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Contact Number</label>

                        <input
                            type="text"
                            name="contact_number"
                            class="form-control"
                            value="{{ old('contact_number', $enforcer->contact_number) }}">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Email</label>

                        <input
                            type="email"
                            name="email"
                            class="form-control"
                            value="{{ old('email', $enforcer->email) }}">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Position</label>

                        <select
                            name="position"
                            class="form-select"
                            required>

                            <option value="">Select Position</option>

                            <option value="Traffic Enforcer I"
                                {{ $enforcer->position == 'Traffic Enforcer I' ? 'selected' : '' }}>
                                Traffic Enforcer I
                            </option>

                            <option value="Traffic Enforcer II"
                                {{ $enforcer->position == 'Traffic Enforcer II' ? 'selected' : '' }}>
                                Traffic Enforcer II
                            </option>

                            <option value="Supervisor"
                                {{ $enforcer->position == 'Supervisor' ? 'selected' : '' }}>
                                Supervisor
                            </option>

                            <option value="Chief Enforcer"
                                {{ $enforcer->position == 'Chief Enforcer' ? 'selected' : '' }}>
                                Chief Enforcer
                            </option>

                        </select>
                    </div>

                </div>

                <button class="btn btn-primary">
                    <i class="fas fa-save"></i> Update Enforcer
                </button>

                <a href="{{ route('enforcers.index') }}"
                   class="btn btn-secondary">

                    Cancel

                </a>

            </form>

        </div>

    </div>

</div>

@endsection