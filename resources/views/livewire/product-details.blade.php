<div>
    <x-buyer-layout>
        <div class="bg-white">
            <div class="mx-auto max-w-2xl px-4 pt-16 sm:px-6 sm:py-24 lg:grid lg:max-w-7xl lg:grid-cols-2 lg:gap-x-8 lg:px-8">
                <!-- Product details -->
                <div class="lg:max-w-lg lg:self-end">
                    <nav aria-label="Breadcrumb">
                        <ol role="list" class="flex items-center space-x-2">
                            <li>
                                <div class="flex items-center text-sm">
                                    <a href="#" class="font-medium text-gray-500 hover:text-gray-900">{{ $product->farmer->farm_name }} Farm</a>
                                    <svg viewBox="0 0 20 20" fill="currentColor" aria-hidden="true" class="ml-2 size-5 shrink-0 text-gray-300">
                                        <path d="M5.555 17.776l8-16 .894.448-8 16-.894-.448z" />
                                    </svg>
                                </div>
                            </li>
                            <li>
                                <div class="flex items-center text-sm">
                                    <a href="{{ route('farmer.details', ['farmerId' => $product->farmer->id]) }}" class="font-medium text-gray-500 hover:text-gray-900">
                                        {{ $product->farmer->user->full_name }}
                                    </a>
                                </div>
                            </li>

                        </ol>
                    </nav>

                    <div class="mt-4">
                        <h1 class="text-3xl font-bold tracking-tight text-gray-900 sm:text-4xl">{{ $product->product_name }}</h1>
                        @if ($product->category)
                        <span class="mt-2 inline-block rounded-full bg-blue-100 px-3 py-1 text-sm font-medium text-blue-800">
                            {{ $product->category->name }}
                        </span>
                    @endif
                        <p class="mt-2 text-base text-gray-500">{{ $product->short_description }}</p>
                    </div>

                    <section aria-labelledby="information-heading" class="mt-4">
                        <div class="flex items-center">
                            <p class="text-lg text-gray-900 sm:text-xl">₱ {{ number_format($product->price, 2) }}</p>

                            <div class="ml-4 border-l border-gray-300 pl-4">
                                <h2 class="sr-only">Code</h2>
                                <div class="flex items-center">
                                    <p class="ml-2 text-sm text-gray-500">Code: {{ $product->code }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="mt-4 space-y-6">
                            <div class="product custom-prose">
                                <p>
                                    @markdown($product->description)
                                </p>
                            </div>
                        </div>
                    </section>
                </div>

                <!-- Product image -->
                <div class="mt-10 lg:col-start-2 lg:row-span-2 lg:mt-0 lg:self-center">
                    <a href="{{ $product->getImage() }}" target="_blank">
                        <img src="{{ $product->getImage() }}" alt="{{ $product->product_name }}" class="aspect-square w-full rounded-lg object-cover">
                    </a>
                </div>

                <!-- Product form -->
                <div class="mt-10 lg:col-start-1 lg:row-start-2 lg:max-w-lg lg:self-start">
                    <section aria-labelledby="options-heading">
                        <h2 id="options-heading" class="sr-only">Product options</h2>
                        <div class="mt-8 flex flex-1 flex-col justify-end">
                            {{ ($this->addToCartAction)(['record' => $product->id]) }}
                        </div>
                        <div class="mt-6 text-center">
                            <a href="#" class="group inline-flex text-base font-medium">
                                <svg class="mr-2 size-6 shrink-0 text-gray-400 group-hover:text-gray-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75m-3-7.036A11.959 11.959 0 0 1 3.598 6 11.99 11.99 0 0 0 3 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285Z" />
                                </svg>
                                <span class="text-gray-500 hover:text-gray-700">Lifetime Guarantee</span>
                            </a>
                        </div>
                    </section>
                </div>
            </div>
            <div class="bg-gray-200 grid-cols-2">
                    Transaction

                <div class="flex space-x-4 text-sm text-gray-500 container mx-auto  max-w-7xl">
                    <div class="flex-none py-10">
                      <img src="https://images.unsplash.com/photo-1502685104226-ee32379fefbe?ixlib=rb-=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=8&w=256&h=256&q=80" alt="" class="size-10 rounded-full bg-gray-100">
                    </div>
                    <div class="py-10">
                      <h3 class="font-medium text-gray-900">Emily Selman</h3>
                      <p><time datetime="2021-07-16">July 16, 2021</time></p>

                      <div class="mt-4 flex items-center">
                        <!-- Active: "text-yellow-400", Default: "text-gray-300" -->
                        <svg class="size-5 shrink-0 text-yellow-400" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true" data-slot="icon">
                          <path fill-rule="evenodd" d="M10.868 2.884c-.321-.772-1.415-.772-1.736 0l-1.83 4.401-4.753.381c-.833.067-1.171 1.107-.536 1.651l3.62 3.102-1.106 4.637c-.194.813.691 1.456 1.405 1.02L10 15.591l4.069 2.485c.713.436 1.598-.207 1.404-1.02l-1.106-4.637 3.62-3.102c.635-.544.297-1.584-.536-1.65l-4.752-.382-1.831-4.401Z" clip-rule="evenodd" />
                        </svg>
                        <svg class="size-5 shrink-0 text-yellow-400" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true" data-slot="icon">
                          <path fill-rule="evenodd" d="M10.868 2.884c-.321-.772-1.415-.772-1.736 0l-1.83 4.401-4.753.381c-.833.067-1.171 1.107-.536 1.651l3.62 3.102-1.106 4.637c-.194.813.691 1.456 1.405 1.02L10 15.591l4.069 2.485c.713.436 1.598-.207 1.404-1.02l-1.106-4.637 3.62-3.102c.635-.544.297-1.584-.536-1.65l-4.752-.382-1.831-4.401Z" clip-rule="evenodd" />
                        </svg>
                        <svg class="size-5 shrink-0 text-yellow-400" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true" data-slot="icon">
                          <path fill-rule="evenodd" d="M10.868 2.884c-.321-.772-1.415-.772-1.736 0l-1.83 4.401-4.753.381c-.833.067-1.171 1.107-.536 1.651l3.62 3.102-1.106 4.637c-.194.813.691 1.456 1.405 1.02L10 15.591l4.069 2.485c.713.436 1.598-.207 1.404-1.02l-1.106-4.637 3.62-3.102c.635-.544.297-1.584-.536-1.65l-4.752-.382-1.831-4.401Z" clip-rule="evenodd" />
                        </svg>
                        <svg class="size-5 shrink-0 text-yellow-400" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true" data-slot="icon">
                          <path fill-rule="evenodd" d="M10.868 2.884c-.321-.772-1.415-.772-1.736 0l-1.83 4.401-4.753.381c-.833.067-1.171 1.107-.536 1.651l3.62 3.102-1.106 4.637c-.194.813.691 1.456 1.405 1.02L10 15.591l4.069 2.485c.713.436 1.598-.207 1.404-1.02l-1.106-4.637 3.62-3.102c.635-.544.297-1.584-.536-1.65l-4.752-.382-1.831-4.401Z" clip-rule="evenodd" />
                        </svg>
                        <svg class="size-5 shrink-0 text-yellow-400" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true" data-slot="icon">
                          <path fill-rule="evenodd" d="M10.868 2.884c-.321-.772-1.415-.772-1.736 0l-1.83 4.401-4.753.381c-.833.067-1.171 1.107-.536 1.651l3.62 3.102-1.106 4.637c-.194.813.691 1.456 1.405 1.02L10 15.591l4.069 2.485c.713.436 1.598-.207 1.404-1.02l-1.106-4.637 3.62-3.102c.635-.544.297-1.584-.536-1.65l-4.752-.382-1.831-4.401Z" clip-rule="evenodd" />
                        </svg>
                      </div>
                      <p class="sr-only">5 out of 5 stars</p>

                      <div class="mt-4 text-sm/6 text-gray-500">
                        <p>This icon pack is just what I need for my latest project. There's an icon for just about anything I could ever need. Love the playful look!</p>
                      </div>
                    </div>
                  </div>


        </div>
    </div>
    </x-buyer-layout>
    <x-filament-actions::modals />
</div>
