@extends('layouts.enforcer')

@section('title', 'Violation Details')

@section('content')

<!-- ========================================================= -->

<!-- HEADER -->

<!-- ========================================================= -->

<div class="bg-gradient-to-br from-[#1D5FBF] to-[#2E77E6] text-white px-5 pt-7 pb-8 rounded-b-[30px] shadow-sm">


<div class="flex items-center gap-3">

    <a href="{{ route('enforcer.violations.index') }}"
       class="w-10 h-10 rounded-xl bg-white/15 hover:bg-white/25 flex items-center justify-center text-xl transition flex-shrink-0"
       aria-label="Back to violations">
        ←
    </a>

    <div class="min-w-0">

        <h1 class="text-xl font-bold leading-tight">
            Violation Details
        </h1>

        <p class="text-blue-100 text-sm mt-1">
            Complete traffic citation information.
        </p>

    </div>

</div>


</div>

<!-- ========================================================= -->

<!-- TICKET SUMMARY -->

<!-- ========================================================= -->

<div class="px-5 -mt-4 relative z-10">


<div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-5">

    <div class="flex items-start justify-between gap-3">

        <div class="min-w-0">

            <p class="text-xs text-gray-500 mb-1">
                Ticket Number
            </p>

            <h2 class="text-xl font-bold text-[#1D5FBF] break-words">
                {{ $violation->ticket_number }}
            </h2>

        </div>


        @if ($violation->status === 'Completed')

            <span class="flex-shrink-0 text-xs font-semibold px-3 py-1.5 rounded-full bg-green-50 text-green-700">
                Completed
            </span>

        @else

            <span class="flex-shrink-0 text-xs font-semibold px-3 py-1.5 rounded-full bg-yellow-50 text-yellow-700">
                {{ $violation->status ?? 'Pending' }}
            </span>

        @endif

    </div>

</div>


</div>

<!-- ========================================================= -->

<!-- VIOLATION INFORMATION -->

<!-- ========================================================= -->

<div class="px-5 mt-5">


<div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">

    <div class="px-5 py-4 border-b border-gray-100">

        <h2 class="text-base font-bold text-gray-800">
            Violation Information
        </h2>

    </div>


    <div class="p-5 space-y-4">

        <!-- Violation Type -->

        <div class="flex items-start gap-3">

            <div class="w-10 h-10 rounded-xl bg-red-50 flex items-center justify-center flex-shrink-0">
                ⚠️
            </div>

            <div class="min-w-0">

                <p class="text-xs text-gray-500">
                    Violation Type
                </p>

                <p class="text-sm font-semibold text-gray-800 mt-1">
                    {{ $violation->violationType?->name ?? 'N/A' }}
                </p>

            </div>

        </div>


        <!-- Date / Time -->

        <div class="grid grid-cols-2 gap-3">

            <div class="bg-gray-50 rounded-xl p-3">

                <p class="text-xs text-gray-500">
                    Date
                </p>

                <p class="text-sm font-semibold text-gray-700 mt-1">

                    @if ($violation->violation_date)

                        {{ \Carbon\Carbon::parse($violation->violation_date)->format('M d, Y') }}

                    @else

                        N/A

                    @endif

                </p>

            </div>


            <div class="bg-gray-50 rounded-xl p-3">

                <p class="text-xs text-gray-500">
                    Time
                </p>

                <p class="text-sm font-semibold text-gray-700 mt-1">

                    @if ($violation->violation_time)

                        {{ \Carbon\Carbon::parse($violation->violation_time)->format('h:i A') }}

                    @else

                        N/A

                    @endif

                </p>

            </div>

        </div>


        <!-- Location -->

        @if ($violation->location)

            <div class="flex items-start gap-3">

                <div class="w-10 h-10 rounded-xl bg-blue-50 flex items-center justify-center flex-shrink-0">
                    📍
                </div>

                <div class="min-w-0">

                    <p class="text-xs text-gray-500">
                        Location
                    </p>

                    <p class="text-sm text-gray-700 mt-1 break-words">
                        {{ $violation->location }}
                    </p>

                </div>

            </div>

        @endif

    </div>

</div>


</div>

<!-- ========================================================= -->

<!-- DRIVER INFORMATION -->

<!-- ========================================================= -->

<div class="px-5 mt-4">


<div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">

    <div class="px-5 py-4 border-b border-gray-100">

        <h2 class="text-base font-bold text-gray-800">
            Driver Information
        </h2>

    </div>


    <div class="p-5 space-y-4">

        <!-- Driver Name -->

        <div>

            <p class="text-xs text-gray-500">
                Full Name
            </p>

            <p class="text-sm font-semibold text-gray-800 mt-1">
                {{ $violation->driver?->full_name ?? 'N/A' }}
            </p>

        </div>


        <!-- License Number -->

        <div>

            <p class="text-xs text-gray-500">
                License Number
            </p>

            <p class="text-sm font-semibold text-gray-800 mt-1">
                {{ $violation->driver?->license_number ?? 'N/A' }}
            </p>

        </div>


        <!-- Address -->

        @if ($violation->driver?->address)

            <div>

                <p class="text-xs text-gray-500">
                    Address
                </p>

                <p class="text-sm text-gray-700 mt-1 break-words">
                    {{ $violation->driver->address }}
                </p>

            </div>

        @endif


        <!-- Birth Date -->

        @if ($violation->driver?->birth_date)

            <div>

                <p class="text-xs text-gray-500">
                    Birth Date
                </p>

                <p class="text-sm text-gray-700 mt-1">
                    {{ \Carbon\Carbon::parse($violation->driver->birth_date)->format('M d, Y') }}
                </p>

            </div>

        @endif

    </div>

</div>


