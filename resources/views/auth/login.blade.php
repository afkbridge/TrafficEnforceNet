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


            <!-- Logo/Header -->

            <div class="text-center mb-8">


                <div class="mx-auto w-20 h-20 rounded-full 
bg-[#005fbf] flex items-center justify-center">


                    <img src="{{ asset('images/logo/logo.png') }}" alt="TrafficEnforceNet Logo"
                        style="width: 90px; height: 90px; object-fit: contain;">


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


                @if (session('status'))
                    <div class="mb-4 text-sm text-green-600">

                        {{ session('status') }}

                    </div>
                @endif



                <form method="POST" action="{{ route('login') }}">

                    @csrf



                    <!-- Email -->

                    <div>

                        <label class="block text-sm font-medium text-[#1E293B]">

                            Email Address

                        </label>


                        <input type="email" name="email" value="{{ old('email') }}" required autofocus
                            class="mt-2 w-full rounded-lg border-gray-300
focus:border-[#005fbf]
focus:ring-[#005fbf]">


                        @error('email')
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



                        <input type="password" name="password" required
                            class="mt-2 w-full rounded-lg border-gray-300
focus:border-[#005fbf]
focus:ring-[#005fbf]">


                        @error('password')
                            <p class="text-sm text-red-500 mt-2">

                                {{ $message }}

                            </p>
                        @enderror


                    </div>




                    <!-- Remember -->

                    <div class="mt-5 flex items-center">


                        <input type="checkbox" name="remember" class="rounded text-[#005fbf]">


                        <span class="ml-2 text-sm text-[#64748B]">

                            Remember me

                        </span>


                    </div>



                    <!-- Button -->

                    <button type="submit"
                        class="mt-7 w-full bg-[#005fbf]
text-white py-3 rounded-xl
font-semibold
hover:bg-[#004a99]
transition">


                        Sign In


                    </button>



                </form>


            </div>



            <p class="text-center text-xs text-[#64748B] mt-6">

                Restricted access. Authorized personnel only.

            </p>


        </div>


    </div>


</body>

</html>
