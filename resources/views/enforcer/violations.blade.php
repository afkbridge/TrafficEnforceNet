@extends('layouts.enforcer')

@section('title', 'Recorded Violations')

@section('content')

    <!-- ========================================================= -->

    <!-- HEADER -->

    <!-- ========================================================= -->

    <div class="bg-gradient-to-br from-[#1D5FBF] to-[#2E77E6] text-white px-5 pt-7 pb-8 rounded-b-[30px] shadow-sm">


        <div class="flex items-center gap-3">

            <a href="{{ route('enforcer.dashboard') }}"
                class="w-10 h-10 rounded-xl bg-white/15 hover:bg-white/25 flex items-center justify-center text-xl transition flex-shrink-0"
                aria-label="Back to dashboard">
                ←
            </a>

            <div class="min-w-0">
                <h1 class="text-xl font-bold leading-tight">
                    Recorded Violations
                </h1>

                <p class="text-blue-100 text-sm mt-1">
                    View traffic citations recorded by you.
                </p>
            </div>

        </div>


    </div>

    <!-- ========================================================= -->

    <!-- SUMMARY CARDS -->

    <!-- ========================================================= -->

    <div class="px-5 -mt-4 relative z-10">


        <div class="grid grid-cols-2 gap-3">

            <!-- Total -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4">

                <div class="flex items-center gap-3">

                    <div class="w-10 h-10 rounded-xl bg-blue-50 flex items-center justify-center text-lg">
                        📋
                    </div>

                    <div>
                        <p class="text-xs text-gray-500">
                            Total
                        </p>

                        <p class="text-xl font-bold text-gray-800">
                            {{ $violations->total() }}
                        </p>
                    </div>

                </div>

            </div>


            <!-- Pending -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4">

                <div class="flex items-center gap-3">

                    <div class="w-10 h-10 rounded-xl bg-yellow-50 flex items-center justify-center text-lg">
                        ⏳
                    </div>

                    <div>
                        <p class="text-xs text-gray-500">
                            Pending
                        </p>

                        <p class="text-xl font-bold text-gray-800">
                            {{ $violations->where('status', 'Pending')->count() }}
                        </p>
                    </div>

                </div>

            </div>

        </div>


    </div>

    <!-- ========================================================= -->

    <!-- DATE FILTER -->

    <!-- ========================================================= -->

    <div class="px-5 mt-5">


        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-5">

            <div class="flex items-center gap-3 mb-4">

                <div class="w-10 h-10 rounded-xl bg-blue-50 flex items-center justify-center text-lg">
                    🔎
                </div>

                <div>
                    <h2 class="text-sm font-bold text-gray-800">
                        Filter Violations
                    </h2>

                    <p class="text-xs text-gray-500 mt-0.5">
                        View your violations by date.
                    </p>
                </div>

            </div>


            <form method="GET" action="{{ route('enforcer.violations.index') }}">

                <div class="grid grid-cols-2 gap-3">

                    <!-- TODAY -->

                    <button type="submit" name="filter" value="today"
                        class="flex items-center justify-center gap-2 rounded-xl py-3 px-3 text-sm font-semibold transition
                {{ request('filter') === 'today'
                    ? 'bg-[#005FBF] text-white shadow-sm'
                    : 'bg-blue-50 text-[#005FBF] hover:bg-blue-100' }}">

                        <span>📅</span>

                        <span>
                            Today
                        </span>

                    </button>


                    <!-- PICK A DATE -->

                    <div>

                        <label for="date"
                            class="flex items-center justify-center gap-2 rounded-xl py-3 px-3 text-sm font-semibold bg-gray-50 text-gray-700 border border-gray-200">

                            <span>🗓️</span>

                            <span>
                                Pick a Date
                            </span>

                        </label>

                        <input type="date" id="date" name="date" value="{{ request('date') }}"
                            onchange="this.form.submit()"
                            class="w-full mt-2 rounded-xl border border-gray-200 bg-gray-50 px-3 py-3 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-[#1D5FBF]/20 focus:border-[#1D5FBF] transition">

                    </div>

            </form>


            <!-- Clear Filter -->

            @if (request()->filled('filter') || request()->filled('date'))
                <div class="mt-3">

                    <a href="{{ route('enforcer.violations.index') }}"
                        class="block w-full text-center text-sm font-semibold text-gray-500 hover:text-[#005FBF] py-2 transition">

                        Clear Filter

                    </a>

                </div>
            @endif

        </div>


    </div>

    <!-- ========================================================= -->

    <!-- ACTIVE FILTER -->

    <!-- ========================================================= -->

    @if (request('filter') === 'today' || request()->filled('date'))

        <div class="px-5 mt-3">

            <div class="flex items-center gap-2 bg-blue-50 border border-blue-100 rounded-xl px-4 py-3">

                <span class="text-sm">
                    🔎
                </span>

                <p class="text-xs text-blue-700">

                    @if (request('filter') === 'today')
                        Showing violations recorded
                        <span class="font-semibold">
                            today
                        </span>.
                    @elseif (request()->filled('date'))
                        Showing violations for
                        <span class="font-semibold">
                            {{ \Carbon\Carbon::parse(request('date'))->format('M d, Y') }}
                        </span>.
                    @endif

                </p>

            </div>

        </div>


    @endif

    <!-- ========================================================= -->

    <!-- VIOLATION LIST -->

    <!-- ========================================================= -->

    <div class="px-5 mt-5">


        <div class="flex items-center justify-between mb-3">

            <div>

                <h2 class="text-base font-bold text-gray-800">
                    Violation Records
                </h2>

                <p class="text-xs text-gray-500 mt-0.5">
                    Your recorded traffic citations
                </p>

            </div>

            @if ($violations->total() > 0)
                <span class="text-xs font-semibold text-gray-500">
                    {{ $violations->total() }} record{{ $violations->total() !== 1 ? 's' : '' }}
                </span>
            @endif

        </div>


        @if ($violations->count())

            <!-- ===================================================== -->
            <!-- COMPACT LIST -->
            <!-- ===================================================== -->

            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">

                @foreach ($violations as $violation)
                    <div class="px-4 py-4
                {{ !$loop->last ? 'border-b border-gray-100' : '' }}">

                        <div class="flex items-center gap-3">

                            <!-- Violation Icon -->

                            <div
                                class="w-10 h-10 rounded-xl bg-blue-50 flex items-center justify-center text-lg flex-shrink-0">
                                ⚠️
                            </div>


                            <!-- Main Information -->

                            <div class="min-w-0 flex-1">

                                <!-- Ticket -->

                                <div class="flex items-center gap-2">

                                    <p class="text-sm font-bold text-[#1D5FBF] truncate">
                                        {{ $violation->ticket_number }}
                                    </p>

                                </div>


                                {{-- Violation Type --}}
                                <div class="text-sm font-semibold text-gray-800 mt-0.5">
                                    @if ($violation->violationTypes->count())
                                        @foreach ($violation->violationTypes as $type)
                                            <div class="truncate">
                                                {{ $type->name }}
                                            </div>
                                        @endforeach
                                    @else
                                        <div class="truncate">
                                            {{ $violation->violationType?->name ?? 'Unknown Violation' }}
                                        </div>
                                    @endif
                                </div>


                                <!-- Date -->

                                <p class="text-xs text-gray-500 mt-1">

                                    {{ \Carbon\Carbon::parse($violation->violation_date)->format('M d, Y') }}

                                    @if ($violation->violation_time)
                                        <span class="mx-1">
                                            •
                                        </span>

                                        {{ \Carbon\Carbon::parse($violation->violation_time)->format('h:i A') }}
                                    @endif

                                </p>

                            </div>


                            <!-- Status + View -->

                            <div class="flex flex-col items-end gap-2 flex-shrink-0">

                                @if ($violation->status === 'Completed')
                                    <span
                                        class="text-[10px] font-semibold px-2.5 py-1 rounded-full bg-green-50 text-green-700">

                                        Completed

                                    </span>
                                @else
                                    <span
                                        class="text-[10px] font-semibold px-2.5 py-1 rounded-full bg-yellow-50 text-yellow-700">

                                        {{ $violation->status ?? 'Pending' }}

                                    </span>
                                @endif


                                <a href="{{ route('enforcer.violations.show', $violation->id) }}"
                                    class="text-xs font-semibold text-[#005FBF] hover:text-[#004F9F] transition">

                                    View →

                                </a>

                            </div>

                        </div>

                    </div>
                @endforeach

            </div>


            <!-- ===================================================== -->
            <!-- PAGINATION -->
            <!-- ===================================================== -->

            @if ($violations->hasPages())
                <div class="mt-5">

                    {{ $violations->links() }}

                </div>
            @endif
        @else
            <!-- ===================================================== -->
            <!-- EMPTY STATE -->
            <!-- ===================================================== -->

            <div class="bg-white rounded-3xl border border-gray-100 shadow-sm p-8 text-center">

                <div class="w-16 h-16 mx-auto rounded-2xl bg-blue-50 flex items-center justify-center text-3xl">
                    📋
                </div>


                <h2 class="font-bold text-gray-800 text-lg mt-5">
                    No Violations Found
                </h2>


                @if (request('filter') === 'today')
                    <p class="text-sm text-gray-500 mt-2 leading-relaxed">
                        You have not recorded any traffic violations today.
                    </p>

                    <a href="{{ route('enforcer.violations.index') }}"
                        class="inline-flex items-center justify-center mt-5 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl px-5 py-3 text-sm font-semibold transition">

                        View All Violations

                    </a>
                @elseif (request()->filled('date'))
                    <p class="text-sm text-gray-500 mt-2 leading-relaxed">

                        No violations were recorded on
                        <span class="font-semibold">
                            {{ \Carbon\Carbon::parse(request('date'))->format('M d, Y') }}
                        </span>.

                    </p>

                    <a href="{{ route('enforcer.violations.index') }}"
                        class="inline-flex items-center justify-center mt-5 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl px-5 py-3 text-sm font-semibold transition">

                        View All Violations

                    </a>
                @else
                    <p class="text-sm text-gray-500 mt-2 leading-relaxed">
                        You haven't recorded any traffic violations yet.
                    </p>

                    <a href="{{ route('enforcer.violations.create') }}"
                        class="inline-flex items-center justify-center gap-2 mt-5 bg-[#005FBF] hover:bg-[#004F9F] text-white rounded-xl px-5 py-3 text-sm font-semibold transition">

                        <span>+</span>

                        Issue Traffic Citation

                    </a>
                @endif

            </div>

        @endif


    </div>

@endsection
