<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>TrafficEnforceNet | POSO Tarlac City</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        html {
            scroll-behavior: smooth;
        }

        .hero-pattern {
            background-image:
                radial-gradient(circle at 20% 20%, rgba(255, 255, 255, 0.08) 0, transparent 25%),
                radial-gradient(circle at 80% 70%, rgba(255, 255, 255, 0.06) 0, transparent 25%);
        }

        .glass-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(12px);
        }

        .gallery-fade {
            opacity: 0;
            transform: scale(1.015);
        }

        .gallery-visible {
            opacity: 1;
            transform: scale(1);
        }

        .gallery-transition {
            transition:
                opacity 0.45s ease,
                transform 0.65s ease;
        }

        .thumbnail-active {
            border-color: #005fbf !important;
            opacity: 1 !important;
            transform: translateY(-2px);
        }

        .thumbnail-inactive {
            opacity: 0.65;
        }
    </style>
</head>

<body class="bg-[#F5F7FB] text-[#1E293B] font-sans antialiased">

    <!-- ========================================================= -->
    <!-- NAVBAR -->
    <!-- ========================================================= -->

    <header class="sticky top-0 z-50 bg-[#005fbf] text-white shadow-lg">

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="flex items-center justify-between h-16">

                <!-- Logo -->
                <a href="/" class="flex items-center gap-3">

                    <div
                        class="w-10 h-10 rounded-full bg-white flex items-center justify-center overflow-hidden shadow-sm">

                        <img src="{{ asset('images/logo/logo.png') }}"
                            alt="TrafficEnforceNet Logo"
                            class="w-full h-full object-contain">

                    </div>

                    <div>
                        <div class="font-bold text-lg leading-tight tracking-tight">
                            TrafficEnforceNet
                        </div>

                        <div class="text-[10px] text-blue-100 uppercase tracking-wider">
                            POSO Tarlac City
                        </div>
                    </div>

                </a>

                <!-- Navigation -->
                <nav class="hidden sm:flex items-center gap-6 text-sm font-medium">

                    <a href="#services"
                        class="text-blue-50 hover:text-white transition">
                        Services
                    </a>

                    <a href="#gallery"
                        class="text-blue-50 hover:text-white transition">
                        Gallery
                    </a>

                    <a href="#how-it-works"
                        class="text-blue-50 hover:text-white transition">
                        How It Works
                    </a>

                    <a href="#about"
                        class="text-blue-50 hover:text-white transition">
                        About
                    </a>

                </nav>

            </div>

        </div>

    </header>


    <!-- ========================================================= -->
    <!-- HERO -->
    <!-- ========================================================= -->

    <section class="relative overflow-hidden bg-[#005fbf] hero-pattern">

        <!-- Background Image -->

        <div class="absolute inset-0">

            <img
                src="https://images.unsplash.com/photo-1582213782179-e0d53f98f2ca?auto=format&fit=crop&w=1600&q=80"
                alt=""
                class="w-full h-full object-cover opacity-20">

        </div>

        <!-- Overlay -->

        <div class="absolute inset-0 bg-gradient-to-r from-[#004b97] via-[#005fbf]/95 to-[#0072ce]/80">
        </div>


        <div class="relative max-w-7xl mx-auto px-6 lg:px-12 py-16 lg:py-24">

            <div class="grid lg:grid-cols-2 gap-12 lg:gap-16 items-center">

                <!-- LEFT -->

                <div class="text-white">

                    <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white/10 border border-white/20 backdrop-blur-sm mb-6">

                        <span class="w-2 h-2 rounded-full bg-green-300"></span>

                        <span class="text-xs sm:text-sm font-medium">
                            Public Traffic Violation Management System
                        </span>

                    </div>


                    <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold leading-tight tracking-tight">

                        Traffic
                        <span class="text-blue-200">
                            EnforceNet
                        </span>

                    </h1>


                    <p class="mt-5 text-lg sm:text-xl text-blue-50 font-medium max-w-xl leading-relaxed">

                        A digital platform supporting the
                        <strong>Public Order and Safety Office (POSO)</strong>
                        of Tarlac City in organizing traffic violation records and improving public service delivery.

                    </p>


                    <p class="mt-4 text-sm sm:text-base text-blue-100 max-w-xl leading-relaxed">

                        Access available traffic violation information, check ticket status, and learn how the system
                        supports a more organized traffic enforcement process.

                    </p>


                    <!-- Buttons -->

                    <div class="mt-8 flex flex-col sm:flex-row gap-3">

                        <a href="#ticket-search"
                            class="inline-flex items-center justify-center gap-2 bg-white text-[#005fbf] px-6 py-3 rounded-xl font-bold shadow-lg hover:bg-blue-50 transition">

                            <svg xmlns="http://www.w3.org/2000/svg"
                                class="w-5 h-5"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                                stroke-width="2">

                                <path stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />

                            </svg>

                            Check Ticket Status

                        </a>


                        <a href="#about"
                            class="inline-flex items-center justify-center gap-2 border border-white/50 text-white px-6 py-3 rounded-xl font-semibold hover:bg-white/10 transition">

                            Learn More

                            <svg xmlns="http://www.w3.org/2000/svg"
                                class="w-4 h-4"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                                stroke-width="2">

                                <path stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M9 5l7 7-7 7" />

                            </svg>

                        </a>

                    </div>

                </div>


                <!-- RIGHT -->

                <div class="flex justify-center lg:justify-end">

                    <div class="relative">

                        <!-- Decorative circle -->

                        <div class="absolute -inset-5 rounded-full bg-white/10 blur-xl">
                        </div>


                        <div class="relative w-64 h-64 sm:w-72 sm:h-72 rounded-full bg-white shadow-2xl flex items-center justify-center p-4">

                            <img
                                src="{{ asset('images/logo/POSOlogo.png') }}"
                                alt="POSO Tarlac City Logo"
                                class="w-full h-full object-contain rounded-full"
                                onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">


                            <!-- Fallback -->

                            <div class="hidden flex-col items-center justify-center text-center text-[#005fbf] p-6">

                                <div class="text-xs font-bold uppercase tracking-wider">
                                    City of Tarlac
                                </div>

                                <div class="text-[10px] mt-1">
                                    Province of Tarlac
                                </div>

                                <div class="mt-4 text-5xl">
                                    🏛
                                </div>

                                <div class="text-[10px] mt-3 font-semibold">
                                    Public Order and<br>
                                    Safety Office
                                </div>

                            </div>

                        </div>


                        <!-- Small badge -->

                        <div class="absolute -bottom-4 left-1/2 -translate-x-1/2 bg-white text-[#005fbf] px-5 py-2 rounded-full shadow-xl text-xs font-bold whitespace-nowrap">

                            POSO Tarlac City

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </section>


    <!-- ========================================================= -->
    <!-- SERVICES -->
    <!-- ========================================================= -->

    <section id="services" class="py-16 sm:py-20 px-6">

        <div class="max-w-6xl mx-auto">

            <!-- Heading -->

            <div class="text-center max-w-2xl mx-auto mb-12">

                <span class="text-xs font-bold uppercase tracking-[0.2em] text-[#005fbf]">
                    Public Services
                </span>

                <h2 class="mt-2 text-3xl font-bold text-[#1E293B]">
                    Access Traffic Information
                </h2>

                <p class="mt-3 text-[#64748B]">
                    TrafficEnforceNet provides public-facing tools designed to make traffic violation information
                    easier to access.
                </p>

            </div>


            <!-- Cards -->

            <div class="grid md:grid-cols-3 gap-6">


                <!-- Card 1 -->

                <div
                    class="group bg-white rounded-2xl border border-slate-200 p-8 shadow-sm hover:shadow-xl hover:-translate-y-1 transition duration-300">

                    <div
                        class="w-14 h-14 rounded-2xl bg-blue-50 flex items-center justify-center mb-6 group-hover:bg-[#005fbf] transition">

                        <svg xmlns="http://www.w3.org/2000/svg"
                            class="h-7 w-7 text-[#005fbf] group-hover:text-white transition"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                            stroke-width="1.8">

                            <path stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />

                        </svg>

                    </div>

                    <h3 class="font-bold text-xl text-[#1E293B]">
                        Digital Ticket Inquiry
                    </h3>

                    <p class="mt-3 text-sm text-[#64748B] leading-relaxed">
                        Check available traffic violation information using your assigned ticket number.
                    </p>

                </div>


                <!-- Card 2 -->

                <div
                    class="group bg-white rounded-2xl border border-slate-200 p-8 shadow-sm hover:shadow-xl hover:-translate-y-1 transition duration-300">

                    <div
                        class="w-14 h-14 rounded-2xl bg-blue-50 flex items-center justify-center mb-6 group-hover:bg-[#005fbf] transition">

                        <svg xmlns="http://www.w3.org/2000/svg"
                            class="h-7 w-7 text-[#005fbf] group-hover:text-white transition"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                            stroke-width="1.8">

                            <path stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />

                        </svg>

                    </div>

                    <h3 class="font-bold text-xl text-[#1E293B]">
                        Transparent Processing
                    </h3>

                    <p class="mt-3 text-sm text-[#64748B] leading-relaxed">
                        View available ticket information and understand the current status of your violation record.
                    </p>

                </div>


                <!-- Card 3 -->

                <div
                    class="group bg-white rounded-2xl border border-slate-200 p-8 shadow-sm hover:shadow-xl hover:-translate-y-1 transition duration-300">

                    <div
                        class="w-14 h-14 rounded-2xl bg-blue-50 flex items-center justify-center mb-6 group-hover:bg-[#005fbf] transition">

                        <svg xmlns="http://www.w3.org/2000/svg"
                            class="h-7 w-7 text-[#005fbf] group-hover:text-white transition"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                            stroke-width="1.8">

                            <path stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M13 10V3L4 14h7v7l9-11h-7z" />

                        </svg>

                    </div>

                    <h3 class="font-bold text-xl text-[#1E293B]">
                        Improved Public Service
                    </h3>

                    <p class="mt-3 text-sm text-[#64748B] leading-relaxed">
                        Supporting organized traffic enforcement and more accessible public information.
                    </p>

                </div>

            </div>

        </div>

    </section>


    <!-- ========================================================= -->
    <!-- TICKET SEARCH -->
    <!-- ========================================================= -->

    <section id="ticket-search" class="py-16 px-6 bg-white">

        <div class="max-w-4xl mx-auto">

            <div class="relative overflow-hidden rounded-3xl bg-[#005fbf] shadow-xl">

                <!-- Background decoration -->

                <div class="absolute -right-20 -top-20 w-64 h-64 rounded-full bg-white/10">
                </div>

                <div class="absolute -left-20 -bottom-32 w-72 h-72 rounded-full bg-white/5">
                </div>


                <div class="relative p-8 sm:p-12 text-center">

                    <div class="mx-auto w-14 h-14 rounded-2xl bg-white/15 flex items-center justify-center mb-5">

                        <svg xmlns="http://www.w3.org/2000/svg"
                            class="w-7 h-7 text-white"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                            stroke-width="1.8">

                            <path stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />

                        </svg>

                    </div>


                    <h2 class="text-2xl sm:text-3xl font-bold text-white">
                        Check Your Traffic Violation
                    </h2>


                    <p class="mt-3 text-blue-100 max-w-xl mx-auto">
                        Enter your ticket number to view available traffic violation information.
                    </p>


                    <form action="#" method="GET"
                        class="mt-8 flex flex-col sm:flex-row gap-3 max-w-xl mx-auto">

                        <input
                            type="text"
                            name="ticket_number"
                            placeholder="Enter Ticket Number"
                            autocomplete="off"
                            class="flex-1 rounded-xl border-0 px-5 py-3.5 text-sm text-[#1E293B] shadow-sm focus:outline-none focus:ring-4 focus:ring-white/20">

                        <button
                            type="submit"
                            class="bg-white text-[#005fbf] px-7 py-3.5 rounded-xl font-bold text-sm hover:bg-blue-50 transition shadow-sm">

                            Search Ticket

                        </button>

                    </form>


                    <p class="mt-4 text-xs text-blue-100">
                        Please enter the ticket number exactly as indicated on your citation.
                    </p>

                </div>

            </div>

        </div>

    </section>


    <!-- ========================================================= -->
    <!-- POSO PHOTO GALLERY -->
    <!-- ========================================================= -->

    <section id="gallery" class="py-16 sm:py-20 px-6 bg-[#F5F7FB]">

        <div class="max-w-6xl mx-auto">

            <!-- Heading -->

            <div class="text-center max-w-2xl mx-auto mb-10">

                <span class="text-xs font-bold uppercase tracking-[0.2em] text-[#005fbf]">
                    Photo Gallery
                </span>

                <h2 class="mt-2 text-3xl font-bold text-[#1E293B]">
                    POSO Tarlac City
                </h2>

                <p class="mt-3 text-[#64748B]">
                    Learn more about the Public Order and Safety Office through our photo gallery.
                </p>

            </div>


            <!-- Gallery -->

            <div class="max-w-5xl mx-auto">

                <div class="relative bg-white rounded-3xl shadow-xl overflow-hidden border border-slate-200">

                    <!-- Main Image -->

                    <div class="relative h-[280px] sm:h-[400px] lg:h-[500px] bg-slate-900 overflow-hidden">

                        <img
                            id="posoGalleryImage"
                            src="{{ asset('images/poso2.jpg') }}"
                            alt="POSO Tarlac City"
                            class="gallery-transition gallery-visible absolute inset-0 w-full h-full object-cover">


                        <!-- Dark gradient -->

                        <div class="absolute inset-x-0 bottom-0 h-32 bg-gradient-to-t from-black/60 to-transparent pointer-events-none">
                        </div>


                        <!-- Counter -->

                        <div class="absolute bottom-5 left-5">

                            <div class="bg-black/50 backdrop-blur-sm text-white px-4 py-2 rounded-full text-xs font-medium">

                                <span id="posoGalleryCounter">
                                    1 / 9
                                </span>

                            </div>

                        </div>


                        <!-- Previous -->

                        <button
                            type="button"
                            id="galleryPrev"
                            aria-label="Previous image"
                            class="absolute left-4 top-1/2 -translate-y-1/2 w-11 h-11 rounded-full bg-black/40 hover:bg-black/60 text-white backdrop-blur-sm flex items-center justify-center transition">

                            <svg xmlns="http://www.w3.org/2000/svg"
                                class="w-5 h-5"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                                stroke-width="2">

                                <path stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M15 19l-7-7 7-7" />

                            </svg>

                        </button>


                        <!-- Next -->

                        <button
                            type="button"
                            id="galleryNext"
                            aria-label="Next image"
                            class="absolute right-4 top-1/2 -translate-y-1/2 w-11 h-11 rounded-full bg-black/40 hover:bg-black/60 text-white backdrop-blur-sm flex items-center justify-center transition">

                            <svg xmlns="http://www.w3.org/2000/svg"
                                class="w-5 h-5"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                                stroke-width="2">

                                <path stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M9 5l7 7-7 7" />

                            </svg>

                        </button>

                    </div>


                    <!-- Thumbnails -->

                    <div class="p-4 sm:p-5 bg-white">

                        <div
                            id="galleryThumbnails"
                            class="grid grid-cols-5 sm:grid-cols-9 gap-2">

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </section>


    <!-- ========================================================= -->
    <!-- HOW IT WORKS -->
    <!-- ========================================================= -->

    <section id="how-it-works" class="py-16 sm:py-20 px-6 bg-white">

        <div class="max-w-6xl mx-auto">

            <div class="text-center max-w-2xl mx-auto mb-12">

                <span class="text-xs font-bold uppercase tracking-[0.2em] text-[#005fbf]">
                    Simple Process
                </span>

                <h2 class="mt-2 text-3xl font-bold text-[#1E293B]">
                    How It Works
                </h2>

                <p class="mt-3 text-[#64748B]">
                    TrafficEnforceNet organizes traffic violation information through a straightforward digital
                    workflow.
                </p>

            </div>


            <div class="grid grid-cols-2 lg:grid-cols-4 gap-5">


                <!-- STEP 1 -->

                <div class="relative">

                    <div
                        class="bg-[#F5F7FB] rounded-2xl p-6 text-center border border-slate-200 h-full">

                        <div
                            class="mx-auto w-14 h-14 rounded-2xl bg-[#005fbf] text-white flex items-center justify-center mb-5">

                            <svg xmlns="http://www.w3.org/2000/svg"
                                class="h-7 w-7"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                                stroke-width="2">

                                <path stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />

                            </svg>

                        </div>

                        <div class="text-[#005fbf] font-extrabold text-sm">
                            STEP 01
                        </div>

                        <h3 class="mt-2 font-bold text-[#1E293B]">
                            Violation Recorded
                        </h3>

                        <p class="mt-2 text-xs sm:text-sm text-[#64748B]">
                            A traffic violation is recorded by an authorized enforcer.
                        </p>

                    </div>

                </div>


                <!-- STEP 2 -->

                <div class="relative">

                    <div
                        class="bg-[#F5F7FB] rounded-2xl p-6 text-center border border-slate-200 h-full">

                        <div
                            class="mx-auto w-14 h-14 rounded-2xl bg-[#005fbf] text-white flex items-center justify-center mb-5">

                            <svg xmlns="http://www.w3.org/2000/svg"
                                class="h-7 w-7"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                                stroke-width="2">

                                <path stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />

                            </svg>

                        </div>

                        <div class="text-[#005fbf] font-extrabold text-sm">
                            STEP 02
                        </div>

                        <h3 class="mt-2 font-bold text-[#1E293B]">
                            Ticket Issued
                        </h3>

                        <p class="mt-2 text-xs sm:text-sm text-[#64748B]">
                            Citation information is entered into the system for organized record keeping.
                        </p>

                    </div>

                </div>


                <!-- STEP 3 -->

                <div class="relative">

                    <div
                        class="bg-[#F5F7FB] rounded-2xl p-6 text-center border border-slate-200 h-full">

                        <div
                            class="mx-auto w-14 h-14 rounded-2xl bg-[#005fbf] text-white flex items-center justify-center mb-5">

                            <svg xmlns="http://www.w3.org/2000/svg"
                                class="h-7 w-7"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                                stroke-width="2">

                                <path stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />

                            </svg>

                        </div>

                        <div class="text-[#005fbf] font-extrabold text-sm">
                            STEP 03
                        </div>

                        <h3 class="mt-2 font-bold text-[#1E293B]">
                            Status Checked
                        </h3>

                        <p class="mt-2 text-xs sm:text-sm text-[#64748B]">
                            The public may check available ticket information using the ticket number.
                        </p>

                    </div>

                </div>


                <!-- STEP 4 -->

                <div class="relative">

                    <div
                        class="bg-[#F5F7FB] rounded-2xl p-6 text-center border border-slate-200 h-full">

                        <div
                            class="mx-auto w-14 h-14 rounded-2xl bg-[#005fbf] text-white flex items-center justify-center mb-5">

                            <svg xmlns="http://www.w3.org/2000/svg"
                                class="h-7 w-7"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                                stroke-width="2">

                                <path stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />

                            </svg>

                        </div>

                        <div class="text-[#005fbf] font-extrabold text-sm">
                            STEP 04
                        </div>

                        <h3 class="mt-2 font-bold text-[#1E293B]">
                            Case Resolution
                        </h3>

                        <p class="mt-2 text-xs sm:text-sm text-[#64748B]">
                            Traffic violation records can be updated as the case progresses toward resolution.
                        </p>

                    </div>

                </div>

            </div>

        </div>

    </section>


    <!-- ========================================================= -->
    <!-- ABOUT POSO -->
    <!-- ========================================================= -->

    <section id="about" class="py-16 sm:py-20 px-6 bg-[#F5F7FB]">

        <div class="max-w-5xl mx-auto">

            <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">

                <div class="grid md:grid-cols-5">

                    <!-- Blue Side -->

                    <div class="md:col-span-2 bg-[#005fbf] p-8 sm:p-10 text-white flex flex-col justify-center">

                        <div class="w-14 h-14 rounded-2xl bg-white/15 flex items-center justify-center mb-6">

                            <svg xmlns="http://www.w3.org/2000/svg"
                                class="w-7 h-7"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                                stroke-width="1.8">

                                <path stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M3 21h18M5 21V7l7-4 7 4v14M9 21v-6h6v6M9 10h.01M15 10h.01" />

                            </svg>

                        </div>

                        <span class="text-xs uppercase tracking-[0.2em] text-blue-200 font-bold">
                            About
                        </span>

                        <h2 class="mt-2 text-3xl font-bold">
                            POSO Tarlac City
                        </h2>

                    </div>


                    <!-- Content -->

                    <div class="md:col-span-3 p-8 sm:p-10">

                        <p class="text-[#64748B] leading-relaxed">

                            TrafficEnforceNet supports the
                            <strong class="text-[#1E293B]">
                                Public Order and Safety Office (POSO) of Tarlac City
                            </strong>
                            by providing a digital platform for organized traffic violation management and improved
                            public service delivery.

                        </p>


                        <p class="mt-5 text-[#64748B] leading-relaxed">

                            The system is designed to help authorized personnel maintain organized traffic violation
                            records while providing the public with a convenient way to access available ticket
                            information.

                        </p>


                        <div class="mt-7 flex flex-wrap gap-3">

                            <span class="px-4 py-2 rounded-full bg-blue-50 text-[#005fbf] text-xs font-semibold">
                                Traffic Management
                            </span>

                            <span class="px-4 py-2 rounded-full bg-blue-50 text-[#005fbf] text-xs font-semibold">
                                Digital Records
                            </span>

                            <span class="px-4 py-2 rounded-full bg-blue-50 text-[#005fbf] text-xs font-semibold">
                                Public Service
                            </span>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </section>


    <!-- ========================================================= -->
    <!-- FOOTER -->
    <!-- ========================================================= -->

    <footer class="bg-[#003F7F] text-white">

        <div class="max-w-7xl mx-auto px-6 py-10">

            <div class="grid md:grid-cols-2 gap-8 items-center">


                <!-- Branding -->

                <div class="text-center md:text-left">

                    <div class="flex items-center justify-center md:justify-start gap-3">

                        <div
                            class="w-10 h-10 rounded-full bg-white flex items-center justify-center overflow-hidden">

                            <img
                                src="{{ asset('images/logo/logo.png') }}"
                                alt="TrafficEnforceNet Logo"
                                class="w-full h-full object-contain">

                        </div>

                        <div>

                            <div class="font-bold text-lg">
                                TrafficEnforceNet
                            </div>

                            <div class="text-xs text-blue-200">
                                POSO Tarlac City
                            </div>

                        </div>

                    </div>


                    <p class="mt-4 text-sm text-blue-100 max-w-md mx-auto md:mx-0 leading-relaxed">

                        A digital platform supporting organized traffic violation management and improved public
                        service delivery.

                    </p>

                </div>


                <!-- Contact -->

                <div class="flex flex-col items-center md:items-end gap-4">

                    <!-- Facebook -->

                    <a
                        href="https://www.facebook.com/share/1CB9zxxzdu/"
                        target="_blank"
                        rel="noopener noreferrer"
                        class="flex items-center gap-3 hover:text-blue-200 transition">

                        <svg xmlns="http://www.w3.org/2000/svg"
                            class="w-5 h-5"
                            viewBox="0 0 24 24"
                            fill="currentColor">

                            <path
                                d="M24 12.073C24 5.405 18.627 0 12 0S0 5.405 0 12.073c0 6.025 4.388 11.024 10.125 11.928v-8.437H7.078v-3.491h3.047V9.41c0-3.017 1.792-4.688 4.533-4.688 1.312 0 2.686.236 2.686.236v2.977H15.83c-1.491 0-1.956.93-1.956 1.886v2.252h3.328l-.532 3.491h-2.796V24C19.612 23.097 24 18.098 24 12.073z" />

                        </svg>

                        <span class="text-sm font-medium">
                            Tarlac City - POSO
                        </span>

                    </a>


                    <!-- Phone -->

                    <a
                        href="tel:09301646092"
                        class="flex items-center gap-3 hover:text-blue-200 transition">

                        <svg xmlns="http://www.w3.org/2000/svg"
                            class="w-5 h-5"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                            stroke-width="1.8">

                            <path stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M3 5a2 2 0 012-2h2.28a2 2 0 011.94 1.515l.58 2.32a2 2 0 01-.45 1.84L8.09 9.91a16 16 0 006 6l1.235-1.26a2 2 0 011.84-.45l2.32.58A2 2 0 0121 16.72V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />

                        </svg>

                        <span class="text-sm font-medium">
                            0930 164 6092
                        </span>

                    </a>


                    <!-- Location -->

                    <div class="flex items-center gap-3 text-center md:text-right">

                        <svg xmlns="http://www.w3.org/2000/svg"
                            class="w-5 h-5 flex-shrink-0"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                            stroke-width="1.8">

                            <path stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M17.657 16.657L13.414 20.9a2 2 0 01-2.828 0l-4.243-4.243a8 8 0 1111.314 0z" />

                            <path stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />

                        </svg>

                        <span class="text-sm text-blue-100">
                            San Sebastian, Tarlac City,<br class="sm:hidden">
                            Tarlac, Philippines, 2300
                        </span>

                    </div>

                </div>

            </div>


            <!-- Bottom -->

            <div class="border-t border-white/10 mt-8 pt-6 text-center">

                <p class="text-xs text-blue-200">
                    © {{ date('Y') }} TrafficEnforceNet. All rights reserved.
                </p>

            </div>

        </div>

    </footer>


    <!-- ========================================================= -->
    <!-- GALLERY JAVASCRIPT -->
    <!-- ========================================================= -->

    <script>
        document.addEventListener('DOMContentLoaded', function() {

            const images = [
                "{{ asset('images/POSO.1.jpg') }}",
                "{{ asset('images/poso3.jpg') }}",
                "{{ asset('images/poso4.jpg') }}",
                "{{ asset('images/poso6.jpg') }}",
                "{{ asset('images/poso7.jpg') }}",
                "{{ asset('images/poso8.jpg') }}",
                "{{ asset('images/poso9.jpg') }}",
                "{{ asset('images/poso10.jpg') }}"
            ];


            const galleryImage =
                document.getElementById('posoGalleryImage');

            const galleryCounter =
                document.getElementById('posoGalleryCounter');

            const thumbnails =
                document.getElementById('galleryThumbnails');

            const previousButton =
                document.getElementById('galleryPrev');

            const nextButton =
                document.getElementById('galleryNext');


            let currentIndex = 0;

            let autoSlide;


            /* =====================================================
               CREATE THUMBNAILS
            ===================================================== */

            images.forEach(function(image, index) {

                const button = document.createElement('button');

                button.type = 'button';

                button.className =
                    'relative h-14 sm:h-16 rounded-lg overflow-hidden border-2 border-transparent transition duration-200 thumbnail-inactive';

                button.setAttribute(
                    'aria-label',
                    'View gallery image ' + (index + 1)
                );


                const thumbnail =
                    document.createElement('img');

                thumbnail.src = image;

                thumbnail.alt =
                    'POSO gallery thumbnail ' + (index + 1);

                thumbnail.className =
                    'w-full h-full object-cover';


                button.appendChild(thumbnail);

                thumbnails.appendChild(button);


                button.addEventListener('click', function() {

                    showImage(index);

                    restartAutoSlide();

                });

            });


            /* =====================================================
               SHOW IMAGE
            ===================================================== */

            function showImage(index) {

                currentIndex = index;

                galleryImage.classList.remove('gallery-visible');

                galleryImage.classList.add('gallery-fade');


                setTimeout(function() {

                    galleryImage.src = images[currentIndex];

                    galleryImage.classList.remove('gallery-fade');

                    galleryImage.classList.add('gallery-visible');

                }, 180);


                galleryCounter.textContent =
                    (currentIndex + 1) + ' / ' + images.length;


                updateThumbnails();

            }


            /* =====================================================
               UPDATE THUMBNAILS
            ===================================================== */

            function updateThumbnails() {

                const buttons =
                    thumbnails.querySelectorAll('button');


                buttons.forEach(function(button, index) {

                    if (index === currentIndex) {

                        button.classList.add('thumbnail-active');

                        button.classList.remove('thumbnail-inactive');

                    } else {

                        button.classList.remove('thumbnail-active');

                        button.classList.add('thumbnail-inactive');

                    }

                });

            }


            /* =====================================================
               NEXT
            ===================================================== */

            function nextImage() {

                currentIndex =
                    (currentIndex + 1) % images.length;

                showImage(currentIndex);

            }


            /* =====================================================
               PREVIOUS
            ===================================================== */

            function previousImage() {

                currentIndex =
                    (currentIndex - 1 + images.length) % images.length;

                showImage(currentIndex);

            }


            /* =====================================================
               BUTTON EVENTS
            ===================================================== */

            nextButton.addEventListener('click', function() {

                nextImage();

                restartAutoSlide();

            });


            previousButton.addEventListener('click', function() {

                previousImage();

                restartAutoSlide();

            });


            /* =====================================================
               AUTO SLIDE
            ===================================================== */

            function startAutoSlide() {

                autoSlide = setInterval(function() {

                    nextImage();

                }, 5000);

            }


            function restartAutoSlide() {

                clearInterval(autoSlide);

                startAutoSlide();

            }


            /* =====================================================
               INITIALIZE
            ===================================================== */

            updateThumbnails();

            startAutoSlide();


            /* =====================================================
               PAUSE SLIDESHOW WHEN HOVERING
            ===================================================== */

            const gallery =
                document.getElementById('posoGalleryImage').parentElement;


            gallery.addEventListener('mouseenter', function() {

                clearInterval(autoSlide);

            });


            gallery.addEventListener('mouseleave', function() {

                startAutoSlide();

            });

        });
    </script>

</body>

</html>