<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TrafficEnforceNet | POSO Tarlac City</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-[#F1F5F9] text-[#1E293B] font-sans antialiased">

    <!-- ===================== NAVBAR ===================== -->
    <header class="bg-[#005fbf] text-white shadow-md">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">

                <!-- Logo -->
                <div class="flex items-center gap-2">
                    <div class="w-9 h-9 rounded-full bg-white flex items-center justify-center overflow-hidden">
                        <img src="{{ asset('images/logo/logo.png') }}" alt="TrafficEnforceNet Logo"
                            class="w-full h-full object-contain">
                    </div>

                    <span class="font-bold text-lg tracking-tight">
                        TrafficEnforceNet
                    </span>
                </div>

            </div>
        </div>
    </header>


    <!-- ===================== HERO ===================== -->
    <section class="relative bg-[#005fbf] overflow-hidden">

        <div class="absolute inset-0 opacity-30">
            <img src="https://images.unsplash.com/photo-1582213782179-e0d53f98f2ca?auto=format&fit=crop&w=1400&q=80"
                alt="" class="w-full h-full object-cover">
        </div>

        <div class="absolute inset-0 bg-gradient-to-r from-[#005fbf]/90 via-[#005fbf]/70 to-[#005fbf]/40"></div>

        <div class="relative max-w-7xl mx-auto px-6 lg:px-12 py-16 lg:py-20">

            <div class="grid lg:grid-cols-2 gap-10 items-center">

                <!-- Left text -->
                <div class="text-white">

                    <p class="text-base lg:text-lg leading-relaxed max-w-lg">
                        TrafficEnforceNet supports the Public Order and Safety Office (POSO) of Tarlac City by providing
                        a digital platform for organized traffic violation management and improved public service
                        delivery.
                    </p>

                    <div class="mt-8">
                        <a href="#ticket-search"
                            class="inline-block bg-[#005fbf] border-2 border-white text-white px-6 py-2.5 rounded-full font-semibold hover:bg-white hover:text-[#005fbf] transition">
                            Check Ticket Status
                        </a>
                    </div>

                </div>

                <!-- Right: Official Seal -->
                <div class="flex justify-center lg:justify-end">

                    <div
                        class="w-56 h-56 lg:w-64 lg:h-64 rounded-full bg-white shadow-2xl flex items-center justify-center p-3">

                        <img src="{{ asset('images/logo/POSOlogo.png') }}" alt="POSO Logo"
                            class="w-full h-full object-contain rounded-full"
                            onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">

                        <div class="hidden flex-col items-center justify-center text-center text-[#005fbf] p-4">
                            <div class="text-xs font-bold uppercase tracking-wider">
                                City of Tarlac
                            </div>

                            <div class="text-[10px] mt-1">
                                Province of Tarlac
                            </div>

                            <div class="mt-3 text-4xl">
                                🏛
                            </div>

                            <div class="text-[10px] mt-2 font-semibold">
                                Public Order and<br>
                                Safety Office
                            </div>
                        </div>

                    </div>

                </div>

            </div>

        </div>
    </section>


    <!-- ===================== PUBLIC SERVICES ===================== -->
    <section class="py-16 px-6">

        <div class="max-w-6xl mx-auto grid md:grid-cols-3 gap-6">

            <!-- Card 1: Ticket Inquiry -->
            <div
                class="bg-white rounded-xl shadow-sm border border-gray-100 p-8 text-center hover:shadow-md transition">

                <div class="mx-auto w-14 h-14 rounded-full bg-blue-50 flex items-center justify-center mb-5">

                    <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 text-[#005fbf]" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">

                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />

                    </svg>

                </div>

                <h3 class="font-bold text-lg text-[#1E293B]">
                    Digital Ticket Inquiry
                </h3>

                <p class="mt-2 text-sm text-[#64748B]">
                    Verify your traffic violation records anytime using your ticket number.
                </p>

            </div>


            <!-- Card 2: Transparent Processing -->
            <div
                class="bg-white rounded-xl shadow-sm border border-gray-100 p-8 text-center hover:shadow-md transition">

                <div class="mx-auto w-14 h-14 rounded-full bg-blue-50 flex items-center justify-center mb-5">

                    <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 text-[#005fbf]" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">

                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />

                    </svg>

                </div>

                <h3 class="font-bold text-lg text-[#1E293B]">
                    Transparent Processing
                </h3>

                <p class="mt-2 text-sm text-[#64748B]">
                    Monitor the current status and updates of your traffic violation case.
                </p>

            </div>


            <!-- Card 3: Improved Public Service -->
            <div
                class="bg-white rounded-xl shadow-sm border border-gray-100 p-8 text-center hover:shadow-md transition">

                <div class="mx-auto w-14 h-14 rounded-full bg-blue-50 flex items-center justify-center mb-5">

                    <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 text-[#005fbf]" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">

                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z" />

                    </svg>

                </div>

                <h3 class="font-bold text-lg text-[#1E293B]">
                    Improved Public Service
                </h3>

                <p class="mt-2 text-sm text-[#64748B]">
                    Supporting faster and more efficient traffic management for the public.
                </p>

            </div>

        </div>

    </section>


    <!-- ===================== TICKET SEARCH ===================== -->
    <section id="ticket-search" class="py-10 px-6">

        <div class="max-w-3xl mx-auto text-center">

            <h2 class="text-2xl font-bold text-[#1E293B]">
                Check Your Traffic Violation
            </h2>

            <p class="mt-2 text-[#64748B]">
                Enter your ticket number to view available violation information.
            </p>

            <form action="#" method="GET"
                class="mt-8 flex flex-col sm:flex-row gap-3 justify-center items-center">

                <input type="text" name="ticket_number" placeholder="Enter Ticket Number"
                    class="w-full sm:w-80 rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#005fbf] focus:border-transparent">

                <button type="submit"
                    class="bg-[#005fbf] text-white px-6 py-2.5 rounded-lg font-semibold text-sm hover:bg-[#004a99] transition w-full sm:w-auto">
                    Search
                </button>

            </form>

        </div>

    </section>


    <!-- ===================== IMAGE PLACEHOLDERS ===================== -->
    <section class="py-10 px-6">

        <div class="max-w-5xl mx-auto grid md:grid-cols-2 gap-6">

            <div
                class="bg-gradient-to-b from-sky-200 to-green-300 rounded-xl h-48 flex items-end justify-center overflow-hidden">
                <div class="w-full h-16 bg-green-500/80 rounded-t-[50%]"></div>
            </div>

            <div
                class="bg-gradient-to-b from-sky-200 to-green-300 rounded-xl h-48 flex items-end justify-center overflow-hidden">
                <div class="w-full h-16 bg-green-500/80 rounded-t-[50%]"></div>
            </div>

        </div>

    </section>


    <!-- ===================== HOW IT WORKS ===================== -->
    <section class="py-16 px-6 bg-white">

        <div class="max-w-6xl mx-auto">

            <h2 class="text-2xl font-bold text-center text-[#1E293B] mb-10">
                How it Works
            </h2>

            <div class="grid grid-cols-2 md:grid-cols-4 gap-5">

                <!-- Step 1 -->
                <div class="bg-[#F1F5F9] rounded-xl p-6 text-center">

                    <div
                        class="mx-auto w-12 h-12 rounded-full bg-[#005fbf] text-white flex items-center justify-center mb-4">

                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="2">

                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />

                        </svg>

                    </div>

                    <div class="text-[#005fbf] font-bold text-lg">
                        01
                    </div>

                    <p class="mt-1 text-sm font-medium text-[#1E293B]">
                        Violation Recorded
                    </p>

                </div>


                <!-- Step 2 -->
                <div class="bg-[#F1F5F9] rounded-xl p-6 text-center">

                    <div
                        class="mx-auto w-12 h-12 rounded-full bg-[#005fbf] text-white flex items-center justify-center mb-4">

                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="2">

                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />

                        </svg>

                    </div>

                    <div class="text-[#005fbf] font-bold text-lg">
                        02
                    </div>

                    <p class="mt-1 text-sm font-medium text-[#1E293B]">
                        Ticket Issued
                    </p>

                </div>


                <!-- Step 3 -->
                <div class="bg-[#F1F5F9] rounded-xl p-6 text-center">

                    <div
                        class="mx-auto w-12 h-12 rounded-full bg-[#005fbf] text-white flex items-center justify-center mb-4">

                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="2">

                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />

                        </svg>

                    </div>

                    <div class="text-[#005fbf] font-bold text-lg">
                        03
                    </div>

                    <p class="mt-1 text-sm font-medium text-[#1E293B]">
                        Status Checked
                    </p>

                </div>


                <!-- Step 4 -->
                <div class="bg-[#F1F5F9] rounded-xl p-6 text-center">

                    <div
                        class="mx-auto w-12 h-12 rounded-full bg-[#005fbf] text-white flex items-center justify-center mb-4">

                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="2">

                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />

                        </svg>

                    </div>

                    <div class="text-[#005fbf] font-bold text-lg">
                        04
                    </div>

                    <p class="mt-1 text-sm font-medium text-[#1E293B]">
                        Case Resolution
                    </p>

                </div>

            </div>

        </div>

    </section>


    <!-- ===================== ABOUT POSO ===================== -->
    <section class="py-16 px-6">

        <div class="max-w-3xl mx-auto text-center">

            <h2 class="text-2xl font-bold text-[#1E293B]">
                About POSO Tarlac City
            </h2>

            <p class="mt-4 text-[#64748B] leading-relaxed">
                TrafficEnforceNet supports the Public Order and Safety Office (POSO) of Tarlac City by providing a
                digital platform for organized traffic violation management and improved public service delivery.
            </p>

        </div>

    </section>


    <!-- ===================== FOOTER ===================== -->
    <footer class="bg-[#005fbf] text-white">

        <div class="max-w-7xl mx-auto px-6 py-8">

            <div class="flex flex-col items-center gap-4">

                <div class="flex items-center gap-2">

                    <div class="w-8 h-8 rounded-full bg-white flex items-center justify-center overflow-hidden">
                        <img src="{{ asset('images/logo/logo.png') }}" alt="TrafficEnforceNet Logo"
                            class="w-full h-full object-contain">
                    </div>

                    <span class="font-bold text-lg">
                        TrafficEnforceNet
                    </span>

                </div>


                <div class="flex flex-col items-center gap-3 mt-1">

                    <!-- Facebook + Phone -->
                    <div class="flex items-center justify-center gap-8">

                        <!-- Facebook -->
                        <a href="https://www.facebook.com/share/1CB9zxxzdu/"
                            class="flex items-center gap-3 hover:opacity-80 transition whitespace-nowrap"
                            aria-label="Tarlac City - POSO Facebook Page">

                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24"
                                fill="currentColor">

                                <path
                                    d="M24 12.073C24 5.405 18.627 0 12 0S0 5.405 0 12.073c0 6.025 4.388 11.024 10.125 11.928v-8.437H7.078v-3.491h3.047V9.41c0-3.017 1.792-4.688 4.533-4.688 1.312 0 2.686.236 2.686.236v2.977H15.83c-1.491 0-1.956.93-1.956 1.886v2.252h3.328l-.532 3.491h-2.796V24C19.612 23.097 24 18.098 24 12.073z" />
                            </svg>

                            <span class="text-sm font-medium">
                                Tarlac City - POSO
                            </span>
                        </a>

                        <!-- Phone -->
                        <a href="tel:09301646092"
                            class="flex items-center gap-3 hover:opacity-80 transition whitespace-nowrap"
                            aria-label="Call POSO">

                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">

                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M3 5a2 2 0 012-2h2.28a2 2 0 011.94 1.515l.58 2.32a2 2 0 01-.45 1.84L8.09 9.91a16 16 0 006 6l1.235-1.26a2 2 0 011.84-.45l2.32.58A2 2 0 0121 16.72V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                            </svg>

                            <span class="text-sm font-medium">
                                0930 164 6092
                            </span>
                        </a>

                    </div>

                    <!-- Location -->
                    <div class="flex items-center gap-3 text-center">

                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 flex-shrink-0" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">

                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M17.657 16.657L13.414 20.9a2 2 0 01-2.828 0l-4.243-4.243a8 8 0 1111.314 0z" />

                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>

                        <span class="text-sm">
                            San Sebastian, Tarlac City, Tarlac, Philippines, 2300
                        </span>

                    </div>

                </div>


                <p class="text-xs text-blue-100 mt-2">
                    © {{ date('Y') }} TrafficEnforceNet
                </p>

            </div>

        </div>

    </footer>

</body>

</html>
