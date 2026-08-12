
@extends('layouts.enforcer')

@section('title', 'My Profile')

@section('content')

<div class="min-h-screen bg-[#F5F7FB] pb-28">

    {{-- Header --}}
    @include('enforcer.partials.header')

    {{-- Profile Content --}}
    <div class="px-5 -mt-5">

        {{-- Profile Card --}}
        <div class="bg-white rounded-3xl shadow-md overflow-hidden">

            {{-- Profile Top --}}
            <div class="bg-gradient-to-r from-[#005FBF] to-[#1D5FBF] px-6 py-8 text-center">

                {{-- Profile Icon --}}
                <div class="mx-auto w-24 h-24 rounded-full bg-white flex items-center justify-center shadow-lg">

                    <svg xmlns="http://www.w3.org/2000/svg"
                         class="w-12 h-12 text-[#1D5FBF]"
                         fill="none"
                         viewBox="0 0 24 24"
                         stroke="currentColor"
                         stroke-width="1.8">

                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0z" />

                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              d="M4.5 20.25a7.5 7.5 0 0115 0" />

                    </svg>

                </div>

                <h2 class="mt-4 text-xl font-bold text-white">
                    {{ $user->first_name ?? $user->name }}
                    {{ $user->last_name ?? '' }}
                </h2>

                <p class="mt-1 text-sm text-blue-100">
                    POSO Enforcer
                </p>

            </div>


            {{-- Account Information --}}
            <div class="p-6">

                <h3 class="text-sm font-bold uppercase tracking-wide text-gray-500 mb-4">
                    Account Information
                </h3>

                <div class="space-y-4">

                    {{-- Name --}}
                    <div class="flex items-center gap-4">

                        <div class="w-10 h-10 rounded-2xl bg-blue-50 flex items-center justify-center shrink-0">

                            <svg xmlns="http://www.w3.org/2000/svg"
                                 class="w-5 h-5 text-[#1D5FBF]"
                                 fill="none"
                                 viewBox="0 0 24 24"
                                 stroke="currentColor">

                                <path stroke-linecap="round"
                                      stroke-linejoin="round"
                                      d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0z" />

                                <path stroke-linecap="round"
                                      stroke-linejoin="round"
                                      d="M4.5 20.25a7.5 7.5 0 0115 0" />

                            </svg>

                        </div>

                        <div class="min-w-0">
                            <p class="text-xs text-gray-500">
                                Full Name
                            </p>

                            <p class="text-sm font-semibold text-[#1F2937] truncate">
                                {{ $user->first_name ?? $user->name }}
                                {{ $user->last_name ?? '' }}
                            </p>
                        </div>

                    </div>


                    {{-- Email --}}
                    <div class="flex items-center gap-4">

                        <div class="w-10 h-10 rounded-2xl bg-blue-50 flex items-center justify-center shrink-0">

                            <svg xmlns="http://www.w3.org/2000/svg"
                                 class="w-5 h-5 text-[#1D5FBF]"
                                 fill="none"
                                 viewBox="0 0 24 24"
                                 stroke="currentColor">

                                <path stroke-linecap="round"
                                      stroke-linejoin="round"
                                      d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25H4.5a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0l-9.75 6.75L2.25 6.75" />

                            </svg>

                        </div>

                        <div class="min-w-0">
                            <p class="text-xs text-gray-500">
                                Email
                            </p>

                            <p class="text-sm font-semibold text-[#1F2937] truncate">
                                {{ $user->email }}
                            </p>
                        </div>

                    </div>


                    {{-- Badge Number --}}
                    @if(isset($user->badge_number))
                    <div class="flex items-center gap-4">

                        <div class="w-10 h-10 rounded-2xl bg-blue-50 flex items-center justify-center shrink-0">

                            <svg xmlns="http://www.w3.org/2000/svg"
                                 class="w-5 h-5 text-[#1D5FBF]"
                                 fill="none"
                                 viewBox="0 0 24 24"
                                 stroke="currentColor">

                                <path stroke-linecap="round"
                                      stroke-linejoin="round"
                                      d="M9 12h6m-6 4h6m2.25-12.75h-9A2.25 2.25 0 005.25 5.5v13A2.25 2.25 0 007.5 20.75h9a2.25 2.25 0 002.25-2.25v-13a2.25 2.25 0 00-2.25-2.25z" />

                            </svg>

                        </div>

                        <div>
                            <p class="text-xs text-gray-500">
                                Badge Number
                            </p>

                            <p class="text-sm font-semibold text-[#1F2937]">
                                {{ $user->badge_number }}
                            </p>
                        </div>

                    </div>
                    @endif


                    {{-- Position --}}
                    <div class="flex items-center gap-4">

                        <div class="w-10 h-10 rounded-2xl bg-blue-50 flex items-center justify-center shrink-0">

                            <svg xmlns="http://www.w3.org/2000/svg"
                                 class="w-5 h-5 text-[#1D5FBF]"
                                 fill="none"
                                 viewBox="0 0 24 24"
                                 stroke="currentColor">

                                <path stroke-linecap="round"
                                      stroke-linejoin="round"
                                      d="M20.25 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-9A3.375 3.375 0 004.5 11.625v2.625m15.75 0a3.375 3.375 0 01-3.375 3.375h-9A3.375 3.375 0 014.5 14.25m15.75 0H4.5" />

                            </svg>

                        </div>

                        <div>
                            <p class="text-xs text-gray-500">
                                Position
                            </p>

                            <p class="text-sm font-semibold text-[#1F2937]">
                                POSO Enforcer
                            </p>
                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- Logout --}}
        <div class="mt-5">

            <form method="POST" action="{{ route('logout') }}">
                @csrf

                <button
                    type="submit"
                    class="w-full bg-white rounded-3xl shadow-md px-6 py-4 flex items-center justify-center gap-3 text-red-600 font-semibold hover:bg-red-50 active:scale-[0.98] transition">

                    <svg xmlns="http://www.w3.org/2000/svg"
                         class="w-5 h-5"
                         fill="none"
                         viewBox="0 0 24 24"
                         stroke="currentColor"
                         stroke-width="2">

                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15" />

                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              d="M18 15l3-3m0 0l-3-3m3 3H9" />

                    </svg>

                    Log Out

                </button>

            </form>

        </div>

    </div>

</div>

{{-- Bottom Navigation --}}
@include('enforcer.partials.bottom-nav')

@endsection

