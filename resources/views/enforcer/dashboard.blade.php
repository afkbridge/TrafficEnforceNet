@extends('layouts.enforcer')

@section('title', 'Enforcer Dashboard')

@section('content')

<div class="min-h-screen bg-[#F5F7FB] pb-24">

    <!-- MOBILE HEADER -->
    <div class="bg-gradient-to-br from-[#1D5FBF] to-[#2E77E6] text-white px-5 pt-8 pb-8 rounded-b-[30px]">

        <div class="flex justify-between items-center mb-6">

            <div class="flex items-center gap-3">

                <div class="w-10 h-10 rounded-xl bg-white/20 flex items-center justify-center">

                    <svg class="w-6 h-6"
                         fill="none"
                         stroke="currentColor"
                         viewBox="0 0 24 24">

                        <path stroke-width="2"
                              stroke-linecap="round"
                              d="M4 6h16M4 12h16M4 18h16"/>

                    </svg>

                </div>


                <h1 class="font-bold text-lg">
                    TrafficEnforceNet
                </h1>

            </div>


            <!-- ONLINE STATUS -->

            <div class="bg-white/20 px-3 py-1 rounded-full text-xs flex items-center gap-2">

                <span class="w-2 h-2 bg-green-400 rounded-full"></span>

                Online

            </div>

        </div>



        <!-- ENFORCER PROFILE CARD -->

        <div class="bg-white/10 backdrop-blur rounded-3xl p-4 flex gap-4 items-center">


            <div class="w-16 h-16 rounded-full bg-white/20 flex items-center justify-center text-xl font-bold">

                {{ strtoupper(substr(auth()->user()->name,0,2)) }}

            </div>


            <div>

                <h2 class="font-bold text-lg">

                    {{ auth()->user()->name }}

                </h2>


                <p class="text-blue-100 text-sm">

                    Traffic Enforcement Officer

                </p>


                <span class="inline-block mt-2 bg-white/20 px-3 py-1 rounded-full text-xs">

                    Badge No.
                    {{ auth()->user()->enforcer_id ?? 'N/A' }}

                </span>


            </div>


        </div>


    </div>





    <!-- TODAY STAT CARD -->

    <div class="px-5 -mt-5">


        <div class="bg-white rounded-3xl shadow-md p-5 flex justify-between items-center">


            <div>

                <p class="text-xs text-gray-500 uppercase font-semibold">

                    Today's Issued Tickets

                </p>


                <h2 class="text-4xl font-bold text-[#0B2545] mt-2">

                    {{ $todayTickets ?? 0 }}

                </h2>


                <span class="inline-block mt-2 bg-blue-100 text-blue-700 px-3 py-1 rounded-full text-xs">

                    This Week:
                    {{ $weeklyTickets ?? 0 }}

                </span>


            </div>



            <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-blue-400 to-blue-700 flex items-center justify-center">


                <svg class="w-8 h-8 text-white"
                     fill="none"
                     stroke="currentColor"
                     viewBox="0 0 24 24">

                    <path stroke-width="2"
                          d="M3 8a2 2 0 012-2h14a2 2 0 012 2v2a2 2 0 010 4v2a2 2 0 01-2 2H5a2 2 0 01-2-2v-2a2 2 0 010-4V8z"/>

                </svg>


            </div>


        </div>


    </div>





    <!-- QUICK ACTIONS -->

    <div class="px-5 mt-6">


        <h3 class="text-xs font-bold text-gray-500 uppercase mb-3">

            Quick Actions

        </h3>



        <div class="grid grid-cols-2 gap-4">


            <!-- ISSUE -->

            <a href="#"
               class="bg-white rounded-2xl p-4 shadow-sm border">

                <div class="w-12 h-12 rounded-xl bg-blue-600 flex items-center justify-center text-white mb-3">


                    +

                </div>


                <p class="font-semibold text-sm">

                    Issue Ticket

                </p>


                <span class="text-xs text-gray-500">

                    Record violation

                </span>


            </a>





            <!-- OCR -->

            <a href="#"
               class="bg-white rounded-2xl p-4 shadow-sm border">


                <div class="w-12 h-12 rounded-xl bg-green-600 flex items-center justify-center text-white mb-3">


                    OCR

                </div>


                <p class="font-semibold text-sm">

                    Scan Ticket

                </p>


                <span class="text-xs text-gray-500">

                    Convert image data

                </span>


            </a>





            <!-- GPS -->

            <a href="#"
               class="bg-white rounded-2xl p-4 shadow-sm border">


                <div class="w-12 h-12 rounded-xl bg-orange-500 flex items-center justify-center text-white mb-3">


                    📍

                </div>


                <p class="font-semibold text-sm">

                    Capture Location

                </p>


                <span class="text-xs text-gray-500">

                    GPS coordinates

                </span>


            </a>





            <!-- SYNC -->

            <a href="#"
               class="bg-white rounded-2xl p-4 shadow-sm border">


                <div class="w-12 h-12 rounded-xl bg-purple-600 flex items-center justify-center text-white mb-3">


                    ↻

                </div>


                <p class="font-semibold text-sm">

                    Sync Data

                </p>


                <span class="text-xs text-gray-500">

                    Offline records

                </span>


            </a>



        </div>


    </div>






    <!-- RECENT TICKETS -->

    <div class="px-5 mt-7">


        <h3 class="text-xs font-bold text-gray-500 uppercase mb-3">

            Recent Tickets

        </h3>



        <div class="bg-white rounded-2xl p-5 text-center text-gray-400 text-sm">


            No tickets issued today.


        </div>


    </div>



</div>





<!-- MOBILE BOTTOM NAV -->

<div class="fixed bottom-0 left-0 right-0 bg-white border-t shadow-lg h-20 flex justify-around items-center">


    <a class="text-blue-600 text-xs flex flex-col items-center">

        🏠

        <span>
            Home
        </span>

    </a>



    <a class="text-gray-400 text-xs flex flex-col items-center">

        🎫

        <span>
            Tickets
        </span>

    </a>



    <a class="w-14 h-14 -mt-8 bg-blue-600 rounded-full flex items-center justify-center text-white text-3xl shadow-lg">

        +

    </a>




    <a class="text-gray-400 text-xs flex flex-col items-center">

        📍

        <span>
            Map
        </span>

    </a>



    <a class="text-gray-400 text-xs flex flex-col items-center">

        👤

        <span>
            Profile
        </span>

    </a>



</div>


@endsection