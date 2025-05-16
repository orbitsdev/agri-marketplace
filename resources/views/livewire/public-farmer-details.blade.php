<div>
    <x-public-layout>
        <div class="bg-white">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-12">
                <!-- Farmer Profile -->
                <div class="bg-white shadow overflow-hidden sm:rounded-lg mb-8">
                    <div class="px-4 py-5 sm:px-6 bg-gray-50">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">Farmer Profile</h3>
                        <p class="mt-1 max-w-2xl text-sm text-gray-500">Details about the farmer and their farm.</p>
                    </div>
                    <div class="border-t border-gray-200">
                        <dl>
                            <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500">Farmer Name</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $farmer->user->full_name }}</dd>
                            </div>
                            <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500">Farm Name</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $farmer->farm_name }}</dd>
                            </div>
                            <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500">Farm Address</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $farmer->farm_address }}</dd>
                            </div>
                            <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500">Farm Size</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $farmer->farm_size }} hectares</dd>
                            </div>
                            <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500">Farm Description</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $farmer->farm_description }}</dd>
                            </div>
                        </dl>
                    </div>
                </div>

                <!-- Farmer Products -->
                <div class="mt-12">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6">Products from this Farmer</h2>
                    
                    @if($products->count() > 0)
                        <div class="grid grid-cols-1 gap-y-10 sm:grid-cols-2 sm:gap-x-6 lg:grid-cols-3 xl:grid-cols-4 xl:gap-x-8">
                            @foreach($products as $product)
                                <div>
                                    <a href="{{ route('public.product.details', ['code'=> $product->code,'slug' => $product->slug]) }}" class="relative block group">
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
                                        <a href="{{ route('login') }}" class="w-full inline-flex items-center justify-center rounded-md border border-transparent bg-primary-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-primary-700">
                                            Login to Add to Cart
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-500 text-center">No products available from this farmer.</p>
                    @endif
                </div>
            </div>
        </div>
    </x-public-layout>
</div>
