<header class="relative bg-white border-b border-gray-200">
    <p class="flex h-10 items-center justify-center bg-gradient-to-r from-primary-500 via-purple-500 to-pink-500 px-4 text-sm font-medium text-white sm:px-6 lg:px-8">
        Get free delivery on orders over Php 20,000
    </p>

    <nav aria-label="Top" class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8" x-data="{ mobileMenuOpen: false }">
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
                <a href="{{route('public.products')}}" class="text-sm font-medium text-gray-700 hover:text-gray-800">Agri Products</a>
            </div>

            <div class="flex items-center space-x-4">
                <!-- Login/Register buttons -->
                <div class="flex items-center space-x-4">
                    <a href="{{ route('login') }}" class="text-sm font-medium text-gray-700 hover:text-gray-800">Login</a>
                    <a href="{{ route('register') }}" class="inline-flex items-center justify-center rounded-md border border-transparent bg-primary-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-primary-700">Register</a>
                </div>
            </div>
        </div>

        <!-- Mobile menu -->
        <div x-show="mobileMenuOpen" class="lg:hidden">
            <div class="pt-2 pb-3 space-y-1">
                <a href="{{route('public.products')}}" class="block px-3 py-2 text-base font-medium text-gray-700 hover:bg-gray-100">Agri Products</a>
            </div>
            <div class="border-t border-gray-200 pt-4 pb-3">
                <div class="mt-3 space-y-1">
                    <a href="{{ route('login') }}" class="block px-3 py-2 text-base font-medium text-gray-700 hover:bg-gray-100">Login</a>
                    <a href="{{ route('register') }}" class="block px-3 py-2 text-base font-medium text-gray-700 hover:bg-gray-100">Register</a>
                </div>
            </div>
        </div>
    </nav>
</header>
