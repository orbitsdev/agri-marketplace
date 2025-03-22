<div class="bg-white">
    <x-buyer-layout>
        <main class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="border-b border-gray-200 pb-10 pt-14">
                <h1 class="text-3xl sm:text-4xl font-bold tracking-tight text-gray-900">
                    Enhancing Trust Between Farmers and Buyers
                </h1>
                <p class="mt-4 text-sm sm:text-base text-gray-500">
                    Welcome to a transparent and reliable agricultural marketplace. Discover fresh harvests and quality goods,
                    brought to you directly by small-scale farmers. Support local agriculture while enjoying the best produce,
                    grown with care and integrity.
                </p>
            </div>

            <!-- Category Tabs (Responsive Horizontal Scroll) -->
            <div x-data="{ activeTab: '{{ $category }}' }" class="mt-6">
                <!-- Ensure horizontal scroll works on small devices -->
                <div class="w-full overflow-x-auto">
                    <div class="flex min-w-full space-x-4 px-2">
                        <!-- All Products Tab -->
                        <button
                            x-on:click="activeTab = ''; $wire.set('category', '')"
                            :class="{ 'border-b-2 border-primary-500 text-primary-600': activeTab === '', 'border-b-2 border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700': activeTab !== '' }"
                            class="whitespace-nowrap px-1 py-4 text-sm font-medium">
                            All Products
                        </button>

                        <!-- Dynamic Tabs -->
                        @foreach ($categories as $category)
                            <button
                                x-on:click="activeTab = '{{ $category->id }}'; $wire.set('category', '{{ $category->id }}')"
                                :class="{ 'border-b-2 border-primary-500 text-primary-600': activeTab === '{{ $category->id }}', 'border-b-2 border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700': activeTab !== '{{ $category->id }}' }"
                                class="whitespace-nowrap px-1 py-4 text-sm font-medium">
                                {{ $category->name }}
                            </button>
                        @endforeach
                    </div>
                </div>
            </div>


            <!-- Search Box -->
            <div class="mt-8 relative max-w-md mx-auto w-full">
                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M21 21l-4.35-4.35M10.5 18a7.5 7.5 0 1 1 0-15 7.5 7.5 0 0 1 0 15Z" />
                    </svg>
                </div>
                <input type="text"
                    wire:model.live.debounce.500ms="search"
                    class="block w-full pl-10 pr-4 py-2 rounded-md border border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm"
                    placeholder="Search for products..." />
            </div>

            <!-- Products Grid -->
            <div class="pb-24 pt-12">
                <section aria-labelledby="product-heading">
                    <h2 id="product-heading" class="sr-only">Products</h2>
                    <div class="mt-8 grid grid-cols-1 gap-y-10 sm:grid-cols-2 sm:gap-x-6 lg:grid-cols-3 xl:grid-cols-4 xl:gap-x-8">
                        @forelse ($products as $product)
                            <div>
                                <a href="{{ route('product.details', ['code'=> $product->code,'slug' => $product->slug]) }}" class="relative block group">
                                    <div class="relative h-72 w-full overflow-hidden rounded-lg">
                                        <img src="{{ $product->getImage() }}" alt="{{ $product->product_name }}"
                                            class="h-full w-full object-cover transition-transform group-hover:scale-105">
                                    </div>
                                    <div class="relative mt-4">
                                        <h3 class="text-sm font-medium text-gray-900">{{ $product->product_name }}</h3>
                                        <p class="mt-1 text-sm text-gray-500">{{ $product->short_description }}</p>
                                    </div>
                                    <div class="absolute inset-x-0 top-0 flex h-72 items-end justify-end rounded-lg p-4">
                                        <div aria-hidden="true"
                                            class="absolute inset-x-0 bottom-0 h-36 bg-gradient-to-t from-black opacity-50"></div>
                                        <p class="relative text-lg font-semibold text-white">₱{{ $product->price }}</p>
                                    </div>
                                </a>
                                <div class="mt-4">
                                    {{ ($this->addToCartAction)(['record' => $product->id]) }}
                                </div>
                            </div>
                        @empty
                            <p class="col-span-full text-center text-gray-500">No products available.</p>
                        @endforelse
                    </div>

                    <!-- Pagination -->
                    <div class="mt-6">
                        {{ $products->links() }}
                    </div>
                </section>
            </div>
        </main>
    </x-buyer-layout>

    <x-filament-actions::modals />
</div>
