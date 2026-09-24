@extends('layouts.admin')

@section('title', 'Add Citation Record')

@section('content')

<div class="container-fluid px-4 pt-3">

    {{-- ========================================================= --}}
    {{-- PAGE HEADER --}}
    {{-- ========================================================= --}}

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h1 class="h3 mb-1">Add Citation Record</h1>
            <p class="text-muted mb-0">
                Encode a physical traffic citation ticket.
            </p>
        </div>

        <a href="{{ route('violations.index') }}" class="btn btn-outline-secondary">
            <i class="fa-solid fa-arrow-left me-1"></i>
            Back
        </a>

    </div>


    {{-- ========================================================= --}}
    {{-- VALIDATION ERRORS --}}
    {{-- ========================================================= --}}

    @if ($errors->any())

        <div class="alert alert-danger">

            <strong>Please correct the following:</strong>

            <ul class="mb-0 mt-2">

                @foreach ($errors->all() as $error)

                    <li>{{ $error }}</li>

                @endforeach

            </ul>

        </div>

    @endif


    {{-- ========================================================= --}}
    {{-- MAIN CITATION FORM --}}
    {{-- ========================================================= --}}

    <div class="card shadow-sm mb-5">

        <div class="card-header bg-white py-3">

            <div class="d-flex justify-content-between align-items-center">

                <div>

                    <h5 class="mb-0">
                        <i class="fa-solid fa-file-pen me-2"></i>
                        Traffic Citation
                    </h5>

                    <small class="text-muted">
                        Enter the information exactly as written on the physical citation.
                    </small>

                </div>

                <span class="badge bg-warning text-dark px-3 py-2">
                    Pending
                </span>

            </div>

        </div>


        <form
            action="{{ route('admin.violations.store') }}"
            method="POST"
        >

            @csrf

            <div class="card-body p-4">


                {{-- ================================================= --}}
                {{-- CITATION DETAILS --}}
                {{-- ================================================= --}}

                <div class="border-bottom pb-3 mb-4">

                    <h6 class="fw-bold text-uppercase mb-3">
                        Citation Details
                    </h6>

                    <div class="row g-3">

                        <div class="col-md-6">

                            <label for="ticket_number" class="form-label">
                                Ticket Number
                            </label>

                            <input
                                type="text"
                                class="form-control"
                                id="ticket_number"
                                name="ticket_number"
                                value="{{ old('ticket_number') }}"
                                placeholder="Enter ticket number"
                            >

                        </div>


                        <div class="col-md-3">

                            <label for="violation_date" class="form-label">
                                Date <span class="text-danger">*</span>
                            </label>

                            <input
                                type="date"
                                class="form-control"
                                id="violation_date"
                                name="violation_date"
                                value="{{ old('violation_date', now()->format('Y-m-d')) }}"
                                required
                            >

                        </div>


                        <div class="col-md-3">

                            <label for="violation_time" class="form-label">
                                Time <span class="text-danger">*</span>
                            </label>

                            <input
                                type="time"
                                class="form-control"
                                id="violation_time"
                                name="violation_time"
                                value="{{ old('violation_time') }}"
                                required
                            >

                        </div>

                    </div>

                    <input
                        type="hidden"
                        name="status"
                        value="Pending"
                    >

                </div>


                {{-- ================================================= --}}
                {{-- VIOLATOR INFORMATION --}}
                {{-- ================================================= --}}

                <div class="border-bottom pb-3 mb-4">

                    <h6 class="fw-bold text-uppercase mb-3">
                        Violator Information
                    </h6>


                    <div class="row g-3">


                        <div class="col-md-4">

                            <label for="last_name" class="form-label">
                                Last Name <span class="text-danger">*</span>
                            </label>

                            <input
                                type="text"
                                class="form-control"
                                id="last_name"
                                name="last_name"
                                value="{{ old('last_name') }}"
                                required
                            >

                        </div>


                        <div class="col-md-4">

                            <label for="first_name" class="form-label">
                                First Name <span class="text-danger">*</span>
                            </label>

                            <input
                                type="text"
                                class="form-control"
                                id="first_name"
                                name="first_name"
                                value="{{ old('first_name') }}"
                                required
                            >

                        </div>


                        <div class="col-md-4">

                            <label for="middle_name" class="form-label">
                                Middle Name
                            </label>

                            <input
                                type="text"
                                class="form-control"
                                id="middle_name"
                                name="middle_name"
                                value="{{ old('middle_name') }}"
                            >

                        </div>


                        <div class="col-md-8">

                            <label for="address" class="form-label">
                                Address <span class="text-danger">*</span>
                            </label>

                            <input
                                type="text"
                                class="form-control"
                                id="address"
                                name="address"
                                value="{{ old('address') }}"
                                placeholder="Complete address"
                                required
                            >

                        </div>


                        <div class="col-md-4">

                            <label for="contact_number" class="form-label">
                                Contact Number
                            </label>

                            <input
                                type="text"
                                class="form-control"
                                id="contact_number"
                                name="contact_number"
                                value="{{ old('contact_number') }}"
                                placeholder="Contact number"
                            >

                        </div>


                        <div class="col-md-6">

                            <label for="license_number" class="form-label">
                                License Number <span class="text-danger">*</span>
                            </label>

                            <input
                                type="text"
                                class="form-control"
                                id="license_number"
                                name="license_number"
                                value="{{ old('license_number') }}"
                                placeholder="License number"
                                required
                            >

                        </div>


                        <div class="col-md-6">

                            <label for="birth_date" class="form-label">
                                Birth Date
                            </label>

                            <input
                                type="date"
                                class="form-control"
                                id="birth_date"
                                name="birth_date"
                                value="{{ old('birth_date') }}"
                            >

                        </div>

                    </div>

                </div>


                {{-- ================================================= --}}
                {{-- VEHICLE INFORMATION --}}
                {{-- ================================================= --}}

                <div class="border-bottom pb-3 mb-4">

                    <h6 class="fw-bold text-uppercase mb-3">
                        Vehicle Information
                    </h6>


                    <div class="row g-3">


                        <div class="col-md-4">

                            <label for="plate_number" class="form-label">
                                Plate Number <span class="text-danger">*</span>
                            </label>

                            <input
                                type="text"
                                class="form-control"
                                id="plate_number"
                                name="plate_number"
                                value="{{ old('plate_number') }}"
                                placeholder="Plate number"
                                required
                            >

                        </div>


                        <div class="col-md-4">

                            <label for="vehicle_type" class="form-label">
                                Vehicle Type
                            </label>

                            <input
                                type="text"
                                class="form-control"
                                id="vehicle_type"
                                name="vehicle_type"
                                value="{{ old('vehicle_type') }}"
                                placeholder="e.g. Motorcycle"
                            >

                        </div>


                        <div class="col-md-4">

                            <label for="region_number" class="form-label">
                                Region Number
                            </label>

                            <input
                                type="text"
                                class="form-control"
                                id="region_number"
                                name="region_number"
                                value="{{ old('region_number') }}"
                            >

                        </div>


                        <div class="col-md-12">

                            <label for="owner_name" class="form-label">
                                Registered Owner
                            </label>

                            <input
                                type="text"
                                class="form-control"
                                id="owner_name"
                                name="owner_name"
                                value="{{ old('owner_name') }}"
                            >

                        </div>

                    </div>

                </div>


                {{-- ================================================= --}}
                {{-- VIOLATION LOCATION --}}
                {{-- ================================================= --}}

                <div class="border-bottom pb-3 mb-4">

                    <h6 class="fw-bold text-uppercase mb-3">
                        Violation Location
                    </h6>

                    <div class="row g-3">

                        <div class="col-md-12">

                            <label for="location" class="form-label">
                                Location <span class="text-danger">*</span>
                            </label>

                            <input
                                type="text"
                                class="form-control"
                                id="location"
                                name="location"
                                value="{{ old('location') }}"
                                placeholder="Enter location where the violation occurred"
                                required
                            >

                            <small class="text-muted">
                                Enter the location manually as written or indicated on the physical citation.
                            </small>

                        </div>

                    </div>

                </div>


                {{-- ================================================= --}}
                {{-- VIOLATIONS --}}
                {{-- ================================================= --}}

                <div class="border-bottom pb-3 mb-4">

                    <div class="d-flex justify-content-between align-items-center mb-3">

                        <h6 class="fw-bold text-uppercase mb-0">
                            Violation/s
                        </h6>

                        <button
                            type="button"
                            class="btn btn-sm btn-outline-primary"
                            id="addViolationBtn"
                        >
                            <i class="fa-solid fa-plus me-1"></i>
                            Add Violation
                        </button>

                    </div>


                    {{-- PRIMARY VIOLATION --}}

                    <div class="row g-3 mb-3">

                        <div class="col-md-12">

                            <label for="violation_type_id" class="form-label">

                                Primary Violation

                                <span class="text-danger">*</span>

                            </label>

                            <select
                                class="form-select"
                                id="violation_type_id"
                                name="violation_type_id"
                                required
                            >

                                <option value="">
                                    Select violation
                                </option>

                                @foreach ($violationTypes as $type)

                                    <option
                                        value="{{ $type->id }}"
                                        {{ old('violation_type_id') == $type->id ? 'selected' : '' }}
                                    >
                                        {{ $type->name }}
                                    </option>

                                @endforeach

                                <option
                                    value="other"
                                    {{ old('violation_type_id') === 'other' ? 'selected' : '' }}
                                >
                                    Other
                                </option>

                            </select>

                        </div>

                    </div>


                    {{-- OTHER PRIMARY VIOLATION --}}

                    <div
                        id="otherViolationContainer"
                        class="mb-3"
                        style="display: none;"
                    >

                        <label for="other_violation" class="form-label">
                            Specify Other Violation
                        </label>

                        <input
                            type="text"
                            class="form-control"
                            id="other_violation"
                            name="other_violation"
                            value="{{ old('other_violation') }}"
                            placeholder="Enter violation"
                        >

                    </div>


                    {{-- ADDITIONAL VIOLATIONS --}}

                    <div id="additionalViolationsContainer"></div>

                    <small class="text-muted">
                        Use "Add Violation" only when more than one violation is written on the citation ticket.
                    </small>

                </div>


                {{-- ================================================= --}}
                {{-- REMARKS --}}
                {{-- ================================================= --}}

                <div class="border-bottom pb-3 mb-4">

                    <h6 class="fw-bold text-uppercase mb-3">
                        Remarks
                    </h6>

                    <textarea
                        class="form-control"
                        id="remarks"
                        name="remarks"
                        rows="3"
                        placeholder="Additional remarks or notes"
                    >{{ old('remarks') }}</textarea>

                </div>


                {{-- ================================================= --}}
                {{-- ENCODING INFORMATION --}}
                {{-- ================================================= --}}

                <div>

                    <h6 class="fw-bold text-uppercase mb-3">
                        Encoding Information
                    </h6>

                    <div class="row g-3">


                        <div class="col-md-6">

                            <label class="form-label">
                                Encoded By
                            </label>

                            <input
                                type="text"
                                class="form-control"
                                value="{{ Auth::user()->name }}"
                                readonly
                            >

                        </div>


                        <div class="col-md-6">

                            <label class="form-label">
                                Status
                            </label>

                            <input
                                type="text"
                                class="form-control"
                                value="Pending"
                                readonly
                            >

                        </div>

                    </div>

                </div>

            </div>


            {{-- ================================================= --}}
            {{-- FORM ACTIONS --}}
            {{-- ================================================= --}}

            <div class="card-footer bg-white d-flex justify-content-end gap-2 py-3">

                <a
                    href="{{ route('violations.index') }}"
                    class="btn btn-outline-secondary"
                >
                    Cancel
                </a>

                <button
                    type="submit"
                    class="btn btn-primary"
                >
                    <i class="fa-solid fa-floppy-disk me-1"></i>
                    Save Citation
                </button>

            </div>

        </form>

    </div>

