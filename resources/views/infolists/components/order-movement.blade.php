<x-dynamic-component :component="$getEntryWrapperView()" :entry="$entry">

    @forelse ($getRecord()->getLatestMovements() as $index => $movement)
                                        <li class="relative flex gap-x-4 {{ $index === 0 && $getRecord()->status === 'Out for Delivery' ? 'bg-primary-50 dark:bg-primary-50 animate-pulse' : '' }}">

                                            <div class="absolute -bottom-6 left-0 top-0 flex w-6 justify-center">
                                                <div class="w-px {{ $index === 0 && $getRecord()->status === 'Out for Delivery' ? 'bg-primary-500 dark:bg-primary-400' : 'bg-gray-200 dark:bg-gray-700' }}"></div>
                                            </div>


                                            <div class="relative flex size-6 flex-none items-center justify-center bg-white">
                                                <div class="size-1.5 rounded-full {{ $index === 0 && $getRecord()->status === 'Out for Delivery' ? 'bg-primary-500 ring-primary-600 dark:bg-primary-400 dark:ring-primary-500' : 'bg-gray-100 ring-gray-300' }}"></div>
                                            </div>


                                            <div class="flex-auto">
                                                <p class="py-0.5 text-sm {{ $index === 0 && $getRecord()->status === 'Out for Delivery' ? 'text-primary-600 dark:text-primary-400 font-semibold' : 'text-gray-700 dark:text-gray-200' }}">
                                                    <span class="font-medium text-gray-900 dark:text-gray-100">Current Location:</span> {{ $movement->current_location }}
                                                </p>
                                                <p class="py-0.5 text-sm {{ $index === 0 && $getRecord()->status === 'Out for Delivery' ? 'text-primary-600 dark:text-primary-400 font-semibold' : 'text-gray-700 dark:text-gray-200' }}">
                                                    <span class="font-medium text-gray-900 dark:text-gray-100">Destination:</span> {{ $movement->destination }}
                                                </p>
                                                <time datetime="{{ $movement->updated_at->toIso8601String() }}" class="py-0.5 text-xs {{ $index === 0 && $getRecord()->status === 'Out for Delivery' ? 'text-primary-500 dark:text-primary-400' : 'text-gray-500 dark:text-gray-400' }}">
                                                    Last updated: {{ $movement->created_at->format('F j, Y g:i a') }}
                                                </time>
                                            </div>
                                        </li>
                                    @empty
                                        <li class="relative flex gap-x-4">
                                            <div class="relative flex size-6 flex-none items-center justify-center bg-white">
                                                <div class="size-1.5 rounded-full bg-gray-100 ring-1 ring-gray-300"></div>
                                            </div>
                                            <p class="flex-auto text-sm text-gray-500">No movement data available for this order.</p>
                                        </li>
                                    @endforelse
</x-dynamic-component>
