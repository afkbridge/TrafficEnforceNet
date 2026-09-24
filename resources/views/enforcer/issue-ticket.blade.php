@extends('layouts.enforcer')

@section('title', 'Issue Traffic Citation')

@section('content')

<div class="min-h-screen bg-[#F5F7FB] pb-28">

    {{-- =========================================================
         TOP HEADER
    ========================================================== --}}
    <header class="sticky top-0 z-50 bg-gradient-to-br from-[#1D5FBF] to-[#2E77E6] text-white shadow-md">

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 sm:py-5">

            <div class="flex items-center gap-3">

                <a
                    href="{{ route('enforcer.dashboard') }}"
                    class="w-10 h-10 rounded-xl bg-white/15 hover:bg-white/25 flex items-center justify-center text-xl transition flex-shrink-0"
                    aria-label="Back to dashboard"
                >
                    ←
                </a>

                <div class="min-w-0">
                    <h1 class="text-lg sm:text-xl font-bold leading-tight">
                        Issue Traffic Citation
                    </h1>

                    <p class="text-blue-100 text-xs sm:text-sm mt-1">
                        Record a traffic violation and capture the required evidence.
                    </p>
                </div>

            </div>

        </div>

    </header>


    {{-- =========================================================
         PAGE CONTENT
    ========================================================== --}}
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">


        {{-- =====================================================
             SERVER VALIDATION ERRORS
        ====================================================== --}}
        @if ($errors->any())

            <div class="mt-5 bg-red-50 border border-red-200 text-red-700 rounded-2xl p-4 shadow-sm">

                <div class="flex items-start gap-3">

                    <div class="w-9 h-9 rounded-full bg-red-100 flex items-center justify-center flex-shrink-0">
                        ⚠️
                    </div>

                    <div class="min-w-0">

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


        {{-- =====================================================
             OFFLINE SYNC STATUS
        ====================================================== --}}
        <div
            id="offlineSyncContainer"
            class="pt-5 space-y-3"
        >

            {{-- PENDING OFFLINE TICKETS --}}
            <div
                id="offlinePendingBox"
                class="hidden rounded-2xl border border-orange-200 bg-orange-50 p-4"
            >

                <div class="flex items-start justify-between gap-3">

                    <div class="flex items-start gap-3 min-w-0">

                        <div class="text-orange-500 text-xl flex-shrink-0">
                            ⚠️
                        </div>

                        <div class="min-w-0">

                            <p
                                id="offlinePendingTitle"
                                class="font-semibold text-orange-800"
                            >
                                Pending Offline Tickets
                            </p>

                            <p
                                id="offlinePendingMessage"
                                class="mt-1 text-sm text-orange-700"
                            >
                                Your ticket is saved on this device.
                            </p>

                        </div>

                    </div>

                    <button
                        type="button"
                        id="syncNowButton"
                        class="hidden shrink-0 rounded-lg bg-orange-500 px-3 py-2 text-sm font-semibold text-white hover:bg-orange-600 transition"
                    >
                        Sync Now
                    </button>

                </div>

            </div>


            {{-- SYNCING --}}
            <div
                id="offlineSyncingBox"
                class="hidden rounded-2xl border border-blue-200 bg-blue-50 p-4"
            >

                <div class="flex items-center gap-3">

                    <div class="text-blue-500 text-xl flex-shrink-0">
                        🔄
                    </div>

                    <div class="min-w-0">

                        <p class="font-semibold text-blue-800">
                            Syncing Tickets
                        </p>

                        <p
                            id="offlineSyncingMessage"
                            class="mt-1 text-sm text-blue-700"
                        >
                            Please wait while your pending tickets are uploaded.
                        </p>

                    </div>

                </div>

            </div>


            {{-- SUCCESS --}}
            <div
                id="offlineSuccessBox"
                class="hidden rounded-2xl border border-green-200 bg-green-50 p-4"
            >

                <div class="flex items-center gap-3">

                    <div class="text-green-500 text-xl flex-shrink-0">
                        ✓
                    </div>

                    <div class="min-w-0">

                        <p class="font-semibold text-green-800">
                            Tickets Synced Successfully
                        </p>

                        <p
                            id="offlineSuccessMessage"
                            class="mt-1 text-sm text-green-700"
                        >
                            All pending tickets have been uploaded.
                        </p>

                    </div>

                </div>

            </div>


            {{-- ERROR --}}
            <div
                id="offlineErrorBox"
                class="hidden rounded-2xl border border-red-200 bg-red-50 p-4"
            >

                <div class="flex items-start gap-3">

                    <div class="text-red-500 text-xl flex-shrink-0">
                        ⚠️
                    </div>

                    <div class="min-w-0">

                        <p class="font-semibold text-red-800">
                            Sync Failed
                        </p>

                        <p
                            id="offlineErrorMessage"
                            class="mt-1 text-sm text-red-700"
                        >
                            Some tickets could not be synchronized.
                        </p>

                    </div>

                </div>

            </div>

        </div>


        {{-- =====================================================
             MAIN FORM
        ====================================================== --}}
        <form
            id="issueTicketForm"
            action="{{ route('enforcer.violations.store') }}"
            method="POST"
            enctype="multipart/form-data"
            class="space-y-5 pt-5"
        >

            @csrf


            {{-- =================================================
                 DOCUMENT SCANNERS
            ================================================== --}}
            <section>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">


                    {{-- DRIVER'S LICENSE --}}
                    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-5">

                        <div class="flex items-start gap-3 mb-5">

                            <div class="w-11 h-11 rounded-2xl bg-blue-50 flex items-center justify-center text-xl flex-shrink-0">
                                🪪
                            </div>

                            <div class="min-w-0">

                                <h2 class="font-bold text-gray-800">
                                    Driver's License
                                </h2>

                                <p class="text-xs text-gray-500 mt-1 leading-relaxed">
                                    Capture or upload the driver's license to automatically extract driver information.
                                </p>

                            </div>

                        </div>


                        {{-- LICENSE PREVIEW --}}
                        <div
                            id="licensePreviewContainer"
                            class="hidden mb-4"
                        >

                            <div class="relative">

                                <img
                                    id="licensePreview"
                                    class="w-full h-48 object-cover rounded-2xl border border-gray-200"
                                    alt="Driver's license preview"
                                >

                                <div class="absolute top-3 right-3 bg-black/60 text-white text-xs px-3 py-1.5 rounded-full">
                                    License Preview
                                </div>

                            </div>

                        </div>


                        {{-- CAMERA / UPLOAD --}}
                        <div class="grid grid-cols-2 gap-3">

                            <button
                                type="button"
                                onclick="openLicenseCamera()"
                                class="bg-[#005FBF] hover:bg-[#004F9F] active:scale-[0.98] text-white rounded-2xl py-3 px-2 font-bold flex flex-col items-center justify-center transition"
                            >

                                <span class="text-2xl">
                                    📷
                                </span>

                                <span class="mt-1.5 text-xs sm:text-sm">
                                    Take Photo
                                </span>

                            </button>


                            <button
                                type="button"
                                onclick="openLicenseFile()"
                                class="bg-blue-50 hover:bg-blue-100 active:scale-[0.98] text-blue-700 rounded-2xl py-3 px-2 font-bold flex flex-col items-center justify-center border border-blue-200 transition"
                            >

                                <span class="text-2xl">
                                    📁
                                </span>

                                <span class="mt-1.5 text-xs sm:text-sm">
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
                            onchange="processDriverLicense(event)"
                        >

                    </div>


                    {{-- CITATION TICKET --}}
                    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-5">

                        <div class="flex items-start gap-3 mb-5">

                            <div class="w-11 h-11 rounded-2xl bg-red-50 flex items-center justify-center text-xl flex-shrink-0">
                                🎫
                            </div>

                            <div class="min-w-0">

                                <h2 class="font-bold text-gray-800">
                                    Citation Ticket
                                </h2>

                                <p class="text-xs text-gray-500 mt-1 leading-relaxed">
                                    Capture or upload the citation ticket to automatically extract ticket and vehicle information.
                                </p>

                            </div>

                        </div>


                        {{-- TICKET PREVIEW --}}
                        <div
                            id="ticketPreviewContainer"
                            class="hidden mb-4"
                        >

                            <div class="relative">

                                <img
                                    id="ticketPreview"
                                    class="w-full h-48 object-cover rounded-2xl border border-gray-200"
                                    alt="Citation ticket preview"
                                >

                                <div class="absolute top-3 right-3 bg-black/60 text-white text-xs px-3 py-1.5 rounded-full">
                                    Ticket Preview
                                </div>

                            </div>

                        </div>


                        {{-- CAMERA / UPLOAD --}}
                        <div class="grid grid-cols-2 gap-3">

                            <button
                                type="button"
                                onclick="openTicketCamera()"
                                class="bg-[#005FBF] hover:bg-[#004F9F] active:scale-[0.98] text-white rounded-2xl py-3 px-2 font-bold flex flex-col items-center justify-center transition"
                            >

                                <span class="text-2xl">
                                    📷
                                </span>

                                <span class="mt-1.5 text-xs sm:text-sm">
                                    Take Photo
                                </span>

                            </button>


                            <button
                                type="button"
                                onclick="openTicketFile()"
                                class="bg-blue-50 hover:bg-blue-100 active:scale-[0.98] text-blue-700 rounded-2xl py-3 px-2 font-bold flex flex-col items-center justify-center border border-blue-200 transition"
                            >

                                <span class="text-2xl">
                                    📁
                                </span>

                                <span class="mt-1.5 text-xs sm:text-sm">
                                    Upload File
                                </span>

                            </button>

                        </div>


                        <input
                            type="file"
                            id="ticket_image"
                            name="ticket_image"
                            accept="image/*"
                            capture="environment"
                            class="hidden"
                            onchange="previewTicket(event)"
                        >

                    </div>

                </div>

            </section>


            {{-- =================================================
                 OCR STATUS
            ================================================== --}}
            <section>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">


                    {{-- CITATION OCR --}}
                    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-5">

                        <div class="flex items-center justify-between gap-3 mb-4">

                            <h2 class="font-bold text-gray-800">
                                🤖 Citation Ticket OCR Status
                            </h2>

                            <span
                                id="ticketOcrBadge"
                                class="text-xs font-medium px-2.5 py-1 rounded-full bg-gray-100 text-gray-500 shrink-0"
                            >
                                Ready
                            </span>

                        </div>


                        <div
                            id="ticketOcrStatus"
                            class="flex items-center gap-3 bg-gray-50 border border-gray-100 rounded-2xl p-4"
                        >

                            <div
                                id="ticketOcrIcon"
                                class="w-11 h-11 rounded-full bg-blue-100 flex items-center justify-center text-lg flex-shrink-0"
                            >
                                🎫
                            </div>

                            <div class="min-w-0">

                                <p
                                    id="ticketOcrTitle"
                                    class="font-semibold text-gray-700 text-sm"
                                >
                                    Ready for scanning
                                </p>

                                <p
                                    id="ticketOcrMessage"
                                    class="text-xs text-gray-500 mt-1 leading-relaxed"
                                >
                                    Upload a clear citation ticket image to begin OCR.
                                </p>

                            </div>

                        </div>

                    </div>


                    {{-- LICENSE OCR --}}
                    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-5">

                        <div class="flex items-center justify-between gap-3 mb-4">

                            <h2 class="font-bold text-gray-800">
                                🪪 Driver's License OCR Status
                            </h2>

                            <span
                                id="ocrBadge"
                                class="text-xs font-medium px-2.5 py-1 rounded-full bg-gray-100 text-gray-500 shrink-0"
                            >
                                Ready
                            </span>

                        </div>


                        <div
                            id="ocrStatus"
                            class="flex items-center gap-3 bg-gray-50 border border-gray-100 rounded-2xl p-4"
                        >

                            <div
                                id="ocrIcon"
                                class="w-11 h-11 rounded-full bg-blue-100 flex items-center justify-center text-lg flex-shrink-0"
                            >
                                🪪
                            </div>

                            <div class="min-w-0">

                                <p
                                    id="ocrTitle"
                                    class="font-semibold text-gray-700 text-sm"
                                >
                                    Ready for scanning
                                </p>

                                <p
                                    id="ocrMessage"
                                    class="text-xs text-gray-500 mt-1 leading-relaxed"
                                >
                                    Upload a clear driver's license image to begin OCR.
                                </p>

                            </div>

                        </div>

                    </div>

                </div>

            </section>


            {{-- =================================================
                 CITATION TICKET NUMBER
            ================================================== --}}
            <section>

                <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-5">

                    <div class="flex items-center gap-3 mb-4">

                        <div class="w-11 h-11 rounded-2xl bg-red-50 flex items-center justify-center text-xl flex-shrink-0">
                            🎫
                        </div>

                        <div class="min-w-0">

                            <h2 class="font-bold text-gray-800">
                                Citation Ticket Number
                            </h2>

                            <p class="text-xs text-gray-500 mt-1">
                                Automatically extracted from the citation ticket.
                            </p>

                        </div>

                    </div>


                    <input
                        type="text"
                        id="ticket_number"
                        name="ticket_number"
                        value="{{ old('ticket_number') }}"
                        placeholder="OCR detected ticket number"
                        class="w-full rounded-xl bg-blue-50 border-blue-200 focus:border-blue-500 focus:ring-blue-500 text-sm"
                    >

                    <p class="text-xs text-blue-600 mt-1.5">
                        🤖 OCR detected • Editable
                    </p>

                </div>

            </section>


            {{-- =================================================
                 DRIVER INFORMATION
            ================================================== --}}
            <section>

                <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-5">

                    <div class="flex items-center gap-3 mb-5">

                        <div class="w-11 h-11 rounded-2xl bg-blue-50 flex items-center justify-center text-xl flex-shrink-0">
                            👤
                        </div>

                        <div class="min-w-0">

                            <h2 class="font-bold text-gray-800">
                                Driver Information
                            </h2>

                            <p class="text-xs text-gray-500 mt-1">
                                Automatically extracted from the driver's license or citation ticket.
                                Review and edit before submitting.
                            </p>

                        </div>

                    </div>


                    <div class="space-y-4">


                        {{-- FIRST / MIDDLE NAME --}}
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                            <div>

                                <label
                                    for="first_name"
                                    class="text-xs font-semibold text-gray-600"
                                >
                                    First Name
                                    <span class="text-red-500">*</span>
                                </label>

                                <input
                                    type="text"
                                    id="first_name"
                                    name="first_name"
                                    value="{{ old('first_name') }}"
                                    placeholder="OCR detected first name"
                                    required
                                    class="w-full mt-1.5 rounded-xl bg-blue-50 border-blue-200 focus:border-blue-500 focus:ring-blue-500 text-sm"
                                >

                                <p class="text-xs text-blue-600 mt-1.5">
                                    🤖 OCR detected • Editable
                                </p>

                            </div>


                            <div>

                                <label
                                    for="middle_name"
                                    class="text-xs font-semibold text-gray-600"
                                >
                                    Middle Name
                                    <span class="text-red-500">*</span>
                                </label>

                                <input
                                    type="text"
                                    id="middle_name"
                                    name="middle_name"
                                    value="{{ old('middle_name') }}"
                                    placeholder="OCR detected middle name"
                                    required
                                    class="w-full mt-1.5 rounded-xl bg-blue-50 border-blue-200 focus:border-blue-500 focus:ring-blue-500 text-sm"
                                >

                                <p class="text-xs text-blue-600 mt-1.5">
                                    🤖 OCR detected • Editable
                                </p>

                            </div>

                        </div>


                        {{-- LAST NAME / LICENSE --}}
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                            <div>

                                <label
                                    for="last_name"
                                    class="text-xs font-semibold text-gray-600"
                                >
                                    Last Name
                                    <span class="text-red-500">*</span>
                                </label>

                                <input
                                    type="text"
                                    id="last_name"
                                    name="last_name"
                                    value="{{ old('last_name') }}"
                                    placeholder="OCR detected last name"
                                    required
                                    class="w-full mt-1.5 rounded-xl bg-blue-50 border-blue-200 focus:border-blue-500 focus:ring-blue-500 text-sm"
                                >

                                <p class="text-xs text-blue-600 mt-1.5">
                                    🤖 OCR detected • Editable
                                </p>

                            </div>


                            <div>

                                <label
                                    for="license_number"
                                    class="text-xs font-semibold text-gray-600"
                                >
                                    License Number
                                    <span class="text-red-500">*</span>
                                </label>

                                <input
                                    type="text"
                                    id="license_number"
                                    name="license_number"
                                    value="{{ old('license_number') }}"
                                    placeholder="OCR detected license number"
                                    required
                                    class="w-full mt-1.5 rounded-xl bg-blue-50 border-blue-200 focus:border-blue-500 focus:ring-blue-500 text-sm"
                                >

                                <p class="text-xs text-blue-600 mt-1.5">
                                    🤖 OCR detected • Editable
                                </p>

                            </div>

                        </div>


                        {{-- ADDRESS --}}
                        <div>

                            <label
                                for="address"
                                class="text-xs font-semibold text-gray-600"
                            >
                                Address
                                <span class="text-red-500">*</span>
                            </label>

                            <input
                                type="text"
                                id="address"
                                name="address"
                                value="{{ old('address') }}"
                                placeholder="OCR detected address"
                                required
                                class="w-full mt-1.5 rounded-xl bg-blue-50 border-blue-200 focus:border-blue-500 focus:ring-blue-500 text-sm"
                            >

                            <p class="text-xs text-blue-600 mt-1.5">
                                🤖 OCR detected • Editable
                            </p>

                        </div>


                        {{-- BIRTH DATE --}}
                        <div class="max-w-md">

                            <label
                                for="birth_date"
                                class="text-xs font-semibold text-gray-600"
                            >
                                Birth Date
                                <span class="text-red-500">*</span>
                            </label>

                            <input
                                type="date"
                                id="birth_date"
                                name="birth_date"
                                value="{{ old('birth_date') }}"
                                required
                                class="w-full mt-1.5 rounded-xl border-gray-300 focus:border-blue-500 focus:ring-blue-500 text-sm"
                            >

                            <p class="text-xs text-blue-600 mt-1.5">
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

                <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-5">

                    <div class="flex items-center gap-3 mb-5">

                        <div class="w-11 h-11 rounded-2xl bg-blue-50 flex items-center justify-center text-xl flex-shrink-0">
                            🚗
                        </div>

                        <div class="min-w-0">

                            <h2 class="font-bold text-gray-800">
                                Vehicle Information
                            </h2>

                            <p class="text-xs text-gray-500 mt-1">
                                Automatically extracted from the citation ticket.
                                Review and edit before submitting.
                            </p>

                        </div>

                    </div>


                    <div class="space-y-4">


                        {{-- PLATE / VEHICLE TYPE --}}
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                            <div>

                                <label
                                    for="plate_number"
                                    class="text-xs font-semibold text-gray-600"
                                >
                                    Plate Number
                                    <span class="text-red-500">*</span>
                                </label>

                                <input
                                    type="text"
                                    id="plate_number"
                                    name="plate_number"
                                    value="{{ old('plate_number') }}"
                                    placeholder="OCR detected plate number"
                                    required
                                    class="w-full mt-1.5 rounded-xl bg-blue-50 border-blue-200 focus:border-blue-500 focus:ring-blue-500 text-sm"
                                >

                                <p class="text-xs text-blue-600 mt-1.5">
                                    🤖 OCR detected • Editable
                                </p>

                            </div>


                            <div>

                                <label
                                    for="vehicle_type"
                                    class="text-xs font-semibold text-gray-600"
                                >
                                    Vehicle Type
                                </label>

                                <input
                                    type="text"
                                    id="vehicle_type"
                                    name="vehicle_type"
                                    value="{{ old('vehicle_type') }}"
                                    placeholder="e.g. Motorcycle, Sedan, SUV"
                                    class="w-full mt-1.5 rounded-xl bg-blue-50 border-blue-200 focus:border-blue-500 focus:ring-blue-500 text-sm"
                                >

                                <p class="text-xs text-blue-600 mt-1.5">
                                    🤖 OCR detected • Editable
                                </p>

                            </div>

                        </div>


                        {{-- REGION / OWNER --}}
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                            <div>

                                <label
                                    for="region_number"
                                    class="text-xs font-semibold text-gray-600"
                                >
                                    Region Number
                                </label>

                                <input
                                    type="text"
                                    id="region_number"
                                    name="region_number"
                                    value="{{ old('region_number') }}"
                                    placeholder="Enter region number"
                                    class="w-full mt-1.5 rounded-xl bg-gray-50 border-gray-300 focus:border-blue-500 focus:ring-blue-500 text-sm"
                                >

                                <p class="text-xs text-gray-500 mt-1.5">
                                    Enter manually if not detected
                                </p>

                            </div>


                            <div>

                                <label
                                    for="owner_name"
                                    class="text-xs font-semibold text-gray-600"
                                >
                                    Vehicle Owner
                                </label>

                                <input
                                    type="text"
                                    id="owner_name"
                                    name="owner_name"
                                    value="{{ old('owner_name') }}"
                                    placeholder="OCR detected vehicle owner"
                                    class="w-full mt-1.5 rounded-xl bg-blue-50 border-blue-200 focus:border-blue-500 focus:ring-blue-500 text-sm"
                                >

                                <p class="text-xs text-blue-600 mt-1.5">
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

                <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-5">

                    <div class="flex items-center gap-3 mb-5">

                        <div class="w-11 h-11 rounded-2xl bg-red-50 flex items-center justify-center text-xl flex-shrink-0">
                            ⚠️
                        </div>

                        <div class="min-w-0">

                            <h2 class="font-bold text-gray-800">
                                Violation Information
                            </h2>

                            <p class="text-xs text-gray-500 mt-1">
                                Specify all traffic violations included in this citation.
                            </p>

                        </div>

                    </div>


                    {{-- VIOLATION ROWS --}}
                    <div
                        id="violationRows"
                        class="space-y-4"
                    >


                        {{-- PRIMARY VIOLATION --}}
                        <div class="violation-row rounded-2xl border border-gray-200 bg-gray-50 p-4">

                            <div class="flex items-center justify-between gap-3 mb-2">

                                <label
                                    for="violation_type_id"
                                    class="text-xs font-semibold text-gray-600"
                                >
                                    Traffic Violation
                                    <span class="text-red-500">*</span>
                                </label>

                                <span class="violation-number text-xs font-semibold text-gray-500">
                                    Violation 1
                                </span>

                            </div>


                            <select
                                name="violation_type_id"
                                id="violation_type_id"
                                required
                                class="w-full rounded-xl bg-blue-50 border-blue-200 focus:border-blue-500 focus:ring-blue-500 text-sm"
                            >

                                <option
                                    value=""
                                    disabled
                                    {{ old('violation_type_id') ? '' : 'selected' }}
                                >
                                    Select violation type
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
                                    {{ old('violation_type_id') == 'other' ? 'selected' : '' }}
                                >
                                    Others
                                </option>

                            </select>


                            {{-- PRIMARY OTHER VIOLATION --}}
                            <div
                                id="otherViolationContainer"
                                class="{{ old('violation_type_id') == 'other' ? '' : 'hidden' }} mt-3"
                            >

                                <label
                                    for="other_violation"
                                    class="text-xs font-semibold text-gray-600"
                                >
                                    Specify Violation
                                    <span class="text-red-500">*</span>
                                </label>

                                <input
                                    type="text"
                                    id="other_violation"
                                    name="other_violation"
                                    value="{{ old('other_violation') }}"
                                    placeholder="Enter violation"
                                    class="w-full mt-1.5 rounded-xl border-gray-300 focus:border-blue-500 focus:ring-blue-500 text-sm"
                                    {{ old('violation_type_id') == 'other' ? 'required' : '' }}
                                >

                            </div>

                        </div>

                    </div>


                    {{-- ADD MORE --}}
                    <button
                        type="button"
                        id="addViolationButton"
                        class="mt-4 inline-flex items-center justify-center gap-2 rounded-xl border border-blue-600 px-4 py-3 text-sm font-semibold text-blue-600 hover:bg-blue-50 transition"
                    >

                        <span class="text-lg leading-none">
                            +
                        </span>

                        <span>
                            Add More Traffic Violation
                        </span>

                    </button>

                    <p class="text-xs text-gray-500 mt-2">
                        Add another violation if more than one traffic offense is included in this citation.
                    </p>


                    {{-- ADDITIONAL VIOLATION TEMPLATE --}}
                    <template id="additionalViolationTemplate">

                        <div class="additional-violation-row rounded-2xl border border-gray-200 bg-gray-50 p-4">

                            <div class="flex items-center justify-between gap-3 mb-2">

                                <div class="violation-number text-xs font-semibold text-gray-500">
                                    Violation
                                </div>

                                <button
                                    type="button"
                                    class="remove-violation-button text-xs font-semibold text-red-600 hover:text-red-700"
                                >
                                    Remove
                                </button>

                            </div>


                            <label class="text-xs font-semibold text-gray-600">

                                Traffic Violation

                                <span class="text-red-500">*</span>

                            </label>


                            <select
                                name="additional_violation_type_ids[]"
                                class="additional-violation-select w-full mt-1.5 rounded-xl bg-blue-50 border-blue-200 focus:border-blue-500 focus:ring-blue-500 text-sm"
                                required
                            >

                                <option
                                    value=""
                                    selected
                                    disabled
                                >
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


                            <div class="additional-other-violation-container hidden mt-3">

                                <label class="text-xs font-semibold text-gray-600">

                                    Specify Violation

                                    <span class="text-red-500">*</span>

                                </label>

                                <input
                                    type="text"
                                    name="additional_other_violation_names[]"
                                    class="additional-other-violation w-full mt-1.5 rounded-xl border-gray-300 focus:border-blue-500 focus:ring-blue-500 text-sm"
                                    placeholder="Enter violation"
                                >

                            </div>

                        </div>

                    </template>


                    {{-- ISSUED BY --}}
                    <div class="mt-5">

                        <label class="text-xs font-semibold text-gray-600">
                            Issued By
                        </label>

                        <div class="relative mt-1.5">

                            <input
                                type="text"
                                readonly
                                value="{{ auth()->user()->name }}"
                                class="w-full rounded-xl bg-gray-100 border-gray-200 text-sm text-gray-600 pr-10"
                            >

                            <span class="absolute right-3 top-1/2 -translate-y-1/2">
                                ✓
                            </span>

                        </div>

                    </div>


                    {{-- REMARKS --}}
                    <div class="mt-4">

                        <label
                            for="remarks"
                            class="text-xs font-semibold text-gray-600"
                        >
                            Remarks
                        </label>

                        <textarea
                            id="remarks"
                            name="remarks"
                            rows="4"
                            placeholder="Enter additional notes"
                            class="w-full mt-1.5 rounded-xl border-gray-300 focus:border-blue-500 focus:ring-blue-500 text-sm"
                        >{{ old('remarks') }}</textarea>

                    </div>

                </div>

            </section>


            {{-- =================================================
                 EVIDENCE PHOTO
            ================================================== --}}
            <section>

                <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-5">

                    <div class="flex items-center gap-3 mb-4">

                        <div class="w-11 h-11 rounded-2xl bg-blue-50 flex items-center justify-center text-xl flex-shrink-0">
                            📸
                        </div>

                        <div class="min-w-0">

                            <h2 class="font-bold text-gray-800">
                                Violation Evidence
                            </h2>

                            <p class="text-xs text-gray-500 mt-1">
                                Capture clear evidence of the violation.
                            </p>

                        </div>

                    </div>


                    <label class="border-2 border-dashed border-gray-300 hover:border-blue-400 hover:bg-blue-50 rounded-3xl min-h-[220px] flex flex-col items-center justify-center cursor-pointer transition p-6 text-center">

                        <div class="w-16 h-16 rounded-2xl bg-blue-50 flex items-center justify-center text-3xl">
                            📷
                        </div>

                        <h3 class="font-bold text-base mt-4 text-gray-800">
                            Capture Evidence Photo
                        </h3>

                        <p class="text-gray-500 text-xs mt-2 max-w-xs leading-relaxed">
                            Take a photo showing the actual traffic violation.
                        </p>

                        <span class="mt-4 inline-flex items-center px-4 py-2 rounded-xl bg-blue-600 text-white text-xs font-semibold">
                            Choose Photo
                        </span>

                        <input
                            type="file"
                            name="evidence_images[]"
                            accept="image/*"
                            capture="environment"
                            multiple
                            class="hidden"
                        >

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

            </section>


            {{-- =================================================
                 GPS LOCATION + DATE/TIME
            ================================================== --}}
            <section>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">


                    {{-- GPS LOCATION --}}
                    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-5">

                        <div class="flex items-center justify-between gap-3 mb-4">

                            <div class="flex items-center gap-3 min-w-0">

                                <div class="w-11 h-11 rounded-2xl bg-blue-50 flex items-center justify-center text-xl flex-shrink-0">
                                    📍
                                </div>

                                <div class="min-w-0">

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
                                class="text-xs font-semibold px-2.5 py-1.5 rounded-full bg-yellow-50 text-yellow-700 shrink-0"
                            >
                                Detecting
                            </div>

                        </div>


                        <div>

                            <label
                                for="location"
                                class="text-xs font-semibold text-gray-600"
                            >
                                Address
                                <span class="text-red-500">*</span>
                            </label>

                            <div class="relative mt-1.5">

                                <input
                                    type="text"
                                    id="location"
                                    name="location"
                                    readonly
                                    value="{{ old('location') }}"
                                    placeholder="Detecting current location..."
                                    class="w-full rounded-xl bg-gray-100 border-gray-200 text-sm pr-10"
                                >

                                <span
                                    id="locationIcon"
                                    class="absolute right-3 top-1/2 -translate-y-1/2"
                                >
                                    📍
                                </span>

                            </div>


                            <p
                                id="locationValidationMessage"
                                class="hidden text-xs text-red-600 mt-1.5"
                            >
                                Violation location is required. Please allow GPS/location access and wait for the location to be captured.
                            </p>

                        </div>


                        <input
                            type="hidden"
                            name="latitude"
                            id="latitude"
                            value="{{ old('latitude') }}"
                        >

                        <input
                            type="hidden"
                            name="longitude"
                            id="longitude"
                            value="{{ old('longitude') }}"
                        >


                        <div
                            id="gpsCoordinates"
                            class="hidden mt-3 bg-blue-50 border border-blue-100 rounded-xl px-3 py-2"
                        >

                            <p class="text-xs text-blue-700">
                                GPS coordinates captured successfully.
                            </p>

                        </div>

                    </div>


                    {{-- DATE AND TIME --}}
                    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-5">

                        <div class="flex items-center gap-3 mb-4">

                            <div class="w-11 h-11 rounded-2xl bg-blue-50 flex items-center justify-center text-xl flex-shrink-0">
                                📅
                            </div>

                            <div class="min-w-0">

                                <h2 class="font-bold text-gray-800">
                                    Citation Date & Time
                                </h2>

                                <p class="text-xs text-gray-500 mt-1">
                                    Automatically recorded by the system.
                                </p>

                            </div>

                        </div>


                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">

                            <div>

                                <label class="text-xs font-semibold text-gray-600">
                                    Date
                                </label>

                                <input
                                    type="text"
                                    readonly
                                    value="{{ now()->setTimezone('Asia/Manila')->format('F d, Y') }}"
                                    class="w-full mt-1.5 rounded-xl bg-gray-100 border-gray-200 text-sm"
                                >

                            </div>


                            <div>

                                <label class="text-xs font-semibold text-gray-600">
                                    Time
                                </label>

                                <input
                                    type="text"
                                    readonly
                                    value="{{ now()->setTimezone('Asia/Manila')->format('h:i A') }}"
                                    class="w-full mt-1.5 rounded-xl bg-gray-100 border-gray-200 text-sm"
                                >

                            </div>

                        </div>

                    </div>

                </div>

            </section>


            {{-- =================================================
                 SUBMIT
            ================================================== --}}
            <section class="pb-6">

                <button
                    type="submit"
                    id="submitCitationButton"
                    class="w-full bg-[#005FBF] hover:bg-[#004F9F] active:scale-[0.99] text-white rounded-2xl py-4 px-5 font-bold text-base shadow-lg transition flex items-center justify-center gap-2"
                >

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

            </section>

        </form>

    </main>

</div>


{{-- ===========================================================
     ADD MORE VIOLATION UI
============================================================ --}}
<script>
document.addEventListener('DOMContentLoaded', function () {

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

        rows.forEach(function (row, index) {

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

            select.addEventListener('change', function () {

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

            removeButton.addEventListener('click', function () {

                row.remove();

                updateViolationNumbers();

            });

        }

    }


    addViolationButton.addEventListener('click', function () {

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


        primaryViolation.addEventListener('change', function () {

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
     JAVASCRIPT
     OCR, OFFLINE SYNC AND GPS
     resources/js/enforcer/issue-ticket.js

     Do not duplicate the OCR JavaScript here.
============================================================ --}}
@vite('resources/js/app.js')

@endsection