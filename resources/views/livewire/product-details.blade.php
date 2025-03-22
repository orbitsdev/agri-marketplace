<div>
    <x-buyer-layout>
        <div class="bg-white">
            <div class="mx-auto max-w-7xl px-4 pt-16 sm:px-6 sm:py-24 lg:px-8">
                <!-- Product Grid Layout -->
                <div class="grid grid-cols-1 lg:grid-cols-2 lg:gap-x-8">
                    <!-- Product Details -->
                    <div class="lg:max-w-lg ">
                        <nav aria-label="Breadcrumb">
                            <div class="flex items-center space-x-2 text-sm">
                                <a href="#" class="font-medium text-gray-500 hover:text-gray-900">
                                    {{ $product->farmer->farm_name }} Farm
                                </a>
                                <svg viewBox="0 0 20 20" fill="currentColor" class="ml-2 size-5 text-gray-300">
                                    <path d="M5.555 17.776l8-16 .894.448-8 16-.894-.448z" />
                                </svg>
                                <div>
                                    <a href="{{ route('farmer.details', ['farmerId' => $product->farmer->id]) }}"
                                       class="font-medium text-gray-500 hover:text-gray-900">
                                        {{ $product->farmer->user->full_name }}
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
                            {{ ($this->addToCartAction)(['record' => $product->id]) }}
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

                <!-- Transaction Conversation -->
                <div class="mt-16 border-t pt-10">
                    <div class="mx-auto max-w-7xl">
                        <div class="flex flex-col md:flex-row md:items-center justify-between mb-6">
                            <h2 class="text-xl md:text-2xl font-bold text-gray-900">Transaction Conversation</h2>
                            <p class="text-sm text-gray-500 mt-2 md:mt-0">
                                Communicate directly with the seller about this product.
                            </p>
                        </div>

                        <div class="flex justify-end mb-6">
                            {{ ($this->addMessageAction)(['record' => $product->id]) }}
                        </div>

                        <div>
                            <h3 class="text-xl font-semibold text-gray-900">Conversation History</h3>

                            <div class="mt-6 space-y-10">
                                @forelse ($comments as $comment)
                                    <div class="relative flex space-x-4 text-sm text-gray-500">
                                        @if (auth()->id() === $comment->buyer_id || auth()->id() === $product->farmer->user_id)
                                            <div class="absolute top-0 right-0 p-2">
                                                {{ ($this->deleteMessageAction)(['record' => $comment->id]) }}
                                            </div>
                                        @endif

                                        <div class="flex-none py-2">
                                            <img src="{{ $comment->creator === 'Farmer' ? $comment->farmer->user->getImage() : $comment->buyer->getImage() }}"
                                                 alt="avatar"
                                                 class="h-10 w-10 rounded-full object-cover bg-gray-100">
                                        </div>

                                        <div class="flex-1 py-2">
                                            <h4 class="font-medium text-gray-900">
                                                {{ $comment->creator === 'Farmer' ? $comment->farmer->user->full_name : $comment->buyer->full_name }}
                                            </h4>
                                            <p class="text-xs text-gray-500">
                                                <time datetime="{{ $comment->created_at }}">{{ $comment->created_at->format('F d, Y') }}</time>
                                            </p>
                                            <div class="mt-2 text-gray-700">{{ $comment->content }}</div>

                                            @if (is_null($comment->parent_id))
                                                <div class="mt-4">
                                                    {{ ($this->addReplyAction)(['record' => $comment->id]) }}
                                                </div>
                                            @endif

                                            <!-- Replies -->
                                            @foreach ($comment->replies as $reply)
                                                <div class="mt-4 ml-4 border-l-2 pl-4">
                                                    <div class="flex space-x-3">
                                                        <img src="{{ $reply->creator === 'Farmer' ? $reply->farmer->user->getImage() : $reply->buyer->getImage() }}"
                                                             alt="avatar"
                                                             class="h-8 w-8 rounded-full object-cover bg-gray-100">
                                                        <div>
                                                            <h4 class="font-medium text-gray-900">
                                                                {{ $reply->creator === 'Farmer' ? $reply->farmer->user->full_name : $reply->buyer->full_name }}
                                                            </h4>
                                                            <p class="text-xs text-gray-500">
                                                                <time datetime="{{ $reply->created_at }}">{{ $reply->created_at->format('F d, Y') }}</time>
                                                            </p>
                                                            <p class="mt-1 text-sm text-gray-700">{{ $reply->content }}</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>

                                    @if (!$loop->last)
                                        <hr class="border-t border-gray-200 my-8">
                                    @endif
                                @empty
                                    <p class="text-gray-500">No messages yet. Start a conversation with the seller!</p>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <x-filament-actions::modals />
    </x-buyer-layout>
</div>
