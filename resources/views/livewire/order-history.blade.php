<div>
    <x-buyer-layout>
        <div class="bg-white  rounded-lg ">
            <div class="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8 lg:pb-24">
                <!-- Header -->
                <div class="max-w-xl">
                    <h1 class="text-2xl font-bold tracking-tight text-gray-900 sm:text-3xl">Order History</h1>
                    <p class="mt-2 text-sm text-gray-500">
                        Check the status of recent orders, manage returns, and download invoices.
                    </p>
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
                                    class="whitespace-nowrap border-b-2 px-1 py-4 text-sm font-medium focus:outline-none">
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
                        <!-- Search Icon -->
                        <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M10.5 18a7.5 7.5 0 1 1 0-15 7.5 7.5 0 0 1 0 15Z" />
                        </svg>
                    </div>
                    <input
                        type="text"
                        wire:model.live.debounce.500ms="search"
                        class="block w-full pl-10 rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm"
                        placeholder="Search orders by number or product name...">
                </div>

                <!-- Order List -->
                <div class="mt-16 space-y-12">
                    @forelse ($orders as $order)
                        <div class="border rounded-md bg-white shadow-md">
                            <div class="flex flex-col border-b px-4 py-6 space-y-6 sm:space-y-0 sm:flex-row sm:items-center sm:justify-between sm:space-x-6">
                                <dl class="grid grid-cols-1 gap-6 text-sm text-gray-600 sm:grid-cols-4 sm:gap-x-6">
                                    <div>
                                        <dt class="font-medium text-gray-900">Ordered Date</dt>
                                        <dd class="mt-1 text-gray-700">
                                            <time>{{ $order->order_date }}</time>
                                        </dd>
                                    </div>
                                    <div>
                                        <dt class="font-medium text-gray-900">Order number</dt>
                                        <dd class="mt-1 text-gray-700">{{ $order->order_number }}</dd>
                                    </div>
                                    <div>
                                        <dt class="font-medium text-gray-900">Total amount</dt>
                                        <dd class="mt-1 text-gray-700">{{ $order->formatted_total }}</dd>
                                    </div>
                                    
                                    <div>
                                        <dt class="font-medium text-gray-900">Order Received</dt>
                                        <dd class="mt-1 text-gray-700"> 
                                            
                                            {{$order->is_received ? 'Yes' : 'No' }}
                                            @if ($order->status === 'Completed' && $order->is_received === 0)
                                            
                                            {{ ($this->receiveOrderAction)(['record' => $order->id]) }} </dd>
                                            @endif
                                    </div>
                                
                                </dl>
                                <div class="mt-4 sm:mt-0">
                                    <span
                                        class="inline-flex items-center rounded-md px-2 py-1 text-xs font-medium
                                            @switch($order->status)
                                                @case('Completed') bg-green-100 text-green-700 @break
                                                @case('Cancelled') bg-red-100 text-red-700 @break
                                                @case('Shipped') bg-blue-100 text-blue-700 @break
                                                @case('Out for Delivery') bg-yellow-100 text-yellow-800 @break
                                                @case('Confirmed') bg-indigo-100 text-indigo-700 @break
                                                @case('Pending') bg-pink-100 text-pink-700 @break
                                                @default bg-gray-100 text-gray-600
                                            @endswitch">
                                        {{ $order->status }}
                                    </span>
                                </div>
                            </div>
                            <div class="px-4 py-4 rounded-md ">
                                <dl class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                   
                                    <div>
                                        <dt class="font-medium text-gray-900">Farm Name</dt>
                                        <dd class="text-sm text-gray-700">
                                            {{ $order->farmer->farm_name ?? 'N/A' }}
                                        </dd>
                                        <div class="text-xs text-gray-500">
                                            {{$order->farmer->location ??'N/A'}}
                                        </div>
                                    </div>
                                </dl>
                            </div>

                               <!-- Additional Ord  er Details -->
                               <div class="px-4 py-4 rounded-md">
                                <dl class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <div>
                                        <dt class="font-medium text-gray-900">Shipped Date</dt>
                                        <dd class="text-sm text-gray-700">
                                            {{ $order->shipped_date ? \Carbon\Carbon::parse($order->shipped_date)->format('F j, Y g:i a') : 'N/A' }}
                                        </dd>
                                    </div>
                                    <div>
                                        <dt class="font-medium text-gray-900">Expected Delivery Date</dt>
                                        <dd class="text-sm text-gray-700">
                                            {{ $order->delivery_date ? \Carbon\Carbon::parse($order->delivery_date)->format('F j, Y g:i a') : 'N/A' }}
                                        </dd>
                                    </div>
                                    <div>
                                        <dt class="font-medium text-gray-900">Received</dt>
                                        <dd class="text-sm text-gray-700">
                                            {{ $order->is_received ? 'Yes' : 'No' }}
                                        </dd>
                                    </div>
                                    @if (in_array($order->status, ['Cancelled', 'Returned']))
                                    <div class="sm:col-span-2">
                                        <dt class="font-medium text-gray-900">Remarks</dt>
                                        <dd class="text-sm text-gray-700">
                                            <div class=" rounded-md border-0.5 border-gray-100 text-gray-500 text-sm">
                                                {{ $order->remarks ?? 'No remarks available' }}
                                            </div>
                                        </dd>
                                    </div>
                                @endif
                                
                                </dl>
                            </div>
                            
                              
                            <!-- Order Items -->
                            <table class="w-full text-sm text-gray-700">
                                <thead class="text-left bg-gray-100 border-b text-gray-600">
                                    <tr>
                                        <th scope="col" class="py-3 px-4 font-medium">Product</th>
                                        <th scope="col" class="hidden py-3 px-4 font-medium sm:table-cell text-right">Price</th>
                                        <th scope="col" class="hidden py-3 px-4 font-medium sm:table-cell text-center">Quantity</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200">
                                    @foreach ($order->items as $item)
                                        <tr>
                                            <td class="py-4 px-4">
                                                <div class="flex items-center space-x-4">
                                                    <img src="{{ $item->product->getImage() }}" alt="{{ $item->product_name }}" class="h-16 w-16 rounded-lg object-cover">
                                                    <div>
                                                        <div class="font-medium text-gray-900">{{ $item->product_name }}</div>
                                                        <div class="mt-1 text-sm text-gray-600">{{ Str::limit($item->product_description, 50) }}</div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="hidden py-4 px-4 sm:table-cell text-right">₱{{ number_format($item->product_price, 2) }}</td>
                                            <td class="hidden py-4 px-4 sm:table-cell text-center">{{ $item->quantity }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot class="bg-gray-50">
                                    <tr>                                    
                                            <td class="py-4 px-4 font-medium text-gray-900 text-left"  >Total</td>
                                            <td class="py-4 px-4 font-medium text-gray-900 text-right"  >  ₱{{ number_format($order->items->sum(fn($item) => $item->product_price * $item->quantity), 2) }}</td>

                                        <td class="py-4 px-4 text-right font-bold text-gray-900" >
                                          
                                        </td>
                                       
                                    </tr>
                                </tfoot>
                            </table>
                            
                            @if (in_array($order->status, ['Out for Delivery', 'Completed']))
                            <div class="px-4 py-6 border-t mt-4">
                                <h3 class="text-lg font-semibold text-gray-900">Order Tracking</h3>
                                <ul role="list" class="mt-2 space-y-6">
                                    @forelse ($order->getLatestMovements() as $index => $movement)
                                        <li class="relative flex gap-x-4 {{ $index === 0 && $order->status === 'Out for Delivery' ? 'bg-primary-50 dark:bg-primary-50 animate-pulse' : '' }}">
                                            <!-- Vertical Line -->
                                            <div class="absolute -bottom-6 left-0 top-0 flex w-6 justify-center">
                                                <div class="w-px {{ $index === 0 && $order->status === 'Out for Delivery' ? 'bg-primary-500 dark:bg-primary-400' : 'bg-gray-200 dark:bg-gray-700' }}"></div>
                                            </div>
                                            
                                            <!-- Timeline Icon -->
                                            <div class="relative flex size-6 flex-none items-center justify-center bg-white">
                                                <div class="size-1.5 rounded-full {{ $index === 0 && $order->status === 'Out for Delivery' ? 'bg-primary-500 ring-primary-600 dark:bg-primary-400 dark:ring-primary-500' : 'bg-gray-100 ring-gray-300' }}"></div>
                                            </div>
                                            
                                            <!-- Movement Details -->
                                            <div class="flex-auto">
                                                <p class="py-0.5 text-sm {{ $index === 0 && $order->status === 'Out for Delivery' ? 'text-primary-600 dark:text-primary-400 font-semibold' : 'text-gray-700 dark:text-gray-200' }}">
                                                    <span class="font-medium text-gray-900 dark:text-gray-100">Current Location:</span> {{ $movement->current_location }}
                                                </p>
                                                <p class="py-0.5 text-sm {{ $index === 0 && $order->status === 'Out for Delivery' ? 'text-primary-600 dark:text-primary-400 font-semibold' : 'text-gray-700 dark:text-gray-200' }}">
                                                    <span class="font-medium text-gray-900 dark:text-gray-100">Destination:</span> {{ $movement->destination }}
                                                </p>
                                                <time datetime="{{ $movement->updated_at->toIso8601String() }}" class="py-0.5 text-xs {{ $index === 0 && $order->status === 'Out for Delivery' ? 'text-primary-500 dark:text-primary-400' : 'text-gray-500 dark:text-gray-400' }}">
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
                                </ul>
                            </div>
                            
                        @endif
                        </div>

                       
                        
                     
                         

                    @empty
                        <div class="text-center text-gray-500">No orders found.</div>
                    @endforelse
                </div>

                <!-- Pagination -->
                <div class="mt-8">
                    {{ $orders->links() }}
                </div>
            </div>
        </div>
    </x-buyer-layout>
    <x-filament-actions::modals />
</div>
