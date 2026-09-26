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

        body {
            overflow-x: hidden;
        }

        /* ================================
           GALLERY
        ================================= */

        .gallery-card {
            background: #ffffff;
            border-radius: 1.25rem;
            box-shadow: 0 10px 40px rgba(15, 23, 42, 0.08);
            border: 1px solid #e2e8f0;
            overflow: hidden;
        }

        .gallery-main {
            position: relative;
            width: 100%;
            height: 300px;
            background: #0f172a;
            overflow: hidden;
        }

        @media (min-width: 640px) {
            .gallery-main {
                height: 400px;
            }
        }

        @media (min-width: 1024px) {
            .gallery-main {
                height: 500px;
            }
        }

        .gallery-main img {
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            opacity: 1;
            transform: scale(1);
            transition:
                opacity 0.45s ease,
                transform 0.55s ease;
        }

        .gallery-main img.gallery-fade {
            opacity: 0;
            transform: scale(1.025);
        }

        .gallery-main img.gallery-visible {
            opacity: 1;
            transform: scale(1);
        }

        .gallery-gradient {
            position: absolute;
            inset-inline: 0;
            bottom: 0;
            height: 120px;
            background: linear-gradient(to top,
                    rgba(0, 0, 0, 0.55),
                    transparent);
            pointer-events: none;
            z-index: 2;
        }

        .gallery-nav {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            width: 42px;
            height: 42px;
            border-radius: 9999px;
            background: rgba(0, 0, 0, 0.45);
            color: #ffffff;
            border: 1px solid rgba(255, 255, 255, 0.2);
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            backdrop-filter: blur(6px);
            transition: all 0.2s ease;
            z-index: 10;
        }

        .gallery-nav:hover {
            background: rgba(0, 95, 191, 0.9);
            transform: translateY(-50%) scale(1.05);
        }

        .gallery-nav:active {
            transform: translateY(-50%) scale(0.96);
        }

        .gallery-nav.prev {
            left: 14px;
        }

        .gallery-nav.next {
            right: 14px;
        }

        @media (min-width: 640px) {
            .gallery-nav {
                width: 46px;
                height: 46px;
            }

            .gallery-nav.prev {
                left: 20px;
            }

            .gallery-nav.next {
                right: 20px;
            }
        }

        .gallery-counter {
            position: absolute;
            bottom: 16px;
            left: 16px;
            background: rgba(0, 0, 0, 0.55);
            backdrop-filter: blur(6px);
            color: #ffffff;
            padding: 6px 13px;
            border-radius: 9999px;
            font-size: 12px;
            font-weight: 600;
            z-index: 10;
        }

        /* ================================
           THUMBNAILS
        ================================= */

        .gallery-thumbs {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 12px 14px;
            overflow-x: auto;
            background: #ffffff;
            scrollbar-width: thin;
            scrollbar-color: #94a3b8 transparent;
        }

        .gallery-thumbs::-webkit-scrollbar {
            height: 5px;
        }

        .gallery-thumbs::-webkit-scrollbar-track {
            background: transparent;
        }

        .gallery-thumbs::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 9999px;
        }

        .gallery-thumb {
            flex: 0 0 auto;
            width: 76px;
            height: 54px;
            padding: 0;
            border-radius: 8px;
            overflow: hidden;
            border: 2px solid transparent;
            opacity: 0.55;
            cursor: pointer;
            transition: all 0.2s ease;
            background: #e2e8f0;
        }

        @media (min-width: 640px) {
            .gallery-thumb {
                width: 86px;
                height: 60px;
            }
        }

        .gallery-thumb img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }

        .gallery-thumb:hover {
            opacity: 0.85;
        }

        .gallery-thumb.active {
            opacity: 1;
            border-color: #005fbf;
            box-shadow: 0 3px 10px rgba(0, 95, 191, 0.25);
            transform: translateY(-2px);
        }

        /* ================================
           FOOTER
        ================================= */

        .footer-link {
            display: flex;
            align-items: center;
            gap: 12px;
            color: #bfdbfe;
            text-decoration: none;
            transition: color 0.2s ease;
        }

        .footer-link:hover {
            color: #ffffff;
        }

        .footer-icon {
            width: 36px;
            height: 36px;
            border-radius: 9999px;
            background: rgba(255, 255, 255, 0.1);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            transition: background 0.2s ease;
        }

        .footer-link:hover .footer-icon {
            background: rgba(255, 255, 255, 0.2);
        }

        /* ================================
           MOBILE NAVIGATION
        ================================= */

        @media (max-width: 639px) {
            .mobile-nav-links {
                display: none;
            }
        }

        /* ================================
           POSO HERO LOGO
        ================================= */

        .poso-logo-circle {
            width: 300px;
            height: 300px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.96);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 18px;
            box-shadow: 0 12px 40px rgba(0, 0, 0, 0.18);
        }

        .poso-hero-logo {
            width: 290px;
            height: 290px;
            object-fit: contain;
            display: block;
        }

        @media (max-width: 639px) {
            .poso-logo-circle {
                width: 180px;
                height: 180px;
                padding: 14px;
            }

            .poso-hero-logo {
                width: 200px;
                height: 200px;
            }
        }
    </style>
