<div>
    <x-buyer-layout>
        <!-- Farmer Details -->
        <div class="bg-white">
            <div class="max-w-7xl mx-auto px-4 py-16 sm:px-6 lg:px-8">
                <div class="border-b border-gray-200 pb-10">
                    <h1 class="text-4xl font-bold text-gray-900">{{ $farmer->farm_name }}</h1>
                    <div class="mt-4 flex flex-col sm:flex-row sm:flex-wrap sm:space-x-8">
                        <!-- Description -->
                        <div class="mt-2 flex items-center text-sm text-gray-500">
                            <svg class="mr-1.5 size-5 shrink-0 text-gray-400" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path d="M4.22 6.22a.75.75 0 1 1 1.06 0L8 8.94l2.72-2.72a.75.75 0 1 1 1.06 1.06l-3.25 3.25a.75.75 0 0 1-1.06 0L4.22 7.28a.75.75 0 0 1 0-1.06Z" />
                            </svg>
                            <p>{{ $farmer->description }}</p>
                        </div>

                        <!-- Location -->
                        <div class="mt-2 flex items-center text-sm text-gray-500">
                            <svg class="mr-1.5 size-5 shrink-0 text-gray-400" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path d="M9.05 2.5a6.5 6.5 0 1 1-4.6 11.1L2.5 17.5 7 15.6a6.5 6.5 0 0 1 2.05-13.1ZM3.35 9a5.35 5.35 0 1 0 10.7 0 5.35 5.35 0 0 0-10.7 0Z" />
                            </svg>
                            <p><strong>Location:</strong> {{ $farmer->location }}</p>
                        </div>

                        <!-- Farm Size -->
                        <div class="mt-2 flex items-center text-sm text-gray-500">
                            <svg class="mr-1.5 size-5 shrink-0 text-gray-400" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path d="M4.75 3.25h10.5v3.5H4.75v-3.5Zm11.5 0A2.25 2.25 0 0 1 18.5 5.5v9a2.25 2.25 0 0 1-2.25 2.25h-9A2.25 2.25 0 0 1 5 14.5v-9A2.25 2.25 0 0 1 7.25 3.25h9Z" />
                            </svg>
                            <p><strong>Farm Size:</strong> {{ $farmer->farm_size }}</p>
                        </div>

                        <!-- Additional Info -->
                        <div class="mt-2 flex items-center text-sm text-gray-500">
                            <svg class="mr-1.5 size-5 shrink-0 text-gray-400" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd" d="M5.75 2a.75.75 0 0 1 .75.75V4h7V2.75a.75.75 0 0 1 1.5 0V4h.25A2.75 2.75 0 0 1 18 6.75v8.5A2.75 2.75 0 0 1 15.25 18H4.75A2.75 2.75 0 0 1 2 15.25v-8.5A2.75 2.75 0 0 1 4.75 4H5V2.75A.75.75 0 0 1 5.75 2Zm-1 5.5c-.69 0-1.25.56-1.25 1.25v6.5c0 .69.56 1.25 1.25 1.25h10.5c.69 0 1.25-.56 1.25-1.25v-6.5c0-.69-.56-1.25-1.25-1.25H4.75Z" clip-rule="evenodd" />
                            </svg>
                            <p><strong>Contact:</strong> Available upon request</p>
                        </div>
                    </div>
                </div>


                <!-- Farmer's Products -->
                <div class="mt-10">
                    <h2 class="text-2xl font-bold text-gray-900">Products from {{ $farmer->farm_name }}</h2>
                    <div class="mt-6 grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
                        @forelse($products as $product)
                            <div class="group relative bg-white border border-gray-200 rounded-lg p-4">
                                <a href="{{ route('product.details', ['code' => $product->code, 'slug' => $product->slug]) }}" class="block">
                                    <img src="{{ $product->getImage() }}" alt="{{ $product->product_name }}" class="h-48 w-full object-cover rounded">
                                    <h3 class="mt-4 text-lg font-medium text-gray-900">{{ $product->product_name }}</h3>
                                    <p class="mt-1 text-sm text-gray-500">{{ $product->short_description }}</p>
                                </a>
                                <p class="mt-4 text-xl font-semibold text-gray-900">₱{{ number_format($product->price, 2) }}</p>
                            </div>
                        @empty
                            <p class="text-gray-500">No products available from this farmer.</p>
                        @endforelse
                    </div>
                </div>

                <!-- Pagination -->
                <div class="mt-6">
                    {{ $products->links() }}
                </div>
            </div>
        </div>
    </x-buyer-layout>
</div>
