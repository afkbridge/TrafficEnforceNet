@extends('layouts.admin')

@section('title', 'Edit Violation')

@section('content')

    <div class="violation-details-page">

        {{-- ========================================================= --}}
        {{-- PAGE HEADER --}}
        {{-- ========================================================= --}}
        <div class="page-header mb-3 d-flex justify-content-between align-items-center">
            <div>
                <h2 class="page-title mb-1">
                    Edit Violation
                </h2>
                <p class="page-subtitle mb-0">
                    Update the recorded citation and violator information.
                </p>
            </div>

            <div class="d-flex gap-2">
                <a href="{{ route('violations.show', $violation->id) }}" class="btn btn-light btn-sm">
                    <i class="fa-solid fa-arrow-left me-1"></i>
                    Back
                </a>
            </div>
        </div>


        {{-- ========================================================= --}}
        {{-- VALIDATION ERRORS --}}
        {{-- ========================================================= --}}
        @if ($errors->any())
            <div class="alert alert-danger edit-alert mb-3">
                <div class="fw-semibold mb-1">
                    <i class="fa-solid fa-circle-exclamation me-1"></i>
                    The violation could not be updated.
                </div>

                <ul class="mb-0 ps-4">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger edit-alert mb-3">
                <i class="fa-solid fa-circle-exclamation me-1"></i>
                {{ session('error') }}
            </div>
        @endif

        @if (session('success'))
            <div class="alert alert-success edit-alert mb-3">
                <i class="fa-solid fa-circle-check me-1"></i>
                {{ session('success') }}
            </div>
        @endif


        {{-- ========================================================= --}}
        {{-- FORM --}}
        {{-- ========================================================= --}}
        <form action="{{ route('violations.update', $violation->id) }}" method="POST" id="editViolationForm">
            @csrf
            @method('PUT')


            {{-- ===================================================== --}}
            {{-- TICKET INFORMATION --}}
            {{-- ===================================================== --}}
            <div class="detail-card mb-3">

                <div class="ticket-header">

                    <div>
                        <div class="label-text">
                            Citation Ticket Number
                        </div>

                        <div class="ticket-number">
                            {{ $violation->ticket_number ?? 'N/A' }}
                        </div>
                    </div>

                    {{-- STATUS DISPLAY ONLY --}}
                    <div class="text-end">
                        <div class="label-text">
                            Status
                        </div>

                        <span
                            class="status-badge
                            {{ strtolower($violation->status ?? '') === 'settled' ? 'status-settled' : 'status-pending' }}">
                            {{ $violation->status ?? 'N/A' }}
                        </span>
                    </div>

                </div>

            </div>


            {{-- ===================================================== --}}
            {{-- VIOLATOR INFORMATION --}}
            {{-- ===================================================== --}}
            <div class="detail-card mb-3">

                <div class="section-header">
                    <div>
                        <h5 class="section-title mb-0">
                            <i class="fa-solid fa-user me-2"></i>
                            Violator Information
                        </h5>

                        <span class="section-subtitle">
                            Personal and license information of the violator.
                        </span>
                    </div>
                </div>


                <div class="info-grid">

                    {{-- LAST NAME --}}
                    <div class="form-info-item">
                        <label for="last_name" class="info-label">
                            Last Name
                        </label>

                        <input type="text" id="last_name" name="last_name" class="form-control"
                            value="{{ old('last_name', $violation->driver->last_name ?? '') }}">
                    </div>


                    {{-- FIRST NAME --}}
                    <div class="form-info-item">
                        <label for="first_name" class="info-label">
                            First Name
                        </label>

                        <input type="text" id="first_name" name="first_name" class="form-control"
                            value="{{ old('first_name', $violation->driver->first_name ?? '') }}">
                    </div>


                    {{-- MIDDLE NAME --}}
                    <div class="form-info-item">
                        <label for="middle_name" class="info-label">
                            Middle Name
                        </label>

                        <input type="text" id="middle_name" name="middle_name" class="form-control"
                            value="{{ old('middle_name', $violation->driver->middle_name ?? '') }}">
                    </div>


                    {{-- LICENSE NUMBER --}}
                    <div class="form-info-item">
                        <label for="license_number" class="info-label">
                            License Number
                            <span class="optional-label">(Optional)</span>
                        </label>

                        <input type="text" id="license_number" name="license_number" class="form-control license-input"
                            value="{{ old('license_number', $violation->driver->license_number ?? '') }}"
                            placeholder="Enter license number if available" maxlength="255" autocomplete="off">
                    </div>


                    {{-- HOME ADDRESS --}}
                    <div class="form-info-item info-item-full">
                        <label for="address" class="info-label">
                            Home Address
                        </label>

                        <input type="text" id="address" name="address" class="form-control"
                            value="{{ old('address', $violation->driver->address ?? '') }}">
                    </div>


                    {{-- BIRTH DATE --}}
                    <div class="form-info-item">
                        <label for="birth_date" class="info-label">
                            Birth Date
                        </label>

                        <input type="date" id="birth_date" name="birth_date" class="form-control"
                            value="{{ old(
                                'birth_date',
                                $violation->driver && $violation->driver->birth_date
                                    ? \Carbon\Carbon::parse($violation->driver->birth_date)->format('Y-m-d')
                                    : '',
                            ) }}">
                    </div>


                    {{-- CONTACT NUMBER --}}
                    <div class="form-info-item">
                        <label for="contact_number" class="info-label">
                            Contact Number
                        </label>

                        <input type="text" id="contact_number" name="contact_number" class="form-control"
                            value="{{ old('contact_number', $violation->driver->contact_number ?? '') }}">
                    </div>

                </div>

            </div>


            {{-- ===================================================== --}}
            {{-- VIOLATION INFORMATION --}}
            {{-- ===================================================== --}}
            <div class="detail-card mb-3">

                <div class="section-header">
                    <div>
                        <h5 class="section-title mb-0">
                            <i class="fa-solid fa-file-circle-exclamation me-2"></i>
                            Violation Information
                        </h5>

                        <span class="section-subtitle">
                            Update the date, time, vehicle and recorded violation/s.
                        </span>
                    </div>
                </div>


                @php

                    /*
                     * Determine the primary violation.
                     */
                    $primarySelection = old(
                        'violation_type_id',
                        $violation->violation_type_id ?? (!empty($violation->other_violation) ? 'other' : ''),
                    );

                    $primaryIsOther = strtolower((string) $primarySelection) === 'other';

                    /*
                     * Existing official additional violations.
                     */
                    $additionalOfficialTypes = $violation->violationTypes
                        ? $violation->violationTypes->filter(function ($type) use ($violation) {
                            return (int) $type->id !== (int) $violation->violation_type_id;
                        })
                        : collect();

                    /*
                     * Existing custom Other violations.
                     */
                    $additionalOtherTypes = $violation->violationOtherTypes ?? collect();

                    /*
                     * =====================================================
                     * VEHICLE TYPE
                     * =====================================================
                     *
                     * Standard values:
                     * MC
                     * MTC Private
                     * MTC For Hire
                     * PUJ
                     * Private Vehicle
                     *
                     * Any other saved value is treated as "Others".
                     */
                    $standardVehicleTypes = ['MC', 'MTC Private', 'MTC For Hire', 'PUJ', 'Private Vehicle'];

                    $currentVehicleType = old('vehicle_type', $violation->vehicle->vehicle_type ?? '');

                    $currentVehicleType = trim((string) $currentVehicleType);

                    /*
                     * If the existing DB value is not one of the standard
                     * vehicle types, treat it as Others.
                     *
                     * This allows existing values such as:
                     * - Tricycle
                     * - Truck
                     * - Van
                     * - E-bike
                     * - Motorcycle with Sidecar
                     * etc.
                     *
                     * to appear under Others.
                     */
                    $isOtherVehicleType =
                        $currentVehicleType !== '' && !in_array($currentVehicleType, $standardVehicleTypes, true);

                    /*
                     * Value shown in the manual Others input.
                     */
                    $otherVehicleType = old('other_vehicle_type', $isOtherVehicleType ? $currentVehicleType : '');

                @endphp


                {{-- ================================================= --}}
                {{-- BASIC VIOLATION INFORMATION --}}
                {{-- ================================================= --}}
                <div class="info-grid violation-basic-info">


                    {{-- DATE --}}
                    <div class="form-info-item">

                        <label for="violation_date" class="info-label">
                            Date of Violation
                        </label>

                        <input type="date" id="violation_date" name="violation_date" class="form-control"
                            value="{{ old('violation_date', $violation->violation_date ?? '') }}">

                    </div>


                    {{-- TIME --}}
                    <div class="form-info-item">

                        <label for="violation_time" class="info-label">
                            Time of Violation
                        </label>

                        <input type="time" id="violation_time" name="violation_time" class="form-control"
                            value="{{ old('violation_time', $violation->violation_time ?? '') }}">

                    </div>


                    {{-- PLATE NUMBER --}}
                    <div class="form-info-item">

                        <label for="plate_number" class="info-label">
                            Plate Number
                        </label>

                        <input type="text" id="plate_number" name="plate_number" class="form-control plate-input"
                            value="{{ old('plate_number', $violation->vehicle->plate_number ?? '') }}">

                    </div>


                    {{-- ================================================= --}}
                    {{-- VEHICLE TYPE --}}
                    {{-- ================================================= --}}
                    <div class="form-info-item">

                        <label for="vehicle_type" class="info-label">
                            Vehicle Type
                        </label>

                        <select id="vehicle_type" name="vehicle_type" class="form-select">

                            <option value="" {{ $currentVehicleType === '' ? 'selected' : '' }}>
                                Select vehicle type
                            </option>

                            <option value="MC" {{ $currentVehicleType === 'MC' ? 'selected' : '' }}>
                                MC
                            </option>

                            <option value="MTC Private" {{ $currentVehicleType === 'MTC Private' ? 'selected' : '' }}>
                                MTC Private
                            </option>

                            <option value="MTC For Hire" {{ $currentVehicleType === 'MTC For Hire' ? 'selected' : '' }}>
                                MTC For Hire
                            </option>

                            <option value="PUJ" {{ $currentVehicleType === 'PUJ' ? 'selected' : '' }}>
                                PUJ
                            </option>

                            <option value="Private Vehicle"
                                {{ $currentVehicleType === 'Private Vehicle' ? 'selected' : '' }}>
                                Private Vehicle
                            </option>

                            <option value="Others"
                                {{ $isOtherVehicleType || $currentVehicleType === 'Others' ? 'selected' : '' }}>
                                Others
                            </option>

                        </select>


                        {{-- ================================================= --}}
                        {{-- OTHER VEHICLE TYPE --}}
                        {{-- ================================================= --}}
                        <div id="otherVehicleTypeContainer" class="other-vehicle-type-container mt-2"
                            style="{{ $isOtherVehicleType || $currentVehicleType === 'Others' ? '' : 'display: none;' }}">

                            <label for="other_vehicle_type" class="info-label">
                                Specify Vehicle Type
                            </label>

                            <input type="text" id="other_vehicle_type" name="other_vehicle_type" class="form-control"
                                placeholder="Enter vehicle type" maxlength="255" autocomplete="off"
                                value="{{ $otherVehicleType }}"
                                {{ $isOtherVehicleType || $currentVehicleType === 'Others' ? '' : 'disabled' }}>

                            <small class="other-vehicle-help">
                                Please specify the vehicle type when "Others" is selected.
                            </small>

                        </div>

                    </div>

                </div>

            </div>


            {{-- ================================================= --}}
            {{-- VIOLATIONS --}}
            {{-- ================================================= --}}
            <div class="violations-highlight">

                <div class="violations-heading">

                    <div class="violations-icon">
                        <i class="fa-solid fa-triangle-exclamation"></i>
                    </div>

                    <div>

                        <div class="violations-title">
                            Violation/s
                        </div>

                        <div class="violations-subtitle">
                            Select the violation/s recorded on this citation.
                        </div>

                    </div>

                </div>


                <div id="violationRows">


                    {{-- ================================================= --}}
                    {{-- PRIMARY VIOLATION --}}
                    {{-- ================================================= --}}
                    <div class="violation-edit-item primary-row" data-primary="true">

                        <div class="violation-row-header">

                            <div>

                                <span class="row-label">
                                    Primary Violation
                                </span>

                                <span class="row-description">
                                    Main violation recorded on the citation.
                                </span>

                            </div>

                            <span class="primary-badge">
                                Primary
                            </span>

                        </div>


                        <select name="violation_type_id" class="form-select violation-type-select"
                            data-primary-select="true">

                            <option value="">
                                Select Violation
                            </option>

                            @foreach ($violationTypes as $type)
                                <option value="{{ $type->id }}"
                                    {{ !$primaryIsOther && (int) $primarySelection === (int) $type->id ? 'selected' : '' }}>
                                    {{ $type->name }}
                                </option>
                            @endforeach

                            <option value="other" {{ $primaryIsOther ? 'selected' : '' }}>
                                Others
                            </option>

                        </select>


                        {{-- PRIMARY OTHER --}}
                        <div id="primaryOtherContainer" class="other-violation-container"
                            style="{{ $primaryIsOther ? '' : 'display: none;' }}">

                            <label for="other_violation" class="form-label">
                                Specify Other Violation
                            </label>

                            <input type="text" id="other_violation" name="other_violation" class="form-control"
                                placeholder="Enter the violation"
                                value="{{ old('other_violation', $violation->other_violation ?? '') }}"
                                {{ $primaryIsOther ? '' : 'disabled' }}>

                        </div>

                    </div>


                    {{-- ================================================= --}}
                    {{-- EXISTING ADDITIONAL OFFICIAL VIOLATIONS --}}
                    {{-- ================================================= --}}
                    @foreach ($additionalOfficialTypes as $additionalType)
                        <div class="violation-edit-item additional-row" data-existing="true">

                            <div class="additional-row-content">

                                <div class="flex-grow-1">

                                    <label class="form-label">
                                        Additional Violation
                                    </label>


                                    <select name="additional_violation_type_ids[]"
                                        class="form-select violation-type-select">

                                        <option value="">
                                            Select Violation
                                        </option>

                                        @foreach ($violationTypes as $type)
                                            <option value="{{ $type->id }}"
                                                {{ (int) $additionalType->id === (int) $type->id ? 'selected' : '' }}>
                                                {{ $type->name }}
                                            </option>
                                        @endforeach

                                        <option value="other">
                                            Others
                                        </option>

                                    </select>


                                    <div class="other-violation-container" style="display: none;">

                                        <label class="form-label">
                                            Specify Other Violation
                                        </label>

                                        <input type="text" name="additional_other_violation_names[]"
                                            class="form-control" placeholder="Enter the violation" value=""
                                            disabled>

                                    </div>

                                </div>


                                <button type="button" class="btn btn-outline-danger remove-violation">
                                    <i class="fa-solid fa-trash"></i>

                                    <span>
                                        Remove
                                    </span>
                                </button>

                            </div>

                        </div>
                    @endforeach


                    {{-- ================================================= --}}
                    {{-- EXISTING ADDITIONAL CUSTOM OTHER VIOLATIONS --}}
                    {{-- ================================================= --}}
                    @foreach ($additionalOtherTypes as $otherType)
                        <div class="violation-edit-item additional-row" data-existing="true">

                            <div class="additional-row-content">

                                <div class="flex-grow-1">

                                    <label class="form-label">
                                        Additional Violation
                                    </label>


                                    <select name="additional_violation_type_ids[]"
                                        class="form-select violation-type-select">

                                        <option value="">
                                            Select Violation
                                        </option>

                                        @foreach ($violationTypes as $type)
                                            <option value="{{ $type->id }}">
                                                {{ $type->name }}
                                            </option>
                                        @endforeach

                                        <option value="other" selected>
                                            Others
                                        </option>

                                    </select>


                                    <div class="other-violation-container">

                                        <label class="form-label">
                                            Specify Other Violation
                                        </label>

                                        <input type="text" name="additional_other_violation_names[]"
                                            class="form-control" placeholder="Enter the violation"
                                            value="{{ $otherType->name }}">

                                    </div>

                                </div>


                                <button type="button" class="btn btn-outline-danger remove-violation">
                                    <i class="fa-solid fa-trash"></i>

                                    <span>
                                        Remove
                                    </span>
                                </button>

                            </div>

                        </div>
                    @endforeach

                </div>


                {{-- ================================================= --}}
                {{-- ADD VIOLATION --}}
                {{-- ================================================= --}}
                <div class="add-violation-area">

                    <button type="button" id="addViolation" class="btn btn-outline-primary add-btn">
                        <i class="fa-solid fa-plus me-1"></i>
                        Add Violation
                    </button>

                    <span class="add-hint">
                        Add another violation only when necessary.
                    </span>

                </div>

            </div>


            {{-- ===================================================== --}}
            {{-- APPREHENDING OFFICER --}}
            {{-- ===================================================== --}}
            <div class="detail-card mb-3">

                <div class="section-header">

                    <div>

                        <h5 class="section-title mb-0">
                            <i class="fa-solid fa-user-shield me-2"></i>
                            Apprehending Officer
                        </h5>

                        <span class="section-subtitle">
                            Personnel associated with this citation record.
                        </span>

                    </div>

                </div>


                <div class="officer-row">

                    <div class="officer-icon">
                        <i class="fa-solid fa-user-shield"></i>
                    </div>

                    <div>

                        <div class="info-label">
                            Officer
                        </div>

                        <div class="officer-name">
                            {{ $violation->user->name ?? 'N/A' }}
                        </div>

                    </div>

                </div>

            </div>


            {{-- ===================================================== --}}
            {{-- REMARKS --}}
            {{-- ===================================================== --}}
            <div class="detail-card mb-3">

                <div class="section-header">

                    <div>

                        <h5 class="section-title mb-0">
                            <i class="fa-solid fa-note-sticky me-2"></i>
                            Remarks
                        </h5>

                        <span class="section-subtitle">
                            Add or update notes related to this citation.
                        </span>

                    </div>

                </div>


                <div class="remarks-body">

                    <textarea name="remarks" id="remarks" class="form-control remarks-field" rows="4"
                        placeholder="Enter remarks if necessary...">{{ old('remarks', $violation->remarks ?? '') }}</textarea>

                </div>

            </div>


            {{-- ===================================================== --}}
            {{-- ACTION BUTTONS --}}
            {{-- ===================================================== --}}
            <div class="form-actions">

                <a href="{{ route('violations.show', $violation->id) }}" class="btn btn-light border">
                    Cancel
                </a>

                <button type="submit" class="btn btn-primary save-btn" id="saveChangesButton">
                    <i class="fa-solid fa-check me-1"></i>
                    Save Changes
                </button>

            </div>

        </form>

    </div>


    {{-- ========================================================= --}}
    {{-- JAVASCRIPT --}}
    {{-- ========================================================= --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            const violationRows =
                document.getElementById('violationRows');

            const addViolationButton =
                document.getElementById('addViolation');

            const vehicleTypeSelect =
                document.getElementById('vehicle_type');

            const otherVehicleTypeContainer =
                document.getElementById('otherVehicleTypeContainer');

            const otherVehicleTypeInput =
                document.getElementById('other_vehicle_type');

            const editViolationForm =
                document.getElementById('editViolationForm');

            const saveChangesButton =
                document.getElementById('saveChangesButton');


            /* =========================================================
               VEHICLE TYPE - OTHERS
               ========================================================= */

            function updateOtherVehicleType() {

                if (
                    !vehicleTypeSelect ||
                    !otherVehicleTypeContainer ||
                    !otherVehicleTypeInput
                ) {
                    return;
                }

                if (vehicleTypeSelect.value === 'Others') {

                    otherVehicleTypeContainer.style.display = '';

                    otherVehicleTypeInput.disabled = false;
                    otherVehicleTypeInput.required = true;

                } else {

                    otherVehicleTypeContainer.style.display = 'none';

                    otherVehicleTypeInput.disabled = true;
                    otherVehicleTypeInput.required = false;

                    /*
                     * Clear the manual value when a standard
                     * vehicle type is selected.
                     */
                    otherVehicleTypeInput.value = '';
                }
            }


            /*
             * Initialize vehicle type state.
             */
            if (vehicleTypeSelect) {

                vehicleTypeSelect.addEventListener(
                    'change',
                    updateOtherVehicleType
                );

                updateOtherVehicleType();
            }


            /* =========================================================
               PRIMARY OTHER INPUT
               ========================================================= */

            function updatePrimaryOther(select) {

                const container =
                    document.getElementById(
                        'primaryOtherContainer'
                    );

                if (!container) {
                    return;
                }

                const input =
                    container.querySelector(
                        'input[name="other_violation"]'
                    );

                if (select.value === 'other') {

                    container.style.display = '';

                    if (input) {
                        input.disabled = false;
                        input.required = true;
                    }

                } else {

                    container.style.display = 'none';

                    if (input) {
                        input.disabled = true;
                        input.required = false;
                        input.value = '';
                    }
                }
            }


            /* =========================================================
               ADDITIONAL OTHER INPUT
               ========================================================= */

            function updateAdditionalOther(select) {

                const row =
                    select.closest('.violation-edit-item');

                if (!row) {
                    return;
                }

                const container =
                    row.querySelector(
                        '.other-violation-container'
                    );

                if (!container) {
                    return;
                }

                const input =
                    container.querySelector(
                        'input[name="additional_other_violation_names[]"]'
                    );

                if (select.value === 'other') {

                    container.style.display = '';

                    if (input) {
                        input.disabled = false;
                        input.required = true;
                    }

                } else {

                    container.style.display = 'none';

                    if (input) {
                        input.disabled = true;
                        input.required = false;
                        input.value = '';
                    }
                }
            }


            /* =========================================================
               SELECT CHANGE
               ========================================================= */

            function setupViolationSelect(select) {

                select.addEventListener(
                    'change',
                    function() {

                        const row =
                            select.closest(
                                '.violation-edit-item'
                            );

                        if (!row) {
                            return;
                        }

                        if (
                            row.dataset.primary === 'true'
                        ) {

                            updatePrimaryOther(select);

                        } else {

                            updateAdditionalOther(select);

                        }

                    }
                );
            }


            /* =========================================================
               REMOVE BUTTON
               ========================================================= */

            function setupRemoveButton(row) {

                const removeButton =
                    row.querySelector(
                        '.remove-violation'
                    );

                if (!removeButton) {
                    return;
                }

                removeButton.addEventListener(
                    'click',
                    function() {
                        row.remove();
                    }
                );
            }


            /* =========================================================
               SETUP EXISTING ROWS
               ========================================================= */

            if (violationRows) {

                violationRows
                    .querySelectorAll(
                        '.violation-edit-item'
                    )
                    .forEach(function(row) {

                        const select =
                            row.querySelector(
                                '.violation-type-select'
                            );

                        if (select) {

                            setupViolationSelect(select);

                            if (
                                row.dataset.primary === 'true'
                            ) {

                                updatePrimaryOther(select);

                            } else {

                                updateAdditionalOther(select);

                            }

                        }

                        setupRemoveButton(row);

                    });

            }


            /* =========================================================
               ADD NEW VIOLATION
               ========================================================= */

            if (
                violationRows &&
                addViolationButton
            ) {

                addViolationButton.addEventListener(
                    'click',
                    function() {

                        const row =
                            document.createElement('div');

                        row.className =
                            'violation-edit-item additional-row';

                        row.innerHTML = `
                            <div class="additional-row-content">

                                <div class="flex-grow-1">

                                    <label class="form-label">
                                        Additional Violation
                                    </label>

                                    <select
                                        name="additional_violation_type_ids[]"
                                        class="form-select violation-type-select"
                                    >

                                        <option value="">
                                            Select Violation
                                        </option>

                                        @foreach ($violationTypes as $type)

                                            <option value="{{ $type->id }}">
                                                {{ $type->name }}
                                            </option>

                                        @endforeach

                                        <option value="other">
                                            Others
                                        </option>

                                    </select>


                                    <div
                                        class="other-violation-container"
                                        style="display: none;"
                                    >

                                        <label class="form-label">
                                            Specify Other Violation
                                        </label>

                                        <input
                                            type="text"
                                            name="additional_other_violation_names[]"
                                            class="form-control"
                                            placeholder="Enter the violation"
                                            disabled
                                        >

                                    </div>

                                </div>


                                <button
                                    type="button"
                                    class="btn btn-outline-danger remove-violation"
                                >

                                    <i class="fa-solid fa-trash"></i>

                                    <span>
                                        Remove
                                    </span>

                                </button>

                            </div>
                        `;


                        violationRows.appendChild(row);


                        const select =
                            row.querySelector(
                                '.violation-type-select'
                            );

                        if (select) {
                            setupViolationSelect(select);
                        }

                        setupRemoveButton(row);

                    }
                );

            }


            /* =========================================================
               FORM SUBMISSION
               ========================================================= */

            if (
                editViolationForm &&
                saveChangesButton
            ) {

                editViolationForm.addEventListener(
                    'submit',
                    function(event) {

                        /*
                         * Validate Others vehicle type before
                         * allowing the form to submit.
                         */
                        if (
                            vehicleTypeSelect &&
                            vehicleTypeSelect.value === 'Others'
                        ) {

                            const value =
                                otherVehicleTypeInput ?
                                otherVehicleTypeInput.value.trim() :
                                '';

                            if (value === '') {

                                event.preventDefault();

                                if (otherVehicleTypeInput) {

                                    otherVehicleTypeInput.disabled = false;
                                    otherVehicleTypeInput.required = true;

                                    otherVehicleTypeInput.focus();
                                }

                                alert(
                                    'Please specify the vehicle type when "Others" is selected.'
                                );

                                return;
                            }
                        }


                        /*
                         * Prevent double submission.
                         */
                        saveChangesButton.disabled = true;

                        saveChangesButton.innerHTML = `
                            <span
                                class="spinner-border spinner-border-sm me-1"
                                role="status"
                                aria-hidden="true"
                            ></span>
                            Saving Changes...
                        `;

                    }
                );

            }

        });
    </script>


    {{-- ========================================================= --}}
    {{-- PAGE STYLING --}}
    {{-- ========================================================= --}}
    <style>
        .violation-details-page {
            padding-bottom: 30px;
        }


        /* =========================================================
               PAGE HEADER
               ========================================================= */

        .page-header {
            margin-bottom: 18px;
        }

        .page-title {
            font-size: 24px;
            font-weight: 700;
            color: #212529;
        }

        .page-subtitle {
            font-size: 14px;
            color: #6c757d;
        }

        .page-header .btn {
            border-radius: 7px;
            padding: 7px 13px;
        }


        /* =========================================================
               ALERT
               ========================================================= */

        .edit-alert {
            border-radius: 8px;
            padding: 12px 15px;
            font-size: 13px;
        }


        /* =========================================================
               DETAIL CARD
               ========================================================= */

        .detail-card {
            background: #ffffff;
            border: 1px solid #e5e7eb;
            border-radius: 10px;
            padding: 18px 20px;
        }


        /* =========================================================
               TICKET HEADER
               ========================================================= */

        .ticket-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 20px;
        }

        .label-text {
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: .04em;
            color: #6c757d;
            margin-bottom: 4px;
        }

        .ticket-number {
            font-size: 19px;
            font-weight: 700;
            color: #212529;
        }


        /* =========================================================
               STATUS
               ========================================================= */

        .status-badge {
            display: inline-block;
            padding: 5px 11px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }

        .status-pending {
            background: #fff3cd;
            color: #856404;
        }

        .status-settled {
            background: #d1e7dd;
            color: #0f5132;
        }


        /* =========================================================
               SECTION HEADER
               ========================================================= */

        .section-header {
            padding-bottom: 12px;
            margin-bottom: 4px;
            border-bottom: 1px solid #f0f0f0;
        }

        .section-title {
            font-size: 15px;
            font-weight: 700;
            color: #212529;
        }

        .section-title i {
            color: #495057;
            font-size: 14px;
        }

        .section-subtitle {
            display: block;
            margin-top: 3px;
            font-size: 12px;
            color: #8a8f98;
        }


        /* =========================================================
               INFORMATION GRID
               ========================================================= */

        .info-grid {
            display: grid;
            grid-template-columns:
                repeat(2, minmax(0, 1fr));
            column-gap: 30px;
            row-gap: 0;
        }


        /* =========================================================
               FORM INFORMATION
               ========================================================= */

        .form-info-item {
            padding: 11px 0;
        }

        .info-item-full {
            grid-column: 1 / -1;
        }

        .info-label {
            display: block;
            font-size: 12px;
            font-weight: 600;
            color: #737980;
            margin-bottom: 5px;
        }


        /* =========================================================
               FORM CONTROLS
               ========================================================= */

        .form-control,
        .form-select {
            min-height: 39px;
            padding: 7px 10px;
            border: 1px solid #d8dde3;
            border-radius: 7px;
            font-size: 13px;
            color: #374151;
            background-color: #ffffff;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: #86b7fe;
            box-shadow:
                0 0 0 2px rgba(13, 110, 253, .08);
        }

        .license-input {
            font-weight: 600;
            letter-spacing: .02em;
        }

        .plate-input {
            font-weight: 700;
            letter-spacing: .04em;
        }


        /* =========================================================
               OTHER VEHICLE TYPE
               ========================================================= */

        .other-vehicle-type-container {
            padding: 10px;
            background: #fffaf0;
            border: 1px solid #f0dfae;
            border-radius: 7px;
        }

        .other-vehicle-type-container .info-label {
            color: #786527;
            margin-bottom: 5px;
        }

        .other-vehicle-help {
            display: block;
            margin-top: 5px;
            font-size: 11px;
            color: #92754a;
        }


        /* =========================================================
               VIOLATIONS HIGHLIGHT
               ========================================================= */

        .violations-highlight {
            margin-top: 16px;
            padding: 17px;
            background: #fffaf0;
            border: 1px solid #f1dfb5;
            border-radius: 10px;
        }

        .violations-heading {
            display: flex;
            align-items: center;
            gap: 11px;
            margin-bottom: 13px;
        }

        .violations-icon {
            width: 40px;
            height: 40px;
            flex-shrink: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #fff0c2;
            color: #b7791f;
            border-radius: 8px;
            font-size: 17px;
        }

        .violations-title {
            font-size: 15px;
            font-weight: 700;
            color: #7c4a03;
        }

        .violations-subtitle {
            margin-top: 2px;
            font-size: 12px;
            color: #92754a;
        }


        /* =========================================================
               VIOLATION EDIT ITEMS
               ========================================================= */

        .violation-edit-item {
            padding: 13px;
            margin-bottom: 10px;
            border: 1px solid #eadfca;
            border-radius: 8px;
            background: #ffffff;
        }

        .primary-row {
            background: #fffdf7;
            border-color: #eadfca;
        }

        .violation-row-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            margin-bottom: 9px;
        }

        .row-label {
            display: block;
            font-size: 12px;
            font-weight: 650;
            color: #374151;
        }

        .row-description {
            display: block;
            margin-top: 2px;
            font-size: 11px;
            color: #8a9199;
        }

        .primary-badge {
            padding: 4px 8px;
            border-radius: 20px;
            background: #eaf2ff;
            color: #0d6efd;
            font-size: 10px;
            font-weight: 600;
        }


        /* =========================================================
               ADDITIONAL ROW
               ========================================================= */

        .additional-row-content {
            display: flex;
            align-items: flex-end;
            gap: 10px;
        }

        .additional-row-content .flex-grow-1 {
            min-width: 0;
            flex: 1;
        }


        /* =========================================================
               OTHER VIOLATION
               ========================================================= */

        .other-violation-container {
            margin-top: 8px;
            padding: 10px;
            background: #fffaf0;
            border: 1px solid #f0dfae;
            border-radius: 7px;
        }

        .other-violation-container .form-label {
            color: #786527;
            font-size: 12px;
            font-weight: 600;
            margin-bottom: 5px;
        }


        /* =========================================================
               REMOVE BUTTON
               ========================================================= */

        .remove-violation {
            min-height: 39px;
            padding: 7px 11px;
            border-radius: 7px;
            font-size: 12px;
            white-space: nowrap;
        }


        /* =========================================================
               ADD VIOLATION
               ========================================================= */

        .add-violation-area {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-top: 12px;
        }

        .add-btn {
            min-height: 36px;
            padding: 6px 12px;
            border-radius: 7px;
            font-size: 12px;
        }

        .add-hint {
            font-size: 11px;
            color: #8a9199;
        }


        /* =========================================================
               APPREHENDING OFFICER
               ========================================================= */

        .officer-row {
            display: flex;
            align-items: center;
            gap: 12px;
            padding-top: 8px;
        }

        .officer-icon {
            width: 38px;
            height: 38px;
            border-radius: 8px;
            background: #f1f3f5;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #495057;
            font-size: 15px;
        }

        .officer-name {
            font-size: 14px;
            font-weight: 600;
            color: #212529;
        }


        /* =========================================================
               REMARKS
               ========================================================= */

        .remarks-body {
            padding-top: 10px;
        }

        .remarks-field {
            resize: vertical;
            min-height: 90px;
            line-height: 1.5;
        }


        /* =========================================================
               ACTIONS
               ========================================================= */

        .form-actions {
            display: flex;
            justify-content: flex-end;
            gap: 8px;
            margin-top: 2px;
        }

        .form-actions .btn {
            min-height: 39px;
            padding: 7px 16px;
            border-radius: 7px;
            font-size: 13px;
        }

        .save-btn {
            min-width: 125px;
        }


        /* =========================================================
               RESPONSIVE
               ========================================================= */

        @media (max-width: 768px) {

            .page-header {
                align-items: flex-start !important;
                flex-direction: column;
                gap: 12px;
            }

            .page-header>div:last-child {
                width: 100%;
            }

            .page-header .btn {
                flex: 1;
            }

            .ticket-header {
                align-items: flex-start;
            }

            .info-grid {
                grid-template-columns: 1fr;
            }

            .info-item-full {
                grid-column: auto;
            }

            .detail-card {
                padding: 15px;
            }

            .violations-highlight {
                padding: 14px;
            }

            .additional-row-content {
                flex-direction: column;
                align-items: stretch;
            }

            .remove-violation {
                width: 100%;
            }

            .add-violation-area {
                align-items: flex-start;
                flex-direction: column;
            }

            .form-actions {
                justify-content: stretch;
            }

            .form-actions .btn {
                flex: 1;
            }

        }
    </style>

@endsection
