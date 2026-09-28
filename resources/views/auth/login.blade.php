<!DOCTYPE html>

<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">


<title>
    TrafficEnforceNet | Office Login
</title>

@vite(['resources/css/app.css', 'resources/js/app.js'])


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


            <form
                method="POST"
                action="{{ route('login') }}"
                id="loginForm"
            >

                @csrf


                <!-- CAAC Location -->
                <input
                    type="hidden"
                    name="latitude"
                    id="latitude"
                >

                <input
                    type="hidden"
                    name="longitude"
                    id="longitude"
                >


                <!-- Username -->
                <div>

                    <label class="block text-sm font-medium text-[#1E293B]">
                        Username
                    </label>

                    <input
                        type="text"
                        name="username"
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

                    <label class="block text-sm font-medium text-[#1E293B]">
                        Password
                    </label>

                    <input
                        type="password"
                        name="password"
                        autocomplete="current-password"
                        class="mt-2 w-full rounded-lg border-gray-300 focus:border-[#005fbf] focus:ring-[#005fbf]"
                    >

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
                        class="rounded text-[#005fbf]"
                    >

                    <span class="ml-2 text-sm text-[#64748B]">
                        Remember me
                    </span>

                </div>


                <!-- Button -->
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


<!-- CAAC Browser Location -->
<script>

    document.addEventListener('DOMContentLoaded', function () {

        const form = document.getElementById('loginForm');
        const loginButton = document.getElementById('loginButton');

        const latitudeInput = document.getElementById('latitude');
        const longitudeInput = document.getElementById('longitude');

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
