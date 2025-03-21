<div class="relative group">
    <a href="{{ route('cart.view', ['name' => Auth::user()->fullNameSlug()]) }}" class="flex items-center p-2">
        <!-- Cart Icon -->
        <div class="relative">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                class="h-6 w-6 text-gray-400 transition duration-200 group-hover:text-gray-500">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 0 0-16.536-1.84M7.5 14.25 5.106 5.272M6 20.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Zm12.75 0a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z" />
            </svg>

            <!-- Cart Badge (Animated) -->
            @if ($itemCount > 0)
                <span class="absolute -top-3 -right-1 bg-red-600 text-white text-xs font-bold rounded-full px-2 py-0.5
                    shadow-md transition-transform duration-200 scale-90 group-hover:scale-100">
                    {{ $itemCount }}
                </span>
            @endif
        </div>
    </a>

    <!-- Tooltip -->
    <div class="absolute left-1/2 bottom-full mb-2 hidden w-max -translate-x-1/2 rounded-md bg-gray-800 px-2 py-1
        text-xs text-white shadow-lg opacity-0 transition-opacity duration-200 group-hover:opacity-100">
        View Cart
    </div>
</div>
