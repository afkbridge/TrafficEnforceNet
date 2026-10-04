@extends('layouts.enforcer')

@section('title', 'Issue Traffic Citation')

@section('content')

    <div class="min-h-screen bg-[#F5F7FB] pb-20">

        {{-- =========================================================
             HEADER
        ========================================================== --}}
        <header class="sticky top-0 z-50 bg-gradient-to-br from-[#1D5FBF] to-[#2E77E6] text-white shadow-md">
            <div class="max-w-6xl mx-auto px-3 sm:px-5 py-2.5">
                <div class="flex items-center gap-2.5">

                    <a href="{{ route('enforcer.dashboard') }}"
                        class="w-8 h-8 rounded-lg bg-white/15 hover:bg-white/25 flex items-center justify-center text-base transition flex-shrink-0"
                        aria-label="Back to dashboard">
                        ←
                    </a>

                    <div class="min-w-0">
                        <h1 class="text-sm sm:text-base font-bold leading-tight">
                            Issue Traffic Citation
                        </h1>

                        <p class="text-blue-100 text-[10px] sm:text-[11px]">
                            Record violation and required information.
                        </p>
                    </div>

                </div>
            </div>
        </header>


        {{-- =========================================================
             NETWORK STATUS
        ========================================================== --}}
        <div class="max-w-6xl mx-auto px-3 sm:px-5">

            <div id="networkStatusNotification"
                class="mt-1.5 mb-1 flex items-center justify-between gap-2 rounded-lg border border-green-200 bg-green-50 px-2.5 py-1.5">

                <div class="flex items-center gap-1.5 min-w-0">

                    <span id="networkStatusDot"
                        class="w-1.5 h-1.5 rounded-full bg-green-500 flex-shrink-0">
                    </span>

                    <p id="networkStatusTitle"
                        class="text-[10px] font-semibold text-green-700">
                        Online
                    </p>

                    <p id="networkStatusMessage"
                        class="text-[9px] text-green-600 truncate">
                        Internet connection is available.
                    </p>

                </div>

                <span id="networkStatusIcon"
                    class="text-xs text-green-600 flex-shrink-0">
                    ✓
                </span>

            </div>

        </div>


        {{-- =========================================================
             CONTENT
        ========================================================== --}}
        <main class="max-w-6xl mx-auto px-3 sm:px-5">

            {{-- SERVER VALIDATION --}}
            @if ($errors->any())
                <div class="mt-2 bg-red-50 border border-red-200 text-red-700 rounded-lg px-3 py-2">

                    <p class="font-bold text-xs mb-1">
                        Please check the following:
                    </p>

                    <ul class="list-disc ml-4 text-[10px] space-y-0.5">

                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach

                    </ul>

                </div>
            @endif


            {{-- =====================================================
                 OFFLINE SYNC STATUS
            ====================================================== --}}
            <div id="offlineSyncContainer"
                class="pt-1 space-y-1.5">

                <div id="offlinePendingBox"
                    class="hidden rounded-lg border border-orange-200 bg-orange-50 px-3 py-2">

                    <div class="flex items-center justify-between gap-2">

                        <div class="min-w-0">

                            <p id="offlinePendingTitle"
                                class="font-semibold text-orange-800 text-xs">
                                Pending Offline Tickets
                            </p>

                            <p id="offlinePendingMessage"
                                class="text-[10px] text-orange-700">
                                Your ticket is saved on this device.
                            </p>

                        </div>

                        <button type="button"
                            id="syncNowButton"
                            class="hidden shrink-0 rounded-md bg-orange-500 px-2.5 py-1 text-[10px] font-semibold text-white">
                            Sync Now
                        </button>

                    </div>

                </div>


                <div id="offlineSyncingBox"
                    class="hidden rounded-lg border border-blue-200 bg-blue-50 px-3 py-2">

                    <p class="font-semibold text-blue-800 text-xs">
                        Syncing Tickets
                    </p>

                    <p id="offlineSyncingMessage"
                        class="text-[10px] text-blue-700">
                        Please wait while pending tickets are uploaded.
                    </p>

                </div>


                <div id="offlineSuccessBox"
                    class="hidden rounded-lg border border-green-200 bg-green-50 px-3 py-2">

                    <p class="font-semibold text-green-800 text-xs">
                        Tickets Synced Successfully
                    </p>

                    <p id="offlineSuccessMessage"
                        class="text-[10px] text-green-700">
                        All pending tickets have been uploaded.
                    </p>

                </div>


                <div id="offlineErrorBox"
                    class="hidden rounded-lg border border-red-200 bg-red-50 px-3 py-2">

                    <p class="font-semibold text-red-800 text-xs">
                        Sync Failed
                    </p>

                    <p id="offlineErrorMessage"
                        class="text-[10px] text-red-700">
                        Some tickets could not be synchronized.
                    </p>

                </div>

            </div>


            {{-- =====================================================
                 MAIN FORM
            ====================================================== --}}
            <form id="issueTicketForm"
                action="{{ route('enforcer.violations.store') }}"
                method="POST"
                enctype="multipart/form-data"
                class="space-y-2.5 pt-1">

                @csrf


                {{-- =================================================
                     DOCUMENT SCANNERS
                ================================================== --}}
                <section>

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-2.5">

                        {{-- DRIVER LICENSE --}}
                        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-3">

                            <div class="flex items-center justify-between gap-2 mb-2">

                                <div class="flex items-center gap-2 min-w-0">

                                    <div class="w-7 h-7 rounded-lg bg-blue-50 flex items-center justify-center text-sm flex-shrink-0">
                                        🪪
                                    </div>

                                    <div class="min-w-0">

                                        <h2 class="font-bold text-gray-800 text-xs sm:text-sm">
                                            Driver's License
                                        </h2>

                                        <p class="text-[9px] text-gray-500">
                                            Capture or upload for OCR.
                                        </p>

                                    </div>

                                </div>

                                <span id="ocrBadge"
                                    class="shrink-0 text-[9px] font-semibold px-1.5 py-0.5 rounded-full bg-gray-100 text-gray-500">
                                    Ready
                                </span>

                            </div>


                            <div id="licensePreviewContainer"
                                class="hidden mb-2">

                                <div class="relative">

                                    <img id="licensePreview"
                                        class="w-full h-28 object-cover rounded-lg border border-gray-200"
                                        alt="Driver's license preview">

                                    <div class="absolute top-1.5 right-1.5 bg-black/60 text-white text-[9px] px-1.5 py-0.5 rounded">
                                        Preview
                                    </div>

                                </div>

                            </div>


                            <div class="grid grid-cols-2 gap-1.5">

                                <button type="button"
                                    onclick="openLicenseCamera()"
                                    class="bg-[#005FBF] hover:bg-[#004F9F] text-white rounded-lg py-2 px-2 font-bold flex items-center justify-center gap-1.5 text-[10px] transition">

                                    <span>📷</span>
                                    <span>Take Photo</span>

                                </button>


                                <button type="button"
                                    onclick="openLicenseFile()"
                                    class="bg-blue-50 hover:bg-blue-100 text-blue-700 rounded-lg py-2 px-2 font-bold flex items-center justify-center gap-1.5 border border-blue-200 text-[10px] transition">

                                    <span>📁</span>
                                    <span>Upload</span>

                                </button>

                            </div>


                            <input type="file"
                                id="driver_license"
                                name="driver_license"
                                accept="image/*"
                                capture="environment"
                                class="hidden"
                                onchange="processDriverLicense(event)">


                            {{-- JS COMPATIBILITY --}}
                            <span id="ocrIcon" class="hidden"></span>

                            <span id="ocrTitle" class="hidden">
                                Ready
                            </span>

                            <p id="ocrMessage" class="hidden"></p>

                            <div id="ocrStatus" class="hidden"></div>

                        </div>


                        {{-- CITATION TICKET --}}
                        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-3">

                            <div class="flex items-center justify-between gap-2 mb-2">

                                <div class="flex items-center gap-2 min-w-0">

                                    <div class="w-7 h-7 rounded-lg bg-red-50 flex items-center justify-center text-sm flex-shrink-0">
                                        🎫
                                    </div>

                                    <div class="min-w-0">

                                        <h2 class="font-bold text-gray-800 text-xs sm:text-sm">
                                            Citation Ticket
                                        </h2>

                                        <p class="text-[9px] text-gray-500">
                                            Capture or upload for OCR.
                                        </p>

                                    </div>

                                </div>

                                <span id="ticketOcrBadge"
                                    class="shrink-0 text-[9px] font-semibold px-1.5 py-0.5 rounded-full bg-gray-100 text-gray-500">
                                    Ready
                                </span>

                            </div>


                            <div id="ticketPreviewContainer"
                                class="hidden mb-2">

                                <div class="relative">

                                    <img id="ticketPreview"
                                        class="w-full h-28 object-cover rounded-lg border border-gray-200"
                                        alt="Citation ticket preview">

                                    <div class="absolute top-1.5 right-1.5 bg-black/60 text-white text-[9px] px-1.5 py-0.5 rounded">
                                        Preview
                                    </div>

                                </div>

                            </div>


                            <div class="grid grid-cols-2 gap-1.5">

                                <button type="button"
                                    onclick="openTicketCamera()"
                                    class="bg-[#005FBF] hover:bg-[#004F9F] text-white rounded-lg py-2 px-2 font-bold flex items-center justify-center gap-1.5 text-[10px] transition">

                                    <span>📷</span>
                                    <span>Take Photo</span>

                                </button>


                                <button type="button"
                                    onclick="openTicketFile()"
                                    class="bg-blue-50 hover:bg-blue-100 text-blue-700 rounded-lg py-2 px-2 font-bold flex items-center justify-center gap-1.5 border border-blue-200 text-[10px] transition">

                                    <span>📁</span>
                                    <span>Upload</span>

                                </button>

                            </div>


                            <input type="file"
                                id="ticket_image"
                                name="ticket_image"
                                accept="image/*"
                                capture="environment"
                                class="hidden"
                                onchange="previewTicket(event)">


                            {{-- JS COMPATIBILITY --}}
                            <span id="ticketOcrIcon" class="hidden"></span>

                            <span id="ticketOcrTitle" class="hidden">
                                Ready
                            </span>

                            <p id="ticketOcrMessage" class="hidden"></p>

                            <div id="ticketOcrStatus" class="hidden"></div>

                        </div>

                    </div>

                </section>


                {{-- =================================================
                     CITATION / DRIVER INFORMATION
                ================================================== --}}
                <section>

                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-3">

                        <div class="flex items-center justify-between mb-2.5">

                            <div class="flex items-center gap-2">

                                <div class="w-7 h-7 rounded-lg bg-red-50 flex items-center justify-center text-sm">
                                    🎫
                                </div>

                                <h2 class="font-bold text-gray-800 text-xs sm:text-sm">
                                    Citation & Driver Information
                                </h2>

                            </div>

                            <span class="text-[9px] text-gray-400">
                                OCR editable
                            </span>

                        </div>


                        <div class="space-y-2">

                            {{-- TICKET NUMBER --}}
                            <div>

                                <label for="ticket_number"
                                    class="text-[10px] font-semibold text-gray-600">

                                    Ticket Number

                                    <span class="text-red-500">*</span>

                                </label>

                                <input type="text"
                                    id="ticket_number"
                                    name="ticket_number"
                                    value="{{ old('ticket_number') }}"
                                    placeholder="6-digit ticket number"
                                    maxlength="6"
                                    minlength="6"
                                    inputmode="numeric"
                                    pattern="[0-9]{6}"
                                    oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 6)"
                                    class="w-full mt-0.5 rounded-lg bg-blue-50 border-blue-200 focus:border-blue-500 focus:ring-blue-500 text-sm py-2"
                                    required>

                            </div>


                            {{-- LAST / FIRST / MIDDLE --}}
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-2">

                                <div>

                                    <label for="last_name"
                                        class="text-[10px] font-semibold text-gray-600">

                                        Last Name
                                        <span class="text-red-500">*</span>

                                    </label>

                                    <input type="text"
                                        id="last_name"
                                        name="last_name"
                                        value="{{ old('last_name') }}"
                                        placeholder="Last name"
                                        required
                                        class="uppercase placeholder:normal-case w-full mt-0.5 rounded-lg bg-blue-50 border-blue-200 focus:border-blue-500 focus:ring-blue-500 text-xs py-2">

                                </div>


                                <div>

                                    <label for="first_name"
                                        class="text-[10px] font-semibold text-gray-600">

                                        First Name
                                        <span class="text-red-500">*</span>

                                    </label>

                                    <input type="text"
                                        id="first_name"
                                        name="first_name"
                                        value="{{ old('first_name') }}"
                                        placeholder="First name"
                                        required
                                        class="uppercase placeholder:normal-case w-full mt-0.5 rounded-lg bg-blue-50 border-blue-200 focus:border-blue-500 focus:ring-blue-500 text-xs py-2">

                                </div>


                                <div>

                                    <label for="middle_name"
                                        class="text-[10px] font-semibold text-gray-600">

                                        Middle Name

                                    </label>

                                    <input type="text"
                                        id="middle_name"
                                        name="middle_name"
                                        value="{{ old('middle_name') }}"
                                        placeholder="Middle name"
                                        class="uppercase placeholder:normal-case w-full mt-0.5 rounded-lg bg-blue-50 border-blue-200 focus:border-blue-500 focus:ring-blue-500 text-xs py-2">

                                </div>

                            </div>


                            {{-- ADDRESS --}}
                            <div>

                                <label for="address"
                                    class="text-[10px] font-semibold text-gray-600">

                                    Address
                                    <span class="text-red-500">*</span>

                                </label>

                                <input type="text"
                                    id="address"
                                    name="address"
                                    value="{{ old('address') }}"
                                    placeholder="Complete address"
                                    required
                                    class="uppercase placeholder:normal-case w-full mt-0.5 rounded-lg bg-blue-50 border-blue-200 focus:border-blue-500 focus:ring-blue-500 text-xs py-2">

                            </div>


                            {{-- LICENSE / BIRTH DATE --}}
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">

                                {{-- LICENSE --}}
                                <div>

                                    <label for="license_number"
                                        class="text-[10px] font-semibold text-gray-600">

                                        License Number

                                        <span id="licenseRequiredMark"
                                            class="text-red-500">
                                            *
                                        </span>

                                    </label>

                                    <input type="text"
                                        id="license_number"
                                        name="license_number"
                                        value="{{ old('license_number') }}"
                                        placeholder="License number"
                                        {{ old('has_no_license') ? '' : 'required' }}
                                        {{ old('has_no_license') ? 'disabled' : '' }}
                                        class="uppercase placeholder:normal-case w-full mt-0.5 rounded-lg bg-blue-50 border-blue-200 focus:border-blue-500 focus:ring-blue-500 text-xs py-2">


                                    <div class="flex items-center gap-1.5 mt-1">

                                        <input type="checkbox"
                                            id="no_license"
                                            name="has_no_license"
                                            value="1"
                                            {{ old('has_no_license') ? 'checked' : '' }}
                                            class="w-3.5 h-3.5 rounded border-gray-300 text-blue-600 focus:ring-blue-500">

                                        <label for="no_license"
                                            class="text-[10px] font-semibold text-gray-600 cursor-pointer">

                                            No License

                                        </label>

                                    </div>


                                    <p id="licenseOcrMessage"
                                        class="text-[9px] text-blue-600 mt-0.5">

                                        🤖 OCR detected • Editable

                                    </p>

                                </div>


                                {{-- =================================================
                                     BIRTH DATE
                                     YEAR-MONTH-DAY FORMAT
                                     Manual input + calendar + OCR compatible
                                ================================================== --}}
                                <div>

                                    <label for="birth_date"
                                        class="text-[10px] font-semibold text-gray-600">

                                        Birth Date

                                    </label>


                                    <div class="relative mt-0.5">

                                        {{-- 
                                            VISIBLE FIELD

                                            Driver's License format:
                                            YYYY-MM-DD

                                            Example:
                                            2000-10-25
                                        --}}
                                        <input type="text"
                                            id="birth_date"
                                            name="birth_date"
                                            value="{{ old('birth_date') }}"
                                            placeholder="YYYY-MM-DD"
                                            inputmode="numeric"
                                            autocomplete="bday"
                                            maxlength="10"
                                            class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 text-xs py-2 pr-10"
                                            aria-label="Birth Date">


                                        {{-- CALENDAR BUTTON --}}
                                        <button type="button"
                                            id="birthDateCalendarButton"
                                            class="absolute right-2 top-1/2 -translate-y-1/2 text-gray-500 hover:text-blue-600"
                                            aria-label="Choose birth date">

                                            📅

                                        </button>


                                        {{-- 
                                            HIDDEN NATIVE DATE PICKER

                                            The browser calendar still uses
                                            the standard YYYY-MM-DD value.
                                        --}}
                                        <input type="date"
                                            id="birth_date_picker"
                                            class="absolute opacity-0 pointer-events-none w-0 h-0"
                                            tabindex="-1"
                                            aria-hidden="true">

                                    </div>


                                    <p class="text-[9px] text-gray-400 mt-0.5">

                                        Type YYYY-MM-DD or choose from the calendar.

                                    </p>


                                    <p id="birthDateValidationMessage"
                                        class="hidden text-[9px] text-red-600 mt-0.5">

                                        Please enter a valid birth date.

                                    </p>

                                </div>

                            </div>

                        </div>

                    </div>

                </section>


                {{-- =================================================
                     LOCATION + VEHICLE
                ================================================== --}}
                <section>

                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-3">

                        <div class="flex items-center justify-between mb-2.5">

                            <div class="flex items-center gap-2">

                                <div class="w-7 h-7 rounded-lg bg-blue-50 flex items-center justify-center text-sm">
                                    📍
                                </div>

                                <h2 class="font-bold text-gray-800 text-xs sm:text-sm">
                                    Violation Location & Vehicle
                                </h2>

                            </div>


                            <div class="flex items-center gap-1">

                                <span id="gpsStatusBadge"
                                    class="text-[9px] font-semibold px-1.5 py-0.5 rounded-full bg-yellow-50 text-yellow-700">

                                    Detecting

                                </span>

                                <button type="button"
                                    id="manualLocationButton"
                                    class="text-[9px] font-semibold px-2 py-1 rounded-md border border-blue-200 bg-blue-50 text-blue-600">

                                    Manual

                                </button>

                            </div>

                        </div>


                        {{-- GPS / MANUAL LOCATION --}}
                        <div id="gpsLocationContainer">

                            <label for="location"
                                class="text-[10px] font-semibold text-gray-600">

                                Place of Violation

                                <span class="text-red-500">*</span>

                            </label>


                            <div class="relative mt-0.5">

                                <input type="text"
                                    id="location"
                                    name="location"
                                    readonly
                                    value="{{ old('location') }}"
                                    placeholder="Detecting current location..."
                                    class="uppercase placeholder:normal-case w-full rounded-lg bg-gray-100 border-gray-200 text-xs pr-8 py-2">

                                <span id="locationIcon"
                                    class="absolute right-2.5 top-1/2 -translate-y-1/2 text-xs">

                                    📍

                                </span>

                            </div>

                        </div>


                        {{-- MANUAL LOCATION --}}
                        <div id="manualLocationContainer"
                            class="hidden mt-2">

                            <div class="flex items-center justify-between mb-1.5">

                                <p class="text-[10px] font-semibold text-gray-700">
                                    Manual Place of Violation
                                </p>

                                <button type="button"
                                    id="useGpsButton"
                                    class="text-[9px] font-semibold text-blue-600">

                                    Use GPS

                                </button>

                            </div>


                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">

                                <div>

                                    <label for="manualStreet"
                                        class="text-[10px] font-semibold text-gray-600">

                                        Street / Place
                                        <span class="text-red-500">*</span>

                                    </label>

                                    <input type="text"
                                        id="manualStreet"
                                        placeholder="e.g. Macabulos Drive"
                                        class="uppercase placeholder:normal-case w-full mt-0.5 rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 text-xs py-2">

                                </div>


                                <div>

                                    <label for="manualCity"
                                        class="text-[10px] font-semibold text-gray-600">

                                        City

                                    </label>

                                    <input type="text"
                                        id="manualCity"
                                        value="TARLAC CITY"
                                        readonly
                                        class="uppercase w-full mt-0.5 rounded-lg bg-gray-100 border-gray-200 text-xs py-2 text-gray-600">

                                </div>

                            </div>

                        </div>


                        <p id="locationValidationMessage"
                            class="hidden text-[9px] text-red-600 mt-1">

                            Violation location is required.

                        </p>


                        <input type="hidden"
                            name="latitude"
                            id="latitude"
                            value="{{ old('latitude') }}">

                        <input type="hidden"
                            name="longitude"
                            id="longitude"
                            value="{{ old('longitude') }}">


                        {{-- VEHICLE --}}
                        <div class="border-t border-gray-100 mt-2.5 pt-2.5">

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">

                                {{-- VEHICLE TYPE --}}
                                <div>

                                    <label for="vehicle_type"
                                        class="text-[10px] font-semibold text-gray-600">

                                        Vehicle Type

                                    </label>

                                    <select id="vehicle_type"
                                        name="vehicle_type"
                                        class="w-full mt-0.5 rounded-lg bg-blue-50 border-blue-200 focus:border-blue-500 focus:ring-blue-500 text-xs py-2">

                                        <option value=""
                                            disabled
                                            {{ old('vehicle_type') ? '' : 'selected' }}>

                                            Select vehicle type

                                        </option>

                                        <option value="MC"
                                            {{ old('vehicle_type') == 'MC' ? 'selected' : '' }}>

                                            MC

                                        </option>

                                        <option value="MTC Private"
                                            {{ old('vehicle_type') == 'MTC Private' ? 'selected' : '' }}>

                                            MTC Private

                                        </option>

                                        <option value="MTC For Hire"
                                            {{ old('vehicle_type') == 'MTC For Hire' ? 'selected' : '' }}>

                                            MTC For Hire

                                        </option>

                                        <option value="PUJ"
                                            {{ old('vehicle_type') == 'PUJ' ? 'selected' : '' }}>

                                            PUJ

                                        </option>

                                        <option value="Private Vehicle"
                                            {{ old('vehicle_type') == 'Private Vehicle' ? 'selected' : '' }}>

                                            Private Vehicle

                                        </option>

                                        <option value="Others"
                                            {{ old('vehicle_type') == 'Others' ? 'selected' : '' }}>

                                            Others

                                        </option>

                                    </select>


                                    <div id="otherVehicleTypeContainer"
                                        class="mt-1"
                                        style="{{ old('vehicle_type') === 'Others' ? '' : 'display: none;' }}">

                                        <input type="text"
                                            id="other_vehicle_type"
                                            name="other_vehicle_type"
                                            value="{{ old('other_vehicle_type') }}"
                                            placeholder="Specify vehicle type"
                                            class="uppercase placeholder:normal-case w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 text-xs py-2">

                                    </div>

                                </div>


                                {{-- PLATE --}}
                                <div>

                                    <label for="plate_number"
                                        class="text-[10px] font-semibold text-gray-600">

                                        Plate Number

                                        <span id="plateRequiredMark"
                                            class="text-red-500">
                                            *
                                        </span>

                                    </label>

                                    <input type="text"
                                        id="plate_number"
                                        name="plate_number"
                                        value="{{ old('plate_number') }}"
                                        placeholder="Plate number"
                                        {{ old('has_no_plate') ? '' : 'required' }}
                                        {{ old('has_no_plate') ? 'disabled' : '' }}
                                        class="uppercase placeholder:normal-case w-full mt-0.5 rounded-lg bg-blue-50 border-blue-200 focus:border-blue-500 focus:ring-blue-500 text-xs py-2">


                                    <div class="flex items-center gap-1.5 mt-1">

                                        <input type="checkbox"
                                            id="no_plate"
                                            name="has_no_plate"
                                            value="1"
                                            {{ old('has_no_plate') ? 'checked' : '' }}
                                            class="w-3.5 h-3.5 rounded border-gray-300 text-blue-600 focus:ring-blue-500">

                                        <label for="no_plate"
                                            class="text-[10px] font-semibold text-gray-600 cursor-pointer">

                                            No Plate Number

                                        </label>

                                    </div>


                                    <p id="plateOcrMessage"
                                        class="text-[9px] text-blue-600 mt-0.5">

                                        🤖 OCR detected • Editable

                                    </p>

                                </div>

                            </div>


                            {{-- REGION / OWNER --}}
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 mt-2">

                                <div>

                                    <label for="region_number"
                                        class="text-[10px] font-semibold text-gray-600">

                                        Region Number

                                    </label>

                                    <input type="text"
                                        id="region_number"
                                        name="region_number"
                                        value="{{ old('region_number') }}"
                                        placeholder="Region number"
                                        class="uppercase placeholder:normal-case w-full mt-0.5 rounded-lg bg-gray-50 border-gray-300 focus:border-blue-500 focus:ring-blue-500 text-xs py-2">

                                </div>


                                <div>

                                    <label for="owner_name"
                                        class="text-[10px] font-semibold text-gray-600">

                                        Vehicle Owner

                                    </label>

                                    <input type="text"
                                        id="owner_name"
                                        name="owner_name"
                                        value="{{ old('owner_name') }}"
                                        placeholder="Vehicle owner"
                                        class="uppercase placeholder:normal-case w-full mt-0.5 rounded-lg bg-blue-50 border-blue-200 focus:border-blue-500 focus:ring-blue-500 text-xs py-2">

                                </div>

                            </div>

                        </div>

                    </div>

                </section>


                {{-- =================================================
                     VIOLATION
                ================================================== --}}
                <section>

                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-3">

                        <div class="flex items-center gap-2 mb-2.5">

                            <div class="w-7 h-7 rounded-lg bg-red-50 flex items-center justify-center text-sm">
                                ⚠️
                            </div>

                            <div>

                                <h2 class="font-bold text-gray-800 text-xs sm:text-sm">
                                    Traffic Violation
                                </h2>

                                <p class="text-[9px] text-gray-500">
                                    Select all violations included in the citation.
                                </p>

                            </div>

                        </div>


                        <div id="violationRows"
                            class="space-y-2">

                            {{-- PRIMARY VIOLATION --}}
                            <div class="violation-row rounded-lg border border-gray-200 bg-gray-50 p-2.5">

                                <div class="flex items-center justify-between gap-2 mb-1.5">

                                    <label for="violation_type_id"
                                        class="text-[10px] font-semibold text-gray-600">

                                        Violation
                                        <span class="text-red-500">*</span>

                                    </label>

                                    <span class="violation-number text-[9px] font-semibold text-gray-500">
                                        Violation 1
                                    </span>

                                </div>


                                <select name="violation_type_id"
                                    id="violation_type_id"
                                    required
                                    class="w-full rounded-lg bg-blue-50 border-blue-200 focus:border-blue-500 focus:ring-blue-500 text-xs py-2">

                                    <option value=""
                                        disabled
                                        {{ old('violation_type_id') ? '' : 'selected' }}>

                                        Select traffic violation

                                    </option>

                                    @foreach ($violationTypes as $type)

                                        <option value="{{ $type->id }}"
                                            {{ old('violation_type_id') == $type->id ? 'selected' : '' }}>

                                            {{ $type->name }}

                                        </option>

                                    @endforeach

                                    <option value="other"
                                        {{ old('violation_type_id') == 'other' ? 'selected' : '' }}>

                                        Others

                                    </option>

                                </select>


                                <div id="otherViolationContainer"
                                    class="{{ old('violation_type_id') == 'other' ? '' : 'hidden' }} mt-1.5">

                                    <input type="text"
                                        id="other_violation"
                                        name="other_violation"
                                        value="{{ old('other_violation') }}"
                                        placeholder="Specify violation"
                                        class="uppercase placeholder:normal-case w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 text-xs py-2"
                                        {{ old('violation_type_id') == 'other' ? 'required' : '' }}>

                                </div>

                            </div>

                        </div>


                        <button type="button"
                            id="addViolationButton"
                            class="mt-2 inline-flex items-center justify-center gap-1 rounded-lg border border-blue-600 px-2.5 py-1.5 text-[10px] font-semibold text-blue-600 hover:bg-blue-50 transition">

                            <span class="text-sm leading-none">+</span>

                            <span>
                                Add More Violation
                            </span>

                        </button>


                        {{-- ADDITIONAL VIOLATION TEMPLATE --}}
                        <template id="additionalViolationTemplate">

                            <div class="additional-violation-row rounded-lg border border-gray-200 bg-gray-50 p-2.5">

                                <div class="flex items-center justify-between gap-2 mb-1.5">

                                    <div class="violation-number text-[9px] font-semibold text-gray-500">
                                        Violation
                                    </div>

                                    <button type="button"
                                        class="remove-violation-button text-[9px] font-semibold text-red-600">

                                        Remove

                                    </button>

                                </div>


                                <label class="text-[10px] font-semibold text-gray-600">

                                    Traffic Violation
                                    <span class="text-red-500">*</span>

                                </label>


                                <select name="additional_violation_type_ids[]"
                                    class="additional-violation-select w-full mt-1 rounded-lg bg-blue-50 border-blue-200 focus:border-blue-500 focus:ring-blue-500 text-xs py-2"
                                    required>

                                    <option value=""
                                        selected
                                        disabled>

                                        Select violation

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


                                <div class="additional-other-violation-container hidden mt-1.5">

                                    <input type="text"
                                        name="additional_other_violation_names[]"
                                        class="additional-other-violation uppercase placeholder:normal-case w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 text-xs py-2"
                                        placeholder="Specify violation"
                                        maxlength="150">

                                </div>

                            </div>

                        </template>


                        {{-- ISSUED BY / REMARKS --}}
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 mt-2.5">

                            <div>

                                <label class="text-[10px] font-semibold text-gray-600">
                                    Issued By
                                </label>

                                <input type="text"
                                    readonly
                                    value="{{ auth()->user()->name }}"
                                    class="w-full mt-0.5 rounded-lg bg-gray-100 border-gray-200 text-xs text-gray-600 py-2">

                            </div>


                            <div>

                                <label for="remarks"
                                    class="text-[10px] font-semibold text-gray-600">

                                    Remarks

                                </label>

                                <input type="text"
                                    id="remarks"
                                    name="remarks"
                                    maxlength="500"
                                    value="{{ old('remarks') }}"
                                    placeholder="Additional notes"
                                    class="uppercase placeholder:normal-case w-full mt-0.5 rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 text-xs py-2">

                            </div>

                        </div>

                    </div>

                </section>


                {{-- =================================================
                     EVIDENCE
                ================================================== --}}
                <section>

                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-3">

                        <div class="flex items-center justify-between gap-2 mb-2">

                            <div class="flex items-center gap-2">

                                <div class="w-7 h-7 rounded-lg bg-blue-50 flex items-center justify-center text-sm">
                                    📸
                                </div>

                                <div>

                                    <h2 class="font-bold text-gray-800 text-xs sm:text-sm">
                                        Violation Evidence
                                    </h2>

                                    <p class="text-[9px] text-gray-500">
                                        Capture clear evidence of the violation.
                                    </p>

                                </div>

                            </div>

                        </div>


                        <label
                            class="border border-dashed border-gray-300 hover:border-blue-400 hover:bg-blue-50 rounded-lg min-h-[90px] flex items-center justify-center gap-3 cursor-pointer transition p-3">

                            <div class="w-9 h-9 rounded-lg bg-blue-50 flex items-center justify-center text-base flex-shrink-0">
                                📷
                            </div>

                            <div class="min-w-0">

                                <h3 class="font-bold text-xs text-gray-800">
                                    Capture Evidence Photo
                                </h3>

                                <p class="text-[9px] text-gray-500 mt-0.5">
                                    Vehicle, violation scene, or road condition.
                                </p>

                                <span
                                    class="mt-1 inline-flex items-center px-2 py-1 rounded-md bg-blue-600 text-white text-[9px] font-semibold">

                                    Choose Photo

                                </span>

                            </div>


                            <input type="file"
                                id="evidence_images"
                                name="evidence_images[]"
                                accept="image/*"
                                multiple
                                class="hidden">

                        </label>


                        <div id="evidencePreviewContainer"
                            class="hidden mt-2">

                            <p class="text-[10px] font-semibold text-gray-600 mb-1">
                                Selected Evidence
                            </p>

                            <div id="evidencePreview"
                                class="grid grid-cols-2 sm:grid-cols-3 gap-1.5">
                            </div>

                        </div>

                    </div>

                </section>


                {{-- =================================================
                     DATE / TIME
                ================================================== --}}
                <section>

                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-3">

                        <div class="flex items-center justify-between">

                            <div class="flex items-center gap-2">

                                <div class="w-7 h-7 rounded-lg bg-blue-50 flex items-center justify-center text-sm">
                                    📅
                                </div>

                                <div>

                                    <h2 class="font-bold text-gray-800 text-xs sm:text-sm">
                                        Citation Date & Time
                                    </h2>

                                    <p class="text-[9px] text-gray-500">
                                        Automatically recorded by the system.
                                    </p>

                                </div>

                            </div>


                            <div class="text-right">

                                <p class="text-[10px] font-semibold text-gray-700">
                                    {{ now()->setTimezone('Asia/Manila')->format('F d, Y') }}
                                </p>

                                <p class="text-[10px] text-gray-500">
                                    {{ now()->setTimezone('Asia/Manila')->format('h:i A') }}
                                </p>

                            </div>

                        </div>

                    </div>

                </section>


                {{-- =================================================
                     SUBMIT
                ================================================== --}}
                <section class="pb-4">

                    <button type="submit"
                        id="submitCitationButton"
                        class="w-full bg-[#005FBF] hover:bg-[#004F9F] active:scale-[0.99] text-white rounded-lg py-3 px-4 font-bold text-xs shadow-md transition flex items-center justify-center gap-2">

                        <span>✓</span>

                        <span>
                            Review & Submit Citation
                        </span>

                    </button>


                    <p class="text-center text-[9px] text-gray-400 mt-1.5">
                        Review all information before submitting.
                    </p>

                </section>

            </form>

        </main>

    </div>


    {{-- ===========================================================
         BIRTH DATE HANDLER
         YEAR-MONTH-DAY FORMAT
         Manual typing + Calendar + Laravel + OCR
    ============================================================ --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {

            const birthDateInput =
                document.getElementById('birth_date');

            const birthDatePicker =
                document.getElementById('birth_date_picker');

            const validationMessage =
                document.getElementById('birthDateValidationMessage');

            const form =
                document.getElementById('issueTicketForm');


            if (
                !birthDateInput ||
                !birthDatePicker
            ) {
                return;
            }


            /*
             * Convert any existing date into YYYY-MM-DD.
             *
             * Supported:
             *
             * YYYY-MM-DD
             * YYYY/MM/DD
             * MM/DD/YYYY
             * MM-DD-YYYY
             * MM.DD.YYYY
             */
            function normalizeBirthDate(value) {

                if (!value) {
                    return '';
                }

                value = value.trim();


                /*
                 * Already YYYY-MM-DD.
                 */
                let match =
                    value.match(
                        /^(\d{4})[-\/](\d{2})[-\/](\d{2})$/
                    );

                if (match) {

                    return (
                        match[1] +
                        '-' +
                        match[2] +
                        '-' +
                        match[3]
                    );

                }


                /*
                 * MM/DD/YYYY or MM-DD-YYYY.
                 */
                match =
                    value.match(
                        /^(\d{2})[-\/\.](\d{2})[-\/\.](\d{4})$/
                    );

                if (match) {

                    return (
                        match[3] +
                        '-' +
                        match[1] +
                        '-' +
                        match[2]
                    );

                }


                return '';

            }


            /*
             * Validate YYYY-MM-DD.
             */
            function validateBirthDate() {

                const value =
                    birthDateInput.value.trim();


                /*
                 * Birth date is optional.
                 */
                if (!value) {

                    birthDateInput.setCustomValidity('');

                    if (validationMessage) {

                        validationMessage.classList.add(
                            'hidden'
                        );

                    }

                    birthDatePicker.value = '';

                    return true;

                }


                /*
                 * Require YYYY-MM-DD.
                 */
                const match =
                    value.match(
                        /^(\d{4})-(\d{2})-(\d{2})$/
                    );


                if (!match) {

                    birthDateInput.setCustomValidity(
                        'Please enter the birth date in YYYY-MM-DD format.'
                    );


                    if (validationMessage) {

                        validationMessage.textContent =
                            'Please enter the birth date in YYYY-MM-DD format.';

                        validationMessage.classList.remove(
                            'hidden'
                        );

                    }

                    return false;

                }


                const year =
                    Number(match[1]);

                const month =
                    Number(match[2]);

                const day =
                    Number(match[3]);


                /*
                 * Prevent impossible dates.
                 *
                 * Example:
                 *
                 * 2000-02-31
                 * 2000-13-01
                 */
                const date =
                    new Date(
                        year,
                        month - 1,
                        day
                    );


                const isValid =
                    date.getFullYear() === year &&
                    date.getMonth() === month - 1 &&
                    date.getDate() === day;


                if (!isValid) {

                    birthDateInput.setCustomValidity(
                        'Please enter a valid birth date.'
                    );


                    if (validationMessage) {

                        validationMessage.textContent =
                            'Please enter a valid birth date.';

                        validationMessage.classList.remove(
                            'hidden'
                        );

                    }

                    return false;

                }


                /*
                 * Birth date cannot be in the future.
                 */
                const today =
                    new Date();

                today.setHours(
                    0,
                    0,
                    0,
                    0
                );


                if (date > today) {

                    birthDateInput.setCustomValidity(
                        'Birth date cannot be in the future.'
                    );


                    if (validationMessage) {

                        validationMessage.textContent =
                            'Birth date cannot be in the future.';

                        validationMessage.classList.remove(
                            'hidden'
                        );

                    }

                    return false;

                }


                /*
                 * Valid date.
                 */
                birthDateInput.setCustomValidity('');

                if (validationMessage) {

                    validationMessage.classList.add(
                        'hidden'
                    );

                }


                birthDatePicker.value =
                    value;


                return true;

            }


            /*
             * MANUAL TYPING
             *
             * Allows the user to type:
             *
             * 20001025
             *
             * and automatically converts it to:
             *
             * 2000-10-25
             */
            birthDateInput.addEventListener(
                'input',
                function () {

                    let value =
                        this.value
                            .replace(/\D/g, '')
                            .slice(0, 8);


                    if (value.length > 6) {

                        value =
                            value.slice(0, 4) +
                            '-' +
                            value.slice(4, 6) +
                            '-' +
                            value.slice(6);

                    } else if (value.length > 4) {

                        value =
                            value.slice(0, 4) +
                            '-' +
                            value.slice(4);

                    }


                    this.value =
                        value;


                    this.setCustomValidity('');


                    if (validationMessage) {

                        validationMessage.classList.add(
                            'hidden'
                        );

                    }


                    if (value.length === 10) {

                        validateBirthDate();

                    } else {

                        birthDatePicker.value = '';

                    }

                }
            );


            /*
             * CALENDAR BUTTON
             */
            const calendarButton =
                document.getElementById(
                    'birthDateCalendarButton'
                );


            if (calendarButton) {

                calendarButton.addEventListener(
                    'click',
                    function () {

                        /*
                         * Synchronize the native
                         * calendar with the text field.
                         */
                        const normalized =
                            normalizeBirthDate(
                                birthDateInput.value
                            );


                        if (normalized) {

                            birthDatePicker.value =
                                normalized;

                        }


                        /*
                         * Open native date picker.
                         */
                        if (
                            typeof birthDatePicker.showPicker ===
                            'function'
                        ) {

                            try {

                                birthDatePicker.showPicker();

                                return;

                            } catch (error) {

                                // Fallback below.

                            }

                        }


                        birthDatePicker.click();

                    }
                );

            }


            /*
             * CALENDAR SELECTION
             *
             * Native date picker already returns:
             *
             * YYYY-MM-DD
             */
            birthDatePicker.addEventListener(
                'change',
                function () {

                    if (!this.value) {

                        birthDateInput.value = '';

                        return;

                    }


                    birthDateInput.value =
                        this.value;


                    validateBirthDate();

                }
            );


            /*
             * BLUR VALIDATION
             */
            birthDateInput.addEventListener(
                'blur',
                function () {

                    /*
                     * If OCR or another source supplied
                     * MM/DD/YYYY, normalize it before
                     * validation.
                     */
                    const normalized =
                        normalizeBirthDate(
                            birthDateInput.value
                        );


                    if (normalized) {

                        birthDateInput.value =
                            normalized;

                    }


                    validateBirthDate();

                }
            );


            /*
             * CHANGE VALIDATION
             */
            birthDateInput.addEventListener(
                'change',
                function () {

                    const normalized =
                        normalizeBirthDate(
                            birthDateInput.value
                        );


                    if (normalized) {

                        birthDateInput.value =
                            normalized;

                    }


                    validateBirthDate();

                }
            );


            /*
             * FINAL FORM VALIDATION
             */
            if (form) {

                form.addEventListener(
                    'submit',
                    function (event) {

                        const normalized =
                            normalizeBirthDate(
                                birthDateInput.value
                            );


                        if (normalized) {

                            birthDateInput.value =
                                normalized;

                        }


                        const isValid =
                            validateBirthDate();


                        if (!isValid) {

                            event.preventDefault();

                            birthDateInput.focus();

                        }

                    }
                );

            }


            /*
             * RESTORE OLD VALUE
             *
             * Laravel normally stores:
             *
             * YYYY-MM-DD
             */
            const initialValue =
                birthDateInput.value.trim();


            if (initialValue) {

                const normalized =
                    normalizeBirthDate(
                        initialValue
                    );


                if (normalized) {

                    birthDateInput.value =
                        normalized;

                    birthDatePicker.value =
                        normalized;

                }

            }

        });
    </script>


    {{-- ===========================================================
         ADD MORE VIOLATION UI
    ============================================================ --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            const addViolationButton =
                document.getElementById(
                    'addViolationButton'
                );

            const violationRows =
                document.getElementById(
                    'violationRows'
                );

            const additionalViolationTemplate =
                document.getElementById(
                    'additionalViolationTemplate'
                );


            if (
                !addViolationButton ||
                !violationRows ||
                !additionalViolationTemplate
            ) {
                return;
            }


            function updateViolationNumbers() {

                const rows =
                    violationRows.querySelectorAll(
                        '.violation-row, .additional-violation-row'
                    );


                rows.forEach(function(row, index) {

                    const numberLabel =
                        row.querySelector(
                            '.violation-number'
                        );


                    if (numberLabel) {

                        numberLabel.textContent =
                            'Violation ' +
                            (index + 1);

                    }

                });

            }


            function setupAdditionalViolation(row) {

                const select =
                    row.querySelector(
                        '.additional-violation-select'
                    );

                const otherContainer =
                    row.querySelector(
                        '.additional-other-violation-container'
                    );

                const otherInput =
                    row.querySelector(
                        '.additional-other-violation'
                    );

                const removeButton =
                    row.querySelector(
                        '.remove-violation-button'
                    );


                if (select) {

                    select.addEventListener(
                        'change',
                        function() {

                            if (this.value === 'other') {

                                if (otherContainer) {

                                    otherContainer.classList.remove(
                                        'hidden'
                                    );

                                }


                                if (otherInput) {

                                    otherInput.required =
                                        true;

                                }

                            } else {

                                if (otherContainer) {

                                    otherContainer.classList.add(
                                        'hidden'
                                    );

                                }


                                if (otherInput) {

                                    otherInput.required =
                                        false;

                                    otherInput.value = '';

                                }

                            }

                        }
                    );

                }


                if (removeButton) {

                    removeButton.addEventListener(
                        'click',
                        function() {

                            row.remove();

                            updateViolationNumbers();

                        }
                    );

                }

            }


            addViolationButton.addEventListener(
                'click',
                function() {

                    const clone =
                        additionalViolationTemplate.content.cloneNode(
                            true
                        );


                    const newRow =
                        clone.querySelector(
                            '.additional-violation-row'
                        );


                    violationRows.appendChild(clone);


                    if (newRow) {

                        setupAdditionalViolation(
                            newRow
                        );

                    }


                    updateViolationNumbers();

                }
            );


            const primaryViolation =
                document.getElementById(
                    'violation_type_id'
                );

            const primaryOtherContainer =
                document.getElementById(
                    'otherViolationContainer'
                );

            const primaryOtherInput =
                document.getElementById(
                    'other_violation'
                );


            if (
                primaryViolation &&
                primaryOtherContainer &&
                primaryOtherInput
            ) {

                if (
                    primaryViolation.value ===
                    'other'
                ) {

                    primaryOtherContainer.classList.remove(
                        'hidden'
                    );

                    primaryOtherInput.required =
                        true;

                }


                primaryViolation.addEventListener(
                    'change',
                    function() {

                        if (this.value === 'other') {

                            primaryOtherContainer.classList.remove(
                                'hidden'
                            );

                            primaryOtherInput.required =
                                true;

                        } else {

                            primaryOtherContainer.classList.add(
                                'hidden'
                            );

                            primaryOtherInput.required =
                                false;

                            primaryOtherInput.value =
                                '';

                        }

                    }
                );

            }

        });
    </script>


    {{-- ===========================================================
         MANUAL LOCATION TOGGLE
    ============================================================ --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            const manualLocationButton =
                document.getElementById(
                    'manualLocationButton'
                );

            const useGpsButton =
                document.getElementById(
                    'useGpsButton'
                );

            const manualLocationContainer =
                document.getElementById(
                    'manualLocationContainer'
                );

            const gpsLocationContainer =
                document.getElementById(
                    'gpsLocationContainer'
                );

            const manualStreet =
                document.getElementById(
                    'manualStreet'
                );

            const manualCity =
                document.getElementById(
                    'manualCity'
                );

            const locationInput =
                document.getElementById(
                    'location'
                );

            const locationValidationMessage =
                document.getElementById(
                    'locationValidationMessage'
                );

            const gpsStatusBadge =
                document.getElementById(
                    'gpsStatusBadge'
                );

            const latitude =
                document.getElementById(
                    'latitude'
                );

            const longitude =
                document.getElementById(
                    'longitude'
                );

            const form =
                document.getElementById(
                    'issueTicketForm'
                );


            if (
                !manualLocationButton ||
                !useGpsButton ||
                !manualLocationContainer ||
                !gpsLocationContainer ||
                !manualStreet ||
                !manualCity ||
                !locationInput
            ) {
                return;
            }


            manualLocationButton.addEventListener(
                'click',
                function() {

                    gpsLocationContainer.classList.add(
                        'hidden'
                    );

                    manualLocationContainer.classList.remove(
                        'hidden'
                    );

                    manualLocationButton.classList.add(
                        'hidden'
                    );


                    if (gpsStatusBadge) {

                        gpsStatusBadge.textContent =
                            'Manual';

                        gpsStatusBadge.className =
                            'text-[9px] font-semibold px-1.5 py-0.5 rounded-full bg-blue-50 text-blue-700';

                    }


                    if (locationValidationMessage) {

                        locationValidationMessage.classList.add(
                            'hidden'
                        );

                    }


                    manualStreet.focus();

                }
            );


            useGpsButton.addEventListener(
                'click',
                function() {

                    manualLocationContainer.classList.add(
                        'hidden'
                    );

                    gpsLocationContainer.classList.remove(
                        'hidden'
                    );

                    manualLocationButton.classList.remove(
                        'hidden'
                    );


                    manualStreet.value = '';


                    if (latitude) {
                        latitude.value = '';
                    }

                    if (longitude) {
                        longitude.value = '';
                    }


                    locationInput.value = '';

                    locationInput.placeholder =
                        'Detecting current location...';

                    locationInput.readOnly = true;


                    if (gpsStatusBadge) {

                        gpsStatusBadge.textContent =
                            'Detecting';

                        gpsStatusBadge.className =
                            'text-[9px] font-semibold px-1.5 py-0.5 rounded-full bg-yellow-50 text-yellow-700';

                    }

                }
            );


            manualStreet.maxLength = 100;


            manualStreet.addEventListener(
                'input',
                function() {

                    const pos =
                        manualStreet.selectionStart;


                    const cleaned =
                        manualStreet.value
                            .replace(
                                /[^\p{L}\p{N}\s,.\-#\/'()]/gu,
                                ''
                            )
                            .replace(
                                /\s{2,}/g,
                                ' '
                            )
                            .replace(
                                /^\s+/,
                                ''
                            )
                            .toUpperCase();


                    if (
                        cleaned !==
                        manualStreet.value
                    ) {

                        const removed =
                            Math.max(
                                0,
                                manualStreet.value.length -
                                cleaned.length
                            );


                        manualStreet.value =
                            cleaned;


                        try {

                            const newPos =
                                Math.max(
                                    0,
                                    (pos ?? cleaned.length) -
                                    removed
                                );


                            manualStreet.setSelectionRange(
                                newPos,
                                newPos
                            );

                        } catch (e) {
                            // Ignore caret positioning errors.
                        }

                    }


                    const street =
                        manualStreet.value.trim();

                    const city =
                        manualCity.value
                            .trim()
                            .toUpperCase();


                    locationInput.value =
                        street
                            ? street + ', ' + city
                            : '';

                }
            );


            if (form) {

                form.addEventListener(
                    'submit',
                    function(event) {

                        if (
                            !manualLocationContainer
                                .classList
                                .contains('hidden')
                        ) {

                            const street =
                                manualStreet.value
                                    .trim()
                                    .toUpperCase();


                            if (!street) {

                                event.preventDefault();

                                manualStreet.focus();

                                locationValidationMessage.classList.remove(
                                    'hidden'
                                );

                                return;

                            }


                            locationInput.value =
                                street +
                                ', ' +
                                manualCity.value
                                    .trim()
                                    .toUpperCase();

                        }

                    }
                );

            }

        });
    </script>


    {{-- =========================================================
         NETWORK STATUS
    ========================================================== --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            const notification =
                document.getElementById(
                    'networkStatusNotification'
                );

            const dot =
                document.getElementById(
                    'networkStatusDot'
                );

            const title =
                document.getElementById(
                    'networkStatusTitle'
                );

            const message =
                document.getElementById(
                    'networkStatusMessage'
                );

            const icon =
                document.getElementById(
                    'networkStatusIcon'
                );


            if (
                !notification ||
                !dot ||
                !title ||
                !message ||
                !icon
            ) {
                return;
            }


            function updateNetworkStatus() {

                if (navigator.onLine) {

                    notification.className =
                        'mt-1.5 mb-1 flex items-center justify-between gap-2 rounded-lg border border-green-200 bg-green-50 px-2.5 py-1.5';

                    dot.className =
                        'w-1.5 h-1.5 rounded-full bg-green-500 flex-shrink-0';

                    title.className =
                        'text-[10px] font-semibold text-green-700';

                    title.textContent =
                        'Online';

                    message.className =
                        'text-[9px] text-green-600 truncate';

                    message.textContent =
                        'Internet connection is available.';

                    icon.className =
                        'text-xs text-green-600 flex-shrink-0';

                    icon.textContent =
                        '✓';

                } else {

                    notification.className =
                        'mt-1.5 mb-1 flex items-center justify-between gap-2 rounded-lg border border-orange-200 bg-orange-50 px-2.5 py-1.5';

                    dot.className =
                        'w-1.5 h-1.5 rounded-full bg-orange-500 flex-shrink-0';

                    title.className =
                        'text-[10px] font-semibold text-orange-700';

                    title.textContent =
                        'Offline';

                    message.className =
                        'text-[9px] text-orange-600 truncate';

                    message.textContent =
                        'No internet connection. You can continue working offline.';

                    icon.className =
                        'text-xs text-orange-600 flex-shrink-0';

                    icon.textContent =
                        '⚠';

                }

            }


            updateNetworkStatus();


            window.addEventListener(
                'online',
                updateNetworkStatus
            );

            window.addEventListener(
                'offline',
                updateNetworkStatus
            );

        });
    </script>


    @vite('resources/js/enforcer/issue-ticket.js')

@endsection