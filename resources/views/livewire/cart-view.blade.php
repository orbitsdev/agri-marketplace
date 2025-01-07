<div>
    <x-buyer-layout>
        <div class="mx-auto max-w-2xl px-4 pb-24 pt-16 sm:px-6 lg:max-w-7xl lg:px-8">
            <h1 class="text-3xl font-bold tracking-tight text-gray-900 sm:text-4xl">My Cart ({{ $totalCartItem }})</h1>

            <form class="mt-12 lg:grid lg:grid-cols-12 lg:items-start lg:gap-x-12 xl:gap-x-16">
                <section aria-labelledby="cart-heading" class="lg:col-span-7 required:">


                    <ul role="list" class="  border-gray-200 relative">
                        <div wire:loading.flex wire:target="toggleSelect, updateQuantity, removeItem"
                            class="absolute inset-0 flex items-center justify-center bg-gray-100 bg-opacity-80 z-10 rounded-md shadow-lg backdrop-blur-sm">
                            <div class="flex flex-col items-center">

                                <div
                                    class="animate-spin rounded-full h-10 w-10 border-t-4 border-blue-500 border-opacity-75 border-b-4 border-gray-300">
                                </div>

                                <span class="mt-2 text-sm font-medium text-gray-700">Processing...</span>
                            </div>
                        </div>

                        {{-- @forelse ($cartItems as $key => $item)
                            <li class="flex py-6 sm:py-10 ">






                                <div class="flex items-center pr-4">
                                    <input type="checkbox" wire:click="toggleSelect({{ $item->id }})"
                                        @if ($item->is_selected) checked @endif
                                        class="form-checkbox h-5 w-5 text-eucalyptus-600">
                                </div>


                                <div class="shrink-0">
                                    <img src="{{ $item->product->getImage() }}" alt="{{ $item->product->product_name }}"
                                        class="size-24 rounded-md object-cover sm:size-48">
                                </div>


                                <div class="ml-4 flex flex-1 flex-col justify-between sm:ml-6">
                                    <div class="relative pr-9 sm:grid sm:grid-cols-2 sm:gap-x-6 sm:pr-0">
                                        <div>
                                            <h3 class="text-sm font-medium text-gray-700 hover:text-gray-800">
                                                {{ $item->product->product_name }}
                                            </h3>
                                            <p class="text-gray-500">{{ $item->product->code }}</p>
                                            <p class="mt-1 text-sm font-medium text-gray-900">
                                                ₱{{ number_format($item->price_per_unit, 2) }}</p>
                                        </div>

                                        <div class="mt-4 sm:mt-0 sm:pr-9 flex items-center space-x-4">
                                            <input type="number" min="1"
                                                class="w-16 border rounded-md px-2 py-1 text-center"
                                                value="{{ $item->quantity }}"
                                                wire:change="updateQuantity({{ $item->id }}, $event.target.value)">


                                            {{ ($this->removeItemAction)(['record' => $item->id]) }}

                                        </div>
                                    </div>
                                </div>
                            </li>
                        @empty
                            <li class="py-6 sm:py-10 text-center">
                                <div class="flex flex-col items-center space-y-4">

                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="h-16 w-16 text-gray-400">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 0 0-16.536-1.84M7.5 14.25 5.106 5.272M6 20.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Zm12.75 0a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z" />
                                    </svg>

                                    <h3 class="text-lg font-semibold text-gray-700">Your cart is empty</h3>
                                    <p class="text-gray-500">Looks like you haven’t added any items yet. Explore our
                                        collection and find something you like!</p>
                                    <a href="{{ route('dashboard') }}"
                                        class="inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-white bg-eucalyptus-600 border border-transparent rounded-md shadow-sm hover:bg-eucalyptus-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-eucalyptus-500">
                                        Browse Products
                                    </a>
                                </div>
                            </li>
                        @endforelse --}}


                        @forelse ($cartItems as $farmerId => $farmerCartItems)
    @php
        // Get the farmer's name from the first item's product
        $farmerName = $farmerCartItems->first()->product->farmer->farm_name ?? 'Unknown Farmer';
    @endphp

    <div class="mb-6 bg-white rounded">
        <!-- Farmer Name -->
        <h3 class="text-lg px-6 font-semibold text-white mb-4 bg-gradient-to-r from-eucalyptus-700 to-eucalyptus-600 rounded-t">
            {{ $farmerName }}
        </h3>

        <!-- Products -->
        <ul>
            @foreach ($farmerCartItems as $item)
                <li class="flex px-6">
                    <!-- Selection Checkbox -->
                    <div class="flex items-center pr-4">
                        <input type="checkbox" wire:click="toggleSelect({{ $item->id }})"
                            @if ($item->is_selected) checked @endif
                            class="form-checkbox h-5 w-5 text-eucalyptus-600">
                    </div>

                    <!-- Product Image -->
                    <div class="shrink-0">
                        <img src="{{ $item->product->getImage() ?? asset('images/placeholder.jpg') }}"
                            alt="{{ $item->product->product_name ?? 'Product' }}"
                            class="size-24 rounded-md object-cover sm:size-48">
                    </div>

                    <!-- Product Details -->
                    <div class="ml-4 flex flex-1 flex-col justify-between sm:ml-6">
                        <div class="relative pr-9 sm:grid sm:grid-cols-2 sm:gap-x-6 sm:pr-0">
                            <div>
                                <h3 class="text-sm font-medium text-gray-700 hover:text-gray-800">
                                    {{ $item->product->product_name ?? 'Unknown Product' }}
                                </h3>
                                <p class="text-gray-500 text-xs">{{ $item->product->code ?? 'N/A' }}</p>
                                <p class="mt-1 text-sm font-medium text-gray-900">
                                    ₱{{ number_format($item->price_per_unit, 2) }}
                                </p>
                            </div>

                            <!-- Quantity and Remove Controls -->
                            <div class="mt-4 sm:mt-0 sm:pr-9 flex items-center space-x-4">
                                <input type="number" min="1"
                                    class="w-16 border border-eucalyptus-500 rounded-md px-2 py-1 text-center focus:outline-none focus:ring-2 focus:ring-eucalyptus-500 focus:border-eucalyptus-500"
                                    value="{{ $item->quantity }}"
                                    wire:change="updateQuantity({{ $item->id }}, $event.target.value)">

                                <!-- Remove Button -->
                                {{ ($this->removeItemAction)(['record' => $item->id]) }}
                            </div>
                        </div>
                    </div>
                </li>

                @if (!$loop->last)
                    <div class="h-px bg-gray-200 my-2"></div>
                @endif
            @endforeach
        </ul>
    </div>