</div>

<!-- ========================================================= -->

<!-- VEHICLE INFORMATION -->

<!-- ========================================================= -->

<div class="px-5 mt-4">


<div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">

    <div class="px-5 py-4 border-b border-gray-100">

        <h2 class="text-base font-bold text-gray-800">
            Vehicle Information
        </h2>

    </div>


    <div class="p-5 space-y-4">

        <!-- Plate Number -->

        <div>

            <p class="text-xs text-gray-500">
                Plate Number
            </p>

            <p class="text-sm font-semibold text-gray-800 mt-1">
                {{ $violation->vehicle?->plate_number ?? 'N/A' }}
            </p>

        </div>


        <!-- Vehicle Type -->

        <div>

            <p class="text-xs text-gray-500">
                Vehicle Type
            </p>

            <p class="text-sm font-semibold text-gray-800 mt-1">
                {{ $violation->vehicle?->vehicle_type ?? 'N/A' }}
            </p>

        </div>


        <!-- Region Number -->

        @if ($violation->vehicle?->region_number)

            <div>

                <p class="text-xs text-gray-500">
                    Region Number
                </p>

                <p class="text-sm text-gray-700 mt-1">
                    {{ $violation->vehicle->region_number }}
                </p>

            </div>

        @endif


        <!-- Owner -->

        @if ($violation->vehicle?->owner_name)

            <div>

                <p class="text-xs text-gray-500">
                    Owner Name
                </p>

                <p class="text-sm text-gray-700 mt-1">
                    {{ $violation->vehicle->owner_name }}
                </p>

            </div>

        @endif

    </div>

</div>


</div>

<!-- ========================================================= -->

<!-- GPS LOCATION -->

<!-- ========================================================= -->

@if ($violation->latitude || $violation->longitude)


<div class="px-5 mt-4">

    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">

        <div class="px-5 py-4 border-b border-gray-100">

            <h2 class="text-base font-bold text-gray-800">
                GPS Location
            </h2>

        </div>


        <div class="p-5">

            <div class="grid grid-cols-2 gap-3">

                <div class="bg-gray-50 rounded-xl p-3">

                    <p class="text-xs text-gray-500">
                        Latitude
                    </p>

                    <p class="text-sm font-semibold text-gray-700 mt-1 break-all">
                        {{ $violation->latitude ?? 'N/A' }}
                    </p>

                </div>


                <div class="bg-gray-50 rounded-xl p-3">

                    <p class="text-xs text-gray-500">
                        Longitude
                    </p>

                    <p class="text-sm font-semibold text-gray-700 mt-1 break-all">
                        {{ $violation->longitude ?? 'N/A' }}
                    </p>

                </div>

            </div>

        </div>

    </div>

</div>


@endif

<!-- ========================================================= -->

<!-- REMARKS -->

<!-- ========================================================= -->

@if ($violation->remarks)


<div class="px-5 mt-4">

    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">

        <div class="px-5 py-4 border-b border-gray-100">

            <h2 class="text-base font-bold text-gray-800">
                Remarks
            </h2>

        </div>


        <div class="p-5">

            <p class="text-sm text-gray-700 leading-relaxed whitespace-pre-line">
                {{ $violation->remarks }}
            </p>

        </div>

    </div>

</div>


@endif

<!-- ========================================================= -->

<!-- TICKET IMAGE -->

<!-- ========================================================= -->

@if ($violation->ticket_image)


<div class="px-5 mt-4">

    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">

        <div class="px-5 py-4 border-b border-gray-100">

            <h2 class="text-base font-bold text-gray-800">
                Ticket Image
            </h2>

        </div>


        <div class="p-5">

            <img
                src="{{ asset('storage/' . $violation->ticket_image) }}"
                alt="Ticket Image"
                class="w-full rounded-2xl border border-gray-200">

        </div>

    </div>

</div>


@endif

<!-- ========================================================= -->

<!-- EVIDENCE IMAGES -->

<!-- ========================================================= -->

@if ($violation->images && $violation->images->count())


<div class="px-5 mt-4">

    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">

        <div class="px-5 py-4 border-b border-gray-100">

            <h2 class="text-base font-bold text-gray-800">
                Evidence Images
            </h2>

        </div>


        <div class="p-5">

            <div class="grid grid-cols-2 gap-3">

                @foreach ($violation->images as $image)

                    <div>

                        <img
                            src="{{ asset('storage/' . $image->image_path) }}"
                            alt="Violation Evidence"
                            class="w-full aspect-square object-cover rounded-2xl border border-gray-200">

                    </div>

                @endforeach

            </div>

        </div>

    </div>

</div>


@endif

<!-- ========================================================= -->

<!-- RECORDED BY -->

<!-- ========================================================= -->

<div class="px-5 mt-4">

<div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">

    <div class="px-5 py-4 border-b border-gray-100">

        <h2 class="text-base font-bold text-gray-800">
            Record Information
        </h2>

    </div>


    <div class="p-5">

        <p class="text-xs text-gray-500">
            Recorded By
        </p>

        <p class="text-sm font-semibold text-gray-800 mt-1">

            @if ($violation->user)

                {{ $violation->user->name }}

            @else

                N/A

            @endif

        </p>

    </div>

</div>


</div>

<!-- ========================================================= -->

<!-- BACK BUTTON -->

<!-- ========================================================= -->

<div class="px-5 mt-6 pb-6">


<a
    href="{{ route('enforcer.violations.index') }}"
    class="flex items-center justify-center gap-2 w-full bg-[#005FBF] hover:bg-[#004F9F] text-white rounded-xl py-3.5 text-sm font-semibold transition">

    <span>←</span>

    Back to Violations

</a>


</div>

@endsection
