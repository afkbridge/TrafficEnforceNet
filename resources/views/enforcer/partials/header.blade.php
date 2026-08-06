@php
    $hour = now()->hour;

    if ($hour < 12) {
        $greeting = 'Good Morning';
    } elseif ($hour < 18) {
        $greeting = 'Good Afternoon';
    } else {
        $greeting = 'Good Evening';
    }
@endphp

<!-- Header -->
<div class="bg-gradient-to-br from-[#1D5FBF] to-[#4B90FF] rounded-b-[32px] shadow-lg">

    <div class="px-5 pt-6 pb-8">

        <!-- Top -->
        <div class="flex justify-between items-center">

            <div>

                <h1 class="text-xl font-bold text-white">
                    TrafficEnforceNet
                </h1>

                <p class="text-blue-100 text-sm">
                    POSO Enforcer Portal
                </p>

            </div>

            <div
                class="flex items-center gap-2 bg-green-500/20 border border-green-300/30 px-3 py-1 rounded-full">

                <span class="w-2 h-2 rounded-full bg-green-300 animate-pulse"></span>

                <span class="text-xs text-white">

                    Online

                </span>

            </div>

        </div>

        <!-- Greeting -->
        <div class="mt-7">

            <p class="text-blue-100 text-sm">

                {{ $greeting }}

            </p>

            <h2 class="text-3xl font-bold text-white mt-1">

                {{ auth()->user()->name }}

            </h2>

        </div>

        <!-- Information Card -->
        <div class="mt-5 bg-white/15 backdrop-blur rounded-3xl p-4 border border-white/20">

            <div class="flex items-center justify-between">

                <div>

                    <p class="text-white font-semibold">

                        {{ $enforcer?->position ?? 'Traffic Enforcer' }}

                    </p>

                    <p class="text-blue-100 text-sm mt-1">

                        Badge No.
                        {{ $enforcer?->badge_number ?? 'N/A' }}

                    </p>

                </div>

               

            </div>

        </div>

    </div>

</div>