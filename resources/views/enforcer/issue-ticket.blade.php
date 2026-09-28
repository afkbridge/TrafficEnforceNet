@extends('layouts.enforcer')

@section('title', 'Issue Traffic Citation')

@section('content')

    <div class="min-h-screen bg-[#F5F7FB] pb-24">

        {{-- =========================================================
             TOP HEADER
        ========================================================== --}}
        <header class="sticky top-0 z-50 bg-gradient-to-br from-[#1D5FBF] to-[#2E77E6] text-white shadow-md">

            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3">

                <div class="flex items-center gap-3">

                    <a href="{{ route('enforcer.dashboard') }}"
                        class="w-9 h-9 rounded-xl bg-white/15 hover:bg-white/25 flex items-center justify-center text-lg transition flex-shrink-0"
                        aria-label="Back to dashboard">
                        ←
                    </a>

                    <div class="min-w-0">

                        <h1 class="text-base sm:text-lg font-bold leading-tight">
                            Issue Traffic Citation
                        </h1>

                        <p class="text-blue-100 text-[11px] sm:text-xs mt-0.5">
                            Record a traffic violation and required evidence.
                        </p>

                    </div>

                </div>

            </div>

        </header>

        {{-- =========================================================
     CONNECTION STATUS
========================================================= --}}
        <div class="max-w-7xl mx-auto px-3 sm:px-5 lg:px-8">

            <div id="networkStatusNotification"
                class="mt-1.5 mb-1 flex items-center justify-between gap-2 rounded-lg border border-green-200 bg-green-50 px-3 py-1.5">
                <div class="flex items-center gap-2 min-w-0">

                    <span id="networkStatusDot" class="w-2 h-2 rounded-full bg-green-500 flex-shrink-0"></span>

                    <div class="min-w-0">

                        <p id="networkStatusTitle" class="text-[11px] font-semibold text-green-700">
                            Online
                        </p>

                        <p id="networkStatusMessage" class="text-[10px] text-green-600 truncate">
                            Internet connection is available.
                        </p>

                    </div>

                </div>

                <span id="networkStatusIcon" class="text-sm text-green-600 flex-shrink-0">
                    ✓
                </span>

            </div>

        </div>

        {{-- =========================================================
             PAGE CONTENT
        ========================================================== --}}
        <main class="max-w-7xl mx-auto px-3 sm:px-5 lg:px-8">


            {{-- =====================================================
                 SERVER VALIDATION ERRORS
            ====================================================== --}}
            @if ($errors->any())

                <div class="mt-4 bg-red-50 border border-red-200 text-red-700 rounded-xl p-3 shadow-sm">

                    <div class="flex items-start gap-2.5">

                        <div class="w-8 h-8 rounded-full bg-red-100 flex items-center justify-center flex-shrink-0 text-sm">
                            ⚠️
                        </div>

                        <div class="min-w-0">

                            <h3 class="font-bold text-xs mb-1">
                                Please check the following:
                            </h3>

                            <ul class="list-disc ml-4 text-xs space-y-0.5">

                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach

                            </ul>

                        </div>

                    </div>

                </div>

            @endif


            {{-- =====================================================
                 OFFLINE SYNC STATUS
            ====================================================== --}}
            <div id="offlineSyncContainer" class="pt-0 space-y-2">

                {{-- PENDING OFFLINE TICKETS --}}
                <div id="offlinePendingBox" class="hidden rounded-xl border border-orange-200 bg-orange-50 p-3">

                    <div class="flex items-center justify-between gap-3">

                        <div class="flex items-center gap-2.5 min-w-0">

                            <div class="text-orange-500 text-lg flex-shrink-0">
                                ⚠️
                            </div>

                            <div class="min-w-0">

                                <p id="offlinePendingTitle" class="font-semibold text-orange-800 text-sm">
                                    Pending Offline Tickets
                                </p>

                                <p id="offlinePendingMessage" class="mt-0.5 text-xs text-orange-700">
                                    Your ticket is saved on this device.
                                </p>

                            </div>

                        </div>

                        <button type="button" id="syncNowButton"
                            class="hidden shrink-0 rounded-lg bg-orange-500 px-3 py-1.5 text-xs font-semibold text-white hover:bg-orange-600 transition">
                            Sync Now
                        </button>

                    </div>

                </div>


                {{-- SYNCING --}}
                <div id="offlineSyncingBox" class="hidden rounded-xl border border-blue-200 bg-blue-50 p-3">

                    <div class="flex items-center gap-2.5">

                        <div class="text-blue-500 text-lg flex-shrink-0">
                            🔄
                        </div>

                        <div class="min-w-0">

                            <p class="font-semibold text-blue-800 text-sm">
                                Syncing Tickets
                            </p>

                            <p id="offlineSyncingMessage" class="mt-0.5 text-xs text-blue-700">
                                Please wait while your pending tickets are uploaded.
                            </p>

                        </div>

                    </div>

                </div>


                {{-- SUCCESS --}}
                <div id="offlineSuccessBox" class="hidden rounded-xl border border-green-200 bg-green-50 p-3">

                    <div class="flex items-center gap-2.5">

                        <div class="text-green-500 text-lg flex-shrink-0">
                            ✓
                        </div>

                        <div class="min-w-0">

                            <p class="font-semibold text-green-800 text-sm">
                                Tickets Synced Successfully
                            </p>

                            <p id="offlineSuccessMessage" class="mt-0.5 text-xs text-green-700">
                                All pending tickets have been uploaded.
                            </p>

                        </div>

                    </div>

                </div>


                {{-- ERROR --}}
                <div id="offlineErrorBox" class="hidden rounded-xl border border-red-200 bg-red-50 p-3">

                    <div class="flex items-center gap-2.5">

                        <div class="text-red-500 text-lg flex-shrink-0">
                            ⚠️
                        </div>

                        <div class="min-w-0">

                            <p class="font-semibold text-red-800 text-sm">
                                Sync Failed
                            </p>

                            <p id="offlineErrorMessage" class="mt-0.5 text-xs text-red-700">
                                Some tickets could not be synchronized.
                            </p>

                        </div>

                    </div>

                </div>

            </div>


            {{-- =====================================================
                 MAIN FORM
            ====================================================== --}}
            <form id="issueTicketForm" action="{{ route('enforcer.violations.store') }}" method="POST"
                enctype="multipart/form-data" class="space-y-4 pt-1">

                @csrf


                {{-- =================================================
                     DOCUMENT SCANNERS
                ================================================== --}}
                <section>

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">


                        {{-- =================================================
                             DRIVER'S LICENSE
                        ================================================== --}}
                        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4">

                            {{-- HEADER --}}
                            <div class="flex items-center gap-2.5 mb-3">

                                <div
                                    class="w-9 h-9 rounded-xl bg-blue-50 flex items-center justify-center text-lg flex-shrink-0">
                                    🪪
                                </div>

                                <div class="min-w-0">

                                    <h2 class="font-bold text-gray-800 text-sm sm:text-base">
                                        Driver's License
                                    </h2>

                                    <p class="text-[11px] text-gray-500 mt-0.5">
                                        Capture or upload the driver's license for OCR.
                                    </p>

                                </div>

                            </div>


                            {{-- LICENSE PREVIEW --}}
                            <div id="licensePreviewContainer" class="hidden mb-3">

                                <div class="relative">

                                    <img id="licensePreview"
                                        class="w-full h-40 sm:h-44 object-cover rounded-xl border border-gray-200"
                                        alt="Driver's license preview">

                                    <div
                                        class="absolute top-2 right-2 bg-black/60 text-white text-[10px] px-2 py-1 rounded-full">
                                        License Preview
                                    </div>

                                </div>

                            </div>


                            {{-- CAMERA / UPLOAD --}}
                            <div class="grid grid-cols-2 gap-2.5">

                                <button type="button" onclick="openLicenseCamera()"
                                    class="bg-[#005FBF] hover:bg-[#004F9F] active:scale-[0.98] text-white rounded-xl py-2.5 px-2 font-bold flex flex-col items-center justify-center transition">

                                    <span class="text-xl">
                                        📷
                                    </span>

                                    <span class="mt-1 text-xs">
                                        Take Photo
                                    </span>

                                </button>


                                <button type="button" onclick="openLicenseFile()"
                                    class="bg-blue-50 hover:bg-blue-100 active:scale-[0.98] text-blue-700 rounded-xl py-2.5 px-2 font-bold flex flex-col items-center justify-center border border-blue-200 transition">

                                    <span class="text-xl">
                                        📁
                                    </span>

                                    <span class="mt-1 text-xs">
                                        Upload File
                                    </span>

                                </button>

                            </div>


                            <input type="file" id="driver_license" name="driver_license" accept="image/*"
                                capture="environment" class="hidden" onchange="processDriverLicense(event)">


                            {{-- DRIVER LICENSE OCR STATUS --}}
                            <div id="ocrStatus"
                                class="mt-3 flex items-center justify-between gap-2 border-t border-gray-100 pt-2.5">

                                <div class="flex items-center gap-2 min-w-0">

                                    <span class="text-xs font-semibold text-gray-600 whitespace-nowrap">
                                        🤖 Driver's License OCR
                                    </span>

                                    {{-- Kept for existing JavaScript --}}
                                    <span id="ocrIcon" class="hidden"></span>

                                    <span id="ocrTitle" class="hidden">
                                        Ready
                                    </span>

                                    <p id="ocrMessage" class="hidden"></p>

                                </div>

                                <span id="ocrBadge"
                                    class="shrink-0 text-[10px] font-semibold px-2 py-1 rounded-full bg-gray-100 text-gray-500">
                                    Ready
                                </span>

                            </div>

                        </div>


                        {{-- =================================================
                             CITATION TICKET
                        ================================================== --}}
                        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4">

                            {{-- HEADER --}}
                            <div class="flex items-center gap-2.5 mb-3">

                                <div
                                    class="w-9 h-9 rounded-xl bg-red-50 flex items-center justify-center text-lg flex-shrink-0">
                                    🎫
                                </div>

                                <div class="min-w-0">

                                    <h2 class="font-bold text-gray-800 text-sm sm:text-base">
                                        Citation Ticket
                                    </h2>

                                    <p class="text-[11px] text-gray-500 mt-0.5">
                                        Capture or upload the citation ticket for OCR.
                                    </p>

                                </div>

                            </div>


                            {{-- TICKET PREVIEW --}}
                            <div id="ticketPreviewContainer" class="hidden mb-3">

                                <div class="relative">

                                    <img id="ticketPreview"
                                        class="w-full h-40 sm:h-44 object-cover rounded-xl border border-gray-200"
                                        alt="Citation ticket preview">

                                    <div
                                        class="absolute top-2 right-2 bg-black/60 text-white text-[10px] px-2 py-1 rounded-full">
                                        Ticket Preview
                                    </div>

                                </div>

                            </div>


                            {{-- CAMERA / UPLOAD --}}
                            <div class="grid grid-cols-2 gap-2.5">

                                <button type="button" onclick="openTicketCamera()"
                                    class="bg-[#005FBF] hover:bg-[#004F9F] active:scale-[0.98] text-white rounded-xl py-2.5 px-2 font-bold flex flex-col items-center justify-center transition">

                                    <span class="text-xl">
                                        📷
                                    </span>

                                    <span class="mt-1 text-xs">
                                        Take Photo
                                    </span>

                                </button>


                                <button type="button" onclick="openTicketFile()"
                                    class="bg-blue-50 hover:bg-blue-100 active:scale-[0.98] text-blue-700 rounded-xl py-2.5 px-2 font-bold flex flex-col items-center justify-center border border-blue-200 transition">

                                    <span class="text-xl">
                                        📁
                                    </span>

                                    <span class="mt-1 text-xs">
                                        Upload File
                                    </span>

                                </button>

                            </div>


                            <input type="file" id="ticket_image" name="ticket_image" accept="image/*"
                                capture="environment" class="hidden" onchange="previewTicket(event)">


                            {{-- CITATION TICKET OCR STATUS --}}
                            <div id="ticketOcrStatus"
                                class="mt-3 flex items-center justify-between gap-2 border-t border-gray-100 pt-2.5">

                                <div class="flex items-center gap-2 min-w-0">

                                    <span class="text-xs font-semibold text-gray-600 whitespace-nowrap">
                                        🤖 Citation Ticket OCR
                                    </span>

                                    {{-- Kept for existing JavaScript --}}
                                    <span id="ticketOcrIcon" class="hidden"></span>

                                    <span id="ticketOcrTitle" class="hidden">
                                        Ready
                                    </span>

                                    <p id="ticketOcrMessage" class="hidden"></p>

                                </div>

                                <span id="ticketOcrBadge"
                                    class="shrink-0 text-[10px] font-semibold px-2 py-1 rounded-full bg-gray-100 text-gray-500">
                                    Ready
                                </span>

                            </div>

                        </div>

                    </div>

                </section>


                {{-- =================================================
                     CITATION TICKET NUMBER
                ================================================== --}}
                <section>

                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4">

                        <div class="flex items-center gap-2.5 mb-3">

                            <div
                                class="w-9 h-9 rounded-xl bg-red-50 flex items-center justify-center text-lg flex-shrink-0">
                                🎫
                            </div>

                            <div class="min-w-0">

                                <h2 class="font-bold text-gray-800 text-sm">
                                    Citation Ticket Number
                                </h2>

                                <p class="text-[11px] text-gray-500 mt-0.5">
                                    Automatically extracted from the citation ticket.
                                </p>

                            </div>

                        </div>


                        <input type="text" id="ticket_number" name="ticket_number" value="{{ old('ticket_number') }}"
                            placeholder="OCR detected ticket number" maxlength="6" minlength="6" inputmode="numeric"
                            pattern="[0-9]{6}" oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 6)"
                            class="w-full rounded-lg bg-blue-50 border-blue-200 focus:border-blue-500 focus:ring-blue-500 text-sm py-2.5">

                        <p class="text-[10px] text-blue-600 mt-1">
                            🤖 OCR detected • Editable
                        </p>

                    </div>

                </section>


                {{-- =================================================
                     DRIVER INFORMATION
                ================================================== --}}
                <section>

                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4">

                        <div class="flex items-center gap-2.5 mb-4">

                            <div
                                class="w-9 h-9 rounded-xl bg-blue-50 flex items-center justify-center text-lg flex-shrink-0">
                                👤
                            </div>

                            <div class="min-w-0">

                                <h2 class="font-bold text-gray-800 text-sm sm:text-base">
                                    Driver Information
                                </h2>

                                <p class="text-[11px] text-gray-500 mt-0.5">
                                    Review and edit information extracted from the license or ticket.
                                </p>

                            </div>

                        </div>


                        <div class="space-y-3">

                            {{-- FIRST / MIDDLE NAME --}}
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">

                                <div>

                                    <label for="first_name" class="text-xs font-semibold text-gray-600">
                                        First Name
                                        <span class="text-red-500">*</span>
                                    </label>

                                    <input type="text" id="first_name" name="first_name"
                                        value="{{ old('first_name') }}" placeholder="OCR detected first name" required
                                        class="w-full mt-1 rounded-lg bg-blue-50 border-blue-200 focus:border-blue-500 focus:ring-blue-500 text-sm py-2.5">

                                    <p class="text-[10px] text-blue-600 mt-1">
                                        🤖 OCR detected • Editable
                                    </p>

                                </div>


                                <div>

                                    <label for="middle_name" class="text-xs font-semibold text-gray-600">
                                        Middle Name

                                    </label>

                                    <input type="text" id="middle_name" name="middle_name"
                                        value="{{ old('middle_name') }}" placeholder="OCR detected middle name"
                                        class="w-full mt-1 rounded-lg bg-blue-50 border-blue-200 focus:border-blue-500 focus:ring-blue-500 text-sm py-2.5">

                                    <p class="text-[10px] text-blue-600 mt-1">
                                        🤖 OCR detected • Editable
                                    </p>

                                </div>

                            </div>


                            {{-- LAST NAME / LICENSE --}}
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">

                                <div>

                                    <label for="last_name" class="text-xs font-semibold text-gray-600">
                                        Last Name
                                        <span class="text-red-500">*</span>
                                    </label>

                                    <input type="text" id="last_name" name="last_name" value="{{ old('last_name') }}"
                                        placeholder="OCR detected last name" required
                                        class="w-full mt-1 rounded-lg bg-blue-50 border-blue-200 focus:border-blue-500 focus:ring-blue-500 text-sm py-2.5">

                                    <p class="text-[10px] text-blue-600 mt-1">
                                        🤖 OCR detected • Editable
                                    </p>

                                </div>


                                <div>

                                    <label for="license_number" class="text-xs font-semibold text-gray-600">
                                        License Number
                                        <span id="licenseRequiredMark" class="text-red-500">*</span>
                                    </label>

                                    <input type="text" id="license_number" name="license_number"
                                        value="{{ old('license_number') }}" placeholder="OCR detected license number"
                                        {{ old('has_no_license') ? '' : 'required' }}
                                        {{ old('has_no_license') ? 'disabled' : '' }}
                                        class="w-full mt-1 rounded-lg bg-blue-50 border-blue-200 focus:border-blue-500 focus:ring-blue-500 text-sm py-2.5">

                                    <div class="flex items-center gap-2 mt-2">

                                        <input type="checkbox" id="no_license" name="has_no_license" value="1"
                                            {{ old('has_no_license') ? 'checked' : '' }}
                                            class="w-4 h-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500">

                                        <label for="no_license"
                                            class="text-xs font-semibold text-gray-600 cursor-pointer">
                                            No License
                                        </label>

                                    </div>

                                    <p id="licenseOcrMessage" class="text-[10px] text-blue-600 mt-1">
                                        🤖 OCR detected • Editable
                                    </p>

                                </div>

                            </div>


                            {{-- ADDRESS --}}
                            <div>

                                <label for="address" class="text-xs font-semibold text-gray-600">
                                    Address
                                    <span class="text-red-500">*</span>
                                </label>

                                <input type="text" id="address" name="address" value="{{ old('address') }}"
                                    placeholder="OCR detected address" required
                                    class="w-full mt-1 rounded-lg bg-blue-50 border-blue-200 focus:border-blue-500 focus:ring-blue-500 text-sm py-2.5">

                                <p class="text-[10px] text-blue-600 mt-1">
                                    🤖 OCR detected • Editable
                                </p>

                            </div>


                            {{-- BIRTH DATE --}}
                            <div class="max-w-md">

                                <label for="birth_date" class="text-xs font-semibold text-gray-600">
                                    Birth Date
                                    <span class="text-red-500">*</span>
                                </label>

                                <input type="date" id="birth_date" name="birth_date" value="{{ old('birth_date') }}"
                                    required
                                    class="w-full mt-1 rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 text-sm py-2.5">

                                <p class="text-[10px] text-blue-600 mt-1">
                                    🤖 OCR detected • Editable
                                </p>

                            </div>

                        </div>

                    </div>

                </section>


                {{-- =================================================
                     VEHICLE INFORMATION
                ================================================== --}}
                <section>

                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4">

                        <div class="flex items-center gap-2.5 mb-4">

                            <div
                                class="w-9 h-9 rounded-xl bg-blue-50 flex items-center justify-center text-lg flex-shrink-0">
                                🚗
                            </div>

                            <div class="min-w-0">

                                <h2 class="font-bold text-gray-800 text-sm sm:text-base">
                                    Vehicle Information
                                </h2>

                                <p class="text-[11px] text-gray-500 mt-0.5">
                                    Review and edit information extracted from the citation ticket.
                                </p>

                            </div>

                        </div>


                        <div class="space-y-3">

                            {{-- PLATE / VEHICLE TYPE --}}
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">

                                <div>

                                    <label for="plate_number" class="text-xs font-semibold text-gray-600">
                                        Plate Number
                                        <span id="plateRequiredMark" class="text-red-500">*</span>
                                    </label>

                                    <input type="text" id="plate_number" name="plate_number"
                                        value="{{ old('plate_number') }}" placeholder="OCR detected plate number"
                                        {{ old('has_no_plate') ? '' : 'required' }}
                                        {{ old('has_no_plate') ? 'disabled' : '' }}
                                        class="w-full mt-1 rounded-lg bg-blue-50 border-blue-200 focus:border-blue-500 focus:ring-blue-500 text-sm py-2.5">

                                    <div class="flex items-center gap-2 mt-2">

                                        <input type="checkbox" id="no_plate" name="has_no_plate" value="1"
                                            {{ old('has_no_plate') ? 'checked' : '' }}
                                            class="w-4 h-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500">

                                        <label for="no_plate" class="text-xs font-semibold text-gray-600 cursor-pointer">
                                            No Plate Number
                                        </label>

                                    </div>

                                    <p id="plateOcrMessage" class="text-[10px] text-blue-600 mt-1">
                                        🤖 OCR detected • Editable
                                    </p>

                                </div>


                                <div>

                                    <label for="vehicle_type" class="text-xs font-semibold text-gray-600">
                                        Vehicle Type
                                    </label>

                                    <input type="text" id="vehicle_type" name="vehicle_type"
                                        value="{{ old('vehicle_type') }}" placeholder="e.g. Motorcycle, Sedan, SUV"
                                        class="w-full mt-1 rounded-lg bg-blue-50 border-blue-200 focus:border-blue-500 focus:ring-blue-500 text-sm py-2.5">

                                    <p class="text-[10px] text-blue-600 mt-1">
                                        🤖 OCR detected • Editable
                                    </p>

                                </div>

                            </div>


                            {{-- REGION / OWNER --}}
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">

                                <div>

                                    <label for="region_number" class="text-xs font-semibold text-gray-600">
                                        Region Number
                                    </label>

                                    <input type="text" id="region_number" name="region_number"
                                        value="{{ old('region_number') }}" placeholder="Enter region number"
                                        class="w-full mt-1 rounded-lg bg-gray-50 border-gray-300 focus:border-blue-500 focus:ring-blue-500 text-sm py-2.5">

                                    <p class="text-[10px] text-gray-500 mt-1">
                                        Enter manually if not detected
                                    </p>

                                </div>


                                <div>

                                    <label for="owner_name" class="text-xs font-semibold text-gray-600">
                                        Vehicle Owner
                                    </label>

                                    <input type="text" id="owner_name" name="owner_name"
                                        value="{{ old('owner_name') }}" placeholder="OCR detected vehicle owner"
                                        class="w-full mt-1 rounded-lg bg-blue-50 border-blue-200 focus:border-blue-500 focus:ring-blue-500 text-sm py-2.5">

                                    <p class="text-[10px] text-blue-600 mt-1">
                                        🤖 OCR detected • Editable
                                    </p>

                                </div>

                            </div>

                        </div>

                    </div>

                </section>


                {{-- =================================================
                     VIOLATION INFORMATION
                ================================================== --}}
                <section>

                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4">

                        <div class="flex items-center gap-2.5 mb-4">

                            <div
                                class="w-9 h-9 rounded-xl bg-red-50 flex items-center justify-center text-lg flex-shrink-0">
                                ⚠️
                            </div>

                            <div class="min-w-0">

                                <h2 class="font-bold text-gray-800 text-sm sm:text-base">
                                    Violation Information
                                </h2>

                                <p class="text-[11px] text-gray-500 mt-0.5">
                                    Specify all traffic violations included in this citation.
                                </p>

                            </div>

                        </div>


                        {{-- VIOLATION ROWS --}}
                        <div id="violationRows" class="space-y-3">

                            {{-- PRIMARY VIOLATION --}}
                            <div class="violation-row rounded-xl border border-gray-200 bg-gray-50 p-3">

                                <div class="flex items-center justify-between gap-3 mb-2">

                                    <label for="violation_type_id" class="text-xs font-semibold text-gray-600">
                                        Traffic Violation
                                        <span class="text-red-500">*</span>
                                    </label>

                                    <span class="violation-number text-[10px] font-semibold text-gray-500">
                                        Violation 1
                                    </span>

                                </div>


                                <select name="violation_type_id" id="violation_type_id" required
                                    class="w-full rounded-lg bg-blue-50 border-blue-200 focus:border-blue-500 focus:ring-blue-500 text-sm py-2.5">

                                    <option value="" disabled {{ old('violation_type_id') ? '' : 'selected' }}>
                                        Select violation type
                                    </option>

                                    @foreach ($violationTypes as $type)
                                        <option value="{{ $type->id }}"
                                            {{ old('violation_type_id') == $type->id ? 'selected' : '' }}>
                                            {{ $type->name }}
                                        </option>
                                    @endforeach

                                    <option value="other" {{ old('violation_type_id') == 'other' ? 'selected' : '' }}>
                                        Others
                                    </option>

                                </select>


                                {{-- PRIMARY OTHER VIOLATION --}}
                                <div id="otherViolationContainer"
                                    class="{{ old('violation_type_id') == 'other' ? '' : 'hidden' }} mt-2.5">

                                    <label for="other_violation" class="text-xs font-semibold text-gray-600">
                                        Specify Violation
                                        <span class="text-red-500">*</span>
                                    </label>

                                    <input type="text" id="other_violation" name="other_violation"
                                        value="{{ old('other_violation') }}" placeholder="Enter violation"
                                        class="w-full mt-1 rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 text-sm py-2.5"
                                        {{ old('violation_type_id') == 'other' ? 'required' : '' }}>

                                </div>

                            </div>

                        </div>


                        {{-- ADD MORE --}}
                        <button type="button" id="addViolationButton"
                            class="mt-3 inline-flex items-center justify-center gap-1.5 rounded-lg border border-blue-600 px-3 py-2 text-xs font-semibold text-blue-600 hover:bg-blue-50 transition">

                            <span class="text-base leading-none">
                                +
                            </span>

                            <span>
                                Add More Traffic Violation
                            </span>

                        </button>

                        <p class="text-[10px] text-gray-500 mt-1.5">
                            Add another violation if more than one traffic offense is included.
                        </p>


                        {{-- ADDITIONAL VIOLATION TEMPLATE --}}
                        <template id="additionalViolationTemplate">

                            <div class="additional-violation-row rounded-xl border border-gray-200 bg-gray-50 p-3">

                                <div class="flex items-center justify-between gap-3 mb-2">

                                    <div class="violation-number text-[10px] font-semibold text-gray-500">
                                        Violation
                                    </div>

                                    <button type="button"
                                        class="remove-violation-button text-[10px] font-semibold text-red-600 hover:text-red-700">
                                        Remove
                                    </button>

                                </div>


                                <label class="text-xs font-semibold text-gray-600">
                                    Traffic Violation
                                    <span class="text-red-500">*</span>
                                </label>


                                <select name="additional_violation_type_ids[]"
                                    class="additional-violation-select w-full mt-1 rounded-lg bg-blue-50 border-blue-200 focus:border-blue-500 focus:ring-blue-500 text-sm py-2.5"
                                    required>

                                    <option value="" selected disabled>
                                        Select violation type
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


                                <div class="additional-other-violation-container hidden mt-2.5">

                                    <label class="text-xs font-semibold text-gray-600">
                                        Specify Violation
                                        <span class="text-red-500">*</span>
                                    </label>

                                    <input type="text" name="additional_other_violation_names[]"
                                        class="additional-other-violation w-full mt-1 rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 text-sm py-2.5"
                                        placeholder="Enter violation">

                                </div>

                            </div>

                        </template>


                        {{-- ISSUED BY --}}
                        <div class="mt-4">

                            <label class="text-xs font-semibold text-gray-600">
                                Issued By
                            </label>

                            <div class="relative mt-1">

                                <input type="text" readonly value="{{ auth()->user()->name }}"
                                    class="w-full rounded-lg bg-gray-100 border-gray-200 text-sm text-gray-600 pr-10 py-2.5">

                                <span class="absolute right-3 top-1/2 -translate-y-1/2 text-sm">
                                    ✓
                                </span>

                            </div>

                        </div>


                        {{-- REMARKS --}}
                        <div class="mt-3">

                            <label for="remarks" class="text-xs font-semibold text-gray-600">
                                Remarks
                            </label>

                            <textarea id="remarks" name="remarks" rows="3" placeholder="Enter additional notes"
                                class="w-full mt-1 rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 text-sm">{{ old('remarks') }}</textarea>

                        </div>

                    </div>

                </section>


                {{-- =================================================
                     EVIDENCE PHOTO
                ================================================== --}}
                <section>

                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4">

                        <div class="flex items-center gap-2.5 mb-3">

                            <div
                                class="w-9 h-9 rounded-xl bg-blue-50 flex items-center justify-center text-lg flex-shrink-0">
                                📸
                            </div>

                            <div class="min-w-0">

                                <h2 class="font-bold text-gray-800 text-sm sm:text-base">
                                    Violation Evidence
                                </h2>

                                <p class="text-[11px] text-gray-500 mt-0.5">
                                    Capture clear evidence of the violation.
                                </p>

                            </div>

                        </div>


                        {{-- EVIDENCE PHOTO INPUT --}}
                        <label
                            class="border-2 border-dashed border-gray-300 hover:border-blue-400 hover:bg-blue-50 rounded-2xl min-h-[150px] flex flex-col items-center justify-center cursor-pointer transition p-4 text-center">

                            <div class="w-11 h-11 rounded-xl bg-blue-50 flex items-center justify-center text-xl">
                                📷
                            </div>

                            <h3 class="font-bold text-sm mt-2.5 text-gray-800">
                                Capture Evidence Photo
                            </h3>

                            <p class="text-gray-500 text-[10px] mt-1 max-w-xs leading-relaxed">
                                Take a photo showing the actual traffic violation.
                            </p>

                            <span
                                class="mt-2.5 inline-flex items-center px-3 py-1.5 rounded-lg bg-blue-600 text-white text-[10px] font-semibold">
                                Choose Photo
                            </span>

                            <input type="file" id="evidence_images" name="evidence_images[]" accept="image/*"
                                multiple class="hidden">

                        </label>


                        {{-- SELECTED EVIDENCE PREVIEW --}}
                        <div id="evidencePreviewContainer" class="hidden mt-3">

                            <div class="flex items-center justify-between mb-2">

                                <p class="text-xs font-semibold text-gray-600">
                                    Selected Evidence
                                </p>

                                <p id="evidenceCount" class="text-[10px] text-gray-400">
                                    0 photos
                                </p>

                            </div>


                            <div id="evidencePreview" class="grid grid-cols-1 sm:grid-cols-2 gap-2.5">
                            </div>

                        </div>


                        {{-- RECOMMENDED EVIDENCE --}}
                        <div class="mt-3 bg-gray-50 rounded-xl p-3">

                            <p class="text-[10px] font-semibold text-gray-600">
                                Recommended evidence:
                            </p>

                            <ul class="text-[10px] text-gray-500 mt-1.5 space-y-0.5">

                                <li>✓ Vehicle involved</li>
                                <li>✓ Traffic violation scene</li>
                                <li>✓ Road/location condition</li>

                            </ul>

                        </div>

                    </div>

                </section>


                {{-- =================================================
                     GPS LOCATION + DATE/TIME
                ================================================== --}}
                <section>

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">


                        {{-- =================================================
                             GPS LOCATION
                        ================================================== --}}
                        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4">

                            {{-- LOCATION HEADER --}}
                            <div class="flex items-center justify-between gap-2.5 mb-3">

                                <div class="flex items-center gap-2.5 min-w-0">

                                    <div
                                        class="w-9 h-9 rounded-xl bg-blue-50 flex items-center justify-center text-lg flex-shrink-0">
                                        📍
                                    </div>

                                    <div class="min-w-0">

                                        <h2 class="font-bold text-gray-800 text-sm">
                                            Current Location
                                        </h2>

                                        <p class="text-[10px] text-gray-500 mt-0.5">
                                            Automatically captured from GPS
                                        </p>

                                    </div>

                                </div>


                                {{-- STATUS + MANUAL BUTTON --}}
                                <div class="flex items-center gap-1.5 shrink-0">

                                    <div id="gpsStatusBadge"
                                        class="text-[10px] font-semibold px-2 py-1 rounded-full bg-yellow-50 text-yellow-700">
                                        Detecting
                                    </div>

                                    <button type="button" id="manualLocationButton"
                                        class="text-[10px] font-semibold px-2.5 py-1.5 rounded-lg border border-blue-200 bg-blue-50 text-blue-600 hover:bg-blue-100 transition">
                                        Enter Manually
                                    </button>

                                </div>

                            </div>


                            {{-- =================================================
                                 GPS LOCATION FIELD
                            ================================================== --}}
                            <div id="gpsLocationContainer">

                                <label for="location" class="text-xs font-semibold text-gray-600">
                                    Address
                                    <span class="text-red-500">*</span>
                                </label>

                                <div class="relative mt-1">

                                    <input type="text" id="location" name="location" readonly
                                        value="{{ old('location') }}" placeholder="Detecting current location..."
                                        class="w-full rounded-lg bg-gray-100 border-gray-200 text-sm pr-10 py-2.5">

                                    <span id="locationIcon" class="absolute right-3 top-1/2 -translate-y-1/2 text-sm">
                                        📍
                                    </span>

                                </div>

                            </div>


                            {{-- =================================================
                                 MANUAL LOCATION FORM
                            ================================================== --}}
                            <div id="manualLocationContainer" class="hidden mt-3">

                                <div class="flex items-center justify-between mb-2">

                                    <div>

                                        <p class="text-xs font-semibold text-gray-700">
                                            Manual Place of Violation
                                        </p>

                                        <p class="text-[10px] text-gray-500 mt-0.5">
                                            Enter the street if GPS is unavailable.
                                        </p>

                                    </div>


                                    <button type="button" id="useGpsButton"
                                        class="text-[10px] font-semibold text-blue-600 hover:text-blue-700">
                                        Use GPS
                                    </button>

                                </div>


                                {{-- STREET --}}
                                <div>

                                    <label for="manualStreet" class="text-xs font-semibold text-gray-600">
                                        Street / Place of Violation
                                        <span class="text-red-500">*</span>
                                    </label>

                                    <input type="text" id="manualStreet" placeholder="e.g. Macabulos Drive"
                                        class="w-full mt-1 rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 text-sm py-2.5">

                                </div>


                                {{-- CITY --}}
                                <div class="mt-2.5">

                                    <label for="manualCity" class="text-xs font-semibold text-gray-600">
                                        City
                                    </label>

                                    <input type="text" id="manualCity" value="Tarlac City" readonly
                                        class="w-full mt-1 rounded-lg bg-gray-100 border-gray-200 text-sm py-2.5 text-gray-600">

                                </div>

                            </div>


                            {{-- VALIDATION MESSAGE --}}
                            <p id="locationValidationMessage" class="hidden text-[10px] text-red-600 mt-1">
                                Violation location is required. Please allow GPS/location access or enter the place of
                                violation manually.
                            </p>


                            {{-- EXISTING GPS COORDINATES --}}
                            <input type="hidden" name="latitude" id="latitude" value="{{ old('latitude') }}">

                            <input type="hidden" name="longitude" id="longitude" value="{{ old('longitude') }}">


                            <div id="gpsCoordinates"
                                class="hidden mt-2 bg-blue-50 border border-blue-100 rounded-lg px-3 py-2">

                                <p class="text-[10px] text-blue-700">
                                    GPS coordinates captured successfully.
                                </p>

                            </div>

                        </div>


                        {{-- =================================================
                             DATE AND TIME
                        ================================================== --}}
                        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4">

                            <div class="flex items-center gap-2.5 mb-3">

                                <div
                                    class="w-9 h-9 rounded-xl bg-blue-50 flex items-center justify-center text-lg flex-shrink-0">
                                    📅
                                </div>

                                <div class="min-w-0">

                                    <h2 class="font-bold text-gray-800 text-sm">
                                        Citation Date & Time
                                    </h2>

                                    <p class="text-[10px] text-gray-500 mt-0.5">
                                        Automatically recorded by the system.
                                    </p>

                                </div>

                            </div>


                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5">

                                <div>

                                    <label class="text-xs font-semibold text-gray-600">
                                        Date
                                    </label>

                                    <input type="text" readonly
                                        value="{{ now()->setTimezone('Asia/Manila')->format('F d, Y') }}"
                                        class="w-full mt-1 rounded-lg bg-gray-100 border-gray-200 text-sm py-2.5">

                                </div>


                                <div>

                                    <label class="text-xs font-semibold text-gray-600">
                                        Time
                                    </label>

                                    <input type="text" readonly
                                        value="{{ now()->setTimezone('Asia/Manila')->format('h:i A') }}"
                                        class="w-full mt-1 rounded-lg bg-gray-100 border-gray-200 text-sm py-2.5">

                                </div>

                            </div>

                        </div>

                    </div>

                </section>


                {{-- =================================================
                     SUBMIT
                ================================================== --}}
                <section class="pb-5">

                    <button type="submit" id="submitCitationButton"
                        class="w-full bg-[#005FBF] hover:bg-[#004F9F] active:scale-[0.99] text-white rounded-xl py-3.5 px-5 font-bold text-sm shadow-md transition flex items-center justify-center gap-2">

                        <span class="text-base">
                            ✓
                        </span>

                        <span>
                            Review & Submit Citation
                        </span>

                    </button>

                    <p class="text-center text-[10px] text-gray-400 mt-2">
                        Review all information and evidence before submitting.
                    </p>

                </section>

            </form>

        </main>

    </div>


    {{-- ===========================================================
         ADD MORE VIOLATION UI
    ============================================================ --}}

    <script>
        document.addEventListener('DOMContentLoaded', function() {

            const addViolationButton =
                document.getElementById('addViolationButton');

            const violationRows =
                document.getElementById('violationRows');

            const additionalViolationTemplate =
                document.getElementById('additionalViolationTemplate');


            if (
                !addViolationButton ||
                !violationRows ||
                !additionalViolationTemplate
            ) {
                return;
            }


            function updateViolationNumbers() {

                const rows =
                    violationRows.querySelectorAll('.violation-row');

                rows.forEach(function(row, index) {

                    const numberLabel =
                        row.querySelector('.violation-number');

                    if (numberLabel) {

                        numberLabel.textContent =
                            'Violation ' + (index + 1);

                    }

                });

            }


            function setupAdditionalViolation(row) {

                const select =
                    row.querySelector('.additional-violation-select');

                const otherContainer =
                    row.querySelector('.additional-other-violation-container');

                const otherInput =
                    row.querySelector('.additional-other-violation');

                const removeButton =
                    row.querySelector('.remove-violation-button');


                if (select) {

                    select.addEventListener('change', function() {

                        if (this.value === 'other') {

                            if (otherContainer) {
                                otherContainer.classList.remove('hidden');
                            }

                            if (otherInput) {
                                otherInput.required = true;
                            }

                        } else {

                            if (otherContainer) {
                                otherContainer.classList.add('hidden');
                            }

                            if (otherInput) {

                                otherInput.required = false;
                                otherInput.value = '';

                            }

                        }

                    });

                }


                if (removeButton) {

                    removeButton.addEventListener('click', function() {

                        row.remove();

                        updateViolationNumbers();

                    });

                }

            }


            addViolationButton.addEventListener('click', function() {

                const clone =
                    additionalViolationTemplate.content.cloneNode(true);

                const newRow =
                    clone.querySelector('.additional-violation-row');

                violationRows.appendChild(clone);

                if (newRow) {
                    setupAdditionalViolation(newRow);
                }

                updateViolationNumbers();

            });


            // Restore primary "Other" state after validation failure.
            const primaryViolation =
                document.getElementById('violation_type_id');

            const primaryOtherContainer =
                document.getElementById('otherViolationContainer');

            const primaryOtherInput =
                document.getElementById('other_violation');


            if (
                primaryViolation &&
                primaryOtherContainer &&
                primaryOtherInput
            ) {

                if (primaryViolation.value === 'other') {

                    primaryOtherContainer.classList.remove('hidden');

                    primaryOtherInput.required = true;

                }


                primaryViolation.addEventListener('change', function() {

                    if (this.value === 'other') {

                        primaryOtherContainer.classList.remove('hidden');

                        primaryOtherInput.required = true;

                    } else {

                        primaryOtherContainer.classList.add('hidden');

                        primaryOtherInput.required = false;

                        primaryOtherInput.value = '';

                    }

                });

            }

        });
    </script>


    {{-- ===========================================================
         EVIDENCE PHOTO PREVIEW
    ============================================================ --}}

    <script>
        document.addEventListener('DOMContentLoaded', function() {

            const evidenceInput =
                document.getElementById('evidence_images');

            const evidencePreviewContainer =
                document.getElementById('evidencePreviewContainer');

            const evidencePreview =
                document.getElementById('evidencePreview');

            const evidenceCount =
                document.getElementById('evidenceCount');


            if (!evidenceInput) {
                return;
            }


            /*
            |--------------------------------------------------------------------------
            | MASTER FILE LIST
            |--------------------------------------------------------------------------
            */

            let evidenceFiles = [];


            /*
            |--------------------------------------------------------------------------
            | UPDATE REAL FILE INPUT
            |--------------------------------------------------------------------------
            */

            function syncFileInput() {

                const dataTransfer =
                    new DataTransfer();

                evidenceFiles.forEach(function(file) {
                    dataTransfer.items.add(file);
                });

                evidenceInput.files =
                    dataTransfer.files;
            }


            /*
            |--------------------------------------------------------------------------
            | CREATE UNIQUE FILE KEY
            |--------------------------------------------------------------------------
            */

            function getFileKey(file) {

                return [
                    file.name,
                    file.size,
                    file.lastModified,
                    file.type
                ].join('|');

            }


            /*
            |--------------------------------------------------------------------------
            | CHECK IF FILE ALREADY EXISTS
            |--------------------------------------------------------------------------
            */

            function fileExists(file) {

                const newFileKey =
                    getFileKey(file);

                return evidenceFiles.some(function(existingFile) {

                    return getFileKey(existingFile) === newFileKey;

                });

            }


            /*
            |--------------------------------------------------------------------------
            | RENDER PREVIEWS
            |--------------------------------------------------------------------------
            */

            function renderEvidencePreviews() {

                evidencePreview.innerHTML = '';


                if (evidenceFiles.length === 0) {

                    evidencePreviewContainer.classList.add('hidden');

                    evidenceCount.textContent =
                        '0 photos';

                    return;
                }


                evidencePreviewContainer.classList.remove('hidden');


                evidenceCount.textContent =
                    evidenceFiles.length +
                    (
                        evidenceFiles.length === 1 ?
                        ' photo' :
                        ' photos'
                    );


                evidenceFiles.forEach(function(file, index) {

                    const wrapper =
                        document.createElement('div');

                    wrapper.className =
                        'relative rounded-xl overflow-hidden border border-gray-200 bg-white shadow-sm';


                    /*
                    | IMAGE
                    */
                    const image =
                        document.createElement('img');

                    const previewUrl =
                        URL.createObjectURL(file);

                    image.src =
                        previewUrl;

                    image.alt =
                        'Evidence photo ' + (index + 1);

                    image.className =
                        'w-full h-32 object-cover';


                    /*
                    | DELETE BUTTON
                    */
                    const removeButton =
                        document.createElement('button');

                    removeButton.type =
                        'button';

                    removeButton.innerHTML =
                        '&times;';

                    removeButton.title =
                        'Remove this photo';

                    removeButton.setAttribute(
                        'aria-label',
                        'Remove evidence photo ' + (index + 1)
                    );

                    removeButton.className =
                        'absolute top-2 right-2 w-8 h-8 rounded-full bg-black/70 hover:bg-red-600 text-white flex items-center justify-center text-lg font-bold leading-none shadow-md transition';


                    /*
                    | REMOVE PHOTO
                    */
                    removeButton.addEventListener(
                        'click',
                        function(event) {

                            event.preventDefault();

                            event.stopPropagation();


                            evidenceFiles.splice(
                                index,
                                1
                            );


                            syncFileInput();

                            renderEvidencePreviews();

                        }
                    );


                    /*
                    | FILE NAME
                    */
                    const fileName =
                        document.createElement('div');

                    fileName.className =
                        'px-2.5 py-1.5 bg-white text-[10px] text-gray-600 truncate';

                    fileName.textContent =
                        file.name;


                    /*
                    | BUILD CARD
                    */
                    wrapper.appendChild(image);

                    wrapper.appendChild(removeButton);

                    wrapper.appendChild(fileName);

                    evidencePreview.appendChild(wrapper);

                });

            }


            /*
            |--------------------------------------------------------------------------
            | NEW FILE SELECTION
            |--------------------------------------------------------------------------
            */

            evidenceInput.addEventListener(
                'change',
                function() {

                    const newlySelectedFiles =
                        Array.from(
                            evidenceInput.files || []
                        );


                    newlySelectedFiles.forEach(
                        function(file) {

                            if (
                                !file.type ||
                                !file.type.startsWith('image/')
                            ) {
                                return;
                            }


                            if (fileExists(file)) {
                                return;
                            }


                            evidenceFiles.push(file);

                        }
                    );


                    evidenceInput.value = '';

                    syncFileInput();

                    renderEvidencePreviews();

                }
            );

        });
    </script>


    {{-- ===========================================================
         MANUAL LOCATION TOGGLE
    ============================================================ --}}

    <script>
        document.addEventListener('DOMContentLoaded', function() {

            const manualLocationButton =
                document.getElementById('manualLocationButton');

            const useGpsButton =
                document.getElementById('useGpsButton');

            const manualLocationContainer =
                document.getElementById('manualLocationContainer');

            const gpsLocationContainer =
                document.getElementById('gpsLocationContainer');

            const manualStreet =
                document.getElementById('manualStreet');

            const manualCity =
                document.getElementById('manualCity');

            const locationInput =
                document.getElementById('location');

            const locationValidationMessage =
                document.getElementById('locationValidationMessage');

            const gpsStatusBadge =
                document.getElementById('gpsStatusBadge');

            const latitude =
                document.getElementById('latitude');

            const longitude =
                document.getElementById('longitude');

            const form =
                document.getElementById('issueTicketForm');


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


            /*
            |--------------------------------------------------------------------------
            | ENTER MANUAL LOCATION
            |--------------------------------------------------------------------------
            */

            manualLocationButton.addEventListener(
                'click',
                function() {

                    gpsLocationContainer.classList.add('hidden');

                    manualLocationContainer.classList.remove('hidden');

                    manualLocationButton.classList.add('hidden');


                    if (gpsStatusBadge) {

                        gpsStatusBadge.textContent =
                            'Manual';

                        gpsStatusBadge.className =
                            'text-[10px] font-semibold px-2 py-1 rounded-full bg-blue-50 text-blue-700 shrink-0';

                    }


                    if (locationValidationMessage) {

                        locationValidationMessage.classList.add(
                            'hidden'
                        );

                    }


                    manualStreet.focus();

                }
            );


            /*
            |--------------------------------------------------------------------------
            | USE GPS AGAIN
            |--------------------------------------------------------------------------
            */

            useGpsButton.addEventListener(
                'click',
                function() {

                    manualLocationContainer.classList.add('hidden');

                    gpsLocationContainer.classList.remove('hidden');

                    manualLocationButton.classList.remove('hidden');


                    /*
                    | Clear manual street.
                    */
                    manualStreet.value = '';


                    /*
                    | Clear coordinates so the
                    | existing GPS logic can recapture them.
                    */
                    if (latitude) {
                        latitude.value = '';
                    }

                    if (longitude) {
                        longitude.value = '';
                    }


                    /*
                    | Clear location so the existing
                    | GPS script can populate it again.
                    */
                    locationInput.value = '';

                    locationInput.placeholder =
                        'Detecting current location...';

                    locationInput.readOnly =
                        true;


                    if (gpsStatusBadge) {

                        gpsStatusBadge.textContent =
                            'Detecting';

                        gpsStatusBadge.className =
                            'text-[10px] font-semibold px-2 py-1 rounded-full bg-yellow-50 text-yellow-700 shrink-0';

                    }

                }
            );


            /*
            |--------------------------------------------------------------------------
            | UPDATE ACTUAL LOCATION FIELD
            |--------------------------------------------------------------------------
            |
            | Manual input is stored in the existing
            | "location" field.
            |
            | Example:
            |
            | Macabulos Drive, Tarlac City
            |
            */

            manualStreet.addEventListener(
                'input',
                function() {

                    const street =
                        manualStreet.value.trim();

                    const city =
                        manualCity.value.trim();


                    if (street) {

                        locationInput.value =
                            street + ', ' + city;

                    } else {

                        locationInput.value = '';

                    }

                }
            );


            /*
            |--------------------------------------------------------------------------
            | VALIDATE MANUAL LOCATION BEFORE SUBMIT
            |--------------------------------------------------------------------------
            */

            if (form) {

                form.addEventListener(
                    'submit',
                    function(event) {

                        /*
                        | Only validate the street
                        | when manual mode is active.
                        */
                        if (
                            !manualLocationContainer.classList.contains(
                                'hidden'
                            )
                        ) {

                            const street =
                                manualStreet.value.trim();


                            if (!street) {

                                event.preventDefault();

                                manualStreet.focus();

                                locationValidationMessage.classList.remove(
                                    'hidden'
                                );

                                return;

                            }


                            /*
                            | Ensure the actual submitted
                            | location field contains:
                            |
                            | Street, Tarlac City
                            */
                            locationInput.value =
                                street +
                                ', ' +
                                manualCity.value.trim();

                        }

                    }
                );

            }

        });
    </script>

    {{-- =========================================================
         NETWORK STATUS NOTIFICATION
    ========================================================== --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            const notification =
                document.getElementById('networkStatusNotification');

            const dot =
                document.getElementById('networkStatusDot');

            const title =
                document.getElementById('networkStatusTitle');

            const message =
                document.getElementById('networkStatusMessage');

            const icon =
                document.getElementById('networkStatusIcon');


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

                    /*
                    |--------------------------------------------------------------------------
                    | ONLINE
                    |--------------------------------------------------------------------------
                    */

                    notification.className =
                        'mt-2.5 flex items-center justify-between gap-3 rounded-lg border border-green-200 bg-green-50 px-3 py-2';

                    dot.className =
                        'w-2 h-2 rounded-full bg-green-500 flex-shrink-0';

                    title.className =
                        'text-[11px] font-semibold text-green-700';

                    title.textContent =
                        'Online';

                    message.className =
                        'text-[10px] text-green-600 truncate';

                    message.textContent =
                        'Internet connection is available.';

                    icon.className =
                        'text-sm text-green-600 flex-shrink-0';

                    icon.textContent =
                        '✓';

                } else {

                    /*
                    |--------------------------------------------------------------------------
                    | OFFLINE
                    |--------------------------------------------------------------------------
                    */

                    notification.className =
                        'mt-2.5 flex items-center justify-between gap-3 rounded-lg border border-orange-200 bg-orange-50 px-3 py-2';

                    dot.className =
                        'w-2 h-2 rounded-full bg-orange-500 flex-shrink-0';

                    title.className =
                        'text-[11px] font-semibold text-orange-700';

                    title.textContent =
                        'Offline';

                    message.className =
                        'text-[10px] text-orange-600 truncate';

                    message.textContent =
                        'No internet connection. You can continue working offline.';

                    icon.className =
                        'text-sm text-orange-600 flex-shrink-0';

                    icon.textContent =
                        '⚠';

                }

            }


            /*
            |--------------------------------------------------------------------------
            | CHECK INITIAL STATUS
            |--------------------------------------------------------------------------
            */

            updateNetworkStatus();


            /*
            |--------------------------------------------------------------------------
            | DETECT CONNECTION CHANGES
            |--------------------------------------------------------------------------
            */

            window.addEventListener('online', function() {
                updateNetworkStatus();
            });


            window.addEventListener('offline', function() {
                updateNetworkStatus();
            });

        });
    </script>


    @vite('resources/js/enforcer/issue-ticket.js')

@endsection
