@extends('layouts.bplo')

@section('title', 'Settings')

@section('content')

    <div class="min-h-screen bg-[#F5F7FB] px-4 py-6 sm:px-6">


        {{-- Main Settings Container --}}
        <div class="mx-auto w-full max-w-3xl">

            {{-- Page Header --}}
            <div class="mb-5">
                <div class="flex items-center gap-3">
                    <div
                        class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-blue-600 shadow-sm shadow-blue-200">
                        <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-.?" />
                        </svg>
                    </div>

                    <div>
                        <h1 class="text-xl font-bold text-gray-800">
                            Settings
                        </h1>
                        <p class="mt-0.5 text-sm text-gray-500">
                            Manage your BPLO account and password
                        </p>
                    </div>
                </div>
            </div>


            {{-- Success Message --}}
            @if (session('success'))
                <div class="mb-4 flex items-center gap-3 rounded-lg border border-green-200 bg-green-50 px-4 py-3">
                    <div class="flex h-7 w-7 flex-shrink-0 items-center justify-center rounded-full bg-green-100">
                        <svg class="h-4 w-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>

                    <p class="text-sm font-medium text-green-700">
                        {{ session('success') }}
                    </p>
                </div>
            @endif


            {{-- Validation Errors --}}
            @if ($errors->any())
                <div class="mb-4 rounded-lg border border-red-200 bg-red-50 px-4 py-3">
                    <div class="flex items-start gap-3">

                        <div class="flex h-7 w-7 flex-shrink-0 items-center justify-center rounded-full bg-red-100">
                            <svg class="h-4 w-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 9v2m0 4h.01M10.29 3.86l-7.82 13.5A1 1 0 003.34 19h17.32a1 1 0 001.37-1.64l-7.82-13.5a1 1 0 00-1.72 0z" />
                            </svg>
                        </div>

                        <div>
                            <p class="text-sm font-semibold text-red-700">
                                Please check the information entered.
                            </p>

                            <ul class="mt-1 list-disc pl-4 text-xs text-red-600">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>

                    </div>
                </div>
            @endif


            {{-- Account Information Card --}}
            <div class="mb-4 overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">

                {{-- Card Header --}}
                <div class="flex items-center justify-between border-b border-gray-100 px-5 py-3.5">

                    <div class="flex items-center gap-3">

                        <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-blue-50">
                            <svg class="h-5 w-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </div>

                        <div>
                            <h2 class="text-sm font-semibold text-gray-800">
                                Account Information
                            </h2>

                            <p class="text-xs text-gray-400">
                                Update your basic account details.
                            </p>
                        </div>

                    </div>

                </div>


                {{-- Account Form --}}
                <form method="POST" action="{{ route('bplo.settings.account') }}">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 gap-4 px-5 py-4 sm:grid-cols-2">

                        {{-- Full Name --}}
                        <div>
                            <label for="name" class="mb-1.5 block text-xs font-semibold text-gray-600">
                                Full Name
                            </label>

                            <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}"
                                required maxlength="255"
                                class="w-full rounded-lg border border-gray-200 bg-gray-50 px-3 py-2.5 text-sm text-gray-800 transition placeholder:text-gray-400 focus:border-blue-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-100">

                            @error('name')
                                <p class="mt-1 text-xs text-red-600">
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>


                        {{-- Username --}}
                        <div>
                            <label for="username" class="mb-1.5 block text-xs font-semibold text-gray-600">
                                Username
                            </label>

                            <input type="text" id="username" name="username"
                                value="{{ old('username', $user->username) }}" required maxlength="255"
                                autocomplete="username"
                                class="w-full rounded-lg border border-gray-200 bg-gray-50 px-3 py-2.5 text-sm text-gray-800 transition placeholder:text-gray-400 focus:border-blue-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-100">

                            @error('username')
                                <p class="mt-1 text-xs text-red-600">
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                    </div>


                    {{-- Account Footer --}}
                    <div class="flex justify-end border-t border-gray-100 bg-gray-50/50 px-5 py-3">

                        <button type="submit"
                            class="inline-flex items-center gap-2 rounded-lg bg-blue-600 px-4 py-2 text-xs font-semibold text-white shadow-sm transition hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>

                            Save Changes
                        </button>

                    </div>

                </form>

            </div>


            {{-- Change Password Card --}}
            <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">

                {{-- Card Header --}}
                <div class="flex items-center justify-between border-b border-gray-100 px-5 py-3.5">

                    <div class="flex items-center gap-3">

                        <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-blue-50">
                            <svg class="h-5 w-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 15v2m-6 4h12a2 2 0 002-2v-5a2 2 0 00-2-2H6a2 2 0 00-2 2v5a2 2 0 002 2zm10-9V7a4 4 0 00-8 0v3h8z" />
                            </svg>
                        </div>

                        <div>
                            <h2 class="text-sm font-semibold text-gray-800">
                                Change Password
                            </h2>

                            <p class="text-xs text-gray-400">
                                Keep your account credentials up to date.
                            </p>
                        </div>

                    </div>

                </div>


                {{-- Password Form --}}
                <form method="POST" action="{{ route('bplo.settings.password') }}">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 gap-4 px-5 py-4 sm:grid-cols-2">

                        {{-- Current Password --}}
                        <div class="sm:col-span-2">
                            <label for="current_password" class="mb-1.5 block text-xs font-semibold text-gray-600">
                                Current Password
                            </label>

                            <input type="password" id="current_password" name="current_password" required
                                autocomplete="current-password"
                                class="w-full rounded-lg border border-gray-200 bg-gray-50 px-3 py-2.5 text-sm text-gray-800 transition focus:border-blue-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-100">

                            @error('current_password')
                                <p class="mt-1 text-xs text-red-600">
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>


                        {{-- New Password --}}
                        <div>
                            <label for="password" class="mb-1.5 block text-xs font-semibold text-gray-600">
                                New Password
                            </label>

                            <input type="password" id="password" name="password" required minlength="8"
                                autocomplete="new-password"
                                class="w-full rounded-lg border border-gray-200 bg-gray-50 px-3 py-2.5 text-sm text-gray-800 transition focus:border-blue-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-100">

                            <p class="mt-1 text-[11px] text-gray-400">
                                Minimum 8 characters.
                            </p>

                            @error('password')
                                <p class="mt-1 text-xs text-red-600">
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>


                        {{-- Confirm Password --}}
                        <div>
                            <label for="password_confirmation" class="mb-1.5 block text-xs font-semibold text-gray-600">
                                Confirm New Password
                            </label>

                            <input type="password" id="password_confirmation" name="password_confirmation" required
                                minlength="8" autocomplete="new-password"
                                class="w-full rounded-lg border border-gray-200 bg-gray-50 px-3 py-2.5 text-sm text-gray-800 transition focus:border-blue-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-100">

                        </div>

                    </div>


                    {{-- Password Footer --}}
                    <div class="flex justify-end border-t border-gray-100 bg-gray-50/50 px-5 py-3">

                        <button type="submit"
                            class="inline-flex items-center gap-2 rounded-lg bg-blue-600 px-4 py-2 text-xs font-semibold text-white shadow-sm transition hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 15v2m-6 4h12a2 2 0 002-2v-5a2 2 0 00-2-2H6a2 2 0 00-2 2v5a2 2 0 002 2zm10-9V7a4 4 0 00-8 0v3h8z" />
                            </svg>

                            Change Password
                        </button>

                    </div>

                </form>

            </div>

        </div>


    </div>

@endsection