</head>

<body class="bg-[#F5F7FB] text-[#1E293B] font-sans antialiased">

    <!-- ================================
         NAVBAR
    ================================= -->

    <header class="sticky top-0 z-50 bg-[#005fbf] text-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="flex items-center justify-between h-16">

                <!-- Brand -->
                <a href="/" class="flex items-center gap-2.5 min-w-0">

                    <div
                        class="w-8 h-8 sm:w-9 sm:h-9 rounded-full bg-white flex items-center justify-center overflow-hidden shadow-sm flex-shrink-0">

                        <img src="{{ asset('images/logo/logo.png') }}" alt="TrafficEnforceNet Logo"
                            class="w-[82%] h-[82%] object-contain">

                    </div>

                    <div class="min-w-0">

                        <div class="font-bold text-base sm:text-lg leading-tight tracking-tight">
                            TrafficEnforceNet
                        </div>

                        <div class="text-[9px] sm:text-[10px] text-blue-100 uppercase tracking-wider">
                            POSO Tarlac City
                        </div>

                    </div>

                </a>

                <!-- Navigation -->
                <nav class="hidden sm:flex items-center gap-5 lg:gap-7 text-sm font-medium">

                    <a href="#services" class="text-blue-50 hover:text-white transition">
                        Services
                    </a>

                    <a href="#ticket-status" class="text-blue-50 hover:text-white transition">
                        Ticket Status
                    </a>

                    <a href="#gallery" class="text-blue-50 hover:text-white transition">
                        Gallery
                    </a>

                    <a href="#how-it-works" class="text-blue-50 hover:text-white transition">
                        How It Works
                    </a>

                    <a href="#about" class="text-blue-50 hover:text-white transition">
                        About
                    </a>

                </nav>

            </div>

        </div>
    </header>


    <!-- ================================
         HERO
    ================================= -->

    <section class="relative overflow-hidden"
        style="
            background: linear-gradient(
                135deg,
                #003b73 0%,
                #005fbf 50%,
                #0072ce 100%
            );
            min-height: 150px;
        ">

        <div class="relative max-w-7xl mx-auto px-6 lg:px-12 h-full flex items-center py-20">

            <div class="grid lg:grid-cols-2 gap-16 lg:gap-24 items-center w-full">

                <!-- LEFT CONTENT -->
                <div class="text-white">

                    <br>

                    <div
                        class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white/10 border border-white/20 mb-8">

                        <span class="w-2 h-2 rounded-full bg-green-400"></span>

                        <span class="text-sm font-medium">
                            Traffic Management System
                        </span>

                    </div>

                    <p class="text-xl sm:text-2xl text-blue-100 leading-relaxed max-w-2xl mb-6">

                        A digital platform supporting the

                        <strong>
                            Public Order and Safety Office (POSO)
                        </strong>

                        of Tarlac City in organizing traffic violation records and improving public service delivery.

                    </p>

                    <p class="text-base sm:text-lg text-blue-100/80 leading-relaxed max-w-xl mb-10">

                        Access available traffic violation information, check ticket status, and learn how the system
                        supports a more organized traffic enforcement process.

                    </p>

                    <div class="flex flex-col sm:flex-row gap-4">

                        <a href="#ticket-status"
                            class="inline-flex items-center justify-center px-8 py-4 bg-white text-[#005fbf] font-bold rounded-xl shadow-lg hover:bg-blue-50 transition">
                            Check Ticket Status
                        </a>

                        <a href="#how-it-works"
                            class="inline-flex items-center justify-center px-8 py-4 border-2 border-white/40 text-white font-bold rounded-xl hover:bg-white/10 transition">
                            How It Works
                        </a>

                    </div>

                </div>


                <!-- RIGHT LOGO -->
                <div class="flex justify-center lg:justify-end">

                    <div class="poso-logo-circle">

                        <img src="{{ asset('images/logo/POSOlogo.png') }}" alt="POSO Tarlac City Logo"
                            class="poso-hero-logo">

                    </div>

                </div>

            </div>

        </div>

    </section>


    <!-- ================================
         SERVICES
    ================================= -->

    <section id="services" class="py-16 sm:py-10 px-6">

        <div class="max-w-6xl mx-auto">

            <div class="text-center max-w-2xl mx-auto mb-12">

                <span class="text-xs font-bold uppercase tracking-[0.2em] text-[#005fbf]">
                    Public Services
                </span>

                <h2 class="mt-2 text-3xl font-bold text-[#1E293B]">
                    Access Traffic Information
                </h2>

                <p class="mt-3 text-[#64748B]">
                    TrafficEnforceNet provides public-facing tools designed to make traffic violation information easier
                    to access.
                </p>

            </div>


            <div class="grid md:grid-cols-3 gap-6">

                <!-- Service 1 -->
                <div
                    class="group bg-white rounded-2xl border border-slate-200 p-8 shadow-sm hover:shadow-xl hover:-translate-y-1 transition duration-300">

                    <div
                        class="w-14 h-14 rounded-2xl bg-blue-50 flex items-center justify-center mb-6 group-hover:bg-[#005fbf] transition">

                        <svg xmlns="http://www.w3.org/2000/svg"
                            class="h-7 w-7 text-[#005fbf] group-hover:text-white transition" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">

                            <path stroke-linecap="round" stroke-linejoin="round"
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


                <!-- Service 2 -->
                <div
                    class="group bg-white rounded-2xl border border-slate-200 p-8 shadow-sm hover:shadow-xl hover:-translate-y-1 transition duration-300">

                    <div
                        class="w-14 h-14 rounded-2xl bg-blue-50 flex items-center justify-center mb-6 group-hover:bg-[#005fbf] transition">

                        <svg xmlns="http://www.w3.org/2000/svg"
                            class="h-7 w-7 text-[#005fbf] group-hover:text-white transition" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">

                            <path stroke-linecap="round" stroke-linejoin="round"
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


                <!-- Service 3 -->
                <div
                    class="group bg-white rounded-2xl border border-slate-200 p-8 shadow-sm hover:shadow-xl hover:-translate-y-1 transition duration-300">

                    <div
                        class="w-14 h-14 rounded-2xl bg-blue-50 flex items-center justify-center mb-6 group-hover:bg-[#005fbf] transition">

                        <svg xmlns="http://www.w3.org/2000/svg"
                            class="h-7 w-7 text-[#005fbf] group-hover:text-white transition" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">

                            <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z" />

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


    <!-- ================================
         TICKET SEARCH
    ================================= -->

    <section id="ticket-status" class="py-16 px-6 bg-white">

        <div class="max-w-4xl mx-auto">

            <!-- BLUE SEARCH CARD -->
            <div class="relative overflow-hidden rounded-3xl bg-[#005fbf] shadow-xl">

                <!-- Decorative circles -->
                <div class="absolute -right-20 -top-20 w-64 h-64 rounded-full bg-white/10"></div>

                <div class="absolute -left-20 -bottom-32 w-72 h-72 rounded-full bg-white/5"></div>


                <div class="relative p-8 sm:p-12 text-center">

                    <!-- Icon -->
                    <div class="mx-auto w-14 h-14 rounded-2xl bg-white/15 flex items-center justify-center mb-5">

                        <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7 text-white" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">

                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />

                        </svg>

                    </div>


                    <!-- Heading -->
                    <h2 class="text-2xl sm:text-3xl font-bold text-white">
                        Check Your Traffic Violation
                    </h2>

                    <p class="mt-3 text-blue-100 max-w-xl mx-auto">
                        Enter your ticket number to view available traffic violation information.
                    </p>


                    <!-- SEARCH FORM -->
                    <form action="{{ route('public.ticket.check') }}" method="GET"
                        class="w-full max-w-2xl mx-auto mt-7">

                        <div class="flex flex-col sm:flex-row items-center justify-center gap-3">

                            <!-- Ticket Number Input -->
                            <input type="text" name="ticket_number" placeholder="Enter Ticket Number"
                                autocomplete="off" value="{{ old('ticket_number') }}"
                                class="w-full sm:flex-1 sm:max-w-md rounded-xl bg-white border-2 border-white px-5 py-3.5 text-sm text-[#1E293B] placeholder-slate-400 shadow-lg focus:outline-none focus:ring-4 focus:ring-white/30">


                            <!-- Search Button -->
                            <button type="submit"
                                class="w-full sm:w-auto bg-white text-[#005fbf] px-7 py-3.5 rounded-xl font-bold text-sm hover:bg-blue-50 transition shadow-lg whitespace-nowrap">
                                Search Ticket
                            </button>

                        </div>

                    </form>


                    <!-- Helper Text -->
                    <p class="mt-4 text-xs text-blue-100">
                        Please enter the ticket number exactly as indicated on your citation.
                    </p>

                </div>

            </div>


            <!-- ================================
                 TICKET NOT FOUND
            ================================= -->

            @if (session('ticket_not_found'))
                <div id="ticket-not-found"
                    class="mt-6 bg-white rounded-3xl border border-red-200 shadow-md overflow-hidden">

                    <div class="p-6 sm:p-7">

                        <div class="flex items-start gap-4">

                            <!-- Error Icon -->
                            <div class="flex-shrink-0 w-11 h-11 rounded-xl bg-red-50 flex items-center justify-center">

                                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-red-600" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">

                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M12 9v3.75m0 3.75h.008M10.29 3.86l-7.06 12.25A2 2 0 005 19.1h14a2 2 0 001.73-2.99L13.67 3.86a2 2 0 00-3.38 0z" />

                                </svg>

                            </div>


                            <!-- Error Text -->
                            <div class="min-w-0">

                                <h3 class="font-bold text-red-700">
                                    Ticket Not Found
                                </h3>

                                <p class="mt-1 text-sm text-slate-600 leading-relaxed">
                                    {{ session('ticket_not_found') }}
                                </p>

                            </div>

                        </div>

                    </div>

                </div>
            @endif


            <!-- ================================
                 TICKET RESULT
            ================================= -->

            @if (session('ticket_result'))
                @php
                    $ticket = session('ticket_result');
                    $status = $ticket->status;
                @endphp

                <div id="ticket-result"
                    class="mt-6 bg-white rounded-3xl border border-slate-200 shadow-lg overflow-hidden">

                    <!-- Result Header -->
                    <div class="px-6 sm:px-8 py-6 border-b border-slate-200">

                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">

                            <div>

                                <p class="text-xs font-bold uppercase tracking-[0.15em] text-[#005fbf]">
                                    Ticket Information
                                </p>

                                <h3 class="mt-1 text-2xl font-bold text-[#1E293B] break-all">
                                    {{ $ticket->ticket_number }}
                                </h3>

                            </div>


                            {{-- Status Badge --}}
                            <span
                                class="inline-flex w-fit px-4 py-2 rounded-full text-xs font-bold
    {{ strtolower($status) === 'settled' ? 'bg-green-100 text-green-700' : 'bg-amber-100 text-amber-700' }}">
                                {{ ucfirst($status) }}
                            </span>

                        </div>

                    </div>


                    <!-- Result Details -->
                    <div class="p-6 sm:p-8">

                        <div class="grid sm:grid-cols-2 gap-x-8 gap-y-6">

                            <!-- Violation -->
                            <div>

                                <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">
                                    Violation
                                </p>

                                <p class="mt-1.5 text-sm font-semibold text-slate-800">
                                    {{ $ticket->violationType->name ?? 'Not available' }}
                                </p>

                            </div>


                            <!-- Date -->
                            <div>

                                <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">
                                    Date
                                </p>

                                <p class="mt-1.5 text-sm font-semibold text-slate-800">
                                    {{ $ticket->violation_date ? \Carbon\Carbon::parse($ticket->violation_date)->format('F d, Y') : 'Not available' }}
                                </p>

                            </div>


                            <!-- Time -->
                            <div>

                                <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">
                                    Time
                                </p>

                                <p class="mt-1.5 text-sm font-semibold text-slate-800">
                                    {{ $ticket->violation_time ? \Carbon\Carbon::parse($ticket->violation_time)->format('h:i A') : 'Not available' }}
                                </p>

                            </div>


                            <!-- Location -->
                            <div>

                                <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">
                                    Location
                                </p>

                                <p class="mt-1.5 text-sm font-semibold text-slate-800">
                                    {{ $ticket->location ?: 'Not available' }}
                                </p>

                            </div>

                        </div>

                    </div>


                    <!-- Privacy Notice -->
                    <div class="px-6 sm:px-8 py-4 bg-slate-50 border-t border-slate-200">

                        <p class="text-xs text-slate-500 leading-relaxed">
                            For privacy, personal driver information and evidence files are not displayed through the
                            public ticket inquiry.
                        </p>

                    </div>

                </div>
            @endif

        </div>

    </section>


    <!-- ================================
         PHOTO GALLERY
    ================================= -->

    <section id="gallery" class="py-16 sm:py-10 px-6 bg-[#F5F7FB]">

        <div class="max-w-5xl mx-auto">

            <!-- Gallery Heading -->
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


            <!-- Gallery Card -->
            <div class="gallery-card">

                <!-- MAIN GALLERY IMAGE -->
                <div class="gallery-main" id="galleryMain">

                    <img id="posoGalleryImage" src="{{ asset('images/poso2.jpg') }}" alt="POSO Tarlac City"
                        class="gallery-visible">


                    <div class="gallery-gradient"></div>


                    <!-- GALLERY COUNTER -->
                    <div class="gallery-counter">

                        <span id="posoGalleryCounter">
                            1 / 9
                        </span>

                    </div>


                    <!-- PREVIOUS BUTTON -->
                    <button type="button" class="gallery-nav prev" id="galleryPrev" aria-label="Previous image">

                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">

                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />

                        </svg>

                    </button>


                    <!-- NEXT BUTTON -->
                    <button type="button" class="gallery-nav next" id="galleryNext" aria-label="Next image">

                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">

                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />

                        </svg>

                    </button>

                </div>


                <!-- GALLERY THUMBNAILS -->
                <div class="gallery-thumbs" id="galleryThumbnails">
                </div>

            </div>

        </div>

    </section>


    <!-- ================================
         HOW IT WORKS
    ================================= -->

    <section id="how-it-works" class="py-16 sm:py-10 px-6 bg-white">

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

                <!-- Step 1 -->
                <div class="bg-[#F5F7FB] rounded-2xl p-6 text-center border border-slate-200 h-full">

                    <div
                        class="mx-auto w-14 h-14 rounded-2xl bg-[#005fbf] text-white flex items-center justify-center mb-5">

                        <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="2">

                            <path stroke-linecap="round" stroke-linejoin="round"
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


                <!-- Step 2 -->
                <div class="bg-[#F5F7FB] rounded-2xl p-6 text-center border border-slate-200 h-full">

                    <div
                        class="mx-auto w-14 h-14 rounded-2xl bg-[#005fbf] text-white flex items-center justify-center mb-5">

                        <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="2">

                            <path stroke-linecap="round" stroke-linejoin="round"
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


                <!-- Step 3 -->
                <div class="bg-[#F5F7FB] rounded-2xl p-6 text-center border border-slate-200 h-full">

                    <div
                        class="mx-auto w-14 h-14 rounded-2xl bg-[#005fbf] text-white flex items-center justify-center mb-5">

                        <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="2">

                            <path stroke-linecap="round" stroke-linejoin="round"
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


                <!-- Step 4 -->
                <div class="bg-[#F5F7FB] rounded-2xl p-6 text-center border border-slate-200 h-full">

                    <div
                        class="mx-auto w-14 h-14 rounded-2xl bg-[#005fbf] text-white flex items-center justify-center mb-5">

                        <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="2">

                            <path stroke-linecap="round" stroke-linejoin="round"
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

    </section>


    <!-- ================================
         ABOUT
    ================================= -->

    <section id="about" class="py-16 sm:py-10 px-6 bg-[#F5F7FB]">

        <div class="max-w-5xl mx-auto">

            <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">

                <div class="grid md:grid-cols-5">

                    <div class="md:col-span-2 bg-[#005fbf] p-8 sm:p-10 text-white flex flex-col justify-center">

                        <div class="w-14 h-14 rounded-2xl bg-white/15 flex items-center justify-center mb-6">

                            <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">

                                <path stroke-linecap="round" stroke-linejoin="round"
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


    <!-- ================================
         FOOTER
    ================================= -->

    <footer class="bg-[#005fbf] text-white">

        <div class="max-w-7xl mx-auto px-6 py-12">

            <div class="grid md:grid-cols-2 gap-10 items-start">

                <!-- Footer Brand -->
                <div class="text-center md:text-left">

                    <div class="flex items-center justify-center md:justify-start gap-3">

                        <div
                            class="w-9 h-9 rounded-full bg-white flex items-center justify-center overflow-hidden shadow-sm">

                            <img src="{{ asset('images/logo/logo.png') }}" alt="TrafficEnforceNet Logo"
                                class="w-[82%] h-[82%] object-contain">

                        </div>

                        <div>

                            <div class="font-bold text-lg leading-tight">
                                TrafficEnforceNet
                            </div>

                            <div class="text-xs text-blue-100">
                                POSO Tarlac City
                            </div>

                        </div>

                    </div>


                    <p class="mt-5 text-sm text-blue-100 max-w-md mx-auto md:mx-0 leading-relaxed">

                        A digital platform supporting organized traffic violation management and improved public service
                        delivery.

                    </p>

                </div>


                <!-- Footer Contact -->
                <div class="flex flex-col items-center md:items-end gap-4">

                    <!-- Facebook -->
                    <a href="https://www.facebook.com/share/1CB9zxxzdu/" target="_blank" rel="noopener noreferrer"
                        class="footer-link">

                        <span class="footer-icon">

                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                viewBox="0 0 24 24" fill="currentColor">

                                <path
                                    d="M24 12.073C24 5.405 18.627 0 12 0S0 5.405 0 12.073c0 6.025 4.388 11.024 10.125 11.928v-8.437H7.078v-3.491h3.047V9.41c0-3.017 1.792-4.688 4.533-4.688 1.312 0 2.686.236 2.686.236v2.977H15.83c-1.491 0-1.956.93-1.956 1.886v2.252h3.328l-.532 3.491h-2.796V24C19.612 23.097 24 18.098 24 12.073z" />

                            </svg>

                        </span>

                        <span class="text-sm font-medium">
                            Tarlac City – POSO
                        </span>

                    </a>


                    <!-- Phone -->
                    <a href="tel:09301646092" class="footer-link">

                        <span class="footer-icon">

                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">

                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M3 5a2 2 0 012-2h2.28a2 2 0 011.94 1.515l.58 2.32a2 2 0 01-.45 1.84L8.09 9.91a16 16 0 006 6l1.235-1.26a2 2 0 011.84-.45l2.32.58A2 2 0 0121 16.72V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />

                            </svg>

                        </span>

                        <span class="text-sm font-medium">
                            0930 164 6092
                        </span>

                    </a>


                    <!-- Address -->
                    <div class="footer-link" style="cursor: default;">

                        <span class="footer-icon">

                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">

                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M17.657 16.657L13.414 20.9a2 2 0 01-2.828 0l-4.243-4.243a8 8 0 1111.314 0z" />

                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />

                            </svg>

                        </span>

                        <span class="text-sm text-blue-100 text-left md:text-right">

                            San Sebastian, Tarlac City,<br>
                            Tarlac, Philippines 2300

                        </span>

                    </div>

                </div>

            </div>


            <!-- Copyright -->
            <div class="border-t border-white/15 mt-10 pt-6 text-center">

                <p class="text-xs text-blue-100">
                    © {{ date('Y') }} TrafficEnforceNet. All rights reserved.
                </p>

            </div>

        </div>

    </footer>


    <!-- ================================
         GALLERY JAVASCRIPT
    ================================= -->

    <script>
        document.addEventListener('DOMContentLoaded', function() {

            const images = [
                "{{ asset('images/poso2.jpg') }}",
                "{{ asset('images/poso1.jpg') }}",
                "{{ asset('images/poso3.jpg') }}",
                "{{ asset('images/poso4.jpg') }}",
                "{{ asset('images/poso6.jpg') }}",
                "{{ asset('images/poso7.jpg') }}",
                "{{ asset('images/poso8.jpg') }}",
                "{{ asset('images/poso9.jpg') }}",
                "{{ asset('images/poso10.jpg') }}"
            ];


            const mainImg =
                document.getElementById('posoGalleryImage');

            const counter =
                document.getElementById('posoGalleryCounter');

            const thumbsEl =
                document.getElementById('galleryThumbnails');

            const prevBtn =
                document.getElementById('galleryPrev');

            const nextBtn =
                document.getElementById('galleryNext');

            const mainArea =
                document.getElementById('galleryMain');


            let current = 0;
            let timer = null;
            let busy = false;


            /* ==============================
               BUILD THUMBNAILS
            ============================== */

            images.forEach((src, index) => {

                const button =
                    document.createElement('button');

                button.type = 'button';

                button.className =
                    'gallery-thumb' +
                    (index === 0 ? ' active' : '');

                button.setAttribute(
                    'aria-label',
                    'View image ' + (index + 1)
                );


                const img =
                    document.createElement('img');

                img.src = src;

                img.alt =
                    'POSO gallery image ' + (index + 1);

                img.loading = 'lazy';


                img.onerror = function() {

                    this.style.background = '#94a3b8';

                    this.alt = 'Image not found';

                };


                button.appendChild(img);

                thumbsEl.appendChild(button);


                button.addEventListener('click', function() {

                    if (index !== current) {
                        show(index);
                    }

                    restart();

                });

            });


            /* ==============================
               SHOW IMAGE
            ============================== */

            function show(index) {

                if (busy || index === current) {
                    return;
                }

                busy = true;

                current = index;


                mainImg.classList.remove(
                    'gallery-visible'
                );

                mainImg.classList.add(
                    'gallery-fade'
                );


                setTimeout(function() {

                    mainImg.src =
                        images[current];

                    mainImg.classList.remove(
                        'gallery-fade'
                    );

                    mainImg.classList.add(
                        'gallery-visible'
                    );

                    busy = false;

                }, 250);


                counter.textContent =
                    (current + 1) +
                    ' / ' +
                    images.length;


                const thumbnails =
                    thumbsEl.querySelectorAll(
                        '.gallery-thumb'
                    );


                thumbnails.forEach(function(
                    thumbnail,
                    i
                ) {

                    thumbnail.classList.toggle(
                        'active',
                        i === current
                    );

                });

            }


            /* ==============================
               NEXT / PREVIOUS
            ============================== */

            function next() {

                const nextIndex =
                    (current + 1) %
                    images.length;

                show(nextIndex);

            }


            function prev() {

                const previousIndex =
                    (current - 1 + images.length) %
                    images.length;

                show(previousIndex);

            }


            /* ==============================
               BUTTONS
            ============================== */

            nextBtn.addEventListener(
                'click',
                function() {

                    next();
                    restart();

                }
            );


            prevBtn.addEventListener(
                'click',
                function() {

                    prev();
                    restart();

                }
            );


            /* ==============================
               KEYBOARD CONTROLS
            ============================== */

            document.addEventListener(
                'keydown',
                function(event) {

                    if (event.key === 'ArrowRight') {

                        next();
                        restart();

                    }


                    if (event.key === 'ArrowLeft') {

                        prev();
                        restart();

                    }

                }
            );


            /* ==============================
               AUTOMATIC SLIDESHOW
            ============================== */

            function start() {

                clearInterval(timer);

                timer = setInterval(
                    function() {

                        next();

                    },
                    5000
                );

            }


            function restart() {

                clearInterval(timer);

                start();

            }


            /* ==============================
               PAUSE WHILE HOVERING
            ============================== */

            mainArea.addEventListener(
                'mouseenter',
                function() {

                    clearInterval(timer);

                }
            );


            mainArea.addEventListener(
                'mouseleave',
                function() {

                    start();

                }
            );


            /* ==============================
               INITIALIZE
            ============================== */

            counter.textContent =
                '1 / ' +
                images.length;

            start();

        });
    </script>

</body>

</html>
