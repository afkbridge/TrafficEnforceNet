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
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            class="w-12 h-12 text-[#1D5FBF]"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                            stroke-width="1.8"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0z"
                            />
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M4.5 20.25a7.5 7.5 0 0115 0"
                            />
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

                        {{-- Full Name --}}
                        <div class="flex items-center gap-4">

                            <div class="w-10 h-10 rounded-2xl bg-blue-50 flex items-center justify-center shrink-0">
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    class="w-5 h-5 text-[#1D5FBF]"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0z"
                                    />
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M4.5 20.25a7.5 7.5 0 0115 0"
                                    />
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


                        {{-- Username --}}
                        <div class="flex items-center gap-4">

                            <div class="w-10 h-10 rounded-2xl bg-blue-50 flex items-center justify-center shrink-0">
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    class="w-5 h-5 text-[#1D5FBF]"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0z"
                                    />
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M4.5 20.25a7.5 7.5 0 0115 0"
                                    />
                                </svg>
                            </div>

                            <div class="min-w-0">
                                <p class="text-xs text-gray-500">
                                    Username
                                </p>

                                <p class="text-sm font-semibold text-[#1F2937] truncate">
                                    {{ $user->username ?? $user->name }}
                                </p>
                            </div>

                        </div>


                        {{-- Badge Number --}}
                        @if (isset($user->badge_number))

                            <div class="flex items-center gap-4">

                                <div class="w-10 h-10 rounded-2xl bg-blue-50 flex items-center justify-center shrink-0">
                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        class="w-5 h-5 text-[#1D5FBF]"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke="currentColor"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            d="M9 12h6m-6 4h6m2.25-12.75h-9A2.25 2.25 0 005.25 5.5v13A2.25 2.25 0 007.5 20.75h9a2.25 2.25 0 002.25-2.25v-13a2.25 2.25 0 00-2.25-2.25z"
                                        />
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
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    class="w-5 h-5 text-[#1D5FBF]"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M20.25 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-9A3.375 3.375 0 004.5 11.625v2.625m15.75 0a3.375 3.375 0 01-3.375 3.375h-9A3.375 3.375 0 014.5 14.25m15.75 0H4.5"
                                    />
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


            {{-- ========================================================= --}}
            {{-- PASSWORD SUCCESS MESSAGE --}}
            {{-- Placed OUTSIDE the collapsible section so it remains visible --}}
            {{-- after the Change Password section automatically closes. --}}
            {{-- ========================================================= --}}
            @if (session('password_success'))

                <div class="mt-5 rounded-2xl border border-green-200 bg-green-50 px-4 py-4 shadow-sm">

                    <div class="flex items-start gap-3">

                        {{-- Success Icon --}}
                        <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-green-100">

                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                class="h-5 w-5 text-green-600"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                                stroke-width="2"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M5 13l4 4L19 7"
                                />
                            </svg>

                        </div>

                        {{-- Success Text --}}
                        <div class="min-w-0">

                            <p class="text-sm font-bold text-green-800">
                                Success!
                            </p>

                            <p class="mt-0.5 text-sm text-green-700">
                                {{ session('password_success') }}
                            </p>

                        </div>

                    </div>

                </div>

            @endif


            {{-- Settings --}}
            <div class="mt-5">

                <div class="bg-white rounded-3xl shadow-md overflow-hidden">

                    {{-- Settings Header --}}
                    <div class="px-6 py-5 border-b border-gray-100">

                        <h3 class="text-sm font-bold uppercase tracking-wide text-gray-500">
                            Settings
                        </h3>

                    </div>


                    {{-- Change Password Button --}}
                    <button
                        type="button"
                        onclick="toggleChangePassword()"
                        class="w-full px-6 py-5 flex items-center justify-between text-left hover:bg-gray-50 active:bg-gray-100 transition"
                    >

                        <div class="flex items-center gap-4">

                            <div class="w-10 h-10 rounded-2xl bg-blue-50 flex items-center justify-center shrink-0">

                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    class="w-5 h-5 text-[#1D5FBF]"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="1.8"
                                        d="M16.5 10.5V6.75a4.5 4.5 0 00-9 0v3.75m-.75 0h10.5A2.25 2.25 0 0119.5 12.75v6A2.25 2.25 0 0117.25 21H6.75a2.25 2.25 0 01-2.25-2.25v-6a2.25 2.25 0 012.25-2.25z"
                                    />
                                </svg>

                            </div>

                            <div>

                                <p class="text-sm font-semibold text-[#1F2937]">
                                    Change Password
                                </p>

                                <p class="text-xs text-gray-500 mt-0.5">
                                    Update your account password
                                </p>

                            </div>

                        </div>


                        <svg
                            id="passwordArrow"
                            xmlns="http://www.w3.org/2000/svg"
                            class="w-5 h-5 text-gray-400 transition-transform"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M19 9l-7 7-7-7"
                            />
                        </svg>

                    </button>


                    {{-- Change Password Form --}}
                    <div
                        id="changePasswordSection"
                        class="hidden border-t border-gray-100 px-6 py-6"
                    >

                        {{-- ========================================================= --}}
                        {{-- ERROR MESSAGE --}}
                        {{-- ========================================================= --}}
                        @if ($errors->any())

                            <div class="mb-5 rounded-2xl border border-red-200 bg-red-50 px-4 py-4 shadow-sm">

                                <div class="flex items-start gap-3">

                                    {{-- Error Icon --}}
                                    <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-red-100">

                                        <svg
                                            xmlns="http://www.w3.org/2000/svg"
                                            class="h-5 w-5 text-red-600"
                                            fill="none"
                                            viewBox="0 0 24 24"
                                            stroke="currentColor"
                                            stroke-width="2"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                d="M12 9v3.75m0 3.75h.007M10.29 3.86l-7.5 13A1.875 1.875 0 004.42 19.75h15.16a1.875 1.875 0 001.63-2.89l-7.5-13a1.875 1.875 0 00-3.42 0z"
                                            />
                                        </svg>

                                    </div>


                                    {{-- Error Text --}}
                                    <div class="min-w-0">

                                        <p class="text-sm font-bold text-red-800">
                                            Unable to change password
                                        </p>

                                        <ul class="mt-1 space-y-1 text-sm text-red-700">

                                            @foreach ($errors->all() as $error)

                                                <li>
                                                    {{ $error }}
                                                </li>

                                            @endforeach

                                        </ul>

                                    </div>

                                </div>

                            </div>

                        @endif


                        <form
                            method="POST"
                            action="{{ route('enforcer.password.update') }}"
                        >

                            @csrf
                            @method('PUT')


                            {{-- ========================================================= --}}
                            {{-- CURRENT PASSWORD --}}
                            {{-- ========================================================= --}}
                            <div class="mb-4">

                                <label
                                    for="current_password"
                                    class="block text-sm font-semibold text-gray-700 mb-2"
                                >
                                    Current Password
                                </label>

                                <div class="relative">

                                    <input
                                        type="password"
                                        id="current_password"
                                        name="current_password"
                                        required
                                        autocomplete="current-password"
                                        class="w-full rounded-2xl border border-gray-200 bg-gray-50 px-4 py-3 pr-12 text-sm text-gray-800 outline-none focus:border-[#1D5FBF] focus:ring-2 focus:ring-blue-100"
                                        placeholder="Enter current password"
                                    >

                                    {{-- Eye Button --}}
                                    <button
                                        type="button"
                                        onclick="togglePasswordVisibility('current_password', this)"
                                        class="absolute inset-y-0 right-0 flex w-12 items-center justify-center text-gray-400 hover:text-[#1D5FBF] transition"
                                        aria-label="Show current password"
                                        title="Show password for 3 seconds"
                                    >

                                        <svg
                                            xmlns="http://www.w3.org/2000/svg"
                                            class="w-5 h-5"
                                            fill="none"
                                            viewBox="0 0 24 24"
                                            stroke="currentColor"
                                            stroke-width="1.8"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                d="M2.036 12.322a1.012 1.012 0 010-.644C3.423 7.51 7.36 5 12 5c4.64 0 8.577 2.51 9.964 6.678.06.18.06.366 0 .644C20.577 16.49 16.64 19 12 19c-4.64 0-8.577-2.51-9.964-6.678z"
                                            />
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"
                                            />
                                        </svg>

                                    </button>

                                </div>

                            </div>


                            {{-- ========================================================= --}}
                            {{-- NEW PASSWORD --}}
                            {{-- ========================================================= --}}
                            <div class="mb-4">

                                <label
                                    for="password"
                                    class="block text-sm font-semibold text-gray-700 mb-2"
                                >
                                    New Password
                                </label>

                                <div class="relative">

                                    <input
                                        type="password"
                                        id="password"
                                        name="password"
                                        required
                                        minlength="8"
                                        autocomplete="new-password"
                                        class="w-full rounded-2xl border border-gray-200 bg-gray-50 px-4 py-3 pr-12 text-sm text-gray-800 outline-none focus:border-[#1D5FBF] focus:ring-2 focus:ring-blue-100"
                                        placeholder="Enter new password"
                                    >

                                    {{-- Eye Button --}}
                                    <button
                                        type="button"
                                        onclick="togglePasswordVisibility('password', this)"
                                        class="absolute inset-y-0 right-0 flex w-12 items-center justify-center text-gray-400 hover:text-[#1D5FBF] transition"
                                        aria-label="Show new password"
                                        title="Show password for 3 seconds"
                                    >

                                        <svg
                                            xmlns="http://www.w3.org/2000/svg"
                                            class="w-5 h-5"
                                            fill="none"
                                            viewBox="0 0 24 24"
                                            stroke="currentColor"
                                            stroke-width="1.8"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                d="M2.036 12.322a1.012 1.012 0 010-.644C3.423 7.51 7.36 5 12 5c4.64 0 8.577 2.51 9.964 6.678.06.18.06.366 0 .644C20.577 16.49 16.64 19 12 19c-4.64 0-8.577-2.51-9.964-6.678z"
                                            />
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"
                                            />
                                        </svg>

                                    </button>

                                </div>

                                <p class="mt-1.5 text-xs text-gray-500">
                                    Password must be at least 8 characters.
                                </p>

                            </div>


                            {{-- ========================================================= --}}
                            {{-- CONFIRM NEW PASSWORD --}}
                            {{-- ========================================================= --}}
                            <div class="mb-5">

                                <label
                                    for="password_confirmation"
                                    class="block text-sm font-semibold text-gray-700 mb-2"
                                >
                                    Confirm New Password
                                </label>

                                <div class="relative">

                                    <input
                                        type="password"
                                        id="password_confirmation"
                                        name="password_confirmation"
                                        required
                                        minlength="8"
                                        autocomplete="new-password"
                                        class="w-full rounded-2xl border border-gray-200 bg-gray-50 px-4 py-3 pr-12 text-sm text-gray-800 outline-none focus:border-[#1D5FBF] focus:ring-2 focus:ring-blue-100"
                                        placeholder="Re-enter new password"
                                    >

                                    {{-- Eye Button --}}
                                    <button
                                        type="button"
                                        onclick="togglePasswordVisibility('password_confirmation', this)"
                                        class="absolute inset-y-0 right-0 flex w-12 items-center justify-center text-gray-400 hover:text-[#1D5FBF] transition"
                                        aria-label="Show password confirmation"
                                        title="Show password for 3 seconds"
                                    >

                                        <svg
                                            xmlns="http://www.w3.org/2000/svg"
                                            class="w-5 h-5"
                                            fill="none"
                                            viewBox="0 0 24 24"
                                            stroke="currentColor"
                                            stroke-width="1.8"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                d="M2.036 12.322a1.012 1.012 0 010-.644C3.423 7.51 7.36 5 12 5c4.64 0 8.577 2.51 9.964 6.678.06.18.06.366 0 .644C20.577 16.49 16.64 19 12 19c-4.64 0-8.577-2.51-9.964-6.678z"
                                            />
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"
                                            />
                                        </svg>

                                    </button>

                                </div>

                            </div>


                            {{-- ========================================================= --}}
                            {{-- UPDATE BUTTON --}}
                            {{-- ========================================================= --}}
                            <button
                                type="submit"
                                class="w-full rounded-2xl bg-[#1D5FBF] px-5 py-3.5 text-sm font-semibold text-white shadow-sm hover:bg-[#174f9f] active:scale-[0.98] transition"
                            >
                                Change Password
                            </button>

                        </form>

                    </div>

                </div>

            </div>


            {{-- Logout --}}
            <div class="mt-5">

                <form
                    method="POST"
                    action="{{ route('logout') }}"
                >

                    @csrf

                    <button
                        type="submit"
                        class="w-full bg-white rounded-3xl shadow-md px-6 py-4 flex items-center justify-center gap-3 text-red-600 font-semibold hover:bg-red-50 active:scale-[0.98] transition"
                    >

                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            class="w-5 h-5"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                            stroke-width="2"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15"
                            />
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M18 15l3-3m0 0l-3-3m3 3H9"
                            />
                        </svg>

                        Log Out

                    </button>

                </form>

            </div>

        </div>

    </div>


    {{-- Bottom Navigation --}}
    @include('enforcer.partials.bottom-nav')


    <script>

        // =======================================================
        // TOGGLE CHANGE PASSWORD SECTION
        // =======================================================

        function toggleChangePassword() {

            const section = document.getElementById('changePasswordSection');
            const arrow = document.getElementById('passwordArrow');

            if (!section || !arrow) {
                return;
            }

            section.classList.toggle('hidden');
            arrow.classList.toggle('rotate-180');
        }


        // =======================================================
        // PASSWORD VISIBILITY
        // Shows the password for 3 seconds, then hides it again.
        // =======================================================

        const passwordVisibilityTimers = {};

        function togglePasswordVisibility(inputId, button) {

            const input = document.getElementById(inputId);

            if (!input) {
                return;
            }

            // Clear the existing timer if the user clicks again.
            if (passwordVisibilityTimers[inputId]) {
                clearTimeout(passwordVisibilityTimers[inputId]);
            }

            // Show the password.
            input.type = 'text';

            button.setAttribute('aria-label', 'Hide password');

            // Automatically hide the password after 3 seconds.
            passwordVisibilityTimers[inputId] = setTimeout(function () {

                input.type = 'password';

                button.setAttribute('aria-label', 'Show password');

                delete passwordVisibilityTimers[inputId];

            }, 3000);
        }


        // =======================================================
        // PAGE LOAD
        // =======================================================

        document.addEventListener('DOMContentLoaded', function () {

            const section = document.getElementById('changePasswordSection');
            const arrow = document.getElementById('passwordArrow');

            if (!section || !arrow) {
                return;
            }


            // ===================================================
            // CHECK PASSWORD SUCCESS
            // ===================================================

            const passwordChangedSuccessfully = @json(session('password_success'));


            // ===================================================
            // CHECK PASSWORD ERRORS
            // ===================================================

            const hasPasswordErrors = @json($errors->any());


            // ===================================================
            // IF THERE IS A VALIDATION ERROR
            //
            // Keep Change Password OPEN so the user can
            // correct the information.
            // ===================================================

            if (hasPasswordErrors) {

                section.classList.remove('hidden');

                arrow.classList.add('rotate-180');
            }


            // ===================================================
            // IF PASSWORD WAS SUCCESSFULLY CHANGED
            //
            // Keep Change Password CLOSED.
            // The green success notification remains visible
            // because it is outside the collapsible section.
            // ===================================================

            if (passwordChangedSuccessfully) {

                section.classList.add('hidden');

                arrow.classList.remove('rotate-180');
            }

        });

    </script>

@endsection