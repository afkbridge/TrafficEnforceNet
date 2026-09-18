@extends('layouts.enforcer')

@section('title', 'Issue Traffic Citation')

@section('content')


@if ($errors->any())
    <div class="mx-5 mt-5 bg-red-50 border border-red-200 text-red-700 rounded-2xl p-4 shadow-sm">
        <div class="flex items-start gap-3">
            <div class="w-9 h-9 rounded-full bg-red-100 flex items-center justify-center flex-shrink-0">
                ⚠️
            </div>

            <div>
                <h3 class="font-bold text-sm mb-1">
                    Please check the following:
                </h3>

                <ul class="list-disc ml-5 text-sm space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
@endif

<div class="min-h-screen bg-[#F5F7FB] pb-28">

    <form action="{{ route('enforcer.violations.store') }}" method="POST"
        enctype="multipart/form-data" class="space-y-5">

        @csrf

        <!-- HEADER -->

        <div class="bg-gradient-to-br from-[#1D5FBF] to-[#2E77E6] text-white px-5 pt-7 pb-7 rounded-b-[30px] shadow-sm">

            <div class="flex items-center gap-3">

                <a href="{{ route('enforcer.dashboard') }}"
                    class="w-10 h-10 rounded-xl bg-white/15 hover:bg-white/25 flex items-center justify-center text-xl transition flex-shrink-0"
                    aria-label="Back to dashboard">
                    ←
                </a>

                <div class="min-w-0">
                    <h1 class="text-xl font-bold leading-tight">
                        Issue Traffic Citation
                    </h1>

                    <p class="text-blue-100 text-sm mt-1">
                        Record a traffic violation and capture the required evidence.
                    </p>
                </div>

            </div>

        </div>

        <!-- DRIVER'S LICENSE SCANNER -->

        <div class="px-5">

            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-5">

                <div class="flex items-start gap-3 mb-4">

                    <div
                        class="w-11 h-11 rounded-2xl bg-blue-50 flex items-center justify-center text-xl flex-shrink-0">
                        🪪
                    </div>

                    <div>
                        <h2 class="font-bold text-gray-800">
                            Driver's License Scanner
                        </h2>

                        <p class="text-xs text-gray-500 mt-1 leading-relaxed">
                            Capture or upload the driver's license to automatically extract driver information.
                        </p>
                    </div>

                </div>

                <!-- License Preview -->

                <div id="licensePreviewContainer" class="hidden mb-4">

                    <div class="relative">

                        <img id="licensePreview"
                            class="w-full h-56 sm:h-64 object-cover rounded-2xl border border-gray-200"
                            alt="Driver's license preview">

                        <div
                            class="absolute top-3 right-3 bg-black/60 text-white text-xs px-3 py-1.5 rounded-full">
                            License Preview
                        </div>

                    </div>

                </div>

                <!-- Camera / Upload -->

                <div class="grid grid-cols-2 gap-3">

                    <button type="button"
                        onclick="openLicenseCamera()"
                        class="bg-[#005FBF] hover:bg-[#004F9F] active:scale-[0.98] text-white rounded-2xl py-4 px-3 font-bold flex flex-col items-center justify-center transition">

                        <span class="text-2xl">
                            📷
                        </span>

                        <span class="mt-2 text-sm">
                            Take Photo
                        </span>

                    </button>

                    <button type="button"
                        onclick="openLicenseFile()"
                        class="bg-blue-50 hover:bg-blue-100 active:scale-[0.98] text-blue-700 rounded-2xl py-4 px-3 font-bold flex flex-col items-center justify-center border border-blue-200 transition">

                        <span class="text-2xl">
                            📁
                        </span>

                        <span class="mt-2 text-sm">
                            Upload File
                        </span>

                    </button>

                </div>

                <input
                    type="file"
                    id="driver_license"
                    name="driver_license"
                    accept="image/*"
                    capture="environment"
                    class="hidden"
                    onchange="processDriverLicense(event)">

            </div>

        </div>

        <!-- OCR STATUS -->

        <div class="px-5">

            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-5">

                <div class="flex items-center justify-between mb-4">

                    <h2 class="font-bold text-gray-800">
                        🤖 OCR Status
                    </h2>

                    <span id="ocrBadge"
                        class="text-xs font-medium px-2.5 py-1 rounded-full bg-gray-100 text-gray-500">
                        Ready
                    </span>

                </div>

                <div id="ocrStatus"
                    class="flex items-center gap-3 bg-gray-50 border border-gray-100 rounded-2xl p-4">

                    <div id="ocrIcon"
                        class="w-11 h-11 rounded-full bg-blue-100 flex items-center justify-center text-lg flex-shrink-0">
                        ⏳
                    </div>

                    <div class="min-w-0">

                        <p id="ocrTitle" class="font-semibold text-gray-700 text-sm">
                            Ready for scanning
                        </p>

                        <p id="ocrMessage" class="text-xs text-gray-500 mt-1 leading-relaxed">
                            Upload a clear driver's license image to begin OCR.
                        </p>

                    </div>

                </div>

            </div>

        </div>

        <!-- DRIVER INFORMATION -->

        <div class="px-5">

            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-5">

                <div class="flex items-center gap-3 mb-5">

                    <div class="w-11 h-11 rounded-2xl bg-blue-50 flex items-center justify-center text-xl">
                        👤
                    </div>

                    <div>
                        <h2 class="font-bold text-gray-800">
                            Driver Information
                        </h2>

                        <p class="text-xs text-gray-500 mt-1">
                            Automatically extracted from the driver's license. Review and edit before submitting.
                        </p>
                    </div>

                </div>

                <div class="space-y-4">

                    <!-- First / Middle -->

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                        <div>

                            <label class="text-xs font-semibold text-gray-600">
                                First Name
                            </label>

                            <input
                                type="text"
                                id="first_name"
                                name="first_name"
                                value="{{ old('first_name') }}"
                                placeholder="OCR detected first name"
                                class="w-full mt-1.5 rounded-xl bg-blue-50 border-blue-200 focus:border-blue-500 focus:ring-blue-500 text-sm">

                            <p class="text-xs text-blue-600 mt-1.5">
                                🤖 OCR detected • Editable
                            </p>

                        </div>

                        <div>

                            <label class="text-xs font-semibold text-gray-600">
                                Middle Name
                            </label>

                            <input
                                type="text"
                                id="middle_name"
                                name="middle_name"
                                value="{{ old('middle_name') }}"
                                placeholder="OCR detected middle name"
                                class="w-full mt-1.5 rounded-xl bg-blue-50 border-blue-200 focus:border-blue-500 focus:ring-blue-500 text-sm">

                            <p class="text-xs text-blue-600 mt-1.5">
                                🤖 OCR detected • Editable
                            </p>

                        </div>

                    </div>

                    <!-- Last / License -->

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                        <div>

                            <label class="text-xs font-semibold text-gray-600">
                                Last Name
                            </label>

                            <input
                                type="text"
                                id="last_name"
                                name="last_name"
                                value="{{ old('last_name') }}"
                                placeholder="OCR detected last name"
                                class="w-full mt-1.5 rounded-xl bg-blue-50 border-blue-200 focus:border-blue-500 focus:ring-blue-500 text-sm">

                            <p class="text-xs text-blue-600 mt-1.5">
                                🤖 OCR detected • Editable
                            </p>

                        </div>

                        <div>

                            <label class="text-xs font-semibold text-gray-600">
                                License Number
                            </label>

                            <input
                                type="text"
                                id="license_number"
                                name="license_number"
                                value="{{ old('license_number') }}"
                                placeholder="OCR detected license number"
                                class="w-full mt-1.5 rounded-xl bg-blue-50 border-blue-200 focus:border-blue-500 focus:ring-blue-500 text-sm">

                            <p class="text-xs text-blue-600 mt-1.5">
                                🤖 OCR detected • Editable
                            </p>

                        </div>

                    </div>

                    <!-- Address -->

                    <div>

                        <label class="text-xs font-semibold text-gray-600">
                            Address
                        </label>

                        <input
                            type="text"
                            id="address"
                            name="address"
                            value="{{ old('address') }}"
                            placeholder="OCR detected address"
                            class="w-full mt-1.5 rounded-xl bg-blue-50 border-blue-200 focus:border-blue-500 focus:ring-blue-500 text-sm">

                        <p class="text-xs text-blue-600 mt-1.5">
                            🤖 OCR detected • Editable
                        </p>

                    </div>

                    <!-- Birth Date -->

                    <div>

                        <label class="text-xs font-semibold text-gray-600">
                            Birth Date
                        </label>

                        <input
                            type="date"
                            id="birth_date"
                            name="birth_date"
                            value="{{ old('birth_date') }}"
                            class="w-full mt-1.5 rounded-xl border-gray-300 focus:border-blue-500 focus:ring-blue-500 text-sm">

                        <p class="text-xs text-blue-600 mt-1.5">
                            🤖 OCR detected • Editable
                        </p>

                    </div>

                </div>

            </div>

        </div>

        <!-- VEHICLE INFORMATION -->

        <div class="px-5">

            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-5">

                <div class="flex items-center gap-3 mb-5">

                    <div class="w-11 h-11 rounded-2xl bg-blue-50 flex items-center justify-center text-xl">
                        🚗
                    </div>

                    <div>
                        <h2 class="font-bold text-gray-800">
                            Vehicle Information
                        </h2>

                        <p class="text-xs text-gray-500 mt-1">
                            Enter vehicle information manually.
                        </p>
                    </div>

                </div>

                <div class="space-y-4">

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                        <div>

                            <label class="text-xs font-semibold text-gray-600">
                                Plate Number
                            </label>

                            <input
                                type="text"
                                name="plate_number"
                                value="{{ old('plate_number') }}"
                                placeholder="Enter plate number manually"
                                class="w-full mt-1.5 rounded-xl bg-gray-50 border-gray-300 focus:border-blue-500 focus:ring-blue-500 text-sm">

                            <p class="text-xs text-gray-500 mt-1.5">
                                Enter manually
                            </p>

                        </div>

                        <div>

                            <label class="text-xs font-semibold text-gray-600">
                                Vehicle Type
                            </label>

                            <input
                                type="text"
                                name="vehicle_type"
                                value="{{ old('vehicle_type') }}"
                                placeholder="e.g. Motorcycle, Sedan, SUV"
                                class="w-full mt-1.5 rounded-xl bg-gray-50 border-gray-300 focus:border-blue-500 focus:ring-blue-500 text-sm">

                            <p class="text-xs text-gray-500 mt-1.5">
                                Enter manually
                            </p>

                        </div>

                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                        <div>

                            <label class="text-xs font-semibold text-gray-600">
                                Region Number
                            </label>

                            <input
                                type="text"
                                name="region_number"
                                value="{{ old('region_number') }}"
                                placeholder="Enter region number"
                                class="w-full mt-1.5 rounded-xl bg-gray-50 border-gray-300 focus:border-blue-500 focus:ring-blue-500 text-sm">

                            <p class="text-xs text-gray-500 mt-1.5">
                                Enter manually
                            </p>

                        </div>

                        <div>

                            <label class="text-xs font-semibold text-gray-600">
                                Vehicle Owner
                            </label>

                            <input
                                type="text"
                                name="owner_name"
                                value="{{ old('owner_name') }}"
                                placeholder="Enter vehicle owner"
                                class="w-full mt-1.5 rounded-xl bg-gray-50 border-gray-300 focus:border-blue-500 focus:ring-blue-500 text-sm">

                            <p class="text-xs text-gray-500 mt-1.5">
                                Enter manually
                            </p>

                        </div>

                    </div>

                </div>

            </div>

        </div>

        <!-- VIOLATION INFORMATION -->

        <div class="px-5">

            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-5">

                <div class="flex items-center gap-3 mb-5">

                    <div class="w-11 h-11 rounded-2xl bg-red-50 flex items-center justify-center text-xl">
                        ⚠️
                    </div>

                    <div>
                        <h2 class="font-bold text-gray-800">
                            Violation Information
                        </h2>

                        <p class="text-xs text-gray-500 mt-1">
                            Specify the traffic violation.
                        </p>
                    </div>

                </div>

                <div class="space-y-4">

                    <div>

                        <label class="text-xs font-semibold text-gray-600">
                            Violation Type
                        </label>

                        <select
                            name="violation_type_id"
                            id="violation_type_id"
                            class="w-full mt-1.5 rounded-xl bg-blue-50 border-blue-200 focus:border-blue-500 focus:ring-blue-500 text-sm">

                            <option value="" selected disabled>
                                Select violation type
                            </option>

                            @foreach ($violationTypes as $type)

                                <option
                                    value="{{ $type->id }}"
                                    {{ old('violation_type_id') == $type->id ? 'selected' : '' }}>

                                    {{ $type->name }}

                                </option>

                            @endforeach

                            <option
                                value="other"
                                {{ old('violation_type_id') == 'other' ? 'selected' : '' }}>

                                Others

                            </option>

                        </select>

                    </div>

                    <div
                        id="otherViolationContainer"
                        class="{{ old('violation_type_id') == 'other' ? '' : 'hidden' }}">

                        <label class="text-xs font-semibold text-gray-600">
                            Specify Violation
                        </label>

                        <input
                            type="text"
                            name="other_violation"
                            value="{{ old('other_violation') }}"
                            placeholder="Enter violation"
                            class="w-full mt-1.5 rounded-xl border-gray-300 focus:border-blue-500 focus:ring-blue-500 text-sm">

                    </div>

                    <div>

                        <label class="text-xs font-semibold text-gray-600">
                            Issued By
                        </label>

                        <div class="relative mt-1.5">

                            <input
                                type="text"
                                readonly
                                value="{{ auth()->user()->name }}"
                                class="w-full rounded-xl bg-gray-100 border-gray-200 text-sm text-gray-600 pr-10">

                            <span class="absolute right-3 top-1/2 -translate-y-1/2">
                                ✓
                            </span>

                        </div>

                    </div>

                    <div>

                        <label class="text-xs font-semibold text-gray-600">
                            Remarks
                        </label>

                        <textarea
                            name="remarks"
                            rows="4"
                            placeholder="Enter additional notes"
                            class="w-full mt-1.5 rounded-xl border-gray-300 focus:border-blue-500 focus:ring-blue-500 text-sm">{{ old('remarks') }}</textarea>

                    </div>

                </div>

            </div>

        </div>

        <!-- EVIDENCE PHOTO -->

        <div class="px-5">

            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-5">

                <div class="flex items-center gap-3 mb-4">

                    <div class="w-11 h-11 rounded-2xl bg-blue-50 flex items-center justify-center text-xl">
                        📸
                    </div>

                    <div>
                        <h2 class="font-bold text-gray-800">
                            Violation Evidence
                        </h2>

                        <p class="text-xs text-gray-500 mt-1">
                            Capture clear evidence of the violation.
                        </p>
                    </div>

                </div>

                <label
                    class="border-2 border-dashed border-gray-300 hover:border-blue-400 hover:bg-blue-50 rounded-3xl min-h-[220px] flex flex-col items-center justify-center cursor-pointer transition p-6 text-center">

                    <div class="w-16 h-16 rounded-2xl bg-blue-50 flex items-center justify-center text-3xl">
                        📷
                    </div>

                    <h3 class="font-bold text-base mt-4 text-gray-800">
                        Capture Evidence Photo
                    </h3>

                    <p class="text-gray-500 text-xs mt-2 max-w-xs leading-relaxed">
                        Take a photo showing the actual traffic violation.
                    </p>

                    <span
                        class="mt-4 inline-flex items-center px-4 py-2 rounded-xl bg-blue-600 text-white text-xs font-semibold">
                        Choose Photo
                    </span>

                    <input
                        type="file"
                        name="evidence_images[]"
                        accept="image/*"
                        capture="environment"
                        multiple
                        class="hidden">

                </label>

                <div class="mt-4 bg-gray-50 rounded-2xl p-4">

                    <p class="text-xs font-semibold text-gray-600">
                        Recommended evidence:
                    </p>

                    <ul class="text-xs text-gray-500 mt-2 space-y-1.5">

                        <li>✓ Vehicle involved</li>
                        <li>✓ Traffic violation scene</li>
                        <li>✓ Road/location condition</li>

                    </ul>

                </div>

            </div>

        </div>

        <!-- GPS LOCATION -->

        <div class="px-5">

            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-5">

                <div class="flex items-center justify-between gap-3 mb-4">

                    <div class="flex items-center gap-3">

                        <div class="w-11 h-11 rounded-2xl bg-blue-50 flex items-center justify-center text-xl">
                            📍
                        </div>

                        <div>
                            <h2 class="font-bold text-gray-800">
                                Current Location
                            </h2>

                            <p class="text-xs text-gray-500 mt-1">
                                Automatically captured from GPS
                            </p>
                        </div>

                    </div>

                    <div
                        id="gpsStatusBadge"
                        class="text-xs font-semibold px-2.5 py-1.5 rounded-full bg-yellow-50 text-yellow-700">

                        Detecting

                    </div>

                </div>

                <div>

                    <label class="text-xs font-semibold text-gray-600">
                        Address
                    </label>

                    <div class="relative mt-1.5">

                        <input
                            type="text"
                            id="location"
                            name="location"
                            readonly
                            value="{{ old('location') }}"
                            placeholder="Detecting current location..."
                            class="w-full rounded-xl bg-gray-100 border-gray-200 text-sm pr-10">

                        <span
                            id="locationIcon"
                            class="absolute right-3 top-1/2 -translate-y-1/2">

                            📍

                        </span>

                    </div>

                </div>

                <input
                    type="hidden"
                    name="latitude"
                    id="latitude"
                    value="{{ old('latitude') }}">

                <input
                    type="hidden"
                    name="longitude"
                    id="longitude"
                    value="{{ old('longitude') }}">

                <div
                    id="gpsCoordinates"
                    class="hidden mt-3 bg-blue-50 border border-blue-100 rounded-xl px-3 py-2">

                    <p class="text-xs text-blue-700">
                        GPS coordinates captured successfully.
                    </p>

                </div>

            </div>

        </div>

        <!-- DATE AND TIME -->

        <div class="px-5">

            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-5">

                <div class="flex items-center gap-3 mb-4">

                    <div class="w-11 h-11 rounded-2xl bg-blue-50 flex items-center justify-center text-xl">
                        📅
                    </div>

                    <div>
                        <h2 class="font-bold text-gray-800">
                            Citation Date & Time
                        </h2>

                        <p class="text-xs text-gray-500 mt-1">
                            Automatically recorded by the system.
                        </p>
                    </div>

                </div>

                <div class="grid grid-cols-2 gap-3">

                    <div>

                        <label class="text-xs font-semibold text-gray-600">
                            Date
                        </label>

                        <input
                            type="text"
                            readonly
                            value="{{ now()->setTimezone('Asia/Manila')->format('F d, Y') }}"
                            class="w-full mt-1.5 rounded-xl bg-gray-100 border-gray-200 text-sm">

                    </div>

                    <div>

                        <label class="text-xs font-semibold text-gray-600">
                            Time
                        </label>

                        <input
                            type="text"
                            readonly
                            value="{{ now()->setTimezone('Asia/Manila')->format('h:i A') }}"
                            class="w-full mt-1.5 rounded-xl bg-gray-100 border-gray-200 text-sm">

                    </div>

                </div>

            </div>

        </div>

        <!-- SUBMIT -->

        <div class="px-5 pt-1 mb-6">

            <button
                type="submit"
                class="w-full bg-[#005FBF] hover:bg-[#004F9F] active:scale-[0.99] text-white rounded-2xl py-4 px-5 font-bold text-base shadow-lg transition flex items-center justify-center gap-2">

                <span class="text-lg">
                    ✓
                </span>

                <span>
                    Review & Submit Citation
                </span>

            </button>

            <p class="text-center text-xs text-gray-400 mt-3">
                Review all information and evidence before submitting.
            </p>

        </div>

    </form>

