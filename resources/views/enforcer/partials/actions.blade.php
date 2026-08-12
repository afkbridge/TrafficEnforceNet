<!-- ================= QUICK ACTIONS ================= -->

<div class="px-5 mt-7">

    <div class="mb-4">

        <h3 class="text-lg font-bold text-[#0B2545]">
            Quick Actions
        </h3>

        <p class="text-sm text-gray-500">
            Select an action below.
        </p>

    </div>

    <div class="space-y-4">

        <!-- Issue Violation -->
        <a href="{{ route('enforcer.violations.create') }}"
            class="group flex items-center justify-between bg-white rounded-3xl p-5 shadow-md border border-gray-100 transition duration-300 hover:shadow-lg hover:-translate-y-1">

            <div class="flex items-center gap-4">

                <div
                    class="w-14 h-14 rounded-2xl bg-gradient-to-br from-[#1D5FBF] to-[#4B90FF] flex items-center justify-center shadow">

                    <svg xmlns="http://www.w3.org/2000/svg"
                        class="w-7 h-7 text-white"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor">

                        <path stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M12 4v16m8-8H4"/>

                    </svg>

                </div>

                <div>

                    <h4 class="font-bold text-[#0B2545]">
                        Issue Violation
                    </h4>

                    <p class="text-sm text-gray-500 mt-1">
                        Create a new traffic violation.
                    </p>

                </div>

            </div>

            <svg xmlns="http://www.w3.org/2000/svg"
                class="w-5 h-5 text-gray-400 group-hover:text-[#1D5FBF] transition"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor">

                <path stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M9 5l7 7-7 7"/>

            </svg>

        </a>

        <!-- My Violations -->
        <a href="{{ route('enforcer.violations.index') }}"
            class="group flex items-center justify-between bg-white rounded-3xl p-5 shadow-md border border-gray-100 transition duration-300 hover:shadow-lg hover:-translate-y-1">

            <div class="flex items-center gap-4">

                <div
                    class="w-14 h-14 rounded-2xl bg-gradient-to-br from-green-500 to-emerald-600 flex items-center justify-center shadow">

                    <svg xmlns="http://www.w3.org/2000/svg"
                        class="w-7 h-7 text-white"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor">

                        <path stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M9 12h6m-6 4h6M7 4h10a2 2 0 012 2v12a2 2 0 01-2 2H7a2 2 0 01-2-2V6a2 2 0 012-2z"/>

                    </svg>

                </div>

                <div>

                    <h4 class="font-bold text-[#0B2545]">
                        Recorded Violations
                    </h4>

                    <p class="text-sm text-gray-500 mt-1">
                        View your recorded violations.
                    </p>

                </div>

            </div>

            <svg xmlns="http://www.w3.org/2000/svg"
                class="w-5 h-5 text-gray-400 group-hover:text-green-600 transition"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor">

                <path stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M9 5l7 7-7 7"/>

            </svg>

        </a>

    </div>

</div>