@empty
    <li class="py-6 sm:py-10 text-center">
        <div class="flex flex-col items-center space-y-4">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                stroke-width="1.5" stroke="currentColor" class="h-16 w-16 text-gray-400">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 0 0-16.536-1.84M7.5 14.25 5.106 5.272M6 20.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Zm12.75 0a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z" />
            </svg>

            <h3 class="text-lg font-semibold text-gray-700">Your cart is empty</h3>
            <p class="text-gray-500">Looks like you haven’t added any items yet. Explore our
                collection and find something you like!
            </p>
            <a href="{{ route('dashboard') }}"
                class="inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-white bg-eucalyptus-600 border border-transparent rounded-md shadow-sm hover:bg-eucalyptus-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-eucalyptus-500">
                Browse Products
            </a>
        </div>
    </li>
@endforelse



                    </ul>
                </section>


                <section aria-labelledby="summary-heading"
                    class="mt-16 rounded-lg bg-gray-50 px-4 py-6 sm:p-6 lg:col-span-5 lg:mt-0 lg:p-8">

                    <h2 id="summary-heading" class="text-lg font-medium text-gray-900">Order summary</h2>

                    <dl class="mt-6 space-y-4">
                        <div class="flex items-center justify-between">
                            <dt class="text-sm text-gray-600">Total Items</dt>
                            <dd class="text-sm font-medium text-gray-900">({{ $totalSelectedItems }})</dd>
                        </div>
                        <div class="flex items-center justify-between border-t border-gray-200 pt-4">
                            <dt class="text-sm text-gray-600">Total Value</dt>
                            <dd class="text-sm font-medium text-gray-900">₱{{ number_format($totalSelectedValue, 2) }}
                            </dd>
                        </div>
                    </dl>


                    @if ($totalSelectedItems > 0)
                        <div class="mt-8 flex flex-1 flex-col justify-end">

                            {{ $this->checkoutCartAction }}
                        </div>
                    @endif
                </section>
            </form>


        </div>
    </x-buyer-layout>
    <x-filament-actions::modals />
</div>
