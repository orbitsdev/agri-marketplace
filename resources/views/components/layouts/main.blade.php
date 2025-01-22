<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">


    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Rubik:ital,wght@0,300..900;1,300..900&display=swap"
        rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
        integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>

    @filamentStyles
    @vite('resources/css/app.css')

    <!-- Styles -->
    @livewireStyles

    {{-- <script src="https://cdn.jsdelivr.net/npm/chart.js@4.2.1/dist/chart.umd.min.js "></script>
    <script src="https://fastly.jsdelivr.net/npm/echarts@5.4.1/dist/echarts.min.js"></script> --}}

    <wireui:scripts />
</head>

<body class="font-sans antialiased bg-white">

    {{ $slot }}

    @livewire('notifications')
    @stack('modals')
    @livewireScripts
    @filamentScripts
    @vite('resources/js/app.js')
    @script
    <x-dialog z-index="z-50" blur="md" align="center" />

    <script>
        import Echo from 'laravel-echo'; // Import Laravel Echo
        import Pusher from 'pusher-js'; // Import Pusher

        // Attach Pusher to the global window object
        window.Pusher = Pusher;

        // Configure Laravel Echo
        window.Echo = new Echo({
            broadcaster: 'pusher',
            key: import.meta.env.VITE_PUSHER_APP_KEY, // Get the key from environment variables
            cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER, // Get the cluster from environment variables
            forceTLS: true, // Use HTTPS
        });
        var isAdmin = {{ Auth::user()->hasRole('admin') }}

        window.Echo.private(`conversation.${conversationId}`) // Replace 5 with the user ID for testing
        .listen('Namu\\WireChat\\Events\\MessageCreated', (e) => {
            console.log('Event received:', e);
        });
        </script>
        @endscript
</body>

</html>
