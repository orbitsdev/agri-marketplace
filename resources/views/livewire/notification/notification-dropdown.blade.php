<div class="relative" x-data="{ open: false }">
    <!-- Notification Bell Button -->
    <!-- Notification Bell Button -->
        <button @click="open = !open; if(open) { Livewire.dispatch('markAllAsRead'); }" class="relative p-2 text-white">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
            class="size-6 text-gray-400">
            <path stroke-linecap="round" stroke-linejoin="round"
                d="M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31A8.967 8.967 0 0 1 18 9.75V9A6 6 0 0 0 6 9v.75a8.967 8.967 0 0 1-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 0 1-5.714 0m5.714 0a3 3 0 1 1-5.714 0M3.124 7.5A8.969 8.969 0 0 1 5.292 3m13.416 0a8.969 8.969 0 0 1 2.168 4.5" />
        </svg>


        <!-- Notification Badge -->
        @if ($unreadCount > 0)
            <span
                class="absolute top-0 right-0 flex items-center justify-center w-5 h-5 text-xs font-bold text-white bg-red-500 rounded-full">
                {{ $unreadCount }}
            </span>
        @endif
    </button>


    <!-- Notification Dropdown -->
    <div x-show="open" x-cloak @click.away="open = false"
        class="absolute right-0 top-10 w-80 bg-white shadow-2xl rounded-lg overflow-hidden z-50">
        <!-- Notification Header -->
        <div class="px-4 py-2 bg-white border-b flex justify-between items-center">
            <h3 class="text-lg text-primary-600 font-semibold">Notifications</h3>

            <!-- Hide Button If All Notifications Are Read -->
            @if ($unreadCount > 0)
                <button wire:click="markAllAsRead"
                    class="text-sm font-medium text-white bg-primary-600 px-3 py-1.5 rounded-md shadow-md transition duration-200
                           hover:bg-primary-700 hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-primary-500">
                    ✅ Mark all as read
                </button>
            @endif
        </div>



        <div class="max-h-96 overflow-y-auto">
            @forelse ($notifications as $notification)
                @php
                    $isUnread = is_null($notification->read_at);
                    $textColor = $isUnread ? 'text-primary-900 font-bold' : 'text-gray-600';
                    $messageColor = $isUnread ? 'text-green-600' : 'text-gray-500';
                    $iconColor = $isUnread ? 'bg-primary-600 text-white' : 'bg-gray-400 text-gray-200';
                    $url = $notification->data['url'] ?? null;
                @endphp

                <!-- Wrap with <a> tag if URL exists -->
                @if ($url)
                    <a href="{{ $url }}" class="block" wire:click="markAsRead('{{ $notification->id }}')">
                    @else
                        <div wire:click="markAsRead('{{ $notification->id }}')">
                @endif

                <div
                    class="flex items-start p-3 border-b hover:bg-primary-50 transition duration-150 ease-in-out cursor-pointer">
                    <!-- User Initials Instead of Icon -->
                    <div
                        class="flex items-center justify-center w-10 h-10 text-sm font-bold {{ $iconColor }} rounded-full shadow">
                        {{ strtoupper(substr($notification->data['profile_image'] ?? 'UA', 0, 1)) }}{{ strtoupper(substr($notification->data['user'] ?? 'UA', 1, 1)) }}
                    </div>

                    <!-- Notification Content -->
                    <div class="ml-4 flex-1">
                        <p class="text-sm {{ $textColor }}">
                            {{ $notification->data['title'] ?? 'New Notification' }}
                        </p>

                        <p class="text-xs {{ $messageColor }} line-clamp-2">
                            {{ $notification->data['message'] ?? 'You have a new notification.' }}
                        </p>

                        <p class="text-xs text-gray-400 mt-1">
                            {{ \Carbon\Carbon::parse($notification->created_at)->diffForHumans() }}
                        </p>
                    </div>

                    <!-- Read Status Indicator -->
                    @if ($isUnread)
                        <div class="w-2 h-2 bg-red-500 rounded-full self-center ml-2"></div>
                    @endif
                </div>

                @if ($url)
                    </a>
                @else
        </div>
        @endif
    @empty
        @endforelse
    </div>


</div>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        if ({{ auth()->check() ? 'true' : 'false' }}) {
            console.log("📡 Subscribing to: notifications.{{ auth()->id() }}");

            window.Echo.channel(`notifications.{{ auth()->id() }}`)
                .listen('.message.created', (notification) => {
                    console.log("🔔 New Notification:", notification);
                    Livewire.dispatch('refreshNotifications'); // Refresh Livewire dropdown
                });
        } else {
            console.warn("⚠️ User is not authenticated, skipping WebSocket subscription.");
        }
    });
</script>

</div>
