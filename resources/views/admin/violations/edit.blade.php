@extends('layouts.admin')

@section('title', 'Edit Violation')

@section('content')

    <div class="container-fluid">

        <h2>Edit Violation</h2>

        <form action="{{ route('violations.update', $violation->id) }}" method="POST">

            @csrf
            @method('PUT')

            <h4>Driver Information</h4>

            <div class="row">

                <div class="col-md-4">
                    <label>First Name</label>
                    <input type="text" name="first_name" class="form-control"
                        value="{{ $violation->driver->first_name ?? '' }}">
                </div>

                <div class="col-md-4">
                    <label>Middle Name</label>
                    <input type="text" name="middle_name" class="form-control"
                        value="{{ $violation->driver->middle_name ?? '' }}">
                </div>

                <div class="col-md-4">
                    <label>Last Name</label>
                    <input type="text" name="last_name" class="form-control"
                        value="{{ $violation->driver->last_name ?? '' }}">
                </div>

            </div>

            <div class="row mt-3">

                <div class="col-md-6">
                    <label>License Number</label>
                    <input type="text" name="license_number" class="form-control"
                        value="{{ $violation->driver->license_number ?? '' }}">
                </div>

                <div class="col-md-6">
                    <label>License Type</label>
                    <input type="text" name="license_type" class="form-control"
                        value="{{ $violation->driver->license_type ?? '' }}">
                </div>

            </div>

            <div class="row mt-3">

                <div class="col-md-6">
                    <label>Address</label>
                    <input type="text" name="address" class="form-control"
                        value="{{ $violation->driver->address ?? '' }}">
                </div>

                <div class="col-md-6">
                    <label>Contact Number</label>
                    <input type="text" name="contact_number" class="form-control"
                        value="{{ $violation->driver->contact_number ?? '' }}">
                </div>

            </div>

            <h4 class="mt-4">
                Violation Information
            </h4>

            <label>Violation Type</label>

            <div id="violationRows">

                @php
                    $selectedViolationTypes = $violation->violationTypes;

                    if ($selectedViolationTypes->count() === 0 && $violation->violationType) {
                        $selectedViolationTypes = collect([$violation->violationType]);
                    }
                @endphp

                @foreach ($selectedViolationTypes as $index => $selectedType)
                    <div class="violation-row mb-3">

                        <div class="input-group">

                            <select name="{{ $index === 0 ? 'violation_type_id' : 'additional_violation_type_ids[]' }}"
                                class="form-control">

                                @foreach ($violationTypes as $type)
                                    <option value="{{ $type->id }}"
                                        {{ $selectedType->id == $type->id ? 'selected' : '' }}>

                                        {{ $type->name }}

                                    </option>
                                @endforeach

                            </select>

                            @if ($index > 0)
                                <button type="button" class="btn btn-danger remove-violation">

                                    Remove

                                </button>
                            @endif

                        </div>

                    </div>
                @endforeach

                @if ($selectedViolationTypes->count() === 0)

                    <div class="violation-row mb-3">

                        <select name="violation_type_id" class="form-control">

                            @foreach ($violationTypes as $type)
                                <option value="{{ $type->id }}">
                                    {{ $type->name }}
                                </option>
                            @endforeach

                        </select>

                    </div>

                @endif

            </div>

            <button type="button" id="addViolation" class="btn btn-secondary mb-3">

                + Add Violation

            </button>

            <label class="mt-3">
                Status
            </label>

            <select name="status" class="form-control">

                <option value="Pending" {{ $violation->status == 'Pending' ? 'selected' : '' }}>

                    Pending

                </option>

                <option value="Settled" {{ $violation->status == 'Settled' ? 'selected' : '' }}>

                    Settled

                </option>

            </select>

            <label class="mt-3">
                Remarks
            </label>

            <textarea name="remarks" class="form-control">{{ $violation->remarks }}</textarea>

            <button class="btn btn-primary mt-4">
                Save Changes
            </button>

            <a href="{{ route('violations.show', $violation->id) }}" class="btn btn-secondary mt-4">

                Cancel

            </a>

        </form>

    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {

            const violationRows = document.getElementById('violationRows');
            const addViolationButton = document.getElementById('addViolation');

            if (!violationRows || !addViolationButton) {
                return;
            }

            addViolationButton.addEventListener('click', function() {

                const row = document.createElement('div');

                row.className = 'violation-row mb-3';

                row.innerHTML = `
            <div class="input-group">

                <select
                    name="additional_violation_type_ids[]"
                    class="form-control">

                    @foreach ($violationTypes as $type)
                        <option value="{{ $type->id }}">
                            {{ $type->name }}
                        </option>
                    @endforeach

                </select>

                <button
                    type="button"
                    class="btn btn-danger remove-violation">

                    Remove

                </button>

            </div>
        `;

                violationRows.appendChild(row);

                setupRemoveButton(row);
            });

            function setupRemoveButton(row) {

                const removeButton = row.querySelector('.remove-violation');

                if (removeButton) {

                    removeButton.addEventListener('click', function() {
                        row.remove();
                    });

                }
            }

            document.querySelectorAll('.violation-row').forEach(function(row) {
                setupRemoveButton(row);
            });

        });
    </script>

@endsection
