<!-- ================= MOBILE BOTTOM NAV ================= -->
<nav class="fixed bottom-0 left-0 right-0 z-50">
    <div class="relative bg-white border-t border-gray-200 shadow-lg rounded-t-3xl h-20">

        <div class="flex items-center h-full px-6 relative">

            <!-- Home (Left half - centered) -->
            <div class="flex-1 flex justify-center">
                <a href="{{ route('enforcer.dashboard') }}"
                   class="flex flex-col items-center justify-center
                   {{ request()->routeIs('enforcer.dashboard') ? 'text-[#1D5FBF]' : 'text-gray-500 hover:text-[#1D5FBF]' }}">

                    <svg xmlns="http://www.w3.org/2000/svg"
                         class="w-6 h-6"
                         fill="none"
                         viewBox="0 0 24 24"
                         stroke="currentColor">
                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              stroke-width="2"
                              d="M3 10.5L12 3l9 7.5V20a1 1 0 01-1 1h-5v-6H9v6H4a1 1 0 01-1-1v-9.5z"/>
                    </svg>
                    <span class="text-[11px] mt-1 font-medium">Home</span>
                </a>
            </div>

            <!-- Spacer for the center button -->
            <div class="w-16"></div>

            <!-- Profile (Right half - centered) -->
            <div class="flex-1 flex justify-center">
                <a href="#"
                   class="flex flex-col items-center justify-center text-gray-500 hover:text-[#1D5FBF]">

                    <svg xmlns="http://www.w3.org/2000/svg"
                         class="w-6 h-6"
                         fill="none"
                         viewBox="0 0 24 24"
                         stroke="currentColor">
                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              stroke-width="2"
                              d="M5.121 17.804A8.966 8.966 0 0112 15a8.966 8.966 0 016.879 2.804M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    <span class="text-[11px] mt-1 font-medium">Profile</span>
                </a>
            </div>

            <!-- Issue Button (Perfectly centered) -->
            <a href="{{ route('enforcer.violations.create') }}"
               class="absolute left-1/2 -translate-x-1/2 -top-6
                      w-16 h-16 rounded-full
                      bg-gradient-to-br from-[#1D5FBF] to-[#4B90FF]
                      border-4 border-[#F5F7FB]
                      shadow-xl
                      flex flex-col items-center justify-center
                      transition duration-200 hover:scale-105 active:scale-95">

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
                <span class="text-[10px] text-white font-semibold leading-none">Issue</span>
            </a>

        </div>
    </div>
</nav>