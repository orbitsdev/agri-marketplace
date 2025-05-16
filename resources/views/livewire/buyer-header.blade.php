<header class="relative bg-white border-b border-gray-200">
    <p class="flex h-10 items-center justify-center bg-gradient-to-r from-primary-500 via-purple-500 to-pink-500 px-4 text-sm font-medium text-white sm:px-6 lg:px-8">
        Sweet Delight Bakery - Fresh Baked Goods Delivered To Your Door
    </p>

    <nav aria-label="Top" class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8" x-data="{ mobileMenuOpen: false, profileOpen: false }">
        <div class="flex h-16 items-center justify-between">
            <!-- Mobile menu toggle button -->
            <button @click="mobileMenuOpen = !mobileMenuOpen" class="lg:hidden p-2 text-gray-400 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500">
                <span class="sr-only">Open menu</span>
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path x-show="!mobileMenuOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-16 6h16" />
                    <path x-show="mobileMenuOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>

            <!-- Desktop menu -->
            <div class="hidden lg:flex space-x-8">
                <a href="{{route('dashboard')}}" class="text-sm font-medium text-gray-700 hover:text-gray-800">Bakery Products</a>
                {{-- <a href="#" class="text-sm font-medium text-gray-700 hover:text-gray-800">Farms</a>
                <a href="#" class="text-sm font-medium text-gray-700 hover:text-gray-800">About us</a> --}}
            </div>

            @auth
            <div class="flex items-center space-x-4">
                <!-- Cart Badge -->
                @livewire('cart-badge')
                @livewire('notification.notification-dropdown')

                <!-- My Orders -->
                <a href="{{ route('order.history', ['name' => Auth::user()->fullNameSlug()]) }}" class="relative text-gray-400 hover:text-gray-500">
                    <svg class="w-6 h-6" fill="none" stroke-width="1.5" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 1 0-7.5 0v4.5M19.1 8.5l1.2 12c.07.67-.45 1.24-1.12 1.24H4.25a1.12 1.12 0 0 1-1.12-1.24l1.27-12A1.12 1.12 0 0 1 5.5 7.5h13c.57 0 1.06.44 1.12 1zM8.625 10.5a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0zm7.5 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0z" />
                    </svg>
                </a>

                <!-- Profile dropdown -->
                <div class="relative" @click.away="profileOpen = false">
                    <button @click="profileOpen = !profileOpen" class="flex items-center focus:outline-none">
                        <img class="w-8 h-8 rounded-full object-cover" src="{{ Auth::user()->getImage() }}" alt="Profile">
                    </button>
                    <div x-show="profileOpen" class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg ring-1 ring-black ring-opacity-5 z-50">
                        <a href="{{ route('edit.profile',['record'=> Auth::user()]) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Profile</a>
                        <a href="{{ route('address.index', ['name' => Auth::user()->fullNameSlug()]) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">My Address</a>
                        <form method="POST" action="{{ route('logout') }}" class="block">
                            @csrf
                            <button type="submit" class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Log Out</button>
                        </form>
                    </div>
                </div>
            </div>
            @else
            <!-- Login/Register buttons for non-authenticated users -->
            <div class="flex items-center space-x-4">
                <a href="{{ route('login') }}" class="text-sm font-medium text-gray-700 hover:text-gray-800">Login</a>
                <a href="{{ route('register') }}" class="inline-flex items-center justify-center rounded-md border border-transparent bg-primary-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-primary-700">Register</a>
            </div>
            @endauth
        </div>

        <!-- Mobile menu -->
        <div x-show="mobileMenuOpen" class="lg:hidden">
            <div class="pt-2 pb-3 space-y-1">
                <a href="{{route('dashboard')}}" class="block px-3 py-2 text-base font-medium text-gray-700 hover:bg-gray-100">Bakery Products</a>
                {{-- <a href="#" class="block px-3 py-2 text-base font-medium text-gray-700 hover:bg-gray-100">Farms</a>
                <a href="#" class="block px-3 py-2 text-base font-medium text-gray-700 hover:bg-gray-100">About us</a> --}}
            </div>
            @auth
            <div class="border-t border-gray-200 pt-4 pb-3">
                <div class="px-5 flex items-center">
                    <img class="w-10 h-10 rounded-full object-cover" src="{{ Auth::user()->getImage() }}" alt="Profile">
                    <div class="ml-3">
                        <div class="text-base font-medium text-gray-800">{{ Auth::user()->full_name }}</div>
                    </div>
                </div>
                <div class="mt-3 space-y-1">
                    <a href="{{ route('edit.profile',['record'=> Auth::user()]) }}" class="block px-3 py-2 text-base font-medium text-gray-700 hover:bg-gray-100">Profile</a>
                    <a href="{{ route('address.index', ['name' => Auth::user()->fullNameSlug()]) }}" class="block px-3 py-2 text-base font-medium text-gray-700 hover:bg-gray-100">My Address</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full text-left px-3 py-2 text-base font-medium text-gray-700 hover:bg-gray-100">Log Out</button>
                    </form>
                </div>
            </div>
            @else
            <div class="border-t border-gray-200 pt-4 pb-3">
                <div class="mt-3 space-y-1">
                    <a href="{{ route('login') }}" class="block px-3 py-2 text-base font-medium text-gray-700 hover:bg-gray-100">Login</a>
                    <a href="{{ route('register') }}" class="block px-3 py-2 text-base font-medium text-gray-700 hover:bg-gray-100">Register</a>
                </div>
            </div>
            @endauth
        </div>
    </nav>
</header>
