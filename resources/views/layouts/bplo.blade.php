<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">


    <title>
        @yield('title', 'TrafficEnforceNet BPLO')
    </title>


    {{-- CSRF TOKEN --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">


    {{-- Bootstrap --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">


    {{-- Font Awesome --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">


    {{-- Laravel Vite --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])


    {{-- Shared Dashboard Layout --}}
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">


    {{-- BPLO Dashboard Only --}}
    <link rel="stylesheet" href="{{ asset('css/bplo.css') }}">


</head>


<body>


    <div class="wrapper">


        {{-- BPLO SIDEBAR --}}
        @include('partials.bplo-sidebar')



        <div class="main">


            {{-- TOP BAR --}}
            @include('partials.bplo-topbar')



            <main class="content">


                @yield('content')


            </main>



        </div>


    </div>




    {{-- Bootstrap JS --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>


</body>

</html>
