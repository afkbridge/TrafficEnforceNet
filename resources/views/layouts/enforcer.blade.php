<!DOCTYPE html>

<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>
        @yield('title', 'TrafficEnforceNet')
    </title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-100">

    <main>
        @yield('content')
    </main>

    @auth
        <script>
            function sendHeartbeat() {
                fetch('{{ route('enforcer.heartbeat') }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    },
                    credentials: 'same-origin'
                })
                .catch(error => {
                    console.error('Heartbeat failed:', error);
                });
            }

            // Send immediately
            sendHeartbeat();

            // Send every 30 seconds
            setInterval(sendHeartbeat, 30000);
        </script>
    @endauth

</body>

</html>