</div>

<!-- DRIVER'S LICENSE OCR JAVASCRIPT -->

<script>

    function openLicenseCamera() {

        const input =
            document.getElementById('driver_license');

        input.setAttribute(
            'capture',
            'environment'
        );

        input.click();
    }


    function openLicenseFile() {

        const input =
            document.getElementById('driver_license');

        input.removeAttribute('capture');

        input.click();
    }


    function processDriverLicense(event) {

        const file =
            event.target.files[0];

        if (!file) {
            return;
        }


        /*
        |--------------------------------------------------------------------------
        | SHOW IMAGE PREVIEW
        |--------------------------------------------------------------------------
        */

        const preview =
            document.getElementById(
                'licensePreview'
            );

        const previewContainer =
            document.getElementById(
                'licensePreviewContainer'
            );

        const imageUrl =
            URL.createObjectURL(file);

        preview.src = imageUrl;

        previewContainer.classList.remove(
            'hidden'
        );


        /*
        |--------------------------------------------------------------------------
        | UPDATE OCR STATUS
        |--------------------------------------------------------------------------
        */

        setOcrStatus(
            'processing',
            'Scanning driver\'s license...',
            'Please wait while OCR.space extracts the information.'
        );


        /*
        |--------------------------------------------------------------------------
        | PREPARE FORM DATA
        |--------------------------------------------------------------------------
        */

        const formData =
            new FormData();

        formData.append(
            'driver_license',
            file
        );


        /*
        |--------------------------------------------------------------------------
        | CSRF TOKEN
        |--------------------------------------------------------------------------
        */

        const csrfToken =
            document
                .querySelector(
                    'meta[name="csrf-token"]'
                )
                ?.getAttribute('content');


        /*
        |--------------------------------------------------------------------------
        | SEND TO LARAVEL OCR ROUTE
        |--------------------------------------------------------------------------
        */

        fetch(
            "{{ route('enforcer.ocr.driver-license') }}",
            {
                method: 'POST',

                headers: {
                    'X-CSRF-TOKEN':
                        csrfToken,

                    'Accept':
                        'application/json'
                },

                body:
                    formData
            }
        )
        .then(async response => {

            const data =
                await response.json();

            if (!response.ok) {

                throw new Error(
                    data.message ||
                    'OCR request failed.'
                );
            }

            return data;
        })
        .then(data => {

            if (!data.success) {

                throw new Error(
                    data.message ||
                    'OCR could not extract information.'
                );
            }


            /*
            |--------------------------------------------------------------------------
            | DEBUG OCR OUTPUT
            |--------------------------------------------------------------------------
            */

            console.log(
                'OCR RAW TEXT:',
                data.raw_text
            );

            console.log(
                'OCR PARSED DATA:',
                data.data
            );


            /*
            |--------------------------------------------------------------------------
            | FILL DRIVER FIELDS
            |--------------------------------------------------------------------------
            */

            const driver =
                data.data || {};


            document.getElementById(
                'first_name'
            ).value =
                driver.first_name || '';


            document.getElementById(
                'middle_name'
            ).value =
                driver.middle_name || '';


            document.getElementById(
                'last_name'
            ).value =
                driver.last_name || '';


            document.getElementById(
                'license_number'
            ).value =
                driver.license_number || '';


            document.getElementById(
                'address'
            ).value =
                driver.address || '';


            document.getElementById(
                'birth_date'
            ).value =
                driver.birth_date || '';


            /*
            |--------------------------------------------------------------------------
            | SUCCESS STATUS
            |--------------------------------------------------------------------------
            */

            setOcrStatus(
                'success',
                'Driver information extracted',
                'Please review the extracted information and correct anything that is inaccurate.'
            );

        })
        .catch(error => {

            console.error(
                'OCR Error:',
                error
            );


            setOcrStatus(
                'error',
                'OCR scanning failed',
                error.message ||
                'Unable to process the driver\'s license.'
            );

        });

    }


    /*
    |--------------------------------------------------------------------------
    | OCR STATUS UI
    |--------------------------------------------------------------------------
    */

    function setOcrStatus(
        type,
        title,
        message
    ) {

        const icon =
            document.getElementById(
                'ocrIcon'
            );

        const titleElement =
            document.getElementById(
                'ocrTitle'
            );

        const messageElement =
            document.getElementById(
                'ocrMessage'
            );

        const badge =
            document.getElementById(
                'ocrBadge'
            );


        titleElement.textContent =
            title;

        messageElement.textContent =
            message;


        if (type === 'processing') {

            icon.textContent = '⏳';

            badge.textContent =
                'Scanning';

            badge.className =
                'text-xs font-medium px-2.5 py-1 rounded-full bg-yellow-100 text-yellow-700';

            icon.className =
                'w-11 h-11 rounded-full bg-yellow-100 flex items-center justify-center text-lg flex-shrink-0';

            return;
        }


        if (type === 'success') {

            icon.textContent = '✓';

            badge.textContent =
                'Completed';

            badge.className =
                'text-xs font-medium px-2.5 py-1 rounded-full bg-green-100 text-green-700';

            icon.className =
                'w-11 h-11 rounded-full bg-green-100 flex items-center justify-center text-lg flex-shrink-0';

            return;
        }


        if (type === 'error') {

            icon.textContent = '⚠️';

            badge.textContent =
                'Failed';

            badge.className =
                'text-xs font-medium px-2.5 py-1 rounded-full bg-red-100 text-red-700';

            icon.className =
                'w-11 h-11 rounded-full bg-red-100 flex items-center justify-center text-lg flex-shrink-0';

            return;
        }


        icon.textContent = '⏳';

        badge.textContent =
            'Ready';

        badge.className =
            'text-xs font-medium px-2.5 py-1 rounded-full bg-gray-100 text-gray-500';

        icon.className =
            'w-11 h-11 rounded-full bg-blue-100 flex items-center justify-center text-lg flex-shrink-0';
    }

</script>

@vite('resources/js/app.js')
```

@endsection
