<x-filament-panels::page>

    <div class="p-4 space-y-4">
        <h2 class="text-xl font-semibold">Product Comments</h2>

        @forelse ($record->comments as $comment)
            <div class="p-3 border rounded-lg shadow-sm flex gap-3">
                {{-- User Image --}}
                <img src="{{ $comment->buyer->getImage() }}" alt="User Image" class="w-10 h-10 rounded-full">

                <div>
                    <p class="text-sm font-semibold">{{ $comment->buyer->full_name ?? 'Unknown Buyer' }}</p>
                    <p class="text-base">{{ $comment->content }}</p>

                    {{-- Show Replies if any --}}
                    @if ($comment->replies->isNotEmpty())
                        <div class="ml-4 mt-2 border-l-2 pl-3">
                            {{-- <div class="mt-8 flex flex-1 flex-col justify-end">
                                {{ ($this->addToCartAction)(['record' => $comment->product->id]) }}
                            </div> --}}
                            @foreach ($comment->replies as $reply)
                                <div class="p-2 border-t flex gap-3">
                                    {{-- Reply User Image --}}
                                    <img src="{{ $reply->buyer->getImage() }}" alt="User Image" class="w-8 h-8 rounded-full">

                                    <div>
                                        <p class="text-sm font-semibold">{{ $reply->buyer->full_name ?? 'Unknown' }}</p>
                                        <p class="text-sm">{{ $reply->content }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        @empty
            <p>No comments yet.</p>
        @endforelse
    </div>
</x-filament-panels::page>
