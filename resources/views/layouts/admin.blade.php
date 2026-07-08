<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title', 'TrafficEnforceNet')</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    @vite([
        'resources/css/app.css',
        'resources/js/app.js'
    ])

    <!-- Google Font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">

    <link rel="preconnect"
          href="https://fonts.gstatic.com"
          crossorigin>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
          rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">

    <!-- Custom CSS -->
    <link rel="stylesheet"
          href="{{ asset('css/admin.css') }}">

</head>

<body>

<div class="wrapper">

    {{-- Sidebar --}}
    @include('partials.sidebar')

    <div class="main">

        {{-- Top Navigation --}}
        @include('partials.topbar')

        {{-- Page Content --}}
        <div class="content">

            @yield('content')

        </div>

    </div>

</div>

</body>

</html>