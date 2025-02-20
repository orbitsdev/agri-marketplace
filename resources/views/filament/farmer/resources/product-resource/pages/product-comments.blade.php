<x-filament-panels::page>
    <div class="p-4 space-y-4">
        <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100">Product Comments</h2>

        @forelse ($record->comments as $comment)
            <div class="p-3 border rounded-lg shadow-sm flex gap-3 bg-white dark:bg-gray-800 border-gray-200 dark:border-gray-700">
                <!-- User Avatar -->
                <img src="{{ $comment->buyer->getImage() }}" alt="User Image" class="w-10 h-10 rounded-full bg-gray-200 dark:bg-gray-700">

                <div class="flex-1">
                    <p class="text-sm font-semibold text-gray-900 dark:text-gray-200">
                        {{ $comment->buyer->full_name ?? 'Unknown Buyer' }}
                    </p>
                    <p class="text-base text-gray-900 dark:text-gray-200">
                        {{ $comment->content }}
                    </p>

                    <!-- Reply Action -->
                    <div class="mt-2">
                        {{ ($this->addReplyAction)(['record' => $comment->id]) }}
                    </div>

                    <!-- Display Replies -->
                    @if ($comment->replies->isNotEmpty())
                        <div class="ml-4 mt-4 border-l-2 pl-3 border-gray-300 dark:border-gray-600">
                            @foreach ($comment->replies as $reply)
                                <div class="p-2 border-t border-gray-200 dark:border-gray-700 flex gap-3 bg-gray-50 dark:bg-gray-900 rounded-lg">
                                    <!-- Reply Avatar -->
                                    <img src="{{ $reply->buyer->getImage() }}" alt="User Image" class="w-8 h-8 rounded-full bg-gray-300 dark:bg-gray-700">

                                    <div>
                                        <p class="text-sm font-semibold text-gray-900 dark:text-gray-200">
                                            {{ $reply->buyer->full_name ?? 'Unknown' }}
                                        </p>
                                        <p class="text-sm text-gray-900 dark:text-gray-200">
                                            {{ $reply->content }}
                                        </p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        @empty
            <p class="text-gray-600 dark:text-gray-400">No comments yet.</p>
        @endforelse
    </div>
</x-filament-panels::page>
