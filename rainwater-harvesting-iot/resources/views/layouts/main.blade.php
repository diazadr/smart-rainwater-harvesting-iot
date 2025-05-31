<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Smart Rainwater Harvesting</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/mqtt/dist/mqtt.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @stack('styles')
</head>
<body class="bg-gray-100">
    @include('partials.nav')

    <div class="min-h-screen pt-16"> <!-- Added pt-16 to account for fixed navbar height -->
        @yield('content')
    </div>

    @stack('scripts')
</body>
<script src="//unpkg.com/alpinejs" defer></script>

</html>
