<x-filament-panels::page>
    <div class="p-4 space-y-4">
        <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100">Product Messages</h2>

        @forelse ($record->latestComments as $comment)
            <div class="p-3 relative border rounded-lg shadow-sm flex gap-3 bg-white dark:bg-gray-800 border-gray-200 dark:border-gray-700">
                <!-- User Avatar -->

                <img src="{{ $comment->creator === 'Farmer' ? $comment->farmer->user->getImage() : $comment->buyer->getImage() }}"
                     alt="User Image"
                     class="w-10 h-10 rounded-full bg-gray-200 dark:bg-gray-700">

                <div class="flex-1 relative w-full">
                        <div class="flex justify-between">

                            <p class="text-sm font-semibold text-gray-900 dark:text-gray-200">
                                {{ $comment->creator === 'Farmer' ? $comment->farmer->user->full_name : $comment->buyer->full_name }}
                            </p>
                            <div class="">
                                {{ ($this->deleteMessageAction)(['record' => $comment->id]) }}
                            </div>
                        </div>
                    <p class="text-xs text-gray-500 dark:text-gray-400">
                        <time datetime="{{ $comment->created_at }}">{{ $comment->created_at->format('F d, Y h:i A') }}</time>
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
                                    <img src="{{ $reply->creator === 'Farmer' ? $reply->farmer->user->getImage() : $reply->buyer->getImage() }}"
                                         alt="User Image"
                                         class="w-8 h-8 rounded-full bg-gray-300 dark:bg-gray-700">

                                    <div class="w-full">
                                        <div class="flex justify-between w-full">

                                            <p class="text-sm font-semibold text-gray-900 dark:text-gray-200">
                                                {{ $reply->creator === 'Farmer' ? $reply->farmer->user->full_name : $reply->buyer->full_name }}
                                            </p>
                                            <div class="">
                                                {{ ($this->deleteMessageAction)(['record' => $reply->id]) }}
                                            </div>

                                        </div>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">
                                            <time datetime="{{ $reply->created_at }}">{{ $reply->created_at->format('F d, Y h:i A') }}</time>
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
