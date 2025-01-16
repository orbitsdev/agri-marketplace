<div class="bg-white">
    <x-buyer-layout>
        <main class="mx-auto max-w-2xl px-4 lg:max-w-7xl lg:px-8">
            <div class="border-b border-gray-200 pb-10 pt-14">
                <h1 class="text-4xl font-bold tracking-tight text-gray-900">Enhancing Trust Between Farmers and Buyers</h1>
                <p class="mt-4 text-base text-gray-500">
                    Welcome to a transparent and reliable agricultural marketplace. Discover fresh harvests and quality goods,
                    brought to you directly by small-scale farmers. Support local agriculture while enjoying the best produce,
                    grown with care and integrity.
                </p>
            </div>

            <div x-data="{ activeTab: '{{ $category }}' }" class="">
                <nav class=" border-gray-200 -mb-px flex space-x-8" aria-label="Tabs">
                    <!-- All Products Tab -->
                    <button
                        x-on:click="activeTab = ''; $wire.set('category', '')"
                        :class="{ 'border-primary-500 text-primary-600': activeTab === '', 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700': activeTab !== '' }"
                        class="flex whitespace-nowrap border-b-2 px-1 py-4 text-sm font-medium">
                        All Products
                    </button>

                    <!-- Dynamic Category Tabs -->
                    @foreach ($categories as $category)
                        <button
                            x-on:click="activeTab = '{{ $category->id }}'; $wire.set('category', '{{ $category->id }}')"
                            :class="{ 'border-primary-500 text-primary-600': activeTab === '{{ $category->id }}', 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700': activeTab !== '{{ $category->id }}' }"
                            class="flex whitespace-nowrap border-b-2 px-1 py-4 text-sm font-medium">
                            {{ $category->name }}
                        </button>
                    @endforeach
                </nav>
            </div>

             <!-- Search Box -->
             <div class="mt-8 relative max-w-lg mx-auto sm:max-w-sm md:max-w-md lg:max-w-lg">
                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M10.5 18a7.5 7.5 0 1 1 0-15 7.5 7.5 0 0 1 0 15Z" />
                    </svg>
                </div>
                <input
                    type="text"
                    wire:model.live.debounce.500ms="search"
                    class="block w-full pl-10 rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm"
                    placeholder="Search for products..."
                />
            </div>

            <div class="pb-24 pt-12 lg:grid lg:grid-cols-6 lg:gap-x-8 xl:grid-cols-4">
                <section aria-labelledby="product-heading" class="mt-6 lg:col-span-2 lg:mt-0 xl:col-span-4">
                    <h2 id="product-heading" class="sr-only">Products</h2>
                    <div class="mt-8 grid grid-cols-1 gap-y-12 sm:grid-cols-2 sm:gap-x-6 lg:grid-cols-4 xl:gap-x-8">
                        @forelse ($products as $product)
                        <div>
                            <a href="{{ route('product.details', ['code'=> $product->code,'slug' => $product->slug]) }}" class="relative">
                              <div class="relative h-72 w-full overflow-hidden rounded-lg">

                                <img src="{{ $product->getImage() }}" alt="Front of zip tote bag with white canvas, black canvas straps and handle, and black zipper pulls." class="size-full object-cover">
                              </div>
                              <div class="relative mt-4">
                                <h3 class="text-sm font-medium text-gray-900">  {{ $product->product_name }}</h3>
                                <p class="mt-1 text-sm text-gray-500">  {{ $product->short_description }}</p>
                              </div>
                              <div class="absolute inset-x-0 top-0 flex h-72 items-end justify-end overflow-hidden rounded-lg p-4">
                                <div aria-hidden="true" class="absolute inset-x-0 bottom-0 h-36 bg-gradient-to-t from-black opacity-50"></div>
                                <p class="relative text-lg font-semibold text-white">₱{{ $product->price }}</p>
                              </div>
                            </a>
                            <div class="mt-6 flex flex-1 flex-col justify-end">
                                {{ ($this->addToCartAction)(['record' => $product->id]) }}

                            </div>
                          </div>

                    @empty
                        <p class="col-span-full text-center text-gray-500">No products available.</p>
                    @endforelse


                      </div>

                    <!-- Pagination -->
                    <div class="mt-4">
                        {{ $products->links() }}
                    </div>
                </section>
            </div>
        </main>
    </x-buyer-layout>


    <x-filament-actions::modals />
</div>
