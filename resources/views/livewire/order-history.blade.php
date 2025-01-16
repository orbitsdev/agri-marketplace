<div>
    <x-buyer-layout>
        <div class="bg-white border rounded-lg">
            <div class="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8 lg:pb-24">
                <div class="max-w-xl">
                    <h1 class="text-2xl font-bold tracking-tight text-gray-900 sm:text-3xl">Order History</h1>
                    <p class="mt-2 text-sm text-gray-500">Check the status of recent orders, manage returns, and download invoices.</p>
                </div>

                <!-- Tabs for Order Status -->
                <div x-data="{ activeTab: '{{ $status }}' }" class="mt-8">
                    <div class="border-b border-gray-200">
                        <nav class="-mb-px flex space-x-8" aria-label="Tabs">
                            @php
                                $tabs = [
                                    '' => 'All Orders',
                                    'Pending' => 'Pending',
                                    'Confirmed' => 'Confirmed',
                                    'Shipped' => 'Shipped',
                                    'Out for Delivery' => 'Out for Delivery',
                                    'Completed' => 'Completed',
                                    'Cancelled' => 'Cancelled',
                                ];
                            @endphp

                            @foreach ($tabs as $statusKey => $label)
                                <button
                                    x-on:click="activeTab = '{{ $statusKey }}'; $wire.set('status', '{{ $statusKey }}')"
                                    :class="{
                                        'border-primary-500 text-primary-600': activeTab === '{{ $statusKey }}',
                                        'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700': activeTab !== '{{ $statusKey }}'
                                    }"
                                    class="flex whitespace-nowrap border-b-2 px-1 py-4 text-sm font-medium">
                                    {{ $label }}
                                    @if ($statusKey !== '' && isset($statusCounts[$statusKey]))
                                        <span class="ml-3 hidden rounded-full bg-gray-100 px-2.5 py-0.5 text-xs font-medium text-gray-900 md:inline-block">
                                            {{ $statusCounts[$statusKey] }}
                                        </span>
                                    @endif
                                </button>
                            @endforeach
                        </nav>
                    </div>
                </div>



              <!-- Search Box -->
<div class="mt-4 relative">
    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
        <!-- Icon -->
        <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M10.5 18a7.5 7.5 0 1 1 0-15 7.5 7.5 0 0 1 0 15Z" />
        </svg>
    </div>
    <input
        type="text"
        wire:model.live.debounce.500ms="search"
        class="block w-full pl-10 rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm"
        placeholder="Search orders by number or product name..."
    />
</div>


                <!-- Order List -->
                <div class="mt-16 space-y-20">
                    @forelse ($orders as $order)
                        <div class="mb-6 border rounded-md bg-white shadow-md">
                            <div class="border-b px-4 py-6 sm:flex sm:items-center sm:justify-between sm:space-x-6 sm:px-6 lg:space-x-8">
                                <dl class="flex-auto space-y-6 divide-y divide-gray-200 text-sm text-gray-600 sm:grid sm:grid-cols-3 sm:gap-x-6 sm:space-y-0 sm:divide-y-0 lg:w-1/2 lg:flex-none lg:gap-x-8">
                                    <div class="flex justify-between sm:block">
                                        <dt class="font-medium text-gray-900">Date placed</dt>
                                        <dd class="sm:mt-1 text-gray-700">
                                            <time>{{ $order->order_date }}</time>
                                        </dd>
                                    </div>
                                    <div class="flex justify-between pt-6 sm:block sm:pt-0">
                                        <dt class="font-medium text-gray-900">Order number</dt>
                                        <dd class="sm:mt-1 text-gray-700">{{ $order->order_number }}</dd>
                                    </div>
                                    <div class="flex justify-between pt-6 font-medium sm:block sm:pt-0">
                                        <dt>Total amount</dt>
                                        <dd class="sm:mt-1 text-gray-700">{{ $order->formatted_total }}</dd>
                                    </div>
                                </dl>
                                <div>
                                    <span
                                        class="inline-flex items-center rounded-md px-1.5 py-0.5 text-xs font-medium
                                            @switch($order->status)
                                                @case('Completed') bg-green-100 text-green-700 @break
                                                @case('Cancelled') bg-red-100 text-red-700 @break
                                                @case('Shipped') bg-blue-100 text-blue-700 @break
                                                @case('Out for Delivery') bg-yellow-100 text-yellow-800 @break
                                                @case('Confirmed') bg-indigo-100 text-indigo-700 @break
                                                {{-- @case('Returned') bg-purple-100 text-purple-700 @break --}}
                                                @case('Pending') bg-pink-100 text-pink-700 @break
                                                @default bg-gray-100 text-gray-600
                                            @endswitch
                                    ">
                                        {{ $order->status }}
                                    </span>
                                </div>
                            </div>

                            <table class="w-full text-gray-700">
                                <thead class="text-left text-sm text-gray-600 bg-gray-100 border-b">
                                    <tr>
                                        <th scope="col" class="py-3 px-3 font-medium sm:w-2/5 lg:w-1/3">Product</th>
                                        <th scope="col" class="hidden py-1 px-4 font-medium sm:table-cell">Price</th>
                                        <th scope="col" class="hidden py-1 px-4 font-medium sm:table-cell">Quantity</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200">
                                    @foreach ($order->items as $item)
                                        <tr>
                                            <td class="py-6 px-4">
                                                <div class="flex items-center">
                                                    <img src="{{ $item->product->getImage() }}" alt="{{ $item->product_name }}" class="mr-6 h-16 w-16 rounded-lg object-cover">
                                                    <div>
                                                        <div class="font-medium text-gray-900">{{ $item->product_name }}</div>
                                                        <div class="mt-1 text-sm text-gray-600">{{ Str::limit($item->product_description, 50) }}</div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="hidden py-6 px-4 sm:table-cell">{{ number_format($item->product_price, 2) }}</td>
                                            <td class="hidden py-6 px-4 sm:table-cell">{{ $item->quantity }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @empty
                        <div class="text-center text-gray-500">No orders found.</div>
                    @endforelse
                </div>

                <!-- Pagination -->
                <div class="mt-6">
                    {{ $orders->links() }}
                </div>
            </div>
        </div>
    </x-buyer-layout>
</div>
