@extends('layouts.admin')

@section('title', 'Add Citation Record')

@section('content')

    <div class="violation-details-page">

        {{-- =========================================================
         PAGE HEADER
         ========================================================= --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="fw-bold mb-1">Add Citation Record</h1>
                <p class="text-muted mb-0">
                    Manually encode a physical traffic citation ticket.
                </p>
            </div>

            <a href="{{ route('violations.index') }}" class="btn btn-outline-secondary">
                <i class="fa-solid fa-arrow-left me-1"></i>
                Back
            </a>
        </div>


        {{-- =========================================================
         VALIDATION ERRORS
         ========================================================= --}}
        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show">
                <strong>Please correct the following:</strong>

                <ul class="mb-0 mt-2">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>

                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif


        {{-- =========================================================
         MAIN CITATION CARD
         ========================================================= --}}
        <div class="card shadow-sm mb-5">

            {{-- CARD HEADER --}}
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


            {{-- =========================================================
             FORM
             ========================================================= --}}
            <form action="{{ route('admin.violations.store') }}" method="POST" id="citationForm"
                enctype="multipart/form-data" novalidate>
                @csrf

                <div class="card-body p-4">


                    {{-- =================================================
                     CITATION DETAILS
                     ================================================= --}}
                    <div class="border-bottom pb-3 mb-4">

                        <h6 class="fw-bold text-uppercase mb-3">
                            Citation Details
                        </h6>

                        <div class="row g-3">

                            {{-- Ticket Number --}}
                            <div class="col-md-6">
                                <label for="ticket_number" class="form-label">
                                    Ticket Number
                                    <span class="text-danger">*</span>
                                </label>

                                <input type="text" class="form-control" id="ticket_number" name="ticket_number"
                                    value="{{ old('ticket_number') }}" placeholder="Enter 6-digit ticket number"
                                    maxlength="6" minlength="6" inputmode="numeric" pattern="[0-9]{6}" required
                                    autocomplete="off">

                                <small class="text-muted">
                                    Must contain exactly 6 digits.
                                </small>

                                <div class="invalid-feedback">
                                    Ticket number must contain exactly 6 digits.
                                </div>
                            </div>


                            {{-- Date --}}
                            <div class="col-md-3">
                                <label for="violation_date" class="form-label">
                                    Date
                                    <span class="text-danger">*</span>
                                </label>

                                <input type="date" class="form-control" id="violation_date" name="violation_date"
                                    value="{{ old('violation_date', now()->setTimezone('Asia/Manila')->format('Y-m-d')) }}"
                                    required>

                                <div class="invalid-feedback">
                                    Please enter the citation date.
                                </div>
                            </div>


                            {{-- Time --}}
                            <div class="col-md-3">
                                <label for="violation_time" class="form-label">
                                    Time
                                    <span class="text-danger">*</span>
                                </label>

                                <input type="time" class="form-control" id="violation_time" name="violation_time"
                                    value="{{ old('violation_time') }}" required>

                                <div class="invalid-feedback">
                                    Please enter the citation time.
                                </div>
                            </div>

                        </div>

                        {{-- Always Pending --}}
                        <input type="hidden" name="status" value="Pending">

                    </div>


                    {{-- =================================================
                     VIOLATOR INFORMATION
                     ================================================= --}}
                    <div class="border-bottom pb-3 mb-4">

                        <h6 class="fw-bold text-uppercase mb-3">
                            Violator Information
                        </h6>

                        <div class="row g-3">

                            {{-- Last Name --}}
                            <div class="col-md-4">
                                <label for="last_name" class="form-label">
                                    Last Name
                                    <span class="text-danger">*</span>
                                </label>

                                <input type="text" class="form-control" id="last_name" name="last_name"
                                    value="{{ old('last_name') }}" placeholder="Last name" maxlength="255" required
                                    autocomplete="off">

                                <div class="invalid-feedback">
                                    Please enter the last name.
                                </div>
                            </div>


                            {{-- First Name --}}
                            <div class="col-md-4">
                                <label for="first_name" class="form-label">
                                    First Name
                                    <span class="text-danger">*</span>
                                </label>

                                <input type="text" class="form-control" id="first_name" name="first_name"
                                    value="{{ old('first_name') }}" placeholder="First name" maxlength="255" required
                                    autocomplete="off">

                                <div class="invalid-feedback">
                                    Please enter the first name.
                                </div>
                            </div>


                            {{-- Middle Name --}}
                            <div class="col-md-4">
                                <label for="middle_name" class="form-label">
                                    Middle Name
                                </label>

                                <input type="text" class="form-control" id="middle_name" name="middle_name"
                                    value="{{ old('middle_name') }}" placeholder="Middle name" maxlength="255"
                                    autocomplete="off">

                                <div class="invalid-feedback">
                                    Please enter the middle name.
                                </div>
                            </div>


                            {{-- Address --}}
                            <div class="col-md-8">
                                <label for="address" class="form-label">
                                    Address
                                    <span class="text-danger">*</span>
                                </label>

                                <input type="text" class="form-control" id="address" name="address"
                                    value="{{ old('address') }}" placeholder="Complete address" maxlength="255" required
                                    autocomplete="off">

                                <div class="invalid-feedback">
                                    Please enter the complete address.
                                </div>
                            </div>


                            {{-- Contact Number --}}
                            <div class="col-md-4">
                                <label for="contact_number" class="form-label">
                                    Contact Number
                                </label>

                                <input type="text" class="form-control" id="contact_number" name="contact_number"
                                    value="{{ old('contact_number') }}" placeholder="Contact number" maxlength="255"
                                    inputmode="tel" autocomplete="off">
                            </div>


                            {{-- License Number --}}
                            <div class="col-md-6">
                                <label for="license_number" class="form-label">
                                    License Number
                                    <span class="text-danger">*</span>
                                </label>

                                <input type="text" class="form-control" id="license_number" name="license_number"
                                    value="{{ old('license_number') }}" placeholder="Driver's license number"
                                    maxlength="255" required autocomplete="off">

                                <div class="invalid-feedback">
                                    Please enter the license number.
                                </div>
                            </div>


                            {{-- Birth Date --}}
                            <div class="col-md-6">
                                <label for="birth_date" class="form-label">
                                    Birth Date
                                    <span class="text-danger">*</span>
                                </label>

                                <input type="date" class="form-control" id="birth_date" name="birth_date"
                                    value="{{ old('birth_date') }}" required>

                                <div class="invalid-feedback">
                                    Please enter the birth date.
                                </div>
                            </div>

                        </div>
                    </div>


                    {{-- =================================================
VEHICLE INFORMATION
================================================= --}}

                    <div class="border-bottom pb-3 mb-4">


                        <h6 class="fw-bold text-uppercase mb-3">
                            Vehicle Information
                        </h6>

                        <div class="row g-3">

                            {{-- Plate Number --}}
                            <div class="col-md-6">

                                <label for="plate_number" class="form-label">
                                    Plate Number
                                    <span class="text-danger">*</span>
                                </label>

                                <input type="text" class="form-control" id="plate_number" name="plate_number"
                                    value="{{ old('plate_number') }}" placeholder="Plate number" maxlength="255"
                                    required autocomplete="off">

                                <div class="invalid-feedback">
                                    Please enter the plate number.
                                </div>

                            </div>


                            {{-- Vehicle Type --}}
                            <div class="col-md-6">

                                <label for="vehicle_type" class="form-label">
                                    Vehicle Type
                                </label>

                                <select class="form-select" id="vehicle_type" name="vehicle_type">
                                    <option value="" disabled {{ old('vehicle_type') ? '' : 'selected' }}>
                                        Select vehicle type
                                    </option>

                                    <option value="MC" {{ old('vehicle_type') === 'MC' ? 'selected' : '' }}>
                                        MC
                                    </option>

                                    <option value="MTC Private"
                                        {{ old('vehicle_type') === 'MTC Private' ? 'selected' : '' }}>
                                        MTC Private
                                    </option>

                                    <option value="MTC For Hire"
                                        {{ old('vehicle_type') === 'MTC For Hire' ? 'selected' : '' }}>
                                        MTC For Hire
                                    </option>

                                    <option value="PUJ" {{ old('vehicle_type') === 'PUJ' ? 'selected' : '' }}>
                                        PUJ
                                    </option>

                                    <option value="Private Vehicle"
                                        {{ old('vehicle_type') === 'Private Vehicle' ? 'selected' : '' }}>
                                        Private Vehicle
                                    </option>

                                    <option value="Others" {{ old('vehicle_type') === 'Others' ? 'selected' : '' }}>
                                        Others
                                    </option>
                                </select>

                            </div>


                            {{-- Specify Other Vehicle Type --}}
                            <div id="otherVehicleTypeContainer" class="col-md-6 offset-md-6"
                                style="{{ old('vehicle_type') === 'Others' ? '' : 'display: none;' }}">

                                <label for="other_vehicle_type" class="form-label">
                                    Specify Vehicle Type
                                    <span class="text-danger">*</span>
                                </label>

                                <input type="text" class="form-control" id="other_vehicle_type"
                                    name="other_vehicle_type" value="{{ old('other_vehicle_type') }}"
                                    placeholder="Enter vehicle type" maxlength="255" autocomplete="off"
                                    {{ old('vehicle_type') === 'Others' ? 'required' : '' }}>

                                <div class="invalid-feedback">
                                    Please specify the vehicle type.
                                </div>

                            </div>

                        </div>


                    </div>



                    {{-- =================================================
                     VIOLATION LOCATION
                     ================================================= --}}
                    <div class="border-bottom pb-3 mb-4">

                        <h6 class="fw-bold text-uppercase mb-3">
                            Violation Location
                        </h6>

                        <label for="location" class="form-label">
                            Place of Violation
                            <span class="text-danger">*</span>
                        </label>

                        <div class="text-muted small mb-2">
                            Manually enter the place where the violation occurred.
                        </div>

                        <input type="text" name="location" id="location"
                            class="form-control @error('location') is-invalid @enderror" value="{{ old('location') }}"
                            placeholder="e.g. McArthur Highway, Tarlac City" maxlength="255" required autocomplete="off">

                        @error('location')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>


                    {{-- =================================================
                     VIOLATIONS
                     ================================================= --}}
                    <div class="border-bottom pb-3 mb-4">

                        <div class="d-flex justify-content-between align-items-center mb-3">

                            <h6 class="fw-bold text-uppercase mb-0">
                                Violation/s
                            </h6>

                            <button type="button" class="btn btn-sm btn-outline-primary" id="addViolationBtn">
                                <i class="fa-solid fa-plus me-1"></i>
                                Add Violation
                            </button>

                        </div>


                        {{-- PRIMARY VIOLATION --}}
                        <div class="border rounded p-3 bg-light">

                            <div class="d-flex justify-content-between align-items-center mb-2">

                                <label for="violation_type_id" class="form-label fw-semibold mb-0">
                                    Primary Violation
                                    <span class="text-danger">*</span>
                                </label>

                                <span class="badge bg-secondary">
                                    Violation 1
                                </span>

                            </div>


                            <select class="form-select" id="violation_type_id" name="violation_type_id" required>
                                <option value="">
                                    Select violation
                                </option>

                                @foreach ($violationTypes as $type)
                                    <option value="{{ $type->id }}"
                                        {{ old('violation_type_id') == $type->id ? 'selected' : '' }}>
                                        {{ $type->name }}
                                    </option>
                                @endforeach

                                <option value="other" {{ old('violation_type_id') === 'other' ? 'selected' : '' }}>
                                    Other
                                </option>
                            </select>

                            <div class="invalid-feedback">
                                Please select a primary violation.
                            </div>


                            {{-- PRIMARY OTHER --}}
                            <div id="otherViolationContainer"
                                class="mt-3 {{ old('violation_type_id') === 'other' ? '' : 'd-none' }}">
                                <label for="other_violation" class="form-label">
                                    Specify Other Violation
                                    <span class="text-danger">*</span>
                                </label>

                                <input type="text" class="form-control" id="other_violation" name="other_violation"
                                    value="{{ old('other_violation') }}"
                                    placeholder="Enter the violation written on the citation" maxlength="255"
                                    {{ old('violation_type_id') === 'other' ? 'required' : '' }}>

                                <div class="invalid-feedback">
                                    Please specify the other violation.
                                </div>
                            </div>

                        </div>


                        {{-- ADDITIONAL VIOLATIONS --}}
                        <div id="additionalViolationsContainer" class="mt-3">
                            {{-- Additional violation rows are created by JavaScript --}}
                        </div>

                        <small class="text-muted d-block mt-2">
                            Use "Add Violation" only when more than one violation
                            is written on the physical citation.
                        </small>

                    </div>


                    {{-- =================================================
                     REMARKS
                     ================================================= --}}
                    <div class="border-bottom pb-3 mb-4">

                        <h6 class="fw-bold text-uppercase mb-3">
                            Remarks
                        </h6>

                        <textarea class="form-control" id="remarks" name="remarks" rows="3" maxlength="1000"
                            placeholder="Additional remarks or notes">{{ old('remarks') }}</textarea>

                        <small class="text-muted">
                            Maximum 1000 characters.
                        </small>

                    </div>


                    {{-- =================================================
                     CITATION / EVIDENCE IMAGES
                     ================================================= --}}
                    <div class="border-bottom pb-3 mb-4">

                        <h6 class="fw-bold text-uppercase mb-3">
                            Citation / Evidence
                        </h6>

                        <div class="row g-3">

                            {{-- Citation Ticket Image --}}
                            <div class="col-md-6">

                                <label for="ticket_image" class="form-label">
                                    Citation Ticket Image
                                </label>

                                <input type="file" class="form-control" id="ticket_image" name="ticket_image"
                                    accept="image/jpeg,image/png,image/webp">

                                <small class="text-muted">
                                    Optional. JPG, JPEG, PNG, or WEBP. Maximum 5 MB.
                                </small>

                                <div id="ticketImageError" class="text-danger small mt-1 d-none"></div>

                            </div>


                            {{-- Evidence Images --}}
                            <div class="col-md-6">

                                <label for="evidence_images" class="form-label">
                                    Evidence Photo/s
                                </label>

                                <input type="file" class="form-control" id="evidence_images" name="evidence_images[]"
                                    accept="image/jpeg,image/png,image/webp" multiple>

                                <small class="text-muted">
                                    Optional. Multiple images may be selected.
                                    Maximum 5 MB each.
                                </small>

                                <div id="evidenceImageError" class="text-danger small mt-1 d-none"></div>

                            </div>

                        </div>

                    </div>


                    {{-- =================================================
                     ENCODING INFORMATION
                     ================================================= --}}
                    <div>

                        <h6 class="fw-bold text-uppercase mb-3">
                            Encoding Information
                        </h6>

                        <div class="row g-3">

                            {{-- Encoded By --}}
                            <div class="col-md-6">

                                <label class="form-label">
                                    Encoded By
                                </label>

                                <input type="text" class="form-control" value="{{ Auth::user()->name }}" readonly>

                            </div>


                            {{-- Status --}}
                            <div class="col-md-6">

                                <label class="form-label">
                                    Status
                                </label>

                                <input type="text" class="form-control" value="Pending" readonly>

                            </div>

                        </div>

                    </div>

                </div>


                {{-- =========================================================
                 FORM ACTIONS
                 ========================================================= --}}
                <div class="card-footer bg-white d-flex justify-content-end gap-2 py-3">

                    <a href="{{ route('violations.index') }}" class="btn btn-outline-secondary">
                        Cancel
                    </a>

                    <button type="submit" class="btn btn-primary" id="saveCitationBtn">
                        <i class="fa-solid fa-floppy-disk me-1"></i>
                        Save Citation
                    </button>

                </div>

            </form>

        </div>

    </div>


    {{-- ===============================================================
     JAVASCRIPT
     =============================================================== --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            const form = document.getElementById('citationForm');

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

            const saveButton =
                document.getElementById('saveCitationBtn');

            const ticketNumber =
                document.getElementById('ticket_number');

            const birthDate =
                document.getElementById('birth_date');


            // =========================================================
            // TICKET NUMBER
            // =========================================================

            if (ticketNumber) {

                ticketNumber.addEventListener('input', function() {

                    this.value = this.value
                        .replace(/[^0-9]/g, '')
                        .slice(0, 6);

                    if (this.value.length === 6) {
                        this.classList.remove('is-invalid');
                    }

                });

            }


            // =========================================================
            // BIRTH DATE
            // Prevent future birth dates.
            // =========================================================

            if (birthDate) {

                const today =
                    new Date().toISOString().split('T')[0];

                birthDate.max = today;

            }


            // =========================================================
            // VEHICLE TYPE / OTHER VEHICLE TYPE
            // =========================================================

            const vehicleTypeSelect =
                document.getElementById('vehicle_type');

            const otherVehicleTypeContainer =
                document.getElementById('otherVehicleTypeContainer');

            const otherVehicleTypeInput =
                document.getElementById('other_vehicle_type');


            function updateOtherVehicleTypeVisibility() {

                if (
                    !vehicleTypeSelect ||
                    !otherVehicleTypeContainer
                ) {
                    return;
                }

                if (vehicleTypeSelect.value === 'Others') {

                    otherVehicleTypeContainer.style.display = '';

                    if (otherVehicleTypeInput) {
                        otherVehicleTypeInput.disabled = false;
                        otherVehicleTypeInput.required = true;
                    }

                } else {

                    otherVehicleTypeContainer.style.display = 'none';

                    if (otherVehicleTypeInput) {

                        otherVehicleTypeInput.disabled = true;
                        otherVehicleTypeInput.required = false;
                        otherVehicleTypeInput.value = '';

                        otherVehicleTypeInput.classList.remove(
                            'is-invalid'
                        );
                    }

                }

            }


            if (vehicleTypeSelect) {

                vehicleTypeSelect.addEventListener(
                    'change',
                    updateOtherVehicleTypeVisibility
                );

                updateOtherVehicleTypeVisibility();

            }


            // =========================================================
            // PRIMARY OTHER VIOLATION
            // =========================================================

            function updateOtherVisibility() {

                if (primaryViolation.value === 'other') {

                    otherContainer.classList.remove('d-none');
                    otherInput.required = true;

                } else {

                    otherContainer.classList.add('d-none');
                    otherInput.required = false;
                    otherInput.value = '';

                    otherInput.classList.remove('is-invalid');

                }

            }


            primaryViolation.addEventListener(
                'change',
                updateOtherVisibility
            );

            updateOtherVisibility();


            // =========================================================
            // CREATE ADDITIONAL VIOLATION ROW
            // =========================================================

            function createViolationRow(
                selectedValue = '',
                selectedOtherValue = ''
            ) {

                const wrapper = document.createElement('div');

                wrapper.className =
                    'border rounded p-3 mb-3 additional-violation-row bg-light';


                wrapper.innerHTML = `
            <div class="d-flex justify-content-between align-items-center mb-2">

                <label class="form-label fw-semibold mb-0">
                    Additional Violation
                    <span class="text-danger">*</span>
                </label>

                <button
                    type="button"
                    class="btn btn-sm btn-outline-danger remove-violation"
                >
                    <i class="fa-solid fa-trash me-1"></i>
                    Remove
                </button>

            </div>


            <select
                class="form-select additional-violation-select"
                name="additional_violation_type_ids[]"
                required
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

            <div class="invalid-feedback">
                Please select an additional violation.
            </div>


            <div class="additional-other-container mt-3 d-none">

                <label class="form-label">
                    Specify Other Violation
                    <span class="text-danger">*</span>
                </label>

                <input
                    type="text"
                    class="form-control additional-other-field"
                    name="additional_other_violation_names[]"
                    placeholder="Specify the other violation"
                    maxlength="255"
                >

                <div class="invalid-feedback">
                    Please specify the other violation.
                </div>

            </div>
        `;


                additionalContainer.appendChild(wrapper);


                const select =
                    wrapper.querySelector(
                        '.additional-violation-select'
                    );

                const otherDiv =
                    wrapper.querySelector(
                        '.additional-other-container'
                    );

                const otherField =
                    wrapper.querySelector(
                        '.additional-other-field'
                    );

                const removeButton =
                    wrapper.querySelector(
                        '.remove-violation'
                    );


                // =====================================================
                // RESTORE OLD VALUES
                // =====================================================

                if (selectedValue !== '') {
                    select.value = selectedValue;
                }

                if (selectedOtherValue !== '') {
                    otherField.value = selectedOtherValue;
                }


                // =====================================================
                // UPDATE ADDITIONAL OTHER VISIBILITY
                // =====================================================

                function updateAdditionalOtherVisibility() {

                    if (select.value === 'other') {

                        otherDiv.classList.remove('d-none');

                        otherField.disabled = false;
                        otherField.required = true;

                    } else {

                        otherDiv.classList.add('d-none');

                        otherField.disabled = true;
                        otherField.required = false;
                        otherField.value = '';

                        otherField.classList.remove(
                            'is-invalid'
                        );

                    }

                }


                select.addEventListener(
                    'change',
                    updateAdditionalOtherVisibility
                );

                updateAdditionalOtherVisibility();


                // =====================================================
                // REMOVE ADDITIONAL VIOLATION
                // =====================================================

                removeButton.addEventListener(
                    'click',
                    function() {
                        wrapper.remove();
                    }
                );


                return wrapper;
            }


            // =========================================================
            // ADD VIOLATION BUTTON
            // =========================================================

            addViolationBtn.addEventListener(
                'click',
                function() {
                    createViolationRow();
                }
            );


            // =========================================================
            // RESTORE OLD ADDITIONAL VIOLATIONS
            // =========================================================

            const oldAdditionalViolationIds =
                @json(old('additional_violation_type_ids', []));

            const oldAdditionalOtherNames =
                @json(old('additional_other_violation_names', []));


            if (Array.isArray(oldAdditionalViolationIds)) {

                oldAdditionalViolationIds.forEach(
                    function(selectedValue, index) {

                        createViolationRow(
                            selectedValue,
                            oldAdditionalOtherNames[index] ?? ''
                        );

                    }
                );

            }


            // =========================================================
            // FILE VALIDATION
            // =========================================================

            const MAX_FILE_SIZE =
                5 * 1024 * 1024;

            const allowedImageTypes = [
                'image/jpeg',
                'image/png',
                'image/webp'
            ];


            const ticketImage =
                document.getElementById('ticket_image');

            const evidenceImages =
                document.getElementById('evidence_images');

            const ticketImageError =
                document.getElementById('ticketImageError');

            const evidenceImageError =
                document.getElementById('evidenceImageError');


            function validateImageFile(file) {

                if (!allowedImageTypes.includes(file.type)) {
                    return 'Only JPG, JPEG, PNG, and WEBP images are allowed.';
                }

                if (file.size > MAX_FILE_SIZE) {
                    return 'Each image must not exceed 5 MB.';
                }

                return null;
            }


            if (ticketImage) {

                ticketImage.addEventListener(
                    'change',
                    function() {

                        ticketImageError.classList.add('d-none');
                        ticketImageError.textContent = '';

                        if (!this.files.length) {
                            return;
                        }

                        const error =
                            validateImageFile(this.files[0]);

                        if (error) {

                            ticketImageError.textContent = error;

                            ticketImageError.classList.remove(
                                'd-none'
                            );

                            this.value = '';
                        }

                    }
                );

            }


            if (evidenceImages) {

                evidenceImages.addEventListener(
                    'change',
                    function() {

                        evidenceImageError.classList.add('d-none');
                        evidenceImageError.textContent = '';

                        for (
                            let i = 0; i < this.files.length; i++
                        ) {

                            const error =
                                validateImageFile(this.files[i]);

                            if (error) {

                                evidenceImageError.textContent =
                                    'Evidence photo ' +
                                    (i + 1) +
                                    ': ' +
                                    error;

                                evidenceImageError.classList.remove(
                                    'd-none'
                                );

                                this.value = '';

                                break;
                            }

                        }

                    }
                );

            }


            // =========================================================
            // FORM VALIDATION
            // =========================================================

            form.addEventListener(
                'submit',
                function(event) {

                    let valid = true;


                    // =================================================
                    // NORMAL REQUIRED FIELDS
                    // =================================================

                    const requiredFields =
                        form.querySelectorAll(
                            'input[required], select[required], textarea[required]'
                        );


                    requiredFields.forEach(
                        function(field) {

                            if (field.disabled) {
                                return;
                            }

                            if (!field.value.trim()) {

                                field.classList.add(
                                    'is-invalid'
                                );

                                valid = false;

                            } else {

                                field.classList.remove(
                                    'is-invalid'
                                );

                            }

                        }
                    );


                    // =================================================
                    // TICKET NUMBER
                    // =================================================

                    const ticketValue =
                        ticketNumber.value.trim();


                    if (
                        ticketValue.length !== 6 ||
                        !/^[0-9]{6}$/.test(ticketValue)
                    ) {

                        ticketNumber.classList.add(
                            'is-invalid'
                        );

                        valid = false;

                    } else {

                        ticketNumber.classList.remove(
                            'is-invalid'
                        );

                    }


                    // =================================================
                    // BIRTH DATE
                    // =================================================

                    if (birthDate.value) {

                        const today =
                            new Date().toISOString().split('T')[0];

                        if (birthDate.value > today) {

                            birthDate.classList.add(
                                'is-invalid'
                            );

                            valid = false;

                        } else {

                            birthDate.classList.remove(
                                'is-invalid'
                            );

                        }

                    }


                    // =================================================
                    // OTHER VEHICLE TYPE
                    // =================================================

                    if (
                        vehicleTypeSelect &&
                        vehicleTypeSelect.value === 'Others'
                    ) {

                        if (
                            !otherVehicleTypeInput.value.trim()
                        ) {

                            otherVehicleTypeInput.classList.add(
                                'is-invalid'
                            );

                            valid = false;

                        } else {

                            otherVehicleTypeInput.classList.remove(
                                'is-invalid'
                            );

                        }

                    }


                    // =================================================
                    // PRIMARY OTHER
                    // =================================================

                    if (
                        primaryViolation.value === 'other'
                    ) {

                        if (
                            !otherInput.value.trim()
                        ) {

                            otherInput.classList.add(
                                'is-invalid'
                            );

                            valid = false;

                        } else {

                            otherInput.classList.remove(
                                'is-invalid'
                            );

                        }

                    }


                    // =================================================
                    // ADDITIONAL VIOLATIONS
                    // =================================================

                    const additionalRows =
                        additionalContainer.querySelectorAll(
                            '.additional-violation-row'
                        );


                    additionalRows.forEach(
                        function(row) {

                            const select =
                                row.querySelector(
                                    '.additional-violation-select'
                                );

                            const otherField =
                                row.querySelector(
                                    '.additional-other-field'
                                );


                            // Additional violation is required
                            if (!select.value) {

                                select.classList.add(
                                    'is-invalid'
                                );

                                valid = false;

                            } else {

                                select.classList.remove(
                                    'is-invalid'
                                );

                            }


                            // Additional Other requires text
                            if (
                                select.value === 'other'
                            ) {

                                if (
                                    !otherField.value.trim()
                                ) {

                                    otherField.classList.add(
                                        'is-invalid'
                                    );

                                    valid = false;

                                } else {

                                    otherField.classList.remove(
                                        'is-invalid'
                                    );

                                }

                            }

                        }
                    );


                    // =================================================
                    // FILE VALIDATION
                    // =================================================

                    if (
                        ticketImage &&
                        ticketImage.files.length
                    ) {

                        const error =
                            validateImageFile(
                                ticketImage.files[0]
                            );

                        if (error) {

                            ticketImageError.textContent =
                                error;

                            ticketImageError.classList.remove(
                                'd-none'
                            );

                            valid = false;

                        }

                    }


                    if (
                        evidenceImages &&
                        evidenceImages.files.length
                    ) {

                        for (
                            let i = 0; i < evidenceImages.files.length; i++
                        ) {

                            const error =
                                validateImageFile(
                                    evidenceImages.files[i]
                                );

                            if (error) {

                                evidenceImageError.textContent =
                                    'Evidence photo ' +
                                    (i + 1) +
                                    ': ' +
                                    error;

                                evidenceImageError.classList.remove(
                                    'd-none'
                                );

                                valid = false;

                                break;
                            }

                        }

                    }


                    // =================================================
                    // STOP SUBMISSION
                    // =================================================

                    if (!valid) {

                        event.preventDefault();

                        const firstInvalid =
                            form.querySelector(
                                '.is-invalid'
                            );


                        if (firstInvalid) {

                            firstInvalid.scrollIntoView({
                                behavior: 'smooth',
                                block: 'center'
                            });

                            firstInvalid.focus();

                        }

                        return false;
                    }


                    // =================================================
                    // PREVENT DOUBLE SUBMISSION
                    // =================================================

                    saveButton.disabled = true;

                    saveButton.innerHTML =
                        '<i class="fa-solid fa-spinner fa-spin me-1"></i> Saving...';

                }
            );


            // =========================================================
            // REMOVE INVALID STATE WHEN CORRECTED
            // =========================================================

            form.addEventListener(
                'input',
                function(event) {

                    if (
                        event.target.matches(
                            'input, textarea'
                        )
                    ) {

                        if (
                            event.target.value.trim()
                        ) {

                            event.target.classList.remove(
                                'is-invalid'
                            );

                        }

                    }

                }
            );


            form.addEventListener(
                'change',
                function(event) {

                    if (
                        event.target.matches(
                            'select, input[type="date"], input[type="time"]'
                        )
                    ) {

                        if (
                            event.target.value
                        ) {

                            event.target.classList.remove(
                                'is-invalid'
                            );

                        }

                    }

                }
            );

        });
    </script>


    {{-- ===============================================================
     PAGE-SPECIFIC STYLING
     Matches the Edit Violation page
     =============================================================== --}}
    <style>
        .violation-details-page {
            padding: 0 4px;
        }

        .violation-details-page .card {
            border: 1px solid #e5e7eb;
            border-radius: 10px;
            overflow: hidden;
        }

        .violation-details-page .card-header {
            border-bottom: 1px solid #e5e7eb;
        }

        .violation-details-page .card-footer {
            border-top: 1px solid #e5e7eb;
        }

        .violation-details-page h1 {
            font-size: 1.65rem;
            letter-spacing: -0.02em;
        }

        .violation-details-page h5 {
            font-weight: 600;
        }

        .violation-details-page h6 {
            font-size: 0.82rem;
            letter-spacing: 0.04em;
            color: #374151;
        }

        .violation-details-page .form-label {
            font-weight: 500;
            color: #374151;
            margin-bottom: 0.4rem;
        }

        .violation-details-page .form-control,
        .violation-details-page .form-select {
            min-height: 42px;
            border-color: #d1d5db;
            border-radius: 7px;
        }

        .violation-details-page textarea.form-control {
            min-height: auto;
        }

        .violation-details-page .form-control:focus,
        .violation-details-page .form-select:focus {
            border-color: #86b7fe;
            box-shadow: 0 0 0 0.15rem rgba(13, 110, 253, 0.12);
        }

        .violation-details-page .text-muted {
            color: #6b7280 !important;
        }

        .violation-details-page .border-bottom {
            border-color: #e5e7eb !important;
        }

        .violation-details-page .bg-light {
            background-color: #f8fafc !important;
        }

        .violation-details-page .additional-violation-row {
            border-color: #dfe3e8 !important;
        }

        .violation-details-page .btn {
            border-radius: 7px;
        }

        .violation-details-page .btn-primary {
            padding-left: 18px;
            padding-right: 18px;
        }

        .violation-details-page .badge {
            font-weight: 500;
        }

        .violation-details-page .invalid-feedback {
            font-size: 0.8rem;
        }

        .violation-details-page small {
            font-size: 0.78rem;
        }

        @media (max-width: 767.98px) {

            .violation-details-page {
                padding: 0;
            }

            .violation-details-page h1 {
                font-size: 1.35rem;
            }

            .violation-details-page .card-body {
                padding: 1rem !important;
            }

            .violation-details-page .card-footer {
                padding: 1rem !important;
            }

            .violation-details-page .page-header {
                align-items: flex-start;
            }

            /* =========================================================
               VEHICLE INFORMATION
               ========================================================= */

            .violation-details-page .other-vehicle-type-box {
                background: #f8fafc;
                border: 1px solid #e2e8f0;
                border-radius: 7px;
                padding: 8px 10px;
            }

            .violation-details-page .other-vehicle-type-inner {
                display: flex;
                align-items: center;
                gap: 10px;
            }

            .violation-details-page .other-vehicle-type-label {
                flex: 0 0 auto;
                display: flex;
                align-items: center;
                font-size: 0.78rem;
                font-weight: 600;
                color: #475569;
                white-space: nowrap;
            }

            .violation-details-page .other-vehicle-type-label i {
                font-size: 0.72rem;
                color: #64748b;
            }

            .violation-details-page #other_vehicle_type {
                flex: 1;
                min-height: 34px;
                font-size: 0.85rem;
                border-radius: 6px;
            }

            .violation-details-page #other_vehicle_type::placeholder {
                color: #9ca3af;
            }

            .violation-details-page #other_vehicle_type:focus {
                border-color: #86b7fe;
                box-shadow: 0 0 0 0.12rem rgba(13, 110, 253, 0.10);
            }


            /* =========================================================
               MOBILE VEHICLE INFORMATION
               ========================================================= */

            @media (max-width: 575.98px) {

                .violation-details-page .other-vehicle-type-inner {
                    display: block;
                }

                .violation-details-page .other-vehicle-type-label {
                    margin-bottom: 6px;
                }

            }

        }
    </style>

@endsection
