<div class="bg-white">
    <x-buyer-layout>
        <main class="mx-auto max-w-2xl px-4 lg:max-w-7xl lg:px-8">
            <div class="border-b border-gray-200 pb-10 pt-24">
                <h1 class="text-4xl font-bold tracking-tight text-gray-900">New Arrivals</h1>
                <p class="mt-4 text-base text-gray-500">Checkout the latest release of Basic Tees, new and improved with four openings!</p>
            </div>

            <div class="pb-24 pt-12 lg:grid lg:grid-cols-6 lg:gap-x-8 xl:grid-cols-4">
                <section aria-labelledby="product-heading" class="mt-6 lg:col-span-2 lg:mt-0 xl:col-span-4">
                    <h2 id="product-heading" class="sr-only">Products</h2>

                    <div class="grid grid-cols-1 gap-y-4 sm:grid-cols-2 sm:gap-x-6 sm:gap-y-10 lg:gap-x-8 xl:grid-cols-3">
                        @forelse ($products as $product)
                        <div class="group relative flex flex-col overflow-hidden rounded-lg border border-gray-200 bg-white">
                            <img src="{{ $product->getImage() }}"
                                alt="{{ $product->product_name }}"
                                class="aspect-[3/4] bg-gray-200 object-cover group-hover:opacity-75 sm:h-96">
                            <div class="flex flex-1 flex-col space-y-2 p-4">
                                <h3 class="text-sm font-medium text-gray-900">
                                    <a href="{{ route('product.details', ['code'=> $product->code,'slug' => $product->slug]) }}">
                                        <span aria-hidden="true" class="absolute inset-0"></span>
                                        {{ $product->product_name }}
                                    </a>
                                </h3>
                                <div class="product product-prose">
                                    <p>
                                        @markdown(Str::limit($product->description, 100, '...'))

                                    </p>
                                </div>
                                <div class="flex flex-1 flex-col justify-end">
                                    <p class="text-2xl font-medium text-gray-900">₱{{ $product->price }}</p>
                                    <div class="mt-2">
                                    </div>
                                    {{ ($this->addToCartAction)(['record' => $product->id]) }}
                                </div>
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
