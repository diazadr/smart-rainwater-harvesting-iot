<nav class="fixed top-0 left-0 right-0 bg-white shadow-md z-50">
    <div class="container mx-auto px-4">
        <div class="flex justify-between items-center h-16">
            <a href="/sensor" class="text-xl font-bold text-blue-800">Rainwater Harvesting</a>

            <div class="hidden md:flex items-center space-x-6">
                 <a href="/" class="text-sm text-gray-700 hover:text-blue-700 transition">Dashboard</a>
                <a href="/sensor" class="text-sm text-gray-700 hover:text-blue-700 transition">Sensor</a>

                @if(auth()->user()->role === 'admin')
                <a href="/device" class="text-sm text-gray-700 hover:text-blue-700 transition">Device</a>
                @endif

                <a href="/ganti-password" class="text-sm text-gray-700 hover:text-blue-700 transition">Ganti Password</a>

                @if(auth()->check())
                <form action="/logout" method="post" class="ml-2">
                    @csrf
                    @method('POST')
                    <button type="submit" class="text-sm px-4 py-2 bg-red-500 text-white rounded-md hover:bg-red-600 transition">
                        Log Out
                    </button>
                </form>
                @endif
            </div>

            <!-- Mobile menu button -->
            <div class="md:hidden flex items-center">
                <button type="button" class="mobile-menu-button p-2 rounded-md text-gray-700 hover:text-blue-700 hover:bg-gray-100 transition">
                    <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile menu -->
    <div class="mobile-menu hidden md:hidden bg-white shadow-lg">
        <div class="px-4 pt-3 pb-4 space-y-2">
            <a href="/sensor" class="block text-sm text-gray-700 hover:text-blue-700 hover:bg-gray-100 px-3 py-2 rounded-md">Sensor</a>

            @if(auth()->user()->role === 'admin')
            <a href="/device" class="block text-sm text-gray-700 hover:text-blue-700 hover:bg-gray-100 px-3 py-2 rounded-md">Device</a>
            @endif

            <a href="/ganti-password" class="block text-sm text-gray-700 hover:text-blue-700 hover:bg-gray-100 px-3 py-2 rounded-md">Ganti Password</a>

            @if(auth()->check())
            <form action="/logout" method="post">
                @csrf
                @method('POST')
                <button type="submit" class="w-full text-left block text-sm text-red-600 hover:bg-red-50 px-3 py-2 rounded-md">
                    Log Out
                </button>
            </form>
            @endif
        </div>
    </div>
</nav>

<!-- Mobile toggle script -->
<script>
    document.querySelector('.mobile-menu-button').addEventListener('click', function () {
        document.querySelector('.mobile-menu').classList.toggle('hidden');
    });
</script>