</div>


{{-- =============================================================== --}}
{{-- JAVASCRIPT --}}
{{-- =============================================================== --}}

<script>

document.addEventListener('DOMContentLoaded', function () {

    const primaryViolation =
        document.getElementById('violation_type_id');

    const otherContainer =
        document.getElementById('otherViolationContainer');

    const otherInput =
        document.getElementById('other_violation');

    const addViolationBtn =
        document.getElementById('addViolationBtn');

    const additionalContainer =
        document.getElementById('additionalViolationsContainer');


    /*
    |--------------------------------------------------------------------------
    | PRIMARY OTHER VIOLATION
    |--------------------------------------------------------------------------
    */

    function updateOtherVisibility() {

        if (primaryViolation.value === 'other') {

            otherContainer.style.display = 'block';

            otherInput.required = true;

        } else {

            otherContainer.style.display = 'none';

            otherInput.required = false;

            otherInput.value = '';

        }

    }


    primaryViolation.addEventListener(
        'change',
        updateOtherVisibility
    );

    updateOtherVisibility();


    /*
    |--------------------------------------------------------------------------
    | ADDITIONAL VIOLATION
    |--------------------------------------------------------------------------
    */

    function createViolationRow() {

        const wrapper = document.createElement('div');

        wrapper.className = 'border rounded p-3 mb-3';

        wrapper.innerHTML = `

            <div class="row g-2 align-items-end">

                <div class="col-md-10">

                    <label class="form-label">
                        Additional Violation
                    </label>

                    <select
                        class="form-select additional-violation-select"
                        name="additional_violation_type_ids[]"
                    >

                        <option value="">
                            Select violation
                        </option>

                        @foreach ($violationTypes as $type)

                            <option value="{{ $type->id }}">
                                {{ $type->name }}
                            </option>

                        @endforeach

                        <option value="other">
                            Other
                        </option>

                    </select>


                    <div
                        class="additional-other-container mt-2"
                        style="display: none;"
                    >

                        <input
                            type="text"
                            class="form-control"
                            name="additional_other_violation_names[]"
                            placeholder="Specify other violation"
                        >

                    </div>

                </div>


                <div class="col-md-2">

                    <button
                        type="button"
                        class="btn btn-outline-danger w-100 remove-violation"
                    >
                        <i class="fa-solid fa-trash me-1"></i>
                        Remove
                    </button>

                </div>

            </div>

        `;


        additionalContainer.appendChild(wrapper);


        const select =
            wrapper.querySelector('.additional-violation-select');

        const otherDiv =
            wrapper.querySelector('.additional-other-container');

        const otherField =
            wrapper.querySelector(
                'input[name="additional_other_violation_names[]"]'
            );


        select.addEventListener('change', function () {

            if (this.value === 'other') {

                otherDiv.style.display = 'block';

                otherField.required = true;

            } else {

                otherDiv.style.display = 'none';

                otherField.required = false;

                otherField.value = '';

            }

        });


        wrapper
            .querySelector('.remove-violation')
            .addEventListener('click', function () {

                wrapper.remove();

            });

    }


    addViolationBtn.addEventListener(
        'click',
        createViolationRow
    );

});

</script>

@endsection
