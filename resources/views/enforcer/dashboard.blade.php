@extends('layouts.enforcer')

@section('title', 'Enforcer Dashboard')

@section('content')

<div class="min-h-screen bg-[#F5F7FB] pb-24">

    {{-- Header --}}
    @include('enforcer.partials.header')

    {{-- Dashboard Cards --}}
    <div class="px-5 -mt-5">

        <div class="grid grid-cols-2 gap-4">

            <!-- Today's Violations -->
            <div class="bg-white rounded-3xl shadow-md p-5">

                <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">
                    Today
                </p>

                <h2 class="text-3xl font-bold text-[#1D5FBF] mt-2">
                    {{ $todayViolations }}
                </h2>

                <p class="text-sm text-gray-500 mt-2">
                    Violations Recorded
                </p>

            </div>

            <!-- Date -->
            <div class="bg-white rounded-3xl shadow-md p-5">

                <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">
                    Date
                </p>

                <h2 class="text-xl font-bold text-[#0B2545] mt-2">
                    {{ now()->format('M d') }}
                </h2>

                <p class="text-sm text-gray-500 mt-2">
                    {{ now()->format('l') }}
                </p>

            </div>

        </div>

    </div>

    {{-- Quick Actions --}}
    @include('enforcer.partials.actions')

</div>

{{-- Bottom Navigation --}}
@include('enforcer.partials.bottom-nav')

@endsection