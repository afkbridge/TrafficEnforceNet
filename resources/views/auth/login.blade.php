
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>TrafficEnforceNet | Office Login</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        /* Hide the built-in Microsoft Edge password reveal and clear buttons */
        input[type="password"]::-ms-reveal,
        input[type="password"]::-ms-clear {
            display: none !important;
        }

        /* Hide browser credential autofill controls where supported */
        input[type="password"]::-webkit-credentials-auto-fill-button {
            visibility: hidden;
            display: none !important;
            pointer-events: none;
        }

        #password {
            padding-right: 3rem;
        }

        /* Keep the custom eye button above the password input */
        #togglePassword {
            z-index: 2;
        }
    </style>
</head>

<body class="bg-[#F1F5F9]">

    <div class="min-h-screen flex items-center justify-center px-6">
        <div class="w-full max-w-md">

            <!-- Header -->
            <div class="text-center mb-8">
                <div class="mx-auto w-20 h-20 rounded-full bg-[#005fbf] flex items-center justify-center">
                    <img
                        src="{{ asset('images/logo/logo.png') }}"
                        alt="TrafficEnforceNet Logo"
                        style="width: 90px; height: 90px; object-fit: contain;"
                    >
                </div>

                <h1 class="mt-5 text-3xl font-bold text-[#1E293B]">
                    TrafficEnforceNet
                </h1>

                <p class="mt-2 text-[#64748B]">
                    Office Authentication Portal
                </p>

                <p class="text-sm text-[#64748B]">
                    Public Order and Safety Office
                    <br>
                    Tarlac City
                </p>
            </div>

            <!-- Login Card -->
            <div class="bg-white rounded-2xl shadow-lg p-8">

                <!-- Login Error Notification -->
                @if ($errors->any())
                    <div class="mb-5 rounded-lg border border-red-200 bg-red-50 px-4 py-3">
                        <div class="flex items-start gap-3">
                            <div class="flex-shrink-0 text-red-600 font-bold">
                                !
                            </div>

                            <div>
                                <p class="text-sm font-semibold text-red-700">
                                    Login failed
                                </p>

                                <p class="text-sm text-red-600 mt-1">
                                    {{ $errors->first() }}
                                </p>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Success Message -->
                @if (session('status'))
                    <div class="mb-4 rounded-lg border border-green-200 bg-green-50 px-4 py-3">
                        <p class="text-sm text-green-600">
                            {{ session('status') }}
                        </p>
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}" id="loginForm">
                    @csrf

                    <!-- CAAC Location -->
                    <input type="hidden" name="latitude" id="latitude">
                    <input type="hidden" name="longitude" id="longitude">

                    <!-- Username -->
                    <div>
                        <label for="username" class="block text-sm font-medium text-[#1E293B]">
                            Username
                        </label>

                        <input
                            type="text"
                            name="username"
                            id="username"
                            value="{{ old('username') }}"
                            autofocus
                            autocomplete="username"
                            class="mt-2 w-full rounded-lg border-gray-300 focus:border-[#005fbf] focus:ring-[#005fbf]"
                        >

                        @error('username')
                            <p class="text-sm text-red-500 mt-2">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div class="mt-5">
                        <label for="password" class="block text-sm font-medium text-[#1E293B]">
                            Password
                        </label>

                        <div class="relative mt-2">
                            <input
                                type="password"
                                name="password"
                                id="password"
                                autocomplete="current-password"
                                data-1p-ignore="true"
                                class="w-full rounded-lg border-gray-300 pr-12 focus:border-[#005fbf] focus:ring-[#005fbf]"
                            >

                            <!-- Custom password toggle -->
                            <button
                                type="button"
                                id="togglePassword"
                                aria-label="Show password"
                                aria-pressed="false"
                                class="absolute inset-y-0 right-0 flex items-center px-3 text-gray-500 hover:text-[#005fbf] focus:outline-none focus:ring-2 focus:ring-inset focus:ring-[#005fbf] rounded-r-lg"
                            >
                                <svg
                                    id="eyeIcon"
                                    xmlns="http://www.w3.org/2000/svg"
                                    width="22"
                                    height="22"
                                    viewBox="0 0 24 24"
                                    fill="none"
                                    stroke="currentColor"
                                    stroke-width="2"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    aria-hidden="true"
                                >
                                    <path d="M2 12s3.6-7 10-7 10 7 10 7-3.6 7-10 7-10-7-10-7Z"/>
                                    <circle cx="12" cy="12" r="3"/>
                                </svg>
                            </button>
                        </div>

                        @error('password')
                            <p class="text-sm text-red-500 mt-2">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Remember -->
                    <div class="mt-5 flex items-center">
                        <input
                            type="checkbox"
                            name="remember"
                            id="remember"
                            class="rounded text-[#005fbf]"
                        >

                        <label for="remember" class="ml-2 text-sm text-[#64748B]">
                            Remember me
                        </label>
                    </div>

                    <!-- Sign In Button -->
                    <button
                        type="submit"
                        id="loginButton"
                        class="mt-7 w-full bg-[#005fbf] text-white py-3 rounded-xl font-semibold hover:bg-[#004a99] transition"
                    >
                        Sign In
                    </button>
                </form>
            </div>

            <p class="text-center text-xs text-[#64748B] mt-6">
                Restricted access. Authorized personnel only.
            </p>

        </div>
    </div>

    <!-- Password Toggle and CAAC Browser Location -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const form = document.getElementById('loginForm');
            const loginButton = document.getElementById('loginButton');

            const latitudeInput = document.getElementById('latitude');
            const longitudeInput = document.getElementById('longitude');

            // Password visibility toggle
            const passwordInput = document.getElementById('password');
            const togglePassword = document.getElementById('togglePassword');
            const eyeIcon = document.getElementById('eyeIcon');

            let hidePasswordTimeout = null;

            function hidePassword() {
                clearTimeout(hidePasswordTimeout);
                hidePasswordTimeout = null;

                passwordInput.type = 'password';

                // Restore the normal eye icon
                eyeIcon.innerHTML = `
                    <path d="M2 12s3.6-7 10-7 10 7 10 7-3.6 7-10 7-10-7-10-7Z"/>
                    <circle cx="12" cy="12" r="3"/>
                `;

                togglePassword.setAttribute('aria-label', 'Show password');
                togglePassword.setAttribute('aria-pressed', 'false');
            }

            togglePassword.addEventListener('click', function () {
                if (passwordInput.type === 'password') {
                    clearTimeout(hidePasswordTimeout);

                    // Reveal the password
                    passwordInput.type = 'text';

                    // Change the same icon to eye-off
                    eyeIcon.innerHTML = `
                        <path d="M3 3l18 18"/>
                        <path d="M10.6 10.6a2 2 0 0 0 2.8 2.8"/>
                        <path d="M9.9 5.2A11 11 0 0 1 12 5c6.4 0 10 7 10 7a15 15 0 0 1-3 3.8"/>
                        <path d="M6.6 6.6C3.6 8.5 2 12 2 12s3.6 7 10 7a10 10 0 0 0 4-.8"/>
                    `;

                    togglePassword.setAttribute('aria-label', 'Hide password');
                    togglePassword.setAttribute('aria-pressed', 'true');

                    // Automatically hide after 5 seconds
                    hidePasswordTimeout = setTimeout(function () {
                        hidePassword();
                    }, 5000);
                } else {
                    // Hide immediately when clicked again
                    hidePassword();
                }
            });

            // CAAC location capture — existing behavior retained
            let locationReady = false;
            let locationRequested = false;

            function requestLocation() {
                if (locationRequested) {
                    return;
                }

                locationRequested = true;

                if (!navigator.geolocation) {
                    locationReady = true;
                    return;
                }

                navigator.geolocation.getCurrentPosition(
                    function (position) {
                        latitudeInput.value = position.coords.latitude;
                        longitudeInput.value = position.coords.longitude;

                        locationReady = true;
                    },
                    function () {
                        latitudeInput.value = '';
                        longitudeInput.value = '';

                        locationReady = true;
                    },
                    {
                        enableHighAccuracy: true,
                        timeout: 10000,
                        maximumAge: 300000
                    }
                );
            }

            requestLocation();

            form.addEventListener('submit', function (event) {
                if (locationReady) {
                    return;
                }

                event.preventDefault();

                loginButton.disabled = true;
                loginButton.textContent = 'Getting location...';

                const checkLocation = setInterval(function () {
                    if (!locationReady) {
                        return;
                    }

                    clearInterval(checkLocation);

                    loginButton.disabled = false;
                    loginButton.textContent = 'Sign In';

                    form.submit();
                }, 100);
            });
        });
    </script>

</body>
</html>
