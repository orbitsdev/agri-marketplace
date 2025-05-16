<div>
    <x-public-layout>
        <div class="bg-white">
            <div class="mx-auto max-w-7xl px-4 pt-16 sm:px-6 sm:py-24 lg:px-8">
                <!-- Product Grid Layout -->
                <div class="grid grid-cols-1 lg:grid-cols-2 lg:gap-x-8">
                    <!-- Product Details -->
                    <div class="lg:max-w-lg ">
                        <nav aria-label="Breadcrumb">
                            <div class="flex items-center space-x-2 text-sm">
                                <a href="#" class="font-medium text-gray-500 hover:text-gray-900">
                                    Sweet Delight Bakery
                                </a>
                                <svg viewBox="0 0 20 20" fill="currentColor" class="ml-2 size-5 text-gray-300">
                                    <path d="M5.555 17.776l8-16 .894.448-8 16-.894-.448z" />
                                </svg>
                                <div>
                                    <a href="#"
                                       class="font-medium text-gray-500 hover:text-gray-900">
                                        Artisanal Baked Goods
                                    </a>
                                </div>
                            </div>
                        </nav>

                        <div class="mt-4">
                            <h1 class="text-3xl font-bold tracking-tight text-gray-900 sm:text-4xl">
                                {{ $product->product_name }}
                            </h1>
                            @if ($product->category)
                                <span class="mt-2 inline-block rounded-full bg-blue-100 px-3 py-1 text-sm font-medium text-blue-800">
                                    {{ $product->category->name }}
                                </span>
                            @endif
                            <p class="mt-2 text-base text-gray-500">{{ $product->short_description }}</p>
                        </div>

                        <section class="mt-4">
                            <div class="flex flex-wrap items-center gap-4">
                                <p class="text-lg text-gray-900 sm:text-xl">
                                    ₱ {{ number_format($product->price, 2) }}
                                </p>
                                <div class="border-l border-gray-300 pl-4">
                                    <p class="text-sm text-gray-500">Code: {{ $product->code }}</p>
                                </div>
                            </div>

                            <div class="mt-6 prose max-w-none">
                                @markdown($product->description)
                            </div>
                        </section>
                        <div class="mt-8">
                            <a href="{{ route('login') }}" class="w-full inline-flex items-center justify-center rounded-md border border-transparent bg-primary-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-primary-700">
                                Login to Add to Cart
                            </a>
                        </div>
                    </div>

                    <!-- Product Image -->
                    <div class="mt-10 lg:mt-0 lg:self-center">
                        <a href="{{ $product->getImage() }}" target="_blank">
                            <img src="{{ $product->getImage() }}" alt="{{ $product->product_name }}"
                                 class="w-full aspect-square rounded-lg object-contain">
                        </a>
                    </div>

                    <!-- Add to Cart -->
                    <div class="mt-10 lg:col-start-1 lg:row-start-2 lg:max-w-lg lg:self-start">
                        <section>
                            <div class="mt-6 text-center">
                                <a href="#" class="inline-flex items-center text-base font-medium text-gray-500 hover:text-gray-700">
                                    <svg class="mr-2 size-6 text-gray-400 group-hover:text-gray-500" fill="none"
                                         viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                              d="M9 12.75 11.25 15 15 9.75m-3-7.036A11.959 11.959 0 0 1 3.598 6
                                              11.99 11.99 0 0 0 3 9.749c0 5.592 3.824 10.29 9 11.623
                                              5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196
                                              0-6.1-1.248-8.25-3.285Z" />
                                    </svg>
                                    Lifetime Guarantee
                                </a>
                            </div>
                        </section>
                    </div>
                </div>

                <!-- Transaction Conversation - Login Prompt -->
                <div class="mt-16 border-t pt-10">
                    <div class="mx-auto max-w-7xl">
                        <div class="flex flex-col md:flex-row md:items-center justify-between mb-6">
                            <h2 class="text-xl md:text-2xl font-bold text-gray-900">Product Inquiry</h2>
                            <p class="text-sm text-gray-500 mt-2 md:mt-0">
                                Have questions about this bakery product? Login to chat with us.
                            </p>
                        </div>

                        <div class="bg-gray-50 p-8 rounded-lg text-center">
                            <h3 class="text-lg font-medium text-gray-900 mb-2">Want to message the seller?</h3>
                            <p class="text-gray-600 mb-4">Login or create an account to start a conversation with the seller.</p>
                            <div class="flex justify-center space-x-4">
                                <a href="{{ route('login') }}" class="inline-flex items-center justify-center rounded-md border border-transparent bg-primary-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-primary-700">
                                    Login
                                </a>
                                <a href="{{ route('register') }}" class="inline-flex items-center justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50">
                                    Register
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </x-public-layout>
</div>
